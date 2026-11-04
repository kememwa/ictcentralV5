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
                ->where('requested_by', auth()->id())
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