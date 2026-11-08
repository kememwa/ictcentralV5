<?php

namespace App\Livewire\Concerns;

use App\Models\Requisition;
use App\Models\RequisitionApproval;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;
use App\Mail\RequisitionRejected;

trait RejectsRequisitions
{
    public $rejectRequisitionId = null;

    public $rejectRequisitionRef = '';

    public $rejectionReason = '';

    /**
     * Each Livewire component must identify its approval stage.
     */
    abstract protected function rejectionStage(): string;

    /**
     * Check whether the logged-in user can reject at this stage.
     */
    protected function canRejectRequisition(
        Requisition $req,
        string $stage
    ): bool {
        $user = auth()->user();

        if (!$user) {
            return false;
        }

        return match ($stage) {
            'hod' => $user->designation_id ===
                $req->requester?->designation?->reportsTo?->id,

            'coo' => $user->roles()
                ->whereIn('name', ['coo', 'Coo', 'coo delegate'])
                ->exists(),

            'hr_rep' => $user->roles()
                ->where('name', 'hr')
                ->exists(),

            'hrm' => $user->roles()
                ->whereIn('name', ['hrm', 'hrm delegate'])
                ->exists(),

            default => false,
        };
    }

    /**
     * Open the shared rejection modal.
     */
    public function openRejectModal($id): void
    {
        $stage = $this->rejectionStage();

        $req = Requisition::with('requester.designation.reportsTo')
            ->findOrFail($id);

        if (
            $req->status !== 'pending' ||
            $req->current_approval_stage !== $stage
        ) {
            $this->dispatch(
                'notify',
                type: 'error',
                title: 'Unavailable',
                message: 'This requisition is no longer awaiting your action.'
            );

            return;
        }

        if (!$this->canRejectRequisition($req, $stage)) {
            abort(403, 'You are not authorized to reject this requisition.');
        }

        $this->rejectRequisitionId = $req->id;
        $this->rejectRequisitionRef = $req->ref_number;
        $this->rejectionReason = '';

        $this->resetValidation();

        $this->dispatch('open-reject-modal');
    }

    /**
     * Permanently reject the requisition.
     */
    public function confirmRejection(): void
    {
        $this->validate([
            'rejectionReason' => [
                
                'string',
                'min:5',
                'max:2000',
            ],
        ], [
            'rejectionReason.required' => 'Please provide a rejection reason.',
            'rejectionReason.min' => 'The rejection reason must be at least 5 characters.',
        ]);

        $id = $this->rejectRequisitionId;
        $stage = $this->rejectionStage();
        $user = auth()->user();

        try {
            $req = DB::transaction(function () use (
                $id,
                $stage,
                $user
            ) {
                $req = Requisition::with(
                    'requester.designation.reportsTo'
                )
                    ->whereKey($id)
                    ->lockForUpdate()
                    ->firstOrFail();

                // Verify that the requisition is still at this stage.
                if (
                    $req->status !== 'pending' ||
                    $req->current_approval_stage !== $stage
                ) {
                    throw new \RuntimeException(
                        'This requisition is no longer awaiting your action.'
                    );
                }

                // Recheck authorization inside the transaction.
                if (!$this->canRejectRequisition($req, $stage)) {
                    throw new \RuntimeException(
                        'You are not authorized to reject this requisition.'
                    );
                }

                // Find the active approval-history record.
                $approval = $req->approvals()
                    ->where('approval_stage', $stage)
                    ->where('approval_cycle', $req->approval_cycle)
                    ->where('status', 'pending')
                    ->lockForUpdate()
                    ->firstOrFail();

                // Record the rejection in approval history.
                $approval->update([
                    'approver_id' => $user->id,
                    'status' => 'rejected',
                    'comments' => $this->rejectionReason,
                    'acted_at' => now(),
                ]);

                // Permanently close the requisition workflow.
                $req->update([
                    'status' => 'rejected',
                    'current_approval_stage' => null,

                    'rejected_by' => $user->id,
                    'rejected_at' => now(),
                    'rejection_reason' => $this->rejectionReason,

                    'completed_at' => now(),

                    // Clear active return information.
                    'return_to_stage' => null,
                    'returned_from_stage' => null,
                    'returned_by' => null,
                    'returned_at' => null,
                    'return_reason' => null,
                ]);

                return $req->fresh(['requester']);
            });

            $requester = $req->requester;

            $actionUrl = route('casual-workforce');

            if ($requester && !empty($requester->email)) {
                try {
                    Mail::to($requester->email)->send(
                        new RequisitionRejected(
                            requisition: $req,
                            requester: $requester,
                            rejectionReason: $this->rejectionReason,
                            rejectedBy: auth()->user()->name,
                            actionUrl: $actionUrl,
                        )
                    );

                } catch (\Throwable $mailException) {
                    Log::error('Requisition rejected email failed', [
                        'requisition_id' => $req->id,
                        'requester_email' => $requester->email,
                        'error' => $mailException->getMessage(),
                    ]);
                }
            }

            $this->reset([
                'rejectRequisitionId',
                'rejectRequisitionRef',
                'rejectionReason',
            ]);

            $this->resetValidation();

            $this->dispatch('close-reject-modal');

            $this->dispatch(
                'notify',
                type: 'success',
                title: 'Requisition Rejected',
                message: $req->ref_number .
                    ' has been rejected successfully. The workflow is now closed.'
            );

        } catch (\Throwable $e) {

            Log::error('Requisition rejection failed.', [
                'requisition_id' => $id,
                'approval_stage' => $stage,
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            $this->dispatch(
                'notify',
                type: 'error',
                title: 'Rejection Failed',
                message: $e instanceof \RuntimeException
                    ? $e->getMessage()
                    : 'The requisition could not be rejected. Please try again.'
            );
        }
    }
}