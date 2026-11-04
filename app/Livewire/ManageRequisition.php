<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Requisition;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\CooApproveRequisition;
use Illuminate\Support\Facades\Log;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;



class ManageRequisition extends Component
{
    use WithPagination;


    public $search = '';
    public $actionFilter = '';

    public $returnRequisitionId;
    public $returnReason;

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function actionFilter()
    {
        $this->resetPage();
    }


    
    public function approveRequest($id)
    {
        try {
            $requisition = DB::transaction(function () use ($id) {

                $req = Requisition::whereKey($id)
                    ->lockForUpdate()
                    ->firstOrFail();

                // Ensure this requisition is awaiting HOD approval.
                if (
                    $req->status !== 'pending' ||
                    $req->current_approval_stage !== 'hod'
                ) {
                    throw new \RuntimeException(
                        'This requisition is not awaiting HOD approval.'
                    );
                }

                $approval = $req->approvals()
                    ->where('approval_stage', 'hod')
                    ->where('approval_cycle', $req->approval_cycle)
                    ->where('status', 'pending')
                    ->lockForUpdate()
                    ->firstOrFail();

                $approval->update([
                    'approver_id' => auth()->id(),
                    'status' => 'approved',
                    'acted_at' => now(),
                ]);

                $req->update([
                    'hod_approval_status' => true,
                    'hod_id' => auth()->id(),
                    'hod_approval_date' => now(),
                    'current_approval_stage' => 'coo',
                ]);

                // Create COO approval record.
                $req->approvals()->create([
                    'approval_stage' => 'coo',
                    'status' => 'pending',
                    'approval_cycle' => $req->approval_cycle,
                ]);

                return $req;
            });

            // Notify COO approvers.
            $cooApprovers = User::whereHas('roles', function ($query) {
                $query->whereIn('name', ['coo', 'coo delegate']);
            })
            ->whereNotNull('email')
            ->get();

            $emails = $cooApprovers->pluck('email')->toArray();

            if (!empty($emails)) {
                Mail::to($emails[0])
                    ->cc(array_slice($emails, 1))
                    ->send(new CooApproveRequisition($requisition));
            }

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
                message: $e instanceof \RuntimeException
                    ? $e->getMessage()
                    : 'The requisition could not be approved. Please contact Admin.'
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

    public function openReturnModal($id)
    {
        $this->resetValidation();

        $this->returnRequisitionId = $id;
        $this->returnReason = '';

        $this->dispatch('open-return-modal');
    }

    public function closeReturnModal()
    {
        $this->reset([
            'returnRequisitionId',
            'returnReason',
        ]);

        $this->resetValidation();

        $this->dispatch('close-return-modal');
    }



    public function returnRequisition($id)
    {
        $this->validate([
            'returnReason' => 'required|string|min:5|max:2000',
        ]);

        try {
            DB::transaction(function () use ($id) {

                $req = Requisition::whereKey($id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if (
                    $req->status !== 'pending' ||
                    $req->current_approval_stage !== 'hod'
                ) {
                    throw new \RuntimeException(
                        'This requisition is not awaiting HOD review.'
                    );
                }

                $approval = $req->approvals()
                    ->where('approval_stage', 'hod')
                    ->where('approval_cycle', $req->approval_cycle)
                    ->where('status', 'pending')
                    ->lockForUpdate()
                    ->firstOrFail();

                $approval->update([
                    'approver_id' => auth()->id(),
                    'status' => 'returned',
                    'comments' => $this->returnReason,
                    'acted_at' => now(),
                ]);

                $req->update([
                    'status' => 'returned',
                    'returned_by' => auth()->id(),
                    'returned_at' => now(),
                    'return_reason' => $this->returnReason,
                    'returned_from_stage' => 'hod',
                    'return_to_stage' => 'requester',
                ]);
            });

            $this->dispatch(
                'notify',
                type: 'success',
                title: 'Requisition Returned',
                message: 'The requisition has been returned to the requester.'
            );

            $this->reset(['returnRequisitionId', 'returnReason']);

        } catch (\Throwable $e) {

            Log::error('HOD requisition return failed', [
                'requisition_id' => $id,
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
            ]);

            $this->dispatch(
                'notify',
                type: 'error',
                title: 'Return Failed',
                message: $e instanceof \RuntimeException
                    ? $e->getMessage()
                    : 'Unable to return the requisition. Please try again.'
            );
        }
    }



    #[Layout('layouts.dashboard')]

    
    public function render()
    {
        $user = auth()->user();

        $departmentId = $user->designation->division->department->id;
        $designationId = $user->designation->id;

        return view('livewire.manage-requisition', [
            'requisitions' => Requisition::query()

                // Only requisitions belonging to this department
                ->where('department_id', $departmentId)

                // Only requisitions awaiting approval
                ->where('status', 'pending')

                // Only requisitions currently at HOD stage
                ->where('current_approval_stage', 'hod')

                // Only requesters reporting to this HOD designation
                ->whereHas('requester.designation', function ($query) use ($designationId) {
                    $query->where('reports_to', $designationId);
                })

                // Search functionality
                ->when($this->search, function ($query) {
                    $search = '%' . $this->search . '%';

                    $query->where(function ($q) use ($search) {
                        $q->where('ref_number', 'like', $search)
                            ->orWhereHas('requester', function ($q) use ($search) {
                                $q->where('name', 'like', $search);
                            });
                    });
                })

                ->latest()
                ->paginate(10),
        ]);
    }

}
