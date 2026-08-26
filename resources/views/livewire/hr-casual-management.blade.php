<div>
<div class="space-y-4">

    <x-page-header title="Casual Detail Management" subtitle="Review, approve and manage casual worker records">
        <x-slot name="actions">
            {{-- Print Contracts --}}
            <button
                type="button"
                wire:click="setView('Print_Casual_Contracts')"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-slate-600 to-gray-700 hover:from-slate-700 hover:to-gray-800 text-white text-sm font-medium rounded-xl shadow-sm hover:shadow-md transition-all duration-200 group"
            >
                <svg
                    class="w-4 h-4 transition-transform duration-200 group-hover:scale-110"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4H7v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"
                    />
                </svg>

                <span>Print Contracts</span>
            </button>


            {{-- Casual Details Management --}}
            <button
                type="button"
                wire:click="setView('casual_management')"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-700 hover:to-green-700 text-white text-sm font-medium rounded-xl shadow-sm hover:shadow-md transition-all duration-200 group relative"
            >
                <svg
                    class="w-4 h-4 transition-transform duration-200 group-hover:scale-110"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.46 5.197a4 4 0 00-5.16-3.754"
                    />
                </svg>

                <span>Casual Details Management</span>

                @if($pendingCasualsCount ?? 0)
                    <span class="absolute -top-2 -right-2 min-w-5 h-5 px-1 bg-amber-500 text-white text-xs font-semibold rounded-full flex items-center justify-center shadow-sm ring-2 ring-white">
                        {{ $pendingCasualsCount }}
                    </span>
                @endif
            </button>
        </x-slot>
    </x-page-header>

    {{-- ===== Segmented Tabs ===== --}}
    <div class="relative">
        <div class="flex gap-1 overflow-x-auto scrollbar-none rounded-2xl bg-gray-100 p-1.5 dark:bg-gray-800/60 ring-1 ring-gray-200/70 dark:ring-gray-700/50">

            {{-- Pending --}}
            <button wire:click="setView('pending')"
                class="relative flex shrink-0 items-center gap-2 rounded-xl px-4 sm:px-5 py-2.5 text-sm font-medium transition-all duration-200
                    {{ $view === 'pending'
                        ? 'bg-white dark:bg-gray-900 text-blue-600 shadow-md ring-1 ring-gray-200/70 dark:ring-gray-700'
                        : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
                <span>Pending</span>
                @if($pendingCount ?? 0)
                    <span class="inline-flex h-5 min-w-[1.25rem] items-center justify-center rounded-full bg-amber-500 px-1.5 text-[10px] font-bold text-white">
                        {{ $pendingCount }}
                    </span>
                @endif
                @if($view === 'pending')
                    <span class="h-1.5 w-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                @endif
            </button>

            {{-- HRM Approved --}}
            <button wire:click="setView('hrm_approved')"
                class="relative flex shrink-0 items-center gap-2 rounded-xl px-4 sm:px-5 py-2.5 text-sm font-medium transition-all duration-200
                    {{ $view === 'hrm_approved'
                        ? 'bg-white dark:bg-gray-900 text-emerald-600 shadow-md ring-1 ring-gray-200/70 dark:ring-gray-700'
                        : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
                <span>HRM Approved</span>
                @if($view === 'hrm_approved')
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                @endif
            </button>

            {{-- Rejected --}}
            <button wire:click="setView('rejected')"
                class="relative flex shrink-0 items-center gap-2 rounded-xl px-4 sm:px-5 py-2.5 text-sm font-medium transition-all duration-200
                    {{ $view === 'rejected'
                        ? 'bg-white dark:bg-gray-900 text-rose-600 shadow-md ring-1 ring-gray-200/70 dark:ring-gray-700'
                        : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
                <span>Rejected</span>
                @if($view === 'rejected')
                    <span class="h-1.5 w-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                @endif
            </button>
        </div>
    </div>
</div>

    <!-- Main Content -->
    @if($view === 'pending')
<div class="space-y-5 mt-3">

    {{-- ===== Header Card ===== --}}
    <div class="relative overflow-hidden rounded-2xl border border-gray-200/70 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-50 via-indigo-50 to-transparent dark:from-blue-950/30 dark:via-indigo-950/20 dark:to-transparent pointer-events-none"></div>

        <div class="relative flex flex-col gap-4 p-5 sm:p-6 sm:flex-row sm:items-center sm:justify-between">
            {{-- Title --}}
            <div class="flex items-start gap-3">
                <div class="hidden sm:flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 text-white shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V6m0 12v-2m0-8a3 3 0 013 3M9 10a3 3 0 013-3"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <h2 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white">
                        HR Rate Approval
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Set approved daily rates for pending requisitions
                    </p>
                </div>
            </div>

            {{-- Right-side stats --}}
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2 px-3 py-2 rounded-xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200/70 dark:border-amber-800/50">
                    <span class="relative flex h-2 w-2">
                        <span class="absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75 animate-ping"></span>
                        <span class="relative inline-flex h-2 w-2 rounded-full bg-amber-500"></span>
                    </span>
                    <span class="text-xs font-medium text-amber-800 dark:text-amber-300 whitespace-nowrap">
                        Awaiting Rate Input
                    </span>
                </div>

                <div class="flex flex-col items-end leading-tight">
                    <span class="text-[11px] uppercase tracking-wide text-gray-500 dark:text-gray-400">Awaiting</span>
                    <span class="text-xl font-bold text-gray-900 dark:text-white">
                        {{ $this->hrRequisitions->count() }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== Data Container ===== --}}
    <div class="rounded-2xl border border-gray-200/70 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm overflow-hidden">

        {{-- ===== Desktop / Tablet Table (md+) ===== --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                <thead class="bg-gray-50 dark:bg-gray-900/60">
                    <tr class="text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        <th class="px-5 py-3">Requested By</th>
                        <th class="px-5 py-3">Casuals</th>
                        <th class="px-5 py-3">Duration</th>
                        <th class="px-5 py-3 w-48">Daily Rate (KES)</th>
                        <th class="px-5 py-3">Estimated Total</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($this->hrRequisitions as $req)
                        <tr class="hover:bg-gray-50/70 dark:hover:bg-gray-800/40 transition-colors">
                            {{-- Requester --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="h-9 w-9 shrink-0 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 text-white flex items-center justify-center text-sm font-semibold shadow-sm">
                                        {{ strtoupper(substr($req->requester->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                            {{ $req->requester->name }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                            {{ $req->requester->department->name ?? 'Department' }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            {{-- Casuals --}}
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 border border-blue-100 dark:border-blue-900/50">
                                    {{ $req->no_of_casuals }} Casuals
                                </span>
                            </td>

                            {{-- Duration --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-1.5 text-sm text-gray-700 dark:text-gray-300">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $req->duration }} days
                                </div>
                            </td>

                            {{-- Rate Input --}}
                            <td class="px-5 py-4">
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-xs font-semibold text-gray-400 dark:text-gray-500 pointer-events-none">
                                        KES
                                    </span>
                                    <input
                                        type="number"
                                        min="0"
                                        step="any"
                                        wire:model.live.debounce.300ms="rates.{{ $req->id }}"
                                        placeholder="0.00"
                                        class="w-full pl-12 pr-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('rates.'.$req->id) border-red-500 ring-1 ring-red-500 @enderror">
                                </div>
                                @error("rates.$req->id")
                                    <p class="mt-1 flex items-center gap-1 text-xs text-red-600 dark:text-red-400">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10A8 8 0 11 2 10a8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </td>

                            {{-- Estimated Total --}}
                            <td class="px-5 py-4">
                                @if(!empty($rates[$req->id]) && is_numeric($rates[$req->id]))
                                    <span class="text-sm font-semibold text-emerald-600 dark:text-emerald-400 whitespace-nowrap">
                                        KES {{ number_format((float) $rates[$req->id] * (int) $req->no_of_casuals * (int) $req->duration) }}
                                    </span>
                                @else
                                    <span class="text-gray-400 dark:text-gray-500 italic text-sm">—</span>
                                @endif
                            </td>

                            {{-- Status --}}
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300 border border-amber-200/60 dark:border-amber-800/50 whitespace-nowrap">
                                    <span class="w-1.5 h-1.5 bg-amber-500 rounded-full mr-1.5 animate-pulse"></span>
                                    Awaiting Rate
                                </span>
                            </td>

                            {{-- Action --}}
                            <td class="px-5 py-4 text-right">
                                <button
                                    wire:click="submitRate({{ $req->id }})"
                                    wire:loading.attr="disabled"
                                    wire:target="submitRate({{ $req->id }})"
                                    class="inline-flex items-center gap-2 px-3.5 py-2 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 rounded-lg shadow-sm hover:shadow-md transition disabled:opacity-60 disabled:cursor-not-allowed">
                                    <svg wire:loading.remove wire:target="submitRate({{ $req->id }})" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <svg wire:loading wire:target="submitRate({{ $req->id }})" class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    <span wire:loading.remove wire:target="submitRate({{ $req->id }})">Submit</span>
                                    <span wire:loading wire:target="submitRate({{ $req->id }})">Saving…</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="h-14 w-14 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">No Pending Requisitions</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">All requisitions have been processed</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ===== Mobile Cards (< md) ===== --}}
        <div class="md:hidden divide-y divide-gray-100 dark:divide-gray-800">
            @forelse($this->hrRequisitions as $req)
                <div class="p-4 space-y-4">
                    {{-- Top row: requester + status --}}
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="h-10 w-10 shrink-0 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 text-white flex items-center justify-center text-sm font-semibold">
                                {{ strtoupper(substr($req->requester->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                    {{ $req->requester->name }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                    {{ $req->requester->department->name ?? 'Department' }}
                                </p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-[11px] font-medium bg-amber-50 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300 border border-amber-200/60 dark:border-amber-800/50 whitespace-nowrap">
                            <span class="w-1.5 h-1.5 bg-amber-500 rounded-full mr-1.5 animate-pulse"></span>
                            Awaiting
                        </span>
                    </div>

                    {{-- Meta grid --}}
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div class="rounded-lg bg-gray-50 dark:bg-gray-800/60 px-3 py-2">
                            <p class="text-[11px] uppercase tracking-wide text-gray-500 dark:text-gray-400">Casuals</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $req->no_of_casuals }}</p>
                        </div>
                        <div class="rounded-lg bg-gray-50 dark:bg-gray-800/60 px-3 py-2">
                            <p class="text-[11px] uppercase tracking-wide text-gray-500 dark:text-gray-400">Duration</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $req->duration }} days</p>
                        </div>
                    </div>

                    {{-- Rate input --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Daily Rate</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-xs font-semibold text-gray-400 dark:text-gray-500 pointer-events-none">KES</span>
                            <input
                                type="number"
                                min="0"
                                step="any"
                                wire:model.live.debounce.300ms="rates.{{ $req->id }}"
                                placeholder="0.00"
                                class="w-full pl-12 pr-3 py-2.5 text-sm rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('rates.'.$req->id) border-red-500 ring-1 ring-red-500 @enderror">
                        </div>
                        @error("rates.$req->id")
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Total + action --}}
                    <div class="flex items-center justify-between gap-3 pt-1">
                        <div class="min-w-0">
                            <p class="text-[11px] uppercase tracking-wide text-gray-500 dark:text-gray-400">Estimated Total</p>
                            @if(!empty($rates[$req->id]) && is_numeric($rates[$req->id]))
                                <p class="text-sm font-semibold text-emerald-600 dark:text-emerald-400 truncate">
                                    KES {{ number_format((float) $rates[$req->id] * (int) $req->no_of_casuals * (int) $req->duration) }}
                                </p>
                            @else
                                <p class="text-sm text-gray-400 italic">—</p>
                            @endif
                        </div>
                        <button
                            wire:click="submitRate({{ $req->id }})"
                            wire:loading.attr="disabled"
                            wire:target="submitRate({{ $req->id }})"
                            class="shrink-0 inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 rounded-lg shadow-sm disabled:opacity-60">
                            <span wire:loading.remove wire:target="submitRate({{ $req->id }})">Submit Rate</span>
                            <span wire:loading wire:target="submitRate({{ $req->id }})">Saving…</span>
                        </button>
                    </div>
                </div>
            @empty
                <div class="py-14 text-center">
                    <div class="mx-auto h-14 w-14 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center mb-3">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">No Pending Requisitions</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">All requisitions have been processed</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Pagination --}}
    @if($this->hrRequisitions->hasPages())
        <div class="px-1">
            {{ $this->hrRequisitions->links() }}
        </div>
    @endif
</div>

@elseif($view === 'casual_management')

<div class="space-y-6">
    <!-- Header Card -->
    <div class="bg-gradient-to-r from-emerald-500 to-green-600 p-2 text-white shadow-xl">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold mb-2">Casual Workers Management</h2>
                <p class="text-emerald-100 opacity-90">Manage all casual workers, their details, assignments and status</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="text-right">
                    <div class="text-sm text-emerald-200">Total Casuals</div>
                    <div class="text-3xl font-bold">{{ $totalcasualsData ?? 0 }}</div>
                </div>
                <div class="text-right">
                    <div class="text-sm text-emerald-200">Active</div>
                    <div class="text-3xl font-bold">{{ $activecasualsData ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 md:gap-6 items-center">

        <!-- Search input takes 5/12 -->
        <div class="md:col-span-5">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input
                    wire:model.live.debounce.300ms="casualSearch"
                    type="text"
                    placeholder="Search by name, phone, or department..."
                    class="w-full pl-10 pr-4 py-3 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:focus:ring-emerald-600 dark:focus:border-emerald-600 transition-all duration-200 placeholder:text-gray-500 dark:placeholder:text-gray-400"
                >
            </div>
        </div>

        <!-- Status filter takes 4/12 -->
        <div class="md:col-span-3">
            <div class="relative">
                <select
                    wire:model.live="casualStatusFilter"
                    class="w-full px-4 py-3 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:focus:ring-emerald-600 dark:focus:border-emerald-600 transition-all duration-200 appearance-none"
                >
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="pending">Pending</option>
                    <option value="blocked">Blocked</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Add Casual Button takes 4/12 -->
        <div class="md:col-span-4"
            x-cloak
            x-data="{ isOpen: false }"
            @close-casual-modal.window="isOpen = false"
            x-init="$watch('isOpen', val => document.body.classList.toggle('overflow-hidden', val))"
        >

            <!-- Trigger Button -->
            <button
                @click="isOpen = true"
                class="w-full px-6 py-3 bg-gradient-to-r from-emerald-500 to-green-600
                    hover:from-emerald-600 hover:to-green-700 text-white rounded-xl
                    font-medium transition-all duration-200 shadow-md hover:shadow-lg
                    flex items-center justify-center gap-2 group">
                <svg class="w-5 h-5 transition-transform group-hover:scale-110"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4v16m8-8H4" />
                </svg>
                <span>Add Casual Staff</span>
            </button>

            <!-- Modal Backdrop -->
            <div
                x-show="isOpen"
                x-transition.opacity.duration.300ms
                @keydown.escape.window="isOpen = false"
                class="fixed inset-0 z-50 flex items-center justify-center
                    p-4 bg-black/50"
            >

                <!-- Modal Panel -->
                <div
                    @click.away="isOpen = false"
                    x-transition.scale.origin.center.duration.300ms
                    class="relative w-full max-w-md"
                >
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl overflow-hidden">

                        <!-- Modal Header -->
                        <div class="px-6 py-5 bg-gradient-to-r from-emerald-50 to-green-50
                                    dark:from-gray-700 dark:to-gray-800
                                    border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                        Add Casual Staff
                                    </h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                                        Fill in the casual staff details below
                                    </p>
                                </div>

                                <button
                                    @click="isOpen = false"
                                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300
                                        hover:bg-gray-100 dark:hover:bg-gray-700
                                        rounded-full w-8 h-8 flex items-center justify-center
                                        transition-colors"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Modal Form -->
                        <form wire:submit.prevent="addCasual" class="p-6 space-y-6">

                            <!-- Personal Details -->
                            <div class="space-y-4">
                                <h4 class="font-medium text-gray-900 dark:text-white flex items-center gap-2">
                                    <svg class="w-5 h-5 text-emerald-500"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0z
                                                M12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Personal Details
                                </h4>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium mb-1.5">
                                            First Name *
                                        </label>
                                        <input type="text" wire:model.live="first_name"
                                            class="w-full px-4 py-2.5 rounded-lg border
                                                    bg-white dark:bg-gray-700
                                                    border-gray-300 dark:border-gray-600
                                                    focus:ring-2 focus:ring-emerald-500
                                                    focus:border-emerald-500 transition"
                                            placeholder="John Doe">
                                        @error('first_name')
                                            <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium mb-1.5">
                                            Last Name *
                                        </label>
                                        <input type="tel" wire:model.live="last_name"
                                            class="w-full px-4 py-2.5 rounded-lg border
                                                    bg-white dark:bg-gray-700
                                                    border-gray-300 dark:border-gray-600
                                                    focus:ring-2 focus:ring-emerald-500
                                                    focus:border-emerald-500 transition"
                                            placeholder="0712 345 678">
                                        @error('last_name')
                                            <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>


                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium mb-1.5">
                                            ID Number *
                                        </label>
                                        <input type="text" wire:model.live="id_number"
                                            class="w-full px-4 py-2.5 rounded-lg border
                                                    bg-white dark:bg-gray-700
                                                    border-gray-300 dark:border-gray-600
                                                    focus:ring-2 focus:ring-emerald-500
                                                    focus:border-emerald-500 transition"
                                            placeholder="John Doe">
                                        @error('id_number')
                                            <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium mb-1.5">
                                            NSSF Number *
                                        </label>
                                        <input type="tel" wire:model.live="nssf_number"
                                            class="w-full px-4 py-2.5 rounded-lg border
                                                    bg-white dark:bg-gray-700
                                                    border-gray-300 dark:border-gray-600
                                                    focus:ring-2 focus:ring-emerald-500
                                                    focus:border-emerald-500 transition"
                                            placeholder="0712 345 678">
                                        @error('nssf_number')
                                            <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>



                                

                                <div>
                                    <label class="block text-sm font-medium mb-1.5">
                                        Department *
                                    </label>
                                    <select wire:model.live="department"
                                            class="w-full px-4 py-2.5 rounded-lg border
                                                bg-white dark:bg-gray-700
                                                border-gray-300 dark:border-gray-600
                                                focus:ring-2 focus:ring-emerald-500
                                                focus:border-emerald-500 transition">
                                        <option value="">Select Department</option>
                                        <option value="operations">Operations</option>
                                        <option value="logistics">Logistics</option>
                                        <option value="warehouse">Warehouse</option>
                                        <option value="administration">Administration</option>
                                    </select>
                                    @error('department')
                                        <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Submit -->
                            <div class="pt-4">
                                <button type="submit"
                                        class="w-full px-6 py-3.5 bg-gradient-to-r
                                            from-emerald-500 to-green-600
                                            hover:from-emerald-600 hover:to-green-700
                                            text-white font-medium rounded-lg
                                            shadow-md hover:shadow-lg
                                            flex items-center justify-center gap-2 group">
                                    <svg class="w-5 h-5 transition-transform group-hover:scale-110"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    Add Casual Staff
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Casuals Grid -->
    
    <div class="overflow-x-auto bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800">
    <table class="min-w-full text-sm text-left">
        <thead class="bg-gray-50 dark:bg-gray-800 text-gray-600 dark:text-gray-400">
            <tr>
                <th class="px-6 py-4 font-semibold">Name</th>
                <th class="px-6 py-4 font-semibold">Status</th>
                <th class="px-6 py-4 font-semibold">Department</th>
                <th class="px-6 py-4 font-semibold">Phone</th>
                <th class="px-6 py-4 font-semibold">Email</th>
                <th class="px-6 py-4 font-semibold">Location</th>
                <th class="px-6 py-4 font-semibold">Assignment</th>
                <th class="px-6 py-4 font-semibold text-right">Actions</th>
            </tr>
        </thead>

        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
        @forelse($this->casualsData as $casual)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/60 transition">

                {{-- Name --}}
                <td class="px-6 py-2">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-green-600 rounded-lg flex items-center justify-center text-white font-bold">
                            {{ substr($casual->name, 0, 1) }}
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900 dark:text-white">
                                {{ $casual->name }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                Added {{ $casual->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                </td>

                {{-- Status --}}
                <td class="px-6 py-2">
                    <div class="flex gap-2">
                        <span class="px-2 py-1 text-xs font-medium rounded-full
                            @if($casual->status === 'active') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300
                            @elseif($casual->status === 'inactive') bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300
                            @elseif($casual->status === 'pending') bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300
                            @else bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 @endif">
                            {{ ucfirst($casual->status) }}
                        </span>

                        @if($casual->is_assigned)
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                Assigned
                            </span>
                        @endif
                    </div>
                </td>

                {{-- Department --}}
                <td class="px-6 py-2 text-gray-700 dark:text-gray-300">
                    {{ $casual->department ?? '—' }}
                </td>

                {{-- Phone --}}
                <td class="px-6 py-2 text-gray-700 dark:text-gray-300">
                    {{ $casual->phone ?? '—' }}
                </td>

                {{-- Email --}}
                <td class="px-6 py-2 text-gray-700 dark:text-gray-300 truncate max-w-xs">
                    {{ $casual->email ?? '—' }}
                </td>

                {{-- Location --}}
                <td class="px-6 py-2 text-gray-700 dark:text-gray-300">
                    {{ $casual->location ?? '—' }}
                </td>

                {{-- Assignment --}}
                <td class="px-6 py-2">
                    @if($casual->current_assignment)
                        <div class="text-xs">
                            <div class="font-medium text-gray-900 dark:text-white">
                                {{ $casual->current_assignment->requisition->department ?? 'N/A' }}
                            </div>
                            <div class="text-gray-500 dark:text-gray-400">
                                {{ $casual->current_assignment->start_date->format('M d') }}
                                –
                                {{ $casual->current_assignment->end_date->format('M d') }}
                            </div>
                        </div>
                    @else
                        <span class="text-gray-400 dark:text-gray-500">None</span>
                    @endif
                    @if($casual->current_assignment)
                        <div class="text-xs">
                            <div class="font-medium text-gray-900 dark:text-white">
                                {{ $casual->current_assignment->requisition->department ?? 'N/A' }}
                            </div>
                            <div class="text-gray-500 dark:text-gray-400">
                                {{ $casual->current_assignment->start_date->format('M d') }}
                                –
                                {{ $casual->current_assignment->end_date->format('M d') }}
                            </div>
                        </div>
                    @else
                        <span class="text-gray-400 dark:text-gray-500">None</span>
                    @endif
                </td>

                {{-- Actions --}}
                <td class="px-6 py-2 text-right relative">
                    <button
                        wire:click="$set('selectedCasualId', {{ $casual->id }})"
                        class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800"
                    >
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 6v.01M12 12v.01M12 18v.01"/>
                        </svg>
                    </button>

                    @if($selectedCasualId == $casual->id)
                        <div class="absolute right-6 mt-2 w-48 bg-white dark:bg-gray-900 rounded-xl shadow-xl border border-gray-200 dark:border-gray-800 z-20">
                            <div class="py-1">
                                <button wire:click="editCasual({{ $casual->id }})" class="dropdown-item">
                                    Edit Details
                                </button>
                                <button wire:click="viewAssignments({{ $casual->id }})" class="dropdown-item">
                                    View Assignments
                                </button>

                                @if($casual->status === 'active')
                                    <button wire:click="deactivateCasual({{ $casual->id }})" class="dropdown-item text-amber-600">
                                        Deactivate
                                    </button>
                                @else
                                    <button wire:click="activateCasual({{ $casual->id }})" class="dropdown-item text-green-600">
                                        Activate
                                    </button>
                                @endif

                                <hr class="my-1 border-gray-200 dark:border-gray-800">

                                <button wire:click="confirmDeleteCasual({{ $casual->id }})" class="dropdown-item text-red-600">
                                    Delete
                                </button>
                            </div>
                        </div>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="py-16 text-center text-gray-500 dark:text-gray-400">
                    No Casual Workers Found
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

    </div>

    <!-- Pagination -->
    @if($this->casualsData->hasPages())
    <div class="mt-6">
        {{ $this->casualsData->links() }}
    </div>
    @endif
</div>

   
@elseif($view === 'Print_Casual_Contracts')

<table class="w-full text-sm">
    <thead>
        <tr class="bg-gray-100">
            <th class="px-4 py-3 text-left">#</th>
            <th class="px-4 py-3 text-left">Requisition No</th>
            <th class="px-4 py-3 text-left">Department</th>
            <th class="px-4 py-3 text-left">Casuals Assigned</th>
            <th class="px-4 py-3 text-left">Status</th>
            <th class="px-4 py-3 text-left">Actions</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-gray-200">
        @forelse($this->AssignedRequisitions as $req)
        <tr class="hover:bg-gray-50">
            <td class="px-4 py-3">{{ $loop->iteration }}</td>
            <td class="px-4 py-3 font-medium text-blue-600">
                {{ $req->requisition_no ?? 'REQ-' . str_pad($req->id, 6, '0', STR_PAD_LEFT) }}
            </td>
            <td class="px-4 py-3">
                {{ $req->requester->name ?? 'N/A' }}
                <!-- Assuming you have a User model with department relationship -->
            </td>
            <td class="px-4 py-3">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                    {{ $req->casual_assignments_count > 0 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                    {{ $req->casual_assignments_count ?? 0 }} / {{ $req->no_of_casuals }}
                </span>
            </td>
            <td class="px-4 py-3">
                @if($req->coo_approval_status && $req->casual_assignment_status)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        Assigned
                    </span>
                @elseif($req->coo_approval_status)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Approved
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        Pending
                    </span>
                @endif
            </td>
            <td class="px-4 py-3 space-x-2">
                @if($req->casual_assignments_count > 0)
                <a href="{{ route('contracts.print', ['requisitionId' => $req->id]) }}" 
                   target="_blank"
                   rel="noopener"
                   class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors text-xs">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Print Contracts
                </a>
                @endif
                
                <a href="#"
                   class="inline-flex items-center px-3 py-1.5 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors text-xs">
                    View Details
                </a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="mt-2 text-sm">No requisitions with assigned casuals found</p>
                <p class="text-xs text-gray-400 mt-1">All approved requisitions with casual assignments will appear here</p>
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

@if($this->AssignedRequisitions->hasPages())
<div class="px-4 py-3 border-t border-gray-200 sm:px-6">
    {{ $this->AssignedRequisitions->links() }}
</div>
@endif


@elseif($view === 'hrm_approved')
<div class="bg-white dark:bg-gray-900 shadow-2xl border border-gray-200/50 dark:border-gray-800/50 overflow-hidden backdrop-blur-sm bg-opacity-95 dark:bg-opacity-95">
    <!-- Header with gradient -->
    <div class="p-2 bg-gradient-to-r from-emerald-600 via-green-500 to-emerald-700">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div class="space-y-2">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-white/20 rounded-xl backdrop-blur-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-white">Approved Requisitions</h2>
                </div>
                <p class="text-emerald-100/90 text-sm md:text-base">Ready for casual worker assignment</p>
            </div>
            
            <!-- Stats Card -->
            <div class="bg-white/20 backdrop-blur-sm rounded-xl p-1 min-w-[140px] border border-white/10">
                <div class="text-sm font-medium text-emerald-100/90">Total Approved</div>
                <div class="flex items-end gap-2">
                    <div class="text-3xl md:text-4xl font-bold text-white">{{ $this->hrRequisitions->count() }}</div>
                    <div class="text-emerald-100/70 text-sm mb-1">requests</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Container -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gradient-to-r from-gray-50/50 to-gray-100/30 dark:from-gray-800/50 dark:to-gray-900/30">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider border-b border-gray-200/50 dark:border-gray-700/50">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Requester
                        </div>
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider border-b border-gray-200/50 dark:border-gray-700/50">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Casuals
                        </div>
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider border-b border-gray-200/50 dark:border-gray-700/50">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Duration
                        </div>
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider border-b border-gray-200/50 dark:border-gray-700/50">
                        Status
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider border-b border-gray-200/50 dark:border-gray-700/50">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200/50 dark:divide-gray-800/50">
                @forelse($this->hrRequisitions as $req)
                <tr class="group hover:bg-gradient-to-r hover:from-emerald-50/30 hover:to-green-50/10 dark:hover:from-gray-800/30 dark:hover:to-gray-900/30 transition-all duration-300">
                    <td class="px-6 py-2">
                        <div class="flex items-center gap-3">
                            <div class="relative">
                                <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl flex items-center justify-center text-white font-semibold shadow-lg">
                                    {{ substr($req->requester->name, 0, 1) }}
                                </div>
                                <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-400 rounded-full border-2 border-white dark:border-gray-900"></div>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900 dark:text-white group-hover:text-emerald-700 dark:group-hover:text-emerald-400 transition-colors">{{ $req->requester->name }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $req->department ?? 'Department' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-2">
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1.5 text-sm font-semibold rounded-lg bg-gradient-to-r from-emerald-100/80 to-green-100/80 text-emerald-800 dark:from-emerald-900/40 dark:to-green-900/40 dark:text-emerald-300 border border-emerald-200/50 dark:border-emerald-800/50">
                                {{ $req->no_of_casuals }} workers
                            </span>
                        </div>
                    </td>
                    <td class="px-6 py-2">
                        <div class="flex items-center gap-2">
                            <div class="font-medium text-gray-900 dark:text-white">{{ $req->duration }} days</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">• {{ now()->addDays($req->duration)->format('M d') }}</div>
                        </div>
                    </td>
                    <td class="px-6 py-2">
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-gradient-to-r from-emerald-100/80 to-green-100/80 text-emerald-800 dark:from-emerald-900/40 dark:to-green-900/40 dark:text-emerald-300 border border-emerald-200/50 dark:border-emerald-800/50 shadow-sm">
                            <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Approved
                        </span>
                    </td>
                    <td class="px-6 py-2">
                        <button
                            wire:click="assignCasuals({{ $req->id }})"
                            class="px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-700 hover:to-green-700 rounded-lg transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center gap-2 group/btn border border-emerald-700/20">
                            <svg class="w-4 h-4 group-hover/btn:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.46 5.197a4 4 0 00-5.16-3.754"/>
                            </svg>
                            Assign Workers
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-16 text-center">
                        <div class="max-w-md mx-auto">
                            <div class="w-24 h-24 mx-auto mb-6 text-gray-300 dark:text-gray-700">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="opacity-50">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">All Caught Up!</h3>
                            <p class="text-gray-500 dark:text-gray-400 mb-6">No approved requisitions pending assignment</p>
                            <div class="w-32 h-1 bg-gradient-to-r from-emerald-400/20 to-green-400/20 rounded-full mx-auto"></div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div x-data="{ 
    show: @entangle('showAssignmentModal'),
    search: '',
    selectedCasuals: @entangle('selectedCasuals'),
    casuals: @entangle('casuals'),
    filteredCasuals: [],
    showDropdown: false
}" 
     x-init="
        filteredCasuals = [];
        $watch('search', value => {
            if (value.length > 0) {
                showDropdown = true;
                filteredCasuals = casuals.filter(casual => 
                    casual.name.toLowerCase().includes(value.toLowerCase()) ||
                    casual.id.toString().includes(value) ||
                    (casual.email && casual.email.toLowerCase().includes(value.toLowerCase()))
                );
            } else {
                showDropdown = false;
                filteredCasuals = [];
            }
        });
        
        // Close dropdown when clicking outside
        $nextTick(() => {
            window.addEventListener('click', (e) => {
                if (!e.target.closest('[data-dropdown]')) {
                    showDropdown = false;
                }
            });
        });
     "
     x-show="show"
     x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/40"
     x-transition>

    <!-- Modal Box -->
    <div @click.away="show = false"
     class="bg-white dark:bg-gray-700 rounded-lg shadow-lg 
            w-full max-w-lg p-6 
            max-h-[90vh] flex flex-col overflow-hidden"
     x-transition.scale>

        <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Assign Casual Workers</h2>

        <form wire:submit.prevent="update" class="space-y-6 flex-1 min-h-0 overflow-y-auto">
            <!-- Search Input with Dropdown -->
            <div data-dropdown>
                <label for="casualSearch" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Search and Select Casual Workers
                </label>
                <div class="relative">
                    <input type="text"
                           id="casualSearch"
                           x-model="search"
                           @focus="if (search.length > 0) showDropdown = true"
                           placeholder="Type to search casuals by name, email, or ID..."
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                           autocomplete="off">
                    
                    <!-- Dropdown Results -->
                    <div x-show="showDropdown && filteredCasuals.length > 0"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute z-10 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-md shadow-lg max-h-60 overflow-y-auto"
                         style="display: none;">
                        <template x-for="casual in filteredCasuals" :key="casual.id">
                            <div class="px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer border-b border-gray-100 dark:border-gray-700 last:border-b-0"
                                 @click="
                                     if (!selectedCasuals.includes(casual.id)) {
                                         selectedCasuals.push(casual.id);
                                     }
                                     search = '';
                                     showDropdown = false;
                                 ">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="font-medium text-gray-900 dark:text-white" x-text="casual.name"></span>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            <span x-text="casual.email"></span>
                                            <span class="mx-1">•</span>
                                            <span>ID: <span x-text="casual.id"></span></span>
                                        </div>
                                    </div>
                                    <svg x-show="selectedCasuals.includes(casual.id)" 
                                         class="w-5 h-5 text-green-500" 
                                         fill="currentColor" 
                                         viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </template>
                    </div>
                    
                    <!-- No Results Message -->
                    <div x-show="showDropdown && filteredCasuals.length === 0 && search.length > 0"
                         class="absolute z-10 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-md shadow-lg p-3"
                         style="display: none;">
                        <div class="text-center text-gray-500 dark:text-gray-400">
                            No casual workers found for "<span x-text="search"></span>"
                        </div>
                    </div>
                </div>
            </div>

            <!-- Selected Casuals Display -->
            <div x-show="selectedCasuals.length > 0" class="space-y-3">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">
                        Selected Workers (<span x-text="selectedCasuals.length"></span>)
                    </h3>
                    <button type="button"
                            @click="selectedCasuals = []"
                            class="text-sm text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                        Clear All
                    </button>
                </div>
                
                <!-- Selected Casuals List -->
                <div class="border border-gray-200 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-800/50 p-3 max-h-48 overflow-y-auto min-h-0">
                    <div class="space-y-2">
                        <template x-for="casualId in selectedCasuals" :key="casualId">
                            <div class="flex items-center justify-between p-2 bg-white dark:bg-gray-800 rounded border border-gray-200 dark:border-gray-700">
                                <div>
                                    <span class="font-medium text-gray-900 dark:text-white"
                                          x-text="casuals.find(c => c.id === casualId)?.name || 'Loading...'"></span>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        <span x-text="casuals.find(c => c.id === casualId)?.email || ''"></span>
                                        <span class="mx-1">•</span>
                                        <span>ID: <span x-text="casualId"></span></span>
                                    </div>
                                </div>
                                <button type="button"
                                        @click="selectedCasuals = selectedCasuals.filter(id => id !== casualId)"
                                        class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 p-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- No Selection Message -->
            <div x-show="selectedCasuals.length === 0" class="text-center py-4 border border-dashed border-gray-300 dark:border-gray-600 rounded-md">
                <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No workers selected yet. Search above to add casual workers.</p>
            </div>

            <!-- Other Form Fields (Optional - Add your other fields here) -->
            <div class="space-y-4">
            </div>

            <!-- Hidden inputs for selected casuals -->
            <template x-for="casualId in selectedCasuals" :key="casualId">
                <input type="hidden" name="selectedCasuals[]" :value="casualId">
            </template>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-600">
                <button type="button"
                        @click="show = false"
                        class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 rounded-md hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                    Cancel
                </button>
                <button type="submit"
                        wire:click = "assignSelectedCasuals"
                        :disabled="selectedCasuals.length === 0"
                        :class="selectedCasuals.length === 0 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-blue-700'"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <span x-show="selectedCasuals.length > 0">
                        Assign <span x-text="selectedCasuals.length"></span> Worker<span x-show="selectedCasuals.length > 1">s</span>
                    </span>
                    <span x-show="selectedCasuals.length === 0">Assign Workers</span>
                </button>
            </div>
        </form>
    </div>
</div>

@elseif($view === 'rejected')
    <div class="bg-white dark:bg-gray-900 shadow-lg border border-gray-200 dark:border-gray-800 overflow-hidden">
        <!-- Header -->
        <div class="p-2 bg-gradient-to-r from-rose-600 to-red-600">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-white mb-2">Rejected Requisitions</h2>
                    <p class="text-rose-100">View rejected requests and reasons</p>
                </div>
                <div class="text-right">
                    <div class="text-sm text-rose-200">Total Rejected</div>
                    <div class="text-3xl font-bold text-white">{{ $this->hrRequisitions->count() }}</div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-800/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Requester</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Casuals</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Duration</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Rejection Reason</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse($this->hrRequisitions as $req)
                    <tr class="hover:bg-rose-50/30 dark:hover:bg-gray-800/50 transition-colors duration-150">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-gradient-to-br from-rose-500 to-red-600 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                    {{ substr($req->requester->name, 0, 1) }}
                                </div>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $req->requester->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-gradient-to-r from-rose-100 to-red-100 text-rose-800 dark:from-rose-900/30 dark:to-red-900/30 dark:text-rose-300">
                                {{ $req->no_of_casuals }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $req->duration }} days</td>
                        <td class="px-6 py-4">
                            <div class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-rose-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.282 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $req->rejection_reason ?? 'No reason provided' }}</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-16 text-center">
                            <div class="max-w-md mx-auto">
                                <div class="w-20 h-20 mx-auto mb-4 text-gray-300 dark:text-gray-700">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Rejected Requisitions</h3>
                                <p class="text-gray-500 dark:text-gray-400">All requisitions are currently approved or pending</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endif
</div>