<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Video;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\Question;
use App\Models\Option;

class VideoManagement extends Component
{

    // Modal state
    public $isOpen = false;
    public $mode = 'create'; // create or edit
    
    // Form fields
    public $title = '';
    public $description = '';
    public $category = '';
    public $department = '';
    public $video_source = 'upload';
    public $video_file = null;
    public $youtube_url = '';
    public $thumbnail = null;
    // Add this with your other properties
    public $uploadedPath = null;
    
    // Video ID for editing
    public $videoId = null;
    
    // Loading state
    public $loading = false;

// start of lms management code

public $showQuestionBank = false;
public $selectedVideo = null;
public $videos = [];
public $questions = [];

// Form fields
public $new_question_text = '';
public $new_question_option_a = '';
public $new_question_option_b = '';
public $new_question_option_c = '';
public $new_question_option_d = '';
public $new_question_correct_answer = null;

// This method is called when showQuestionBank changes
public function updatedShowQuestionBank($value)
{
    if ($value) {
        $this->loadQuestionBank();
    }
}

public function loadQuestionBank()
{
    // Load videos from database - category is a string field, not a relationship
    $this->videos = Video::select('id', 'title', 'category')
        ->get()
        ->map(function($video) {
            return [
                'id' => $video->id,
                'title' => $video->title,
                'category' => $video->category // This is a string like 'who-we-are', 'our-products', etc.
            ];
        })
        ->toArray();
}

public function updatedSelectedVideo($value)
{
    if ($value) {
        $this->loadQuestions();
    }
}

public function loadQuestions()
{
    $this->questions = Question::where('video_id', $this->selectedVideo)
        ->with('options') // Eager load options
        ->get()
        ->map(function($question) {
            // Get options and organize them by option letter
            $options = $question->options->keyBy('option_letter');
            
            return [
                'id' => $question->id,
                'text' => $question->question, // Map 'question' to 'text'
                'option_a' => $options['A']->option_text ?? '',
                'option_b' => $options['B']->option_text ?? '',
                'option_c' => $options['C']->option_text ?? '',
                'option_d' => $options['D']->option_text ?? '',
                'correct_answer' => $this->getCorrectAnswer($options) // You'll need to determine this
            ];
        })
        ->toArray();
    
}

private function getCorrectAnswer($options)
{
    foreach ($options as $letter => $option) {
        if ($option->is_correct ?? false) {
            return $letter;
        }
    }

    return null; // or return a default value if needed
    
}

public function resetQuestionForm()
{
    $this->new_question_text = '';
    $this->new_question_option_a = '';
    $this->new_question_option_b = '';
    $this->new_question_option_c = '';
    $this->new_question_option_d = '';
    $this->new_question_correct_answer = null;


    $this->resetvalidation([
        'new_question_text', 
        'new_question_option_a', 
        'new_question_option_b', 
        'new_question_option_c', 
        'new_question_option_d', 
        'new_question_correct_answer'
    ]);
}   

public function addQuestion()
{

    $this->validate([
        'new_question_text' => 'required',
        'new_question_option_a' => 'required',
        'new_question_option_b' => 'required',
        'new_question_option_c' => 'required',
        'new_question_option_d' => 'required',
        'new_question_correct_answer' => 'required|in:A,B,C,D'
    ]);

    //create question first to get the question ID for options
    $question = Question::create([
        'video_id' => $this->selectedVideo,
        'question' => $this->new_question_text,
    ]);

    // get the question ID and create options
    $questionId = $question->id;
    // use the question ID to create options
 
        // 3. Create options array with all four options
    $options = [
        [
            'option_letter' => 'A',
            'question_id' => $questionId,
            'option_text' => $this->new_question_option_a,
        ],
        [
            'option_letter' => 'B',
            'question_id' => $questionId,
            'option_text' => $this->new_question_option_b,
            
        ],
        [
            'option_letter' => 'C',
            'question_id' => $questionId,
            'option_text' => $this->new_question_option_c,
            
        ],
        [
            'option_letter' => 'D',
            'question_id' => $questionId,
            'option_text' => $this->new_question_option_d,
            
        ]
    ];

    // 4. Loop through and create each option
    foreach ($options as $optionData) {
        Option::create($optionData);
    }

    // 5. Update the correct answer
    Option::where('question_id', $questionId)
        ->where('option_letter', $this->new_question_correct_answer)
        ->update(['is_correct' => true]);

    // Reset form
    $this->resetQuestionForm();
    // Reload questions
    $this->loadQuestions();
    
    $this->dispatch('notify', 
        type: 'success',
        title: 'Question Added',
        message: 'Question added successfully.'
    );
}

public function deleteQuestion($questionId)
{
    Question::find($questionId)->delete();
    $this->loadQuestions();
    
    $this->dispatch('notify', 
        type: 'success',
        title: 'Question Deleted',
        message: 'Question deleted successfully.'
    );
}

//end of lms management code


