<div x-cloak class="p-4 md:p-6 rounded-xl bg-white shadow-lg border border-gray-100 h-full flex flex-col">
    <!-- Header Section -->
    <div class="mb-6">
        <h2 class="text-xl md:text-2xl font-bold mb-2 text-indigo-700 flex items-center">
            <i class="fas fa-user-tie mr-3 bg-indigo-100 p-2 rounded-lg"></i> HR Document Approval
        </h2>
        <p class="text-gray-600 text-sm md:text-base">Review, manage, and approve submitted documents.</p>
    </div>

    <!-- Search and Filter Section -->
    <div class="mb-6 flex flex-col md:flex-row gap-4 justify-between items-start md:items-center">
        <div class="relative w-full md:w-64">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
            <input type="text"
                   wire:model.live.debounce.500ms="search"
                   class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                   placeholder="Search documents...">
                   
        </div>
        
        <div class="flex gap-2">
    <select wire:model.live="statusFilter"
            class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
        <option value="all">All Status</option>
        <option value="pending">Pending Review</option>
        <option value="approved">Approved</option>
    </select>
</div>

    </div>

    <!-- Documents Table -->
    <div class="overflow-x-auto rounded-lg border border-gray-200 mb-6 flex-grow shadow-sm">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        User
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Document Name
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Submitted
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($onboardingDocuments as $onboardDoc)
                <tr class="hover:bg-gray-50 transition-colors">
                    <!-- User Column -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div class="h-10 w-10 rounded-full bg-indigo-100 text-indigo-800 flex items-center justify-center font-semibold">
                                    {{ substr($onboardDoc->onboarding->name, 0, 2) }}
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $onboardDoc->onboarding->name }}</div>
                            </div>
                        </div>
                    </td>
                    <!-- Document Name -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $onboardDoc->document->name }}</div>
                        <div class="text-sm text-gray-500">{{ $onboardDoc->document->type }}</div>
                    </td>
                    <!-- Submitted Date -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $onboardDoc->created_at->format('M d, Y') }}</div>
                        <div class="text-sm text-gray-500">{{ $onboardDoc->created_at->format('g:i A') }}</div>
                    </td>
                    <!-- Status -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex justify-start">
                            @if($onboardDoc->submitted == false)
                            <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200 shadow-sm">
                                <i class="fas fa-clock mr-2 text-amber-500 text-xs"></i>
                                Pending Submission
                            </span>
                            @elseif($onboardDoc->submitted == true && $onboardDoc->hr_approved == false)
                            <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200 shadow-sm">
                                <i class="fas fa-hourglass-half mr-2 text-blue-500 text-xs"></i>
                                Under Review
                            </span>
                            @elseif($onboardDoc->submitted == true && $onboardDoc->hr_approved == true)
                            <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-sm">
                                <i class="fas fa-check-circle mr-2 text-emerald-500 text-xs"></i>
                                Approved
                            </span>
                            @endif
                        </div>
                    </td>
<td class="px-4 py-3 whitespace-nowrap">
    <div class="flex items-center gap-2">
        <!-- View Button -->
        <button 
            wire:click="viewDocument({{ $onboardDoc->id }})" 
            wire:loading.attr="disabled" 
            wire:target="viewDocument({{ $onboardDoc->id }})"
            class="inline-flex items-center px-3 py-1.5 bg-indigo-500 text-white text-xs font-medium rounded-md hover:bg-indigo-600 focus:outline-none focus:ring-1 focus:ring-indigo-300 transition-colors duration-150"
            title="View document">

            <span wire:loading.remove wire:target="viewDocument({{ $onboardDoc->id }})">
                <i class="fas fa-eye mr-1.5 text-xs"></i>
                View
            </span>

            <span wire:loading wire:target="viewDocument({{ $onboardDoc->id }})">
                <i class="fas fa-spinner fa-spin mr-1.5 text-xs"></i>
            </span>
        </button>

        @if($onboardDoc->hr_approved == false && $onboardDoc->submitted == true)
            <!-- Action Buttons -->
            <div class="flex items-center gap-1">
                <!-- Disapprove Button -->
                <button 
                    wire:click="disapproveDocument({{ $onboardDoc->id }})" 
                    wire:loading.attr="disabled"
                    wire:target="disapproveDocument({{ $onboardDoc->id }})"
                    class="inline-flex items-center px-3 py-1.5 bg-red-500 text-white text-xs font-medium rounded-md hover:bg-red-600 focus:outline-none focus:ring-1 focus:ring-red-300 transition-colors duration-150"
                    title="Disapprove document">
                    
                    <span wire:loading.remove wire:target="disapproveDocument({{ $onboardDoc->id }})">
                        <i class="fas fa-times text-xs"></i>Disaprove
                    </span>
                    
                    <span wire:loading wire:target="disapproveDocument({{ $onboardDoc->id }})">
                        <i class="fas fa-spinner fa-spin text-xs"></i>
                    </span>
                </button>

                <!-- Approve Button -->
                <button 
                    wire:click="approveDocument({{ $onboardDoc->id }}, {{ $onboardDoc->onboarding_id }})" 
                    wire:loading.attr="disabled"
                    wire:target="approveDocument({{ $onboardDoc->id }}, {{ $onboardDoc->onboarding_id }})" 
                    class="inline-flex items-center px-3 py-1.5 bg-green-500 text-white text-xs font-medium rounded-md hover:bg-green-600 focus:outline-none focus:ring-1 focus:ring-green-300 transition-colors duration-150"
                    title="Approve document">

                    <span wire:loading.remove wire:target="approveDocument({{ $onboardDoc->id }}, {{ $onboardDoc->onboarding_id }})">
                        <i class="fas fa-check text-xs"></i>Approve
                    </span>

                    <span wire:loading wire:target="approveDocument({{ $onboardDoc->id }}, {{ $onboardDoc->onboarding_id }})">
                        <i class="fas fa-spinner fa-spin text-xs"></i>
                    </span>
                </button>
            </div>
        @endif
    </div>
</td>

<!-- Minimal Modal -->
@if($showPreview)
<div 
    class="fixed inset-0 flex items-center justify-center bg-black/40 z-50 p-4"
    wire:click="$set('showPreview', false)">
    
    <div class="relative bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] flex flex-col"
         wire:click.stop>
         
        <!-- Close Button -->
        <button wire:click="$set('showPreview', false)" 
                class="absolute -top-2 -right-2 w-6 h-6 flex items-center justify-center 
                       rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 
                       text-xs shadow transition-colors duration-150 z-10">
            ✕
        </button>

        <!-- Preview -->
        <iframe src="{{ asset('storage/' . $previewPath) }}" 
                class="w-full h-[500px] rounded-lg"
                frameborder="0"
                loading="lazy">
        </iframe>
    </div>
</div>
@endif

                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center">
                        <div class="flex flex-col items-center justify-center text-gray-400">
                            <i class="fas fa-folder-open text-4xl mb-2"></i>
                            <p class="text-lg font-medium">No documents found</p>
                            <p class="text-sm">There are no documents awaiting approval</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="flex items-center justify-between border-t border-gray-200 pt-4">
        <div class="flex gap-2">
            {{ $onboardingDocuments->links() }}
        </div>
    </div>

</div>