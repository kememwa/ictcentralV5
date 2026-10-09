<?php

namespace App\Livewire;

use App\Models\MpesaTransaction;
use Carbon\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.dashboard')]
class MpesaAnalytics extends Component
{
    use WithPagination;

    public string $month;

    public function mount(): void
    {
        $this->month = now()->format('Y-m');
    }

    public function updatedMonth(): void
    {
        $this->resetPage();
        $this->dispatch('trend-updated', trend: $this->trend);
    }

    private function bounds(?Carbon $start = null): array
    {
        $start ??= Carbon::createFromFormat('Y-m-d', $this->month . '-01')->startOfMonth();

        return [$start->copy()->startOfMonth(), $start->copy()->endOfMonth()];
    }

    private function summary(Carbon $start, Carbon $end): array
    {
        $rows = MpesaTransaction::whereBetween('created_at', [$start, $end])
            ->selectRaw('UPPER(status) as status, COUNT(*) as count, COALESCE(SUM(amount),0) as total')
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        $count = fn ($s) => (int) ($rows[$s]->count ?? 0);

        return [
            'collected' => (float) ($rows['SUCCESS']->total ?? 0),
            'success'   => $count('SUCCESS'),
            'failed'    => $count('FAILED'),
            'cancelled' => $count('CANCELLED'),
            'timeout'   => $count('TIMEOUT'),
            'pending'   => $count('PENDING'),
            'total'     => (int) $rows->sum('count'),
        ];
    }

    private function change(float|int $current, float|int $previous): ?float
    {
        return $previous > 0 ? round((($current - $previous) / $previous) * 100, 1) : null;
    }

    #[Computed]
    public function stats(): array
    {
        [$start, $end] = $this->bounds();
        [$pStart, $pEnd] = $this->bounds($start->copy()->subMonthNoOverflow());

        $cur  = $this->summary($start, $end);
        $prev = $this->summary($pStart, $pEnd);

        $resolved = $cur['success'] + $cur['failed'] + $cur['cancelled'] + $cur['timeout'];

        return [
            'cur' => $cur,
            'change' => [
                'collected' => $this->change($cur['collected'], $prev['collected']),
                'success'   => $this->change($cur['success'], $prev['success']),
                'failed'    => $this->change($cur['failed'], $prev['failed']),
                'total'     => $this->change($cur['total'], $prev['total']),
            ],
            'resolved'     => $resolved,
            'success_rate' => $resolved ? round($cur['success'] / $resolved * 100, 1) : 0,
            'range'        => $start->format('j M Y') . ' – ' . $end->format('j M Y'),
        ];
    }

    #[Computed]
    public function outcomes(): array
    {
        $c = $this->stats['cur'];
        $total = max($c['total'], 1);

        $items = [
            ['label' => 'Successful', 'count' => $c['success'],   'color' => '#10b981'],
            ['label' => 'Failed',     'count' => $c['failed'],    'color' => '#ef4444'],
            ['label' => 'Cancelled',  'count' => $c['cancelled'], 'color' => '#f97316'],
            ['label' => 'Timed Out',  'count' => $c['timeout'],   'color' => '#facc15'],
            ['label' => 'Pending',    'count' => $c['pending'],   'color' => '#94a3b8'],
        ];

        $offset = 0;
        $stops = [];
        foreach ($items as &$i) {
            $i['pct'] = round($i['count'] / $total * 100, 1);
            $end = $offset + ($i['count'] / $total * 100);
            $stops[] = "{$i['color']} {$offset}% {$end}%";
            $offset = $end;
        }

        return [
            'items'    => $items,
            'gradient' => $c['total'] ? implode(', ', $stops) : '#e2e8f0 0% 100%',
        ];
    }

    #[Computed]
    public function trend(): array
    {
        [$start, $end] = $this->bounds();

        $rows = MpesaTransaction::whereBetween('created_at', [$start, $end])
            ->whereRaw("UPPER(status) = 'SUCCESS'")
            ->selectRaw('DATE(created_at) as day, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('day')
            ->get()
            ->keyBy('day');

        $labels = $amounts = $counts = [];
        for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
            $row = $rows[$d->toDateString()] ?? null;
            $labels[]  = $d->format('j M');
            $amounts[] = (float) ($row->total ?? 0);
            $counts[]  = (int) ($row->count ?? 0);
        }

        return compact('labels', 'amounts', 'counts');
    }

    #[Computed]
    public function failureReasons(): array
    {
        [$start, $end] = $this->bounds();

        $rows = MpesaTransaction::whereBetween('created_at', [$start, $end])
            ->whereNotNull('result_code')
            ->where('result_code', '!=', 0)
            ->selectRaw('result_description as reason, COUNT(*) as count')
            ->groupBy('result_description')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        $total = max($rows->sum('count'), 1);
        $colors = ['bg-red-500', 'bg-orange-400', 'bg-yellow-400', 'bg-blue-500', 'bg-slate-400'];

        return $rows->values()->map(fn ($r, $i) => [
            'reason' => $r->reason ?: 'Unknown',
            'count'  => $r->count,
            'pct'    => round($r->count / $total * 100, 1),
            'color'  => $colors[$i] ?? 'bg-slate-400',
        ])->all();
    }

    #[Computed]
    public function transactions()
    {
        [$start, $end] = $this->bounds();

        return MpesaTransaction::whereBetween('created_at', [$start, $end])
            ->latest()
            ->paginate(5);
    }

    public function render()
    {
        return view('livewire.mpesa-analytics');
    }
}