<div>

@if($pendingDocs->isNotEmpty())
<div class="mt-8">
    <div class="max-w-2xl w-full bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="bg-gradient-to-r from-blue-300 rounded-t-lg to-blue-700 p-6 text-white">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <!-- Company Logo -->
                     <div class="bg-white/20 p-2 rounded-lg">
                    <img src="{{ asset('images/kimfay.png') }}" 
                         alt="Company Logo" 
                         class="w-30 h-8 object-contain"
                         onerror="this.style.display='none'">
                </div>
                    
                    <!-- Text Content -->
                    <div class="border-l border-white/20 pl-4">
                        <h1 class="text-2xl font-bold mb-1">Complete Your Profile</h1>
                        <p class="text-blue-100">Upload your documents to get started</p>
                    </div>
                </div>
                
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <!-- Alert -->
            <div class="flex items-start mb-6 p-4 bg-amber-50 rounded-lg border border-amber-200">
                <i class="fas fa-exclamation-circle text-amber-500 mt-0.5 mr-3"></i>
                <div class="text-sm">
                    <p class="font-medium text-gray-800">Required documents needed</p>
                    <p class="text-gray-600 mt-1">Upload these to access your learning dashboard</p>
                </div>
            </div>

            <!-- Progress -->
            <div class="mb-6">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-gray-700">Progress</span>
                    <span class="text-sm font-medium text-gray-900">
                        {{ count(array_filter($uploads)) }}/{{ count($pendingDocs) }}
                    </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-500 h-2 rounded-full transition-all duration-500"
                         style="width: {{ (count($pendingDocs) ? (count(array_filter($uploads)) / count($pendingDocs) * 100) : 0) }}%">
                    </div>
                </div>
            </div>

            <!-- Document List -->
            <div class="space-y-4 mb-6">
                @foreach($pendingDocs as $onboardDoc)
                <div class="bg-gray-50 rounded-lg border border-gray-300 p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center min-w-0 flex-1">
                            <div class="bg-white p-2 rounded border border-gray-300 mr-3">
                                <i class="fas fa-file text-gray-500 text-sm"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h4 class="font-medium text-gray-900 text-sm truncate">{{ $onboardDoc->document->name }}</h4>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    {{ $onboardDoc->created_at->format('M d, Y') }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3 ml-4">
                            @if(isset($uploads[$onboardDoc->id]))
                                <span class="inline-flex items-center text-xs text-green-600 font-medium">
                                    <i class="fas fa-check mr-1.5 text-xs"></i>
                                    Done
                                </span>
                            @else
                                <span class="inline-flex items-center text-xs text-gray-500">
                                    <i class="fas fa-clock mr-1.5 text-xs"></i>
                                    Pending
                                </span>
                            @endif

                            <input type="file"
                                wire:model="uploads.{{ $onboardDoc->id }}"
                                class="hidden"
                                id="upload-{{ $onboardDoc->id }}"
                                accept=".pdf,.jpg,.jpeg,.png">

                            <label for="upload-{{ $onboardDoc->id }}"
                                class="cursor-pointer px-3 py-1.5 rounded text-xs font-medium border transition-colors
                                        @if(isset($uploads[$onboardDoc->id]))
                                            bg-green-50 text-green-700 border-green-300
                                        @else
                                            bg-white text-gray-700 border-gray-400 hover:bg-gray-50
                                        @endif">

                                <!-- Normal state -->
                                <span wire:loading.remove wire:target="uploads.{{ $onboardDoc->id }}">
                                    <i class="fas fa-upload mr-1.5 text-xs"></i>
                                    {{ isset($uploads[$onboardDoc->id]) ? 'Change' : 'Upload' }}
                                </span>

                                <!-- Loading state -->
                                <span wire:loading wire:target="uploads.{{ $onboardDoc->id }}">
                                    <svg class="animate-spin h-3 w-3 mr-1 inline" viewBox="0 0 24 24"></svg>
                                    Uploading...
                                </span>
                            </label>
                        </div>
                    </div>

                    @error("uploads.{$onboardDoc->id}") 
                        <div class="mt-2 p-2 bg-red-50 border border-red-200 rounded text-xs">
                            <span class="text-red-600 flex items-center">
                                <i class="fas fa-exclamation-triangle mr-1.5 text-xs"></i>
                                {{ $message }}
                            </span>
                        </div>
                    @enderror
                </div>
                @endforeach
            </div>

            <!-- Action Button -->
            <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                <div class="text-xs text-gray-500 flex items-center">
                    <i class="fas fa-lock mr-1.5 text-xs"></i>
                    Secure & encrypted
                </div>
                
                <button wire:click="submit"
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded transition-colors disabled:opacity-40 disabled:cursor-not-allowed flex items-center"
                        @disabled(count(array_filter($uploads)) < count($pendingDocs))>
                    <span>Submit Documents</span>
                    <i class="fas fa-arrow-right ml-2 text-xs"></i>
                </button>
            </div>
        </div>
    </div>
</div>

@elseif($pendingApproval->isNotEmpty())
<div class="mt-8">
    <div class="max-w-2xl w-full bg-white rounded-lg border border-gray-200">
        <!-- Header -->
        <div class="bg-gradient-to-r rounded-t-lg from-amber-500 to-orange-600 p-6 text-white">
            <div class="flex items-center justify-between">
                 <div class="bg-white/20 p-2 rounded-lg">
                    <img src="{{ asset('images/kimfay.png') }}" 
                         alt="Company Logo" 
                         class="w-30 h-8 object-contain"
                         onerror="this.style.display='none'">
                </div>
                <div>
                    <h1 class="text-xl font-semibold mb-1">Application Under Review</h1>
                    <p class="text-amber-100 text-sm">Your documents are being reviewed</p>
                </div>
               
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <!-- Status Card -->
            <div class="flex items-start p-4 bg-amber-50 rounded-lg border border-amber-200">
                <div class="bg-amber-100 p-2 rounded-lg mr-3">
                    <i class="fas fa-clock text-amber-600"></i>
                </div>
                <div class="flex-1">
                    <h2 class="font-semibold text-gray-800 mb-1">Pending HR Approval</h2>
                    <p class="text-gray-600 text-sm mb-2">Your documents are under review. This typically takes 1-3 business days.</p>
                    <div class="flex items-center text-xs text-amber-700">
                        <i class="fas fa-info-circle mr-1"></i>
                        <span>You'll be notified via email once approved</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@elseif($allApproved)
<!-- Pass the questions from Laravel to Alpine -->
<div x-data="lmsApp(
    {{ json_encode($questions) }}, 
    {{ json_encode((int)($totalQuestions ?? 0)) }}, 
    {{ json_encode((int)($answeredQuestions ?? 0)) }}, 
    {{ json_encode((int)($unansweredQuestions ?? 0)) }}, 
    {{ json_encode((int)($correctAnsweredCount ?? 0)) }}, 
    {{ json_encode((bool)($allQuestionsAnswered ?? false)) }}, 
    {{ json_encode((bool)($submittedScore ?? false)) }}
)" 
     x-init="init" 
     class="min-h-screen flex flex-col bg-gray-50">
    
    <!-- Main Content -->
    <main class="flex-1 container mx-auto px-4 mt-7 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Left Sidebar -->
            <div class="w-full lg:w-1/4">
                <!-- Company Info Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
                    <div class="text-center mb-6">
                        <div class="w-20 h-20 bg-gradient-to-br from-blue-600 to-blue-700 rounded-2xl mx-auto mb-4 flex items-center justify-center">
                            <span class="text-white text-2xl font-bold">KF</span>
                        </div>
                        <h2 class="font-bold text-xl text-gray-800">Kim-Fay EA LTD</h2>
                        <p class="text-gray-500 text-sm">E-Learning Center</p>
                    </div>
                    
                    <!-- Progress -->
                    <div class="mb-6">
                        <div class="flex justify-between text-sm mb-2">
                            <span class="font-medium text-gray-700">Course Progress</span>
                            <span class="text-blue-600 font-semibold" x-text="getProgressPercentage() + '%'"></span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2.5">
                            <div class="bg-gradient-to-r from-blue-600 to-blue-500 h-2.5 rounded-full transition-all duration-300" 
                                 :style="'width: ' + getProgressPercentage() + '%'"></div>
                        </div>
                    </div>
                    
                    <!-- Categories Navigation -->
                    <div class="mb-6">
                        <h3 class="font-semibold text-gray-700 mb-3 flex items-center">
                            <i class="fas fa-layer-group text-blue-500 mr-2 text-sm"></i>
                            Categories
                        </h3>
                        <ul class="space-y-1">
                            <template x-for="(category, key) in categories" :key="key">
                                <li>
                                    <button @click="setCategory(key)" 
                                            class="w-full text-left px-4 py-2.5 rounded-lg transition-all duration-200 flex items-center"
                                            :class="currentCategory === key ? 'bg-blue-50 text-blue-700 font-medium shadow-sm' : 'text-gray-600 hover:bg-gray-50'">
                                        <i class="fas fa-circle text-[8px] mr-3" 
                                           :class="currentCategory === key ? 'text-blue-500' : 'text-gray-300'"></i>
                                        <span x-text="category.title"></span>
                                    </button>
                                </li>
                            </template>
                        </ul>
                    </div>
                    
                    <!-- Info Box -->
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-100 rounded-xl p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-lightbulb text-blue-500 text-xl"></i>
                            </div>
                            <p class="text-sm text-gray-600 ml-3 leading-relaxed">
                                Watch the videos to gain a deeper understanding of the company, its operations, and its people.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Department List (Shows only when Departmental Induction is selected) -->
                <div x-show="currentCategory === 'departmental-induction'" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform -translate-y-2"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-semibold text-gray-700 mb-4 flex items-center">
                        <i class="fas fa-building text-blue-500 mr-2"></i>
                        Departments
                    </h3>
                    <ul class="space-y-2">
                        <template x-for="(videos, deptKey) in categories['departmental-induction'].departments" :key="deptKey">
                            <li>
                                <button @click="selectDepartment(deptKey)" 
                                        class="w-full text-left px-4 py-3 rounded-lg transition-all duration-200 flex items-center hover:bg-gray-50"
                                        :class="selectedDepartment === deptKey ? 'bg-blue-50 text-blue-700' : 'text-gray-600'">
                                    <i class="fas fa-building mr-3 text-sm" 
                                       :class="selectedDepartment === deptKey ? 'text-blue-500' : 'text-gray-400'"></i>
                                    <span x-text="deptKey.charAt(0).toUpperCase() + deptKey.slice(1) + ' Department'"></span>
                                </button>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="w-full lg:w-3/4">
                <!-- Video Player Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6"
                @contextmenu.prevent
                @keydown.down.prevent
                @keydown.s.down.prevent>
                    <!-- Category Title -->
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-gray-800 mb-1" x-text="getCurrentCategoryTitle()"></h2>
                        <p class="text-gray-500" x-text="getCurrentCategoryDescription()"></p>
                    </div>
                    
                    <!-- Video Player -->
                    <div class="mb-6">
                        <div class="relative  bg-black rounded-xl overflow-hidden shadow-lg">
                            <video controls controlslist="nodownload noremoteplayback"
                           disablepictureinpicture playsinline preload="metadata" 
                                   class="w-full aspect-video"
                                   x-ref="videoPlayer"
                                   @ended="handleVideoEnd">
                                <template x-if="currentVideo">
                                    <source :src="currentVideo.src" type="video/mp4">
                                </template>
                                <template x-if="!currentVideo && getCurrentVideos().length > 0">
                                    <source :src="getCurrentVideos()[0].src" type="video/mp4">
                                </template>
                                Your browser does not support the video tag.
                            </video>
                            
                            <!-- Fallback message when no videos available -->
                            <div x-show="getCurrentVideos().length === 0" 
                                 class="absolute inset-0 flex items-center justify-center bg-gray-900 bg-opacity-75">
                                <p class="text-white text-lg">No videos available for this section</p>
                            </div>
                        </div>

                        <!-- Video Navigation & Title -->
                        <div class="flex items-center justify-between mt-4">
                            <div class="flex space-x-2">
                                <button @click="previousVideo" 
                                        :disabled="!hasPreviousVideo()"
                                        class="p-2 rounded-lg transition-all duration-200"
                                        :class="hasPreviousVideo() ? 'hover:bg-gray-100 text-gray-700' : 'text-gray-300 cursor-not-allowed'">
                                    <i class="fas fa-step-backward"></i>
                                </button>
                                <button @click="nextVideo"
                                        :disabled="!hasNextVideo()"
                                        class="p-2 rounded-lg transition-all duration-200"
                                        :class="hasNextVideo() ? 'hover:bg-gray-100 text-gray-700' : 'text-gray-300 cursor-not-allowed'">
                                    <i class="fas fa-step-forward"></i>
                                </button>
                            </div>
                            <div class="flex items-center space-x-4">
                                <span class="text-sm font-medium text-gray-700" x-show="currentVideo" x-text="currentVideo.title"></span>
                                <span class="text-sm text-gray-400" x-show="!currentVideo && getCurrentVideos().length > 0">
                                    <span x-text="getCurrentVideos()[0].title"></span>
                                </span>
                                <span class="text-sm text-gray-400" x-show="!currentVideo && getCurrentVideos().length === 0">
                                    No videos available
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Video Description -->
                    <div x-show="currentVideo || getCurrentVideos().length > 0" x-transition>
                        <h3 class="font-semibold text-gray-700 mb-2">About this video</h3>
                        <p class="text-gray-600 leading-relaxed" 
                           x-text="(currentVideo || getCurrentVideos()[0])?.description || 'No description available.'">
                        </p>
                    </div>

                    <!-- Video List for Current Category -->
                    <div x-show="getCurrentVideos().length > 0" class="mt-8">
                        <h3 class="font-semibold text-gray-700 mb-4">Available Videos</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <template x-for="video in getCurrentVideos()" :key="video.id">
                                <button @click="setVideo(video)"
                                        class="text-left p-4 rounded-xl border-2 transition-all duration-200"
                                        :class="isCurrentVideo(video) ? 'border-blue-500 bg-blue-50' : 'border-gray-100 hover:border-blue-200 hover:bg-gray-50'">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-play-circle text-2xl" 
                                               :class="isCurrentVideo(video) ? 'text-blue-500' : 'text-gray-300'"></i>
                                        </div>
                                        <div class="ml-3">
                                            <h4 class="font-medium text-gray-800" x-text="video.title"></h4>
                                            <p class="text-sm text-gray-500 mt-1 line-clamp-2" x-text="video.description || 'Click to watch'"></p>
                                        </div>
                                    </div>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Quiz Section -->

               <!-- <pre>{{ json_encode($questions, JSON_PRETTY_PRINT) }}</pre> -->

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Check Your Understanding</h2>
                    <template x-if="!quizCompleted">
                        <div>
                            <!-- Question Progress -->
                            <div class="mb-6">
                                <div class="flex justify-between text-sm mb-2">
                                    <span class="font-medium text-gray-700">Question <span x-text="answeredQuestions"></span> of <span x-text="totalQuestions"></span></span>
                                    <span class="text-blue-600 font-medium" x-text="getProgressPercentage() + '%'"></span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-blue-600 to-blue-500 h-2 rounded-full transition-all duration-300" 
                                         :style="'width: ' + getProgressPercentage() + '%'"></div>
                                </div>
                            </div>

                            <!-- Question -->
                            <div class="mb-8">
                                <h3 class="text-lg text-gray-800 mb-4" x-text="currentQuestion.question"></h3>
                                
                                <!-- Options -->
                                <div class="space-y-3">
                                    <template x-for="option in currentQuestion.options" :key="option.id">
                                        <label class="flex items-center p-4 rounded-xl border-2 transition-all duration-200 cursor-pointer"
                                            :class="selectedAnswers[currentQuestionIndex] === option.id 
                                                        ? 'border-blue-500 bg-blue-50' 
                                                        : 'border-gray-200 hover:border-blue-200'">

                                            <input type="radio"
                                                :name="'q' + currentQuestion.id"
                                                :value="option.id"
                                                @change="selectAnswer(option.id)"
                                                class="hidden">

                                            <div class="flex items-center w-full">
                                                
                                                <!-- Custom Radio Circle -->
                                                <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center mr-3"
                                                    :class="selectedAnswers[currentQuestionIndex] === option.id 
                                                            ? 'border-blue-500 bg-blue-500' 
                                                            : 'border-gray-300'">

                                                    <div class="w-2 h-2 rounded-full bg-white"
                                                        x-show="selectedAnswers[currentQuestionIndex] === option.id">
                                                    </div>
                                                </div>

                                                <!-- Option Text -->
                                                <span class="text-gray-700">
                                                    <span class="font-semibold text-blue-600 mr-2"
                                                        x-text="option.letter + ')'"></span>
                                                    <span x-text="option.text"></span>
                                                </span>

                                            </div>
                                        </label>
                                    </template>
                                </div>
                            </div>
 
                            <!-- Navigation -->
                            <div class="flex justify-between">
                      
                                <button @click="nextQuestion" 
                                        :disabled="!canProgress()"
                                        class="px-6 py-2.5 rounded-lg font-medium text-white transition-all duration-200"
                                        :class="canProgress() ? 'bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600' : 'bg-blue-300 cursor-not-allowed'">
                                    <span x-show="currentQuestionIndex < questions.length - 1">Next Question</span>
                                    <span x-show="currentQuestionIndex === questions.length - 1">Submit Answer</span>
                                    <i class="fas fa-arrow-right ml-2"></i>
                                </button>
                            </div>
                        </div>
                    </template>

 
                    <!-- Results -->
                    <template x-if="quizCompleted">
                        <div class="text-center py-12">
                            <!-- If score is already submitted (waiting for approval) -->
                            <template x-if="submittedScore === true">
                                <div>
                                    <div class="w-24 h-24 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                                        <i class="fas fa-clock text-white text-3xl"></i>
                                    </div>
                                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Quiz Submitted!</h3>
                                    <p class="text-gray-600 mb-4">
                                        You scored <span class="text-2xl font-bold text-yellow-500 mx-1" x-text="calculateScore()"></span>
                                    </p>
                                    
                                    <!-- Clear information about HR approval -->
                                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 text-left max-w-md mx-auto">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-info-circle text-blue-500"></i>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm text-blue-700">
                                                    Your quiz has been submitted and is pending HR approval. 
                                                    Once approved, you will be able to proceed to the next step.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <button disabled 
                                            class="bg-gray-400 cursor-not-allowed text-white px-8 py-3 rounded-xl font-medium transition-all duration-200 shadow-md">
                                        <i class="fas fa-hourglass-half mr-2"></i>
                                        Awaiting HR Approval
                                    </button>
                                </div>
                            </template>

                            <!-- If score is NOT submitted yet (fresh completion) -->
                            <template x-if="submittedScore !== true">
                                <div>
                                    <div class="w-24 h-24 bg-gradient-to-br from-green-400 to-green-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                                        <i class="fas fa-check text-white text-3xl"></i>
                                    </div>
                                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Quiz Completed!</h3>
                                    <p class="text-gray-600 mb-6">
                                        You scored <span class="text-2xl font-bold text-green-500 mx-1" x-text="calculateScore()"></span> 
                                    </p>
                                    <p class="text-lg mb-4" x-text="'Percentage: ' + calculateScorePercentage() + '%'"></p>
                                    <button @click="completeAssessment" 
                                            class="bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white px-8 py-3 rounded-xl font-medium transition-all duration-200 shadow-md hover:shadow-lg">
                                        <i class="fas fa-redo-alt mr-2"></i>
                                        Complete Assessment
                                    </button>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-12 py-8">
        <div class="container mx-auto px-4">
            <div class="text-center text-gray-500 text-sm">
                <p>© 2025 Kim-Fay EA LTD. All rights reserved.</p>
            </div>
        </div>
    </footer>
