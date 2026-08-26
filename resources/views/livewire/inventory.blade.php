<div class="space-y-6">

    {{-- ===== Page Header ===== --}}
    <x-page-header
        title="Kim-Fay Inventory & Assets"
        subtitle="Track, assign and manage devices across the organization">
        <x-slot name="actions">
            <button
                type="button"
                wire:click="openDeviceModal"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-medium rounded-xl shadow-sm hover:shadow-md transition group"
            >
                <svg class="w-4 h-4 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add New Device
            </button>
        </x-slot>
    </x-page-header>

   

    {{-- ===== Quick stat strip (optional but recommended) ===== --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <x-stat-card label="Total Devices"   :value="$totalDevices ?? 0"    color="indigo"  icon="cube"/>
        <x-stat-card label="Assigned"        :value="$assignedDevices ?? 0" color="emerald" icon="check"/>
        <x-stat-card label="Unassigned"      :value="$unassignedDevices ?? 0" color="amber" icon="clock"/>
        <x-stat-card label="Total Value"     :value="'$'.number_format($totalValue ?? 0, 0)" color="blue" icon="dollar"/>
    </div>

    {{-- ===== Devices Table (Livewire) ===== --}}
    @livewire('devices')

    {{-- ===== Add Device Modal — TELEPORTED (survives wire:navigate) ===== --}}
    <template x-teleport="body">
        <div
            x-data="{ isOpen: false }"
            x-on:open-device-modal.window="isOpen = true"
            x-on:close-device-modal.window="isOpen = false"
            x-init="$watch('isOpen', v => document.body.classList.toggle('overflow-hidden', v))"
        >
            <div
                x-show="isOpen"
                x-transition.opacity
                @keydown.escape.window="isOpen = false"
                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
            >
                <div
                    @click.away="isOpen = false"
                    x-transition.scale.origin.center
                    class="relative w-full max-w-md bg-white dark:bg-gray-900 rounded-2xl shadow-2xl overflow-hidden"
                >
                    {{-- Header --}}
                    <div class="flex items-start justify-between p-6 border-b border-gray-100 dark:border-gray-800">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Create New Device</h3>
                            <p class="text-sm text-gray-500 mt-0.5">Add a new device to the inventory</p>
                        </div>
                        <button @click="isOpen = false"
                            class="text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-700 rounded-full w-8 h-8 inline-flex items-center justify-center transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Form --}}
                    <form wire:submit.prevent="createDevice" class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">

                       <div class="grid grid-cols-2 gap-3">

                           <div 
                                x-data="{
                                    open: false,
                                    selectedName: '',
                                }"
                                class="relative"
                            >

                                <!-- Label -->
                                <label class="text-xs font-medium text-gray-700 dark:text-gray-300">
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
                                    class="mt-1 w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-sm focus:ring-2 focus:ring-blue-500"
                                >

                                <!-- Dropdown -->
                                <div 
                                    x-show="open"
                                    x-transition
                                    class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg max-h-60 overflow-y-auto"
                                >

                                    <!-- Loading -->
                                    <div wire:loading wire:target="search" class="p-2 text-sm text-gray-500">
                                        Searching...
                                    </div>

                                    <!-- Results -->
                                    <ul wire:loading.remove wire:target="search">
                                        @forelse($users as $user)
                                            <li 
                                                class="px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer"
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

                                @error('selectedUser') 
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p> 
                                @enderror
                            </div>

                            <div>
                                <label class="text-xs font-medium text-gray-700 dark:text-gray-300">Type/Category</label>
                                <select wire:model="type"
                                        class="mt-1 w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-sm focus:ring-2 focus:ring-blue-500">
                                    <option value="">Select Category</option>
                                    <option value="Laptop">Laptop</option>
                                    <option value="Desktop">Desktop</option>
                                    <option value="TV">TV</option>
                                    <option value="Monitor">Monitor</option>
                                    <option value="Printer">Printer</option>
                                </select>
                                @error('type') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                        </div>

                        <div class="grid grid-cols-2 gap-3">

                            <div>
                                <label class="text-xs font-medium text-gray-700 dark:text-gray-300">Device Name</label>
                                <input wire:model="name" type="text"
                                       class="mt-1 w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-sm focus:ring-2 focus:ring-blue-500">
                                @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-xs font-medium text-gray-700 dark:text-gray-300">Cost</label>
                                <input wire:model="cost" type="number" step="0.01"
                                       class="mt-1 w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-sm focus:ring-2 focus:ring-blue-500">
                                @error('cost') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                             <div>
                                <label class="text-xs font-medium text-gray-700 dark:text-gray-300">Purchase Date</label>
                                <input wire:model="purchase_date" type="date"
                                       class="mt-1 w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-sm focus:ring-2 focus:ring-blue-500">
                                @error('purchase_date') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-xs font-medium text-gray-700 dark:text-gray-300">Model</label>
                                <input wire:model="model" type="text"
                                       class="mt-1 w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-sm focus:ring-2 focus:ring-blue-500">
                                @error('model') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>         
                        </div>

                       <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-xs font-medium text-gray-700 dark:text-gray-300">Tag Number</label>
                                <input wire:model="tag_number" type="text"
                                       class="mt-1 w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-sm focus:ring-2 focus:ring-blue-500">
                                @error('tag_number') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-xs font-medium text-gray-700 dark:text-gray-300">Serial Number</label>
                                <input wire:model="serial_number" type="text"
                                       class="mt-1 w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-sm focus:ring-2 focus:ring-blue-500">
                                @error('serial_number') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="flex justify-end gap-2 pt-4 border-t border-gray-100 dark:border-gray-800">
                            <button type="button" @click="isOpen = false"
                                class="px-4 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800">
                                Cancel
                            </button>
                            <button type="submit"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm">
                                <span wire:loading.remove wire:target="createDevice">Add Device</span>
                                <span wire:loading wire:target="createDevice">Saving…</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>

</div>
