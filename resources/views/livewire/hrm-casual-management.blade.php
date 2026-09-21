<div class="space-y-6">

    {{-- ===== Page Header ===== --}}
    <x-page-header
        title="HRM Approval – Casual Requisitions"
        subtitle="Review HR-approved rates and approve or reject requisitions">
    </x-page-header>

    {{-- ===== Card ===== --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 p-5 border-b border-gray-100 dark:border-gray-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-violet-100 text-violet-600 dark:bg-violet-900/30 dark:text-violet-300 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-9a4 4 0 11-8 0 4 4 0 018 0zm6 4a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>

            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-violet-50 text-violet-700 dark:bg-violet-900/30 dark:text-violet-300">
                {{ $hrm_requisitions->total() ?? count($hrm_requisitions) }} awaiting
            </span>
        </div>

        {{-- ===== DESKTOP TABLE (lg and up) ===== --}}
        <div class="hidden lg:block overflow-x-auto">
            <table class="w-full text-xs">
                <thead class="bg-gray-50 text-left uppercase tracking-wider text-[11px] text-gray-500">
                    <tr>
                        <th class="px-3 py-2.5 whitespace-nowrap">Requested By</th>
                        <th class="px-3 py-2.5 whitespace-nowrap text-center">No. of Casuals</th>
                        <th class="px-3 py-2.5 whitespace-nowrap">Days</th>
                        <th class="px-3 py-2.5 w-2/5 min-w-[280px]">Reason</th>
                        <th class="px-3 py-2.5 whitespace-nowrap text-right">Daily Rate</th>
                        <th class="px-3 py-2.5 whitespace-nowrap text-right">NSSF Rate</th>
                        <th class="px-3 py-2.5 whitespace-nowrap text-right">SHA Rate</th>
                        <th class="px-3 py-2.5 whitespace-nowrap text-right">Estimated Total</th>
                        <th class="px-3 py-2.5 whitespace-nowrap text-right">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse($hrm_requisitions as $req)
                        <tr wire:key="hrm-req-{{ $req->id }}" class="align-top hover:bg-gray-50 transition">

                            {{-- Requested By --}}
                            <td class="px-3 py-3 whitespace-nowrap">
                                <p class="font-medium text-gray-900">
                                    {{ $req->requester->name }}
                                </p>
                            </td>

                            {{-- No of Casuals --}}
                            <td class="px-3 py-3 text-center text-gray-700 tabular-nums">
                                {{ $req->no_of_casuals }}
                            </td>

                            {{-- Duration --}}
                            <td class="px-3 py-3 whitespace-nowrap text-gray-500">
                                {{ $req->duration }}
                            </td>

                            {{-- Reason (wide, wraps, expandable) --}}
                            <td class="px-3 py-3">
                                @if($req->reason)
                                    <div x-data="{ open: false }" class="max-w-xl">
                                        <p
                                            class="text-[12px] leading-relaxed text-slate-600 break-words whitespace-normal"
                                            :class="open ? '' : 'line-clamp-3'"
                                        >{{ $req->reason }}</p>

                                        @if(mb_strlen($req->reason) > 140)
                                            <button
                                                type="button"
                                                @click="open = !open"
                                                class="mt-1 text-[11px] font-medium text-blue-600 hover:text-blue-700 hover:underline"
                                                x-text="open ? 'Show less' : 'Read more'"
                                            ></button>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-slate-400 text-[11px] italic">No reason provided</span>
                                @endif
                            </td>

                            {{-- Daily Rate --}}
                            <td class="px-3 py-3 whitespace-nowrap text-right text-gray-700 tabular-nums">
                                {{ number_format((float) $req->daily_rate, 2) }}
                            </td>

                            {{-- NSSF Rate --}}
                            <td class="px-3 py-3 whitespace-nowrap text-right text-gray-700 tabular-nums">
                                {{ number_format((float) $req->nssf_rate, 2) }}
                            </td>

                            {{-- SHA Rate --}}
                            <td class="px-3 py-3 whitespace-nowrap text-right text-gray-700 tabular-nums">
                                {{ number_format((float) $req->sha_rate, 2) }}
                            </td>

                            {{-- Estimated Total --}}
                            <td class="px-3 py-3 whitespace-nowrap text-right font-semibold text-gray-900 tabular-nums">
                                {{ number_format((float) $req->total_amount, 2) }}
                            </td>

                            {{-- Actions --}}
                            <td class="px-3 py-2.5 text-right">
                                <div class="flex items-center justify-end gap-1">

                                    {{-- Approve --}}
                                    <button
                                        type="button"
                                        wire:click="approveHrm({{ $req->id }})"
                                        wire:confirm="Approve this requisition?"
                                        title="Approve"
                                        aria-label="Approve requisition"
                                        class="p-1.5 rounded-md text-green-600 hover:bg-green-50 hover:text-green-700 transition"
                                    >
                                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M16.704 4.884a1 1 0 01.012 1.414l-8.25 8.25a1 1 0 01-1.414 0l-3.75-3.75a1 1 0 011.414-1.414l3.043 3.043 7.543-7.543a1 1 0 011.402 0z"
                                                clip-rule="evenodd"/>
                                        </svg>
                                    </button>

                                    {{-- Reject --}}
                                    <button
                                        type="button"
                                        wire:click="reject({{ $req->id }})"
                                        wire:confirm="Reject this requisition?"
                                        title="Reject"
                                        aria-label="Reject requisition"
                                        class="p-1.5 rounded-md text-red-600 hover:bg-red-50 hover:text-red-700 transition"
                                    >
                                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                clip-rule="evenodd"/>
                                        </svg>
                                    </button>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-16 text-center">
                                <p class="font-medium text-gray-700 dark:text-gray-200">No requisitions awaiting HRM approval</p>
                                <p class="text-xs text-gray-500 mt-1">All caught up 🎉</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ===== MOBILE / TABLET CARDS ===== --}}
        <div class="lg:hidden divide-y divide-gray-100 dark:divide-gray-800">
            @forelse($hrm_requisitions as $req)
                @php $total = $req->daily_rate * $req->no_of_casuals * $req->duration; @endphp
                <div class="p-4">
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-violet-500 to-fuchsia-600 text-white flex items-center justify-center font-semibold text-sm shrink-0">
                                {{ strtoupper(substr($req->requester->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="font-semibold text-gray-900 dark:text-white truncate">{{ $req->requester->name }}</p>
                                <span class="inline-flex items-center gap-1 mt-0.5 px-2 py-0.5 rounded-full text-[10px] font-medium bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300">
                                    <span class="w-1 h-1 bg-emerald-500 rounded-full"></span> HR Approved
                                </span>
                            </div>
                        </div>
                        <span class="shrink-0 inline-flex items-center justify-center min-w-[2rem] px-2 py-1 rounded-lg text-xs font-semibold bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300">
                            {{ $req->no_of_casuals }} casuals
                        </span>
                    </div>

                    <dl class="grid grid-cols-3 gap-2 text-xs mb-3">
                        <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-2">
                            <dt class="text-gray-500">Duration</dt>
                            <dd class="font-medium text-gray-900 dark:text-white mt-0.5">{{ $req->duration }}d</dd>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-2">
                            <dt class="text-gray-500">Daily</dt>
                            <dd class="font-medium text-gray-900 dark:text-white mt-0.5">{{ number_format($req->daily_rate) }}</dd>
                        </div>
                        <div class="bg-violet-50 dark:bg-violet-900/20 rounded-lg p-2">
                            <dt class="text-violet-600 dark:text-violet-300">Total KES</dt>
                            <dd class="font-bold text-violet-700 dark:text-violet-200 mt-0.5">{{ number_format($total) }}</dd>
                        </div>
                    </dl>

                    <div class="flex gap-2">
                        <button type="button"
                            wire:click="approve({{ $req->id }})"
                            wire:confirm="Approve this requisition (KES {{ number_format($total) }})?"
                            class="flex-1 inline-flex items-center justify-center gap-1 px-3 py-2 rounded-lg text-xs font-medium text-white bg-emerald-600 hover:bg-emerald-700 shadow-sm">
                            ✓ Approve
                        </button>
                        <button type="button"
                            wire:click="reject({{ $req->id }})"
                            wire:confirm="Reject this requisition?"
                            class="flex-1 inline-flex items-center justify-center gap-1 px-3 py-2 rounded-lg text-xs font-medium text-white bg-rose-600 hover:bg-rose-700 shadow-sm">
                            ✕ Reject
                        </button>
                    </div>
                </div>
            @empty
                <div class="py-16 text-center">
                    <p class="font-medium text-gray-700 dark:text-gray-200">No requisitions awaiting HRM approval</p>
                    <p class="text-xs text-gray-500 mt-1">All caught up 🎉</p>
                </div>
            @endforelse
        </div>

        @if(method_exists($hrm_requisitions, 'hasPages') && $hrm_requisitions->hasPages())
            <div class="px-4 sm:px-6 py-4 border-t border-gray-100 dark:border-gray-800">
                {{ $hrm_requisitions->links() }}
            </div>
        @endif
    </div>
</div>
