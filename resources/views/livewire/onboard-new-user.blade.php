
<div>

<nav x-cloak class="flex flex-wrap items-center mb-6 text-sm font-medium text-gray-700 dark:text-gray-200" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-2">
        <li class="inline-flex items-center">
            <svg class="w-4 h-4 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                <path d="M7.05 5.05a.7.7 0 011 0l4.9 4.9a.7.7 0 010 1l-4.9 4.9a.7.7 0 01-1-1l4.4-4.4-4.4-4.4a.7.7 0 010-1z" />
            </svg>
            <span class="ml-1 text-blue-500 dark:text-gray-400">Hr Onboard New Staff Page</span>
        </li>
    </ol>
</nav>

<div x-cloak class="grid grid-cols-1 md:grid-cols-4 gap-4">
    <!-- Card 1 - Continue Onboarding -->
    <div class="pb-3 h-full">
        <a wire:navigate href="{{ route('continue-onboarding') }}" class="group block p-6 border rounded-2xl shadow-sm transition-all duration-300 h-full
                  border-gray-200 dark:border-gray-700
                  bg-white dark:bg-gray-800
                  hover:border-blue-500 hover:shadow-md hover:transform hover:-translate-y-1">
            <div class="flex flex-col items-center text-center space-y-3">
                <div class="p-1 bg-blue-100 dark:bg-blue-900/40 rounded-xl transition group-hover:bg-blue-200 group-hover:dark:bg-blue-900/60">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M6.5 5.5l7 4.5-7 4.5v-9z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400">Continue Onboarding</h3>
                    <p class="text-xs text-gray-600 dark:text-gray-300 mt-1">Resume an existing session</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Card 2 - Induction Progress -->
    <div class="pb-3 h-full">
        <a wire:navigate href="{{ route('onboarding') }}" class="group block p-6 border rounded-2xl shadow-sm transition-all duration-300 h-full
                  border-gray-200 dark:border-gray-700
                  bg-white dark:bg-gray-800
                  hover:border-blue-500 hover:shadow-md hover:transform hover:-translate-y-1">
            <div class="flex flex-col items-center text-center space-y-3">
                <div class="p-1 bg-blue-100 dark:bg-blue-900/40 rounded-xl transition group-hover:bg-blue-200 group-hover:dark:bg-blue-900/60">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 11h4v6H3v-6zm6-8h4v14h-4V3zm6 4h4v10h-4V7z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400">Induction Progress</h3>
                    <p class="text-xs text-gray-600 dark:text-gray-300 mt-1">View Induction progress</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Card 3 - Document Management -->
    <div class="pb-3 h-full">
        <a wire:navigate href="{{ route('documents-management') }}" class="group block p-6 border rounded-2xl shadow-sm transition-all duration-300 h-full
                  border-gray-200 dark:border-gray-700
                  bg-white dark:bg-gray-800
                  hover:border-blue-500 hover:shadow-md hover:transform hover:-translate-y-1">
            <div class="flex flex-col items-center text-center space-y-3">
                <div class="p-1 bg-blue-100 dark:bg-blue-900/40 rounded-xl transition group-hover:bg-blue-200 group-hover:dark:bg-blue-900/60">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <!-- Document icon -->
                        <path d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.828a2 2 0 00-.586-1.414l-4.828-4.828A2 2 0 009.172 1H6zm5 1.414L15.586 7H12a1 1 0 01-1-1V3.414z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400">Document Management</h3>
                    <p class="text-xs text-gray-600 dark:text-gray-300 mt-1">Manage required documents</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Card 4 - Video Management -->
    <div class="pb-3 h-full">
        <a wire:navigate href="{{ route('video-management') }}" class="group block p-6 border rounded-2xl shadow-sm transition-all duration-300 h-full
                  border-gray-200 dark:border-gray-700
                  bg-white dark:bg-gray-800
                  hover:border-blue-500 hover:shadow-md hover:transform hover:-translate-y-1">
            <div class="flex flex-col items-center text-center space-y-3">
                <div class="p-1 bg-blue-100 dark:bg-blue-900/40 rounded-xl transition group-hover:bg-blue-200 group-hover:dark:bg-blue-900/60">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <!-- Video camera icon -->
                        <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" />
                        <path d="M14 8l4-2v8l-4-2v-4z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400">LMS Management</h3>
                    <p class="text-xs text-gray-600 dark:text-gray-300 mt-1">Manage LMS content and settings</p>
                </div>
            </div>
        </a>
    </div>
