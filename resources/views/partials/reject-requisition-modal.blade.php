<div
    x-data="{ open: false }"
    x-on:open-reject-modal.window="open = true"
    x-on:close-reject-modal.window="open = false"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-[10000] flex items-center justify-center
        bg-black/50 px-4 py-6"
>
    <div
        x-show="open"
        x-transition
        @click.outside="open = false"
        @keydown.escape.window="open = false"
        class="w-full max-w-md rounded-xl bg-white shadow-xl"
    >
        <form wire:submit.prevent="confirmRejection">

            {{-- HEADER --}}
            <div class="border-b border-gray-100 px-5 py-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center
                        rounded-full bg-red-100 text-red-600">
                        <svg class="h-5 w-5" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="2"
                                d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2
                                2 0 001.71 3h16.94a2 2 0 001.71-3L13.71
                                3.86a2 2 0 00-3.42 0z"/>
                        </svg>
                    </div>

                    <div>
                        <h3 class="text-base font-bold text-gray-900">
                            Reject Requisition
                        </h3>
                        <p class="text-xs text-gray-500">
                            This action permanently ends the approval process.
                        </p>
                    </div>
                </div>
            </div>

            {{-- BODY --}}
            <div class="space-y-4 px-5 py-5">

                <div>
                    <label class="text-xs font-semibold text-gray-600">
                        Requisition Number
                    </label>

                    <p class="mt-1 text-sm font-bold text-gray-900">
                        {{ $rejectRequisitionRef }}
                    </p>
                </div>

                <div>
                    <label class="mb-1.5 block text-xs font-semibold
                        text-gray-700">
                        Reason for Rejection
                        <span class="text-red-600">*</span>
                    </label>

                    <textarea
                        wire:model.defer="rejectionReason"
                        rows="4"
                        maxlength="2000"
                        placeholder="Explain why this requisition is being rejected..."
                        class="w-full rounded-lg border border-gray-300 px-3 py-2
                            text-sm text-gray-800 placeholder:text-gray-400
                            focus:border-red-500 focus:ring-2
                            focus:ring-red-100"
                    ></textarea>

                    @error('rejectionReason')
                        <p class="mt-1 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="rounded-lg border border-red-100 bg-red-50 p-3">
                    <p class="text-xs leading-relaxed text-red-700">
                        Once rejected, this requisition cannot proceed to
                        another approval stage or be resubmitted through
                        the normal workflow.
                    </p>
                </div>
            </div>

            {{-- FOOTER --}}
            <div class="flex justify-end gap-2 border-t border-gray-100
                px-5 py-4">

                <button
                    type="button"
                    @click="open = false"
                    class="rounded-lg border border-gray-300 px-4 py-2
                        text-xs font-semibold text-gray-600
                        hover:bg-gray-50"
                >
                    Cancel
                </button>

                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    wire:target="confirmRejection"
                    class="inline-flex items-center gap-2 rounded-lg
                        bg-red-600 px-4 py-2 text-xs font-semibold
                        text-white hover:bg-red-700
                        disabled:cursor-not-allowed disabled:opacity-60"
                >
                    <span wire:loading.remove
                        wire:target="confirmRejection">
                        Confirm Rejection
                    </span>

                    <span wire:loading
                        wire:target="confirmRejection">
                        Rejecting...
                    </span>
                </button>

            </div>
        </form>
    </div>
</div>