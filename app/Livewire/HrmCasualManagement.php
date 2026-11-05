<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Requisition;
use Illuminate\Support\Facades\Mail;
use App\Mail\HrmApproveReq;
use Illuminate\Support\Facades\Log;
use Livewire\WithPagination;
use App\Models\RequisitionApproval;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class HrmCasualManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $actionFilter = '';

    public $hrmReturnRequisitionId = null;
    public $hrmReturnReason = '';


    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function openHrmReturnModal($id)
    {
        $user = auth()->user();

        $isHrm = $user->roles()
            ->whereIn('name', ['hrm', 'hrm delegate'])
            ->exists();

        if (!$isHrm) {
            $this->dispatch(
                'notify',
                type: 'error',
                title: 'Unauthorized',
                message: 'You are not authorized to return this requisition.'
            );

            return;
        }

        $req = Requisition::findOrFail($id);

        if (
            $req->status !== 'pending' ||
            $req->current_approval_stage !== 'hrm'
        ) {
            $this->dispatch(
                'notify',
                type: 'error',
                title: 'Invalid Action',
                message: 'This requisition is no longer awaiting HRM approval.'
            );

            return;
        }

        $this->hrmReturnRequisitionId = $id;
        $this->hrmReturnReason = '';

        $this->resetValidation('hrmReturnReason');

        $this->dispatch('open-hrm-return-modal');
    }

    public function returnHrm()
    {
        $this->validate([
            'hrmReturnReason' => 'required|string|min:5|max:2000',
        ]);

        try {

            $user = auth()->user();

            // Verify HRM authorization
            $isHrm = $user->roles()
                ->whereIn('name', ['hrm', 'hrm delegate'])
                ->exists();

            if (!$isHrm) {
                throw new \Exception(
                    'You are not authorized to return this requisition.'
                );
            }

            DB::transaction(function () use ($user) {

                $req = Requisition::whereKey($this->hrmReturnRequisitionId)
                    ->lockForUpdate()
                    ->firstOrFail();

                // Verify current workflow stage
                if (
                    $req->status !== 'pending' ||
                    $req->current_approval_stage !== 'hrm'
                ) {
                    throw new \Exception(
                        'This requisition is no longer awaiting HRM approval.'
                    );
                }

                // Find pending HRM approval history
                $approval = RequisitionApproval::where(
                    'requisition_id',
                    $req->id
                )
                    ->where('approval_stage', 'hrm')
                    ->where('approval_cycle', $req->approval_cycle)
                    ->where('status', 'pending')
                    ->lockForUpdate()
                    ->firstOrFail();

                // Update approval history
                $approval->update([
                    'approver_id' => $user->id,
                    'status' => 'returned',
                    'comments' => $this->hrmReturnReason,
                    'acted_at' => now(),
                ]);

                // Return requisition to HR Representative
                $req->update([
                    'status' => 'pending',
                    'current_approval_stage' => 'hr_rep',

                    'return_to_stage' => 'hr_rep',
                    'returned_from_stage' => 'hrm',

                    'returned_by' => $user->id,
                    'returned_at' => now(),
                    'return_reason' => $this->hrmReturnReason,

                    'hrm_approval_status' => 'returned',
                ]);
            });

            // Reset modal properties
            $this->reset([
                'hrmReturnRequisitionId',
                'hrmReturnReason',
            ]);

            $this->resetValidation();

            $this->dispatch('close-hrm-return-modal');

            $this->dispatch(
                'notify',
                type: 'success',
                title: 'Requisition Returned',
                message: 'The requisition has been returned to the HR Representative for correction.'
            );

        } catch (\Throwable $e) {

            Log::error('HRM requisition return failed', [
                'requisition_id' => $this->hrmReturnRequisitionId,
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            $this->dispatch(
                'notify',
                type: 'error',
                title: 'Return Failed',
                message: 'The requisition could not be returned. Please try again.'
            );
        }
    }

    public function approveHrm($id)
    {
        try {

            $user = auth()->user();

            // Verify HRM authorization
            $isHrm = $user->roles()
                ->whereIn('name', ['hrm', 'hrm delegate'])
                ->exists();

            if (!$isHrm) {
                throw new \Exception(
                    'You are not authorized to approve this requisition.'
                );
            }

            // Approve within a transaction
            $req = DB::transaction(function () use ($id, $user) {

                $req = Requisition::whereKey($id)
                    ->lockForUpdate()
                    ->firstOrFail();

                // Verify current workflow stage
                if (
                    $req->status !== 'pending' ||
                    $req->current_approval_stage !== 'hrm'
                ) {
                    throw new \Exception(
                        'This requisition is no longer awaiting HRM approval.'
                    );
                }

                // Find pending HRM approval history
                $approval = RequisitionApproval::where(
                    'requisition_id',
                    $req->id
                )
                    ->where('approval_stage', 'hrm')
                    ->where('approval_cycle', $req->approval_cycle)
                    ->where('status', 'pending')
                    ->lockForUpdate()
                    ->firstOrFail();

                // Update approval history
                $approval->update([
                    'approver_id' => $user->id,
                    'status' => 'approved',
                    'acted_at' => now(),
                ]);

                // Complete requisition
                $req->update([
                    'hrm_approval_status' => 'approved',
                    'hrm_approval_date' => now(),
                    'hrm_id' => $user->id,

                    'status' => 'approved',
                    'current_approval_stage' => null,
                    'completed_at' => now(),

                    // Clear previous return information
                    'return_to_stage' => null,
                    'returned_from_stage' => null,
                    'returned_by' => null,
                    'returned_at' => null,
                    'return_reason' => null,
                ]);

                return $req->fresh(['hrRep']);
            });

            // Notify the HR Representative
            $hrUser = $req->hrRep;

            if ($hrUser && !empty($hrUser->email)) {

                Mail::to($hrUser->email)
                    ->queue(new HrmApproveReq($req));

            } else {

                Log::warning('HR Representative email not found after HRM approval.', [
                    'requisition_id' => $id,
                ]);
            }

            $this->dispatch(
                'notify',
                type: 'success',
                title: 'Approved',
                message: 'Requisition approved successfully. The approval process is complete.'
            );

        } catch (\Throwable $e) {

            Log::error('HRM requisition approval failed', [
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

    #[layout('layouts.dashboard')]
    public function render()
    {
        return view('livewire.hrm-casual-management',[
            'hrm_requisitions' => Requisition::where('hr_approval_status', true)
            ->where('hrm_approval_status', 'pending')
            ->when($this->search, function ($query) {
                $search = '%' . $this->search . '%';

                $query->where(function ($q) use ($search) {
                    $q->where('ref_number', 'like', $search)
                        ->orWhereHas('requester', function ($q) use ($search) {
                            $q->where('name', 'like', $search);
                        });
                });
            })->latest()->paginate(10)
        ]);
    }
}
