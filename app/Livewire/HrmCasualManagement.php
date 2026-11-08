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
use App\Mail\RequisitionReturned;
use App\Livewire\Concerns\RejectsRequisitions;

class HrmCasualManagement extends Component
{
    use WithPagination;
    use RejectsRequisitions;

    protected function rejectionStage(): string
    {
        return 'hrm';
    }

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

            $id = $this->hrmReturnRequisitionId;
            $returnReason = $this->hrmReturnReason;

            $req = DB::transaction(function () use ($user, $id, $returnReason) {

                $req = Requisition::whereKey($id)
                    ->lockForUpdate()
                    ->firstOrFail();

                // Verify current workflow stage
                if (
                    $req->status !== 'pending' ||
                    $req->current_approval_stage !== 'hrm'
                ) {
                    throw new \RuntimeException(
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
                    'comments' => $returnReason,
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
                    'return_reason' => $returnReason,

                    'hrm_approval_status' => 'returned',
                ]);

                return $req->fresh(['requester']);
            });

            // Notify HR Representative via email
            $requester = $req->requester;

            $actionUrl = route('hr.casual.manage');

            $hrApprovers = User::whereHas('roles', function ($query) {
                $query->whereIn('name', ['Hr', 'hr']);
            })
                ->whereNotNull('email')
                ->get();

            $emails = $hrApprovers->pluck('email')->unique()->values()->all();

            if (!empty($emails)) {
                try {
                    Mail::to($emails[0])
                        ->cc(array_slice($emails, 1))
                        ->send(new RequisitionReturned(
                            requisition: $req,
                            requester: $requester,
                            returnReason: $returnReason,
                            returnedBy: $user->name,
                            actionUrl: $actionUrl,
                            recipientGreeting: 'Team',
                        ));

                } catch (\Throwable $mailException) {
                    Log::error('HRM requisition returned email failed', [
                        'requisition_id' => $req->id,
                        'error' => $mailException->getMessage(),
                    ]);
                }

            } else {
                Log::warning('No HR Representative users found to receive HRM return notification.', [
                    'requisition_id' => $id,
                ]);
            }

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
                message: $e instanceof \RuntimeException
                    ? $e->getMessage()
                    : 'The requisition could not be returned. Please try again.'
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
        return view('livewire.hrm-casual-management', [
            'hrm_requisitions' => Requisition::query()
                ->where('status', 'pending')
                ->where('current_approval_stage', 'hrm')

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
