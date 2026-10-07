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


        {{-- ===== DESKTOP TABLE (lg and up) ===== --}}
        <div class="hidden lg:block overflow-x-auto">
            <table class="w-full text-xs">
                <thead class="border-b border-slate-200 bg-slate-50/80 text-left">
                    <tr>
                        <th class="px-4 py-2.5 text-[10px] font-semibold uppercase tracking-wider text-slate-500">
                            Requisition Number
                        </th>
                        <th class="px-4 py-2.5 text-[10px] font-semibold uppercase tracking-wider text-slate-500">
                            Requested By
                        </th>
                        <th class="px-4 py-2.5 text-center text-[10px] font-semibold uppercase tracking-wider text-slate-500">
                            No of  Casuals
                        </th>
                        <th class="px-4 py-2.5 text-[10px] font-semibold uppercase tracking-wider text-slate-500">
                            Reason
                        </th>
                        <th class="px-4 py-2.5 text-[10px] font-semibold uppercase tracking-wider text-slate-500">
                            Duration
                        </th>
                        <th class="px-4 py-2.5 text-[10px] font-semibold uppercase tracking-wider text-slate-500">
                            Start
                        </th>
                        <th class="px-4 py-2.5 text-[10px] font-semibold uppercase tracking-wider text-slate-500">
                            End
                        </th>
                       
                        <th class="px-4 py-2.5 text-right text-[10px] font-semibold uppercase tracking-wider text-slate-500">
                            Actions
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($requisitions as $requisition)
                        <tr class="group align-middle transition-colors hover:bg-slate-50/70">

                            <td class="px-4 py-3">
                                <p class="text-[11px] font-medium text-blue-600">
                                    {{ $requisition->ref_number }}
                                </p>
                            </td>

                            {{-- Requested By --}}
                            <td class="px-4 py-3">
                                <div class="flex min-w-0 items-center gap-3">
                                    
                                    <div class="min-w-0">
                                        <p class="truncate font-medium text-slate-800">
                                            {{ $requisition->requester->name }}
                                        </p>

                                    </div>
                                </div>
                            </td>

                            {{-- Casuals --}}
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex min-w-[2rem] items-center justify-center rounded-lg bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-700">
                                    {{ $requisition->no_of_casuals }}
                                </span>
                            </td>

                            {{-- Reason --}}
                            <td class="px-4 py-3">

                                @if($requisition->reason)

                                    <div
                                        x-data="{ open: false }"
                                        class="max-w-xl"
                                    >

                                        <p
                                            class="text-[11px] leading-relaxed text-slate-600
                                                break-words whitespace-normal"
                                            :class="open ? '' : 'line-clamp-2'"
                                        >
                                            {{ $requisition->reason }}
                                        </p>


                                        @if(mb_strlen($requisition->reason) > 110)

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

                            {{-- Duration --}}
                            <td class="whitespace-nowrap px-4 py-3 text-slate-600">
                                {{ $requisition->duration }}
                                {{ $requisition->duration == 1 ? 'day' : 'days' }}
                            </td>

                            {{-- Start --}}
                            <td class="whitespace-nowrap px-4 py-3 text-slate-600">
                                {{ \Carbon\Carbon::parse($requisition->start_date)->format('M d, Y') }}
                            </td>

                            {{-- End --}}
                            <td class="whitespace-nowrap px-4 py-3 text-slate-600">
                                {{ \Carbon\Carbon::parse($requisition->end_date)->format('M d, Y') }}
                            </td>

                            {{-- Actions --}}
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2">

                                    <button
                                        type="button"
                                        wire:click="approveRequest({{ $requisition->id }})"
                                        wire:confirm="Approve this requisition?"
                                        class="inline-flex items-center gap-1.5 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-xs font-medium text-emerald-700 transition hover:bg-emerald-100"
                                    >  
                                        Approve
                                    </button>

                                    <button
                                        type="button"
                                        wire:click="reject({{ $requisition->id }})"
                                        wire:confirm="Reject this requisition?"
                                        class="inline-flex items-center gap-1.5 rounded-lg border border-rose-200 bg-rose-50 px-3 py-1.5 text-xs font-medium text-rose-700 transition hover:bg-rose-100"
                                    >
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
        <div class="divide-y divide-slate-100 lg:hidden">
            @forelse($requisitions as $requisition)

                <div class="p-4">

                    {{-- Header --}}
                    <div class="mb-3 flex items-start justify-between gap-3">
                        <div class="flex min-w-0 items-center gap-3">

                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-slate-100 text-xs font-semibold text-slate-600">
                                {{ strtoupper(substr($requisition->requester->name, 0, 1)) }}
                            </div>

                            <div class="min-w-0">
                                <p class="truncate text-sm font-semibold text-slate-800">
                                    {{ $requisition->requester->name }}
                                </p>

                                <p class="text-[11px] text-blue-600">
                                    {{ $requisition->ref_number }}
                                </p>
     
                            </div>
                        </div>

                        <span class="inline-flex shrink-0 items-center justify-center rounded-lg bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-700">
                            {{ $requisition->no_of_casuals }} casuals
                        </span>
                    </div>


                    {{-- Reason --}}
                    <div class="mt-3 rounded-lg border border-slate-100 bg-white">

                        <div class="px-3 pt-2.5">

                            <p class="text-[10px] font-semibold uppercase tracking-wide text-slate-400">
                                Reason
                            </p>

                        </div>


                        @if($requisition->reason)

                            <div
                                x-data="{ open: false }"
                                class="px-3 pb-2.5 pt-1.5"
                            >

                                <p
                                    class="text-[11px] leading-relaxed text-slate-600 break-words"
                                    :class="open ? '' : 'line-clamp-3'"
                                >
                                    {{ $requisition->reason }}
                                </p>


                                @if(mb_strlen($requisition->reason) > 110)

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


                    {{-- Details --}}
                    <dl class="mb-3 mt-1 grid grid-cols-3 gap-2">

                        <div class="rounded-lg border border-slate-100 bg-slate-50/70 p-2.5">
                            <dt class="text-[10px] font-medium uppercase tracking-wide text-slate-400">
                                Duration
                            </dt>

                            <dd class="mt-0.5 text-xs font-semibold text-slate-700">
                                {{ $requisition->duration }}d
                            </dd>
                        </div>

                        <div class="rounded-lg border border-slate-100 bg-slate-50/70 p-2.5">
                            <dt class="text-[10px] font-medium uppercase tracking-wide text-slate-400">
                                Start
                            </dt>

                            <dd class="mt-0.5 text-xs font-semibold text-slate-700">
                                {{ \Carbon\Carbon::parse($requisition->start_date)->format('M d, Y') }}
                            </dd>
                        </div>

                        <div class="rounded-lg border border-slate-100 bg-slate-50/70 p-2.5">
                            <dt class="text-[10px] font-medium uppercase tracking-wide text-slate-400">
                                End
                            </dt>

                            <dd class="mt-0.5 text-xs font-semibold text-slate-700">
                                {{ \Carbon\Carbon::parse($requisition->end_date)->format('M d, Y') }}
                            </dd>
                        </div>

                    </dl>


                    {{-- Actions --}}
                    <div class="flex items-center gap-2">

                        <button
                            type="button"
                            wire:click="approveRequest({{ $requisition->id }})"
                            wire:confirm="Approve this requisition?"
                            class="inline-flex flex-1 items-center justify-center gap-1.5 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs font-medium text-emerald-700 transition hover:bg-emerald-100"
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
                                    stroke-width="2.5"
                                    d="M5 13l4 4L19 7"
                                />
                            </svg>
                            Approve
                        </button>

                        <button
                            type="button"
                            wire:click="reject({{ $requisition->id }})"
                            wire:confirm="Reject this requisition?"
                            class="inline-flex flex-1 items-center justify-center gap-1.5 rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-xs font-medium text-rose-700 transition hover:bg-rose-100"
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
                                    stroke-width="2.5"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                            Reject
                        </button>

                    </div>

                </div>

            @empty
                @include('partials.requisitions-empty')
            @endforelse
        </div>


        {{-- ===== PAGINATION ===== --}}
        @if($requisitions->hasPages())
            <div class="border-t border-slate-100 px-4 py-3 sm:px-6">
                {{ $requisitions->links() }}
            </div>
        @endif

    </div>

</div>
