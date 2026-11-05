
@props(['req'])

@php
    $requisition = $req ;
@endphp

<div
    class="inline-flex items-center justify-end gap-1"
    x-data="{
        moreOpen: false,
        moreStyle: '',
        positionMore() {
            this.$nextTick(() => {
                const btn = this.$refs.moreBtn.getBoundingClientRect();
                const menuW = 192;
                const menuH = 190;

                let top = btn.bottom + 6;
                let left = btn.right - menuW;

                if (left < 8) left = 8;
                if (left + menuW > window.innerWidth - 8) {
                    left = window.innerWidth - menuW - 8;
                }

                if (top + menuH > window.innerHeight - 8) {
                    top = btn.top - menuH - 6;
                }

                if (top < 8) top = 8;

                this.moreStyle = `top:${top}px; left:${left}px;`;
            });
        }
    }"
    @scroll.window="moreOpen = false"
    @resize.window="moreOpen = false"
>

    {{-- THREE-DOT TRIGGER --}}
    <button
        type="button"
        x-ref="moreBtn"
        @click="moreOpen ? moreOpen = false : (moreOpen = true, positionMore())"
        title="Requisition actions"
        aria-label="Requisition actions"
        :aria-expanded="moreOpen"
        class="inline-flex items-center justify-center p-1.5
            rounded-md text-gray-500 hover:bg-gray-100
            hover:text-gray-800 transition"
    >
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zm0 6a2 2 0 110-4 2 2 0 010 4zm0 6a2 2 0 110-4 2 2 0 010 4z"/>
        </svg>
    </button>

    {{-- TELEPORTED DROPDOWN --}}
    <template x-teleport="body">
        <div
            x-show="moreOpen"
            x-cloak
            x-transition.opacity
            @click.outside="moreOpen = false"
            @keydown.escape.window="moreOpen = false"
            :style="moreStyle"
            class="fixed z-[9999] w-48 bg-white border border-gray-200
                rounded-lg shadow-xl py-1"
        >

            {{-- APPROVE --}}
            <button
                type="button"
                wire:click="approveHrm({{ $requisition->id }})"
                wire:confirm="Are you sure you want to approve this requisition?"
                @click="moreOpen = false"
                class="flex w-full items-center gap-3 px-3 py-2.5
                    text-sm text-green-700 hover:bg-green-50 transition"
            >
                <svg class="w-4 h-4 shrink-0" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M16.704 4.884a1 1 0 01.012 1.414l-8.25 8.25a1 1 0 01-1.414 0l-3.75-3.75a1 1 0 011.414-1.414l3.043 3.043 7.543-7.543a1 1 0 011.402 0z"
                        clip-rule="evenodd"/>
                </svg>
                <span>Approve</span>
            </button>

            {{-- RETURN --}}
            <button
                type="button"
                wire:click="openHrmReturnModal({{ $requisition->id }})"
                @click="moreOpen = false"
                class="flex w-full items-center gap-3 px-3 py-2.5
                    text-sm text-amber-700 hover:bg-amber-50 transition"
            >
                <svg class="w-4 h-4 shrink-0" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M7.707 3.293a1 1 0 010 1.414L5.414 7H13a4 4 0 010 8H9a1 1 0 110-2h4a2 2 0 100-4H5.414l2.293 2.293a1 1 0 11-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                        clip-rule="evenodd"/>
                </svg>
                <span>Return</span>
            </button>

            {{-- REJECT --}}
            <button
                type="button"
                wire:click="reject({{ $requisition->id }})"
                wire:confirm="Are you sure you want to reject this requisition?"
                @click="moreOpen = false"
                class="flex w-full items-center gap-3 px-3 py-2.5
                    text-sm text-red-700 hover:bg-red-50 transition"
            >
                <svg class="w-4 h-4 shrink-0" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                        clip-rule="evenodd"/>
                </svg>
                <span>Reject</span>
            </button>

            <div class="my-1 border-t border-gray-100"></div>

        </div>
    </template>
</div>
