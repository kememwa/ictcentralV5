<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Designation;
use App\Models\Division;

class Designations extends Component
{
    public $search = '';
    public $actionFilter = '';

public $designation_name = '';

public $selectedDivision = null; // Selected division ID

public $searchHead = ''; // Search text

public $headResults = []; // Matching users

public $showHeadDropdown = false;

//Search and select Designation for "Reports To" field

public $selectedReportsTo = null; // Selected designation ID
public $searchReportsTo = ''; // Search text for reports to
public $reportsToResults = []; // Matching designations for reports to
public $showReportsToDropdown = false; // Show/hide reports to dropdown


protected $rules = [
    'designation_name' => 'required|string|max:255|unique:designations,name',
    'selectedDivision' => 'nullable|exists:divisions,id',
    'selectedReportsTo' => 'nullable|exists:designations,id',
];

protected $messages = [
    'designation_name.required' => 'Designation name is required.',
    'designation_name.unique' => 'This designation already exists.',
    'selectedDivision.exists' => 'Selected division is invalid.',
    'selectedReportsTo.exists' => 'Selected reports to designation is invalid.',
];


//search for division from the divisions table
public function updatedSearchHead()
{
    $search = trim($this->searchHead);

    if (strlen($search) < 2) {
        $this->headResults = [];
        $this->showHeadDropdown = false;
        return;
    }

    $this->showHeadDropdown = true;

    $this->headResults = Division::query()
        ->where(function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%");
        })
        ->orderBy('name')
        ->limit(8)
        ->get();
}

public function selectHead($id)
{
    $division = Division::find($id);

    if (!$division) {
        return;
    }

    $this->selectedDivision = $division->id;

    $this->searchHead = $division->name;

    $this->showHeadDropdown = false;

    $this->headResults = [];
}

public function removeHead()
{
    $this->selectedDivision = null;
    $this->searchHead = '';
}

//search for division from the divisions table
public function updatedSearchReportsTo()
{
    $search = trim($this->searchReportsTo);

    if (strlen($search) < 2) {
        $this->reportsToResults = [];
        $this->showReportsToDropdown = false;
        return;
    }

    $this->showReportsToDropdown = true;

    $this->reportsToResults = Designation::query()
        ->where(function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%");
        })
        ->orderBy('name')
        ->limit(8)
        ->get();
}

public function selectReportsTo($id)
{
    $designation = Designation::find($id);

    if (!$designation) {
        return;
    }

    $this->selectedReportsTo = $designation->id;

    $this->searchReportsTo = $designation->name;

    $this->showReportsToDropdown = false;

    $this->reportsToResults = [];
}

public function removeReportsTo()
{
    $this->selectedReportsTo = null;
    $this->searchReportsTo = '';
}


// Create the department
public function createDesignation()
{
    $this->validate();

    try {
        $designation = Designation::create([
            'name' => $this->designation_name,
            'division_id' => $this->selectedDivision, // Store only the ID
            'reports_to' => $this->selectedReportsTo, // Store only the ID
        ]);

        // Reset form
        $this->reset(['designation_name', 'searchReportsTo', 'selectedReportsTo', 'showReportsToDropdown', 'reportsToResults']);
        
        // Close modal
        $this->dispatch('close-designation-modal');

        // Success notification
        $this->dispatch('notify', 
                type: 'success',
                title: 'Designation Created',
                message: "Designation created successfully."
        );


    } catch (\Exception $e) {
        $this->addError('designation_name', 'Failed to create designation. Please try again.');
        
        // Log the error for debugging
        \Log::error('Designation creation failed: ' . $e->getMessage());
    }
}


    #[layout('layouts.dashboard')]
    public function render()
    {
        return view('livewire.designation',[
            'designations' => Designation::where('name', 'like', '%' . $this->search . '%')  
                                          ->latest()
                                          ->paginate(6),
        ]);
    }
}
