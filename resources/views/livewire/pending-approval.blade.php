<div class="space-y-6">

    <x-page-header
        title="Pending Approval"
        subtitle="Review and approve device assignments awaiting your action.">
        <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-3 py-1 text-xs font-medium text-amber-700 ring-1 ring-amber-200">
            <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
            {{ $pendingDevices->total() }} pending
        </span>
    </x-page-header>

    <x-data-card title="Pending Devices" subtitle="Devices awaiting your approval before assignment.">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50">
                    <tr class="text-left text-xs font-medium uppercase tracking-wider text-slate-500">
                        <th class="px-5 sm:px-6 py-3">Device</th>
                        <th class="px-5 sm:px-6 py-3">Category</th>
                        <th class="px-5 sm:px-6 py-3 text-right">Value</th>
                        <th class="px-5 sm:px-6 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pendingDevices as $device)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="px-5 sm:px-6 py-4">
                                <p class="font-medium text-slate-900">{{ $device->name }}</p>
                                <p class="text-xs text-slate-500">{{ $device->color }}</p>
                            </td>
                            <td class="px-5 sm:px-6 py-4 text-slate-700">{{ $device->category }}</td>
                            <td class="px-5 sm:px-6 py-4 text-right font-medium text-slate-900">
                                ${{ number_format($device->value, 2) }}
                            </td>
                            <td class="px-5 sm:px-6 py-4 text-right">
                                <div x-data="{ open: false }">
                                    <button @click="open = true"
                                        class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-emerald-700">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Approve
                                    </button>

                                    <template x-teleport="body">
                                        <div x-show="open" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                                            <div x-show="open" x-transition.opacity @click="open = false"
                                                 class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
                                            <div x-show="open" x-transition
                                                 class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl">
                                                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                </div>
                                                <h3 class="mt-4 text-lg font-semibold text-slate-900">Confirm Approval</h3>
                                                <p class="mt-1.5 text-sm text-slate-600">
                                                    Approve <span class="font-medium text-slate-900">{{ $device->name }}</span>? This will assign it to the user.
                                                </p>
                                                <div class="mt-6 flex justify-end gap-2">
                                                    <button @click="open = false"
                                                        class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                                                        Cancel
                                                    </button>
                                                    <button @click="open = false; $nextTick(() => { $wire.approveDevice({{ $device->id }}) })"
                                                        class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">
                                                        Yes, approve
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center">
                                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <p class="mt-3 text-sm font-medium text-slate-900">All caught up</p>
                                <p class="mt-1 text-xs text-slate-500">No pending devices for approval.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pendingDevices->hasPages())
            <div class="border-t border-slate-100 px-5 sm:px-6 py-3">
                {{ $pendingDevices->links() }}
            </div>
        @endif
    </x-data-card>
</div>
