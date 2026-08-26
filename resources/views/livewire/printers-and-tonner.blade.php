<div class="space-y-6">

    {{-- ===== Page Header ===== --}}
    <x-page-header
        title="Kim-Fay Printers & Toner Management"
        subtitle="Manage all your printers and toner inventory in one place">
    </x-page-header>

    {{-- ===== Main Content ===== --}}

       {{-- ===== Quick stat strip (optional but recommended) ===== --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <x-stat-card label="Total Printers"   :value="$totalDevices ?? 0"    color="indigo"  icon="cube"/>
        <x-stat-card label="Total Active Printers"        :value="$assignedDevices ?? 0" color="emerald" icon="check"/>
        <x-stat-card label="Total Inactive Printers"      :value="$unassignedDevices ?? 0" color="amber" icon="clock"/>
        <x-stat-card label="Total MFI Printers"     :value="'$'.number_format($totalValue ?? 0, 0)" color="blue" icon="dollar"/>
    </div>


    <!-- Category Tabs -->
    <div class="mb-6 border-b border-gray-200">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center">
            <li class="mr-2">
                <button wire:click="$set('activeCategory', 'HQ Printers')" 
                    class="inline-block p-4 rounded-t-lg {{ $activeCategory === 'HQ Printers' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500 hover:text-gray-700' }}">
                    HQ Printers
                </button>
            </li>
            <li class="mr-2">
                <button wire:click="$set('activeCategory', 'tatu city printers')"
                    class="inline-block p-4 rounded-t-lg {{ $activeCategory === 'tatu city printers' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500 hover:text-gray-700' }}">
                    Tatu City Printers
                </button>
            </li>
            <li class="mr-2">
                <button wire:click="$set('activeCategory', 'mombasa printers')"
                    class="inline-block p-4 rounded-t-lg {{ $activeCategory === 'mombasa printers' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500 hover:text-gray-700' }}">
                    Mombasa Printers
                </button>
            </li>
            <li class="mr-2">
                <button wire:click="$set('activeCategory', 'c-suite printers')"
                    class="inline-block p-4 rounded-t-lg {{ $activeCategory === 'c-suite printers' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500 hover:text-gray-700' }}">
                    C-Suite Printers
                </button>
            </li>

            <li class="mr-2">
                <button wire:click="$set('activeCategory', 'mfi printers')"
                    class="inline-block p-4 rounded-t-lg {{ $activeCategory === 'mfi printers' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500 hover:text-gray-700' }}">
                    MFI Printers
                </button>
            </li>
            <li class="mr-2">
                <button wire:click="$set('activeCategory', 'toners')"
                    class="inline-block p-4 rounded-t-lg {{ $activeCategory === 'toners' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500 hover:text-gray-700' }}">
                    Toner Management
                </button>
            </li>
            <li class="mr-2">
                <button wire:click="$set('activeCategory', 'drum units')"
                    class="inline-block p-4 rounded-t-lg {{ $activeCategory === 'drum units' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500 hover:text-gray-700' }}">
                    Drum Units
                </button>
            </li>
            <li class="mr-2">
                <button wire:click="$set('activeCategory', 'idle printers')"
                    class="inline-block p-4 rounded-t-lg {{ $activeCategory === 'idle printers' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500 hover:text-gray-700' }}">
                    idle Printers
                </button>
            </li>

        </ul>
    </div>

    @if($activeCategory === 'idle printers')
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-semibold text-gray-800">Printers Management</h2>
                
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
                            <button wire:click="editPrinterLocation({{ $printer->id }})" class="flex-1 px-3 py-1 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                                Edit Location Details
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

    @if(in_array($activeCategory, ['HQ Printers', 'tatu city printers', 'mombasa printers', 'c-suite printers', 'mfi printers']))
    <div class="space-y-6">
        <x-data-card title="Printer Inventory" subtitle="All printer devices currently tracked in {{ $activeCategory }}">
            <x-slot name="actions">
                {{-- Search + filter --}}
                <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                    <div class="relative flex-1 sm:w-72">
                        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/>
                        </svg>
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search Printer…"
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
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-3">

                @forelse($Printers as $printer)

                    <div class="border border-gray-200 rounded-xl p-4 bg-white hover:shadow-lg transition duration-200">

                        {{-- Header --}}
                        <div class="flex justify-between items-start gap-3">

                            <div class="space-y-1">

                                <h3 class="font-semibold text-gray-900">
                                    {{ $printer->printer->office_location ?? 'Location Not Set' }}
                                </h3>

                                <p class="text-sm text-gray-500">
                                    {{ $printer->model ?? 'No Model' }}
                                </p>

                                <p class="text-xs text-gray-400">
                                    {{ $printer->name ?? 'Unnamed Printer' }}
                                </p>

                            </div>

                            {{-- Status Badge --}}
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full
                                {{ $printer->status === 'online'
                                    ? 'bg-green-100 text-green-700'
                                    : ($printer->status === 'idle'
                                        ? 'bg-yellow-100 text-yellow-700'
                                        : 'bg-red-100 text-red-700') }}">

                                {{ ucfirst($printer->status ?? 'offline') }}

                            </span>
                        </div>

                        {{-- Compatible Toners --}}
                        <div class="mt-4">

                            <div class="flex items-center justify-between mb-2">

                                <h4 class="text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Compatible Toners
                                </h4>

                                <span class="text-xs text-gray-400">
                                    {{ $printer->printer?->toners?->count() ?? 0 }}
                                </span>

                            </div>

                            <div class="flex flex-wrap gap-2">

                                @forelse($printer->printer?->toners ?? [] as $toner)

                                    <div class="px-2 py-1 rounded-lg bg-gray-100 border border-gray-200">

                                        <div class="flex items-center gap-1.5">

                                            {{-- Toner Details --}}
                                            <p class="text-[11px] text-gray-600whitespace-nowrap">
                                                <strong>
                                                {{ $toner->model ?? 'No Model' }}
                                                
                                                @if($toner->color)
                                                    • {{ $toner->color }}
                                                @endif
                                                </strong>
                                            </p>

                                            {{-- Compact Quantity Badge --}}
                                            <span class="text-[11px] font-bold px-1 rounded
                                                {{ $toner->quantity > 10
                                                    ? 'bg-green-100 text-green-700'
                                                    : ($toner->quantity > 3
                                                        ? 'bg-yellow-100 text-yellow-700'
                                                        : 'bg-red-100 text-red-700') }}">

                                               <strong> {{ $toner->quantity ?? 0 }} </strong>

                                            </span>

                                        </div>

                                    </div>
                                @empty

                                    <div class="w-full text-center py-4 border border-dashed border-gray-300 rounded-lg">

                                        <p class="text-xs text-gray-400">
                                            No compatible toners assigned
                                        </p>

                                    </div>

                                @endforelse

                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="mt-4 flex gap-2">

                            <button
                                wire:click="editPrinterLocation({{ $printer->id }})"
                                class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition">

                                Edit Location Details

                            </button>

                        </div>

                    </div>

                @empty

                    <div class="col-span-full">

                        <div class="text-center py-10 border border-dashed border-gray-300 rounded-xl">

                            <p class="text-sm text-gray-500">
                                No printers found
                            </p>

                        </div>

                    </div>

                @endforelse

            </div>
        </x-data-card>      
    </div>
    @endif

    <!-- Toners View -->
    @if($activeCategory === 'toners')
        <div class="space-y-6">
            <x-data-card title="Toner Inventory" subtitle="All toner devices currently tracked in {{ $activeCategory }}">

                <x-slot name="actions">

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 w-full">

                        {{-- Add Toner Button --}}
                        <button
                            type="button"
                            @click="$dispatch('open-device-modal')"
                            class="inline-flex items-center justify-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition whitespace-nowrap">

                            Add Toner

                        </button>

                        {{-- Search + Clear --}}
                        <div class="flex items-center gap-2 w-full sm:w-auto">

                            {{-- Search Bar --}}
                            <div class="relative flex-1 sm:w-72">

                                <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/>

                                </svg>

                                <input
                                    type="text"
                                    wire:model.live.debounce.300ms="search"
                                    placeholder="Search Toner..."
                                    class="w-full pl-10 pr-3 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                            </div>

                            {{-- Clear Button --}}
                            @if($search)

                                <button
                                    wire:click="$set('search', ''); $set('actionFilter', '')"
                                    class="inline-flex items-center justify-center px-3 py-2 text-xs font-medium rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 transition">

                                    Clear

                                </button>

                            @endif

                        </div>

                    </div>

                </x-slot>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 p-3">
                        @forelse($toners as $toner)
                            <div class="border border-gray-200 rounded-xl p-4 hover:shadow-md transition bg-white">

                            {{-- Header --}}
                            <div class="flex justify-between items-start gap-3">

                                <div>
                                    <h3 class="font-semibold text-gray-900">
                                        {{ $toner->model }}
                                    </h3>

                                    <p class="text-sm text-gray-500">
                                        {{ $toner->brand_name }}
                                    </p>
                                </div>

                                {{-- Actions --}}
                                <div class="flex gap-1">

                                    <button
                                        wire:click="editToner({{ $toner->id }})"
                                        class="text-blue-600 hover:text-blue-800 transition">

                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586" />
                                        </svg>

                                    </button>

                                    <button
                                        wire:click="deleteToner({{ $toner->id }})"
                                        class="text-red-600 hover:text-red-800 transition">

                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>

                                    </button>

                                </div>
                            </div>

                            {{-- Stock Section --}}
                            <div class="mt-4 space-y-2">

                                <div class="flex items-center justify-between">

                                    <span class="text-sm text-gray-600">
                                        Stock Level
                                    </span>

                                    <span class="text-sm font-bold
                                        {{ $toner->stock > 10
                                            ? 'text-green-600'
                                            : ($toner->stock > 3
                                                ? 'text-yellow-600'
                                                : 'text-red-600') }}">

                                        {{ $toner->stock }} units

                                    </span>

                                </div>

                                {{-- Progress Bar --}}
                                <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">

                                    <div class="h-2 rounded-full transition-all duration-300
                                        {{ $toner->stockPercentage > 60
                                            ? 'bg-green-600'
                                            : ($toner->stockPercentage > 25
                                                ? 'bg-yellow-500'
                                                : 'bg-red-600') }}"
                                        style="width: {{ $toner->stockPercentage }}%">
                                    </div>

                                </div>

                                {{-- Stock Controls --}}
                                <div class="flex items-center justify-center gap-3 pt-2">

                                    {{-- Reduce --}}
                                <button
                                    type="button"
                                    wire:click="openStockModal({{ $toner->id }}, 'subtract')"
                                    class="w-8 h-8 rounded-lg bg-red-100 hover:bg-red-200 text-red-600 flex items-center justify-center">

                                    -

                                </button>

                                            {{-- Stock Number --}}
                                            <div class="min-w-[50px] text-center">

                                                <span class="text-lg font-bold text-gray-900">
                                                    {{ $toner->quantity }}
                                                </span>

                                            </div>

                                        {{-- Add --}}
                                <button
                                    type="button"
                                    wire:click="openStockModal({{ $toner->id }}, 'add')"
                                    class="w-8 h-8 rounded-lg bg-green-100 hover:bg-green-200 text-green-600 flex items-center justify-center">

                                    +

                                </button>

                                </div>
                            </div>

                            {{-- Compatible Printers --}}
                            <div class="mt-3 text-xs">

                                <span class="text-gray-500">Compatible with:</span>

                                <span class="text-gray-700">
                                    {{ $toner->compatiblePrinters && count($toner->compatiblePrinters)
                                        ? implode(', ', $toner->compatiblePrinters)
                                        : 'Not set'
                                    }}
                                </span>

                            </div>
                        </div>
                        @empty
                        <div class="col-span-full">
                            <div class="text-center py-10 border border-dashed border-gray-300 rounded-xl">

                                    <p class="text-sm text-gray-500">
                                        No printers found
                                    </p>

                            </div>
                        </div>
                        @endforelse
                    </div>
            </x-data-card>
        </div>
    @endif

    <div
    x-data
    x-show="$wire.showStockModal"
    x-cloak
    class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">

    <div class="bg-white p-6 rounded-xl w-80 shadow-lg">

        <h2 class="text-lg font-semibold mb-4">
            {{ $adjustmentType === 'add' ? 'Add Stock' : 'Reduce Stock' }}
        </h2>

        <input
            type="number"
            min="1"
            wire:model="adjustQuantity"
            class="w-full px-3 py-2 border rounded-lg text-sm mb-4">

        <div class="flex justify-end gap-2">

            <button
                type="button"
                wire:click="$set('showStockModal', false)"
                class="px-3 py-2 text-sm bg-gray-200 rounded-lg">

                Cancel

            </button>

            <button
                type="button"
                wire:click="updateStock"
                class="px-3 py-2 text-sm bg-blue-600 text-white rounded-lg">

                Confirm

            </button>

        </div>

    </div>
</div>

    <div x-data="{ 
        show: @entangle('showPrinterLocationModal'),
        init() {
            this.$watch('show', value => {
                if (value) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = '';
                }
            });
        }
    }"
    x-show="show" x-cloak x-transition.opacity
    @keydown.escape.window="show = false"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">

    <div @click.away="show = false" x-transition.scale.origin.center
         class="relative w-full max-w-3xl bg-white rounded-2xl shadow-2xl overflow-hidden">

        <div class="flex items-center justify-between p-6 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Edit Printer Location</h3>
                    <p class="text-xs text-gray-500">Update the physical location of this printer</p>
                </div>
            </div>
            <button @click="show = false" class="text-gray-400 hover:bg-gray-200 rounded-full w-8 h-8 inline-flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form wire:submit.prevent="updatePrinterLocation" class="p-6 space-y-6 max-h-[70vh] overflow-y-auto">

            <div>
                <h4 class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-3">Printer Details</h4>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-500">Printer Name</p>
                        <p class="text-sm font-medium text-gray-900 mt-0.5">{{ $selectedPrinterName ?? 'N/A' }}</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-500">Model</p>
                        <p class="text-sm font-medium text-gray-900 mt-0.5">{{ $printerModel ?? 'N/A' }}</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-500">Tag Number</p>
                        <p class="text-sm font-medium text-gray-900 mt-0.5">{{ $printerTagNumber ?? 'N/A' }}</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-500">Current Location</p>
                        <p class="text-sm font-medium text-gray-900 mt-0.5">{{ $office_location ?? 'Not set' }}</p>
                    </div>
                </div>
            </div>

            <div>
                <h4 class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-3">Location Details</h4>
                <div class="space-y-3">
                    
                
                {{-- Office Dropdown --}}
                <div>
                    <label class="text-xs font-medium text-gray-700">Office <span class="text-red-500">*</span></label>
                        <div class="relative mt-1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                </div>
                                <select wire:model="office_location"
                                        class="w-full pl-10 pr-8 py-2 border border-gray-200 rounded-lg bg-whitetext-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none cursor-pointer">
                                    <option value="">Select Office</option>
                                    <option value="Main Office - Floor 1">Main Office - Floor 1</option>
                                    <option value="Main Office - Floor 2">Main Office - Floor 2</option>
                                    <option value="IT Office - Floor 1">IT Office - Floor 1</option>
                                    <option value="IT Office - Floor 2">IT Office - Floor 2</option>
                                    <option value="HR Office">HR Office</option>
                                    <option value="Finance Office">Finance Office</option>
                                    <option value="Conference Room A">Conference Room A</option>
                                    <option value="Conference Room B">Conference Room B</option>
                                    <option value="Reception Area">Reception Area</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                            @error('office') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>      
                    </div>
                </div>

                {{-- Compatible Toners --}}
