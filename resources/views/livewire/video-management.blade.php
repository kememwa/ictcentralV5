<div>
  
<div x-data="{ 
    showQuestionBank: @entangle('showQuestionBank'),
    selectedVideo: @entangle('selectedVideo'),
    videos: @entangle('videos'),
    questions: @entangle('questions'),
    
    // Form fields
    new_question_text: @entangle('new_question_text'),
    new_question_option_a: @entangle('new_question_option_a'),
    new_question_option_b: @entangle('new_question_option_b'),
    new_question_option_c: @entangle('new_question_option_c'),
    new_question_option_d: @entangle('new_question_option_d'),
    new_question_correct_answer: @entangle('new_question_correct_answer'),

    init() {
        // Watch for selectedVideo changes and call Livewire
        this.$watch('selectedVideo', (value) => {
            if (value) {
                // This will trigger the updatedSelectedVideo method in Livewire
                $wire.set('selectedVideo', value);
            }
        });
    }
}" 
x-effect="!showQuestionBank && (selectedVideo = null, new_question_text = '', new_question_option_a = '', new_question_option_b = '', new_question_option_c = '', new_question_option_d = '', new_question_correct_answer = '')"
x-cloak 
class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Video Management</h1>
                <p class="text-gray-600 dark:text-gray-300 mt-2">Manage training and induction videos for your organization</p>
            </div>

            <div class="flex gap-2">
                <!-- Question Bank Button -->
                <button
                    @click="showQuestionBank = true; $wire.loadQuestionBank()"
                    class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg shadow transition duration-200 flex items-center gap-2"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z" />
                    </svg>
                    Question Bank
                </button>

                <div class="md:col-span-4"
                    x-data="{ 
                        isOpen: false,
                        loading: false
                    }"
                    x-init="$watch('isOpen', val => document.body.classList.toggle('overflow-hidden', val))"
                >
                    <!-- Trigger Button -->
                    <button
                        @click="isOpen = true"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow transition duration-200 flex items-center gap-2"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Upload New Video
                    </button>

                    <!-- Upload Video Modal -->
                    <div
                        x-show="isOpen"
                        x-transition.opacity.duration.300ms
                        @keydown.escape.window="isOpen = false"
                        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50"
                        x-cloak
                    >
                        <!-- Modal Panel -->
                        <div
                            @click.away="isOpen = false"
                            x-transition.scale.origin.center.duration.300ms
                            class="relative w-full max-w-2xl"
                        >
                            <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-xl max-h-[90vh] overflow-y-auto">
                                <!-- Header -->
                                <div class="sticky top-0 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                                    <h3 class="text-xl font-semibold text-gray-800 dark:text-white">Upload New Video</h3>
                                    <button @click="isOpen = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>

                                <!-- Form -->
                                <form class="p-6 space-y-4">
                                    <!-- Title -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Video Title</label>
                                        <input type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                    </div>

                                    <!-- Description -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                                        <textarea rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"></textarea>
                                    </div>

                                    <!-- Category Selection -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category</label>
                                        <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                            <option value="">Select Category</option>
                                            <option value="who-we-are">Who We Are</option>
                                            <option value="our-products">Our Products</option>
                                            <option value="departmental-induction">Departmental Induction</option>
                                            <option value="knowledgebase">Knowledge Base</option>
                                        </select>
                                    </div>

                                    <!-- Video Upload Options -->
                                    <div class="space-y-4">
                                        <div class="flex items-center space-x-4">
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="video_source" value="upload" class="form-radio text-blue-600" checked>
                                                <span class="ml-2">Upload Video File</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="video_source" value="youtube" class="form-radio text-blue-600">
                                                <span class="ml-2">YouTube URL</span>
                                            </label>
                                        </div>

                                        <!-- File Upload -->
                                        <div>
                                            <label class="block text-sm font-medium mb-2">Video File</label>
                                            <div class="border-2 border-dashed rounded-lg p-6 text-center">
                                                <input type="file" id="videoUploader" class="hidden">
                                                <button type="button"
                                                    onclick="document.getElementById('videoUploader').click()"
                                                    class="bg-blue-600 text-white px-4 py-2 rounded">
                                                    Select Video
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Form Actions -->
                                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                                        <button type="button" 
                                                @click="isOpen = false"
                                                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-200">
                                            Cancel
                                        </button>
                                        <button type="submit"
                                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow transition duration-200">
                                            Upload Video
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Question Bank Modal -->
    <div x-show="showQuestionBank"
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50"
         x-transition.opacity.duration.300ms>

        <div @click.away="showQuestionBank = false; $wire.resetQuestionForm()" class="relative w-full max-w-4xl max-h-[90vh] overflow-y-auto bg-white dark:bg-gray-800 rounded-xl shadow-xl">
            <!-- Modal Header -->
            <div class="sticky top-0 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-6 py-4 flex justify-between items-center">
                <h3 class="text-xl font-semibold text-gray-800 dark:text-white">Question Bank Management</h3>
                <button @click="showQuestionBank = false; $wire.resetQuestionForm()" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Content -->
            <div class="p-6">
    <!-- Video Selection Dropdown -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Video</label>
        <select x-model="selectedVideo" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
            <option value="">Choose a video to manage questions</option>
            <template x-for="video in videos" :key="video.id">
                <option :value="video.id" x-text="video.title + ' (' + video.category + ')'"></option>
            </template>
        </select>
    </div>

    <!-- When video is selected -->
    <template x-if="selectedVideo">
        <div>
            <!-- Add Question Form -->
            <div class="mb-8 p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                <h4 class="text-lg font-medium text-gray-800 dark:text-white mb-4">Add New Question</h4>
                
                <div class="space-y-4">
                    <!-- Question Text -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Question</label>
                        <textarea 
                            wire:model="new_question_text" 
                            rows="2" 
                            class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('new_question_text') border-red-500 @enderror"
                            placeholder="Enter your question"
                        ></textarea>
                        @error('new_question_text')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Options A-D -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <span class="inline-block w-6 h-6 bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 rounded-full text-center leading-6 mr-2">A</span>
                                Option A
                            </label>
                            <input 
                                type="text" 
                                wire:model="new_question_option_a" 
                                class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('new_question_option_a') border-red-500 @enderror"
                                placeholder="Option A"
                            >
                            @error('new_question_option_a')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <span class="inline-block w-6 h-6 bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 rounded-full text-center leading-6 mr-2">B</span>
                                Option B
                            </label>
                            <input 
                                type="text" 
                                wire:model="new_question_option_b" 
                                class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('new_question_option_b') border-red-500 @enderror"
                                placeholder="Option B"
                            >
                            @error('new_question_option_b')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <span class="inline-block w-6 h-6 bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 rounded-full text-center leading-6 mr-2">C</span>
                                Option C
                            </label>
                            <input 
                                type="text" 
                                wire:model="new_question_option_c" 
                                class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('new_question_option_c') border-red-500 @enderror"
                                placeholder="Option C"
                            >
                            @error('new_question_option_c')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <span class="inline-block w-6 h-6 bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 rounded-full text-center leading-6 mr-2">D</span>
                                Option D
                            </label>
                            <input 
                                type="text" 
                                wire:model="new_question_option_d" 
                                class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('new_question_option_d') border-red-500 @enderror"
                                placeholder="Option D"
                            >
                            @error('new_question_option_d')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Correct Answer Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Correct Answer</label>
                        <div class="flex gap-4">
                            <label class="inline-flex items-center">
                                <input 
                                    type="radio" 
                                    wire:model="new_question_correct_answer" 
                                    value="A" 
                                    class="form-radio text-blue-600 @error('new_question_correct_answer') border-red-500 @enderror"
                                >
                                <span class="ml-2">A</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input 
                                    type="radio" 
                                    wire:model="new_question_correct_answer" 
                                    value="B" 
                                    class="form-radio text-blue-600 @error('new_question_correct_answer') border-red-500 @enderror"
                                >
                                <span class="ml-2">B</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input 
                                    type="radio" 
                                    wire:model="new_question_correct_answer" 
                                    value="C" 
                                    class="form-radio text-blue-600 @error('new_question_correct_answer') border-red-500 @enderror"
                                >
                                <span class="ml-2">C</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input 
                                    type="radio" 
                                    wire:model="new_question_correct_answer" 
                                    value="D" 
                                    class="form-radio text-blue-600 @error('new_question_correct_answer') border-red-500 @enderror"
                                >
                                <span class="ml-2">D</span>
                            </label>
                        </div>
                        @error('new_question_correct_answer')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Add Question Button -->
                    <div class="flex justify-end">
                        <div class="flex justify-end">
                        <button 
                            type="button"
                            wire:click="addQuestion" 
                            wire:loading.attr="disabled"
                            wire:target="addQuestion"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow transition duration-200 flex items-center gap-2 min-w-[140px] justify-center"
                        >
                            <!-- Normal state -->
                            <span wire:loading.remove wire:target="addQuestion" class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                Add Question
                            </span>

                            <!-- Loading State -->
                            <span wire:loading wire:target="addQuestion" class="flex items-center gap-2">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Adding...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Questions List -->
            <div>
    <h4 class="text-lg font-medium text-gray-800 dark:text-white mb-4">Questions for this Video</h4>
    

    <!-- Questions List -->
    <div x-show="questions && questions.length > 0" x-cloak>
        <div class="space-y-4">
            <template x-for="(question, index) in questions" :key="question.id">
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 text-sm font-medium px-2.5 py-0.5 rounded">
                                    Question <span x-text="index + 1"></span>
                                </span>
                            </div>
                            <p class="text-gray-800 dark:text-white font-medium mb-3" x-text="question.text"></p>
                            
                            <div class="grid grid-cols-2 gap-2 mb-3">
                                <div class="flex items-center gap-2">
                                    <span class="w-6 h-6 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-full text-center leading-6 text-sm">A</span>
                                    <span class="text-gray-600 dark:text-gray-300" x-text="question.option_a"></span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="w-6 h-6 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-full text-center leading-6 text-sm">B</span>
                                    <span class="text-gray-600 dark:text-gray-300" x-text="question.option_b"></span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="w-6 h-6 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-full text-center leading-6 text-sm">C</span>
                                    <span class="text-gray-600 dark:text-gray-300" x-text="question.option_c"></span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="w-6 h-6 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-full text-center leading-6 text-sm">D</span>
                                    <span class="text-gray-600 dark:text-gray-300" x-text="question.option_d"></span>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-2">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Correct Answer:</span>
                                <span class="bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-300 text-sm font-medium px-2.5 py-0.5 rounded">
                                    Option <span x-text="question.correct_answer"></span>
                                </span>
                            </div>
                        </div>
                        
                        <button @click="
                            if(confirm('Are you sure you want to delete this question?')) {
                                $wire.deleteQuestion(question.id);
                            }
                        " class="text-red-600 hover:text-red-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>
    
    <div x-show="!questions || questions.length === 0" x-cloak>
        <div class="text-center py-8 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-gray-500 dark:text-gray-400">No questions added for this video yet.</p>
            <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Use the form above to add your first question.</p>
        </div>
    </div>
