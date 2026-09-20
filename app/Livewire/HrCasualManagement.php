<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Requisition;
use Livewire\WithPagination;
use App\Models\Casual;
use App\Models\Assignment;

class HrCasualManagement extends Component
{
    use WithPagination;

    // Casual data for adding new casuals
    public $casualData = [
        'full_name' => '',
        'email' => '',
        'phone' => '',
        'employee_id' => '',
        'position' => '',
        'assignment_details' => '',
    ];

    // For assignment modal
    public $selectedCasuals = [];
    public $search = '';
    public $filteredCasuals = [];
    public $allCasuals = [];
    public $casuals = []; // This is for Alpine.js binding
   public $selectedCasualId;

    // Assignment form fields
    public $shiftType;
    public $assignmentId;
    public $existingAssignment;

    protected $rules = [
        'selectedCasuals' => 'required|array|min:1',
        'selectedCasuals.*' => 'exists:casuals,id',
    ];

    public function mount()
    {
        $this->loadAllCasuals();
        $this->resetForm();
    }

    public function loadAllCasuals()
    {
        // Load all casual workers with necessary fields
        $this->allCasuals = Casual::orderBy('name')
            ->get(['id', 'name']) // Make sure these fields exist in your Casual model
            ->toArray();
            
        $this->filteredCasuals = $this->allCasuals;
        $this->casuals = $this->allCasuals; // Also populate this for Alpine
    }

    public function resetForm()
    {
        $this->selectedCasuals = [];
        $this->search = '';
        $this->assignmentDate = now()->format('Y-m-d');
        $this->shiftType = 'full_day';
        $this->location = '';
        $this->notes = '';
        $this->assignmentId = null;
        $this->existingAssignment = null;
        
        // Reset filtered casuals to show all
        $this->filteredCasuals = $this->allCasuals;
        $this->casuals = $this->allCasuals;
    }

    public function updatedSearch($value)
    {
        // Filter casuals based on search term
        if (empty($value)) {
            $this->filteredCasuals = $this->allCasuals;
        } else {
            $searchTerm = strtolower($value);
            $this->filteredCasuals = array_filter($this->allCasuals, function ($casual) use ($searchTerm) {
                $matches = [];
                
                // Check name
                if (isset($casual['name']) && str_contains(strtolower($casual['name']), $searchTerm)) {
                    $matches[] = true;
                }
                
             
                
                // Check id
                if (isset($casual['id']) && str_contains(strval($casual['id']), $searchTerm)) {
                    $matches[] = true;
                }
                
                return !empty($matches);
            });
        }
        
        // Update the casuals property for Alpine.js
        $this->casuals = array_values($this->filteredCasuals);
    }

    // For adding new casuals
    public $fname;
    public $lname;
    public $id_number;
    public $nssf_number;
    public $sha_number;
    public $phone_number;
    public $nfname;
    public $nlname;
    public $nphone_number;

    public string $view = 'pending';
    protected $queryString = ['view'];
    public array $rates = [];
    public $nhifRates;
    public $shaRates;

    public $casualSearch = '';
    public $casualStatusFilter = '';

    public $availableStaff = [];
    public $selectedStaffIds = [];
    public $approvedStaffCount = 0;
    public $staffSearch = '';

    public $showAssignmentModal = false;
    public $selectedRequisition;