</div>



<div x-cloak class="w-full h-full flex flex-col bg-white shadow-lg overflow-hidden">

    <!-- Header -->
    <div class="bg-gradient-to-br from-indigo-50 via-white to-purple-50 
                dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 
                text-gray-800 dark:text-white tracking-tight p-4 md:p-6 flex-shrink-0">
        <h1 class="text-xl md:text-2xl font-bold flex items-center">
            <i class="fas fa-user-plus mr-2 md:mr-3"></i>HR User Onboarding
        </h1>
        <p class="mt-1 md:mt-2 text-sm md:text-base opacity-90">
            Streamlined process for adding new team members
        </p>
    </div>

    <div class="w-full flex flex-col items-center p-4 md:p-6 flex-grow overflow-auto">
        <!-- Stepper -->
        <div class="flex items-center w-full mb-6 md:mb-10 relative">
            @foreach ($steps as $index => $step)
                <div class="flex items-center w-1/3">
                    <div class="flex flex-col items-center w-full">
                        <!-- Step connector -->
                        @if ($index > 0)
                            <div class="h-2 flex-1 bg-gray-200 mx-[-10px] relative top-[-20px] z-0 
                                {{ $current > $index + 1 ? 'bg-green-500' : '' }}">
                            </div>
                        @endif

                        <!-- Step circle -->
                        <div class="relative z-10 flex items-center justify-center w-10 h-10 md:w-12 md:h-12 
                                    rounded-full font-semibold transition-all
                                    {{ $current == $step['id'] ? 'bg-indigo-600 text-white shadow-lg' : '' }}
                                    {{ $current > $step['id'] ? 'bg-green-500 text-white' : '' }}
                                    {{ $current < $step['id'] ? 'bg-gray-200 text-gray-500' : '' }}">
                            @if ($current > $step['id'])
                                <i class="fas fa-check text-sm md:text-base"></i>
                            @else
                                <span class="text-sm md:text-base">{{ $step['id'] }}</span>
                            @endif
                        </div>

                        <!-- Step label -->
                        <div class="text-center mt-2">
                            <div class="text-xs md:text-sm font-medium">{{ $step['title'] }}</div>
                            <div class="text-xs text-gray-500 mt-1 hidden md:block">{{ $step['description'] }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Progress bar -->
        <div class="w-full bg-gray-200 rounded-full h-2 mb-4 md:mb-6">
            <div class="bg-indigo-600 h-2 rounded-full transition-all duration-500" 
                 style="width: {{ ($current / count($steps)) * 100 }}%">
            </div>
        </div>

        <!-- Tab Content -->
        <div class="w-full flex-grow overflow-auto">
            
            <!-- STEP 1: Personal Info -->
            @if ($current == 1)
                <div class="p-4 md:p-6 rounded-xl bg-white shadow-sm h-full flex flex-col">
                    <h2 class="text-lg md:text-xl font-semibold mb-3 md:mb-4 text-indigo-700 flex items-center">
                        <i class="fas fa-user-circle mr-2"></i> Personal Information
                    </h2>
                    <p class="text-gray-600 mb-4 md:mb-6 text-sm md:text-base">
                        Please provide the new employee's details. Details marked with (*) are mandatory.
                    </p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 flex-grow overflow-auto">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">First Name *</label>
                            <input type="text" wire:model="formData.personal.firstName"
                                   class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500"
                                   placeholder="Enter first name">
                            @error('formData.personal.firstName')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Last Name *</label>
                            <input type="text" wire:model="formData.personal.lastName"
                                   class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500"
                                   placeholder="Enter last name">
                            @error('formData.personal.lastName')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                            <input type="email" wire:model="formData.personal.email"
                                   class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500"
                                   placeholder="employee@company.com">
                            @error('formData.personal.email')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                            <input type="tel" wire:model="formData.personal.phone"
                                   class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500"
                                   placeholder="2547*******">
                            @error('formData.personal.phone')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Position *</label>
                            <input type="text" wire:model="formData.personal.position"
                                   class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500"
                                   placeholder="Job title">
                            @error('formData.personal.position')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Department *</label>
                            <select wire:model="formData.personal.department"
                                    class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
                                <option value="">Select Department</option>
                                <option>Engineering</option>
                                <option>Marketing</option>
                                <option>Sales</option>
                                <option>Human Resources</option>
                                <option>Finance</option>
                                <option>Operations</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Start Date *</label>
                            <input type="date" wire:model="formData.personal.startDate"
                                   class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>
                        
                    </div>
                    
                    <div class="mt-6 flex justify-end">
                        <button wire:click="next"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-5 rounded-lg flex items-center">
                            Continue <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>
            @endif

<!-- STEP 2: Documentation -->
@if ($current == 2)
<div class="p-4 md:p-6 rounded-xl bg-white shadow-sm h-full flex flex-col">
        <!-- Error messages -->
    @if ($errors->any())
        <div class="mb-4 p-4 rounded-lg bg-red-50 border border-red-200 text-red-700">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <!-- Header -->
    <h2 class="text-lg md:text-xl font-semibold mb-3 md:mb-4 text-indigo-700 flex items-center">
        <i class="fas fa-file-alt mr-2"></i> Documentation
    </h2>
    <p class="text-gray-600 mb-4 md:mb-6 text-sm md:text-base">Select and confirm Documents to be submitted during onboarding.</p>

    <!-- Selection info + select all -->
    <div class="flex items-center justify-between mb-3">
        <span class="text-sm text-gray-600">
            Selected: <span class="font-semibold text-indigo-600">{{ count($selectedDocIds) }}</span> /
            {{ count($documents) }}
        </span>
        <button wire:click="toggleSelectAll"
            class="text-sm px-3 py-1 rounded-lg border border-gray-300 bg-gray-50 hover:bg-gray-100">
            {{ count($selectedDocIds) === count($documents) ? 'Deselect All' : 'Select All' }}
        </button>
    </div>
    
    <!-- Document selection table -->
    <div class="overflow-x-auto rounded-lg border border-gray-200 mb-6">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Document</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($documents as $doc)
                <tr wire:key="doc-{{ $doc->id }}"
                    class="cursor-pointer transition 
                        {{ in_array($doc->id, $selectedDocIds ?? []) 
                            ? 'bg-indigo-50 hover:bg-indigo-100' 
                            : 'hover:bg-gray-50' }}">
                    
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $doc->name }}</div>
                        <div class="text-sm text-gray-500">Required for onboarding</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if(in_array($doc->id, $selectedDocIds ?? []))
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Selected
                            </span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                Not Selected
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <input type="checkbox" value="{{ $doc->id }}" 
                            wire:model.live="selectedDocIds"
                            class="h-4 w-4 text-indigo-600 rounded focus:ring-indigo-500 border-gray-300"
                            @click.stop>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                        No documents available
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
   
    <!-- Confirm button -->