<div x-data="{ open: true }" class="space-y-3">

    <div>
        <label class="text-xs font-medium text-gray-700">
            Compatible Toners
        </label>

        {{-- Search Input --}}
        <div class="relative mt-1">
            <input
                type="text"
                wire:model.live.debounce.300ms="tonerSearch"
                placeholder="Search toner by name, model or color..."
                class="w-full px-4 py-2 border border-gray-200 rounded-lg bg-white text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >

            {{-- Search Results --}}
            @if(!empty($filteredToners))
                <div class="absolute z-50 mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-y-auto">

                    @foreach($filteredToners as $toner)
                        <button
                            type="button"
                            wire:click="addToner({{ $toner['id'] }})"
                            class="w-full px-4 py-3 text-left hover:bg-blue-50 border-b border-gray-100"
                        >
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $toner['brand_name'] }}
                                    </p>

                                    <p class="text-xs text-gray-500">
                                        {{ $toner['model'] ?? 'No model' }}
                                    </p>
                                </div>

                                <span class="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-700">
                                    Add
                                </span>
                            </div>
                        </button>
                    @endforeach

                </div>
            @endif
        </div>
    </div>

    {{-- Selected Toners --}}
    <div class="flex flex-wrap gap-2">

        @foreach($selectedToners as $tonerId)

            @php
                $toner = \App\Models\Toner::find($tonerId);
            @endphp

            @if($toner)
                <div class="flex items-center gap-2 px-3 py-1.5 bg-blue-100 text-blue-700 rounded-full text-xs">

                    <span>
                        {{ $toner->brand_name }}
                    </span>
                    <span>
                        {{ $toner->model}}
                    </span>

                    <button
                        type="button"
                        wire:click="removeToner({{ $toner->id }})"
                        class="hover:text-red-600"
                    >
                        ✕
                    </button>
                </div>
            @endif

        @endforeach

    </div>
