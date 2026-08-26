<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Department;
use App\Models\Division;

class Divisions extends Component
{


public $search = '';
public $actionFilter = '';


public $division_name = '';

public $selectedDepartment = null; // Selected department ID

public $searchHead = ''; // Search text

public $headResults = []; // Matching users

public $showHeadDropdown = false;


protected $rules = [
    'division_name' => 'required|string|max:255|unique:divisions,name',
    'selectedDepartment' => 'nullable|exists:departments,id',
];

protected $messages = [
    'division_name.required' => 'Division name is required.',
    'division_name.unique' => 'This division already exists.',
    'selectedDepartment.exists' => 'Selected department is invalid.',
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

    $this->headResults = Department::query()
        ->where(function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%");
        })
        ->orderBy('name')
        ->limit(8)
        ->get();
}

public function selectHead($id)
{
    $department = Department::find($id);

    if (!$department) {
        return;
    }

    $this->selectedDepartment = $department->id;

    $this->searchHead = $department->name;

    $this->showHeadDropdown = false;

    $this->headResults = [];
}

public function removeHead()
{
    $this->selectedDepartment = null;
    $this->searchHead = '';
}

// Create the department
public function createDivision()
{

    $this->validate();

    try {
        $division = Division::create([
            'name' => $this->division_name,
            'department_id' => $this->selectedDepartment, // Store only the ID
        ]);

        // Reset form
        $this->reset(['division_name', 'searchHead', 'selectedDepartment', 'showHeadDropdown', 'headResults']);
        
        // Close modal
        $this->dispatch('close-division-modal');

        // Success notification
        $this->dispatch('notify', 
                type: 'success',
                title: 'Division Created',
                message: "Division created successfully."
        );


    } catch (\Exception $e) {
        $this->addError('division_name', 'Failed to create division. Please try again.');
        
        // Log the error for debugging
        \Log::error('Division creation failed: ' . $e->getMessage());
    }
}


    #[layout('layouts.dashboard')]
    public function render()
    {
        return view('livewire.division',[
            'divisions' => Division::where('name', 'like', '%' . $this->search . '%')  
                                          ->latest()
                                          ->paginate(6),
        ]);
    }
}
