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

class CasualWorkforce extends Component
{
    use WithPagination;

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
        $departmentId = auth()->user()->designation->division->department->id;

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

            $requester = auth()->user();

            $reportingDesignation = $requester->designation?->reportsTo;

            $lineManagers = User::where(
                'designation_id',
                $reportingDesignation?->id
            )
            ->whereNotNull('email')
            ->get();

            $requisition = Requisition::create([
                'requested_by' => auth()->id(),
                'department_id' => $departmentId,
                'requested_date' => Carbon::now()->toDateString(),
                'no_of_casuals' => $this->no_of_casuals,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'reason' => $this->reason,
                'duration' => $this->duration,
            ]);

            // Send notification to all reporting managers
            $emails = $lineManagers->pluck('email')->toArray();

            if (!empty($emails)) {
                Mail::to($emails[0])
                    ->cc(array_slice($emails, 1))
                    ->send(new HodApproveRequisition($requisition));
            }

            // Success notification
            $this->dispatch(
                'notify',
                type: 'success',
                title: 'Requisition Submitted',
                message: 'Your requisition has been submitted for approval'
            );

            // Close modal
            $this->dispatch('close-casual-modal');

            // Reset form
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

            // Log the actual exception for debugging
            Log::error('Casual requisition submission failed', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            // Show friendly error to the user
            $this->dispatch(
                'notify',
                type: 'error',
                title: 'Submission Failed',
                message: 'Unable to submit the requisition. Please try again or contact IT support.'
            );

            return;
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
            'MyRequisitions' => Requisition::where('requested_by', auth()->id())->latest()->paginate(5),
        ]);
    }
}