</div>
        </div>
    </template>

    <!-- When no video selected -->
    <template x-if="!selectedVideo">
        <div class="text-center py-12">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
            </svg>
            <p class="text-gray-500 dark:text-gray-400 text-lg">Please select a video to manage its questions</p>
        </div>
    </template>
</div>
        </div>
    </div>

    <!-- Category Tabs -->
    <div class="mb-6 border-b border-gray-200 dark:border-gray-700" x-data="{ activeTab: 'who-we-are' }">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center">
            <li class="mr-2">
                <a href="#" @click.prevent="activeTab = 'who-we-are'" 
                   :class="{ 'text-blue-600 border-b-2 border-blue-600': activeTab === 'who-we-are', 'text-gray-500 hover:text-gray-700': activeTab !== 'who-we-are' }"
                   class="inline-block p-4 rounded-t-lg">
                    Who We Are
                </a>
            </li>
            <li class="mr-2">
                <a href="#" @click.prevent="activeTab = 'our-products'"
                   :class="{ 'text-blue-600 border-b-2 border-blue-600': activeTab === 'our-products', 'text-gray-500 hover:text-gray-700': activeTab !== 'our-products' }"
                   class="inline-block p-4 rounded-t-lg">
                    Our Products
                </a>
            </li>
            <li class="mr-2">
                <a href="#" @click.prevent="activeTab = 'departmental-induction'"
                   :class="{ 'text-blue-600 border-b-2 border-blue-600': activeTab === 'departmental-induction', 'text-gray-500 hover:text-gray-700': activeTab !== 'departmental-induction' }"
                   class="inline-block p-4 rounded-t-lg">
                    Departmental Induction
                </a>
            </li>
            <li class="mr-2">
                <a href="#" @click.prevent="activeTab = 'knowledgebase'"
                   :class="{ 'text-blue-600 border-b-2 border-blue-600': activeTab === 'knowledgebase', 'text-gray-500 hover:text-gray-700': activeTab !== 'knowledgebase' }"
                   class="inline-block p-4 rounded-t-lg">
                    Knowledge Base
                </a>
            </li>
        </ul>

        <!-- Who We Are Videos -->
        <div x-show="activeTab === 'who-we-are'" class="mt-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Sample Video Card 1 -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="relative group">
                        <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" 
                             alt="Company Overview" 
                             class="w-full h-48 object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                            <button class="bg-white rounded-full p-3 shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                        <span class="absolute bottom-2 right-2 bg-black bg-opacity-75 text-white text-xs px-2 py-1 rounded">5:30</span>
                    </div>
                    <div class="p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-semibold text-gray-800 dark:text-white">Company Overview & History</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Learn about our company's mission, vision and values</p>
                                <div class="flex items-center gap-3 mt-2 text-xs text-gray-500 dark:text-gray-400">
                                    <span>Uploaded: Jan 15, 2024</span>
                                    <span>•</span>
                                    <span>Views: 245</span>
                                </div>
                            </div>
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="text-gray-500 hover:text-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                    </svg>
                                </button>
                                <div x-show="open" @click.away="open = false" 
                                     class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg border border-gray-200 dark:border-gray-700 z-10">
                                    <button class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Edit Video</button>
                                    <button class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-700">Delete Video</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sample Video Card 2 -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="relative group">
                        <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" 
                             alt="Leadership Team" 
                             class="w-full h-48 object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                            <button class="bg-white rounded-full p-3 shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                        <span class="absolute bottom-2 right-2 bg-black bg-opacity-75 text-white text-xs px-2 py-1 rounded">8:15</span>
                    </div>
                    <div class="p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-semibold text-gray-800 dark:text-white">Meet Our Leadership Team</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Introduction to our executive leadership</p>
                                <div class="flex items-center gap-3 mt-2 text-xs text-gray-500 dark:text-gray-400">
                                    <span>Uploaded: Feb 3, 2024</span>
                                    <span>•</span>
                                    <span>Views: 128</span>
                                </div>
                            </div>
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="text-gray-500 hover:text-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                    </svg>
                                </button>
                                <div x-show="open" @click.away="open = false" 
                                     class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg border border-gray-200 dark:border-gray-700 z-10">
                                    <button class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Edit Video</button>
                                    <button class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-700">Delete Video</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Our Products Videos -->
        <div x-show="activeTab === 'our-products'" class="mt-6" x-cloak>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Sample Product Video -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="relative group">
                        <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" 
                             alt="Product Demo" 
                             class="w-full h-48 object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                            <button class="bg-white rounded-full p-3 shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                        <span class="absolute bottom-2 right-2 bg-black bg-opacity-75 text-white text-xs px-2 py-1 rounded">12:20</span>
                    </div>
                    <div class="p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-semibold text-gray-800 dark:text-white">Product X Overview</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Complete walkthrough of our flagship product</p>
                                <div class="flex items-center gap-3 mt-2 text-xs text-gray-500 dark:text-gray-400">
                                    <span>Uploaded: Mar 10, 2024</span>
                                    <span>•</span>
                                    <span>Views: 567</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Departmental Induction Videos -->
        <div x-show="activeTab === 'departmental-induction'" class="mt-6" x-cloak>
            <div x-data="{ departmentTab: 'ict' }">
                <!-- Department Tabs -->
                <div class="mb-6">
                    <ul class="flex flex-wrap gap-2">
                        <li>
                            <button @click="departmentTab = 'ict'" 
                                    :class="{ 'bg-blue-600 text-white': departmentTab === 'ict', 'bg-gray-200 text-gray-700': departmentTab !== 'ict' }"
                                    class="px-4 py-2 rounded-lg font-medium transition">
                                ICT
                            </button>
                        </li>
                        <li>
                            <button @click="departmentTab = 'marketing'"
                                    :class="{ 'bg-blue-600 text-white': departmentTab === 'marketing', 'bg-gray-200 text-gray-700': departmentTab !== 'marketing' }"
                                    class="px-4 py-2 rounded-lg font-medium transition">
                                Marketing
                            </button>
                        </li>
                        <li>
                            <button @click="departmentTab = 'procurement'"
                                    :class="{ 'bg-blue-600 text-white': departmentTab === 'procurement', 'bg-gray-200 text-gray-700': departmentTab !== 'procurement' }"
                                    class="px-4 py-2 rounded-lg font-medium transition">
                                Procurement
                            </button>
                        </li>
                        <li>
                            <button @click="departmentTab = 'hr'"
                                    :class="{ 'bg-blue-600 text-white': departmentTab === 'hr', 'bg-gray-200 text-gray-700': departmentTab !== 'hr' }"
                                    class="px-4 py-2 rounded-lg font-medium transition">
                                HR
                            </button>
                        </li>
                        <li>
                            <button @click="departmentTab = 'fleet'"
                                    :class="{ 'bg-blue-600 text-white': departmentTab === 'fleet', 'bg-gray-200 text-gray-700': departmentTab !== 'fleet' }"
                                    class="px-4 py-2 rounded-lg font-medium transition">
                                Fleet
                            </button>
                        </li>
                    </ul>
                </div>

                <!-- ICT Department Videos -->
                <div x-show="departmentTab === 'ict'" x-cloak>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <div class="relative group">
                                <img src="https://images.unsplash.com/photo-1558494949-ef010cbdcc31?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" 
                                     alt="ICT Induction" 
                                     class="w-full h-48 object-cover">
                                <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                    <button class="bg-white rounded-full p-3 shadow-lg">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                                <span class="absolute bottom-2 right-2 bg-black bg-opacity-75 text-white text-xs px-2 py-1 rounded">15:45</span>
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-800 dark:text-white">ICT Department Induction</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">IT policies, systems access and security protocols</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Marketing Department Videos -->
                <div x-show="departmentTab === 'marketing'" x-cloak>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <div class="relative group">
                                <img src="https://images.unsplash.com/photo-1533750349088-cd871a92f312?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" 
                                     alt="Marketing Induction" 
                                     class="w-full h-48 object-cover">
                                <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                    <button class="bg-white rounded-full p-3 shadow-lg">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                                <span class="absolute bottom-2 right-2 bg-black bg-opacity-75 text-white text-xs px-2 py-1 rounded">12:30</span>
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-800 dark:text-white">Marketing Department Induction</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Brand guidelines, marketing tools and strategies</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Procurement Department Videos -->
                <div x-show="departmentTab === 'procurement'" x-cloak>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <div class="relative group">
                                <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" 
                                     alt="Procurement Induction" 
                                     class="w-full h-48 object-cover">
                                <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                    <button class="bg-white rounded-full p-3 shadow-lg">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                                <span class="absolute bottom-2 right-2 bg-black bg-opacity-75 text-white text-xs px-2 py-1 rounded">10:15</span>
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-800 dark:text-white">Procurement Department Induction</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Procurement processes, vendor management and policies</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- HR Department Videos -->
                <div x-show="departmentTab === 'hr'" x-cloak>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <div class="relative group">
                                <img src="https://images.unsplash.com/photo-1521791136064-7986c2920216?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" 
                                     alt="HR Induction" 
                                     class="w-full h-48 object-cover">
                                <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                    <button class="bg-white rounded-full p-3 shadow-lg">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                                <span class="absolute bottom-2 right-2 bg-black bg-opacity-75 text-white text-xs px-2 py-1 rounded">14:20</span>
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-800 dark:text-white">HR Department Induction</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">HR policies, payroll, benefits and employee relations</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fleet Department Videos -->
                <div x-show="departmentTab === 'fleet'" x-cloak>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <div class="relative group">
                                <img src="https://images.unsplash.com/photo-1570125909232-eb263c188f7e?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" 
                                     alt="Fleet Induction" 
                                     class="w-full h-48 object-cover">
                                <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                    <button class="bg-white rounded-full p-3 shadow-lg">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                                <span class="absolute bottom-2 right-2 bg-black bg-opacity-75 text-white text-xs px-2 py-1 rounded">11:45</span>
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-800 dark:text-white">Fleet Department Induction</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Vehicle policies, maintenance and safety procedures</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Knowledge Base Videos -->
        <div x-show="activeTab === 'knowledgebase'" class="mt-6" x-cloak>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Sample Knowledge Base Video -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="relative group">
                        <img src="https://images.unsplash.com/photo-1434030216411-0b793f4b4173?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" 
                             alt="Knowledge Base" 
                             class="w-full h-48 object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                            <button class="bg-white rounded-full p-3 shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                        <span class="absolute bottom-2 right-2 bg-black bg-opacity-75 text-white text-xs px-2 py-1 rounded">20:15</span>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-800 dark:text-white">Company Policies & Procedures</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Comprehensive guide to company policies</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let uploader = document.getElementById('videoUploader');
    if (!uploader) return;

    uploader.addEventListener('change', function(e) {
        let file = e.target.files[0];
        alert('File selected: ' + file.name + ' (Demo mode - no actual upload)');
    });
});
</script>
</div>