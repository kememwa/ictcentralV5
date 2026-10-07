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
    <div class="relative pb-3">

        {{-- Loading indicator --}}
        <div
            wire:loading
            wire:target="setView"
            class="absolute -top-1 left-0 right-0 h-0.5 overflow-hidden rounded-full bg-gray-200"
        >
            <div class="h-full w-1/3 animate-[loading_1s_ease-in-out_infinite] rounded-full bg-blue-500"></div>
        </div>

        <div class="flex gap-1 overflow-x-auto scrollbar-none rounded-2xl
                    bg-gray-100 p-1.5 ring-1 ring-gray-200/70">

            {{-- Pending --}}
            <button
                wire:click="setView('pending')"
                wire:loading.attr="disabled"
                wire:target="setView('pending')"
                class="relative flex shrink-0 items-center gap-2 rounded-xl px-4 sm:px-5 py-2.5
                    text-sm font-medium transition-all duration-200
                    disabled:cursor-wait disabled:opacity-70
                    {{ $view === 'pending'
                            ? 'bg-white text-blue-600 shadow-md ring-1 ring-gray-200/70'
                            : 'text-gray-600 hover:text-gray-900' }}"
            >
                <span>Pending</span>

                @if($pendingCount ?? 0)
                    <span class="inline-flex h-5 min-w-[1.25rem] items-center justify-center
                                rounded-full bg-amber-500 px-1.5 text-[10px] font-bold text-white">
                        {{ $pendingCount }}
                    </span>
                @endif

                {{-- Active indicator --}}
                @if($view === 'pending')
                    <span
                        wire:loading.remove
                        wire:target="setView('pending')"
                        class="h-1.5 w-1.5 rounded-full bg-blue-500 animate-pulse">
                    </span>
                @endif

                {{-- Loading spinner --}}
                <svg
                    wire:loading
                    wire:target="setView('pending')"
                    class="h-4 w-4 animate-spin text-blue-500"
                    fill="none"
                    viewBox="0 0 24 24"
                >
                    <circle
                        class="opacity-25"
                        cx="12"
                        cy="12"
                        r="10"
                        stroke="currentColor"
                        stroke-width="4">
                    </circle>

                    <path
                        class="opacity-75"
                        fill="currentColor"
                        d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                    </path>
                </svg>
            </button>

            {{-- HRM Approved --}}
            <button
                wire:click="setView('hrm_approved')"
                wire:loading.attr="disabled"
                wire:target="setView('hrm_approved')"
                class="relative flex shrink-0 items-center gap-2 rounded-xl px-4 sm:px-5 py-2.5
                    text-sm font-medium transition-all duration-200
                    disabled:cursor-wait disabled:opacity-70
                    {{ $view === 'hrm_approved'
                            ? 'bg-white text-emerald-600 shadow-md ring-1 ring-gray-200/70'
                            : 'text-gray-600 hover:text-gray-900' }}"
            >
                <span>HRM Approved</span>

                @if($view === 'hrm_approved')
                    <span
                        wire:loading.remove
                        wire:target="setView('hrm_approved')"
                        class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse">
                    </span>
                @endif

                <svg
                    wire:loading
                    wire:target="setView('hrm_approved')"
                    class="h-4 w-4 animate-spin text-emerald-500"
                    fill="none"
                    viewBox="0 0 24 24"
                >
                    <circle
                        class="opacity-25"
                        cx="12"
                        cy="12"
                        r="10"
                        stroke="currentColor"
                        stroke-width="4">
                    </circle>

                    <path
                        class="opacity-75"
                        fill="currentColor"
                        d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                    </path>
                </svg>
            </button>

            {{-- Rejected --}}
            <button
                wire:click="setView('rejected')"
                wire:loading.attr="disabled"
                wire:target="setView('rejected')"
                class="relative flex shrink-0 items-center gap-2 rounded-xl px-4 sm:px-5 py-2.5
                    text-sm font-medium transition-all duration-200
                    disabled:cursor-wait disabled:opacity-70
                    {{ $view === 'rejected'
                            ? 'bg-white text-rose-600 shadow-md ring-1 ring-gray-200/70'
                            : 'text-gray-600 hover:text-gray-900' }}"
            >
                <span>Rejected</span>

                @if($view === 'rejected')
                    <span
                        wire:loading.remove
                        wire:target="setView('rejected')"
                        class="h-1.5 w-1.5 rounded-full bg-rose-500 animate-pulse">
                    </span>
                @endif

                <svg
                    wire:loading
                    wire:target="setView('rejected')"
                    class="h-4 w-4 animate-spin text-rose-500"
                    fill="none"
                    viewBox="0 0 24 24"
                >
                    <circle
                        class="opacity-25"
                        cx="12"
                        cy="12"
                        r="10"
                        stroke="currentColor"
                        stroke-width="4">
                    </circle>

                    <path
                        class="opacity-75"
                        fill="currentColor"
                        d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                    </path>
                </svg>
            </button>
        </div>
    </div>
</div>

    <!-- Main Content -->
