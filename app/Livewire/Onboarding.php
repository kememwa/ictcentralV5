<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Password;

#[Layout('layouts.dashboard')]
class Onboarding extends Component
{
    public function render()
    {
        $pendingOnboardings = \App\Models\Onboarding::withCount([
            'userAnswers as total_questions',
            'userAnswers as answered_questions' => function ($query) {
                $query->where('is_answered', true);
            },
        ])
        ->where('completed', false)
        ->paginate(10);
        
        return view('livewire.onboarding', [
            'pendingOnboardings' => $pendingOnboardings
        ]);
    }
    
    public function resendPasswordLink($id)
    {
        $onboarding = \App\Models\Onboarding::findOrFail($id);

        // Attempt to send password reset link
        $status = Password::broker('onboarding')->sendResetLink([
            'email' => $onboarding->email,
        ]);

        if ($status === Password::RESET_LINK_SENT) {
            $this->dispatch('notify',
                type: 'success',
                title: 'Notification Sent',
                message: 'Password reset link sent successfully',
            );
        } elseif ($status === Password::RESET_THROTTLED) {
            // Throttled: token is still valid
            $this->dispatch('notify',
                type: 'info',
                title: 'Link Already Sent',
                message: 'A password reset link has already been sent and is still valid.',
            );
        } else {
            $this->dispatch('notify',
                type: 'error',
                title: 'Failed',
                message: 'Could not send the password reset link. Please try again later.',
            );
        }
    }
    
    public function markHrFinished($onboardingId)
    {
        $onboarding = \App\Models\Onboarding::find($onboardingId);  // Added backslash
        if ($onboarding) {
            $onboarding->hr_finished = true;
            $onboarding->save();
            session()->flash('message', 'HR onboarding marked as finished!');
        }
    }
    
    public function markItFinished($onboardingId)
    {
        $onboarding = \App\Models\Onboarding::find($onboardingId);  // Added backslash
        if ($onboarding) {
            $onboarding->it_finished = true;
            $onboarding->save();
            session()->flash('message', 'IT onboarding marked as finished!');
        }
    }
}