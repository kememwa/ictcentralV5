<div>
    <x-data-card title="Device Inventory" subtitle="All devices currently tracked">
        <x-slot name="actions">
            {{-- Search + filter --}}
            <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                <div class="relative flex-1 sm:w-72">
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/>
                    </svg>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search devices…"
                        class="w-full pl-10 pr-3 py-2 bg-white border border-gray-200 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                </div>

                @if($search)
                    <button wire:click="$set('search', ''); $set('actionFilter', '')"
                        class="inline-flex items-center justify-center gap-1 rounded-lg border border-slate-200 dark:border-slate-700 bg-white px-3 py-2 text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-gray-800">
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
                <th class="px-3 py-2">Name</th>
                <th class="px-3 py-2">Email</th>
                <th class="px-3 py-2 hidden xl:table-cell">Designation</th>
                <th class="px-3 py-2 hidden xl:table-cell">Department</th>
                <th class="px-3 py-2 hidden xl:table-cell">Role</th>
                <th class="px-3 py-2">Action</th>
            </tr>
        </thead>

        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
            @forelse ($users as $user)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40 transition">
                    
                    <td class="px-3 py-2">
                        <p class="font-medium text-gray-900 dark:text-white truncate max-w-[140px]">
                            {{ $user->name }}
                        </p>
                    </td>

                    <td class="px-3 py-2 text-gray-700 dark:text-gray-300">
                        {{ $user->email }}
                    </td>

                    <td class="px-3 py-2 hidden xl:table-cell text-gray-500">
                        {{ $user->designation->name }}
                    </td>


                    <td class="px-3 py-2 hidden xl:table-cell text-gray-700 dark:text-gray-300">
                        {{ $user->designation->division->department->name}}
                    </td>

                    {{-- Actions --}}
                    <td class="px-3 py-2 text-right">
                        <div class="flex flex-wrap gap-1">
                            @if($user->roles->isNotEmpty())
                                @foreach($user->roles->unique('name') as $role)
                                    <span class="px-2 py-0.5 text-xs font-medium rounded-full 
                                        @if($role->name === 'admin') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                                        @elseif($role->name === 'manager') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300
                                        @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 @endif">
                                        {{ ucfirst($role->name) }}
                                    </span>
                                @endforeach
                            @else
                                <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                    No role
                                </span>
                            @endif
                        </div>
                    </td>

                    <td class="px-3 py-2 text-right">
                        <div class="flex items-center space-x-2">
                            <button 
                                wire:click="$parent.editUser({{ $user->id }})"
                                @click="showEditUserModal = true"
                                class="px-3 py-1.5 text-xs font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                            >
                                Edit
                            </button>
                            <button 
                                wire:click="$parent.deleteUser({{ $user->id }})"
                                wire:confirm="Are you sure you want to delete this user?"
                                class="px-3 py-1.5 text-xs font-medium text-center text-white bg-red-700 rounded-lg hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800"
                            >
                                Delete
                            </button>
                        </div>
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
                            <p class="font-medium text-gray-700 dark:text-gray-200 text-sm">No devices found</p>
                            <p class="text-[11px]">Try adjusting your search or add a new device</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

    {{-- ===== MOBILE / TABLET CARDS (below lg) ===== --}}
    <div class="lg:hidden divide-y divide-gray-100 dark:divide-gray-800">
        @forelse ($users as $user)
            <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-800/40 transition">
                {{-- Header row --}}
                <div class="flex items-start justify-between gap-3 mb-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 text-white flex items-center justify-center font-semibold text-sm shrink-0">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="font-semibold text-gray-900 dark:text-white truncate">{{ $user->name }}</p>
                        </div>
                    </div>
                </div>

                {{-- Detail grid --}}
                <dl class="grid grid-cols-2 gap-x-4 gap-y-2 text-xs">
                    <div>
                        <dt class="text-gray-500">Email</dt>
                        <dd class="font-medium text-gray-900 dark:text-white">${{ $user->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Designation</dt>
                        <dd class="font-mono text-gray-700 dark:text-gray-300">{{ $user->designation->name }} </dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Department</dt>
                        <dd class="text-gray-700 dark:text-gray-300 truncate">{{ $user->designation->division->department->name}}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Role</dt>
                        <dd class="text-gray-700 dark:text-gray-300 truncate">{{"#"}}</dd>
                    </div>
                    <div class="col-span-2">
                        <dt class="text-gray-500">Action</dt>
                        <dd class="font-mono text-gray-700 dark:text-gray-300 truncate">{{"#"}}</dd>
                    </div>
                </dl>
            </div>
        @empty
            <div class="py-16 text-center">
                <p class="font-medium text-gray-700 dark:text-gray-200">No devices found</p>
                <p class="text-xs text-gray-500 mt-1">Try adjusting your search or add a new device</p>
            </div>
        @endforelse
    </div>

    @if($users->hasPages())
        <div class="px-4 sm:px-6 py-4 border-t border-gray-100 dark:border-gray-800">
            {{ $users->links() }}
        </div>
    @endif
</x-data-card>
</div>