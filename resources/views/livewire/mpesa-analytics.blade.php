@php
    $s = $this->stats;
    $cur = $s['cur'];
    $ch = $s['change'];

    // $good = whether an increase is good news (false for failed)
    $badge = function (?float $v, bool $upIsGood = true) {
        if ($v === null) return ['—', 'text-slate-400'];
        $up = $v >= 0;
        $good = $up === $upIsGood;
        return [($up ? '↑ ' : '↓ ') . abs($v) . '%', $good ? 'text-emerald-600' : 'text-red-600'];
    };

    $cards = [
        ['label' => 'Amount Collected', 'value' => 'KES ' . number_format($cur['collected'], 2), 'sub' => 'From successful transactions', 'ch' => $badge($ch['collected']), 'style' => 'bg-emerald-50/60 border-emerald-200'],
        ['label' => 'Successful Transactions', 'value' => number_format($cur['success']), 'sub' => 'Confirmed payments', 'ch' => $badge($ch['success']), 'style' => 'bg-blue-50/60 border-blue-200'],
        ['label' => 'Failed Transactions', 'value' => number_format($cur['failed']), 'sub' => 'Unsuccessful payment attempts', 'ch' => $badge($ch['failed'], false), 'style' => 'bg-red-50/60 border-red-200'],
        ['label' => 'Total Transactions', 'value' => number_format($cur['total']), 'sub' => 'All attempts this month', 'ch' => $badge($ch['total']), 'style' => 'bg-slate-50 border-slate-200'],
    ];

    $statusStyle = fn ($st) => match (strtoupper($st)) {
        'SUCCESS'   => 'bg-emerald-100 text-emerald-700',
        'FAILED'    => 'bg-red-100 text-red-700',
        'CANCELLED' => 'bg-orange-100 text-orange-700',
        'TIMEOUT'   => 'bg-yellow-100 text-yellow-700',
        default     => 'bg-amber-100 text-amber-700',
    };
@endphp