</div>

                
            <!-- Suggested Locations -->
            <div>
                <h4 class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-3">Suggested Locations</h4>
                <div class="flex flex-wrap gap-2">
                    <button type="button" 
                            @click="$wire.printerLocation = 'Main Office'"
                            class="px-3 py-1.5 text-xs bg-gray-100 text-gray-700 rounded-lg hover:bg-blue-100 hover:text-blue-700 transition">
                        Main Office
                    </button>
                    <button type="button"
                            @click="$wire.printerLocation = 'IT Department'"
                            class="px-3 py-1.5 text-xs bg-gray-100 text-gray-700 rounded-lg hover:bg-blue-100 hover:text-blue-700 transition">
                        IT Department
                    </button>
                    <button type="button"
                            @click="$wire.printerLocation = 'HR Department'"
                            class="px-3 py-1.5 text-xs bg-gray-100 text-gray-700 rounded-lg hover:bg-blue-100 hover:text-blue-700 transition">
                        HR Department
                    </button>
                    <button type="button"
                            @click="$wire.printerLocation = 'Finance Department'"
                            class="px-3 py-1.5 text-xs bg-gray-100 text-gray-700 rounded-lg hover:bg-blue-100 hover:text-blue-700 transition">
                        Finance Department
                    </button>
                    <button type="button"
                            @click="$wire.printerLocation = 'Conference Room'"
                            class="px-3 py-1.5 text-xs bg-gray-100 text-gray-700 rounded-lg hover:bg-blue-100 hover:text-blue-700 transition">
                        Conference Room
                    </button>
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-4 border-t border-gray-100">
                <button type="button" @click="show = false"
                    class="px-4 py-2 text-sm border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit"
                    wire:loading.attr="disabled"
                    class="px-4 py-2 text-sm text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 rounded-lg shadow-sm flex items-center gap-2">
                    <svg wire:loading.remove class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span wire:loading.remove>Update Location</span>
                    <svg wire:loading class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span wire:loading>Updating...</span>
                </button>
            </div>
        </form>
    </div>
