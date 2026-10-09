<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\LoginActivity;
use Livewire\WithPagination;

class LoginActivities extends Component
{
    use WithPagination;

    public $search = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function getLoginActivitiesProperty()
    {
        return LoginActivity::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('email', 'like', '%' . $this->search . '%')
                      ->orWhere('guard', 'like', '%' . $this->search . '%')
                      ->orWhere('ip_address', 'like', '%' . $this->search . '%')
                      ->orWhere('status', 'like', '%' . $this->search . '%');
                });
            })
            ->orderByDesc('logged_in_at')
            ->paginate(15);
    }

    #[Layout('layouts.dashboard')]
    public function render()
    {
        return view('livewire.login-activities');
    }
}
