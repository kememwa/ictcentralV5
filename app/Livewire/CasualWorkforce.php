<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Requisition;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Mail\HodApproveRequisition;
use Illuminate\Support\Facades\Log;
use App\Models\DocumentSequence;
use Illuminate\Support\Facades\DB;
use App\Models\RequisitionApproval;


class CasualWorkforce extends Component
{
    use WithPagination;

    public $search = '';
    public $actionFilter = '';
    public $statusFilter = '';
    public $no_of_casuals;
    public $start_date;
    public $end_date;
    public $reason;
    public $exclude_weekends = [
        'saturday' => false,
        'sunday' => false,
    ];
    public $duration = 0;

    protected $rules = [
        'no_of_casuals' => 'required|numeric|min:1',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'reason' => 'required|string|max:255',
        'duration' => 'min:1',
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function actionFilter()
    {
        $this->resetPage();
    }

    public function updated($field)
    {
        $this->calculateDuration();
    }

    public function calculateDuration()
    {
        if (!$this->start_date || !$this->end_date) {
            $this->duration = 0;
            return;
        }

        $start = Carbon::parse($this->start_date);
        $end = Carbon::parse($this->end_date);
        $period = CarbonPeriod::create($start, $end);
        $days = 0;

        foreach ($period as $date) {
            $isSaturday = $date->isSaturday();
            $isSunday = $date->isSunday();

            if ($isSaturday && $this->exclude_weekends['saturday']) {
                continue;
            }
            if ($isSunday && $this->exclude_weekends['sunday']) {
                continue;
            }
            $days++;
        }

        $this->duration = $days;
    }

    public function openModal()
    {
        $this->reset([
            'start_date',
            'end_date',
            'no_of_casuals',
            'reason',
            'exclude_weekends',
            'duration',
        ]);
        $this->resetErrorBag();
        $this->resetValidation();
    }

    
    public function requisition()
    {
        $requester = auth()->user();

        $departmentId = $requester->designation
            ->division
            ->department
            ->id;

        $this->calculateDuration();
        $this->validate();

        if ($this->duration === 0) {
            $this->dispatch(
                'notify',
                type: 'error',
                title: 'Duration Days',
                message: 'Duration days should be a minimum of 1 day'
            );

            return;
        }

        try {

            // Identify HOD using the requester's reporting designation.
            $reportingDesignation = $requester->designation?->reportsTo;

            if (!$reportingDesignation) {
                throw new \RuntimeException(
                    'No reporting designation found for the requester.'
                );
            }

            $lineManagers = User::where(
                'designation_id',
                $reportingDesignation->id
            )
            ->whereNotNull('email')
            ->get();

            if ($lineManagers->isEmpty()) {
                throw new \RuntimeException(
                    'No HOD users with email addresses were found.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Create Requisition and Initial Approval Record
            |--------------------------------------------------------------------------
            */

            $requisition = DB::transaction(function () use ($departmentId, $lineManagers) {

                // Lock the requisition sequence.
                $sequence = DocumentSequence::where('name', 'requisition')
                    ->lockForUpdate()
                    ->first();

                if (!$sequence) {
                    $sequence = DocumentSequence::create([
                        'name' => 'requisition',
                        'current_number' => 0,
                    ]);

                    $sequence = DocumentSequence::where('id', $sequence->id)
                        ->lockForUpdate()
                        ->first();
                }

                // Increment sequence.
                $sequence->increment('current_number');

                $number = $sequence->current_number;

                // Create requisition.
                $requisition = Requisition::create([
                    'requested_by' => auth()->id(),
                    'department_id' => $departmentId,
                    'requested_date' => Carbon::now()->toDateString(),
                    'no_of_casuals' => $this->no_of_casuals,
                    'start_date' => $this->start_date,
                    'end_date' => $this->end_date,
                    'reason' => $this->reason,
                    'duration' => $this->duration,

                    'ref_number' => 'REQ-' . str_pad(
                        $number,
                        6,
                        '0',
                        STR_PAD_LEFT
                    ),

                    // Workflow initialization.
                    'status' => 'pending',
                    'current_approval_stage' => 'hod',
                    'approval_cycle' => 1,

                    // Existing HOD approval fields.
                    'hod_approval_status' => false,
                ]);

                // Create initial HOD approval-history record.
                RequisitionApproval::create([
                    'requisition_id' => $requisition->id,
                    'approver_id' => null,
                    'approval_stage' => 'hod',
                    'status' => 'pending',
                    'approval_cycle' => 1,
                ]);

                return $requisition;
            });

            /*
            |--------------------------------------------------------------------------
            | Notify HOD
            |--------------------------------------------------------------------------
            */

            $emails = $lineManagers->pluck('email')->toArray();

            Mail::to($emails[0])
                ->cc(array_slice($emails, 1))
                ->send(new HodApproveRequisition($requisition));

            /*
            |--------------------------------------------------------------------------
            | Success Notification
            |--------------------------------------------------------------------------
            */

            $this->dispatch(
                'notify',
                type: 'success',
                title: 'Requisition Submitted',
                message: 'Requisition ' . $requisition->ref_number .
                    ' has been submitted for approval'
            );

            $this->dispatch('close-casual-modal');

            $this->reset([
                'start_date',
                'end_date',
                'no_of_casuals',
                'reason',
                'exclude_weekends',
                'duration',
            ]);

            $this->resetPage();

        } catch (\Throwable $e) {

            Log::error('Failed to create requisition', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            $this->dispatch(
                'notify',
                type: 'error',
                title: 'Submission Failed',
                message: 'Unable to submit the requisition. Please try again.'
            );
        }
    }

    public $editingRequisitionId = null;

    public function editReturnedRequisition($id)
    {
        $requisition = Requisition::query()
            ->where('id', $id)
            ->where('requested_by', auth()->id())
            ->where('status', 'returned')
            ->where('return_to_stage', 'requester')
            ->firstOrFail();

        $this->editingRequisitionId = $requisition->id;

        $this->no_of_casuals = $requisition->no_of_casuals;
        $this->start_date = $requisition->start_date->format('Y-m-d');
        $this->end_date = $requisition->end_date->format('Y-m-d');
        $this->reason = $requisition->reason;
        $this->duration = $requisition->duration;

        // Restore the weekend settings if you persist them.
        $this->exclude_weekends = [
            'saturday' => false,
            'sunday' => false,
        ];

        $this->resetValidation();

        $this->dispatch('open-casual-modal');
    }

    public function saveRequisition()
    {
        if ($this->editingRequisitionId) {
            $this->resubmitReturnedRequisition();

            return;
        }

        $this->requisition();
    }

    //resubmit a returned requisition after editing
    public function resubmitReturnedRequisition()
    {
        $this->validate([
            'no_of_casuals' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|min:5|max:2000',
        ]);

        $requester = auth()->user();

        try {

            /*
            |--------------------------------------------------------------------------
            | Recalculate Duration
            |--------------------------------------------------------------------------
            | Use the same weekend-exclusion rules as your original requisition().
            */

            $this->calculateDuration();

            if ($this->duration < 1) {
                throw ValidationException::withMessages([
                    'duration' => 'The selected dates must contain at least one working day.',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Determine the Stage Where Approval Should Resume
            |--------------------------------------------------------------------------
            */

            $result = DB::transaction(function () use ($requester) {

                $requisition = Requisition::where('id', $this->editingRequisitionId)
                    ->lockForUpdate()
                    ->firstOrFail();

                // Security: only the original requester can resubmit.
                if ($requisition->requested_by !== $requester->id) {
                    abort(403, 'You are not authorized to edit this requisition.');
                }

                // Only requisitions returned to the requester can be edited.
                if (
                    $requisition->status !== 'returned' ||
                    $requisition->return_to_stage !== 'requester'
                ) {
                    throw ValidationException::withMessages([
                        'reason' => 'This requisition is no longer available for resubmission.',
                    ]);
                }

                $resumeStage = match ($requisition->returned_from_stage) {
                    'hod', 'coo' => 'hod',
                    'hr_rep' => 'hr_rep',
                    default => throw new \RuntimeException(
                        'Invalid requisition return stage.'
                    ),
                };

                $newCycle = $requisition->approval_cycle + 1;

                /*
                |--------------------------------------------------------------------------
                | Update Requisition
                |--------------------------------------------------------------------------
                */

                $updateData = [
                    'no_of_casuals' => $this->no_of_casuals,
                    'start_date' => $this->start_date,
                    'end_date' => $this->end_date,
                    'reason' => $this->reason,
                    'duration' => $this->duration,

                    'status' => 'pending',
                    'current_approval_stage' => $resumeStage,
                    'approval_cycle' => $newCycle,

                    'return_to_stage' => null,
                    'returned_from_stage' => null,
                    'returned_by' => null,
                    'returned_at' => null,
                    'return_reason' => null,
                    'resubmitted_at' => now(),
                ];

                /*
                * If the request returns to HOD, HOD and all downstream
                * approvals must be completed again.
                */
                if ($resumeStage === 'hod') {

                    $updateData = array_merge($updateData, [
                        'hod_approval_status' => false,
                        'hod_id' => null,
                        'hod_approval_date' => null,

                        'coo_approval_status' => false,
                        'coo_id' => null,
                        'coo_approval_date' => null,

                        'hr_approval_status' => false,
                        'hr_rep_id' => null,
                        'hr_rep_approval_date' => null,

                        'hrm_approval_status' => "pending",
                        'hrm_id' => null,
                        'hrm_approval_date' => null,
                    ]);

                } else {

                    /*
                    * HR Representative return:
                    * Preserve HOD and COO approvals because COO approval
                    * is already final.
                    */
                    $updateData = array_merge($updateData, [
                        'hr_approval_status' => false,
                        'hr_rep_id' => null,
                        'hr_rep_approval_date' => null,

                        'hrm_approval_status' => "pending",
                        'hrm_id' => null,
                        'hrm_approval_date' => null,
                    ]);
                }

                $requisition->update($updateData);

                /*
                |--------------------------------------------------------------------------
                | Create New Approval History
                |--------------------------------------------------------------------------
                | Previous approval records are deliberately NOT deleted.
                */

                RequisitionApproval::create([
                    'requisition_id' => $requisition->id,
                    'approver_id' => null,
                    'approval_stage' => $resumeStage,
                    'status' => 'pending',
                    'approval_cycle' => $newCycle,
                ]);

                return [
                    'requisition' => $requisition->fresh(),
                    'resume_stage' => $resumeStage,
                ];

            });

            /*
            |--------------------------------------------------------------------------
            | Notify the Next Approver
            |--------------------------------------------------------------------------
            | Send emails after the database transaction succeeds.
            */

            $requisition = $result['requisition'];

            if ($result['resume_stage'] === 'hod') {

                $reportingDesignation = $requester->designation?->reportsTo;

                $lineManagers = User::where(
                    'designation_id',
                    $reportingDesignation?->id
                )
                    ->whereNotNull('email')
                    ->get();

                if ($lineManagers->isEmpty()) {
                    Log::warning('No HOD email found for resubmitted requisition.', [
                        'requisition_id' => $requisition->id,
                        'requester_id' => $requester->id,
                    ]);
                } else {

                    $primaryManager = $lineManagers->first();
                    $ccManagers = $lineManagers->skip(1)
                        ->pluck('email')
                        ->filter()
                        ->unique()
                        ->values()
                        ->all();

                    Mail::to($primaryManager->email)
                        ->cc($ccManagers)
                        ->send(new HodApproveRequisition($requisition));
                }

            } elseif ($result['resume_stage'] === 'hr_rep') {

                /*
                * IMPORTANT:
                * Replace this recipient query with the exact HR Representative
                * assignment rule used by your existing application.
                *
                * This assumes HR Representatives have the role 'hr'.
                */
                $hrRepresentatives = User::role('hr')
                    ->whereNotNull('email')
                    ->get();

                if ($hrRepresentatives->isEmpty()) {
                    Log::warning('No HR Representative email found for resubmitted requisition.', [
                        'requisition_id' => $requisition->id,
                    ]);
                } else {
                    Mail::to($hrRepresentatives->pluck('email')->filter()->unique()->all())
                        ->send(new \App\Mail\HrApproveRequisition($requisition));
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Reset Form
            |--------------------------------------------------------------------------
            */

            $this->editingRequisitionId = null;

            $this->reset([
                'no_of_casuals',
                'start_date',
                'end_date',
                'reason',
                'duration',
            ]);

            $this->resetValidation();

            $this->dispatch('close-casual-modal');

            $this->dispatch(
                'notify',
                type: 'success',
                title: 'Requisition Resubmitted',
                message: 'Your requisition has been resubmitted successfully and sent for approval.'
            );

        } catch (ValidationException $e) {
            throw $e;

        } catch (\Throwable $e) {

            Log::error('Casual requisition resubmission failed.', [
                'requisition_id' => $this->editingRequisitionId,
                'requester_id' => $requester->id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            $this->dispatch(
                'notify',
                type: 'error',
                title: 'Resubmission Failed',
                message: 'An error occurred while resubmitting your requisition. Please try again.'
            );
        }
    }

    public function pettyCash()
    {
        $this->dispatch('notify', 
                    type: 'success',
                    title: 'Requisition Submitted',
                    message: "Your requisition has been submitted for approval"
                );

            $this->dispatch('close-petty-cash-modal');
    }

    
    public function GeneralRequisition()
    {
        $this->dispatch('notify', 
            type: 'error',
            title: 'Coming Soon',
            message: "This feature is under development."
        );
        $this->dispatch('close-requisition-modal');
    }

    #[Layout('layouts.dashboard')]
    public function render()
    {
        return view('livewire.casual-workforce', [
            'MyRequisitions' => Requisition::query()
                ->with(['returnedBy'])
                ->where('requested_by', auth()->id())

                ->when($this->statusFilter, function ($query) {
                    $query->where('status', $this->statusFilter);
                })

                ->when($this->search, function ($query) {
                    $search = '%' . $this->search . '%';

                    $query->where(function ($q) use ($search) {
                        $q->where('ref_number', 'like', $search)
                            ->orWhere('reason', 'like', $search);
                    });
                })

                ->latest()
                ->paginate(5),
        ]);
    }

}