    public function addCasual()
    {
        $this->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'id_number' => 'required|string|unique:casuals,id_number',
            'nssf_number' => 'required|string|unique:casuals,nssf_number',
            'sha_number' => 'required|string|unique:casuals,sha_number',
            'phone_number' => 'required|string|max:9|unique:casuals,phone_number',
            'nfname' => 'required|string|max:255',
            'nlname' => 'required|string|max:255',
            'nphone_number' => 'required|string|max:9',
        
        ]);

        $casual = Casual::create([
            'name' => trim($this->fname) . ' ' . trim($this->lname),
            'id_number' => $this->id_number,
            'nssf_number' => trim($this->nssf_number),
            'sha_number' => trim($this->sha_number),
            'phone_number' => '254'.trim($this->phone_number),
            'n_name' => trim($this->nfname) . ' ' . trim($this->nlname),
            'n_phone' => '254'.trim($this->nphone_number), // Default value
        ]);

        // Refresh the casuals list
        $this->loadAllCasuals();

        // Reset input fields
        $this->fname = '';
        $this->lname = '';
        $this->id_number = '';
        $this->nssf_number = '';
        $this->sha_number = '';
        $this->phone_number = '';
        $this->nfname = '';
        $this->nlname = '';
        $this->nphone_number = '';
        // Flash message for Livewire UI
        $this->dispatch('notify',
            type: 'success',
            title: 'Casual Added',
            message: "Casual worker added successfully."
        );


        $this->dispatch('close-casual-modal');
    }

    public function assignCasuals($requisitionId)
    {

        $this->resetForm();
        $this->resetValidation();

        $this->selectedRequisition = Requisition::findOrFail($requisitionId);
        $this->showAssignmentModal = true;
    }


    public function assignSelectedCasuals()
    {

        if ($this->selectedRequisition && !empty($this->selectedCasuals)) {
            
            //create a new assignment for each selected casual
            foreach ($this->selectedCasuals as $casualId) {
                Assignment::create([
                    'requisition_id' => $this->selectedRequisition->id,
                    'casual_id' => $casualId,
                    'status' => 'assigned',
                ]);
            }

            Requisition::where('id', $this->selectedRequisition->id)->update([
                'casual_assignment_status' => true,
            ]);
            
            $this->dispatch('notify',
                type: 'success',
                title: 'Assignment Successful',
                message: count($this->selectedCasuals) . " casual(s) assigned successfully."
            );
        }

        $this->showAssignmentModal = false;
        $this->resetForm();
    }

    public function update() // Add this method for form submission
    {
        $this->validate();

        // Handle the assignment logic here
        // For example, attach selected casuals to the requisition
        
        if ($this->selectedRequisition) {
            $this->selectedRequisition->casuals()->sync($this->selectedCasuals);
            
            // Update requisition status
            $this->selectedRequisition->update([
                'status' => 'assigned',
                'assigned_at' => now(),
            ]);
            
            $this->dispatch('notify',
                type: 'success',
                title: 'Assignment Successful',
                message: count($this->selectedCasuals) . " casual(s) assigned successfully."
            );
        }

        $this->showAssignmentModal = false;
        $this->resetForm();
    }

    public function submitRate($id)
    {
        $this->validate([
            "rates.$id" => 'required|numeric|min:1',
            "nhifRates" => 'required|numeric|min:0',
            "shaRates" => 'required|numeric|min:0',
        ]);

        $req = Requisition::findOrFail($id);

        $dailyRate = (float) $this->rates[$id];
        $nhifRate = (float) $this->nhifRates;
        $shaRate = (float) $this->shaRates;
        $casuals = (int) $req->no_of_casuals;
        $duration = (int) $req->duration;

        // Gross amount before deductions
        $grossAmount = $dailyRate * $duration * $casuals;

        // NHIF + SHA deductions
        $deductions = ($nhifRate + $shaRate) * $casuals;

        // Final amount
        $totalAmount = $grossAmount - $deductions;

        $req->update([
            'daily_rate' => $dailyRate,
            'hr_approval_status' => true,
            'total_amount' => $totalAmount,
        ]);

        unset($this->rates[$id]);

        // Flash message for Livewire UI
        $this->dispatch(
            'notify',
            type: 'success',
            title: 'Rate Submitted',
            message: 'Daily rate submitted successfully.'
        );
    }

    public function setView($view)
    {
        $this->view = $view;
        $this->resetPage();
    }

    public function getAssignedRequisitionsProperty()
    {
        return Requisition::query()
            ->where('hrm_approval_status', 'approved')
            ->where('casual_assignment_status', true)
            ->withCount('casualAssignments')
            ->latest()
            ->paginate(10);
    }

    public function getHrRequisitionsProperty()
    {
        return Requisition::query()
            ->where('hod_approval_status', '1')
            ->where('coo_approval_status', '1')
            ->when($this->view === 'pending', function ($q) {
                $q->whereNull('hr_approval_status');
            })
            ->when($this->view === 'hrm_approved', function ($q) {
                $q->where('hrm_approval_status', true)
                ->where('casual_assignment_status', false);
            })
            ->when($this->view === 'rejected', function ($q) {
                $q->where('hrm_approval_status', 'rejected');
            })
            ->latest()
            ->paginate(10);
    }

    public function getCasualsDataProperty()
    {
        return Casual::latest()->paginate(5);
    }

    #[Layout('layouts.dashboard')]
    public function render()
    {
        return view('livewire.hr-casual-management', [
            'hrRequisitions' => $this->hrRequisitions,
            'casualsList' => $this->casuals,
        ]);
    }
}