<div class="space-y-6 p-6">

    {{-- Header --}}
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-emerald-600 text-white">
                <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" d="M6 20V10m6 10V4m6 16v-7" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-900">M-Pesa Analytics</h1>
                <p class="text-sm text-slate-500">Track M-Pesa collections, transaction success and failure rates</p>
            </div>
        </div>

        <div class="rounded-lg border border-slate-200 bg-white px-4 py-2 shadow-sm">
            <input type="month" wire:model.live="month" max="{{ now()->format('Y-m') }}"
                class="border-0 p-0 text-sm font-semibold text-slate-800 focus:ring-0">
            <p class="text-xs text-slate-400">{{ $s['range'] }}</p>
        </div>
    </div>

    {{-- KPI cards --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($cards as $card)
            <div class="rounded-xl border p-5 {{ $card['style'] }}">
                <p class="text-sm font-semibold text-slate-600">{{ $card['label'] }}</p>
                <p class="mt-2 text-2xl font-bold text-slate-900">{{ $card['value'] }}</p>
                <p class="text-sm text-slate-500">{{ $card['sub'] }}</p>
                <p class="mt-3 text-sm">
                    <span class="font-semibold {{ $card['ch'][1] }}">{{ $card['ch'][0] }}</span>
                    <span class="text-slate-500">vs. last month</span>
                </p>
            </div>
        @endforeach
    </div>

    {{-- Trend + Outcomes --}}
    <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm lg:col-span-2">
            <h2 class="mb-4 font-semibold text-slate-900">Daily Collection Trend</h2>
            <div class="relative h-72" wire:ignore>
                <canvas id="mpesaTrendChart"></canvas>
            </div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <h2 class="mb-4 font-semibold text-slate-900">Transaction Outcomes</h2>
            <div class="flex flex-wrap items-center justify-center gap-6">
                <div class="relative h-40 w-40 rounded-full"
                     style="background: conic-gradient({{ $this->outcomes['gradient'] }})">
                    <div class="absolute inset-5 flex flex-col items-center justify-center rounded-full bg-white">
                        <span class="text-2xl font-bold text-slate-900">{{ $cur['total'] }}</span>
                        <span class="text-xs text-slate-500">Total</span>
                    </div>
                </div>

                <ul class="space-y-2 text-sm">
                    @foreach ($this->outcomes['items'] as $o)
                        <li class="flex items-center gap-3">
                            <span class="h-2.5 w-2.5 rounded-full" style="background: {{ $o['color'] }}"></span>
                            <span class="w-20 text-slate-700">{{ $o['label'] }}</span>
                            <span class="w-8 text-right font-semibold text-slate-900">{{ $o['count'] }}</span>
                            <span class="w-12 text-right text-slate-500">{{ $o['pct'] }}%</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    {{-- Failure reasons + Insights --}}
    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <h2 class="mb-4 font-semibold text-slate-900">Failure Reasons</h2>
            <div class="space-y-4">
                @forelse ($this->failureReasons as $r)
                    <div class="flex items-center gap-3 text-sm">
                        <span class="w-40 truncate text-slate-700" title="{{ $r['reason'] }}">{{ $r['reason'] }}</span>
                        <span class="w-6 font-semibold text-slate-900">{{ $r['count'] }}</span>
                        <span class="w-12 text-slate-500">{{ $r['pct'] }}%</span>
                        <div class="h-2 flex-1 rounded-full bg-slate-100">
                            <div class="h-2 rounded-full {{ $r['color'] }}" style="width: {{ $r['pct'] }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-400">No failures recorded for this period.</p>
                @endforelse
            </div>
        </div>

        <div class="rounded-xl border border-emerald-200 bg-emerald-50/60 p-5">
            <h2 class="mb-4 font-semibold text-slate-900">Key Insights</h2>
            <ul class="space-y-3 text-sm text-slate-700">
                @if ($ch['collected'] !== null)
                    <li>✅ Collections are {{ $ch['collected'] >= 0 ? 'up' : 'down' }} by {{ abs($ch['collected']) }}% compared to last month.</li>
                @endif
                <li>✅ Success rate is {{ $s['success_rate'] }}% ({{ $cur['success'] }} / {{ $s['resolved'] }} resolved transactions).</li>
                @if ($ch['failed'] !== null)
                    <li>✅ Failed transactions {{ $ch['failed'] <= 0 ? 'decreased' : 'increased' }} by {{ abs($ch['failed']) }}% compared to last month.</li>
                @endif
                @if ($top = $this->failureReasons[0] ?? null)
                    <li>✅ "{{ $top['reason'] }}" is the top failure reason ({{ $top['pct'] }}%).</li>
                @endif
            </ul>
        </div>
    </div>

    

    @script
    <script>
        let chart;

        const draw = (t) => {
            chart?.destroy();
            chart = new Chart(document.getElementById('mpesaTrendChart'), {
                data: {
                    labels: t.labels,
                    datasets: [
                        {
                            type: 'line', label: 'Amount Collected (KES)', data: t.amounts,
                            borderColor: '#2563eb', backgroundColor: '#2563eb',
                            tension: 0.3, pointRadius: 3, yAxisID: 'y', order: 1,
                        },
                        {
                            type: 'bar', label: 'Transaction Count', data: t.counts,
                            backgroundColor: 'rgba(147,197,253,0.7)', yAxisID: 'y1', order: 2,
                        },
                    ],
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: { legend: { position: 'top', align: 'end' } },
                    scales: {
                        y:  { beginAtZero: true, ticks: { callback: v => 'KES ' + v.toLocaleString() } },
                        y1: { beginAtZero: true, position: 'right', grid: { drawOnChartArea: false }, ticks: { precision: 0 } },
                        x:  { grid: { display: false } },
                    },
                },
            });
        };

        draw(@js($this->trend));
        $wire.on('trend-updated', ({ trend }) => draw(trend));
    </script>
    @endscript
</div>