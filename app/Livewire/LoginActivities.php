<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\LoginActivity;

class LoginActivities extends Component
{
    public function getLoginActivitiesProperty()
    {
        return LoginActivity::query()
            ->orderByDesc('logged_in_at')
            ->paginate(15);
    }

    #[Layout('layouts.dashboard')]
    public function render()
    {
        return view('livewire.login-activities');
    }
}