<div class="mb-6">
    <button wire:click="saveSelection" type="button"
        class="bg-indigo-500 hover:bg-indigo-600 text-white py-2 px-4 rounded-lg flex items-center">
        <i class="fas fa-check-circle mr-2"></i> Confirm Document Selection
    </button>
</div>

<div class="mt-6 flex justify-between">
    <button wire:click="prev"
            class="text-gray-600 hover:text-gray-800 font-medium py-2 px-5 rounded-lg flex items-center">
        <i class="fas fa-arrow-left mr-2"></i> Back
    </button>
<button wire:click="next"
    class="font-medium py-2 px-5 rounded-lg flex items-center 
        {{ $documentsConfirmed ? 'bg-indigo-600 hover:bg-indigo-700 text-white' : 'bg-gray-300 text-gray-500 opacity-50 cursor-not-allowed' }}"
    @if(!$documentsConfirmed) disabled @endif>
    Continue <i class="fas fa-arrow-right ml-2"></i>
</button>


</div>

</div>
@endif

@if ($current == 3)
<div class="p-4 md:p-6 rounded-xl bg-white shadow-sm h-full flex flex-col">
    <!-- Header -->
    <h2 class="text-lg md:text-xl font-semibold mb-3 md:mb-4 text-indigo-700 flex items-center">
        <i class="fas fa-laptop mr-2"></i> IT & Admin Requirements Declaration
    </h2>
    <p class="text-gray-600 mb-4 md:mb-6 text-sm md:text-base">Specify the IT resources, access, and admin items needed for this employee. Notifications will be sent to relevant departments.</p>
    
    <!-- IT Requirements Form -->
    <div class="space-y-6 mb-6 flex-grow">
        <!-- Hardware Section -->
        <div class="border border-gray-200 rounded-lg p-4">
            <h3 class="text-md font-medium text-gray-800 mb-3 flex items-center">
                <i class="fas fa-laptop-code mr-2 text-indigo-600"></i> Hardware Requirements
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-center">
                    <input id="laptop" type="checkbox" wire:model.defer="laptop" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="laptop" class="ml-2 block text-sm text-gray-700">Laptop</label>
                </div>
                <div class="flex items-center">
                    <input id="desktop" type="checkbox" wire:model.defer="desktop" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="desktop" class="ml-2 block text-sm text-gray-700">Desktop Computer</label>
                </div>
                <div class="flex items-center">
                    <input id="monitor" type="checkbox" wire:model.defer="monitor" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="monitor" class="ml-2 block text-sm text-gray-700">Additional Monitor</label>
                </div>
                <div class="flex items-center">
                    <input id="mobile" type="checkbox" wire:model.defer="mobile" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="mobile" class="ml-2 block text-sm text-gray-700">Company Mobile Phone</label>
                </div>
            </div>
        </div>

        <!-- Software Access Section -->
        <div class="border border-gray-200 rounded-lg p-4">
            <h3 class="text-md font-medium text-gray-800 mb-3 flex items-center">
                <i class="fas fa-key mr-2 text-indigo-600"></i> Software & System Access
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-center">
                    <input id="email" type="checkbox" wire:model.defer="email" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" checked>
                    <label for="email" class="ml-2 block text-sm text-gray-700">Company Email Account</label>
                </div>
                <div class="flex items-center">
                    <input id="acumatica" type="checkbox" wire:model.defer="acumatica" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="acumatica" class="ml-2 block text-sm text-gray-700">Acumatica Account</label>
                </div>
                <div class="flex items-center">
                    <input id="vpn" type="checkbox" wire:model.defer="vpn" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="vpn" class="ml-2 block text-sm text-gray-700">VPN Access</label>
                </div>
                <div class="flex items-center">
                    <input id="shared_drives" type="checkbox" wire:model.defer="shared_drives" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="shared_drives" class="ml-2 block text-sm text-gray-700">Shared Drive Access</label>
                </div>
            </div>
        </div>

        <!-- Facility Access Section -->
        <div class="border border-gray-200 rounded-lg p-4">
            <h3 class="text-md font-medium text-gray-800 mb-3 flex items-center">
                <i class="fas fa-door-open mr-2 text-indigo-600"></i> Facility Access
            </h3>
            <p class="text-sm text-gray-500 mb-3">Select which gates/areas this employee should have access to:</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-center">
                    <input id="main_gate" type="checkbox" wire:model.defer="main_gate" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="main_gate" class="ml-2 block text-sm text-gray-700">Main Gate</label>
                </div>
                <div class="flex items-center">
                    <input id="production" type="checkbox" wire:model.defer="production" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="production" class="ml-2 block text-sm text-gray-700">Production Area</label>
                </div>
                <div class="flex items-center">
                    <input id="warehouse" type="checkbox" wire:model.defer="warehouse" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="warehouse" class="ml-2 block text-sm text-gray-700">Warehouse</label>
                </div>
                <div class="flex items-center">
                    <input id="office" type="checkbox" wire:model.defer="office" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" checked>
                    <label for="office" class="ml-2 block text-sm text-gray-700">Office Building</label>
                </div>
            </div>
        </div>

        <!-- Admin Requirements Section -->
        <div class="border border-gray-200 rounded-lg p-4">
            <h3 class="text-md font-medium text-gray-800 mb-3 flex items-center">
                <i class="fas fa-hard-hat mr-2 text-indigo-600"></i> Admin Requirements & PPE
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-center">
                    <input id="safety_shoes" type="checkbox" wire:model.defer="safety_shoes" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="safety_shoes" class="ml-2 block text-sm text-gray-700">Safety Shoes</label>
                </div>
                <div class="flex items-center">
                    <input id="hard_hat" type="checkbox" wire:model.defer="hard_hat" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="hard_hat" class="ml-2 block text-sm text-gray-700">Hard Hat</label>
                </div>
                <div class="flex items-center">
                    <input id="safety_glasses" type="checkbox" wire:model.defer="safety_glasses" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="safety_glasses" class="ml-2 block text-sm text-gray-700">Safety Glasses</label>
                </div>
                <div class="flex items-center">
                    <input id="uniform" type="checkbox" wire:model.defer="uniform" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="uniform" class="ml-2 block text-sm text-gray-700">Company Uniform</label>
                </div>
                <div class="flex items-center">
                    <input id="id_card" type="checkbox" wire:model.defer="id_card" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" checked>
                    <label for="id_card" class="ml-2 block text-sm text-gray-700">ID Card</label>
                </div>
                <div class="flex items-center">
                    <input id="access_card" type="checkbox" wire:model.defer="access_card" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="access_card" class="ml-2 block text-sm text-gray-700">Access Card</label>
                </div>
            </div>
        </div>

        <!-- Additional Notes -->
        <div class="border border-gray-200 rounded-lg p-4">
            <h3 class="text-md font-medium text-gray-800 mb-3 flex items-center">
                <i class="fas fa-sticky-note mr-2 text-indigo-600"></i> Additional Notes For IT
            </h3>
            <textarea wire:model.defer="notes_it" class="w-full h-24 px-3 py-2 text-gray-700 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Add any special requirements or notes for the IT or Admin departments..."></textarea>
        </div>

         <div class="border border-gray-200 rounded-lg p-4">
            <h3 class="text-md font-medium text-gray-800 mb-3 flex items-center">
                <i class="fas fa-sticky-note mr-2 text-indigo-600"></i> Additional Notes For Admin
            </h3>
            <textarea wire:model.defer="notes_admin" class="w-full h-24 px-3 py-2 text-gray-700 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Add any special requirements or notes for the IT or Admin departments..."></textarea>
        </div>
    </div>
    
    <!-- Navigation -->
    <div class="mt-6 flex justify-between">
        <button wire:click="prev"
                class="text-gray-600 hover:text-gray-800 font-medium py-2 px-5 rounded-lg flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Back
        </button>
        <button wire:click="next"
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-5 rounded-lg flex items-center">
            Submit Requirements <i class="fas fa-paper-plane ml-2"></i>
        </button>
    </div>
