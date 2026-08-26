<div x-cloak class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <nav class="flex items-center mb-6 text-sm font-medium text-gray-700 dark:text-gray-200" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-2">
                <li class="inline-flex items-center">
                    <svg class="w-4 h-4 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path d="M7.05 5.05a.7.7 0 011 0l4.9 4.9a.7.7 0 010 1l-4.9 4.9a.7.7 0 01-1-1l4.4-4.4-4.4-4.4a.7.7 0 010-1z" />
                    </svg>
                    <span class="ml-1 text-blue-500 dark:text-gray-400">Hr Document Management Page</span>
                </li>
            </ol>
        </nav>
        
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Document Center</h1>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    Manage all employee documents in one place
                </p>
            </div>

               <a wire:navigate href="{{ route('users-documents') }}"
                class="bg-gradient-to-r from-purple-500 to-violet-600 text-white px-5 py-3 rounded-xl text-sm font-medium hover:from-purple-600 hover:to-violet-700 transition-all shadow-md hover:shadow-lg flex items-center">
                    <i class="fas fa-file-alt mr-2"></i> User Documents
                </a>
        </div>

    </div>

    <!-- Action Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
        <!-- Card 1 -->
        <a wire:navigate href="{{ route('onboard-new-user') }}" class="group block p-6 border rounded-2xl shadow-sm transition-all duration-300
                  border-gray-200 dark:border-gray-700
                  bg-white dark:bg-gray-800
                  hover:border-blue-500 hover:shadow-md hover:transform hover:-translate-y-1">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-blue-100 dark:bg-blue-900/40 rounded-xl transition group-hover:bg-blue-200 group-hover:dark:bg-blue-900/60">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 2a8 8 0 100 16 8 8 0 000-16zM9 11V5h2v6H9zm0 4v-2h2v2H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400">Onboard New User</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">Start the onboarding process</p>
                </div>
            </div>
        </a>

        <!-- Card 2 -->
        <a wire:navigate href="{{ route('continue-onboarding') }}" class="group block p-6 border rounded-2xl shadow-sm transition-all duration-300
                  border-gray-200 dark:border-gray-700
                  bg-white dark:bg-gray-800
                  hover:border-blue-500 hover:shadow-md hover:transform hover:-translate-y-1">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-blue-100 dark:bg-blue-900/40 rounded-xl transition group-hover:bg-blue-200 group-hover:dark:bg-blue-900/60">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M6.5 5.5l7 4.5-7 4.5v-9z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400">Continue Onboarding</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">Resume an existing session</p>
                </div>
            </div>
        </a>

        <!-- Card 3 -->
        <a wire:navigate href="{{ route('onboarding') }}" class="group block p-6 border rounded-2xl shadow-sm transition-all duration-300
                  border-gray-200 dark:border-gray-700
                  bg-white dark:bg-gray-800
                  hover:border-blue-500 hover:shadow-md hover:transform hover:-translate-y-1">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-blue-100 dark:bg-blue-900/40 rounded-xl transition group-hover:bg-blue-200 group-hover:dark:bg-blue-900/60">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 11h4v6H3v-6zm6-8h4v14h-4V3zm6 4h4v10h-4V7z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400">Induction Progress</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">View Induction progress</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Document Management Section -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 md:p-8">

        <!-- Section Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                    Document Management
                </h2>
                <p class="text-gray-600 dark:text-gray-400 mt-2">Manage your required documents</p>
            </div>
            <div class="bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 px-4 py-2 rounded-full text-sm font-medium">
                <span>{{$documents->count()}}</span> Documents
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Document List -->
            <div>
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                        Required Documents
                    </h3>
                    <span class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-xs font-medium px-2.5 py-1 rounded-full" 
                          >{{$documents->count()}}</span>
                </div>
                
                <div class="space-y-4">
                    @forelse ($documents as $doc)
                        <div wire:key="doc-{{ $doc->id }}" 
                            class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-100 dark:border-gray-600 shadow-sm">
                            
                            <div class="flex items-center">
                                <!-- Document Type Icon/Badge -->
                                <div class="flex-shrink-0 mr-4">
                                    <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center">
                                        <span class="text-xs font-medium text-blue-600 dark:text-blue-400">
                                            {{ $doc->type ?? 'PDF' }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Document Info -->
                                <div>
                                    <span class="text-gray-800 dark:text-gray-200 font-medium block">
                                        {{ $doc->name }}
                                    </span>
                                    <div class="flex items-center mt-1 space-x-3">
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            Added: {{ $doc->created_at->format('d M Y') }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Delete Button -->
                            <button type="button"
                                wire:click.prevent="confirmDelete({{ $doc->id }})"
                                wire:target="confirmDelete({{ $doc->id }})"
                                class="relative text-red-500 hover:text-red-700 p-2 rounded-full hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-200">

                                <!-- Normal icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" 
                                    class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor"
                                    wire:loading.remove
                                    wire:target="confirmDelete({{ $doc->id }})">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 
                                        0116.138 21H7.862a2 2 0 
                                        01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4
                                        a1 1 0 00-1-1h-4a1 1 0 
                                        00-1 1v3M4 7h16" />
                                </svg>

                                <!-- Loading spinner -->
                                <svg class="animate-spin h-5 w-5 text-red-600"
                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24"
                                    wire:loading
                                    wire:target="confirmDelete({{ $doc->id }})">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" 
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8v4l3-3-3-3v4
                                        a8 8 0 100 16v-4l-3 3 3 3v-4
                                        a8 8 0 01-8-8z"></path>
                                </svg>
                            </button>
                        </div>
                    @empty
                        <div class="text-center py-10">
                            <p class="text-gray-500 dark:text-gray-400">No documents added yet.</p>
                        </div>
                @endforelse

                </div>


                <div 
                    x-data="{ open: @entangle('confirmingDelete') }" 
                    x-show="open"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 bg-opacity-50"
                    x-cloak
                >
                    <!-- Modal Container -->
                    <div class="w-full max-w-md mx-4 bg-white dark:bg-gray-800 rounded-xl shadow-lg transform transition-all">
                        <div class="p-6">
                            <!-- Title -->
                            <h2 class="text-lg md:text-xl font-semibold text-gray-800 dark:text-gray-100">
                                Confirm Deletion
                            </h2>
                            
                            <!-- Description -->
                            <p class="mt-2 text-sm md:text-base text-gray-600 dark:text-gray-400">
                                Are you sure you want to delete this document? This action cannot be undone.
                            </p>

                            <!-- Actions -->
                            <div class="mt-6 flex flex-col sm:flex-row sm:justify-end sm:space-x-3 space-y-3 sm:space-y-0">
                                <!-- Cancel Button -->
                                <button 
                                    @click="open = false"
                                    class="w-full sm:w-auto px-4 py-2 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition"
                                >
                                    Cancel
                                </button>

                                <!-- Delete Button -->
                                <button 
                                    wire:click="delete"
                                    class="w-full sm:w-auto px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition"
                                >
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add Document Form -->
            <div>
    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-5">
        Add New Document
    </h3>

    <!-- Document Name -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Document Name</label>
        <div class="relative">
            <input type="text" wire:model.defer="name" 
                   placeholder="e.g., Passport Photo, Academic Certificates..." 
                   class="w-full border border-gray-300 dark:border-gray-600 rounded-xl p-4 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
            @error('name') 
                <span class="text-red-500 text-sm">{{ $message }}</span> 
            @enderror
        </div>
    </div>

    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Document Type</label>
        <select wire:model.defer="type"
                class="w-full border border-gray-300 dark:border-gray-600 rounded-xl p-4 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <option value="">Select type</option>
            <option value="PDF">PDF</option>
            <option value="Word">Word Document</option>
            <option value="Excel">Excel Spreadsheet</option>
            <option value="Image">Image</option>
            <option value="Other">Other</option>
        </select>
        @error('type') 
            <span class="text-red-500 text-sm">{{ $message }}</span> 
        @enderror
    </div>

    <!-- Tips -->
    <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-xl mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="ml-3">
                <h4 class="text-sm font-medium text-blue-800 dark:text-blue-300">Tips</h4>
                <div class="mt-1 text-sm text-blue-700 dark:text-blue-400">
                    <p>Add any document required for your onboarding process</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Submit -->
        <button 
            wire:click="addDocument"
            wire:loading.attr="disabled"
            wire:target="addDocument"
            class="w-full px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium shadow-sm hover:shadow-md transition-all duration-200 flex items-center justify-center">

            <!-- Plus Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" 
                class="h-5 w-5 mr-2"
                fill="none" viewBox="0 0 24 24" stroke="currentColor"
                wire:loading.remove
                wire:target="addDocument">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M12 4v16m8-8H4" />
            </svg>

            <!-- Normal text -->
            <span wire:loading.remove wire:target="addDocument">
                Add Document
            </span>

            <!-- Loading state -->
            <span wire:loading wire:target="addDocument" class="flex items-center">
                <svg class="animate-spin h-5 w-5 mr-2 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3-3-3-3v4
                        a8 8 0 100 16v-4l-3 3 3 3v-4
                        a8 8 0 01-8-8z"></path>
                </svg>
                Adding...
            </span>
        </button>

</div>


        </div>
    </div>
</div>