@if($view === 'pending')
<div class="space-y-5">

    {{-- ===== Header Card ===== --}}
    <div class="relative overflow-hidden rounded-2xl border border-gray-200/70 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-50 via-indigo-50 to-transparent dark:from-blue-950/30 dark:via-indigo-950/20 dark:to-transparent pointer-events-none"></div>

        <div class="relative flex flex-col gap-4 p-3 sm:flex-row sm:items-center sm:justify-between">
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
            </div>
        </div>
    </div>

        {{-- =========================================================
            DATA CONTAINER
        ========================================================= --}}
        <div class="overflow-hidden">


            {{-- =========================================================
                DESKTOP TABLE (lg and up)
            ========================================================= --}}
            <div class="hidden lg:block overflow-x-auto">

                <table class="w-full table-auto text-xs">

                    <thead class="border-b border-slate-200 bg-slate-50/80">

                        <tr class="text-left uppercase tracking-wider text-[10px] font-semibold text-slate-500">

                            <th class="px-2.5 py-2.5 whitespace-nowrap">
                                Requisition Num
                            </th>

                            <th class="px-2.5 py-2.5 whitespace-nowrap">
                                Requested By
                            </th>

                            <th class="px-2 py-2.5 whitespace-nowrap text-center">
                                Casuals
                            </th>

                            <th class="px-2 py-2.5 whitespace-nowrap text-center">
                                Duration
                            </th>

                            <th class="px-1.5 py-2.5 w-[105px]">
                                Daily Rate
                            </th>

                            <th class="px-1.5 py-2.5 w-[105px]">
                                NSSF
                            </th>

                            <th class="px-1.5 py-2.5 w-[105px]">
                                SHA
                            </th>

                            <th class="px-2.5 py-2.5 whitespace-nowrap">
                                Estimated Total
                            </th>

                            <th class="px-2 py-2.5 w-[70px] whitespace-nowrap text-center">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-slate-100">

                        @forelse($this->hrRequisitions as $req)

                            <tr
                                wire:key="hr-req-{{ $req->id }}"
                                class="group align-middle transition-colors hover:bg-slate-50/70"
                            >

                                {{-- REQUISITION NUMBER --}}
                                <td class="px-2.5 py-2.5 whitespace-nowrap">

                                    <p class="font-medium text-blue-600">
                                        {{ $req->ref_number }}
                                    </p>

                                    <p class="mt-0.5 text-[9px] text-slate-400">
                                        {{ $req->created_at->diffForHumans() }}
                                    </p>

                                </td>


                                {{-- REQUESTED BY --}}
                                <td class="px-2.5 py-2.5 whitespace-nowrap">

                                    <div class="min-w-0">

                                        <p class="font-medium text-slate-800 truncate max-w-[150px]">
                                            {{ $req->requester->name ?? 'Unknown User' }}
                                        </p>

                                        @if($req->requester?->department)

                                            <p class="mt-0.5 text-[9px] text-slate-400 truncate max-w-[150px]">
                                                {{ $req->requester->department->name }}
                                            </p>

                                        @endif

                                    </div>

                                </td>


                                {{-- CASUALS --}}
                                <td class="px-2 py-2.5 text-center whitespace-nowrap">

                                    <span class="font-medium tabular-nums text-slate-700">
                                        {{ number_format($req->no_of_casuals) }}
                                    </span>

                                </td>


                                {{-- DURATION --}}
                                <td class="px-2 py-2.5 text-center whitespace-nowrap">

                                    <span class="font-medium tabular-nums text-slate-700">
                                        {{ $req->duration }}
                                    </span>

                                    <span class="ml-0.5 text-[9px] text-slate-400">
                                        {{ $req->duration == 1 ? 'day' : 'days' }}
                                    </span>

                                </td>


                                {{-- DAILY RATE --}}
                                <td class="px-1.5 py-2.5">

                                    <div class="relative">

                                        <span
                                            class="pointer-events-none absolute inset-y-0 left-0 flex items-center
                                                pl-2 text-[9px] font-semibold text-slate-400"
                                        >
                                            KES
                                        </span>

                                        <input
                                            type="number"
                                            min="0"
                                            step="any"
                                            wire:model.live.debounce.300ms="rates.{{ $req->id }}"
                                            placeholder="0.00"
                                            class="w-[105px] rounded-md border border-slate-200 bg-white
                                                py-1.5 pl-9 pr-1.5 text-[11px] text-slate-700
                                                placeholder:text-slate-300
                                                focus:border-blue-500 focus:outline-none focus:ring-2
                                                focus:ring-blue-500/10
                                                @error('rates.'.$req->id)
                                                    border-red-400 ring-1 ring-red-400
                                                @enderror"
                                        />

                                    </div>

                                    @error("rates.$req->id")
                                        <p class="mt-1 text-[9px] text-red-600">
                                            {{ $message }}
                                        </p>
                                    @enderror

                                </td>


                                {{-- NSSF --}}
                                <td class="px-1.5 py-2.5">

                                    <div class="relative">

                                        <span
                                            class="pointer-events-none absolute inset-y-0 left-0 flex items-center
                                                pl-2 text-[9px] font-semibold text-slate-400"
                                        >
                                            KES
                                        </span>

                                        <input
                                            type="number"
                                            min="0"
                                            step="any"
                                            wire:model.live.debounce.300ms="nssfRates"
                                            placeholder="0.00"
                                            class="w-[105px] rounded-md border border-slate-200 bg-white
                                                py-1.5 pl-9 pr-1.5 text-[11px] text-slate-700
                                                placeholder:text-slate-300
                                                focus:border-blue-500 focus:outline-none focus:ring-2
                                                focus:ring-blue-500/10
                                                @error('nssfRates')
                                                    border-red-400 ring-1 ring-red-400
                                                @enderror"
                                        />

                                    </div>

                                    @error('nssfRates')
                                        <p class="mt-1 text-[9px] text-red-600">
                                            {{ $message }}
                                        </p>
                                    @enderror

                                </td>


                                {{-- SHA --}}
                                <td class="px-1.5 py-2.5">

                                    <div class="relative">

                                        <span
                                            class="pointer-events-none absolute inset-y-0 left-0 flex items-center
                                                pl-2 text-[9px] font-semibold text-slate-400"
                                        >
                                            KES
                                        </span>

                                        <input
                                            type="number"
                                            min="0"
                                            step="any"
                                            wire:model.live.debounce.300ms="shaRates"
                                            placeholder="0.00"
                                            class="w-[105px] rounded-md border border-slate-200 bg-white
                                                py-1.5 pl-9 pr-1.5 text-[11px] text-slate-700
                                                placeholder:text-slate-300
                                                focus:border-blue-500 focus:outline-none focus:ring-2
                                                focus:ring-blue-500/10
                                                @error('shaRates')
                                                    border-red-400 ring-1 ring-red-400
                                                @enderror"
                                        />

                                    </div>

                                    @error('shaRates')
                                        <p class="mt-1 text-[9px] text-red-600">
                                            {{ $message }}
                                        </p>
                                    @enderror

                                </td>


                                {{-- ESTIMATED TOTAL --}}
                                <td class="px-2.5 py-2.5 whitespace-nowrap">

                                    @if(!empty($rates[$req->id]) && is_numeric($rates[$req->id]))

                                        <span class="font-semibold tabular-nums text-emerald-600">

                                            KES
                                            {{ number_format(
                                                (
                                                    ((float) $rates[$req->id]
                                                    * (int) $req->no_of_casuals
                                                    * (int) $req->duration)
                                                    -
                                                    (
                                                        ((float) $nssfRates + (float) $shaRates)
                                                        * (int) $req->no_of_casuals
                                                    )
                                                )
                                            ) }}

                                        </span>

                                    @else

                                        <span class="text-slate-400">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- ACTION --}}
                                <td class="px-2 py-2.5 whitespace-nowrap text-center">

                                    <button
                                        type="button"
                                        wire:click="submitRate({{ $req->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="submitRate({{ $req->id }})"
                                        title="Submit rate"
                                        aria-label="Submit rate"
                                        class="inline-flex h-8 w-8 items-center justify-center
                                            rounded-lg border border-emerald-200 bg-white
                                            text-emerald-600 transition
                                            hover:bg-emerald-50 hover:text-emerald-700
                                            focus:outline-none focus:ring-2
                                            focus:ring-emerald-500/20
                                            disabled:cursor-not-allowed disabled:opacity-50"
                                    >

                                        {{-- CHECK --}}
                                        <svg
                                            wire:loading.remove
                                            wire:target="submitRate({{ $req->id }})"
                                            class="h-4 w-4"
                                            viewBox="0 0 20 20"
                                            fill="currentColor"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M16.704 4.884a1 1 0 01.012 1.414l-8.25 8.25a1 1 0 01-1.414 0l-3.75-3.75a1 1 0 011.414-1.414l3.043 3.043 7.543-7.543a1 1 0 011.402 0z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>

                                        {{-- LOADING --}}
                                        <svg
                                            wire:loading
                                            wire:target="submitRate({{ $req->id }})"
                                            class="h-4 w-4 animate-spin"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9
                                                m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                                            />
                                        </svg>

                                    </button>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="9" class="px-4 py-16 text-center">

                                    <div class="flex flex-col items-center justify-center">

                                        <div
                                            class="mb-3 flex h-10 w-10 items-center justify-center
                                                rounded-full bg-slate-100"
                                        >
                                            <svg
                                                class="h-5 w-5 text-slate-400"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.707a1 1 0 00-1.414-1.414L9 11.172
                                                    7.707 9.879a1 1 0 001.414 1.414l2 2a1 1 0 001.414 0l3.414-3.414z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                        </div>

                                        <p class="font-medium text-slate-700">
                                            No pending requisitions
                                        </p>

                                        <p class="mt-1 text-[11px] text-slate-400">
                                            All requisitions have been processed
                                        </p>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            {{-- =========================================================
                MOBILE / TABLET CARDS
            ========================================================= --}}
            <div class="lg:hidden divide-y divide-slate-100">

                @forelse($this->hrRequisitions as $req)

                    <div
                        wire:key="mobile-hr-req-{{ $req->id }}"
                        class="p-4"
                    >

                        {{-- =================================================
                            HEADER
                        ================================================== --}}
                        <div class="flex items-start justify-between gap-3">

                            <div class="flex min-w-0 items-center gap-2.5">

                                {{-- Initial --}}
                                <div
                                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full
                                        bg-slate-100 text-xs font-semibold text-slate-600"
                                >
                                    {{ strtoupper(substr($req->requester->name ?? 'U', 0, 1)) }}
                                </div>


                                {{-- Requester --}}
                                <div class="min-w-0">

                                    <p class="truncate text-sm font-semibold text-slate-800">
                                        {{ $req->requester->name ?? 'Unknown User' }}
                                    </p>

                                    <p class="mt-0.5 truncate text-[10px] text-blue-600">
                                        {{ $req->ref_number}}
                                    </p>
                                    

                                </div>

                            </div>


                            {{-- Awaiting --}}
                            <span
                                class="inline-flex shrink-0 items-center gap-1.5 text-[10px]
                                    font-medium text-amber-700"
                            >
                                <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                Awaiting
                            </span>

                        </div>


                        {{-- =================================================
                            SUMMARY
                        ================================================== --}}
                        <div class="mt-4 grid grid-cols-2 gap-2">

                            {{-- Casuals --}}
                            <div class="rounded-lg border border-slate-100 bg-slate-50/70 p-2.5">

                                <p class="text-[10px] font-medium uppercase tracking-wide text-slate-400">
                                    Casuals
                                </p>

                                <p class="mt-1 text-xs font-semibold text-slate-700">
                                    {{ number_format($req->no_of_casuals) }}
                                </p>

                            </div>


                            {{-- Duration --}}
                            <div class="rounded-lg border border-slate-100 bg-slate-50/70 p-2.5">

                                <p class="text-[10px] font-medium uppercase tracking-wide text-slate-400">
                                    Duration
                                </p>

                                <p class="mt-1 text-xs font-semibold text-slate-700">

                                    {{ $req->duration }}

                                    <span class="font-normal text-slate-400">
                                        {{ $req->duration == 1 ? 'day' : 'days' }}
                                    </span>

                                </p>

                            </div>

                        </div>


                        {{-- =================================================
                            RATE INPUTS
                        ================================================== --}}
                        <div class="mt-4 space-y-3">

                            {{-- Daily Rate --}}
                            <div>

                                <label
                                    class="mb-1 block text-[10px] font-semibold uppercase
                                        tracking-wide text-slate-400"
                                >
                                    Daily Rate
                                </label>

                                <div class="relative">

                                    <span
                                        class="pointer-events-none absolute inset-y-0 left-0 flex
                                            items-center pl-3 text-[10px] font-semibold text-slate-400"
                                    >
                                        KES
                                    </span>

                                    <input
                                        type="number"
                                        min="0"
                                        step="any"
                                        wire:model.live.debounce.300ms="rates.{{ $req->id }}"
                                        placeholder="0.00"
                                        class="w-full rounded-lg border border-slate-200 bg-white
                                            py-2.5 pl-11 pr-3 text-xs text-slate-700
                                            placeholder:text-slate-300
                                            focus:border-blue-500 focus:outline-none
                                            focus:ring-2 focus:ring-blue-500/10
                                            @error('rates.'.$req->id)
                                                border-red-400 ring-1 ring-red-400
                                            @enderror"
                                    />

                                </div>

                                @error("rates.$req->id")
                                    <p class="mt-1 text-[10px] text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>


                            {{-- NSSF + SHA --}}
                            <div class="grid grid-cols-2 gap-2">

                                {{-- NSSF --}}
                                <div>

                                    <label
                                        class="mb-1 block text-[10px] font-semibold uppercase
                                            tracking-wide text-slate-400"
                                    >
                                        NSSF
                                    </label>

                                    <div class="relative">

                                        <span
                                            class="pointer-events-none absolute inset-y-0 left-0 flex
                                                items-center pl-3 text-[10px] font-semibold text-slate-400"
                                        >
                                            KES
                                        </span>

                                        <input
                                            type="number"
                                            min="0"
                                            step="any"
                                            wire:model.live.debounce.300ms="nssfRates"
                                            placeholder="0.00"
                                            class="w-full rounded-lg border border-slate-200 bg-white
                                                py-2.5 pl-11 pr-2 text-xs text-slate-700
                                                placeholder:text-slate-300
                                                focus:border-blue-500 focus:outline-none
                                                focus:ring-2 focus:ring-blue-500/10
                                                @error('nssfRates')
                                                    border-red-400 ring-1 ring-red-400
                                                @enderror"
                                        />

                                    </div>

                                    @error('nssfRates')
                                        <p class="mt-1 text-[10px] text-red-600">
                                            {{ $message }}
                                        </p>
                                    @enderror

                                </div>


                                {{-- SHA --}}
                                <div>

                                    <label
                                        class="mb-1 block text-[10px] font-semibold uppercase
                                            tracking-wide text-slate-400"
                                    >
                                        SHA
                                    </label>

                                    <div class="relative">

                                        <span
                                            class="pointer-events-none absolute inset-y-0 left-0 flex
                                                items-center pl-3 text-[10px] font-semibold text-slate-400"
                                        >
                                            KES
                                        </span>

                                        <input
                                            type="number"
                                            min="0"
                                            step="any"
                                            wire:model.live.debounce.300ms="shaRates"
                                            placeholder="0.00"
                                            class="w-full rounded-lg border border-slate-200 bg-white
                                                py-2.5 pl-11 pr-2 text-xs text-slate-700
                                                placeholder:text-slate-300
                                                focus:border-blue-500 focus:outline-none
                                                focus:ring-2 focus:ring-blue-500/10
                                                @error('shaRates')
                                                    border-red-400 ring-1 ring-red-400
                                                @enderror"
                                        />

                                    </div>

                                    @error('shaRates')
                                        <p class="mt-1 text-[10px] text-red-600">
                                            {{ $message }}
                                        </p>
                                    @enderror

                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                            TOTAL + ACTION
                        ================================================== --}}
                        <div
                            class="mt-4 flex items-center justify-between gap-3
                                border-t border-slate-100 pt-3"
                        >

                            {{-- Estimated Total --}}
                            <div class="min-w-0">

                                <p class="text-[10px] font-medium uppercase tracking-wide text-slate-400">
                                    Estimated Total
                                </p>

                                @if(!empty($rates[$req->id]) && is_numeric($rates[$req->id]))

                                    <p class="mt-0.5 truncate text-sm font-semibold text-emerald-600">

                                        KES
                                        {{ number_format(
                                            (
                                                ((float) $rates[$req->id]
                                                * (int) $req->no_of_casuals
                                                * (int) $req->duration)
                                                -
                                                (
                                                    ((float) $nssfRates + (float) $shaRates)
                                                    * (int) $req->no_of_casuals
                                                )
                                            )
                                        ) }}

                                    </p>

                                @else

                                    <p class="mt-0.5 text-sm italic text-slate-400">
                                        —
                                    </p>

                                @endif

                            </div>


                            {{-- Submit --}}
                            <button
                                type="button"
                                wire:click="submitRate({{ $req->id }})"
                                wire:loading.attr="disabled"
                                wire:target="submitRate({{ $req->id }})"
                                class="inline-flex h-9 shrink-0 items-center justify-center
                                    rounded-lg border border-emerald-200 bg-white px-3
                                    text-xs font-medium text-emerald-600
                                    transition hover:bg-emerald-50 hover:text-emerald-700
                                    focus:outline-none focus:ring-2 focus:ring-emerald-500/20
                                    disabled:cursor-not-allowed disabled:opacity-50"
                            >

                                <svg
                                    wire:loading.remove
                                    wire:target="submitRate({{ $req->id }})"
                                    class="mr-1.5 h-4 w-4"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M16.704 4.884a1 1 0 01.012 1.414l-8.25 8.25a1 1 0 01-1.414 0l-3.75-3.75a1 1 0 011.414-1.414l3.043 3.043 7.543-7.543a1 1 0 011.402 0z"
                                        clip-rule="evenodd"
                                    />
                                </svg>

                                <svg
                                    wire:loading
                                    wire:target="submitRate({{ $req->id }})"
                                    class="mr-1.5 h-4 w-4 animate-spin"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9
                                        m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                                    />
                                </svg>

                                <span
                                    wire:loading.remove
                                    wire:target="submitRate({{ $req->id }})"
                                >
                                    Submit
                                </span>

                                <span
                                    wire:loading
                                    wire:target="submitRate({{ $req->id }})"
                                >
                                    Saving…
                                </span>

                            </button>

                        </div>

                    </div>

                @empty

                    <div class="px-4 py-16 text-center">

                        <div class="flex flex-col items-center justify-center">

                            <div
                                class="mb-3 flex h-10 w-10 items-center justify-center rounded-full
                                    bg-slate-100"
                            >
                                <svg
                                    class="h-5 w-5 text-slate-400"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.707a1 1 0 00-1.414-1.414L9 11.172
                                        7.707 9.879a1 1 0 001.414 1.414l2 2a1 1 0 001.414 0l3.414-3.414a1 1 0 00-1.414 0z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </div>

                            <p class="font-medium text-slate-700">
                                No pending requisitions
                            </p>

                            <p class="mt-1 text-[11px] text-slate-400">
                                All requisitions have been processed
                            </p>

                        </div>

                    </div>

                @endforelse

            </div>

        </div>


        {{-- =========================================================
            PAGINATION
        ========================================================= --}}
        @if($this->hrRequisitions->hasPages())

            <div class="border-t border-slate-100 px-4 py-3 sm:px-6">
                {{ $this->hrRequisitions->links() }}
            </div>

        @endif
