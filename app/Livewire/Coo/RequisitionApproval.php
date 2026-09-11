<?php

namespace App\Livewire\Coo;

use Livewire\Component;
use Livewire\Attributes\Layout;

class RequisitionApproval extends Component
{

    #[Layout('layouts.dashboard')]
    public function render()
    {
        return view('livewire.coo.requisition-approval');
    }
}
