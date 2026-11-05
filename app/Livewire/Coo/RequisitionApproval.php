<?php

namespace App\Livewire\Coo;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Requisition;
use Illuminate\Support\Facades\Mail;
use App\Mail\HrApproveRequisition;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class RequisitionApproval extends Component 
{
    use WithPagination;

    public $search = '';
    public $actionFilter = '';

    public $cooReturnRequisitionId = null;

    public $cooReturnReason = '';

    public function openCooReturnModal($id)
    {
        $requisition = Requisition::query()
            ->whereKey($id)
            ->where('status', 'pending')
            ->where('current_approval_stage', 'coo')
            ->firstOrFail();

        $this->cooReturnRequisitionId = $requisition->id;
        $this->cooReturnReason = '';

        $this->resetValidation();

        $this->dispatch('open-coo-return-modal');
    }

    public function closeCooReturnModal()
    {
        $this->reset([
            'cooReturnRequisitionId',
            'cooReturnReason',
        ]);

        $this->resetValidation();

        $this->dispatch('close-coo-return-modal');
    }

    public function returnCoo()
    {
        $this->validate([
            'cooReturnReason' => 'required|string|min:5|max:2000',
        ]);

        $id = $this->cooReturnRequisitionId;

        try {

            DB::transaction(function () use ($id) {

                $req = Requisition::whereKey($id)
                    ->lockForUpdate()
                    ->firstOrFail();

                /*
                |--------------------------------------------------------------------------
                | Verify COO authorization
                |--------------------------------------------------------------------------
                */

                $isCooApprover = auth()->user()
                    ->roles()
                    ->whereIn('name', ['Coo', 'coo delegate'])
                    ->exists();

                if (!$isCooApprover) {
                    abort(403, 'You are not authorized to return this requisition.');
                }

                /*
                |--------------------------------------------------------------------------
                | Verify current workflow stage
                |--------------------------------------------------------------------------
                */

                if (
                    $req->status !== 'pending' ||
                    $req->current_approval_stage !== 'coo'
                ) {
                    throw new \RuntimeException(
                        'This requisition is no longer awaiting COO action.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Find Pending COO Approval Record
                |--------------------------------------------------------------------------
                */

                $approval = $req->approvals()
                    ->where('approval_stage', 'coo')
                    ->where('approval_cycle', $req->approval_cycle)
                    ->where('status', 'pending')
                    ->lockForUpdate()
                    ->firstOrFail();

                /*
                |--------------------------------------------------------------------------
                | Update Approval History
                |--------------------------------------------------------------------------
                */

                $approval->update([
                    'approver_id' => auth()->id(),
                    'status' => 'returned',
                    'comments' => $this->cooReturnReason,
                    'acted_at' => now(),
                ]);

                /*
                |--------------------------------------------------------------------------
                | Return Requisition to Requester
                |--------------------------------------------------------------------------
                */

                $req->update([
                    'status' => 'returned',

                    'current_approval_stage' => 'requester',

                    'returned_from_stage' => 'coo',
                    'return_to_stage' => 'requester',

                    'returned_by' => auth()->id(),
                    'returned_at' => now(),
                    'return_reason' => $this->cooReturnReason,
                ]);

            });

            /*
            |--------------------------------------------------------------------------
            | Reset Modal
            |--------------------------------------------------------------------------
            */

            $this->reset([
                'cooReturnRequisitionId',
                'cooReturnReason',
            ]);

            $this->resetValidation();

            $this->dispatch('close-coo-return-modal');

            $this->dispatch(
                'notify',
                type: 'success',
                title: 'Requisition Returned',
                message: 'The requisition has been returned to the requester for corrections.'
            );

        } catch (\Throwable $e) {

            Log::error('COO requisition return failed', [
                'requisition_id' => $id,
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

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function actionFilter()
    {
        $this->resetPage();
    }


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
        return view('livewire.coo.requisition-approval', [
            'coo_requisitions' => Requisition::query()
                ->with(['requester.designation', 'approvals'])
                ->where('status', 'pending')
                ->where('current_approval_stage', 'coo')

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