</div> 


    {{-- Toner Creation Modal --}}
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
                        <h3 class="text-lg font-semibold text-gray-900">Create New Toner</h3>
                        <p class="text-sm text-gray-500 mt-0.5">Add a new toner to the inventory</p>
                    </div>
                    <button @click="isOpen = false"
                        class="text-gray-400 hover:bg-gray-100 hover:text-gray-700 rounded-full w-8 h-8 inline-flex items-center justify-center transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Form --}}
                <form wire:submit.prevent="createToner" class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">

                    <div class="grid grid-cols-2 gap-3">

                        <div>
                            <label class="text-xs font-medium text-gray-700">Brand Name</label>
                            <input wire:model="brand_name" type="text"
                                    class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-sm focus:ring-2 focus:ring-blue-500">
                            @error('brand_name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="text-xs font-medium text-gray-700">Model</label>
                            <input wire:model="toner_model" type="text"
                                    class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-sm focus:ring-2 focus:ring-blue-500">
                            @error('toner_model') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div> 
                        
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs font-medium text-gray-700">Color</label>
                            <input wire:model="toner_color" type="text"
                                    class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-sm focus:ring-2 focus:ring-blue-500">
                            @error('toner_color') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-700">Quantity</label>
                            <input wire:model="toner_stock" type="text"
                                    class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-sm focus:ring-2 focus:ring-blue-500">
                            @error('toner_stock') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
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

</div>
