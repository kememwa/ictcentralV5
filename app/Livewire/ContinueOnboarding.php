<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use App\Models\Onboarding;
use App\Models\OnboardingStep;


class ContinueOnboarding extends Component
{
    public $search = '';

    public function continueOnboarding($onboardingUuid)
    {

        $onboarding = Onboarding::where('uuid', $onboardingUuid)->firstOrFail();

        $pendingSteps = OnboardingStep::where('onboarding_id', $onboarding->id)
            ->where('status', 'in_progress')
            ->first();

        return $this->redirect(
            route('onboard-new-user', ['onboardingId' => $onboarding->uuid]),
            navigate: true
        );
    }


    #[layout('layouts.dashboard')]
    public function render()
    {
       $staff = Onboarding::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('email', 'like', "%{$this->search}%")
                    ->orWhere('status', 'like', "%{$this->search}%");
                });
            })
            ->latest()
            ->paginate(5);

        return view('livewire.continue-onboarding', [
            'staff' => $staff
        ]);

    }
}
