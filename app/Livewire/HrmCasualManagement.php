<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Requisition;

class HrmCasualManagement extends Component
{

    public function approveHrm($id)
    {
        $req = Requisition::findOrFail($id);

        $req->update([
            'hrm_approval_status' => 'approved',
        ]);

        // Flash message for Livewire UI
        $this->dispatch('notify',
            type: 'success',
            title: 'Approved',
            message: "Requisition approved successfully."
        );
    }

    #[layout('layouts.dashboard')]
    public function render()
    {
        return view('livewire.hrm-casual-management',[
            'hrm_requisitions' => Requisition::where('hr_approval_status', true)
            ->where('hrm_approval_status', 'pending')->latest()->paginate(10)
        ]);
    }
}
