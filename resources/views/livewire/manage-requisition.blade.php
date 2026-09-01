<div class="space-y-6">


   <x-page-header
        title="HOD Approval – Casual Requisitions"
        subtitle="Review HR-approved rates and approve or reject requisitions">

        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300 ring-1 ring-amber-200 dark:ring-amber-800">
            <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse"></span>
            {{ count($requisitions) }} Pending
        </span>
    </x-page-header>

    {{-- ===== Card ===== --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">

        {{-- Card header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 p-5 border-b border-gray-100 dark:border-gray-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-300 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">Requisitions Awaiting Your Approval</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Review and approve casual worker requests</p>
                </div>
            </div>
        </div>

        {{-- ===== DESKTOP TABLE ===== --}}
        <div class="hidden lg:block overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800/50 text-left text-xs uppercase tracking-wider text-gray-500">
                    <tr>
                        <th class="px-4 py-3">Requested By</th>
                        <th class="px-4 py-3 text-center"># Casuals</th>
                        <th class="px-4 py-3">Reason</th>
                        <th class="px-4 py-3">Duration</th>
                        <th class="px-4 py-3">Start</th>
                        <th class="px-4 py-3">End</th>
                        <th class="px-4 py-3 hidden xl:table-cell">Submitted</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($requisitions as $requisition)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40 transition">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center font-semibold text-xs shrink-0">
                                        {{ strtoupper(substr($requisition->requester->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-medium text-gray-900 dark:text-white truncate">{{ $requisition->requester->name }}</p>
                                        <p class="text-xs text-gray-500 truncate">{{ $requisition->requester->email ?? '' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center justify-center min-w-[2rem] px-2 py-1 rounded-lg text-xs font-semibold bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300">
                                    {{ $requisition->no_of_casuals }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300 max-w-[260px]">
                                <p class="line-clamp-2" title="{{ $requisition->reason }}">{{ $requisition->reason }}</p>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-gray-700 dark:text-gray-300">{{ $requisition->duration }} days</td>
                            <td class="px-4 py-3 whitespace-nowrap text-gray-700 dark:text-gray-300">{{ \Carbon\Carbon::parse($requisition->start_date)->format('M d, Y') }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-gray-700 dark:text-gray-300">{{ \Carbon\Carbon::parse($requisition->end_date)->format('M d, Y') }}</td>
                            <td class="px-4 py-3 hidden xl:table-cell whitespace-nowrap text-xs text-gray-500">{{ $requisition->created_at->diffForHumans() }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button type="button"
                                        wire:click="approveRequest({{ $requisition->id }})"
                                        wire:confirm="Approve this requisition?"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-md text-xs font-medium text-white bg-emerald-600 hover:bg-emerald-700 shadow-sm transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        Approve
                                    </button>
                                    <button type="button"
                                        wire:click="reject({{ $requisition->id }})"
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
                            <td colspan="8" class="py-16">
                                @include('partials.requisitions-empty')
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ===== MOBILE / TABLET CARDS ===== --}}
        <div class="lg:hidden divide-y divide-gray-100 dark:divide-gray-800">
            @forelse($requisitions as $requisition)
                <div class="p-4">
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center font-semibold text-sm shrink-0">
                                {{ strtoupper(substr($requisition->requester->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="font-semibold text-gray-900 dark:text-white truncate">{{ $requisition->requester->name }}</p>
                                <p class="text-xs text-gray-500">{{ $requisition->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <span class="shrink-0 inline-flex items-center justify-center min-w-[2rem] px-2 py-1 rounded-lg text-xs font-semibold bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300">
                            {{ $requisition->no_of_casuals }} casuals
                        </span>
                    </div>

                    <p class="text-sm text-gray-700 dark:text-gray-300 mb-3 line-clamp-3">{{ $requisition->reason }}</p>

                    <dl class="grid grid-cols-3 gap-2 text-xs mb-3">
                        <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-2">
                            <dt class="text-gray-500">Duration</dt>
                            <dd class="font-medium text-gray-900 dark:text-white mt-0.5">{{ $requisition->duration }}d</dd>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-2">
                            <dt class="text-gray-500">Start</dt>
                            <dd class="font-medium text-gray-900 dark:text-white mt-0.5">{{ \Carbon\Carbon::parse($requisition->start_date)->format('M d') }}</dd>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-2">
                            <dt class="text-gray-500">End</dt>
                            <dd class="font-medium text-gray-900 dark:text-white mt-0.5">{{ \Carbon\Carbon::parse($requisition->end_date)->format('M d') }}</dd>
                        </div>
                    </dl>

                    <div class="flex gap-2">
                        <button type="button"
                            wire:click="approve({{ $requisition->id }})"
                            wire:confirm="Approve this requisition?"
                            class="flex-1 inline-flex items-center justify-center gap-1 px-3 py-2 rounded-lg text-xs font-medium text-white bg-emerald-600 hover:bg-emerald-700 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            Approve
                        </button>
                        <button type="button"
                            wire:click="reject({{ $requisition->id }})"
                            wire:confirm="Reject this requisition?"
                            class="flex-1 inline-flex items-center justify-center gap-1 px-3 py-2 rounded-lg text-xs font-medium text-white bg-rose-600 hover:bg-rose-700 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                            Reject
                        </button>
                    </div>
                </div>
            @empty
                @include('partials.requisitions-empty')
            @endforelse
        </div>

        @if($requisitions->hasPages())
            <div class="px-4 sm:px-6 py-4 border-t border-gray-100 dark:border-gray-800">
                {{ $requisitions->links() }}
            </div>
        @endif
    </div>
</div>
