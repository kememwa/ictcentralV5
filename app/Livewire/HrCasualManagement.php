<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Requisition;
use Livewire\WithPagination;
use App\Models\Casual;
use App\Models\Assignment;
use Illuminate\Support\Facades\Mail;
use App\Mail\HrmApproveRequisition;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Mail\CasualApproved;

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

    // For searching casuals
    public $hrSearch = '';
    public $searchCasual = '';
    public $actionFilter = '';
    // For assignment modal

    public function updatedhrSearch()
    {
        $this->resetPage();
    }

    public function updatedsearchCasual()
    {
        $this->resetPage();
    }


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
    public $nssfRates;
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
        try {

            if (!$this->selectedRequisition || empty($this->selectedCasuals)) {
                return;
            }

            // Create an assignment for each selected casual
            foreach ($this->selectedCasuals as $casualId) {
                Assignment::create([
                    'requisition_id' => $this->selectedRequisition->id,
                    'casual_id' => $casualId,
                    'status' => 'assigned',
                ]);
            }

            // Get the requisition model
            $req = Requisition::with('requester')
                ->findOrFail($this->selectedRequisition->id);

            // Update requisition assignment status
            $req->update([
                'casual_assignment_status' => true,
                'casual_assignment_date' => now(),
            ]);

            $assignments = $req->CasualAssignments()
            ->with('casual')
            ->where('status', 'assigned')
            ->get();

            // Send notification to the staff who requested the requisition
            $staffEmail = $req->requester?->email;

            if (!empty($staffEmail)) {
                Mail::to($staffEmail)
                    ->queue(new CasualApproved($req, $assignments));
            }

            $this->dispatch(
                'notify',
                type: 'success',
                title: 'Assignment Successful',
                message: count($this->selectedCasuals) . ' casual(s) assigned successfully.'
            );

            $this->showAssignmentModal = false;
            $this->resetForm();

        } catch (\Throwable $e) {

            Log::error('Casual assignment failed', [
                'requisition_id' => $this->selectedRequisition?->id,
                'user_id' => auth()->id(),
                'selected_casuals' => $this->selectedCasuals,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            $this->dispatch(
                'notify',
                type: 'error',
                title: 'Assignment Failed',
                message: 'The casuals could not be assigned. Please try again or contact IT support.'
            );
        }
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
            "nssfRates" => 'required|numeric|min:0',
            "shaRates" => 'required|numeric|min:0',
        ]);

        try{

            $req = Requisition::findOrFail($id);

            $dailyRate = (float) $this->rates[$id];
            $nssfRate = (float) $this->nssfRates;
            $shaRate = (float) $this->shaRates;
            $casuals = (int) $req->no_of_casuals;
            $duration = (int) $req->duration;

            // Gross amount before deductions
            $grossAmount = $dailyRate * $duration * $casuals;

            // NSSF + SHA deductions
            $deductions = ($nssfRate + $shaRate) * $casuals;

            // Final amount
            $totalAmount = $grossAmount - $deductions;

            $req->update([
                'daily_rate' => $dailyRate,
                'nssf_rate' => $nssfRate,
                'sha_rate' => $shaRate,
                'hr_approval_status' => true,
                'hr_approval_date' => now(),
                'hr_rep_id' => auth()->user()->id,
                'total_amount' => $totalAmount,
            ]);

            unset($this->rates[$id]);

            // send notification to HRM for approval
            $hrmApprovers = User::whereHas('roles', function ($query) {
                $query->where('name', 'hrm');
            })
            ->whereNotNull('email')
            ->get();

            $hrm_delegate = User::whereHas('roles', function ($query) {
                $query->where('name', 'hrm delegate');
            })
            ->whereNotNull('email')
            ->get();

            // Get HR email addresses
            $hrm_emails = $hrmApprovers->pluck('email')->toArray();
            $hrm_delegate_emails = $hrm_delegate->pluck('email')->toArray();

            // Send notification to HR
            if (!empty($hrm_emails)) {
                Mail::to($hrm_emails)
                    ->cc($hrm_delegate_emails)
                    ->queue(new HrmApproveRequisition($req));
            }

            // Flash message for Livewire UI
            $this->dispatch(
                'notify',
                type: 'success',
                title: 'Rate Submitted',
                message: 'Daily rate submitted successfully.'
            );

        }catch(\Throwable $e){

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
            // Search
            ->when($this->hrSearch, function ($q) {
                $search = '%' . $this->hrSearch . '%';

                $q->where(function ($query) use ($search) {
                    $query->where('ref_number', 'like', $search)
                        ->orWhereHas('requester', function ($query) use ($search) {
                            $query->where('name', 'like', $search);
                        });
                });
            })
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
                $q->where('hrm_approval_status', 'approved')
                ->where('casual_assignment_status', false);
            })

            ->when($this->view === 'rejected', function ($q) {
                $q->where('hrm_approval_status', 'rejected');
            })

            // Search
            ->when($this->hrSearch, function ($q) {
                $search = '%' . $this->hrSearch . '%';

                $q->where(function ($query) use ($search) {
                    $query->where('ref_number', 'like', $search)
                        ->orWhereHas('requester', function ($query) use ($search) {
                            $query->where('name', 'like', $search);
                        });
                });
            })

            ->latest()
            ->paginate(10);
    }

    public function getCasualsDataProperty()
    {
        return Casual::query()
            ->when($this->searchCasual, function ($query) {
                $search = '%' . $this->searchCasual . '%';

                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', $search)
                        ->orWhere('id_number', 'like', $search)
                        ->orWhere('phone_number', 'like', $search);
                });
            })
            ->latest()
            ->paginate(5);
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