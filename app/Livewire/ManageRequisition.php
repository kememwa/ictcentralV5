<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Requisition;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\CooApproveRequisition;




class ManageRequisition extends Component
{


    public function approveRequest($id)
    {
        try {

            $requisition = Requisition::findOrFail($id);

            // Approve requisition as HOD
            $requisition->update([
                'hod_approval_status' => '1',
                'hod_id' => auth()->user()->id,
                'hod_approval_date' => now(),
            ]);

            // Find COO and COO Delegate
            $cooApprovers = User::whereHas('roles', function ($query) {
                $query->whereIn('name', ['coo', 'coo delegate']);
            })
            ->whereNotNull('email')
            ->get();

            // Send email to COO / COO Delegate
            $emails = $cooApprovers->pluck('email')->toArray();

            if (!empty($emails)) {
                Mail::to($emails[0])
                    ->cc(array_slice($emails, 1))
                    ->send(new CooApproveRequisition($requisition));
            }

            // Success notification
            $this->dispatch(
                'notify',
                type: 'success',
                title: 'Requisition Approved',
                message: 'Requisition approved successfully.'
            );

        } catch (\Throwable $e) {

            Log::error('HOD requisition approval failed', [
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
        $departmentId = auth()->user()->designation->division->department->id;

        $designationId = auth()->user()->designation->id;

        return view('livewire.manage-requisition', [
            'requisitions' => Requisition::where('department_id', $departmentId)
                ->where('hod_approval_status', '0')
                ->whereHas('requester.designation', function ($query) use ($designationId) {
                    $query->where('reports_to', $designationId);
                })
                ->latest()
                ->paginate(10),
        ]);
        

    }

}
