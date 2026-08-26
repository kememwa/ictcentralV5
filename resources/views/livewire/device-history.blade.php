<div class="space-y-6">

    <x-page-header
        title="Device History"
        subtitle="Audit log of every device assignment, return, and change.">
        <div class="flex items-center gap-2">
            <a href="#"
               class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50">
                <svg class="h-4 w-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4"/>
                </svg>
                Excel
            </a>
            <a href="#"
               class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50">
                <svg class="h-4 w-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4"/>
                </svg>
                CSV
            </a>
        </div>
    </x-page-header>

    <x-data-card title="Activity Log" subtitle="Chronological record of device actions.">
    <div class="overflow-x-auto">
        <div class="flex flex-col gap-3 border-b border-slate-100 px-4 py-3 sm:flex-row sm:items-center sm:justify-between">
            {{-- Search --}}
            <div class="relative flex-1 max-w-sm">
                <svg class="pointer-events-none absolute left-3 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-slate-400"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-4.35-4.35M11 19a8 8 0 110-16 8 8 0 010 16z"/>
                </svg>
                <input type="text" wire:model.live.debounce.300ms="search"
                    placeholder="Search..."
                    class="w-full rounded-lg border border-slate-200 bg-white py-1.5 pl-8 pr-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100"/>
            </div>

            {{-- Action filter --}}
            <div class="flex items-center gap-2">
                <select wire:model.live="actionFilter"
                    class="rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 text-sm text-slate-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                    <option value="">All actions</option>
                    <option value="assign">Assigned</option>
                    <option value="unassign">Returned</option>
                    <option value="delete">Delete</option>
                </select>

                @if($search || $actionFilter)
                    <button wire:click="$set('search', ''); $set('actionFilter', '')"
                        class="inline-flex items-center gap-1 rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 text-xs font-medium text-slate-600 hover:bg-slate-50">
                        Clear
                    </button>
                @endif
            </div>
        </div>

        {{-- Compact Table with Visible Reason --}}
        <table class="w-full text-sm">
            <thead class="bg-slate-50">
                <tr class="text-left text-[10px] font-semibold uppercase tracking-wider text-slate-500">
                    <th class="px-3 py-2 w-[90px]">Date</th>
                    <th class="px-3 py-2 w-[130px]">Device</th>
                    <th class="px-3 py-2 w-[85px]">Action</th>
                    <th class="px-3 py-2 w-[110px]">User</th>
                    <th class="px-3 py-2 w-[110px]">Performed By</th>
                    <th class="px-3 py-2 min-w-[150px]">Reason</th>
                    <th class="px-3 py-2">Comment</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($logs as $log)
                    <tr class="hover:bg-slate-50/60 transition text-xs">
                        {{-- Date --}}
                        <td class="px-3 py-2 whitespace-nowrap font-mono text-[11px] text-slate-600">
                            {{\Carbon\Carbon::parse($log->action_date)->format('d M Y')}}
                        </td>

                        {{-- Device --}}
                        <td class="px-3 py-2">
                            <p class="font-medium text-slate-900 truncate max-w-[120px]" title="{{ $log->device->name ?? 'N/A' }}">
                                {{ $log->device->name ?? 'N/A' }}
                            </p>
                        </td>

                        {{-- Action --}}
                        <td class="px-3 py-2">
                            @if($log->action_type === 'assign')
                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-0.5 text-[10px] font-medium text-emerald-700">
                                    <span class="h-1 w-1 rounded-full bg-emerald-500"></span> Assign
                                </span>
                            @elseif($log->action_type === 'delete')
                                <span class="inline-flex items-center gap-1 rounded-full bg-rose-50 px-2 py-0.5 text-[10px] font-medium text-rose-700">
                                    <span class="h-1 w-1 rounded-full bg-rose-500"></span> Delete
                                </span>
                            @elseif($log->action_type === 'unassign')
                                <span class="inline-flex items-center gap-1 rounded-full bg-rose-50 px-2 py-0.5 text-[10px] font-medium text-yellow-700">
                                    <span class="h-1 w-1 rounded-full bg-yellow-500"></span> Return
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-medium text-slate-700">
                                    {{ ucfirst($log->action_type) }}
                                </span>
                            @endif
                        </td>

                        {{-- User (Assigned To) --}}
                        <td class="px-3 py-2">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span class="text-slate-700 truncate max-w-[90px]" title="{{ $log->user->name ?? 'N/A' }}">
                                    {{ $log->user->name ?? 'N/A' }}
                                </span>
                            </div>
                        </td>

                        {{-- Performed By --}}
                        <td class="px-3 py-2">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                <span class="text-slate-700 truncate max-w-[90px]" title="{{ $log->actionByUser->name ?? 'N/A' }}">
                                    {{ $log->actionByUser->name ?? 'N/A' }}
                                </span>
                            </div>
                        </td>

                        {{-- Reason (Fully Visible) --}}
                        <td class="px-3 py-2">
                            @if($log->reason)
                                <div class="flex items-start gap-1.5">
                                    <svg class="w-3 h-3 text-amber-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="text-slate-700 text-[11px] leading-relaxed break-words">
                                        {{ $log->reason }}
                                    </p>
                                </div>
                            @else
                                <span class="text-slate-400 text-[11px] italic ml-5">No reason provided</span>
                            @endif
                        </td>

                        {{-- Comment (with tooltip for long text) --}}
                        <td class="px-3 py-2">
                            @if($log->comment)
                                <div class="group relative">
                                    <div class="flex items-start gap-1.5">
                                        <svg class="w-3 h-3 text-blue-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                        </svg>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-slate-600 text-[11px] leading-relaxed line-clamp-2 break-words max-w-[200px]">
                                                {{ $log->comment }}
                                            </p>
                                        </div>
                                    </div>
                                    
                                    {{-- Tooltip for long comments --}}
                                    @if(strlen($log->comment) > 80)
                                        <div class="absolute bottom-full left-0 mb-2 hidden group-hover:block z-20">
                                            <div class="bg-gray-900 text-white text-xs rounded-lg p-2 max-w-xs shadow-lg">
                                                <p class="text-[11px] leading-relaxed">{{ $log->comment }}</p>
                                                <div class="absolute left-4 top-full w-2 h-2 bg-gray-900 transform rotate-45 -mt-1"></div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <span class="text-slate-400 text-[11px] italic">No comment</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-sm font-medium text-slate-700">No logs found</p>
                                <p class="text-xs text-slate-400">Device activity will appear here once recorded</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($logs->hasPages())
            <div class="border-t border-slate-100 px-4 py-3">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</x-data-card>
</div>
