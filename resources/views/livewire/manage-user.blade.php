<div x-data="{
    showAddUserModal: false,
    showEditUserModal: false,

resetAddForm() {
        // Reset Livewire properties when opening add modal
       @this.call('prepareAddUser');
    }
}" 
@close-add-user-modal.window="showAddUserModal = false"
 @open-edit-user-modal.window="showEditUserModal = true"
  @close-edit-user-modal.window="showEditUserModal = false">
    <!-- ▸ User-Management Breadcrumb + Toolbar -->

    <x-page-header title="User Management" subtitle="Create and manage users">
        <x-slot name="actions">
            <button
                type="button"
                @click="resetAddForm(); showAddUserModal = true"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-medium rounded-xl shadow-sm hover:shadow-md transition group"
            >
                <svg class="w-4 h-4 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add User
            </button>
        </x-slot>
    </x-page-header>


    <!-- Main content container -->
    <div class="pt-2">
        @livewire('users',['users' => $users])
    </div>

    <!-- Add User Modal -->
    <div 
        x-show="showAddUserModal"
        class="fixed inset-0 flex items-center justify-center bg-black/50 z-50"
        
         x-cloak
    >
        <div class="bg-white dark:bg-gray-700 rounded-lg shadow-lg w-11/12 max-w-2xl max-h-[calc(100vh-2rem)] overflow-y-auto">
            <!-- Header -->
            <div class="flex items-start justify-between p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Add New User
                </h3>
                <button type="button" class="text-gray-400 hover:text-gray-900 dark:hover:text-white"
                        @click="showAddUserModal = false">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24"><path stroke-linecap="round"
                         stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    <span class="sr-only">Close</span>
                </button>
            </div>
            
            <!-- Body -->
            <form wire:submit.prevent="addUser" class="p-6 space-y-6">
                <!-- Full width fields -->
                <div class="grid grid-cols-1 gap-6 mb-6">
                    <div>
                        <label for="add-name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Full Name</label>
                        <input wire:model="name" id="add-name" type="text"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                            placeholder="John Doe">
                        @error('name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="add-email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                        <input wire:model="email" id="add-email" type="email"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                            placeholder="john.doe@company.com">
                        @error('email')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                {{-- Designation selection --}}
                    <div class="relative">
                        <label class="text-xs font-semibold text-gray-700 flex items-center gap-1">
                            Designation for the user
                            <span class="text-gray-400 font-normal">(optional)</span>
                        </label>

                        <div class="relative mt-1.5">
                            <input
                                type="text"
                                wire:model.live.debounce.300ms="searchHead"
                                wire:keydown.escape="$set('showHeadDropdown', false)"
                                @focus="if($wire.searchHead.length >= 2) $wire.set('showHeadDropdown', true)"
                                autocomplete="off"
                                placeholder="Search designation by name..."
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
                                                {{ count($headResults) }} designation{{ count($headResults) > 1 ? 's' : '' }} found
                                            </div>
                                            
                                            @foreach($headResults as $designation)
                                                <button
                                                    type="button"
                                                    wire:click="selectHead({{ $designation->id }})"
                                                    @click="$wire.set('showHeadDropdown', false)"
                                                    class="w-full px-4 py-2.5 text-left hover:bg-blue-50 active:bg-blue-100 transition-colors duration-150 border-b border-gray-50 last:border-0 group"
                                                >
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 font-semibold flex items-center justify-center flex-shrink-0 text-sm">
                                                            {{ strtoupper(substr($designation->name, 0, 1)) }}
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <div class="font-medium text-sm text-gray-800 group-hover:text-blue-700 truncate">
                                                                {{ $designation->name }}
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
                                            <p class="text-sm text-gray-500">No designation found</p>
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

                        {{-- Selected Designation --}}
                        @if($selectedDesignation)
                            <div class="mt-3 rounded-lg bg-green-50 border border-green-200 p-3 flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-green-200 text-green-700 font-semibold flex items-center justify-center flex-shrink-0 text-sm">
                                    {{ strtoupper(substr($searchHead, 0, 1)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-xs text-green-600 font-medium">Selected Designation</div>
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


                {{-- Line manager selection --}}
                    <div class="relative">
                        <label class="text-xs font-semibold text-gray-700 flex items-center gap-1">
                            Reports To
                            <span class="text-gray-400 font-normal">(Line Manager)</span>
                        </label>

                        <div class="relative mt-1.5">
                            <input
                                type="text"
                                wire:model.live.debounce.300ms="searchLineManager"
                                wire:keydown.escape="$set('showLineManagerDropdown', false)"
                                @focus="if($wire.searchLineManager.length >= 2) $wire.set('showLineManagerDropdown', true)"
                                autocomplete="off"
                                placeholder="Search line manager by name..."
                                class="w-full px-3.5 py-2.5 pl-10 pr-10 border border-gray-200 rounded-lg bg-white text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-all duration-200 placeholder:text-gray-400 disabled:opacity-70 disabled:cursor-not-allowed"                
                            />

                            {{-- Search Icon --}}
                            <div class="absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none">
                                <svg wire:loading.remove wire:target="searchLineManager" class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <svg wire:loading wire:target="searchLineManager" class="animate-spin h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                                </svg>
                            </div>

                            {{-- Clear Button --}}
                            @if(!empty($searchLineManager))
                                <button
                                    type="button"
                                    wire:click="$set('searchLineManager', '')"
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
                        @if($showLineManagerDropdown)
                            <div 
                                x-data
                                @click.outside="$wire.set('showLineManagerDropdown', false)"
                                class="absolute z-[9999] mt-1.5 w-full bg-white border border-gray-200 rounded-lg shadow-xl max-h-60 overflow-y-auto"
                            >
                                {{-- Loading State --}}
                                <div wire:loading wire:target="searchLineManager" class="p-4 space-y-3">
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
                                <div wire:loading.remove wire:target="searchLineManager">
                                    @if(count($lineManagerResults))
                                        <div class="py-1">
                                            <div class="px-3 py-1.5 text-[10px] font-medium text-gray-400 uppercase tracking-wider border-b border-gray-100">
                                                {{ count($lineManagerResults) }} line manager{{ count($lineManagerResults) > 1 ? 's' : '' }} found
                                            </div>
                                            
                                            @foreach($lineManagerResults as $manager)
                                                <button
                                                    type="button"
                                                    wire:click="selectLineManager({{ $manager->id }})"
                                                    @click="$wire.set('showLineManagerDropdown', false)"
                                                    class="w-full px-4 py-2.5 text-left hover:bg-blue-50 active:bg-blue-100 transition-colors duration-150 border-b border-gray-50 last:border-0 group"
                                                >
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 font-semibold flex items-center justify-center flex-shrink-0 text-sm">
                                                            {{ strtoupper(substr($manager->name, 0, 1)) }}
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <div class="font-medium text-sm text-gray-800 group-hover:text-blue-700 truncate">
                                                                {{ $manager->name }}
                                                            </div>
                                                        </div>
                                                        <svg class="w-4 h-4 text-blue-600 opacity-0 group-hover:opacity-100 transition-opacity flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                        </svg>
                                                    </div>
                                                </button>
                                            @endforeach
                                        </div>
                                    @elseif(strlen($searchLineManager) >= 2)
                                        <div class="p-6 text-center">
                                            <svg class="w-10 h-10 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                            </svg>
                                            <p class="text-sm text-gray-500">No manager found</p>
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

                        {{-- Selected Line Manager --}}
                        @if($selectedLineManager)
                            <div class="mt-3 rounded-lg bg-green-50 border border-green-200 p-3 flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-green-200 text-green-700 font-semibold flex items-center justify-center flex-shrink-0 text-sm">
                                    {{ strtoupper(substr($searchLineManager, 0, 1)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-xs text-green-600 font-medium">Selected Line Manager</div>
                                    <div class="font-semibold text-green-800 truncate text-sm">
                                        {{ $searchLineManager }}
                                    </div>
                                </div>
                                <button 
                                    type="button"
                                    wire:click="removeLineManagerHead"
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
                </div>

                
                                
                <!-- Roles Section -->
                <div class="mb-6">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Roles</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($roles as $role)
                        <div class="flex items-center">
                            <input wire:model="selectedRoles" 
                                   id="role-{{ $role }}" 
                                   type="checkbox" 
                                   value="{{ $role }}"
                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="role-{{ $role }}" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                {{ $role }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                    @error('selectedRoles')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Submit Button -->
                <div class="flex justify-end space-x-3">
                    <button type="button" 
                            @click="showAddUserModal = false"
                            class="text-gray-900 bg-white hover:bg-gray-100 border border-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-600 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-700 dark:focus:ring-gray-700">
                        Cancel
                    </button>
                    <button type="submit"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Add User
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit User Modal - Simplified version using wire:model -->
    <div x-show="showEditUserModal"
         class="fixed inset-0 flex items-center justify-center bg-black/50 z-50"
         
          x-cloak>
        <div class="bg-white dark:bg-gray-700 rounded-lg shadow-lg w-11/12 max-w-2xl max-h-[calc(100vh-2rem)] overflow-y-auto">
            <!-- Header -->
            <div class="flex items-start justify-between p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Edit User
                </h3>
                <button type="button" class="text-gray-400 hover:text-gray-900 dark:hover:text-white"
                       @click="resetAddForm(); showEditUserModal = false"
                        >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24"><path stroke-linecap="round"
                         stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    <span class="sr-only">Close</span>
                </button>
            </div>
            
            <!-- Body - Using wire:model like the add user form -->
            <form wire:submit.prevent="updateUser" class="p-6 space-y-6">
                <!-- Full width fields -->
                <div class="grid grid-cols-1 gap-6 mb-6">
                    <div>
                        <label for="edit-name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Full Name</label>
                        <input wire:model="name" id="edit-name" type="text"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        @error('name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="edit-email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                        <input wire:model="email" id="edit-email" type="email"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        @error('email')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Half width fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="edit-department" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Department</label>
                        <select wire:model="department_id" id="edit-department"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                            <option value="">Select Department</option>
                            @foreach($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                        @error('department_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="edit-division" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Division</label>
                        <select wire:model="division_id" id="edit-division"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                            <option value="">Select Division</option>
                            @foreach($divisions as $division)
                            <option value="{{ $division->id }}">{{ $division->name }}</option>
                            @endforeach
                        </select>
                        @error('division_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="edit-designation" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Designation</label>
                        <select wire:model="designation_id" id="edit-designation"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                            <option value="">Select Designation</option>
                            @foreach($designations as $designation)
                            <option value="{{ $designation->id }}">{{ $designation->name }}</option>
                            @endforeach
                        </select>
                        @error('designation_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Roles Section -->
                <div class="mb-6">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Roles</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($roles as $role)
                        <div class="flex items-center">
                            <input wire:model="editSelectedRoles" 
                                   id="edit-role-{{ $role }}" 
                                   type="checkbox" 
                                   value="{{ $role }}"
                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="edit-role-{{ $role }}" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                {{ $role }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                    @error('editSelectedRoles')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3">
                    <button type="button" 
                             @click="resetAddForm(); showEditUserModal = false"
                            class="text-gray-900 bg-white hover:bg-gray-100 border border-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-600 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-700 dark:focus:ring-gray-700">
                        Cancel
                    </button>
                    <button type="submit"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Update User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>