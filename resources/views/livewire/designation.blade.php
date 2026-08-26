<div class="space-y-6">

    {{-- ===== Page Header ===== --}}
    <x-page-header
        title="Designations Management"
        subtitle="Manage designations information">

        <x-slot name="actions">
            <button
                type="button"
                @click="$dispatch('open-designation-modal')"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-medium rounded-xl shadow-sm hover:shadow-md transition group"
            >
                <svg class="w-4 h-4 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add New Designation
            </button>
        </x-slot>
    </x-page-header>
       
     <x-data-card title="Kim-Fay Designations" subtitle="All designations in the organization.">
            <x-slot name="actions">
                {{-- Search + filter --}}
                <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                    <div class="relative flex-1 sm:w-72">
                        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/>
                        </svg>
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search departments…"
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
        

            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full text-xs mb-3">
                    <thead class="bg-gray-50 text-left uppercase tracking-wider text-[11px] text-gray-500">
                        <tr>
                            <th class="px-3 py-2">Designation Name</th>
                            <th class="px-3 py-2">Division Name</th>
                            <th class="px-3 py-2">Department Name</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse($designations as $designation)
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-2 hidden xl:table-cell text-gray-700">
                                    {{ $designation->name }}
                                </td>
                                <td class="px-3 py-2 hidden xl:table-cell text-gray-700">
                                    {{ $designation->division->name ?? 'N/A' }}
                                </td>
                                <td class="px-3 py-2 hidden xl:table-cell text-gray-700">
                                    {{ $designation->division->department->name ?? 'N/A' }}
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
                                        <p class="font-medium text-gray-700 text-sm">No designations found</p>
                                        <p class="text-[11px]">Try adjusting your search or add a new designation</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>  
            
            {{-- ===== MOBILE / TABLET CARDS (below lg) ===== --}}
            <div class="lg:hidden divide-y divide-gray-100">
                @forelse($designations as $designation)
                    <div class="p-3 hover:bg-gray-50 transition">
                        {{-- Header row --}}
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm">
                                    <span class="font-semibold text-gray-700">Designation Name:</span>
                                    <span class="text-gray-900">{{ $designation->name }}</span>
                                </p>

                                <p class="text-sm">
                                    <span class="font-semibold text-gray-700">Division Name:</span>
                                    <span class="text-gray-900">{{ $designation->division->name ?? 'N/A' }}</span>
                                </p>

                                <p class="text-xs mt-1">
                                    <span class="font-semibold text-gray-600">Department:</span>
                                    <span class="text-gray-500">{{ $designation->division->department->name ?? 'N/A' }}</span>
                                </p>
                            </div>
                        </div>  
                    </div>   
                @empty
                    <div class="py-16 text-center">
                        <p class="font-medium text-gray-700">No designations found</p>
                        <p class="text-xs text-gray-500 mt-1">Try adjusting your search or add a new designation</p>
                    </div>
                @endforelse
            </div>

            @if($designations->hasPages())
                <div class="px-4 sm:px-6 py-4 border-t border-gray-100">
                    {{ $designations->links() }}
                </div>
            @endif
    </x-data-card>


