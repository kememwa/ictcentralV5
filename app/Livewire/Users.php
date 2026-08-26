<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Device;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;


class Users extends Component
{



    use WithPagination;
    
    protected $listeners = ['userUpdatedOrAdded' => '$refresh'];
   
    public string $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
    

        return view('livewire.users', [
            'users' => User::where('name', 'like', '%' . $this->search . '%')
                               ->orWhere('email', 'like', '%' . $this->search . '%')
                               ->latest()
                               ->paginate(4),
                               
        ]);
    }
}