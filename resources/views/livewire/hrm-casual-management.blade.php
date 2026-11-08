<div class="space-y-6">

    {{-- ===== Page Header ===== --}}
    <x-page-header
        title="HRM Approval – Casual Requisitions"
        subtitle="Review HR-approved rates and approve or reject requisitions">
    </x-page-header>

    <x-data-card title="Requisitions Awaiting Your Approval" 
        subtitle="Review and approve casual worker requests">
        <x-slot:actions>
            <div class="flex w-full flex-col gap-2 sm:w-auto sm:flex-row sm:items-center sm:gap-2">

                {{-- Search --}}
                <div class="flex w-full gap-2 sm:w-auto">
                    <div class="relative w-full sm:w-72">
                        <svg
                            class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"
                            />
                        </svg>

                        <input
                            type="text"
                            wire:model.live.debounce.300ms="search"
                            placeholder="Search devices…"
                            class="w-full rounded-lg border border-gray-200 bg-white py-2 pl-10 pr-3
                                text-sm text-slate-700 placeholder:text-slate-400
                                focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                        />
                    </div>

                    @if($search)
                        <button
                            type="button"
                            wire:click="$set('search', ''); $set('actionFilter', '')"
                            class="inline-flex shrink-0 items-center justify-center rounded-lg
                                border border-slate-200 bg-white px-3 py-2
                                text-xs font-medium text-slate-600
                                transition hover:bg-slate-50"
                        >
                            Clear
                        </button>
                    @endif
                </div>

            </div>
        </x-slot:actions>

        
        {{-- ===== DESKTOP TABLE (lg and up) ===== --}}
        <div class="hidden lg:block w-full overflow-hidden">

            <table class="w-full table-fixed text-xs">

                <thead class="bg-gray-50 text-left uppercase tracking-wider text-[10px] text-gray-500">
                    <tr>
                        <th class="w-[10%] px-2 py-3">Requisition No.</th>
                        <th class="w-[12%] px-2 py-3">Requested By</th>
                        <th class="w-[6%] px-1 py-3 text-center">Casuals</th>
                        <th class="w-[5%] px-1 py-3 text-center">Days</th>
                        <th class="w-[20%] px-2 py-3">Reason</th>
                        <th class="w-[9%] px-1 py-3 text-right">Daily Rate</th>
                        <th class="w-[8%] px-1 py-3 text-right">NSSF</th>
                        <th class="w-[8%] px-1 py-3 text-right">SHA</th>
                        <th class="w-[11%] px-1 py-3 text-right">Estimated Total</th>
                        <th class="w-[11%] px-1 py-3 text-center">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">

                    @forelse($hrm_requisitions as $req)

                        <tr
                            wire:key="hrm-req-{{ $req->id }}"
                            class="align-top hover:bg-gray-50 transition"
                        >

                            {{-- REQUISITION NUMBER --}}
                            <td class="px-2 py-3">
                                <span class="font-semibold text-blue-600 break-words">
                                    {{ $req->ref_number }}
                                </span>
                            </td>

                            {{-- REQUESTED BY --}}
                            <td class="px-2 py-3">
                                <div class="min-w-0">
                                    <p class="font-medium text-gray-900 truncate"
                                    title="{{ $req->requester->name ?? '' }}">
                                        {{ $req->requester->name ?? 'Unknown User' }}
                                    </p>
                                </div>
                            </td>

                            {{-- CASUALS --}}
                            <td class="px-1 py-3 text-center text-gray-700 tabular-nums">
                                {{ $req->no_of_casuals }}
                            </td>

                            {{-- DURATION --}}
                            <td class="px-1 py-3 text-center text-gray-500 tabular-nums">
                                {{ $req->duration }}
                            </td>

                            {{-- REASON --}}
                            <td class="px-2 py-3 min-w-0">
                                @if($req->reason)

                                    <div x-data="{ open: false }">

                                        <p
                                            class="text-[11px] leading-relaxed text-slate-600 break-words [overflow-wrap:anywhere]"
                                            :class="open ? '' : 'line-clamp-3'"
                                        >
                                            {{ $req->reason }}
                                        </p>

                                        @if(mb_strlen($req->reason) > 140)
                                            <button
                                                type="button"
                                                @click="open = !open"
                                                class="mt-1 text-[10px] font-medium text-blue-600 hover:text-blue-700 hover:underline"
                                                x-text="open ? 'Show less' : 'Read more'"
                                            ></button>
                                        @endif

                                    </div>

                                @else

                                    <span class="text-slate-400 text-[10px] italic">
                                        No reason provided
                                    </span>

                                @endif
                            </td>

                            {{-- DAILY RATE --}}
                            <td class="px-1 py-3 text-right text-gray-700 tabular-nums">
                                {{ number_format((float) $req->daily_rate, 2) }}
                            </td>

                            {{-- NSSF --}}
                            <td class="px-1 py-3 text-right text-gray-700 tabular-nums">
                                {{ number_format((float) $req->nssf_rate, 2) }}
                            </td>

                            {{-- SHA --}}
                            <td class="px-1 py-3 text-right text-gray-700 tabular-nums">
                                {{ number_format((float) $req->sha_rate, 2) }}
                            </td>

                            {{-- ESTIMATED TOTAL --}}
                            <td class="px-1 py-3 text-right font-semibold text-gray-900 tabular-nums">
                                {{ number_format((float) $req->total_amount, 2) }}
                            </td>

                            {{-- ACTIONS --}}
                            <td class="px-2 py-3 text-center">
                                <div class="flex items-center justify-center">
                                    @include('partials.requisition-actions', [
                                        'req' => $req,
                                        'approveMethod' => 'approveHrm',
                                        'returnMethod' => 'openHrmReturnModal',
                                        'rejectMethod' => 'reject',
                                    ])
                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="10" class="py-16 text-center">

                                <p class="font-medium text-gray-700">
                                    No requisitions awaiting HRM approval
                                </p>

                                <p class="text-xs text-gray-500 mt-1">
                                    All caught up 🎉
                                </p>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- ===== MOBILE / TABLET CARDS ===== --}}
        <div class="lg:hidden divide-y divide-gray-100">
            @forelse($hrm_requisitions as $req)

                <div
                    wire:key="hrm-req-mobile-{{ $req->id }}"
                    class="p-4 sm:p-5 hover:bg-gray-50/50 transition"
                >

                    {{-- ===== HEADER ===== --}}
                    <div class="flex items-start justify-between gap-3 mb-4">

                        {{-- Requester --}}
                        <div class="flex items-center gap-3 min-w-0">
                            <div
                                class="w-10 h-10 rounded-full bg-gradient-to-br from-violet-500 to-fuchsia-600
                                    text-white flex items-center justify-center font-semibold text-sm shrink-0"
                            >
                                {{ strtoupper(substr($req->requester->name, 0, 1)) }}
                            </div>

                            <div class="min-w-0">
                                <p class="font-semibold text-gray-900 truncate">
                                    {{ $req->requester->name }}
                                </p>

                                <p class="text-[11px] text-blue-600 mt-0.5">
                                    {{ $req->ref_number }}
                                </p>
                            </div>
                        </div>

                        {{-- Number of Casuals --}}
                        <span
                            class="shrink-0 inline-flex items-center justify-center
                                px-2.5 py-1 rounded-lg text-[11px] font-semibold
                                bg-indigo-50 text-indigo-700"
                        >
                            {{ $req->no_of_casuals }} casuals
                        </span>

                    </div>


                    {{-- ===== SUMMARY INFORMATION ===== --}}
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 mb-4">

                        {{-- No. of Casuals --}}
                        <div class="bg-gray-50 rounded-lg p-2.5">
                            <dt class="text-[10px] uppercase tracking-wide text-gray-500">
                                Casuals
                            </dt>

                            <dd class="font-semibold text-gray-900 mt-0.5 tabular-nums">
                                {{ $req->no_of_casuals }}
                            </dd>
                        </div>

                        {{-- Duration --}}
                        <div class="bg-gray-50 rounded-lg p-2.5">
                            <dt class="text-[10px] uppercase tracking-wide text-gray-500">
                                Days
                            </dt>

                            <dd class="font-semibold text-gray-900 mt-0.5 tabular-nums">
                                {{ $req->duration }}
                            </dd>
                        </div>

                        {{-- Daily Rate --}}
                        <div class="bg-gray-50 rounded-lg p-2.5">
                            <dt class="text-[10px] uppercase tracking-wide text-gray-500">
                                Daily Rate
                            </dt>

                            <dd class="font-semibold text-gray-900 mt-0.5 tabular-nums">
                                {{ number_format((float) $req->daily_rate, 2) }}
                            </dd>
                        </div>

                        {{-- Estimated Total --}}
                        <div class="bg-violet-50 rounded-lg p-2.5">
                            <dt class="text-[10px] uppercase tracking-wide text-violet-600">
                                Estimated Total
                            </dt>

                            <dd class="font-bold text-violet-700 mt-0.5 tabular-nums">
                                {{ number_format((float) $req->total_amount, 2) }}
                            </dd>
                        </div>

                    </div>


                    {{-- ===== RATES ===== --}}
                    <div class="border border-gray-100 rounded-lg overflow-hidden mb-4">

                        <div class="px-3 py-2 bg-gray-50 border-b border-gray-100">
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-gray-500">
                                Statutory Rates
                            </p>
                        </div>

                        <div class="grid grid-cols-2 divide-x divide-gray-100">

                            {{-- NSSF --}}
                            <div class="px-3 py-2.5">
                                <p class="text-[10px] text-gray-500">
                                    NSSF Rate
                                </p>

                                <p class="mt-0.5 text-xs font-medium text-gray-800 tabular-nums">
                                    {{ number_format((float) $req->nssf_rate, 2) }}
                                </p>
                            </div>

                            {{-- SHA --}}
                            <div class="px-3 py-2.5">
                                <p class="text-[10px] text-gray-500">
                                    SHA Rate
                                </p>

                                <p class="mt-0.5 text-xs font-medium text-gray-800 tabular-nums">
                                    {{ number_format((float) $req->sha_rate, 2) }}
                                </p>
                            </div>

                        </div>

                    </div>


                    {{-- ===== REASON ===== --}}
                    <div class="mb-4">

                        <p class="text-[10px] font-semibold uppercase tracking-wider text-gray-500 mb-1.5">
                            Reason
                        </p>

                        @if($req->reason)

                            <div x-data="{ open: false }">

                                <p
                                    class="text-xs leading-relaxed text-slate-600 break-words"
                                    :class="open ? '' : 'line-clamp-3'"
                                >
                                    {{ $req->reason }}
                                </p>

                                @if(mb_strlen($req->reason) > 140)
                                    <button
                                        type="button"
                                        @click="open = !open"
                                        class="mt-1 text-[11px] font-medium text-blue-600
                                            hover:text-blue-700 hover:underline"
                                        x-text="open ? 'Show less' : 'Read more'"
                                    ></button>
                                @endif

                            </div>

                        @else

                            <span class="text-xs text-slate-400 italic">
                                No reason provided
                            </span>

                        @endif

                    </div>


                    {{-- ===== ACTIONS ===== --}}
                    <div class="flex items-center gap-2 pt-1">

                        {{-- Approve --}}
                        <button
                            type="button"
                            wire:click="approveHrm({{ $req->id }})"
                            wire:confirm="Approve this requisition?"
                            title="Approve"
                            class="flex-1 inline-flex items-center justify-center gap-1.5
                                px-3 py-2 rounded-lg text-xs font-medium
                                text-white bg-emerald-600
                                hover:bg-emerald-700 transition shadow-sm"
                        >
                            <svg
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

                            Approve
                        </button>


                        {{-- Reject --}}
                        <button
                            type="button"
                            wire:click="reject({{ $req->id }})"
                            wire:confirm="Reject this requisition?"
                            title="Reject"
                            class="flex-1 inline-flex items-center justify-center gap-1.5
                                px-3 py-2 rounded-lg text-xs font-medium
                                text-white bg-rose-600
                                hover:bg-rose-700 transition shadow-sm"
                        >
                            <svg
                                class="h-4 w-4"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd"
                                />
                            </svg>

                            Reject
                        </button>

                    </div>

                
                </div>

                @empty

                <div class="py-16 text-center">
                    <p class="font-medium text-gray-700">
                        No requisitions awaiting HRM approval
                    </p>

                    <p class="text-xs text-gray-500 mt-1">
                        All caught up 🎉
                    </p>
                </div>

            @endforelse
        </div>

        {{-- ===== PAGINATION ===== --}}
        <div class="border-t border-slate-100 px-4 py-3 sm:px-6">
            {{ $hrm_requisitions->onEachSide(0)->links() }}
        </div>

        {{-- HRM RETURN MODAL --}}
        <div
            x-data="{ open: false }"
            @open-hrm-return-modal.window="open = true"
            @close-hrm-return-modal.window="open = false"
            @keydown.escape.window="open = false"
            x-show="open"
            x-cloak
            class="fixed inset-0 z-[10000] flex items-center justify-center
                bg-black/50 px-4 py-6"
        >
            <div
                @click.outside="open = false"
                x-transition
                class="w-full max-w-lg rounded-xl bg-white shadow-xl"
            >

                {{-- HEADER --}}
                <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4">
                    <div>
                        <h3 class="text-base font-semibold text-gray-800">
                            Return Requisition
                        </h3>
                        <p class="mt-1 text-xs text-gray-500">
                            Return this requisition to the HR Representative for corrections.
                        </p>
                    </div>

                    <button
                        type="button"
                        @click="open = false"
                        class="rounded-md p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-700"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- BODY --}}
                <form wire:submit.prevent="returnHrm" class="p-5">

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Reason for returning
                            <span class="text-red-500">*</span>
                        </label>

                        <textarea
                            wire:model.defer="hrmReturnReason"
                            rows="4"
                            placeholder="Explain why this requisition is being returned..."
                            class="w-full rounded-lg border border-gray-300 px-3 py-2.5
                                text-sm text-gray-700 focus:border-amber-500
                                focus:ring-amber-500"
                        ></textarea>

                        @error('hrmReturnReason')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- FOOTER --}}
                    <div class="mt-5 flex justify-end gap-2">

                        <button
                            type="button"
                            @click="open = false"
                            class="rounded-lg border border-gray-300 px-4 py-2
                                text-sm font-medium text-gray-700 hover:bg-gray-50"
                        >
                            Cancel
                        </button>

                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            wire:target="returnHrm"
                            class="rounded-lg bg-amber-600 px-4 py-2
                                text-sm font-medium text-white hover:bg-amber-700
                                disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            <span wire:loading.remove wire:target="returnHrm">
                                Return to HR
                            </span>

                            <span wire:loading wire:target="returnHrm">
                                Returning...
                            </span>
                        </button>

                    </div>
                </form>
            </div>
        </div>

    </x-data-card>


</div>