    // Validation rules
    protected function rules()
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:who-we-are,our-products,departmental-induction,knowledgebase',
            'department' => 'required_if:category,departmental-induction|nullable|in:ict,marketing,procurement,hr,fleet',
            'video_source' => 'required|in:upload,youtube',
        ];
        
       if ($this->video_source === 'upload') {
            $rules['uploadedPath'] = 'required|string';
            $rules['thumbnail'] = 'nullable|image|max:10240';
        } else {
            $rules['youtube_url'] = 'required|url|regex:/^(https?\:\/\/)?(www\.)?(youtube\.com|youtu\.?be)\/.+$/';
        }
        
        return $rules;
    }
    
    // Custom validation messages
    protected $messages = [
        'youtube_url.regex' => 'Please enter a valid YouTube URL.',
        'department.required_if' => 'Please select a department for departmental induction.',
    ];
    
    // Reset form to default values
    public function resetForm()
    {
        $this->reset([
            'title', 'description', 'category', 'department', 
            'video_source', 'video_file', 'youtube_url', 'thumbnail',
            'videoId', 'mode'
        ]);
        $this->resetValidation();
    }
    
    // Open modal for creating new video
    public function create()
    {
        $this->resetForm();
        $this->mode = 'create';
        $this->isOpen = true;
    }
    
    // Open modal for editing existing video
    public function edit($id)
    {
        $video = Video::findOrFail($id);
        
        $this->videoId = $video->id;
        $this->title = $video->title;
        $this->description = $video->description;
        $this->category = $video->category;
        $this->department = $video->department;
        $this->video_source = $video->video_type;
        
        if ($video->video_type === 'youtube') {
            $this->youtube_url = $video->youtube_url ?? '';
        }
        
        $this->mode = 'edit';
        $this->isOpen = true;
    }
    
    // Close modal
    public function closeModal()
    {
        $this->isOpen = false;
        $this->uploadedPath = null;
        $this->resetForm();
    }

    // Listen for video upload event from the upload component

    #[On('videoUploaded')]
    public function handleVideoUploaded($data)
    {
        if (is_array($data) && isset($data['path'])) {
            $this->uploadedPath = $data['path'];
        } else {
            $this->uploadedPath = $data;
        }
    }
    
    // Save the video
    public function save()
    {
        $this->loading = true;
        
        // Validate
        $validatedData = $this->validate();
        
        try {
            if ($this->video_source === 'upload') {
                $this->saveUploadedVideo();
            } else {
                $this->saveYoutubeVideo();
            }
            
            $this->loading = false;
            $this->closeModal();
            
            // Show success message
            session()->flash('message', 'Video ' . ($this->mode === 'create' ? 'uploaded' : 'updated') . ' successfully!');
            
            // Emit event to refresh video list
            $this->emit('videoSaved');
            
        } catch (\Exception $e) {
            $this->loading = false;
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }
    
    // Save uploaded video
   protected function saveUploadedVideo()
    {
        // Ensure we have uploaded path from uploader
        if (!$this->uploadedPath) {
            throw new \Exception('Video upload not completed.');
        }

        // Store thumbnail if provided (this can stay as Livewire handles images fine)
        $thumbnailPath = null;
        if ($this->thumbnail) {
            $thumbnailPath = $this->thumbnail->store('thumbnails', 'public');
        }

        $data = [
            'title' => $this->title,
            'slug' => Str::slug($this->title),
            'description' => $this->description,
            'category' => $this->category,
            'department' => $this->category === 'departmental-induction' ? $this->department : null,
            'video_type' => 'upload',
            'video_path' => $this->uploadedPath,  // <-- use JS path
            'thumbnail_path' => $thumbnailPath,
            'status' => 'processing',
            'user_id' => auth()->id(),
        ];

        if ($this->mode === 'edit' && $this->videoId) {
            $video = Video::findOrFail($this->videoId);

            // Delete old video if replaced
            if ($this->uploadedPath && $video->video_path !== $this->uploadedPath) {
                \Storage::disk('public')->delete($video->video_path);
            }

            if ($this->thumbnail && $video->thumbnail_path) {
                \Storage::disk('public')->delete($video->thumbnail_path);
            }

            $video->update($data);
        } else {
            Video::create($data);
        }
    }
    
    // Save YouTube video
    protected function saveYoutubeVideo()
    {
        // Extract YouTube video ID
        $videoId = $this->extractYoutubeId($this->youtube_url);
        
        if (!$videoId) {
            throw new \Exception('Invalid YouTube URL');
        }
        
        $data = [
            'title' => $this->title,
            'slug' => Str::slug($this->title),
            'description' => $this->description,
            'category' => $this->category,
            'department' => $this->category === 'departmental-induction' ? $this->department : null,
            'video_type' => 'youtube',
            'youtube_id' => $videoId,
            'youtube_url' => $this->youtube_url,
            'thumbnail_url' => "https://img.youtube.com/vi/{$videoId}/hqdefault.jpg",
            'status' => 'ready',
            'user_id' => auth()->id(),
        ];
        
        // Optionally fetch additional info from YouTube API
        $youtubeInfo = $this->fetchYoutubeInfo($videoId);
        if ($youtubeInfo) {
            $data['title'] = $youtubeInfo['title'] ?? $this->title;
            $data['description'] = $youtubeInfo['description'] ?? $this->description;
            $data['duration'] = $youtubeInfo['duration'] ?? null;
        }
        
        if ($this->mode === 'edit' && $this->videoId) {
            // Update existing video
            Video::find($this->videoId)->update($data);
        } else {
            // Create new video
            Video::create($data);
        }
    }
    
    // Extract YouTube ID from URL
    protected function extractYoutubeId($url)
    {
        preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([^&\n?#]+)/', $url, $matches);
        return $matches[1] ?? null;
    }
    
    // Fetch YouTube video info (optional - requires API key)
    protected function fetchYoutubeInfo($videoId)
    {
        // You'll need to set YOUTUBE_API_KEY in your .env file
        $apiKey = env('YOUTUBE_API_KEY');
        
        if (!$apiKey) {
            return null;
        }
        
        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->get("https://www.googleapis.com/youtube/v3/videos", [
                'query' => [
                    'part' => 'snippet,contentDetails',
                    'id' => $videoId,
                    'key' => $apiKey
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            
            if (!empty($data['items'][0])) {
                $item = $data['items'][0];
                return [
                    'title' => $item['snippet']['title'],
                    'description' => $item['snippet']['description'],
                    'duration' => $this->convertYoutubeDuration($item['contentDetails']['duration']),
                ];
            }
        } catch (\Exception $e) {
            // Log error but don't fail the request
            \Log::error('YouTube API error: ' . $e->getMessage());
        }
        
        return null;
    }
    
    // Convert YouTube duration format (PT1H2M3S) to seconds
    protected function convertYoutubeDuration($duration)
    {
        $interval = new \DateInterval($duration);
        return ($interval->h * 3600) + ($interval->i * 60) + $interval->s;
    }

    #[layout('layouts.dashboard')]
    public function render()
    {
        return view('livewire.video-management');
    }
}
