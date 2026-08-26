<div x-cloak>
    <nav class="flex flex-wrap items-center mb-6 text-sm font-medium text-gray-700 dark:text-gray-200" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-2">
            <li class="inline-flex items-center">
                <svg class="w-4 h-4 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                    <path d="M7.05 5.05a.7.7 0 011 0l4.9 4.9a.7.7 0 010 1l-4.9 4.9a.7.7 0 01-1-1l4.4-4.4-4.4-4.4a.7.7 0 010-1z" />
                </svg>
                <span class="ml-1 text-blue-500 dark:text-gray-400">Hr user Induction Progress tracking page</span>
            </li>
        </ol>
    </nav>

    <div class="flex flex-col md:flex-row gap-4">
        <!-- Card 1 -->
        <div class="max-w-sm md:w-1/2 pb-3">
            <a wire:navigate href="{{ route('onboard-new-user') }}" class="group block p-6 border rounded-2xl shadow-sm transition-all duration-300 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:border-blue-500 hover:shadow-md hover:transform hover:-translate-y-1">
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
        </div>

        <!-- Card 2 -->
        <div class="max-w-sm md:w-1/2 pb-3">
            <a wire:navigate href="{{ route('continue-onboarding') }}" class="group block p-6 border rounded-2xl shadow-sm transition-all duration-300 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:border-blue-500 hover:shadow-md hover:transform hover:-translate-y-1">
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
        </div>

        <!-- Document Management Card -->
        <div class="max-w-sm md:w-1/3 pb-3">
            <a wire:navigate href="{{ route('documents-management') }}" class="group block p-6 border rounded-2xl shadow-sm transition-all duration-300 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:border-blue-500 hover:shadow-md hover:transform hover:-translate-y-1">
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-blue-100 dark:bg-gray-700 rounded-full transition group-hover:bg-white group-hover:dark:bg-white/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-600 dark:text-blue-400 group-hover:text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.828a2 2 0 00-.586-1.414l-4.828-4.828A2 2 0 009.172 1H6zm5 1.414L15.586 7H12a1 1 0 01-1-1V3.414z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400">Document Management</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Manage required documents</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="overflow-x-auto shadow-md rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Department</th>
                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date Onboarded</th>
                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Progress</th>
                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Reset Password Link</th>
                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Score</th>
                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Hr Finish</th>
                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                    
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($pendingOnboardings as $onboarding)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                        <td class="px-3 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $onboarding->name }}</td>
                        <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $onboarding->department }}</td>
                        <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $onboarding->created_at->format('Y-m-d') }}</td>
                        <td class="px-3 py-4 whitespace-nowrap">
                            @php
                                $total = $onboarding->total_questions ?? 0;
                                $answered = $onboarding->answered_questions ?? 0;

                                $percentage = $total > 0 ? round(($answered / $total) * 100) : 0;

                                // Determine color based on progress
                                if ($percentage < 40) {
                                    $color = 'bg-red-500';
                                } elseif ($percentage < 70) {
                                    $color = 'bg-yellow-400';
                                } else {
                                    $color = 'bg-green-500';
                                }
                            @endphp

                            <div class="flex items-center gap-2">
                                <div class="w-24 bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                    <div 
                                        class="{{ $color }} h-2.5 rounded-full transition-all duration-300"
                                        style="width: {{ $percentage }}%">
                                    </div>
                                </div>

                                <span class="text-xs font-medium text-gray-600 dark:text-gray-300">
                                    {{ $percentage }}%
                                </span>
                            </div>
                        </td>
                        <td class="px-3 py-4 whitespace-nowrap text-sm font-medium">
                            <button 
                                wire:click="resendPasswordLink('{{ $onboarding->id }}')" 
                                wire:loading.attr="disabled"
                                class="flex items-center gap-2 text-blue-600 hover:text-blue-900 
                                    dark:text-blue-400 dark:hover:text-blue-300 
                                    hover:bg-blue-50 dark:hover:bg-gray-700 
                                    px-3 py-1.5 rounded-md 
                                    transition-all duration-200 ease-in-out 
                                    transform hover:scale-105 active:scale-95 
                                    disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <!-- Default Icon -->
                                <svg 
                                    wire:loading.remove 
                                    wire:target="resendPasswordLink('{{ $onboarding->id }}')"
                                    class="w-5 h-5"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>

                                <!-- Spinner -->
                                <svg 
                                    wire:loading 
                                    wire:target="resendPasswordLink('{{ $onboarding->id }}')"
                                    class="w-5 h-5 animate-spin"
                                    fill="none" viewBox="0 0 24 24"
                                >
                                    <circle 
                                        class="opacity-25" 
                                        cx="12" cy="12" r="10" 
                                        stroke="currentColor" stroke-width="4">
                                    </circle>
                                    <path 
                                        class="opacity-75" 
                                        fill="currentColor"
                                        d="M4 12a8 8 0 018-8v8H4z">
                                    </path>
                                </svg>

                                <!-- Text -->
                                <span wire:loading.remove wire:target="resendPasswordLink('{{ $onboarding->id }}')">
                                    Resend Link
                                </span>

                                <span wire:loading wire:target="resendPasswordLink('{{ $onboarding->id }}')">
                                    Sending...
                                </span>
                            </button>
                        </td>
                        <td class="px-3 py-4 whitespace-nowrap">
                            <!-- Display the score from the database -->
                            <span class="text-sm font-medium text-gray-900">
                                {{ $onboarding->score ?? 'N/A' }}%
                            </span>
                        </td>
                        <td class="px-3 py-4 whitespace-nowrap">
                            <button wire:click="markItFinished(1)" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition">
                                Mark HR Finish
                            </button>
                        </td>
                        <td class="px-3 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                Pending it
                            </span>
                        </td>
                        
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                            No pending onboardings found.
                        </td> 
                    </tr>
                @endforelse
                
            </tbody>
        </table>
    </div>
</div>