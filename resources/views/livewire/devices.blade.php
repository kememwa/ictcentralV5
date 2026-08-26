<div class="space-y-4">

    <x-data-card title="Device Inventory" subtitle="All devices currently tracked">
        <x-slot name="actions">
            {{-- Search + filter --}}
            <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                <div class="relative flex-1 sm:w-72">
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/>
                    </svg>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search devices…"
                        class="w-full pl-10 pr-3 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                </div>

                @if($search)
                    <button wire:click="$set('search', ''); $set('actionFilter', '')"
                        class="inline-flex items-center justify-center gap-1 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-medium text-slate-600 hover:bg-slate-50">
                        Clear
                    </button>
                @endif
            </div>
        </x-slot>
    

    {{-- ===== DESKTOP TABLE (lg and up) ===== --}}
<div class="hidden lg:block overflow-x-auto">
    <table class="w-full text-xs">
        <thead class="bg-gray-50 text-left uppercase tracking-wider text-[11px] text-gray-500">
            <tr>
                <th class="px-3 py-2">Device</th>
                <th class="px-3 py-2">Type</th>
                <th class="px-3 py-2 hidden xl:table-cell">Purchase</th>
                <th class="px-3 py-2 hidden xl:table-cell">Model</th>
                <th class="px-3 py-2 hidden xl:table-cell">Custodian</th>
                <th class="px-3 py-2">Tag</th>
                <th class="px-3 py-2 hidden xl:table-cell">Serial</th>
                <th class="px-3 py-2">Status</th>
                <th class="px-3 py-2 text-right">Actions</th>
            </tr>
        </thead>

        <tbody class="divide-y divide-gray-100">
            @forelse ($devices as $device)
                <tr class="hover:bg-gray-50 transition">
                    
                    {{-- Device --}}
                    <td class="px-3 py-2">
                        <p class="font-medium text-gray-900 truncate max-w-[140px]">
                            {{ $device->name }}
                        </p>
                    </td>

                    {{-- Type --}}
                    <td class="px-3 py-2 capitalize text-gray-700">
                        {{ $device->type }}
                    </td>

                    {{-- Purchase Date --}}
                    <td class="px-3 py-2 hidden xl:table-cell text-gray-500">
                        {{ $device->purchase_date }}
                    </td>

                    {{-- Model --}}
                    <td class="px-3 py-2 hidden xl:table-cell text-gray-700">
                        {{ $device->model }}
                    </td>

                    {{-- Custodian --}}
                    <td class="px-3 py-2 hidden xl:table-cell text-gray-700 truncate max-w-[120px]">
                        {{ $device->user->name ?? 'Unassigned' }}
                    </td>

                    {{-- Tag --}}
                    <td class="px-3 py-2">
                        <span class="font-mono text-[10px] px-1.5 py-0.5 rounded bg-gray-100 text-gray-700">
                            {{ $device->tag_number }}
                        </span>
                    </td>

                    {{-- Serial --}}
                    <td class="px-3 py-2 hidden xl:table-cell font-mono text-[10px] text-gray-500">
                        {{ $device->serial_number }}
                    </td>

                    {{-- Status --}}
                    <td class="px-3 py-2">
                        @if($device->user_id)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-medium rounded-full bg-green-100 text-green-700">
                                <span class="w-1 h-1 bg-green-500 rounded-full"></span> Assigned
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-medium rounded-full bg-rose-100 text-rose-700">
                                <span class="w-1 h-1 bg-rose-500 rounded-full"></span> Unassigned
                            </span>
                        @endif
                    </td>

                    {{-- Actions --}}
                    <td class="px-3 py-2 text-right">
                        @include('partials.device-actions', ['device' => $device])
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="9" class="py-12 text-center">
                        <div class="flex flex-col items-center gap-2 text-gray-500">
                            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0l-2 7H6l-2-7m16 0H4"/>
                            </svg>
                            <p class="font-medium text-gray-700 text-sm">No devices found</p>
                            <p class="text-[11px]">Try adjusting your search or add a new device</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

    {{-- ===== MOBILE / TABLET CARDS (below lg) ===== --}}
    <div class="lg:hidden divide-y divide-gray-100">
        @forelse ($devices as $device)
            <div class="p-4 hover:bg-gray-50 transition">
                {{-- Header row --}}
                <div class="flex items-start justify-between gap-3 mb-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 text-white flex items-center justify-center font-semibold text-sm shrink-0">
                            {{ strtoupper(substr($device->name, 0, 2)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="font-semibold text-gray-900 truncate">{{ $device->name }}</p>
                            <p class="text-xs text-gray-500 capitalize">{{ $device->type }} · {{ $device->location }}</p>
                        </div>
                    </div>
                    @if($device->user_id)
                        <span class="shrink-0 inline-flex items-center gap-1.5 px-2 py-0.5 text-xs font-medium rounded-full bg-green-100 text-green-700">
                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span> Assigned
                        </span>
                    @else
                        <span class="shrink-0 inline-flex items-center gap-1.5 px-2 py-0.5 text-xs font-medium rounded-full bg-rose-100 text-rose-700">
                            <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span> Unassigned
                        </span>
                    @endif
                </div>

                {{-- Detail grid --}}
                <dl class="grid grid-cols-2 gap-x-4 gap-y-2 text-xs">
                    <div>
                        <dt class="text-gray-500">Cost</dt>
                        <dd class="font-medium text-gray-900">${{ number_format($device->cost ?? 0, 2) }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Tag #</dt>
                        <dd class="font-mono text-gray-700">{{ $device->tag_number }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Model</dt>
                        <dd class="text-gray-700 truncate">{{ $device->model }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Custodian</dt>
                        <dd class="text-gray-700 truncate">{{ $device->user->name ?? 'Unassigned' }}</dd>
                    </div>
                    <div class="col-span-2">
                        <dt class="text-gray-500">Serial</dt>
                        <dd class="font-mono text-gray-700 truncate">{{ $device->serial_number }}</dd>
                    </div>
                </dl>

                {{-- Actions --}}
                <div class="mt-3 pt-3 border-t border-gray-100 flex justify-end">
                    @include('partials.device-actions', ['device' => $device])
                </div>
            </div>
        @empty
            <div class="py-16 text-center">
                <p class="font-medium text-gray-700">No devices found</p>
                <p class="text-xs text-gray-500 mt-1">Try adjusting your search or add a new device</p>
            </div>
        @endforelse
    </div>

    @if($devices->hasPages())
        <div class="px-4 sm:px-6 py-4 border-t border-gray-100">
            {{ $devices->links() }}
        </div>
    @endif
</x-data-card>


    {{-- ============================================================ --}}
    {{-- ASSIGN MODAL --}}
    {{-- ============================================================ --}}
    <div x-data="{ show: @entangle('showAssignModal') }"
         x-show="show" x-cloak x-transition.opacity
         @keydown.escape.window="show = false"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">

        <div @click.away="show = false" x-transition.scale.origin.center
             class="relative w-full max-w-3xl bg-white rounded-2xl shadow-2xl overflow-hidden">

            {{-- Header --}}
            <div class="flex items-center justify-between p-6 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Assign Device</h3>
                        <p class="text-xs text-gray-500">Allocate this device to a user</p>
                    </div>
                </div>
                <button @click="show = false" class="text-gray-400 hover:bg-gray-200 rounded-full w-8 h-8 inline-flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form wire:submit.prevent="assignDevice" class="p-6 space-y-6 max-h-[70vh] overflow-y-auto">

                {{-- Device Details --}}
                <div>
                    <h4 class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-3">Device Details</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-500">Device Name</p>
                            <p class="text-sm font-medium text-gray-900">{{ $name }}</p>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-500">Tag Number</p>
                            <p class="text-sm font-medium text-gray-900">{{ $tag_number}}</p>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-500">Category</p>
                            <p class="text-sm font-medium text-gray-900">{{ $category }}</p>
                        </div>
                    </div>
                </div>

                {{-- Assignment --}}
                <div>
                    <h4 class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-3">Assignment Details</h4>
                    <div class="space-y-3">
                        <div 
                                x-data="{
                                    open: false,
                                    selectedName: '',
                                }"
                                class="relative"
                            >

                                <!-- Label -->
                                <label class="text-xs font-medium text-gray-700">
                                    Assign User
                                </label>

                                <!-- Input -->
                                <input 
                                    type="text"
                                    wire:model.live.debounce.300ms="userSearch"
                                    x-model="selectedName"
                                    @focus="open = true"
                                    @input="open = true"
                                    @click.outside="open = false"
                                    placeholder="Search users..."
                                    class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-sm focus:ring-2 focus:ring-blue-500"
                                >

                                <!-- Dropdown -->
                                <div 
                                    x-show="open"
                                    x-transition
                                    class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-y-auto"
                                >

                                    <!-- Loading -->
                                    <div wire:loading wire:target="search" class="p-2 text-sm text-gray-500">
                                        Searching...
                                    </div>

                                    <!-- Results -->
                                    <ul wire:loading.remove wire:target="search">
                                        @forelse($users as $user)
                                            <li 
                                                class="px-3 py-2 text-sm hover:bg-gray-100 cursor-pointer"
                                                @click="
                                                    selectedName = '{{ $user['name'] }}';
                                                    $wire.set('selectedUser', {{ $user['id'] }});
                                                    open = false;
                                                "
                                            >
                                                {{ $user['name'] }}
                                            </li>
                                        @empty
                                            <li class="px-3 py-2 text-sm text-gray-500">
                                                No users found
                                            </li>
                                        @endforelse
                                    </ul>
                                </div>

                                @error('user_id') 
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p> 
                                @enderror
                            </div>

                        <div>
                            <label class="text-xs font-medium text-gray-700">Reason</label>
                            <select wire:model="reason"
                                class="mt-1 w-full px-3 py-2 border border-gray-200 text-sm focus:ring-2 focus:ring-blue-500">
                                <option value="">— Select Reason —</option>
                                <option value="New Assignment">New Assignment</option>
                                <option value="Replacement">Replacement</option>
                                <option value="Lost Device">Lost Device</option>
                                <option value="Department Change">Department Change</option>
                                <option value="Upgrade">Upgrade</option>
                            </select>
                            @error('reason') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="text-xs font-medium text-gray-700">Comment</label>
                            <textarea wire:model="comment" rows="3"
                                class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-sm focus:ring-2 focus:ring-blue-500"></textarea>
                            @error('comment') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t border-gray-100">
                    <button type="button" @click="show = false"
                        class="px-4 py-2 text-sm border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-sm text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 rounded-lg shadow-sm">
                        Assign Device
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- UNASSIGN MODAL --}}
    {{-- ============================================================ --}}
    <div x-data="{ show: @entangle('reassignDeviceModal') }"
         x-show="show" x-cloak x-transition.opacity
         @keydown.escape.window="show = false"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">

        <div @click.away="show = false" x-transition.scale.origin.center
             class="relative w-full max-w-3xl bg-white rounded-2xl shadow-2xl overflow-hidden">

            <div class="flex items-center justify-between p-6 border-b border-gray-100 bg-gradient-to-r from-amber-50 to-rose-50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Unassign Device</h3>
                        <p class="text-xs text-gray-500">Return this device to inventory</p>
                    </div>
                </div>
                <button @click="show = false" class="text-gray-400 hover:bg-gray-200 rounded-full w-8 h-8 inline-flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form wire:submit.prevent="unassignDevice" class="p-6 space-y-6 max-h-[70vh] overflow-y-auto">

                <div>
                    <h4 class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-3">Device Details</h4>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-500">Current User</p>
                            <p class="text-sm font-medium text-gray-900 mt-0.5">{{ $currentUser }}</p>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-500">Device</p>
                            <p class="text-sm font-medium text-gray-900 mt-0.5">{{ $name }}</p>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-500">Device Tag Number</p>
                            <p class="text-sm font-medium text-gray-900 mt-0.5">{{ $tag_number }}</p>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-500">Category</p>
                            <p class="text-sm font-medium text-gray-900 mt-0.5">{{ $category }}</p>
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-3">Unassignment Details</h4>
                    <div class="space-y-3">
                        <div>
                            <label class="text-xs font-medium text-gray-700">Reason</label>
                            <select wire:model="reason"
                                class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-sm focus:ring-2 focus:ring-rose-500">
                                <option value="">— Select Reason —</option>
                                <option value="User Terminated">User Terminated</option>
                                <option value="Transferred">Transferred</option>
                                <option value="Device Returned">Device Returned</option>
                            </select>
                            @error('reason') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="text-xs font-medium text-gray-700">Comment</label>
                            <textarea wire:model="comment" rows="3"
                                class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-sm focus:ring-2 focus:ring-rose-500"></textarea>
                            @error('comment') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t border-gray-100">
                    <button type="button" @click="show = false"
                        class="px-4 py-2 text-sm border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-sm text-white bg-gradient-to-r from-rose-600 to-red-600 hover:from-rose-700 hover:to-red-700 rounded-lg shadow-sm">
                        Unassign Device
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- EDIT MODAL --}}
    {{-- ============================================================ --}}
    <div x-data="{ 
        show: @entangle('showEditModal'), 
        showComment: false,
        isResetting: false,
        init() {
            this.$watch('show', value => {
                if (!value) {
                    // Small delay to avoid flicker during close animation
                    setTimeout(() => {
                        if (!this.show) {
                            this.resetForm();
                        }
                    }, 150);
                } else {
                    // Sync when modal opens
                    this.showComment = !@entangle('is_in_good_condition');
                }
            });
        },
        resetForm() {
            if (this.isResetting) return;
            this.isResetting = true;
            
            // Reset local Alpine state
            this.showComment = false;
            
            // Reset Livewire properties
            @this.set('is_in_good_condition', true);
            @this.set('condition_comment', '');
            

            setTimeout(() => {
                this.isResetting = false;
            }, 100);
        },
        closeModal() {
            this.show = false;
            // Don't reset immediately, let the animation finish
            setTimeout(() => {
                if (!this.show) {
                    this.resetForm();
                }
            }, 200);
        }
    }"
    x-init="init()"
    x-show="show" x-cloak x-transition.opacity
    @keydown.escape.window="closeModal()"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">

        <div @click.away="closeModal()" 
            x-transition.scale.origin.center
            class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden">

            <div class="flex items-center justify-between p-6 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900">Edit Device</h3>
                <button @click="closeModal()" class="text-gray-400 hover:bg-gray-200 rounded-full w-8 h-8 inline-flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form wire:submit.prevent="update" class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-medium text-gray-700">Device Name</label>
                        <div class="mt-1 px-3 py-2 border border-gray-200 rounded-lg bg-gray-100 text-sm text-gray-700 select-none">
                            {{ $name }}
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-medium text-gray-700">Current User</label>
                        <div class="mt-1 px-3 py-2 border border-gray-200 rounded-lg bg-gray-100 text-sm text-gray-700 select-none">
                            {{ $currentUser }}
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-medium text-gray-700">Device Category</label>
                        <div class="mt-1 px-3 py-2 border border-gray-200 rounded-lg bg-gray-100 text-sm text-gray-700 select-none">
                            {{ $category }}
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-medium text-gray-700">Purchase Date</label>
                        <div class="mt-1 px-3 py-2 border border-gray-200 rounded-lg bg-gray-100 text-sm text-gray-700 select-none">
                            {{ $purchase_date }}
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-medium text-gray-700">Model</label>
                        <div class="mt-1 px-3 py-2 border border-gray-200 rounded-lg bg-gray-100 text-sm text-gray-700 select-none">
                            {{ $model }}
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-medium text-gray-700">Serial Number</label>
                        <div class="mt-1 px-3 py-2 border border-gray-200 rounded-lg bg-gray-100 text-sm text-gray-700 select-none">
                            {{ $serialNumber }}
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div> 
                        <label class="text-xs font-medium text-gray-700">Tag Number</label> 
                        <input wire:model="tag_number" type="text" 
                            class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-sm focus:ring-2 focus:ring-blue-500"> 
                        @error('tag_number') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror 
                    </div>

                    <div>
                        <label class="text-xs font-medium text-gray-700">Branch</label>
                        <select wire:model="branch"
                                class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-sm focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Branch</option>
                            <option value="HQ">HQ</option>
                            <option value="Tatu-city">Tatu-city</option>
                            <option value="Mombasa">Mombasa</option>
                            <option value="FGS2">FGS2</option>
                            <option value="Wall-street">Wall-street</option>
                        </select>
                        @error('branch') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Device Condition Checkbox -->
                <div class="pt-2">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" 
                            wire:model="is_in_good_condition"
                            @change="showComment = !$event.target.checked"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                        <span class="text-sm font-medium text-gray-700">Device is in good condition</span>
                    </label>
                </div>

                <!-- Conditional Comment Field (appears only when device is NOT in good condition) -->
                <div x-show="showComment" 
                    x-transition.duration.200ms
                    class="space-y-2">
                    <label class="text-xs font-medium text-gray-700">Condition Comments <span class="text-red-500">*</span></label>
                    <textarea wire:model="condition_comment" 
                            rows="3"
                            placeholder="Please describe the issues or damage..."
                            class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-sm focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                    @error('condition_comment') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    <p class="text-xs text-gray-500">Please provide details about the device's condition</p>
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t border-gray-100">
                    <button type="button" @click="closeModal()"
                        class="px-4 py-2 text-sm border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-sm text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 rounded-lg shadow-sm">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>


    <div x-data="{ show: @entangle('showDeleteModal') }"
         x-show="show" x-cloak x-transition.opacity
         @keydown.escape.window="show = false"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">

        <div @click.away="show = false" x-transition.scale.origin.center
             class="relative w-full max-w-3xl bg-white rounded-2xl shadow-2xl overflow-hidden">

            <div class="flex items-center justify-between p-6 border-b border-gray-100 bg-gradient-to-r from-amber-50 to-rose-50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Archive Device</h3>
                        <p class="text-xs text-gray-500">Archive this device from the inventory</p>
                    </div>
                </div>
                <button @click="show = false" class="text-gray-400 hover:bg-gray-200 rounded-full w-8 h-8 inline-flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form wire:submit.prevent="deleteDevice" class="p-6 space-y-6 max-h-[70vh] overflow-y-auto">

                <div>
                    <h4 class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-3">Device Details</h4>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                       
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-500">Device</p>
                            <p class="text-sm font-medium text-gray-900 mt-0.5">{{ $name }}</p>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-500">Device Tag Number</p>
                            <p class="text-sm font-medium text-gray-900 mt-0.5">{{ $tag_number }}</p>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-500">Category</p>
                            <p class="text-sm font-medium text-gray-900 mt-0.5">{{ $category }}</p>
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-3">Delete Details</h4>
                    <div class="space-y-3">
                        <div>
                            <label class="text-xs font-medium text-gray-700">Reason</label>
                            <select wire:model="reason"
                                class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-sm focus:ring-2 focus:ring-rose-500">
                                <option value="">— Select Reason —</option>
                                <option value="Old Device">Old Device</option>
                                <option value="Cannot be Repaired">Cannot Be Repaired</option>
                            </select>
                            @error('reason') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t border-gray-100">
                    <button type="button" @click="show = false"
                        class="px-4 py-2 text-sm border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-sm text-white bg-gradient-to-r from-rose-600 to-red-600 hover:from-rose-700 hover:to-red-700 rounded-lg shadow-sm">
                        Archive Device
                    </button>
                </div>
            </form>
        </div>
    </div>


</div>