</div>
@endif

<!-- STEP 4: HR Review & Notifications -->
@if ($current == 4)
    <div class="p-4 md:p-6 rounded-xl bg-white shadow-sm h-full flex flex-col">
        <h2 class="text-lg md:text-xl font-semibold mb-3 md:mb-4 text-indigo-700 flex items-center">
            <i class="fas fa-user-check mr-2"></i> HR Review & Notifications
        </h2>
        
        <div class="bg-gray-50 p-4 md:p-6 rounded-lg mb-4 md:mb-6 flex-grow overflow-auto">
            <!-- Personal Information Review -->
            <h3 class="font-medium text-md md:text-lg mb-3 md:mb-4 text-indigo-600">Personal Information</h3>

 
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4 mb-4 p-3 bg-white rounded-md">
                <div><span class="text-gray-600">Name:</span> {{ $personalInfo->name }}</div>
                <div><span class="text-gray-600">Email:</span> {{ $personalInfo->email }}</div>
                <div><span class="text-gray-600">Phone:</span> {{ $personalInfo->phone }}</div>
                <div><span class="text-gray-600">Position:</span> {{ $personalInfo->position }}</div>
                <div><span class="text-gray-600">Department:</span> {{ $personalInfo->department }}</div>
                <div><span class="text-gray-600">Start Date:</span> {{ date('M d, Y', strtotime($personalInfo->start_date)) }}</div>
            </div>

            
            <!-- Notification Section -->
            <h3 class="font-medium text-md md:text-lg mt-6 mb-3 md:mb-4 text-indigo-600">Notification Setup</h3>
            <!-- New Hire Notifications Section -->
            <div class="bg-white rounded-xl shadow-sm border-l-4 border-indigo-500 overflow-hidden transition-all duration-300 hover:shadow-md">
                <div class="p-6">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div class="bg-indigo-100 p-3 rounded-lg mr-3">
                                <i class="fas fa-bell text-indigo-600 text-lg"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-indigo-700 text-lg">New Hire Notification</h4>
                                <p class="text-sm text-gray-500 mt-1">Documents required before start date</p>
                            </div>
                        </div>
                       
                    </div>

                    <!-- Content -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Left Column - Instructions -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-start mb-3">
                                <i class="fas fa-info-circle text-indigo-500 mt-1 mr-2"></i>
                                <p class="text-sm text-gray-700">
                                    An email will be sent to the new hire to upload the following documents before their start date. 
                                    All documents must be uploaded for approval to be done.
                                </p>
                            </div>                       
                        </div>

                        <!-- Right Column - Documents -->
                        <div>
                            <h5 class="font-medium text-gray-700 mb-3 flex items-center">
                                <i class="fas fa-file-alt mr-2 text-indigo-500"></i> Required Documents
                            </h5>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach($onboardingDocuments as $doc)
                                <div class="flex items-center bg-gray-50 rounded-lg p-3 transition-colors hover:bg-indigo-50 group">
                                    <div class="bg-white p-2 rounded-md mr-3 group-hover:bg-indigo-100 transition-colors">
                                        <i class="fas fa-file-pdf text-red-500"></i>
                                    </div>
                                    <span class="text-gray-700 font-medium text-sm">{{ $doc->document->name }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Footer Actions -->
                    <div class="flex justify-between items-center mt-6 pt-4 border-t border-gray-100">
                        <div class="text-sm text-gray-500">
                            <i class="far fa-user mr-1"></i> Recipient: New Hire
                        </div>
                        <div class="flex space-x-3">
                        
                            <button 
                                wire:click="sendNewHireNotification"
                                wire:loading.attr="disabled"
                                wire:target="sendNewHireNotification"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md flex items-center w-full justify-center disabled:opacity-50 disabled:cursor-not-allowed">
                                
                                <i class="fas fa-paper-plane mr-2"></i>
                                
                                <!-- Normal state -->
                                <span wire:loading.remove wire:target="sendNewHireNotification">
                                    Send Email to New Hire
                                </span>
                                
                                <!-- Loading state -->
                                <span wire:loading wire:target="sendNewHireNotification" class="flex items-center">
                                    Sending Email to New Hire...
                                    <i class="fas fa-spinner fa-spin ml-2"></i>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            

            <!-- Team Notification -->
            <div class="bg-white p-4 mt-3 rounded-md mb-4 border-l-4 border-blue-500">
                <h4 class="font-medium text-blue-700 mb-3 flex items-center">
                    <i class="fas fa-users mr-2"></i> Team Notification Will be sent via Email
                </h4>
                <p class="text-sm text-gray-600 mb-3">
                    Notify team members about the new hire and their requirements
                </p>

                <!-- Team Member Search and Selection -->
<div class="mb-4" 
     x-data="{ 
        search: '',
        selectedMembers: @entangle('selectedTeamMembers').live, // Changed from 'selectedMembers' to 'selectedTeamMembers'
        teamMembers: @entangle('allTeamMembers'), // Changed from 'teamMembers' to 'allTeamMembers'
        filteredMembers: [],
        showDropdown: false
     }" 
     x-init="
        filteredMembers = [];
        $watch('search', value => {
            if (value.length > 0) {
                showDropdown = true;
                filteredMembers = teamMembers.filter(member => 
                    member.name.toLowerCase().includes(value.toLowerCase()) ||
                    member.email.toLowerCase().includes(value.toLowerCase()) ||
                    member.id.toString().includes(value)
                );
            } else {
                showDropdown = false;
                filteredMembers = [];
            }
        });
        
        // Close dropdown when clicking outside
        $nextTick(() => {
            window.addEventListener('click', (e) => {
                if (!e.target.closest('[data-team-search]')) {
                    showDropdown = false;
                }
            });
        });
     ">

    <label class="block text-gray-700 text-sm font-medium mb-2">
        Search and Add Team Members
    </label>

    <!-- Search Bar -->
    <div class="relative" data-team-search>
        <input 
            type="text"
            x-model="search"
            @focus="if (search.length > 0) showDropdown = true"
            placeholder="Type to search team members by name, email, or ID..."
            class="w-full px-3 py-2 border border-gray-300 rounded-md 
                focus:outline-none focus:ring-2 focus:ring-indigo-500"
            autocomplete="off"
        >

        <!-- Icons -->
        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
            <i class="fas fa-search text-gray-400"></i>
        </div>
    </div>

    <!-- Dropdown Results -->
    <div x-show="showDropdown && filteredMembers.length > 0"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-md shadow-lg max-h-60 overflow-y-auto"
         style="width: inherit;">
        <template x-for="member in filteredMembers" :key="member.id">
            <div class="px-3 py-2 hover:bg-indigo-50 cursor-pointer border-b border-gray-100 last:border-b-0"
                 @click="
                     if (!selectedMembers.some(m => m.id === member.id)) {
                         selectedMembers.push({
                             id: member.id,
                             name: member.name,
                             email: member.email
                         });
                     }
                     search = '';
                     showDropdown = false;
                 ">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="font-medium text-gray-800" x-text="member.name"></span>
                        <div class="text-sm text-gray-600">
                            <span x-text="member.email"></span>
                            <span class="mx-1">•</span>
                            <span>ID: <span x-text="member.id"></span></span>
                        </div>
                    </div>
                    <svg x-show="selectedMembers.some(m => m.id === member.id)" 
                         class="w-5 h-5 text-green-500" 
                         fill="currentColor" 
                         viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
        </template>
    </div>

    <!-- No Results Message -->
    <div x-show="showDropdown && filteredMembers.length === 0 && search.length > 0"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         class="absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-md shadow-lg p-3"
         style="width: inherit;">
        <div class="text-center text-gray-500">
            No team members found for "<span x-text="search"></span>"
        </div>
    </div>

    <!-- Display Selected Team Members -->
    <div class="mt-3">
        <label class="block text-gray-700 text-sm font-medium mb-2">
            Selected Team Members:
        </label>

        <div class="min-h-20 p-3 border border-gray-300 rounded-md bg-gray-50">
            <template x-if="selectedMembers.length === 0">
                <span class="text-gray-500 text-sm">No team members selected yet</span>
            </template>

            <template x-for="member in selectedMembers" :key="member.id">
                <span 
                    class="inline-flex items-center bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-md mr-2 mb-2"
                >
                    <span>
                        <span x-text="member.name"></span>
                        <span class="text-gray-500 ml-1">
                            (<span x-text="member.email"></span>)
                        </span>
                    </span>

                    <button 
                        @click="selectedMembers = selectedMembers.filter(m => m.id !== member.id)"
                        type="button"
                        class="ml-3 text-red-600 hover:text-red-800 font-bold focus:outline-none"
                        title="Remove member"
                    >
                        ✕
                    </button>
                </span>
            </template>
        </div>
    </div>