</div>



<script>
function lmsApp(questionsFromDb = [], totalQuestionsFromDb, answeredQuestionsFromDb, unansweredQuestionsFromDb, correctAnsweredCountFromDb, allQuestionsAnsweredFromDb, submittedScoreFromDb) {
    return {
        // State
        currentCategory: 'who-we-are',
        currentVideo: null,
        selectedDepartment: 'ict',
        selectedAnswers: {},
        currentQuestionIndex: 0,
        quizCompleted: allQuestionsAnsweredFromDb,
        
        // Questions from database (passed from Laravel)
        questions: questionsFromDb,
        totalQuestions: totalQuestionsFromDb,
        answeredQuestions: answeredQuestionsFromDb,
        unansweredQuestions: unansweredQuestionsFromDb,
        correctAnsweredCount: correctAnsweredCountFromDb,
        allQuestionsAnswered: allQuestionsAnsweredFromDb,
        submittedScore: submittedScoreFromDb,
        
        
        // Categories Data
        categories: {
            'who-we-are': {
                title: 'Who We Are',
                description: 'Get to know our company, mission, and values that drive everything we do.',
                videos: [
                    {
                        id: 1,
                        title: 'Company Introduction',
                        src: '/storage/videos/who-we-are-video.mp4',
                        description: 'An introduction to our company culture and values.'
                    }
                ]
            },
            'our-products': {
                title: 'Our Products',
                description: 'Explore our product range and manufacturing excellence.',
                videos: [
                    {
                        id: 2,
                        title: 'Product Overview',
                        src: '/storage/videos/reception-video.mp4',
                        description: 'Overview of our main products and their features.'
                    }
                ]
            },
            'departmental-induction': {
                title: 'Departmental Induction',
                description: 'Learn about different departments and their roles.',
                departments: {
                    ict: [
                        {
                            id: 3,
                            title: 'ICT Department Overview',
                            src: '/storage/videos/ict.mp4',
                            description: 'Learn about our ICT infrastructure and systems.'
                        },
                        {
                            id: 4,
                            title: 'ICT Security Guidelines',
                            src: '/storage/videos/ict-security.mp4',
                            description: 'Important security protocols for ICT.'
                        }
                    ],
                    marketing: [
                        {
                            id: 5,
                            title: 'Marketing Department Overview',
                            src: '/storage/videos/marketing.mp4',
                            description: 'How our marketing team drives brand awareness.'
                        }
                    ],
                    procurement: [
                        {
                            id: 6,
                            title: 'Procurement Process',
                            src: '/storage/videos/procurement.mp4',
                            description: 'Understanding our procurement workflow.'
                        }
                    ],
                    finance: [
                        {
                            id: 7,
                            title: 'Finance Department Overview',
                            src: '/storage/videos/finance.mp4',
                            description: 'Financial operations and reporting.'
                        }
                    ],
                    humanResources: [
                        {
                            id: 8,
                            title: 'HR Policies and Procedures',
                            src: '/storage/videos/hr.mp4',
                            description: 'Learn about HR policies and employee guidelines.'
                        }
                    ],
                    fleet: [
                        {
                            id: 9,
                            title: 'Fleet Management',
                            src: '/storage/videos/fleet.mp4',
                            description: 'Fleet operations and maintenance procedures.'
                        }
                    ]
                }
            },
            'knowledge-base': {
                title: 'Knowledge Base',
                description: 'Additional resources and learning materials.',
                videos: [
                    {
                        id: 10,
                        title: 'Company Policies',
                        src: '/storage/videos/policies.mp4',
                        description: 'Important company policies and procedures.'
                    }
                ]
            }
        },

        // Initialize
        init() {
            // Auto-select first video when component loads
            this.$nextTick(() => {
                const videos = this.getCurrentVideos();
                if (videos.length > 0) {
                    this.currentVideo = videos[0];
                }
            });
        },

    
        // Computed Properties
        get currentQuestion() {
            return this.questions[this.currentQuestionIndex] || { question: '', options: [] };
        },

        // Methods
        setCategory(category) {
            this.currentCategory = category;
            this.currentVideo = null;
            this.selectedDepartment = 'ict';
            
            // Auto-select first video of the new category
            this.$nextTick(() => {
                const videos = this.getCurrentVideos();
                if (videos.length > 0) {
                    this.currentVideo = videos[0];
                    // Force video element to reload
                    this.$refs.videoPlayer?.load();
                }
            });
        },

        selectDepartment(dept) {
            this.selectedDepartment = dept;
            const deptVideos = this.categories['departmental-induction'].departments[dept];
            if (deptVideos && deptVideos.length > 0) {
                this.currentVideo = deptVideos[0];
                // Force video element to reload
                this.$nextTick(() => {
                    this.$refs.videoPlayer?.load();
                });
            }
        },

        setVideo(video) {
            this.currentVideo = video;
            // Force video element to reload with new source
            this.$nextTick(() => {
                this.$refs.videoPlayer?.load();
            });
        },

        isCurrentVideo(video) {
            return this.currentVideo?.id === video.id;
        },

        getCurrentCategoryTitle() {
            return this.categories[this.currentCategory]?.title || '';
        },

        getCurrentCategoryDescription() {
            return this.categories[this.currentCategory]?.description || '';
        },

        getCurrentVideos() {
            if (this.currentCategory === 'departmental-induction') {
                return this.categories['departmental-induction'].departments[this.selectedDepartment] || [];
            }
            return this.categories[this.currentCategory]?.videos || [];
        },

        hasPreviousVideo() {
            const videos = this.getCurrentVideos();
            if (!this.currentVideo || videos.length === 0) return false;
            const currentIndex = videos.findIndex(v => v.id === this.currentVideo.id);
            return currentIndex > 0;
        },

        hasNextVideo() {
            const videos = this.getCurrentVideos();
            if (!this.currentVideo || videos.length === 0) return false;
            const currentIndex = videos.findIndex(v => v.id === this.currentVideo.id);
            return currentIndex < videos.length - 1 && currentIndex !== -1;
        },

        previousVideo() {
            const videos = this.getCurrentVideos();
            if (!this.currentVideo || videos.length === 0) return;
            const currentIndex = videos.findIndex(v => v.id === this.currentVideo.id);
            if (currentIndex > 0) {
                this.currentVideo = videos[currentIndex - 1];
                this.$nextTick(() => {
                    this.$refs.videoPlayer?.load();
                });
            }
        },

        nextVideo() {
            const videos = this.getCurrentVideos();
            if (!this.currentVideo || videos.length === 0) return;
            const currentIndex = videos.findIndex(v => v.id === this.currentVideo.id);
            if (currentIndex < videos.length - 1) {
                this.currentVideo = videos[currentIndex + 1];
                this.$nextTick(() => {
                    this.$refs.videoPlayer?.load();
                });
            }
        },

        handleVideoEnd() {
            console.log('Video ended');
            // Auto-play next video if available
            if (this.hasNextVideo()) {
                this.nextVideo();
            }
        },

        // Progress
        getProgressPercentage() {

            const totalVideos = this.totalQuestions;
            const watched = this.answeredQuestions; 
            return Math.round((watched / totalVideos) * 100);
        },

        // Quiz Methods
       selectAnswer(optionId) {
            this.selectedAnswers[this.currentQuestionIndex] = optionId;
        },

    

        canProgress() {
            return this.selectedAnswers[this.currentQuestionIndex] !== undefined;
        },

       
        async nextQuestion() {

            if (!this.canProgress()) return;

            // Save answer to database
            await this.$wire.saveAnswer(
                this.currentQuestion.id,
                this.selectedAnswers[this.currentQuestionIndex]
            );

            // Increase answered questions
            this.answeredQuestions++;

            // If this was the last question → complete quiz
            if (this.answeredQuestions >= this.totalQuestions) {
                this.quizCompleted = true;
                return;
            }

            // Otherwise move to next question
            if (this.currentQuestionIndex < this.questions.length - 1) {
                this.currentQuestionIndex++;
            }

        },

        prevQuestion() {
            if (this.currentQuestionIndex > 0) {
                this.currentQuestionIndex--;
            }
        },

          calculateScore() {
            return this.correctAnsweredCount + ' / ' + this.totalQuestions;
        },

     
        async completeAssessment() {
            try {

                // Call the Livewire method
                const response = await this.$wire.completeAssessment();
                
                // Only update local state if the Livewire method was successful
                if (response && response.success) {
                    this.submittedScore = true;
                    this.quizCompleted = true;
                }
                
            } catch (error) {
                console.error('Error completing assessment:', error);
            }
        }
    }
}
</script>
@endif

</div>