</div>

@elseif($view === 'casual_management')

<div class="space-y-6">

    {{-- ===== Device Inventory Card ===== --}}
    <x-data-card title="Casual Staff" subtitle="All casual staff records in the system">
        <x-slot name="actions">
            
            {{-- Search + filter --}}
            <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                {{-- Add Casual Button --}}
                <div class="md:col-span-4"
                    x-cloak
                    x-data="{ isOpen: false }"
                    @close-casual-modal.window="isOpen = false"
                    x-init="$watch('isOpen', val => document.body.classList.toggle('overflow-hidden', val))"
                >

                    <!-- Trigger Button -->
                    <button
                        @click="isOpen = true"
                        class="w-full p-2 bg-gradient-to-r from-emerald-500 to-green-600
                            hover:from-emerald-600 hover:to-green-700 text-white rounded-xl
                            font-medium text-sm transition-all duration-200 shadow-md hover:shadow-lg
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
                            class="relative w-full max-w-md max-h-[90vh] overflow-hidden"
                        >

                            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">

                                <!-- Modal Header -->
                                <div class="shrink-0 px-6 py-5 bg-gradient-to-r from-emerald-50 to-green-50
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
                                            type="button"
                                            @click="isOpen = false"
                                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300
                                                hover:bg-gray-100 dark:hover:bg-gray-700
                                                rounded-full w-8 h-8 flex items-center justify-center
                                                transition-colors"
                                        >
                                            <svg class="w-5 h-5"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24">

                                                <path stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>

                                    </div>
                                </div>


                                <!-- Scrollable Modal Content -->
                                <div class="flex-1 min-h-0 overflow-y-auto">
                                    <!-- Modal Form -->
                                    <form wire:submit.prevent="addCasual" class="p-6 space-y-7">

                                        <!-- Personal Details -->
                                        <div class="space-y-5">
                                            <div class="flex items-center gap-3 pb-1 border-b border-gray-200">
                                                <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0z
                                                                M12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                </div>

                                                <div>
                                                    <h4 class="text-base font-semibold text-gray-900">
                                                        Personal Details
                                                    </h4>
                                                    <p class="text-xs text-gray-500">
                                                        Enter the casual staff member's personal information.
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                                                <!-- First Name -->
                                                <div>
                                                    <label for="first_name"
                                                        class="block text-sm font-medium text-gray-700">
                                                        First Name <span class="text-red-500">*</span>
                                                    </label>

                                                    <input
                                                        id="first_name"
                                                        type="text"
                                                        wire:model="fname"
                                                        autocomplete="given-name"
                                                        class="w-full p-2 rounded-lg border border-gray-300
                                                            bg-white text-gray-900 placeholder-gray-400
                                                            focus:ring-2 focus:ring-emerald-500/20
                                                            focus:border-emerald-500 transition"
                                                        placeholder="Enter first name"
                                                    >

                                                    @error('fname')
                                                        <span class="mt-1 block text-sm text-red-500">
                                                            {{ $message }}
                                                        </span>
                                                    @enderror
                                                </div>

                                                <!-- Last Name -->
                                                <div>
                                                    <label for="last_name"
                                                        class="block text-sm font-medium text-gray-700">
                                                        Last Name <span class="text-red-500">*</span>
                                                    </label>

                                                    <input
                                                        id="last_name"
                                                        type="text"
                                                        wire:model="lname"
                                                        autocomplete="family-name"
                                                        class="w-full p-2 rounded-lg border border-gray-300
                                                            bg-white text-gray-900 placeholder-gray-400
                                                            focus:ring-2 focus:ring-emerald-500/20
                                                            focus:border-emerald-500 transition"
                                                        placeholder="Enter last name"
                                                    >

                                                    @error('lname')
                                                        <span class="mt-1 block text-sm text-red-500">
                                                            {{ $message }}
                                                        </span>
                                                    @enderror
                                                </div>

                                            </div>

                                            <!-- Casual Phone Number -->
                                            <div>
                                                <label for="phone_number"
                                                    class="block text-sm font-medium text-gray-700">
                                                    Phone Number <span class="text-red-500">*</span>
                                                </label>

                                                <div class="mt-1 flex rounded-lg border border-gray-300 bg-white
                                                            focus-within:ring-2 focus-within:ring-emerald-500/20
                                                            focus-within:border-emerald-500 transition overflow-hidden">

                                                    <!-- Country Prefix -->
                                                    <span class="inline-flex items-center px-3
                                                                bg-gray-50 text-gray-700 text-sm font-medium
                                                                border-r border-gray-300">
                                                        254
                                                    </span>

                                                    <!-- Phone Number -->
                                                    <input
                                                        id="phone_number"
                                                        type="tel"
                                                        wire:model="phone_number"
                                                        inputmode="numeric"
                                                        autocomplete="tel"
                                                        maxlength="9"
                                                        pattern="[7][0-9]{8}"
                                                        class="flex-1 p-2 border-0 outline-none
                                                            bg-white text-gray-900 placeholder-gray-400
                                                            focus:ring-0"
                                                        placeholder="7** *** ***"
                                                    >
                                                </div>

                                                @error('phone_number')
                                                    <span class="mt-1 block text-sm text-red-500">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>

                                        </div>


                                        <!-- Government Details -->
                                        <div class="space-y-5">

                                            <div class="flex items-center gap-3 border-b border-gray-200">
                                                <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-blue-50 text-blue-600">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586
                                                                a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19
                                                                a2 2 0 01-2 2z" />
                                                    </svg>
                                                </div>

                                                <div>
                                                    <h4 class="text-base font-semibold text-gray-900">
                                                        Government Details
                                                    </h4>
                                                    <p class="text-xs text-gray-500">
                                                        Provide the staff member's statutory identification details.
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                                                <!-- ID Number -->
                                                <div>
                                                    <label for="id_number"
                                                        class="block text-sm font-medium text-gray-700">
                                                        ID Number <span class="text-red-500">*</span>
                                                    </label>

                                                    <input
                                                        id="id_number"
                                                        type="text"
                                                        wire:model="id_number"
                                                        inputmode="numeric"
                                                        autocomplete="off"
                                                        class="w-full p-2 rounded-lg border border-gray-300
                                                            bg-white text-gray-900 placeholder-gray-400
                                                            focus:ring-2 focus:ring-emerald-500/20
                                                            focus:border-emerald-500 transition"
                                                        placeholder="Enter ID number"
                                                    >

                                                    @error('id_number')
                                                        <span class="mt-1 block text-sm text-red-500">
                                                            {{ $message }}
                                                        </span>
                                                    @enderror
                                                </div>

                                                <!-- SHA Number -->
                                                <div>
                                                    <label for="sha_number"
                                                        class="block text-sm font-medium text-gray-700">
                                                        SHA Number <span class="text-red-500">*</span>
                                                    </label>

                                                    <input
                                                        id="sha_number"
                                                        type="text"
                                                        wire:model="sha_number"
                                                        inputmode="numeric"
                                                        autocomplete="off"
                                                        class="w-full p-2 rounded-lg border border-gray-300
                                                            bg-white text-gray-900 placeholder-gray-400
                                                            focus:ring-2 focus:ring-emerald-500/20
                                                            focus:border-emerald-500 transition"
                                                        placeholder="Enter SHA number"
                                                    >

                                                    @error('sha_number')
                                                        <span class="mt-1 block text-sm text-red-500">
                                                            {{ $message }}
                                                        </span>
                                                    @enderror
                                                </div>

                                            </div>
                                            <div>
                                                <label for="nssf_number"
                                                    class="block text-sm font-medium text-gray-700">
                                                    NSSF Number <span class="text-red-500">*</span>
                                                </label>

                                                <input
                                                    id="nssf_number"
                                                    type="text"
                                                    wire:model="nssf_number"
                                                    inputmode="numeric"
                                                    autocomplete="off"
                                                    class="w-full p-2 rounded-lg border border-gray-300
                                                        bg-white text-gray-900 placeholder-gray-400
                                                        focus:ring-2 focus:ring-emerald-500/20
                                                        focus:border-emerald-500 transition"
                                                    placeholder="Enter NSSF number"
                                                >

                                                @error('nssf_number')
                                                    <span class="mt-1 block text-sm text-red-500">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>


                                        <!-- Next of Kin Details -->
                                        <div class="space-y-5">

                                            <div class="flex items-center gap-3 border-b border-gray-200">
                                                <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-purple-50 text-purple-600">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857
                                                                M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857
                                                                M7 20H2v-2a3 3 0 015.356-1.857
                                                                M7 20v-2c0-.656.126-1.283.356-1.857
                                                                m0 0a5.002 5.002 0 019.288 0
                                                                M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0
                                                                2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                    </svg>
                                                </div>

                                                <div>
                                                    <h4 class="text-base font-semibold text-gray-900">
                                                        Next of Kin Details
                                                    </h4>
                                                    <p class="text-xs text-gray-500">
                                                        Provide the emergency contact information.
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                                                <!-- Next of Kin First Name -->
                                                <div>
                                                    <label for="next_of_kin_first_name"
                                                        class="block text-sm font-medium text-gray-700">
                                                        First Name <span class="text-red-500">*</span>
                                                    </label>

                                                    <input
                                                        id="next_of_kin_first_name"
                                                        type="text"
                                                        wire:model="nfname"
                                                        autocomplete="given-name"
                                                        class="w-full p-2 rounded-lg border border-gray-300
                                                            bg-white text-gray-900 placeholder-gray-400
                                                            focus:ring-2 focus:ring-emerald-500/20
                                                            focus:border-emerald-500 transition"
                                                        placeholder="Enter first name"
                                                    >

                                                    @error('nfname')
                                                        <span class="mt-1 block text-sm text-red-500">
                                                            {{ $message }}
                                                        </span>
                                                    @enderror
                                                </div>

                                                <!-- Next of Kin Last Name -->
                                                <div>
                                                    <label for="next_of_kin_last_name"
                                                        class="block text-sm font-medium text-gray-700">
                                                        Last Name <span class="text-red-500">*</span>
                                                    </label>

                                                    <input
                                                        id="next_of_kin_last_name"
                                                        type="text"
                                                        wire:model="nlname"
                                                        autocomplete="family-name"
                                                        class="w-full p-2 rounded-lg border border-gray-300
                                                            bg-white text-gray-900 placeholder-gray-400
                                                            focus:ring-2 focus:ring-emerald-500/20
                                                            focus:border-emerald-500 transition"
                                                        placeholder="Enter last name"
                                                    >

                                                    @error('nlname')
                                                        <span class="mt-1 block text-sm text-red-500">
                                                            {{ $message }}
                                                        </span>
                                                    @enderror
                                                </div>

                                            </div>

                                            <!-- Next of Kin Phone Number -->
                                            <div>
                                                <label for="next_of_kin_phone"
                                                    class="block text-sm font-medium text-gray-700">
                                                    Phone Number <span class="text-red-500">*</span>
                                                </label>

                                                <div class="mt-1 flex rounded-lg border border-gray-300 bg-white
                                                            focus-within:ring-2 focus-within:ring-emerald-500/20
                                                            focus-within:border-emerald-500 transition overflow-hidden">

                                                    <!-- Country Prefix -->
                                                    <span class="inline-flex items-center px-3
                                                                bg-gray-50 text-gray-700 text-sm font-medium
                                                                border-r border-gray-300">
                                                        254
                                                    </span>

                                                    <!-- Phone Number -->
                                                    <input
                                                        id="next_of_kin_phone"
                                                        type="tel"
                                                        wire:model="nphone_number"
                                                        inputmode="numeric"
                                                        autocomplete="tel"
                                                        maxlength="9"
                                                        pattern="[7][0-9]{8}"
                                                        class="flex-1 p-2 border-0 outline-none
                                                            bg-white text-gray-900 placeholder-gray-400
                                                            focus:ring-0"
                                                        placeholder="7** *** ***"
                                                    >
                                                </div>

                                                @error('nphone_number')
                                                    <span class="mt-1 block text-sm text-red-500">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>

                                        </div>


                                        <!-- Submit -->
                                        <div class="pt-2 border-t border-gray-200">

                                            <button
                                                type="submit"
                                                wire:loading.attr="disabled"
                                                wire:target="addCasual"
                                                class="w-full p-2
                                                    bg-gradient-to-r from-emerald-500 to-green-600
                                                    hover:from-emerald-600 hover:to-green-700
                                                    disabled:opacity-60 disabled:cursor-not-allowed
                                                    text-white font-semibold rounded-lg
                                                    shadow-sm hover:shadow-md
                                                    transition-all duration-200
                                                    flex items-center justify-center gap-2">

                                                <!-- Normal State -->
                                                <span wire:loading.remove wire:target="addCasual"
                                                    class="flex items-center justify-center gap-2">

                                                    <svg class="w-5 h-5"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M5 13l4 4L19 7" />
                                                    </svg>

                                                    Add Casual Staff
                                                </span>

                                                <!-- Loading State -->
                                                <span wire:loading wire:target="addCasual"
                                                    class="flex items-center justify-center gap-2">

                                                    <svg class="w-5 h-5 animate-spin"
                                                        fill="none"
                                                        viewBox="0 0 24 24">
                                                        <circle class="opacity-25"
                                                                cx="12"
                                                                cy="12"
                                                                r="10"
                                                                stroke="currentColor"
                                                                stroke-width="4">
                                                        </circle>

                                                        <path class="opacity-75"
                                                            fill="currentColor"
                                                            d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                                                        </path>
                                                    </svg>

                                                    Adding Casual Staff...
                                                </span>

                                            </button>

                                        </div>

                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="relative flex-1 sm:w-72">
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/>
                    </svg>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search devices…"
                        class="w-full pl-10 pr-3 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                </div>

                @if($search)
                    <button wire:click="$set('search', ''); $set('actionFilter', '')"
                        class="inline-flex items-center justify-center gap-1 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-medium text-slate-600 hover:bg-slate-50">
                        Clear
                    </button>
                @endif
            </div>
        </x-slot>
    

        {{-- ===== DESKTOP TABLE (lg and up) ===== --}}
        <div class="hidden lg:block overflow-x-auto">
            <table class="w-full text-xs">
                <thead class="bg-gray-50 text-left uppercase tracking-wider text-[11px] text-gray-500">
                    <tr>
                        <th class="px-3 py-2">Casual Name</th>
                        <th class="px-3 py-2">Id Number</th>
                        <th class="px-3 py-2 hidden xl:table-cell">NSSF Number</th>
                        <th class="px-3 py-2 hidden xl:table-cell">SHA Number</th>
                        <th class="px-3 py-2 hidden xl:table-cell">Phone Number</th>
                        <th class="px-3 py-2">Next of Kin Name</th>
                        <th class="px-3 py-2 hidden xl:table-cell">Next of Kin Phone</th>
                        <th class="px-3 py-2">Status</th>
                        <th class="px-3 py-2 text-right">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse($this->casualsData as $casual)
                        <tr class="hover:bg-gray-50 transition">
                            
                            {{-- Casual Name --}}
                            <td class="px-3 py-2">
                                <p class="font-medium text-gray-900 truncate max-w-[140px]">
                                    {{ $casual->name }}
                                </p>
                            </td>

                            {{-- Casual Id Number --}}
                            <td class="px-3 py-2 capitalize text-gray-700">
                                {{ $casual->id_number }}
                            </td>

                            {{-- NSSF Number --}}
                            <td class="px-3 py-2 hidden xl:table-cell text-gray-500">
                                {{ $casual->nssf_number }}
                            </td>

                            {{-- SHA Number --}}
                            <td class="px-3 py-2 hidden xl:table-cell text-gray-700">
                                {{ $casual->sha_number }}
                            </td>

                            {{-- Casual Phone Number --}}
                            <td class="px-3 py-2 hidden xl:table-cell text-gray-700 truncate max-w-[120px]">
                                {{ $casual->phone_number }}
                            </td>

                            {{-- Next of Kin Name --}}
                            <td class="px-3 py-2">
                                <span class="font-mono text-[10px] px-1.5 py-0.5 rounded bg-gray-100 text-gray-700">
                                    {{ $casual->n_name }}
                                </span>
                            </td>

                            {{-- Next of Kin Phone Number --}}
                            <td class="px-3 py-2 hidden xl:table-cell font-mono text-[10px] text-gray-500">
                                {{ $casual->n_phone }}
                            </td>

                            {{-- Status --}}
                            <td class="px-3 py-2">
                                @if($casual->is_active)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-medium rounded-full bg-green-100 text-green-700">
                                        <span class="w-1 h-1 bg-green-500 rounded-full"></span> Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-medium rounded-full bg-rose-100 text-rose-700">
                                        <span class="w-1 h-1 bg-rose-500 rounded-full"></span> Inactive
                                    </span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="px-3 py-2 text-right">
                                actions
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-12 text-center">
                                <div class="flex flex-col items-center gap-2 text-gray-500">
                                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0l-2 7H6l-2-7m16 0H4"/>
                                    </svg>
                                    <p class="font-medium text-gray-700 text-sm">No casual workers found</p>
                                    <p class="text-[11px]">Try adjusting your search or add a new casual worker</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

            {{-- ===== MOBILE / TABLET CARDS (below lg) ===== --}}
            <div class="lg:hidden divide-y divide-gray-100">
                @forelse ($this->casualsData as $casual)
                    <div class="p-4 hover:bg-gray-50 transition">
                        {{-- Header row --}}
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 text-white flex items-center justify-center font-semibold text-sm shrink-0">
                                    {{ strtoupper(substr($casual->name, 0, 2)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="font-semibold text-gray-900 truncate">{{ $casual->name }}</p>
                                    <p class="text-xs text-gray-500 capitalize">{{ $casual->id_number }}</p>
                                </div>
                            </div>
                            @if($casual->is_active)
                                <span class="shrink-0 inline-flex items-center gap-1.5 px-2 py-0.5 text-xs font-medium rounded-full bg-green-100 text-green-700">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span> Active
                                </span>
                            @else
                                <span class="shrink-0 inline-flex items-center gap-1.5 px-2 py-0.5 text-xs font-medium rounded-full bg-rose-100 text-rose-700">
                                    <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span> Inactive
                                </span>
                            @endif
                        </div>

                        {{-- Detail grid --}}
                        <dl class="grid grid-cols-2 gap-x-4 gap-y-2 text-xs">
                            <div>
                                <dt class="text-gray-500">SHA Number</dt>
                                <dd class="font-medium text-gray-900">{{ $casual->sha_number }}</dd>
                            </div>
                            <div>
                                <dt class="text-gray-500">NSSF Number</dt>
                                <dd class="font-mono text-gray-700">{{ $casual->nssf_number }}</dd>
                            </div>
                            <div>
                                <dt class="text-gray-500">Phone</dt>
                                <dd class="text-gray-700 truncate">{{ $casual->phone_number ?? 'Not specified' }}</dd>
                            </div>
                            <div>
                                <dt class="text-gray-500">Next of Kin Name</dt>
                                <dd class="text-gray-700 truncate">{{ $casual->n_name ?? 'Not specified' }}</dd>
                            </div>
                            <div class="col-span-2">
                                <dt class="text-gray-500">Next of Kin Phone</dt>
                                <dd class="font-mono text-gray-700 truncate">{{ $casual->n_phone ?? 'Not specified' }}</dd>
                            </div>
                        </dl>

                        {{-- Actions --}}
                        <div class="mt-3 pt-3 border-t border-gray-100 flex justify-end">
                            actions
                        </div>
                    </div>
                @empty
                    <div class="py-16 text-center">
                        <p class="font-medium text-gray-700">No casuals found</p>
                        <p class="text-xs text-gray-500 mt-1">Try adjusting your search or add a new casual</p>
                    </div>
                @endforelse
            </div>

            @if($this->casualsData->hasPages())
                <div class="px-4 sm:px-6 py-4 border-t border-gray-100">
                    {{ $this->casualsData->links() }}
                </div>
            @endif
    </x-data-card>

   
</div>

   
@elseif($view === 'Print_Casual_Contracts')

    {{-- ===== DESKTOP TABLE (lg and up) ===== --}}
    <div class="hidden lg:block overflow-x-auto">
        <table class="w-full text-xs">
            <thead class="border-b border-gray-200 bg-gray-50">
                <tr class="text-left text-[11px] font-medium uppercase tracking-wide text-gray-500">
                    <th class="px-3 py-2.5 whitespace-nowrap">#</th>
                    <th class="px-3 py-2.5 whitespace-nowrap">Requisition No.</th>
                    <th class="px-3 py-2.5 whitespace-nowrap">Department</th>
                    <th class="px-3 py-2.5 whitespace-nowrap">Casuals Assigned</th>
                    <th class="px-3 py-2.5 whitespace-nowrap">Status</th>
                    <th class="px-3 py-2.5 whitespace-nowrap text-right">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                @forelse($this->AssignedRequisitions as $req)

                    <tr class="align-middle transition hover:bg-gray-50">

                        {{-- # --}}
                        <td class="px-3 py-3 text-gray-400 tabular-nums">
                            {{ $loop->iteration }}
                        </td>

                        {{-- Requisition Number --}}
                        <td class="px-3 py-3 whitespace-nowrap">
                            <span class="font-medium text-blue-600">
                                {{ $req->ref_number }}
                            </span>
                        </td>

                        {{-- Department / Requester --}}
                        <td class="px-3 py-3">
                            <p class="font-medium text-gray-900">
                                {{ $req->requester->name ?? 'N/A' }}
                            </p>
                        </td>

                        {{-- Casuals Assigned --}}
                        <td class="px-3 py-3 whitespace-nowrap">
                            <span
                                class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-medium
                                {{ $req->casual_assignments_count > 0
                                    ? 'bg-green-50 text-green-700'
                                    : 'bg-yellow-50 text-yellow-700' }}"
                            >
                                {{ $req->casual_assignments_count ?? 0 }}
                                /
                                {{ $req->no_of_casuals }}
                            </span>
                        </td>

                        {{-- Status --}}
                        <td class="px-3 py-3 whitespace-nowrap">
                            @if($req->coo_approval_status && $req->casual_assignment_status)

                                <span class="inline-flex items-center gap-1.5 text-[11px] font-medium text-blue-600">
                                    <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                                    Assigned
                                </span>

                            @elseif($req->coo_approval_status)

                                <span class="inline-flex items-center gap-1.5 text-[11px] font-medium text-green-600">
                                    <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                    Approved
                                </span>

                            @else

                                <span class="inline-flex items-center gap-1.5 text-[11px] font-medium text-yellow-600">
                                    <span class="h-1.5 w-1.5 rounded-full bg-yellow-500"></span>
                                    Pending
                                </span>

                            @endif
                        </td>

                        {{-- Actions --}}
                        <td class="px-3 py-2.5">
                            <div class="flex items-center justify-end gap-1.5">

                                @if($req->casual_assignments_count > 0)
                                    <a
                                        href="{{ route('contracts.print', ['requisitionId' => $req->uuid]) }}"
                                        target="_blank"
                                        rel="noopener"
                                        title="Print Contracts"
                                        class="inline-flex h-7 items-center gap-1 rounded-md
                                            border border-blue-200 bg-blue-50 px-2
                                            text-[11px] font-medium text-blue-600
                                            transition hover:bg-blue-100"
                                    >
                                        <svg
                                            class="h-3.5 w-3.5"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"
                                            />
                                        </svg>
                                        Print
                                    </a>
                                @endif

                                <a
                                    href="#"
                                    class="inline-flex h-7 items-center rounded-md
                                        border border-gray-200 bg-white px-2
                                        text-[11px] font-medium text-gray-600
                                        transition hover:bg-gray-50"
                                >
                                    View
                                </a>

                            </div>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center">

                            <svg
                                class="mx-auto h-10 w-10 text-gray-300"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                />
                            </svg>

                            <p class="mt-2 text-sm font-medium text-gray-600">
                                No requisitions with assigned casuals found
                            </p>

                            <p class="mt-1 text-xs text-gray-400">
                                Approved requisitions with casual assignments will appear here
                            </p>

                        </td>
                    </tr>

                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ===== MOBILE VIEW (below lg) ===== --}}
    <div class="lg:hidden space-y-2.5">

        @forelse($this->AssignedRequisitions as $req)

            <div
                wire:key="mobile-assigned-req-{{ $req->id }}"
                class="rounded-lg border border-gray-200 bg-white p-3"
            >

                {{-- Header --}}
                <div class="flex items-start justify-between gap-3">

                    <div class="min-w-0">
                        <p class="text-[10px] font-medium uppercase tracking-wide text-gray-400">
                            Requisition
                        </p>

                        <p class="mt-0.5 truncate text-sm font-semibold text-blue-600">
                            {{ $req->ref_number }}
                        </p>
                    </div>

                    {{-- Status --}}
                    <div class="shrink-0">
                        @if($req->coo_approval_status && $req->casual_assignment_status)

                            <span class="inline-flex items-center gap-1.5 text-[10px] font-medium text-blue-600">
                                <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                                Assigned
                            </span>

                        @elseif($req->coo_approval_status)

                            <span class="inline-flex items-center gap-1.5 text-[10px] font-medium text-green-600">
                                <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                Approved
                            </span>

                        @else

                            <span class="inline-flex items-center gap-1.5 text-[10px] font-medium text-yellow-600">
                                <span class="h-1.5 w-1.5 rounded-full bg-yellow-500"></span>
                                Pending
                            </span>

                        @endif
                    </div>

                </div>

                {{-- Details --}}
                <div class="mt-3 grid grid-cols-2 gap-x-4 gap-y-2.5">

                    {{-- Requester --}}
                    <div>
                        <p class="text-[10px] uppercase tracking-wide text-gray-400">
                            Requested By
                        </p>

                        <p class="mt-0.5 truncate text-xs font-medium text-gray-800">
                            {{ $req->requester->name ?? 'N/A' }}
                        </p>
                    </div>

                    {{-- Casuals --}}
                    <div>
                        <p class="text-[10px] uppercase tracking-wide text-gray-400">
                            Casuals Assigned
                        </p>

                        <p class="mt-0.5 text-xs font-medium text-gray-800">
                            {{ $req->casual_assignments_count ?? 0 }}
                            /
                            {{ $req->no_of_casuals }}
                        </p>
                    </div>

                </div>

                {{-- Actions --}}
                <div class="mt-3 flex items-center justify-end gap-1.5 border-t border-gray-100 pt-2.5">

                    @if($req->casual_assignments_count > 0)
                        <a
                            href="{{ route('contracts.print', ['requisitionId' => $req->uuid]) }}"
                            target="_blank"
                            rel="noopener"
                            class="inline-flex items-center gap-1.5 rounded-md
                                border border-blue-200 bg-blue-50 px-2.5 py-1.5
                                text-[11px] font-medium text-blue-600
                                transition hover:bg-blue-100"
                        >
                            <svg
                                class="h-3.5 w-3.5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 002 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"
                                />
                            </svg>
                            Print Contracts
                        </a>
                    @endif

                    <a
                        href="#"
                        class="inline-flex items-center rounded-md
                            border border-gray-200 bg-white px-2.5 py-1.5
                            text-[11px] font-medium text-gray-600
                            transition hover:bg-gray-50"
                    >
                        View Details
                    </a>

                </div>

            </div>

        @empty

            <div class="rounded-lg border border-gray-200 bg-white px-4 py-10 text-center">

                <svg
                    class="mx-auto h-10 w-10 text-gray-300"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                    />
                </svg>

                <p class="mt-2 text-sm font-medium text-gray-600">
                    No requisitions with assigned casuals found
                </p>

                <p class="mt-1 text-xs text-gray-400">
                    Approved requisitions with casual assignments will appear here
                </p>

            </div>

        @endforelse

    </div>

    @if($this->AssignedRequisitions->hasPages())
    <div class="px-4 py-3 border-t border-gray-200 sm:px-6">
        {{ $this->AssignedRequisitions->links() }}
    </div>
@endif


@elseif($view === 'hrm_approved')
<div class="space-y-5">
    {{-- ===== Header Card ===== --}}
    <div class="relative overflow-hidden rounded-2xl border border-gray-200/70 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-50 via-indigo-50 to-transparent dark:from-blue-950/30 dark:via-indigo-950/20 dark:to-transparent pointer-events-none"></div>

        <div class="relative flex flex-col gap-4 p-3 sm:flex-row sm:items-center sm:justify-between">
            {{-- Title --}}
            <div class="flex items-start gap-3">
                <div class="hidden sm:flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 text-white shadow-md">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <h2 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white">
                        Approved Requisitions by HRM
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        These are the requisitions that have been approved by the HR Manager and are ready for casual worker assignment.
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
                        Awaiting Casual Assignment
                    </span>
                </div>
            </div>
        </div>
    </div>


    {{-- ===== DATA CONTAINER ===== --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200/70 bg-white shadow-sm">

        {{-- ===== DESKTOP TABLE (lg and up) ===== --}}
        <div class="hidden overflow-x-auto lg:block">
            <table class="w-full text-xs">

                <thead class="border-b border-slate-200 bg-slate-50/80 text-left">
                    <tr>
                        <th class="px-4 py-2.5 text-[10px] font-semibold uppercase tracking-wider text-slate-500">
                            Requisition No
                        </th>
                        <th class="px-4 py-2.5 text-[10px] font-semibold uppercase tracking-wider text-slate-500">
                            Requested By
                        </th>

                        <th class="px-4 py-2.5 text-[10px] font-semibold uppercase tracking-wider text-slate-500">
                            No of Casuals
                        </th>

                        <th class="px-4 py-2.5 text-[10px] font-semibold uppercase tracking-wider text-slate-500">
                            Duration
                        </th>

                        <th class="px-4 py-2.5 text-right text-[10px] font-semibold uppercase tracking-wider text-slate-500">
                            Action
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">

                    @forelse($this->hrRequisitions as $req)

                        <tr class="group align-middle transition-colors hover:bg-slate-50/70">

                            {{-- Requisition No --}}
                            <td class="px-4 py-3 whitespace-nowrap">

                                <div class="flex items-center gap-2.5">


                                    {{-- Name --}}
                                    <div class="min-w-0">
                                        <p class="font-medium text-blue-600">
                                            {{ $req->ref_number }}
                                        </p>
                                    </div>

                                </div>

                            </td>

                            {{-- Requested By --}}
                            <td class="px-4 py-3">
                                <div class="flex min-w-0 items-center gap-3">

                                    <div class="min-w-0">
                                        <p class="truncate font-medium text-slate-800">
                                            {{ $req->requester->name }}
                                        </p>
                                    </div>

                                </div>
                            </td>

                            {{-- Casuals --}}
                            <td class="px-4 py-3">
                                <span class="inline-flex min-w-[2rem] items-center justify-center rounded-lg bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-700">
                                    {{ $req->no_of_casuals }}
                                </span>
                            </td>

                            {{-- Duration --}}
                            <td class="px-4 py-3">
                                <div class="inline-flex items-center gap-1.5 whitespace-nowrap text-slate-600">

                                    <svg
                                        class="h-3.5 w-3.5 shrink-0 text-slate-400"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                        />
                                    </svg>

                                    {{ $req->duration }}
                                    {{ $req->duration == 1 ? 'day' : 'days' }}

                                </div>
                            </td>

                            {{-- Action --}}
                            <td class="px-4 py-3">
                                <div class="flex justify-end">

                                    <button
                                        type="button"
                                        wire:click="assignCasuals({{ $req->id }})"
                                        class="inline-flex items-center gap-1.5 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-xs font-medium text-emerald-700 transition hover:bg-emerald-100"
                                    >
                                        <svg
                                            class="h-3.5 w-3.5"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M12 4.354a4 4 0 110 5.292
                                                M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1
                                                a6 6 0 00-9-5.197
                                                m13.46 5.197a4 4 0 00-5.16-3.754"
                                            />
                                        </svg>

                                        Assign Workers
                                    </button>

                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="4" class="py-16">
                                <div class="flex flex-col items-center gap-3 text-center">

                                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-slate-100">
                                        <svg
                                            class="h-6 w-6 text-slate-400"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                            />
                                        </svg>
                                    </div>

                                    <div>
                                        <p class="text-sm font-semibold text-slate-800">
                                            No Pending Requisitions
                                        </p>

                                        <p class="mt-0.5 text-xs text-slate-400">
                                            All requisitions have been processed
                                        </p>
                                    </div>

                                </div>
                            </td>
                        </tr>

                    @endforelse

                </tbody>
            </table>
        </div>


        {{-- ===== MOBILE / TABLET CARDS ===== --}}
        <div class="divide-y divide-slate-100 lg:hidden">

            @forelse($this->hrRequisitions as $req)

                <div class="p-4">

                    {{-- Requester --}}
                    <div class="mb-3 flex min-w-0 items-center justify-between gap-3">

                        <div class="flex min-w-0 items-center gap-3">

                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-slate-100 text-xs font-semibold text-slate-600">
                                {{ strtoupper(substr($req->requester->name, 0, 1)) }}
                            </div>

                            <div class="min-w-0">
                                <p class="truncate text-sm font-semibold text-slate-800">
                                    {{ $req->requester->name }}
                                </p>

                                <p class="truncate text-[11px] text-blue-600">
                                    {{ $req->ref_number }}
                                </p>
                            </div>

                        </div>

                        {{-- Casual Count --}}
                        <span class="inline-flex shrink-0 items-center justify-center rounded-lg bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-700">
                            {{ $req->no_of_casuals }} casuals
                        </span>

                    </div>


                    {{-- Details --}}
                    <div class="mb-3 grid grid-cols-2 gap-2">

                        {{-- Casuals --}}
                        <div class="rounded-lg border border-slate-100 bg-slate-50/70 p-2.5">
                            <p class="text-[10px] font-medium uppercase tracking-wide text-slate-400">
                                Casuals
                            </p>

                            <p class="mt-0.5 text-xs font-semibold text-slate-700">
                                {{ $req->no_of_casuals }} Casuals
                            </p>
                        </div>

                        {{-- Duration --}}
                        <div class="rounded-lg border border-slate-100 bg-slate-50/70 p-2.5">
                            <p class="text-[10px] font-medium uppercase tracking-wide text-slate-400">
                                Duration
                            </p>

                            <div class="mt-0.5 flex items-center gap-1.5 text-xs font-semibold text-slate-700">

                                <svg
                                    class="h-3.5 w-3.5 shrink-0 text-slate-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>

                                {{ $req->duration }}
                                {{ $req->duration == 1 ? 'day' : 'days' }}

                            </div>
                        </div>

                    </div>


                    {{-- Action --}}
                    <button
                        type="button"
                        wire:click="assignCasuals({{ $req->id }})"
                        class="inline-flex w-full items-center justify-center gap-1.5 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs font-medium text-emerald-700 transition hover:bg-emerald-100"
                    >
                        <svg
                            class="h-3.5 w-3.5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292
                                M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1
                                a6 6 0 00-9-5.197
                                m13.46 5.197a4 4 0 00-5.16-3.754"
                            />
                        </svg>

                        Assign Workers
                    </button>

                </div>

            @empty

                <div class="py-14 text-center">

                    <div class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-full bg-slate-100">
                        <svg
                            class="h-6 w-6 text-slate-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                    </div>

                    <p class="text-sm font-semibold text-slate-800">
                        No Pending Requisitions
                    </p>

                    <p class="mt-0.5 text-xs text-slate-400">
                        All requisitions have been processed
                    </p>

                </div>

            @endforelse

        </div>


        {{-- ===== PAGINATION ===== --}}
        @if($this->hrRequisitions->hasPages())
            <div class="border-t border-slate-100 px-4 py-3 sm:px-6">
                {{ $this->hrRequisitions->links() }}
            </div>
        @endif

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
    <div class="space-y-5">
        {{-- ===== Header Card ===== --}}
        <div class="relative overflow-hidden rounded-2xl border border-gray-200/70 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-50 via-indigo-50 to-transparent dark:from-blue-950/30 dark:via-indigo-950/20 dark:to-transparent pointer-events-none"></div>

            <div class="relative flex flex-col gap-4 p-3 sm:flex-row sm:items-center sm:justify-between">
                {{-- Title --}}
                <div class="flex items-start gap-3">
                    <div class="hidden sm:flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 text-white shadow-md">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V6m0 12v-2m0-8a3 3 0 013 3M9 10a3 3 0 013-3"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <h2 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white">
                            Rejected Requisitions
                        </h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            These are the requisitions that have been rejected. You can view the reasons for rejection here.
                        </p>
                    </div>
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