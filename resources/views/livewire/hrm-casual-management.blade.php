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
        <div class="hidden lg:block w-full overflow-x-auto">
            <table class="w-full table-fixed text-xs">

                <thead class="bg-gray-50 text-left uppercase tracking-wider text-[10px] text-gray-500">
                    <tr>
                        <th class="w-[90px] px-2.5 py-2.5 whitespace-nowrap">
                            Requisition No.
                        </th>

                        <th class="w-[130px] px-2.5 py-2.5 whitespace-nowrap">
                            Requested By
                        </th>

                        <th class="w-[75px] px-2.5 py-2.5 whitespace-nowrap text-center">
                            Casuals
                        </th>

                        <th class="w-[55px] px-2.5 py-2.5 whitespace-nowrap">
                            Days
                        </th>

                        <th class="w-[22%] px-2.5 py-2.5">
                            Reason
                        </th>

                        <th class="w-[85px] px-2.5 py-2.5 whitespace-nowrap text-right">
                            Daily Rate
                        </th>

                        <th class="w-[80px] px-2.5 py-2.5 whitespace-nowrap text-right">
                            NSSF
                        </th>

                        <th class="w-[80px] px-2.5 py-2.5 whitespace-nowrap text-right">
                            SHA
                        </th>

                        <th class="w-[100px] px-2.5 py-2.5 whitespace-nowrap text-right">
                            Estimated Total
                        </th>

                        <th class="w-[65px] px-2.5 py-2.5 whitespace-nowrap text-right">
                            Actions
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse($hrm_requisitions as $req)

                        <tr
                            wire:key="hrm-req-{{ $req->id }}"
                            class="align-top hover:bg-gray-50 transition"
                        >

                            {{-- Requisition Number --}}
                            <td class="px-2.5 py-3 whitespace-nowrap">
                                <span class="font-medium text-blue-600">
                                    {{ $req->ref_number }}
                                </span>
                            </td>

                            {{-- Requested By --}}
                            <td class="px-2.5 py-3">
                                <p class="font-medium text-gray-900 truncate">
                                    {{ $req->requester->name }}
                                </p>
                            </td>

                            {{-- No of Casuals --}}
                            <td class="px-2.5 py-3 text-center text-gray-700 tabular-nums">
                                {{ $req->no_of_casuals }}
                            </td>

                            {{-- Duration --}}
                            <td class="px-2.5 py-3 whitespace-nowrap text-gray-500">
                                {{ $req->duration }}
                            </td>

                            {{-- Reason --}}
                            <td class="px-2.5 py-3">
                                @if($req->reason)

                                    <div x-data="{ open: false }">

                                        <p
                                            class="text-[11px] leading-relaxed text-slate-600 break-words"
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

                            {{-- Daily Rate --}}
                            <td class="px-2.5 py-3 whitespace-nowrap text-right text-gray-700 tabular-nums">
                                {{ number_format((float) $req->daily_rate, 2) }}
                            </td>

                            {{-- NSSF Rate --}}
                            <td class="px-2.5 py-3 whitespace-nowrap text-right text-gray-700 tabular-nums">
                                {{ number_format((float) $req->nssf_rate, 2) }}
                            </td>

                            {{-- SHA Rate --}}
                            <td class="px-2.5 py-3 whitespace-nowrap text-right text-gray-700 tabular-nums">
                                {{ number_format((float) $req->sha_rate, 2) }}
                            </td>

                            {{-- Estimated Total --}}
                            <td class="px-2.5 py-3 whitespace-nowrap text-right font-semibold text-gray-900 tabular-nums">
                                {{ number_format((float) $req->total_amount, 2) }}
                            </td>

                            {{-- Actions --}}
                            <td class="px-2.5 py-2.5">
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
                                    </button>

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
    </div>

    {{-- ===== PAGINATION ===== --}}
    <div class="mt-6">
        {{ $hrm_requisitions->links() }}
    </div>

</div>
