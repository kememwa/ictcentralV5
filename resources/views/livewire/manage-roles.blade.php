<div>
    <!-- ▸ Role-Management Breadcrumb + Toolbar -->
<x-slot name="navguide">
    <div class="flex items-center justify-between mb-6">
        <nav class="text-sm font-medium text-gray-700 dark:text-gray-200" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-2">
                <li class="inline-flex items-center">
                    <svg class="w-4 h-4 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 3.5a.75.75 0 01.75.75V10h5.75a.75.75 0 010 1.5H10.75v5.75a.75.75 0 01-1.5 0V11.5H3.5a.75.75 0 010-1.5h5.75V4.25A.75.75 0 0110 3.5z" />
                    </svg>
                    <span class="text-gray-900 dark:text-white"> 📋 Role Management</span>
                </li>
            </ol>
        </nav>
    </div>
</x-slot>

<div class="rounded-lg  ring-gray-200 dark:ring-gray-700 mt-4 w-full">
   

  <!-- Role Creation Form -->
<div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
        {{ $editingRoleId ? 'Edit Role' : 'Create New Role' }}
    </h3>
    
    <form wire:submit.prevent="{{ $editingRoleId ? 'updateRole' : 'createRole' }}">
        <!-- Flex container for role name and permissions -->
        <div class="flex flex-col sm:flex-row gap-4 mb-4">
            <!-- Role Name Input -->
            <div class="flex-1">
                <label for="roleName" class="block text-sm font-medium text-gray-700 dark:text-white mb-1">Role Name</label>
                <input
                    type="text"
                    id="roleName"
                    wire:model="roleName"
                    class="w-full p-2 text-sm bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Enter role name"
                >
                @error('roleName')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Permissions Section -->
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-white mb-1">Permissions</label>
                <div class="space-y-2">
                    @foreach($permissionGroups as $group)
                        <div class="flex items-center">
                            <input
                                type="checkbox"
                                id="perm-group-{{ $group }}"
                                wire:model="selectedPermissions"
                                value="{{ $permissions[$group]->first()->id }}"
                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                            >
                            <label for="perm-group-{{ $group }}" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                {{ $group }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        
        <!-- Form Buttons - Now below the input box -->
        <div class="flex space-x-2">
            @if($editingRoleId)
                <button
                    type="button"
                    wire:click="resetForm"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 dark:bg-gray-600 dark:text-white"
                >
                    Cancel
                </button>
            @endif
            <button
                type="submit"
                class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:ring-4 focus:outline-none focus:ring-indigo-300"
            >
                {{ $editingRoleId ? 'Update Role' : 'Create Role' }}
            </button>
        </div>
    </form>
</div>
<!-- Search bar - Moved here and made full width -->
    <div class="mb-4 ml-4">
        <div class="relative w-full">
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Search roles..."
                class="w-full pl-10 pr-4 py-2 text-sm bg-white dark:bg-gray-800 text-gray-700 dark:text-white border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none"
            >
            <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 0 5 11a6 6 0 0 0 12 0z" />
            </svg>
        </div>
    </div>
    <!-- Roles Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-700 dark:text-gray-300">
            <thead class="text-xs font-semibold uppercase bg-gray-50 dark:bg-gray-800 text-gray-600 dark:text-gray-400 border-b dark:border-gray-700">
                <tr>
                    <th class="px-6 py-3">Name</th>
                    <th class="px-6 py-3">Permissions Count</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse ($roles as $role)
                    <tr class="bg-white dark:bg-gray-900 hover:bg-indigo-50 dark:hover:bg-gray-800 transition-colors">
                        <td class="px-6 py-4 font-medium">{{ $role->name }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-0.5 text-xs font-medium rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                {{ $role->permissions_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <button
                                wire:click="editRole({{ $role->id }})"
                                class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-indigo-600 border border-indigo-600 rounded-lg hover:bg-indigo-600 hover:text-white transition-all duration-150"
                            >
                                ✏️ Edit
                            </button>
                            <button
                                wire:click="deleteRole({{ $role->id }})"
                                wire:confirm="Are you sure you want to delete this role?"
                                class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-red-600 border border-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all duration-150"
                            >
                                🗑️ Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400 italic">
                            No roles found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="pt-2 flex justify-end">
        {{ $roles->links() }}
    </div>
</div>