</div>

                <button 
                        wire:click="sendEmailNotifications"
                        wire:loading.attr="disabled"
                        wire:target="sendEmailNotifications"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md flex items-center w-full justify-center disabled:opacity-50 disabled:cursor-not-allowed">
                        
                        <i class="fas fa-paper-plane mr-2"></i>
                        
                        <!-- Normal state -->
                        <span wire:loading.remove wire:target="sendEmailNotifications">
                            Send Email Notifications
                        </span>
                        
                        <!-- Loading state -->
                        <span wire:loading wire:target="sendEmailNotifications" class="flex items-center">
                            Sending Email Notifications...
                            <i class="fas fa-spinner fa-spin ml-2"></i>
                        </span>
                    </button>

            </div>

           
                        
                    <!-- Support Staff Notification -->
            <div class="bg-white p-4 rounded-md mb-4 border-l-4 border-green-500">
                <h4 class="font-medium text-green-700 mb-3 flex items-center">
                    <i class="fas fa-tools mr-2"></i> Support Staff Notifications Will be sent via (SMS)
                </h4>
                <p class="text-sm text-gray-600 mb-3">
                    Notify support staff to prepare for the new hire's arrival
                </p>
                
                <!-- Support Staff Checkbox Selection -->
                            <div class="space-y-3">
                                <label class="flex items-center">
                                    <input type="checkbox" class="form-checkbox text-green-600 support-checkbox" wire:model="supportStaffNumbers" value="{{config('app.support_one_number')}}">
                                    <span class="ml-2 text-gray-700">{{ config('app.support_one_name') }}</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="form-checkbox text-green-600 support-checkbox" wire:model="supportStaffNumbers" value="{{ config('app.support_two_number') }}">
                                    <span class="ml-2 text-gray-700">{{ config('app.support_two_name') }}</span>
                                </label>
                            </div>
                            

                            <!-- Send Notification Button -->
                            <div class="mt-4">
                                <button 
                                    type="button"
                                    wire:click="sendSupportNotification"
                                    wire:loading.attr="disabled"
                                    wire:target="sendSupportNotification"
                                    class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md shadow hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <i class="fas fa-paper-plane mr-1"></i> 
                                    <span wire:loading.remove wire:target="sendSupportNotification">Send Notification</span>
                                    <span wire:loading wire:target="sendSupportNotification">
                                        Sending...
                                        <i class="fas fa-spinner fa-spin ml-1"></i>
                                    </span>
                                </button>
                            </div>
                        </div>
            
                    
                    </div>

                    <div class="flex justify-between pt-3">
                        <button wire:click="prev"
                                class="text-gray-600 hover:text-gray-800 font-medium py-2 px-5 rounded-lg flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i> Back
                        </button>
                        <button wire:click="completeOnboarding"
                                class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-5 rounded-lg flex items-center">
                            <i class="fas fa-check-circle mr-2"></i> Complete Onboarding
                        </button>
                    </div>
                </div>
            @endif
                </div>
                </div>

                <!-- Footer -->
                <div class="bg-gray-100 p-3 md:p-4 text-center text-xs md:text-sm text-gray-600 flex-shrink-0">
                    <p>© 2025 KIM-FAY EA LTD. All rights reserved.</p>
                </div>

            </div>

</div>