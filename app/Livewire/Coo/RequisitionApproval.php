<?php

namespace App\Livewire\Coo;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Requisition;
use Illuminate\Support\Facades\Mail;
use App\Mail\HrApproveRequisition;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class RequisitionApproval extends Component 
{
    public function approveCoo($id)
    {
        try {

            $req = Requisition::findOrFail($id);

            // Approve requisition as COO
            $req->update([
                'coo_approval_status' => true,
                'coo_approval_date' => now(),
                'coo_id' => auth()->user()->id,
            ]);

            // Find all HR users
            $hrApprovers = User::whereHas('roles', function ($query) {
                $query->where('name', 'hr');
            })
            ->whereNotNull('email')
            ->get();

            // Get HR email addresses
            $emails = $hrApprovers->pluck('email')->toArray();

            // Send notification to HR
            if (!empty($emails)) {
                Mail::to($emails[0])
                    ->cc(array_slice($emails, 1))
                    ->queue(new HrApproveRequisition($req));
            }

            // Success notification
            $this->dispatch(
                'notify',
                type: 'success',
                title: 'Approved',
                message: 'Requisition approved successfully.'
            );

        } catch (\Throwable $e) {

            Log::error('COO requisition approval failed', [
                'requisition_id' => $id,
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            $this->dispatch(
                'notify',
                type: 'error',
                title: 'Approval Failed',
                message: 'The requisition could not be approved. Please try again.'
            );
        }
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
