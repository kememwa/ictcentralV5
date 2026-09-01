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

        {{-- ===== DESKTOP TABLE ===== --}}
        <div class="hidden lg:block overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800/50 text-left text-xs uppercase tracking-wider text-gray-500">
                    <tr>
                        <th class="px-4 py-3">Requested By</th>
                        <th class="px-4 py-3 text-center">Casuals</th>
                        <th class="px-4 py-3">Duration</th>
                        <th class="px-4 py-3 text-right">Daily Rate</th>
                        <th class="px-4 py-3 text-right">Estimated Total</th>
                        <th class="px-4 py-3">HR Status</th>
                        <th class="px-4 py-3 text-right">HRM Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($hrm_requisitions as $req)
                        @php $total = $req->daily_rate * $req->no_of_casuals * $req->duration; @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40 transition">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-violet-500 to-fuchsia-600 text-white flex items-center justify-center font-semibold text-xs shrink-0">
                                        {{ strtoupper(substr($req->requester->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-medium text-gray-900 dark:text-white truncate">{{ $req->requester->name }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center justify-center min-w-[2rem] px-2 py-1 rounded-lg text-xs font-semibold bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300">
                                    {{ $req->no_of_casuals }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-gray-700 dark:text-gray-300">{{ $req->duration }} days</td>
                            <td class="px-4 py-3 whitespace-nowrap text-right text-gray-700 dark:text-gray-300">KES {{ number_format($req->daily_rate, 2) }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-right">
                                <span class="font-semibold text-gray-900 dark:text-white">KES {{ number_format($total) }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-300 dark:ring-emerald-800">
                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> HR Approved
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button type="button"
                                        wire:click="approveHrm({{ $req->id }})"
                                        wire:confirm="Approve this requisition (KES {{ number_format($total) }})?"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-md text-xs font-medium text-white bg-emerald-600 hover:bg-emerald-700 shadow-sm transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        Approve
                                    </button>
                                    <button type="button"
                                        wire:click="reject({{ $req->id }})"
                                        wire:confirm="Reject this requisition?"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-md text-xs font-medium text-white bg-rose-600 hover:bg-rose-700 shadow-sm transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                        Reject
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-16 text-center">
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
