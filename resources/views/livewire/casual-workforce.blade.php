<div x-data="{ 
    activeModal: null,
    isOpen: false 
}" class="space-y-6">

    <x-page-header
        title="Casual Workforce"
        subtitle="Request casual staff, petty cash, and submit requisitions." />

    {{-- ===== Quick Actions ===== --}}
    <div>
        <h2 class="mb-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Quick Actions</h2>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">

            {{-- Casual Requisition Card --}}
            <button 
                @click="activeModal = 'casualReq'"
                @close-casual-modal.window="activeModal = null"
                type="button"
                class="group flex w-full items-start gap-4 rounded-2xl border border-slate-200 bg-white p-5 text-left shadow-sm transition-all duration-200 hover:border-indigo-300 hover:-translate-y-0.5 hover:shadow-indigo-100">
                
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 transition group-hover:scale-110">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-5.13a4 4 0 11-8 0 4 4 0 018 0zm6 3a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <h3 class="text-sm font-semibold text-slate-900">Casual Requisition</h3>
                    <p class="mt-0.5 text-xs text-slate-500">Request temporary staff or casual workers</p>
                </div>
                <svg class="h-4 w-4 shrink-0 text-slate-300 transition group-hover:translate-x-0.5 group-hover:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>

            {{-- Petty Cash Card --}}
            <button 
                @click="activeModal = 'pettyCash'"
                @close-petty-cash-modal.window="activeModal = null"
                type="button"
                class="group flex w-full items-start gap-4 rounded-2xl border border-slate-200 bg-white p-5 text-left shadow-sm transition-all duration-200 hover:border-emerald-300 hover:-translate-y-0.5 hover:shadow-emerald-100">
                
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 transition group-hover:scale-110">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V6m0 12v-2m9-4a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <h3 class="text-sm font-semibold text-slate-900">Petty Cash</h3>
                    <p class="mt-0.5 text-xs text-slate-500">Request small cash for immediate expenses</p>
                </div>
                <svg class="h-4 w-4 shrink-0 text-slate-300 transition group-hover:translate-x-0.5 group-hover:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>

            {{-- Requisitions Card --}}
            <button 
                @click="activeModal = 'requisition'"
                type="button"
                class="group flex w-full items-start gap-4 rounded-2xl border border-slate-200 bg-white p-5 text-left shadow-sm transition-all duration-200 hover:border-violet-300 hover:-translate-y-0.5 hover:shadow-violet-100">
                
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-violet-50 text-violet-600 transition group-hover:scale-110">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <h3 class="text-sm font-semibold text-slate-900">Requisitions</h3>
                    <p class="mt-0.5 text-xs text-slate-500">Submit formal requests for goods or services</p>
                </div>
                <svg class="h-4 w-4 shrink-0 text-slate-300 transition group-hover:translate-x-0.5 group-hover:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>

        </div>
    </div>

    {{-- ===== My Requisitions ===== --}}
    <x-data-card title="My Requisitions" subtitle="Track and manage requests you've submitted.">
        <x-slot:actions>
            <div class="flex items-center gap-2">
                <select wire:model.live="statusFilter"
                    class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs text-slate-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                    <option value="">All status</option>
                    <option value="approved">Approved</option>
                    <option value="in-progress">In Progress</option>
                    <option value="rejected">Rejected</option>
                </select>
                <span class="hidden sm:inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-700">
                    {{ count($MyRequisitions) }} total
                </span>
            </div>
        </x-slot:actions>

        @php
            $statusDots = [
                'approved'    => 'bg-emerald-500',
                'rejected'    => 'bg-rose-500',
                'in-progress' => 'bg-amber-500',
            ];
        @endphp

        {{-- =========================================================
            DESKTOP TABLE (lg and up)
        ========================================================= --}}
        <div class="hidden lg:block overflow-x-auto">

            <table class="w-full text-xs">

                <thead class="border-b border-slate-200 bg-slate-50/80">

                    <tr class="text-left uppercase tracking-wider text-[10px] font-semibold text-slate-500">

                        <th class="px-4 py-3 whitespace-nowrap">
                            Requisition Number
                        </th>

                        <th class="px-4 py-3 whitespace-nowrap text-center">
                            Casuals
                        </th>

                        <th class="px-4 py-3 min-w-[280px]">
                            Reason
                        </th>

                        <th class="px-4 py-3 whitespace-nowrap text-center">
                            Days
                        </th>

                        <th class="px-4 py-3 whitespace-nowrap">
                            Period
                        </th>


                        <th class="px-4 py-3 whitespace-nowrap">
                            Status
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-slate-100">

                    @forelse($MyRequisitions as $req)

                        <tr
                            wire:key="my-req-{{ $req->id }}"
                            class="group align-top transition-colors hover:bg-slate-50/70"
                        >

                            {{-- =================================================
                                REQUISITION NUMBER
                            ================================================== --}}
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


                            {{-- =================================================
                                CASUALS
                            ================================================== --}}
                            <td class="px-4 py-3 text-center whitespace-nowrap">

                                <span class="font-medium tabular-nums text-slate-700">
                                    {{ number_format($req->no_of_casuals) }}
                                </span>

                            </td>


                            {{-- =================================================
                                REASON
                            ================================================== --}}
                            <td class="px-4 py-3">

                                @if($req->reason)

                                    <div
                                        x-data="{ open: false }"
                                        class="max-w-xl"
                                    >

                                        <p
                                            class="text-[11px] leading-relaxed text-slate-600
                                                break-words whitespace-normal"
                                            :class="open ? '' : 'line-clamp-2'"
                                        >
                                            {{ $req->reason }}
                                        </p>


                                        @if(mb_strlen($req->reason) > 110)

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


                            {{-- =================================================
                                DAYS
                            ================================================== --}}
                            <td class="px-4 py-3 text-center whitespace-nowrap">

                                <span class="font-medium tabular-nums text-slate-700">
                                    {{ $req->duration }}
                                </span>

                                <span class="ml-0.5 text-[10px] text-slate-400">
                                    {{ $req->duration == 1 ? 'day' : 'days' }}
                                </span>

                            </td>


                            {{-- =================================================
                                PERIOD
                            ================================================== --}}
                            <td class="px-4 py-3 whitespace-nowrap">

                                <div class="text-slate-700">

                                    <span class="font-medium">
                                        {{ \Carbon\Carbon::parse($req->start_date)->format('d M Y') }}
                                    </span>

                                    <span class="mx-1 text-slate-300">
                                        –
                                    </span>

                                    <span class="font-medium">
                                        {{ \Carbon\Carbon::parse($req->end_date)->format('d M Y') }}
                                    </span>

                                </div>

                            </td>


                            {{-- =================================================
                                STATUS
                            ================================================== --}}
                            <td class="px-4 py-3 whitespace-nowrap">

                                @if($req->casual_assignment_status == 1)

                                    <span class="inline-flex items-center gap-1.5 text-[11px] font-medium text-emerald-600">

                                        <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-emerald-500"></span>

                                        Approved

                                    </span>

                                @else

                                    <span class="inline-flex items-center gap-1.5 text-[11px] font-medium text-amber-600">

                                        <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-amber-500"></span>

                                        Pending

                                    </span>

                                @endif

                            </td>
                        </tr>

                    @empty

                        <tr>

                            <td colspan="7" class="px-4 py-16 text-center">

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
                                                7.707 9.879a1 1 0 001.414 1.414l2 2a1 1 0 001.414 0l3.414-3.414z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </div>

                                    <p class="font-medium text-slate-700">
                                        No requisitions yet
                                    </p>

                                    <p class="mt-1 text-[11px] text-slate-400">
                                        Submit your first request using the cards above.
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

            @forelse($MyRequisitions as $req)

                <div
                    wire:key="mobile-my-req-{{ $req->id }}"
                    class="p-4"
                >

                    {{-- =================================================
                        HEADER
                    ================================================== --}}
                    <div class="flex items-start justify-between gap-3">

                        <div class="flex min-w-0 items-center gap-2.5">


                            {{-- Requested By --}}
                            <div class="min-w-0">

                                <p class="font-medium text-blue-600">
                                    {{ $req->ref_number }}
                                </p>

                                <p class="mt-0.5 text-[10px] text-slate-400">
                                    Submitted {{ $req->created_at->format('d M Y') }}
                                </p>

                            </div>

                        </div>


                        {{-- Status --}}
                        <span
                            class="inline-flex shrink-0 items-center gap-1.5 text-[10px] font-medium
                                {{ $req->casual_assignment_status == 1
                                    ? 'text-emerald-600'
                                    : 'text-amber-600' }}"
                        >

                            <span
                                class="h-1.5 w-1.5 rounded-full
                                    {{ $req->casual_assignment_status == 1
                                        ? 'bg-emerald-500'
                                        : 'bg-amber-500' }}"
                            ></span>

                            {{ $req->casual_assignment_status == 1 ? 'Approved' : 'Pending' }}

                        </span>

                    </div>


                    {{-- =================================================
                        SUMMARY
                    ================================================== --}}
                    <div class="mt-4 grid grid-cols-2 gap-2">

                        {{-- Casuals --}}
                        <div
                            class="rounded-lg border border-slate-100 bg-slate-50/70 p-2.5"
                        >

                            <p class="text-[10px] font-medium uppercase tracking-wide text-slate-400">
                                Casuals
                            </p>

                            <p class="mt-1 text-xs font-semibold text-slate-700">
                                {{ number_format($req->no_of_casuals) }}
                            </p>

                        </div>


                        {{-- Days --}}
                        <div
                            class="rounded-lg border border-slate-100 bg-slate-50/70 p-2.5"
                        >

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
                        PERIOD
                    ================================================== --}}
                    <div
                        class="mt-3 rounded-lg border border-slate-100 bg-slate-50/70 p-2.5"
                    >

                        <p class="text-[10px] font-medium uppercase tracking-wide text-slate-400">
                            Period
                        </p>

                        <div class="mt-1 flex items-center gap-1.5 text-xs font-semibold text-slate-700">

                            <span>
                                {{ \Carbon\Carbon::parse($req->start_date)->format('d M Y') }}
                            </span>

                            <span class="text-slate-300">
                                →
                            </span>

                            <span>
                                {{ \Carbon\Carbon::parse($req->end_date)->format('d M Y') }}
                            </span>

                        </div>

                    </div>


                    {{-- =================================================
                        REASON
                    ================================================== --}}
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


                                @if(mb_strlen($req->reason) > 110)

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
                                    7.707 9.879a1 1 0 001.414 1.414l2 2a1 1 0 001.414 0l3.414-3.414z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </div>

                        <p class="font-medium text-slate-700">
                            No requisitions yet
                        </p>

                        <p class="mt-1 text-[11px] text-slate-400">
                            Submit your first request using the cards above.
                        </p>

                    </div>

                </div>

            @endforelse

        </div>


        {{-- =========================================================
            PAGINATION
        ========================================================= --}}
        @if($MyRequisitions->hasPages())

            <div class="border-t border-slate-100 px-4 py-3 sm:px-6">
                {{ $MyRequisitions->links() }}
            </div>

        @endif

    </x-data-card>

    {{-- Casual Requisition Modal --}}
    <div 
        x-show="activeModal === 'casualReq'" 
        x-cloak 
        x-transition
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
        @keydown.escape.window="activeModal = null"
    >
        <div 
            @click.away="activeModal = null"
            class="relative w-full max-w-md max-h-[90vh] overflow-y-auto p-4"
        >
            <div class="bg-white rounded-lg shadow-sm">
                <!-- Header -->
                <div class="flex items-center justify-between p-4 border-b">
                    <h3 class="text-lg font-semibold">
                        Create Casual Requisition
                    </h3>
                    <button 
                        @click="activeModal = null"
                        class="text-gray-400 hover:bg-gray-200 rounded-lg w-8 h-8 flex items-center justify-center"
                    >
                        ✕
                    </button>
                </div>
                
                <!-- Modal body - Livewire Form -->
                <form wire:submit.prevent="requisition" class="p-4 md:p-5 space-y-4">
                    <!-- Row 1: Start Date | End Date -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Start Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-900">Start Date</label>
                            <input type="date" wire:model.live="start_date"
                                class="w-full mt-1 p-2.5 text-sm border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500">
                            @error('start_date') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- End Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-900">End Date</label>
                            <input type="date" wire:model.live="end_date"
                                class="w-full mt-1 p-2.5 text-sm border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500">
                            @error('end_date') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Row 2: No. of Casuals | Duration -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- No. of Casuals -->
                        <div>
                            <label class="block text-sm font-medium text-gray-900">No. of Casuals</label>
                            <input type="number" wire:model.defer="no_of_casuals" min="1"
                                class="w-full mt-1 p-2.5 text-sm border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Enter number">
                            @error('no_of_casuals') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Duration (Calculated) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-900">Duration (Days)</label>
                            <div class="mt-1 p-2.5 text-sm bg-gray-100 rounded-lg font-semibold text-blue-700">
                                {{ $duration }} days
                            </div>
                        </div>
                    </div>

                    <!-- Weekend Exclusion Checkboxes -->
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-1">Exclude Weekend Days</label>
                        <p class="text-xs text-gray-500 mb-2">
                            *To include weekends as working days, leave both boxes unchecked.*
                        </p>
                        <div class="flex items-center gap-4">
                            <label class="flex items-center gap-2">
                                <input type="checkbox" wire:model.live="exclude_weekends.saturday"
                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded">
                                <span class="text-gray-800 text-sm">Saturday</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" wire:model.live="exclude_weekends.sunday"
                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded">
                                <span class="text-gray-800 text-sm">Sunday</span>
                            </label>
                        </div>
                    </div>

                    <!-- Reasons -->
                    <div>
                        <label for="reason" class="block text-sm font-medium text-gray-900">
                            Reason(s) for Engagement
                        </label>
                        <textarea wire:model.defer="reason" id="reason" rows="3"
                            class="w-full mt-1 p-2.5 text-sm border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Provide detailed justification"></textarea>
                        @error('reason') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit"
                        
                        class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        Submit Request
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Petty Cash Modal (placeholder) --}}
{{-- Petty Cash Modal (Toast Test Mode) --}}
<div 
    x-show="activeModal === 'pettyCash'" 
    x-cloak 
    x-transition
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
>
    <div class="relative w-full max-w-md p-4">

        <div class="bg-white rounded-lg shadow-sm">

            <!-- Header -->
            <div class="p-4 border-b">
                <h3 class="text-lg font-semibold">Petty Cash Test</h3>
                <p class="text-xs text-gray-400">
                    Click submit to test toast notification only
                </p>
            </div>

            <!-- Form -->
            <form wire:submit.prevent="pettyCash" class="p-4 space-y-4">

                <div class="text-sm text-gray-600">
                    This is a test modal for dispatching notifications.
                </div>

                <!-- Submit Only -->
                <button
                    type="submit"
                    class="w-full px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition"
                >
                    Submit Test Request
                </button>

            </form>

        </div>

    </div>
</div>


    {{-- Requisition Modal (placeholder) --}}
    <div 
        x-show="activeModal === 'requisition'" 
        x-cloak 
        x-transition
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
        @keydown.escape.window="activeModal = null"
    >
        <div @click.away="activeModal = null" class="relative w-full max-w-md max-h-[90vh] overflow-y-auto p-4">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold mb-4">General Requisition</h3>
                <p class="text-gray-600">Requisition form will go here</p>
                <button @click="activeModal = null" class="mt-4 px-4 py-2 bg-gray-200 rounded">Close</button>
            </div>
        </div>
    </div>
</div>