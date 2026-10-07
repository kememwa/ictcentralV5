<div class="space-y-6">
    
    {{-- ===== Page Header ===== --}}
    <x-page-header
        title="COO Approval – Casual Requisitions"
        subtitle="Review, approve or reject requisitions submitted by HODs for casual staff hiring.">
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
                {{ $coo_requisitions->total() ?? count($coo_requisitions) }} awaiting
            </span>
        </div>

        {{-- ===== DESKTOP TABLE (lg and up) ===== --}}
        <div class="hidden lg:block overflow-x-auto">
            <table class="w-full text-xs">
                <thead class="border-b border-slate-200 bg-slate-50/80">
                    <tr class="text-left uppercase tracking-wider text-[10px] font-semibold text-slate-500">
                        
                        <th class="px-4 py-3 whitespace-nowrap">
                            Requisition Num
                        </th>

                        <th class="px-4 py-3 whitespace-nowrap">
                            Requested By
                        </th>

                        <th class="px-4 py-3 whitespace-nowrap text-center">
                            Casuals
                        </th>

                        <th class="px-4 py-3 whitespace-nowrap text-center">
                            Days
                        </th>

                        <th class="px-4 py-3 min-w-[280px]">
                            Reason
                        </th>

                        <th class="px-4 py-3 whitespace-nowrap text-right">
                            Start Date
                        </th>

                        <th class="px-4 py-3 whitespace-nowrap text-right">
                            End Date
                        </th>

                        <th class="px-4 py-3 whitespace-nowrap text-right">
                            Actions
                        </th>

                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($coo_requisitions as $req)

                        <tr
                            wire:key="coo-req-{{ $req->id }}"
                            class="group align-top transition-colors hover:bg-slate-50/70"
                        >

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
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center gap-2.5">

                                    <div>
                                        <p class="font-medium text-slate-800">
                                            {{ $req->requester->name ?? 'Unknown User' }}
                                        </p>
                                    </div>

                                </div>
                            </td>


                            {{-- Number of Casuals --}}
                            <td class="px-4 py-3 text-center">
                                <span class="font-medium tabular-nums text-slate-700">
                                    {{ number_format($req->no_of_casuals) }}
                                </span>
                            </td>


                            {{-- Duration --}}
                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                <span class="font-medium tabular-nums text-slate-700">
                                    {{ $req->duration }}
                                </span>

                                <span class="ml-0.5 text-[10px] text-slate-400">
                                    {{ $req->duration == 1 ? 'day' : 'days' }}
                                </span>
                            </td>


                            {{-- Reason --}}
                            <td class="px-4 py-3">
                                @if($req->reason)

                                    <div
                                        x-data="{ open: false }"
                                        class="max-w-xl"
                                    >
                                        <p
                                            class="text-[11px] leading-relaxed text-slate-600 break-words whitespace-normal"
                                            :class="open ? '' : 'line-clamp-2'"
                                        >
                                            {{ $req->reason }}
                                        </p>

                                        @if(mb_strlen($req->reason) > 140)
                                            <button
                                                type="button"
                                                @click="open = !open"
                                                class="mt-1 text-[10px] font-medium text-blue-600
                                                    hover:text-blue-700 hover:underline"
                                                x-text="open ? 'Show less' : 'Read more'"
                                            ></button>
                                        @endif
                                    </div>

                                @else

                                    <span class="text-[11px] italic text-slate-400">
                                        No reason provided
                                    </span>

                                @endif
                            </td>


                            {{-- Start Date --}}
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                <span class="font-medium text-slate-800">
                                    {{ \Carbon\Carbon::parse($req->start_date)->format('d M Y') }}
                                </span>
                            </td>


                            {{-- End Date --}}
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                <span class="font-medium text-slate-800">
                                    {{ \Carbon\Carbon::parse($req->end_date)->format('d M Y') }}
                                </span>
                            </td>

                            {{-- Actions --}}
                            <td class="px-3 py-2.5 text-right">
                                <div class="flex items-center justify-end gap-1">

                                    {{-- Approve --}}
                                    <button
                                        type="button"
                                        wire:click="approveCoo({{ $req->id }})"
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
                                        wire:click="rejectCoo({{ $req->id }})"
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
                            <td colspan="8" class="px-4 py-16 text-center">

                                <div class="flex flex-col items-center justify-center">

                                    <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-full bg-slate-100">
                                        <svg
                                            class="h-5 w-5 text-slate-400"
                                            viewBox="0 0 20 20"
                                            fill="currentColor"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.707a1 1 0 00-1.414-1.414L9 11.172 7.707 9.879a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l3.414-3.414z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </div>

                                    <p class="font-medium text-slate-700">
                                        No requisitions awaiting COO approval
                                    </p>

                                    <p class="mt-1 text-[11px] text-slate-400">
                                        All caught up 🎉
                                    </p>

                                </div>

                            </td>
                        </tr>

                    @endforelse

                </tbody>
            </table>
        </div>


        {{-- ===== MOBILE / TABLET CARDS ===== --}}
        <div class="lg:hidden divide-y divide-slate-100">

            @forelse($coo_requisitions as $req)

                <div
                    wire:key="mobile-coo-req-{{ $req->id }}"
                    class="p-4"
                >

                    {{-- Header: Requester + Casual Count --}}
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

                                @if($req->requester?->designation)
                                    <p class="mt-0.5 truncate text-[10px] text-blue-600">
                                        {{ $req->ref_number }}
                                    </p>
                                @endif
                            </div>

                        </div>


                        {{-- Casual Count --}}
                        <div class="shrink-0 text-right">
                            <p class="text-[10px] uppercase tracking-wide text-slate-400">
                                No of Casuals
                            </p>

                            <p class="mt-0.5 text-sm font-semibold tabular-nums text-slate-700">
                                {{ number_format($req->no_of_casuals) }}
                            </p>
                        </div>

                    </div>


                    {{-- Summary --}}
                    <div class="mt-4 grid grid-cols-2 gap-2">

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


                        {{-- Period --}}
                        <div class="rounded-lg border border-slate-100 bg-slate-50/70 p-2.5">
                            <p class="text-[10px] font-medium uppercase tracking-wide text-slate-400">
                                Period
                            </p>

                            <p class="mt-1 text-xs font-semibold text-slate-700">
                                {{ \Carbon\Carbon::parse($req->start_date)->format('d M Y') }}
                            </p>

                            <p class="mt-0.5 text-[10px] text-slate-400">
                                to {{ \Carbon\Carbon::parse($req->end_date)->format('d M Y') }}
                            </p>
                        </div>

                    </div>


                    {{-- Reason --}}
                    <div class="mt-3 rounded-lg border border-slate-100 bg-white">

                        <div class="px-3 pt-2.5">
                            <p class="text-[10px] font-semibold uppercase tracking-wide text-slate-400">
                                Reason
                            </p>
                        </div>

                        @if($req->reason)

                            <div
                                x-data="{ open: false }"
                                class="px-3 pb-2.5 pt-1.5"
                            >
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
                                        class="mt-1 text-[10px] font-medium text-blue-600
                                            hover:text-blue-700 hover:underline"
                                        x-text="open ? 'Show less' : 'Read more'"
                                    ></button>
                                @endif
                            </div>

                        @else

                            <p class="px-3 pb-2.5 pt-1.5 text-[11px] italic text-slate-400">
                                No reason provided
                            </p>

                        @endif

                    </div>


                    {{-- Actions --}}
                    <div class="mt-4 flex items-center justify-end gap-2">

                        {{-- Approve --}}
                        <button
                            type="button"
                            wire:click="approveCoo({{ $req->id }})"
                            wire:confirm="Approve this requisition?"
                            title="Approve"
                            aria-label="Approve requisition"
                            class="inline-flex h-9 w-9 items-center justify-center rounded-lg
                                border border-emerald-200 bg-white text-emerald-600
                                transition hover:bg-emerald-50 hover:text-emerald-700
                                focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
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
                            wire:click="rejectCoo({{ $req->id }})"
                            wire:confirm="Reject this requisition?"
                            title="Reject"
                            aria-label="Reject requisition"
                            class="inline-flex h-9 w-9 items-center justify-center rounded-lg
                                border border-red-200 bg-white text-red-600
                                transition hover:bg-red-50 hover:text-red-700
                                focus:outline-none focus:ring-2 focus:ring-red-500/20"
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

                </div>

            @empty

                <div class="px-4 py-16 text-center">

                    <div class="flex flex-col items-center justify-center">

                        <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-full bg-slate-100">
                            <svg
                                class="h-5 w-5 text-slate-400"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.707a1 1 0 00-1.414-1.414L9 11.172 7.707 9.879a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l3.414-3.414a1 1 0 000-1.414z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </div>

                        <p class="font-medium text-slate-700">
                            No requisitions awaiting COO approval
                        </p>

                        <p class="mt-1 text-[11px] text-slate-400">
                            All caught up 🎉
                        </p>

                    </div>

                </div>

            @endforelse

        </div>

</div>
