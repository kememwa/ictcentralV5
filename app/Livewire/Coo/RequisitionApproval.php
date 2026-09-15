<?php

namespace App\Livewire\Coo;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Requisition;

class RequisitionApproval extends Component
{

    #[Layout('layouts.dashboard')]
    public function render()
    {
        return view('livewire.coo.requisition-approval',[
            'coo_requisitions' => Requisition::where('hod_approval_status', true)
            ->where('coo_approval_status', 'false')->latest()->paginate(10)
        ]);
    }
}
