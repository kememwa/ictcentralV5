<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use App\Models\OnboardingDocuments;
use App\Models\Question;
use App\Models\UserAnswer;

class GettingStarted extends Component
{

    use WithFileUploads;

    public $uploads = []; // temp file storage
    

    public function submit()
    {
        foreach ($this->uploads as $docId => $file) {

            if ($file) {
                $doc = OnboardingDocuments::find($docId);
                $path = $file->store('documents', 'root_public_storage');

                $doc->update([
                    'document_path' => $path,
                    'submitted' => true, // you can keep hr_approved = false until HR checks
                    'submitted_at' => now(),
                    'submitted_by'=> auth('onboarding')->id(),
                ]);
            }
        }

        $this->dispatch('notify', 
            type: 'success',
            title: 'Documents Submitted',
            message: "Documents submitted successfully."
        );
    }
    



    public function saveAnswer($questionId, $answerId)
    {

        $authUserId = auth('onboarding')->id();
        $correctAnswerId = Question::find($questionId)->options()->where('is_correct', true)->value('id');
            // Check if the answer is correct
        $isCorrect = $answerId == $correctAnswerId;
        
        
        //update the records for the users response

       UserAnswer::where('user_id', $authUserId)
        ->where('question_id', $questionId)
        ->update([
            'selected_option_id' => $answerId,
            'is_answered' => true,
            'is_correct' => $isCorrect,
            'answered_at' => now(),
        ]);
        
    }

    public function completeAssessment()
    {
        // Get authenticated onboarding user
        $onboarding = auth('onboarding')->user();
        
        // Check if user is authenticated
        if (!$onboarding) {
            $this->dispatch('notify', 
                type: 'error',
                title: 'Authentication Error',
                message: "User not found. Please login again."
            );
            return;
        }
        
        // Get correct answers count
        $correctAnswersCount = UserAnswer::where('user_id', $onboarding->id)
            ->where('is_correct', true)
            ->count();
        
        // Get total answered questions
        $totalQuestionsCount = UserAnswer::where('user_id', $onboarding->id)
            ->count();
        
        // Check if any questions were answered
        if ($totalQuestionsCount === 0) {
            $this->dispatch('notify', 
                type: 'error',
                title: 'No Answers Found',
                message: "Please answer the quiz questions before submitting."
            );
            return;
        }
        
        // Calculate percentage score
        $score = $totalQuestionsCount > 0 
            ? round(($correctAnswersCount / $totalQuestionsCount) * 100) 
            : 0;
        
        // Update onboarding record
        $onboarding->update([
            'submit_score' => true,
            'score' => $score,
        ]);


       $this->dispatch('refresh-page');
        
        $this->dispatch('notify', 
            type: 'success',
            title: 'Assessment Completed',
            message: "Your assessment has been completed and your score of {$score}% has been submitted for HR approval."
        );

        return [
            'success' => true,
            'submitted_score' => true,
        ];
    }

    #[Layout('layouts.guest')]
    public function render()
    {

    // Fetch unanswered questions for the user
   $unAnsweredQuestions = UserAnswer::where('user_id', auth('onboarding')->id())
        ->where('is_answered', false)
        ->with('question.options')  // This loads question and its options
        ->get();
    
    $formattedTotalQuestions = UserAnswer::where('user_id', auth('onboarding')->id())->count();
    $answeredQuestions = UserAnswer::where('user_id', auth('onboarding')->id())->where('is_answered', true)->count();
    $unansweredQuestions = UserAnswer::where('user_id', auth('onboarding')->id())->where('is_answered', false)->count();
    $correctAnsweredCount = UserAnswer::where('user_id', auth('onboarding')->id())->where('is_answered', true)->where('is_correct', true)->count();
    $allQuestionsAnswered = UserAnswer::where('user_id', auth('onboarding')->id())->where('is_answered', false)->count() === 0;
        // Transform questions to the format needed
        $formattedQuestions = $unAnsweredQuestions->map(function($userAnswer) {

        $question = $userAnswer->question; // Get the related question model

            return [
                'id' => $question->id,
                'question' => $question->question,
                'options' => $question->options->map(function ($option) {
                        return [
                            'id' => $option->id,
                            'letter' => $option->option_letter,
                            'text' => $option->option_text,
                            'is_correct' => $option->is_correct
                        ];
                    })->values()->toArray(),

                 'correctAnswerId' => optional(
                        $question->options->firstWhere('is_correct', true)
                )->id, // or however you store it
            ];
        });

        $onboarding = auth('onboarding')->user();
        $submittedScore = (bool) ($onboarding->submit_score ?? false);

        $documents = $onboarding?->documents()->get() ?? collect();

        // Separate them into buckets
        $pendingDocs = $documents->where('submitted', false);
        $pendingApproval = $documents->where('submitted', true)->where('hr_approved', false);
        $allApproved = $documents->isNotEmpty() && $documents->every(fn($doc) => $doc->submitted && $doc->hr_approved);


        return view('livewire.getting-started', [
            'pendingDocs' => $pendingDocs,
            'pendingApproval' => $pendingApproval,
            'allApproved' => $allApproved,
            'questions' => $formattedQuestions,
            'totalQuestions' => $formattedTotalQuestions,
            'answeredQuestions' => $answeredQuestions,
            'unansweredQuestions' => $unansweredQuestions,
            'correctAnsweredCount' => $correctAnsweredCount,
            'allQuestionsAnswered' => $allQuestionsAnswered,
            'submittedScore' => $submittedScore,
        ]);
    }
}