{{-- Designation Creation Modal --}}
    <div
        x-data="{ isOpen: false }"
        x-on:open-designation-modal.window="isOpen = true"
        x-on:close-designation-modal.window="isOpen = false"
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
            class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl"
        >
            {{-- Header --}}
            <div class="flex items-start justify-between p-6 border-b border-gray-100">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Create New Designation</h3>
                    <p class="text-sm text-gray-500 mt-0.5">Add a new designation to the system</p>
                </div>
                <button @click="isOpen = false"
                    class="text-gray-400 hover:bg-gray-100 hover:text-gray-700 rounded-full w-8 h-8 inline-flex items-center justify-center transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Form --}}
            <form wire:submit.prevent="createDesignation" class="p-6 space-y-5 max-h-[70vh] overflow-y-auto">
                {{-- Designation Name --}}
                <div>
                    <label class="text-xs font-semibold text-gray-700 flex items-center gap-1">
                        Designation Name
                        <span class="text-red-500">*</span>
                    </label>
                    <input 
                        wire:model="designation_name" 
                        type="text"
                        placeholder="Enter designation name..."
                        class="mt-1.5 w-full px-3.5 py-2.5 border border-gray-200 rounded-lg bg-white text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-all duration-200 placeholder:text-gray-400"
                    >
                    @error('designation_name') 
                        <p class="text-xs text-red-500 mt-1.5 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Head of Department --}}
                <div class="relative">
                    <label class="text-xs font-semibold text-gray-700 flex items-center gap-1">
                        Division
                    </label>

                    <div class="relative mt-1.5">
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="searchHead"
                            wire:keydown.escape="$set('showHeadDropdown', false)"
                            @focus="if($wire.searchHead.length >= 2) $wire.set('showHeadDropdown', true)"
                            autocomplete="off"
                            placeholder="Search division by name..."
                            class="w-full px-3.5 py-2.5 pl-10 pr-10 border border-gray-200 rounded-lg bg-white text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-all duration-200 placeholder:text-gray-400 disabled:opacity-70 disabled:cursor-not-allowed"                
                        />

                        {{-- Search Icon --}}
                        <div class="absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none">
                            <svg wire:loading.remove wire:target="searchHead" class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <svg wire:loading wire:target="searchHead" class="animate-spin h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                            </svg>
                        </div>

                        {{-- Clear Button --}}
                        @if(!empty($searchHead))
                            <button
                                type="button"
                                wire:click="$set('searchHead', '')"
                                wire:loading.attr="disabled"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        @endif
                    </div>

                    {{-- Dropdown --}}
                    @if($showHeadDropdown)
                        <div 
                            x-data
                            @click.outside="$wire.set('showHeadDropdown', false)"
                            class="absolute z-[9999] mt-1.5 w-full bg-white border border-gray-200 rounded-lg shadow-xl max-h-60 overflow-y-auto"
                        >
                            {{-- Loading State --}}
                            <div wire:loading wire:target="searchHead" class="p-4 space-y-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gray-200 animate-pulse"></div>
                                    <div class="flex-1">
                                        <div class="h-4 bg-gray-200 rounded w-3/4 animate-pulse"></div>
                                        <div class="h-3 bg-gray-100 rounded w-1/2 mt-2 animate-pulse"></div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gray-200 animate-pulse"></div>
                                    <div class="flex-1">
                                        <div class="h-4 bg-gray-200 rounded w-2/3 animate-pulse"></div>
                                        <div class="h-3 bg-gray-100 rounded w-1/3 mt-2 animate-pulse"></div>
                                    </div>
                                </div>
                            </div>

                            {{-- Results --}}
                            <div wire:loading.remove wire:target="searchHead">
                                @if(count($headResults))
                                    <div class="py-1">
                                        <div class="px-3 py-1.5 text-[10px] font-medium text-gray-400 uppercase tracking-wider border-b border-gray-100">
                                            {{ count($headResults) }} division{{ count($headResults) > 1 ? 's' : '' }} found
                                        </div>
                                        
                                        @foreach($headResults as $division)
                                            <button
                                                type="button"
                                                wire:click="selectHead({{ $division->id }})"
                                                @click="$wire.set('showHeadDropdown', false)"
                                                class="w-full px-4 py-2.5 text-left hover:bg-blue-50 active:bg-blue-100 transition-colors duration-150 border-b border-gray-50 last:border-0 group"
                                            >
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 font-semibold flex items-center justify-center flex-shrink-0 text-sm">
                                                        {{ strtoupper(substr($division->name, 0, 1)) }}
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <div class="font-medium text-sm text-gray-800 group-hover:text-blue-700 truncate">
                                                            {{ $division->name }}
                                                        </div>
                                                    </div>
                                                    <svg class="w-4 h-4 text-blue-600 opacity-0 group-hover:opacity-100 transition-opacity flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                    </svg>
                                                </div>
                                            </button>
                                        @endforeach
                                    </div>
                                @elseif(strlen($searchHead) >= 2)
                                    <div class="p-6 text-center">
                                        <svg class="w-10 h-10 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                        <p class="text-sm text-gray-500">No division found</p>
                                        <p class="text-xs text-gray-400 mt-1">Try adjusting your search</p>
                                    </div>
                                @else
                                    <div class="p-4 text-center text-sm text-gray-400">
                                        Type at least 2 characters to search
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Selected Division --}}
                    @if($selectedDivision)
                        <div class="mt-3 rounded-lg bg-green-50 border border-green-200 p-3 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-green-200 text-green-700 font-semibold flex items-center justify-center flex-shrink-0 text-sm">
                                {{ strtoupper(substr($searchHead, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-xs text-green-600 font-medium">Selected Division</div>
                                <div class="font-semibold text-green-800 truncate text-sm">
                                    {{ $searchHead }}
                                </div>
                            </div>
                            <button 
                                type="button"
                                wire:click="removeHead"
                                class="text-green-600 hover:text-green-800 transition-colors"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    @endif

                    @error('department_head')
                        <p class="text-xs text-red-500 mt-1.5 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Actions --}}
                <div class="flex justify-end gap-2 pt-4 border-t border-gray-100">
                    <button type="button" @click="isOpen = false"
                        class="px-4 py-2.5 text-sm border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition-all duration-200">
                        Cancel
                    </button>
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm hover:shadow-md transition-all duration-200 disabled:opacity-70 disabled:cursor-not-allowed"
                        wire:loading.attr="disabled"
                    >
                        <span wire:loading.remove wire:target="createDesignation">Add Designation</span>
                        <span wire:loading wire:target="createDesignation" class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                            </svg>
                            Saving...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


</div>
