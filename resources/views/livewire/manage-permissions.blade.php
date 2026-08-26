<div>

   <x-slot name="navguide">
    <nav class="flex items-center mb-2 text-sm font-medium text-gray-700 dark:text-gray-200" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-2">
            <li class="inline-flex items-center">
                <svg class="w-4 h-4 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                    <path d="M7.05 5.05a.7.7 0 011 0l4.9 4.9a.7.7 0 010 1l-4.9 4.9a.7.7 0 01-1-1l4.4-4.4-4.4-4.4a.7.7 0 010-1z" />
                </svg>
                <span class="text-gray-900 dark:text-white">Permission Management</span>
            </li>
        </ol>
    </nav>
</x-slot>

 <!-- Main Content -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5">
        <!-- Title -->
        <!-- Create/Edit Permission Section -->
    <div class="mb-1">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Permission Name</h3>
        <div class="flex gap-2 items-center">
            <input 
                type="text" 
                wire:model="permissionName"
                placeholder="Enter permission name"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-2/3 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 h-9"
            >
            @if($isEditing)
                <button 
                    wire:click="createPermission"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-1.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 h-9"
                >
                    Update
                </button>
                <button 
                    wire:click="cancelEdit"
                    class="text-gray-900 bg-white border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-1.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700 h-9"
                >
                    Cancel
                </button>
            @else
                <button 
                    wire:click="createPermission"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-1.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 h-9"
                >
                    Create
                </button>
            @endif
        </div>
        @error('permissionName') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
    </div>

    <hr class="h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">

    <!-- Search and List Section -->
    <div class="mb-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Search permissions...</h3>
        <input 
            type="text" 
            wire:model.live.debounce.300ms="search"
            placeholder="Search permissions..."
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 mb-6 h-9"
        >
            <!-- Permissions List -->
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">Name</th>
                            <th scope="col" class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($permissions as $permission)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $permission->name }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-2">
                                        <button 
                                            wire:click="editPermission({{ $permission->id }})"
                                            class="px-3 py-2 text-xs font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                        >
                                            Edit
                                        </button>
                                        <button 
                                            wire:click="deletePermission({{ $permission->id }})"
                                             wire:confirm="Are you sure you want to delete this permission?"
                                            class="px-3 py-2 text-xs font-medium text-center text-white bg-red-700 rounded-lg hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800"
                                        >
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    No permissions found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $permissions->links() }}
            </div>
        </div>
    </div>

    
 
</div>