<div class="space-y-6">
    <!-- Page Header -->
    <x-page-header title="Device Analytics And Inventory" subtitle="Complete device management and analytics">
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
            <button wire:click="openCategoryReport('all')" class="inline-flex items-center gap-2 rounded-lg bg-purple-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-purple-700">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Generate Full Report
            </button>
        </x-slot>
    </x-page-header>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mt-3">
        @foreach($reportCards as $card)
            <button wire:click="openCategoryReport('{{ $card['key'] }}')" class="group relative overflow-hidden text-left bg-white rounded-2xl shadow-sm border border-gray-200 p-5 hover:shadow-lg transition-all duration-200">
                <!-- Card content -->
                <div class="relative">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">{{ $card['label'] }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $card['sub'] }}</p>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-{{ $card['color'] }}-100 text-{{ $card['color'] }}-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/>
                            </svg>
                        </div>
                    </div>
                    <p class="mt-4 text-3xl font-bold text-gray-900">{{ $card['value'] }}</p>
                    <p class="mt-1 text-xs text-gray-500">{{ $card['trend'] }}</p>
                </div>
            </button>
        @endforeach
    </div>

    <!-- Category Tabs -->
    <div class="mb-6 border-b border-gray-200">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center">
            <li class="mr-2">
                <button wire:click="$set('activeCategory', 'all')" 
                    class="inline-block p-4 rounded-t-lg {{ $activeCategory === 'all' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500 hover:text-gray-700' }}">
                    All Devices
                </button>
            </li>
            <li class="mr-2">
                <button wire:click="$set('activeCategory', 'active devices with issues')"
                    class="inline-block p-4 rounded-t-lg {{ $activeCategory === 'active devices with issues' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500 hover:text-gray-700' }}">
                    Active Devices with issues
                </button>
            </li>
            <li class="mr-2">
                <button wire:click="$set('activeCategory', 'inactive devices with issues')"
                    class="inline-block p-4 rounded-t-lg {{ $activeCategory === 'inactive devices with issues' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500 hover:text-gray-700' }}">
                    Inactive Devices with issues
                </button>
            </li>
            <li class="mr-2">
                <button wire:click="$set('activeCategory', 'unassigned devices in good condition')"
                    class="inline-block p-4 rounded-t-lg {{ $activeCategory === 'unassigned devices in good condition' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500 hover:text-gray-700' }}">
                    Unassigned Devices in good condition
                </button>
            </li>
            <li class="mr-2">
                <button wire:click="$set('activeCategory', 'archived devices')"
                    class="inline-block p-4 rounded-t-lg {{ $activeCategory === 'archived devices' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500 hover:text-gray-700' }}">
                    Archived Devices
                </button>
            </li>
            <li class="mr-2">
                <button wire:click="$set('activeCategory', 'printers')"
                    class="inline-block p-4 rounded-t-lg {{ $activeCategory === 'printers' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500 hover:text-gray-700' }}">
                    Printers
                </button>
            </li>
        </ul>
    </div>

    <!-- All/Active/Inactive Devices View -->
    @if(in_array($activeCategory, ['all']))
        @livewire('devices')
    @endif

    @if(in_array($activeCategory, ['active devices with issues', 'inactive devices with issues']))
    <x-data-card 
            title="{{ $this->getCategoryTitle()['title'] }}" 
            subtitle="{{ $this->getCategoryTitle()['subtitle'] }}">
            <x-slot name="actions">
                <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                    <div class="relative flex-1 sm:w-72">
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search devices…"
                            class="w-full pl-10 pr-3 py-2 bg-white border border-gray-200 rounded-lg text-sm">
                    </div>
                    <select wire:model.live="actionFilter" class="px-3 py-2 bg-white border border-gray-200 rounded-lg text-sm">
                        <option value="">All actions</option>
                        <option value="assigned">Assigned</option>
                        <option value="returned">Returned</option>
                    </select>
                </div>
            </x-slot>
            <div class="hidden lg:block overflow-x-auto">
            <table class="w-full text-xs">
                <thead class="bg-gray-50 text-left uppercase tracking-wider text-[11px] text-gray-500">
                    <tr>
                        <th class="px-3 py-2 w-[15%] min-w-[140px]">Device Name</th>
                        <th class="px-3 py-2 w-[10%] min-w-[80px]">Type</th>
                        <th class="px-3 py-2 w-[12%] min-w-[100px]">Current User</th>
                        <th class="px-3 py-2 w-[10%] min-w-[90px]">Tag Number</th>
                        <th class="px-3 py-2 w-[43%] min-w-[250px]">IT Comments</th>
                        <th class="px-3 py-2 w-[10%] min-w-[80px] text-center">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse ($devices as $device)
                        <tr class="hover:bg-gray-50 transition">
                            {{-- Device Name --}}
                            <td class="px-3 py-2">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-md bg-gradient-to-br from-blue-500 to-indigo-600 text-white flex items-center justify-center text-[10px] font-semibold shrink-0">
                                        {{ strtoupper(substr($device->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-medium text-gray-900 truncate max-w-[120px]" title="{{ $device->name }}">
                                            {{ $device->name }}
                                        </p>
                                        <p class="text-[10px] text-gray-400 truncate max-w-[120px]">
                                            {{ $device->model }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            {{-- Type --}}
                            <td class="px-3 py-2">
                                <span class="inline-flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full 
                                        {{ $device->type === 'laptop' ? 'bg-blue-500' : 
                                        ($device->type === 'desktop' ? 'bg-purple-500' : 
                                        ($device->type === 'printer' ? 'bg-amber-500' : 'bg-gray-500')) }}">
                                    </span>
                                    <span class="capitalize text-gray-700">
                                        {{ $device->type }}
                                    </span>
                                </span>
                            </td>

                            {{-- Current User --}}
                            <td class="px-3 py-2">
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-3 h-3 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span class="text-gray-700 truncate max-w-[100px]" title="{{ $device->user->name ?? 'Unassigned' }}">
                                        {{ $device->user->name ?? 'Unassigned' }}
                                    </span>
                                </div>
                            </td>

                            {{-- Tag Number --}}
                            <td class="px-3 py-2">
                                <code class="text-[11px] font-mono bg-gray-100 px-1.5 py-0.5 rounded text-gray-700">
                                    {{ $device->tag_number ?? 'N/A' }}
                                </code>
                            </td>

                            {{-- IT Comments --}}
                            <td class="px-3 py-2">
                                <div class="max-w-[300px]">
                                    @php
                                        $issue = $device->issues->first();
                                    @endphp
                                    
                                    @if($issue && $issue->comment)
                                        <div class="group relative">
                                            <div class="flex items-start gap-1.5">
                                                <svg class="w-3 h-3 text-blue-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                                </svg>
                                                <p class="text-gray-600 text-[11px] leading-relaxed break-words line-clamp-2 hover:line-clamp-none transition-all duration-200">
                                                    {{ $issue->comment }}
                                                </p>
                                            </div>
                                            @if(strlen($issue->comment) > 80)
                                                <div class="absolute bottom-full left-0 mb-2 hidden group-hover:block z-10">
                                                    <div class="bg-gray-900 text-white text-xs rounded-lg p-2 max-w-xs shadow-lg">
                                                        {{ $issue->comment }}
                                                        <div class="absolute left-4 top-full w-2 h-2 bg-gray-900 transform rotate-45 -mt-1"></div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <div class="flex items-center gap-1.5">
                                            <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                            </svg>
                                            <span class="text-gray-400 italic text-[11px]">No comments</span>
                                        </div>
                                    @endif
                                </div>
                            </td>

                            {{-- Actions --}}
                            <td class="px-3 py-2">
                                <div class="flex items-center justify-center gap-1">
                                    {{-- Mark as Resolved Button --}}
                                    @if(isset($device) && isset($issue) && $issue->status !== 'resolved')
                                        <div x-data="{ open: false }">
                                            <button @click="open = true"
                                                    class="p-1.5 rounded-md text-green-600 hover:bg-green-50 transition"
                                                    title="Mark as resolved">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </button>

                                            {{-- Modal --}}
                                            <template x-teleport="body">
                                                <div x-show="open" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                                                    {{-- Backdrop --}}
                                                    <div x-show="open" x-transition.opacity @click="open = false"
                                                        class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
                                                    
                                                    {{-- Modal Content --}}
                                                    <div x-show="open" x-transition
                                                        class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl">
                                                        
                                                        {{-- Icon --}}
                                                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-green-100 text-green-600">
                                                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                        </div>
                                                        
                                                        {{-- Title --}}
                                                        <h3 class="mt-4 text-lg font-semibold text-slate-900">
                                                            Confirm Resolution
                                                        </h3>
                                                        
                                                        {{-- Description --}}
                                                        <p class="mt-1.5 text-sm text-slate-600">
                                                            Resolve issue for <span class="font-medium text-slate-900">{{ $device->name ?? 'this device' }}</span>? 
                                                            This will mark the issue as completed.
                                                        </p>
                                                        
                                                        {{-- Actions --}}
                                                        <div class="mt-6 flex justify-end gap-2">
                                                            <button @click="open = false"
                                                                    class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                                                                Cancel
                                                            </button>
                                                            <button @click="open = false; $nextTick(() => { $wire.markAsResolved({{ $device->id }}, {{ $issue->id }}) })"
                                                                    class="rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700">
                                                                Yes, resolve
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    @endif
                                    
                                    {{-- View Details Button --}}
                                    @if(isset($device))
                                        <button wire:click="viewDeviceDetails({{ $device->id }})" 
                                                class="p-1.5 rounded-md text-gray-600 hover:bg-gray-100 transition"
                                                title="View details">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center">
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
        @if($devices->hasPages())
                <div class="px-4 sm:px-6 py-4 border-t border-gray-100">
                    {{ $devices->links() }}
                </div>
            @endif
    </x-data-card>
    @endif


    @if($activeCategory=='archived devices')
     <x-data-card 
            title="{{ $this->getCategoryTitle()['title'] }}" 
            subtitle="{{ $this->getCategoryTitle()['subtitle'] }}">
            <x-slot name="actions">
                <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                    <div class="relative flex-1 sm:w-72">
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search devices…"
                            class="w-full pl-10 pr-3 py-2 bg-white border border-gray-200 rounded-lg text-sm">
                    </div>
                    <select wire:model.live="actionFilter" class="px-3 py-2 bg-white border border-gray-200 rounded-lg text-sm">
                        <option value="">All actions</option>
                        <option value="assigned">Assigned</option>
                        <option value="returned">Returned</option>
                    </select>
                </div>
            </x-slot>
            <div class="hidden lg:block overflow-x-auto">
            <table class="w-full text-xs">
                <thead class="bg-gray-50 text-left uppercase tracking-wider text-[11px] text-gray-500">
                    <tr>
                        <th class="px-3 py-2 w-[15%] min-w-[140px]">Device Name</th>
                        <th class="px-3 py-2 w-[10%] min-w-[80px]">Type</th>
                        <th class="px-3 py-2 w-[12%] min-w-[100px]">Previous User</th>
                        <th class="px-3 py-2 w-[10%] min-w-[90px]">Tag Number</th>
                        <th class="px-3 py-2 w-[43%] min-w-[250px]">IT Comments</th>
                        <th class="px-3 py-2 w-[10%] min-w-[80px] text-center">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse ($devices as $device)
                        <tr class="hover:bg-gray-50 transition">
                            {{-- Device Name --}}
                            <td class="px-3 py-2">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-md bg-gradient-to-br from-blue-500 to-indigo-600 text-white flex items-center justify-center text-[10px] font-semibold shrink-0">
                                        {{ strtoupper(substr($device->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-medium text-gray-900 truncate max-w-[120px]" title="{{ $device->name }}">
                                            {{ $device->name }}
                                        </p>
                                        <p class="text-[10px] text-gray-400 truncate max-w-[120px]">
                                            {{ $device->model }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            {{-- Type --}}
                            <td class="px-3 py-2">
                                <span class="inline-flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full 
                                        {{ $device->type === 'laptop' ? 'bg-blue-500' : 
                                        ($device->type === 'desktop' ? 'bg-purple-500' : 
                                        ($device->type === 'printer' ? 'bg-amber-500' : 'bg-gray-500')) }}">
                                    </span>
                                    <span class="capitalize text-gray-700">
                                        {{ $device->type }}
                                    </span>
                                </span>
                            </td>

                            {{-- Current User --}}
                            <td class="px-3 py-2">
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-3 h-3 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span class="text-gray-700 truncate max-w-[100px]" title="{{ $device->user->name ?? 'Unassigned' }}">
                                        {{ $device->latestAssignmentLog?->previousUser?->name ?? 'not set' }}
                                    </span>
                                </div>
                            </td>

                            {{-- Tag Number --}}
                            <td class="px-3 py-2">
                                <code class="text-[11px] font-mono bg-gray-100 px-1.5 py-0.5 rounded text-gray-700">
                                    {{ $device->tag_number ?? 'N/A' }}
                                </code>
                            </td>

                            {{-- IT Comments --}}
                            <td class="px-3 py-2">
                                <div class="max-w-[300px]">
                                    @php
                                        $issue = $device->issues->first();
                                    @endphp
                                    
                                    @if($issue && $issue->comment)
                                        <div class="group relative">
                                            <div class="flex items-start gap-1.5">
                                                <svg class="w-3 h-3 text-blue-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                                </svg>
                                                <p class="text-gray-600 text-[11px] leading-relaxed break-words line-clamp-2 hover:line-clamp-none transition-all duration-200">
                                                    {{ $issue->comment }}
                                                </p>
                                            </div>
                                            @if(strlen($issue->comment) > 80)
                                                <div class="absolute bottom-full left-0 mb-2 hidden group-hover:block z-10">
                                                    <div class="bg-gray-900 text-white text-xs rounded-lg p-2 max-w-xs shadow-lg">
                                                        {{ $issue->comment }}
                                                        <div class="absolute left-4 top-full w-2 h-2 bg-gray-900 transform rotate-45 -mt-1"></div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <div class="flex items-center gap-1.5">
                                            <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                            </svg>
                                            <span class="text-gray-400 italic text-[11px]">No comments</span>
                                        </div>
                                    @endif
                                </div>
                            </td>

                            {{-- Actions --}}
                            <td class="px-3 py-2">
                                 <button
                                    wire:click="restoreDevice({{ $device->id }})"
                                    class="px-3 py-1 text-xs bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition">

                                    Restore

                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center">
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
        @if($devices->hasPages())
                <div class="px-4 sm:px-6 py-4 border-t border-gray-100">
                    {{ $devices->links() }}
                </div>
            @endif
    </x-data-card>
        
    @endif


    @if($activeCategory =='unassigned devices in good condition')
    <x-data-card 
            title="{{ $this->getCategoryTitle()['title'] }}" 
            subtitle="{{ $this->getCategoryTitle()['subtitle'] }}">
            <x-slot name="actions">
                <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                    <div class="relative flex-1 sm:w-72">
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search devices…"
                            class="w-full pl-10 pr-3 py-2 bg-white border border-gray-200 rounded-lg text-sm">
                    </div>
                    <select wire:model.live="actionFilter" class="px-3 py-2 bg-white border border-gray-200 rounded-lg text-sm">
                        <option value="">All actions</option>
                        <option value="assigned">Assigned</option>
                        <option value="returned">Returned</option>
                    </select>
                </div>
            </x-slot>
            <div class="hidden lg:block overflow-x-auto">
            <table class="w-full text-xs">
                <thead class="bg-gray-50 text-left uppercase tracking-wider text-[11px] text-gray-500">
                    <tr>
                        <th class="px-3 py-2 w-[15%] min-w-[140px]">Device Name</th>
                        <th class="px-3 py-2 w-[10%] min-w-[80px]">Type</th>
                        <th class="px-3 py-2 w-[12%] min-w-[100px]">Previous User</th>
                        <th class="px-3 py-2 w-[10%] min-w-[90px]">Tag Number</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse ($devices as $device)
                        <tr class="hover:bg-gray-50 transition">
                            {{-- Device Name --}}
                            <td class="px-3 py-2">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-md bg-gradient-to-br from-blue-500 to-indigo-600 text-white flex items-center justify-center text-[10px] font-semibold shrink-0">
                                        {{ strtoupper(substr($device->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-medium text-gray-900 truncate max-w-[120px]" title="{{ $device->name }}">
                                            {{ $device->name }}
                                        </p>
                                        <p class="text-[10px] text-gray-400 truncate max-w-[120px]">
                                            {{ $device->model }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            {{-- Type --}}
                            <td class="px-3 py-2">
                                <span class="inline-flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full 
                                        {{ $device->type === 'laptop' ? 'bg-blue-500' : 
                                        ($device->type === 'desktop' ? 'bg-purple-500' : 
                                        ($device->type === 'printer' ? 'bg-amber-500' : 'bg-gray-500')) }}">
                                    </span>
                                    <span class="capitalize text-gray-700">
                                        {{ $device->type }}
                                    </span>
                                </span>
                            </td>

                            {{-- Previous User --}}
                            <td class="px-3 py-2">
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-3 h-3 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span class="text-gray-700 truncate max-w-[100px]" title="{{ $device->user->name ?? 'Unassigned' }}">
                                        {{ $device->latestAssignmentLog?->previousUser?->name ?? 'not set' }}
                                    </span>
                                </div>
                            </td>

                            {{-- Tag Number --}}
                            <td class="px-3 py-2">
                                <code class="text-[11px] font-mono bg-gray-100 px-1.5 py-0.5 rounded text-gray-700">
                                    {{ $device->tag_number ?? 'N/A' }}
                                </code>
                            </td>

                            
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center">
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
        @if($devices->hasPages())
                <div class="px-4 sm:px-6 py-4 border-t border-gray-100">
                    {{ $devices->links() }}
                </div>
            @endif
    </x-data-card>
    @endif


    <!-- Toners View -->
    @if($activeCategory === 'toners')
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-semibold text-gray-800">Toners Management</h2>
                    <button wire:click="openAddTonerModal" class="px-3 py-1 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                        Add Toner
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($toners as $toner)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                            <div class="flex justify-between items-start">
                                <div class="flex items-center gap-3">
                                    <div class="w-4 h-8 rounded-full {{ $toner->colorClass }}"></div>
                                    <div>
                                        <h3 class="font-semibold">{{ $toner->name }}</h3>
                                        <p class="text-sm text-gray-500">{{ $toner->model }}</p>
                                    </div>
                                </div>
                                <div class="flex gap-1">
                                    <button wire:click="editToner({{ $toner->id }})" class="text-blue-600">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button wire:click="deleteToner({{ $toner->id }})" class="text-red-600">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="mt-3">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-sm">Stock Level</span>
                                    <span class="text-sm font-bold">{{ $toner->stock }} units</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="rounded-full h-2 {{ $toner->stockPercentage > 60 ? 'bg-green-600' : ($toner->stockPercentage > 25 ? 'bg-yellow-600' : 'bg-red-600') }}" style="width: {{ $toner->stockPercentage }}%"></div>
                                </div>
                            </div>
                            <div class="mt-2 text-xs">
                                <span class="text-gray-500">Compatible with: </span>
                                <span>{{ implode(', ', $toner->compatiblePrinters) }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Printers View -->
    @if($activeCategory === 'printers')
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-semibold text-gray-800">Printers Management</h2>
                    <button wire:click="openAddPrinterModal" class="px-3 py-1 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                        Add Printer
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($printers as $printer)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-semibold">{{ $printer->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $printer->model }}</p>
                                    <p class="text-xs text-gray-400 mt-1">{{ $printer->location }}</p>
                                </div>
                                <span class="px-2 py-1 text-xs rounded-full 
                                    {{ $printer->status === 'online' ? 'bg-green-100 text-green-600' : ($printer->status === 'idle' ? 'bg-yellow-100 text-yellow-600' : 'bg-red-100 text-red-600') }}">
                                    {{ $printer->status }}
                                </span>
                            </div>
                            
                            <div class="mt-3">
                                <p class="text-sm font-medium mb-2">Compatible Toners:</p>
                                <div class="flex flex-wrap gap-1">
                                    @foreach($printer->compatibleToners as $toner)
                                        <span class="text-xs bg-blue-100 text-blue-600 px-2 py-1 rounded-full">{{ $toner }}</span>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mt-3 grid grid-cols-2 gap-2 text-sm">
                                <div>
                                    <span class="text-gray-500">Pages Printed:</span>
                                    <span class="font-medium ml-1">{{ $printer->pagesPrinted }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Toner Level:</span>
                                    <span class="font-medium ml-1">{{ $printer->tonerLevel }}%</span>
                                </div>
                            </div>

                            <div class="mt-3 flex gap-2">
                                <button wire:click="editPrinter({{ $printer->id }})" class="flex-1 px-3 py-1 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                                    Edit
                                </button>
                                <button wire:click="viewPrinterDetails({{ $printer->id }})" class="flex-1 px-3 py-1 bg-gray-600 text-white text-sm rounded-lg hover:bg-gray-700">
                                    Details
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Device Modal -->
    @if($showDeviceModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ open: true }" x-show="open" x-cloak>
            <!-- Modal content - same as before but with wire:click -->
            <div class="fixed inset-0 bg-black/50"></div>
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="relative bg-white rounded-xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                    <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4">
                        <h3 class="text-xl font-semibold">{{ $deviceModalMode === 'add' ? 'Add New Device' : 'Edit Device' }}</h3>
                        <button wire:click="$set('showDeviceModal', false)" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form wire:submit.prevent="saveDevice" class="p-6 space-y-4">
                        <!-- Form fields - same as before -->
                        <div>
                            <label class="block text-sm font-medium mb-2">Device Name</label>
                            <input type="text" wire:model="deviceForm.name" class="w-full px-3 py-2 border rounded-lg" required>
                        </div>
                        <!-- Add other form fields... -->
                        
                        <div class="flex justify-end space-x-3 pt-4">
                            <button type="button" wire:click="$set('showDeviceModal', false)" class="px-4 py-2 border rounded-lg">Cancel</button>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    
    <!-- Report Modal -->
    @if($showReportModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ open: true }" x-show="open" x-cloak>
            <div class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="relative w-full max-w-5xl max-h-[90vh] flex flex-col bg-white rounded-2xl shadow-2xl overflow-hidden">
                    <div class="flex items-start justify-between p-6 border-b bg-gradient-to-r from-blue-50 to-purple-50">
                        <div>
                            <h3 class="text-lg font-semibold">{{ $reportTitle }}</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Generated {{ now()->toDayDateTimeString() }}</p>
                        </div>
                        <button wire:click="$set('showReportModal', false)" class="text-gray-400 hover:text-gray-700">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="flex-1 overflow-y-auto p-6">
                        {!! $reportContent !!}
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(session()->has('message'))
        <div class="fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg">
            {{ session('message') }}
        </div>
    @endif

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
                    class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden"
                >
                    {{-- Header --}}
                    <div class="flex items-start justify-between p-6 border-b border-gray-100">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Create New Device</h3>
                            <p class="text-sm text-gray-500 mt-0.5">Add a new device to the inventory</p>
                        </div>
                        <button @click="isOpen = false"
                            class="text-gray-400 hover:bg-gray-100 hover:text-gray-700 rounded-full w-8 h-8 inline-flex items-center justify-center transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Form --}}
                    <form wire:submit.prevent="createDevice" class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">

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
                                    placeholder="Search users...(can be left blank)"
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

                                @error('selectedUser') 
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p> 
                                @enderror
                            </div>


                       <div class="grid grid-cols-2 gap-3">

                            <div>
                                <label class="text-xs font-medium text-gray-700">Type/Category</label>
                                <select wire:model="type"
                                        class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-sm focus:ring-2 focus:ring-blue-500">
                                    <option value="">Select Category</option>
                                    <option value="Laptop">Laptop</option>
                                    <option value="Desktop">Desktop</option>
                                    <option value="TV">TV</option>
                                    <option value="Monitor">Monitor</option>
                                    <option value="Printer">Printer</option>
                                </select>
                                @error('type') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
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

                        <div class="grid grid-cols-2 gap-3">

                            <div>
                                <label class="text-xs font-medium text-gray-700">Device Name</label>
                                <input wire:model="name" type="text"
                                       class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-sm focus:ring-2 focus:ring-blue-500">
                                @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-xs font-medium text-gray-700">Cost</label>
                                <input wire:model="cost" type="number" step="0.01"
                                       class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-sm focus:ring-2 focus:ring-blue-500">
                                @error('cost') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                             <div>
                                <label class="text-xs font-medium text-gray-700">Purchase Date</label>
                                <input wire:model="purchase_date" type="date"
                                       class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-sm focus:ring-2 focus:ring-blue-500">
                                @error('purchase_date') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-xs font-medium text-gray-700">Model</label>
                                <input wire:model="model" type="text"
                                       class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-sm focus:ring-2 focus:ring-blue-500">
                                @error('model') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
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
                                <label class="text-xs font-medium text-gray-700">Serial Number</label>
                                <input wire:model="serial_number" type="text"
                                       class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-sm focus:ring-2 focus:ring-blue-500">
                                @error('serial_number') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="flex justify-end gap-2 pt-4 border-t border-gray-100">
                            <button type="button" @click="isOpen = false"
                                class="px-4 py-2 text-sm border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
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