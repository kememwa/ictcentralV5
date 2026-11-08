<div class="space-y-6">

   <x-page-header
        title="HOD Approval – Casual Requisitions"
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


                            {{-- ACTIONS --}}
                            <td class="px-1 py-2.5">
                                <div class="flex items-center justify-center">
                                    @include('partials.requisition-actions', [
                                        'req' => $requisition,
                                        'approveMethod' => 'approveRequest',
                                        'returnMethod' => 'openReturnModal',
                                        'rejectMethod' => 'openRejectModal',
                                    ])
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



        {{-- HOD Return Requisition Modal --}}
        @if($returnRequisitionId)
            <div
                class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 p-4"
                wire:key="return-requisition-modal"
                wire:keydown.escape="closeReturnModal"
            >
                <div
                    class="w-full max-w-lg overflow-hidden rounded-xl bg-white shadow-xl"
                    wire:click.stop
                >

                    {{-- Modal Header --}}
                    <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">

                        <div class="flex items-center gap-3">

                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-amber-100">
                                <svg
                                    class="h-5 w-5 text-amber-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M3 10h11a5 5 0 0 1 0 10h-1M3 10l4-4M3 10l4 4"
                                    />
                                </svg>
                            </div>

                            <div>
                                <h3 class="text-base font-semibold text-gray-900">
                                    Return Requisition
                                </h3>

                                <p class="text-xs text-gray-500">
                                    Provide a reason for returning this requisition.
                                </p>
                            </div>

                        </div>

                        <button
                            type="button"
                            wire:click="closeReturnModal"
                            class="rounded-lg p-2 text-gray-400 transition hover:bg-gray-100 hover:text-gray-700"
                        >
                            <svg
                                class="h-5 w-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </button>

                    </div>

                    {{-- Modal Body --}}
                    <form wire:submit.prevent="returnRequisition({{ $returnRequisitionId }})">

                        <div class="space-y-4 px-6 py-5">

                            <div class="rounded-lg border border-amber-200 bg-amber-50 p-3">
                                <p class="text-sm text-amber-800">
                                    This requisition will be returned to the requester
                                    for correction. After resubmission, it will be
                                    submitted for HOD approval again.
                                </p>
                            </div>

                            <div>
                                <label
                                    for="returnReason"
                                    class="mb-2 block text-sm font-medium text-gray-700"
                                >
                                    Reason for Return
                                    <span class="text-rose-500">*</span>
                                </label>

                                <textarea
                                    id="returnReason"
                                    wire:model="returnReason"
                                    rows="4"
                                    placeholder="Explain why this requisition is being returned..."
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-700 placeholder-gray-400 outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-100"
                                ></textarea>

                                @error('returnReason')
                                    <p class="mt-1 text-xs text-rose-600">
                                        {{ $message }}
                                    </p>
                                @enderror

                                <p class="mt-1 text-xs text-gray-400">
                                    Minimum 5 characters, maximum 2,000 characters.
                                </p>
                            </div>

                        </div>

                        {{-- Modal Footer --}}
                        <div class="flex justify-end gap-3 border-t border-gray-100 bg-gray-50 px-6 py-4">

                            <button
                                type="button"
                                wire:click="closeReturnModal"
                                wire:loading.attr="disabled"
                                class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100 disabled:opacity-50"
                            >
                                Cancel
                            </button>

                            <button
                                type="submit"
                                wire:loading.attr="disabled"
                                wire:target="returnRequisition"
                                class="inline-flex items-center gap-2 rounded-lg bg-amber-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-amber-700 disabled:cursor-not-allowed disabled:opacity-50"
                            >
                                <span
                                    wire:loading.remove
                                    wire:target="returnRequisition"
                                >
                                    Return Requisition
                                </span>

                                <span
                                    wire:loading
                                    wire:target="returnRequisition"
                                >
                                    Returning...
                                </span>
                            </button>

                        </div>

                    </form>

                </div>
            </div>
        @endif



    </x-data-card>

    @include('partials.reject-requisition-modal')
</div>
