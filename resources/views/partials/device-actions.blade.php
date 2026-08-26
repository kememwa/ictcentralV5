@props(['device'])

<div class="inline-flex items-center justify-end gap-1"
     x-data="{
        moreOpen: false,
        moreStyle: '',
        positionMore() {
            this.$nextTick(() => {
                const btn = this.$refs.moreBtn.getBoundingClientRect();
                const menuW = 176; // w-44
                const menuH = 96;
                let top  = btn.bottom + 6;
                let left = btn.right - menuW;
                if (left < 8) left = 8;
                if (top + menuH > window.innerHeight - 8) top = btn.top - menuH - 6;
                this.moreStyle = `top:${top}px; left:${left}px;`;
            });
        }
     }"
     @scroll.window="moreOpen = false"
     @resize.window="moreOpen = false">


    {{-- ===== "More" trigger ===== --}}
    <button type="button"
        x-ref="moreBtn"
        @click="moreOpen ? moreOpen = false : (moreOpen = true, positionMore())"
        title="More actions"
        class="p-1.5 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zm0 6a2 2 0 110-4 2 2 0 010 4zm0 6a2 2 0 110-4 2 2 0 010 4z"/>
        </svg>
    </button>

    {{-- ===== Teleported menu (escapes table overflow) ===== --}}
    <template x-teleport="body">
        <div x-show="moreOpen" x-cloak x-transition.opacity
            @click.outside="moreOpen = false"
            @keydown.escape.window="moreOpen = false"
            :style="moreStyle"
            class="fixed z-[9999] w-44 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg shadow-xl py-1">

            <button type="button"
                wire:click="loadDevice({{ $device->id }})"
                @click="moreOpen = false"
                class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                ✏️ Edit
            </button>

            @if (!$device->user_id)
                <button type="button"
                    wire:click="openAssignModal({{ $device->id }})"
                    @click="moreOpen = false"
                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                    👤➕ Assign
                </button>
            @else
                <button type="button"
                    wire:click="reassignDevice({{ $device->id }}, 'unassign')"
                    @click="moreOpen = false"
                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                    📤 Unassign
                </button>
            @endif

            <div class="my-1 border-t border-gray-100 dark:border-gray-800"></div>

            @if(!$device->user_id)
            <button type="button"
                wire:click="openDeleteModal({{ $device->id }})"
                @click="moreOpen = false"
                class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition">
                🗑️ Archive
            </button>
            @endif
        </div>
    </template>
</div>
