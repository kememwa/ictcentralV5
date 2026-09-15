<?php

namespace App\Livewire\Coo;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Requisition;

class RequisitionApproval extends Component
{
    public function approveCoo($id)
    {
        $req = Requisition::findOrFail($id);

        $req->update([
            'coo_approval_status' => true,
        ]);

        // Flash message for Livewire UI
        $this->dispatch('notify',
            type: 'success',
            title: 'Approved',
            message: "Requisition approved successfully."
        );
    }

    #[Layout('layouts.dashboard')]
    public function render()
    {
        return view('livewire.coo.requisition-approval',[
            'coo_requisitions' => Requisition::where('hod_approval_status', true)
            ->where('coo_approval_status', 'false')->latest()->paginate(10)
        ]);
    }
}
