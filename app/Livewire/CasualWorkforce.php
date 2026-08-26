<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Requisition;
use Livewire\WithPagination;

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
        $this->calculateDuration();
        $this->validate();

        if ($this->duration === 0) {
            // Correct Livewire 3 dispatch syntax
            $this->dispatch('notify', 
                type: 'error',
                title: 'Duration Days',
                message: "Duration days should be a minimum of 1 day"
            );
        } else {
            Requisition::create([
                'requested_by' => auth()->id(),
                'requested_date' => Carbon::now()->toDateString(),
                'no_of_casuals' => $this->no_of_casuals,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'reason' => $this->reason,
                'duration' => $this->duration,
                'hod_id' => auth()->user()->line_manager_id,
            ]);

            // Dispatch success notification
            $this->dispatch('notify', 
                type: 'success',
                title: 'Requisition Submitted',
                message: "Your requisition has been submitted for approval"
            );

            // Dispatch close modal event
            $this->dispatch('close-casual-modal');
            
            // Reset form after successful submission
            $this->reset([
                'start_date',
                'end_date',
                'no_of_casuals',
                'reason',
                'exclude_weekends',
                'duration',
            ]);
        }
        $this->resetPage();
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