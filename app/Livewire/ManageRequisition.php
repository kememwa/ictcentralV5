<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Requisition;




class ManageRequisition extends Component
{


    public function approveRequest($id)
    {

    Requisition::where('id', $id)->update([
        'hod_approval_status' => '1',
        'hod_id' => auth()->user()->id,
        'hod_approval_date' => now(),
    ]);    
    
                // Flash message for Livewire UI
    $this->dispatch('notify', 
                type: 'success',
                title: 'Requisition Approved',
                message: "Requisition approved successfully."
    );


    }

    public function rejectRequest($id)
    {
                        // Flash message for Livewire UI
    $this->dispatch('notify', 
                type: 'error',
                title: 'Requisition Rejected',
                message: "Requisition rejected successfully."
    );
    }


    #[Layout('layouts.dashboard')]
    public function render()
    {
        return view('livewire.manage-requisition', [
            'requisitions' => Requisition::where('hod_id', auth()->id())
                ->where('hod_approval_status', '0')
                ->latest()
                ->paginate(10)
        ]);
    }

}
