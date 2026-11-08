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
use App\Mail\RequisitionReturned;
use App\Models\RequisitionApproval;
use Illuminate\Support\Facades\DB;



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

    public $hrReturnRequisitionId = null;
    public $hrReturnReason = '';

    public function openHrReturnModal($id)
    {
        abort_unless(auth()->user()->hasRole('Hr'), 403);

        $requisition = Requisition::findOrFail($id);

        if (
            $requisition->status !== 'pending' ||
            $requisition->current_approval_stage !== 'hr_rep'
        ) {
            $this->dispatch(
                'notify',
                type: 'error',
                title: 'Action unavailable',
                message: 'This requisition is no longer awaiting HR action.'
            );

            return;
        }

        $this->hrReturnRequisitionId = $requisition->id;
        $this->hrReturnReason = '';

        $this->resetValidation('hrReturnReason');

        $this->dispatch('open-hr-return-modal');
    }

    public function returnHrRequisition()
    {
        $this->validate([
            'hrReturnReason' => ['required', 'string', 'min:5', 'max:2000'],
        ], [
            'hrReturnReason.required' => 'Please provide a reason for returning this requisition.',
            'hrReturnReason.min' => 'The return reason must be at least 5 characters.',
        ]);

        abort_unless(auth()->user()->hasRole('Hr'), 403);

        try {
            $user = auth()->user();
            $returnReason = $this->hrReturnReason;

            $req = DB::transaction(function () use ($user, $returnReason) {

                $requisition = Requisition::whereKey(
                    $this->hrReturnRequisitionId
                )
                    ->lockForUpdate()
                    ->firstOrFail();

                // Confirm that HR is the current approval stage.
                if (
                    $requisition->status !== 'pending' ||
                    $requisition->current_approval_stage !== 'hr_rep'
                ) {
                    throw new \RuntimeException(
                        'This requisition is no longer awaiting HR action.'
                    );
                }

                // Find the pending HR Representative approval record.
                $approval = RequisitionApproval::where(
                    'requisition_id',
                    $requisition->id
                )
                    ->where('approval_stage', 'hr_rep')
                    ->where('approval_cycle', $requisition->approval_cycle)
                    ->where('status', 'pending')
                    ->lockForUpdate()
                    ->first();

                /*
                * If HRM previously returned the requisition, the earlier
                * HR Representative approval may already be marked approved.
                * Create a fresh history record for this new HR action.
                */
                if (!$approval) {
                    $approval = RequisitionApproval::create([
                        'requisition_id' => $requisition->id,
                        'approver_id' => null,
                        'approval_stage' => 'hr_rep',
                        'status' => 'pending',
                        'approval_cycle' => $requisition->approval_cycle,
                    ]);
                }

                // Record the HR Representative's return action.
                $approval->update([
                    'status' => 'returned',
                    'approver_id' => $user->id,
                    'comments' => $returnReason,
                    'acted_at' => now(),
                ]);

                // Return the requisition to its requester.
                $requisition->update([
                    'status' => 'returned',

                    'current_approval_stage' => 'requester',

                    'return_to_stage' => 'requester',
                    'returned_from_stage' => 'hr_rep',

                    'returned_by' => $user->id,
                    'returned_at' => now(),
                    'return_reason' => $returnReason,
                ]);

                return $requisition->fresh(['requester']);
            });

            // Notify the requester via email
            // Send email notification to requester
            $requester = $req->requester;

            $actionUrl = route('casual-workforce');

            if ($requester && !empty($requester->email)) {
                try {
                    Mail::to($requester->email)->send(
                        new RequisitionReturned(
                            requisition: $req,
                            requester: $requester,
                            returnReason: $returnReason,
                            returnedBy: auth()->user()->name,
                            actionUrl: $actionUrl,
                        )
                    );

                } catch (\Throwable $mailException) {
                    Log::error('Requisition returned email failed', [
                        'requisition_id' =>  $req->id,
                        'requester_email' => $requester->email,
                        'error' => $mailException->getMessage(),
                    ]);
                }
            }

            $this->reset([
                'hrReturnRequisitionId',
                'hrReturnReason',
            ]);

            $this->dispatch('close-hr-return-modal');

            $this->dispatch(
                'notify',
                type: 'success',
                title: 'Requisition Returned',
                message: 'The requisition has been returned to the requester successfully.'
            );

        } catch (\Throwable $e) {

            Log::error('HR Representative requisition return failed', [
                'requisition_id' => $this->hrReturnRequisitionId,
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
            ]);

            $this->dispatch(
                'notify',
                type: 'error',
                title: 'Return Failed',
                message: $e instanceof \RuntimeException
                    ? $e->getMessage()
                    : 'An error occurred while returning the requisition.'
            );
        }
    }

    private function loadExistingHrRates($requisitions): void
    {
        foreach ($requisitions as $req) {

            // Load the previously saved daily rate
            if (!array_key_exists($req->id, $this->rates ?? [])) {
                $this->rates[$req->id] = $req->daily_rate;
            }

            // Load the previously saved NSSF rate
            if (!array_key_exists($req->id, $this->nssfRates ?? [])) {
                $this->nssfRates[$req->id] = $req->nssf_rate;
            }

            // Load the previously saved SHA rate
            if (!array_key_exists($req->id, $this->shaRates ?? [])) {
                $this->shaRates[$req->id] = $req->sha_rate;
            }
        }
    }

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
    public array $nssfRates = [];
    public array $shaRates = [];

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
            "nssfRates.$id" => 'required|numeric|min:0',
            "shaRates.$id" => 'required|numeric|min:0',
        ]);

        try {

            $user = auth()->user();

            // Confirm that the user is an HR Representative
            $isHr = $user->roles()
                ->where('name', 'hr')
                ->exists();

            if (!$isHr) {
                $this->dispatch(
                    'notify',
                    type: 'error',
                    title: 'Unauthorized',
                    message: 'You are not authorized to submit requisition rates.'
                );

                return;
            }

            $dailyRate = (float) $this->rates[$id];
            $nssfRate = (float) $this->nssfRates[$id];
            $shaRate = (float) $this->shaRates[$id];

            // Approve the HR Representative stage and move to HRM
            $req = DB::transaction(function () use (
                $id,
                $user,
                $dailyRate,
                $nssfRate,
                $shaRate
            ) {

                $req = Requisition::whereKey($id)
                    ->lockForUpdate()
                    ->firstOrFail();

                // Confirm the requisition is awaiting HR Representative action
                if (
                    $req->status !== 'pending' ||
                    $req->current_approval_stage !== 'hr_rep'
                ) {
                    throw new \Exception(
                        'This requisition is no longer awaiting HR Representative action.'
                    );
                }

                // Get the pending HR Representative approval record
                $approval = RequisitionApproval::where(
                    'requisition_id',
                    $req->id
                )
                    ->where('approval_stage', 'hr_rep')
                    ->where('approval_cycle', $req->approval_cycle)
                    ->where('status', 'pending')
                    ->lockForUpdate()
                    ->firstOrFail();

                $casuals = (int) $req->no_of_casuals;
                $duration = (int) $req->duration;

                // Calculate gross amount
                $grossAmount = $dailyRate * $duration * $casuals;

                // Calculate deductions
                $deductions = ($nssfRate + $shaRate) * $casuals;

                // Calculate final amount
                $totalAmount = $grossAmount - $deductions;

                // Update HR Representative approval history
                $approval->update([
                    'approver_id' => $user->id,
                    'status' => 'approved',
                    'acted_at' => now(),
                ]);

                // Update requisition details
                $req->update([
                    'daily_rate' => $dailyRate,
                    'nssf_rate' => $nssfRate,
                    'sha_rate' => $shaRate,
                    'total_amount' => $totalAmount,

                    'hr_approval_status' => true,
                    'hr_approval_date' => now(),
                    'hr_rep_id' => $user->id,

                    // Move to HRM
                    'current_approval_stage' => 'hrm',
                ]);

                // Create pending HRM approval history
                RequisitionApproval::create([
                    'requisition_id' => $req->id,
                    'approver_id' => null,
                    'approval_stage' => 'hrm',
                    'status' => 'pending',
                    'approval_cycle' => $req->approval_cycle,
                ]);

                return $req->fresh();
            });

            unset(
                $this->rates[$id],
                $this->nssfRates[$id],
                $this->shaRates[$id]
            );

            // Find HRM approvers
            $hrmApprovers = User::whereHas('roles', function ($query) {
                $query->where('name', 'hrm');
            })
                ->whereNotNull('email')
                ->get();

            // Find HRM delegates
            $hrmDelegates = User::whereHas('roles', function ($query) {
                $query->where('name', 'hrm delegate');
            })
                ->whereNotNull('email')
                ->get();

            $hrmEmails = $hrmApprovers->pluck('email')
                ->merge($hrmDelegates->pluck('email'))
                ->unique()
                ->values()
                ->all();

            // Notify HRM
            if (!empty($hrmEmails)) {

                Mail::to($hrmEmails[0])
                    ->cc(array_slice($hrmEmails, 1))
                    ->queue(new HrmApproveRequisition($req));

            } else {

                Log::warning('No HRM approvers found for requisition notification.', [
                    'requisition_id' => $id,
                ]);
            }

            $this->dispatch(
                'notify',
                type: 'success',
                title: 'Rates Submitted',
                message: 'Rates submitted successfully. The requisition has been forwarded to HRM.'
            );

        } catch (\Throwable $e) {

            Log::error('HR Representative rate submission failed', [
                'requisition_id' => $id,
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            $this->dispatch(
                'notify',
                type: 'error',
                title: 'Submission Failed',
                message: 'The requisition could not be submitted. Please try again.'
            );
        }
    }

    public function resubmitToHrm($id)
    {
        $user = auth()->user();

        // Only HR Representatives can resubmit.
        abort_unless($user->hasRole('Hr'), 403);

        // Validate the edited rates.
        $this->validate([
            "rates.$id" => ['required', 'numeric', 'min:1'],
            "nssfRates.$id" => ['required', 'numeric', 'min:0'],
            "shaRates.$id" => ['required', 'numeric', 'min:0'],
        ], [
            "rates.$id.required" => 'Please enter the daily rate.',
            "rates.$id.min" => 'The daily rate must be greater than zero.',
            "nssfRates.$id.required" => 'Please enter the NSSF rate.',
            "shaRates.$id.required" => 'Please enter the SHA rate.',
        ]);

        try {
            $requisition = DB::transaction(function () use ($id, $user) {

                $req = Requisition::whereKey($id)
                    ->lockForUpdate()
                    ->firstOrFail();

                // Ensure this is genuinely an HRM-returned requisition.
                if (
                    $req->status !== 'pending' ||
                    $req->current_approval_stage !== 'hr_rep' ||
                    $req->return_to_stage !== 'hr_rep' ||
                    $req->returned_from_stage !== 'hrm'
                ) {
                    throw new \Exception(
                        'This requisition is not awaiting HR Representative resubmission.'
                    );
                }

                $dailyRate = (float) $this->rates[$id];
                $nssfRate = (float) $this->nssfRates[$id];
                $shaRate = (float) $this->shaRates[$id];

                $casuals = (int) $req->no_of_casuals;
                $duration = (int) $req->duration;

                // Recalculate the estimated total.
                $grossAmount = $dailyRate * $duration * $casuals;
                $deductions = ($nssfRate + $shaRate) * $casuals;
                $totalAmount = $grossAmount - $deductions;

                if ($totalAmount < 0) {
                    throw new \Exception(
                        'The deductions cannot exceed the gross amount.'
                    );
                }

                /*
                * Preserve the original HRM return reason in the HRM
                * approval history before clearing the active return fields.
                *
                * The previous HR Representative approval record remains
                * untouched to preserve the approval history.
                */

                // Create a NEW approved HR Representative history record.
                $req->approvals()->create([
                    'approval_stage' => 'hr_rep',
                    'approval_cycle' => $req->approval_cycle,
                    'status' => 'approved',
                    'approver_id' => $user->id,
                    'comments' => 'Edited rates resubmitted to HRM.',
                    'acted_at' => now(),
                ]);

                // Create a NEW pending HRM approval record.
               $req->approvals()->create([
                    'approval_stage' => 'hrm',
                    'approval_cycle' => $req->approval_cycle,
                    'status' => 'pending',
                ]);

                // Update the requisition without changing HOD or COO approvals.
                $req->update([
                    'daily_rate' => $dailyRate,
                    'nssf_rate' => $nssfRate,
                    'sha_rate' => $shaRate,
                    'total_amount' => $totalAmount,

                    'hr_approval_status' => true,
                    'hr_rep_id' => $user->id,
                    'hr_rep_approval_date' => now(),

                    'current_approval_stage' => 'hrm',

                    // Clear the active return information.
                    // The return reason remains in the HRM history comments.
                    'return_to_stage' => null,
                    'returned_from_stage' => null,
                    'returned_by' => null,
                    'returned_at' => null,
                    'return_reason' => null,
                ]);

                return $req->fresh();
            });

            /*
            * Send the HRM approval email here.
            * Reuse the same HRM email-recipient and Mailable logic
            * that you already use in submitRate().
            */

            // Find HRM approvers
            $hrmApprovers = User::whereHas('roles', function ($query) {
                $query->where('name', 'hrm');
            })
                ->whereNotNull('email')
                ->get();

            // Find HRM delegates
            $hrmDelegates = User::whereHas('roles', function ($query) {
                $query->where('name', 'hrm delegate');
            })
                ->whereNotNull('email')
                ->get();

            $hrmEmails = $hrmApprovers->pluck('email')
                ->merge($hrmDelegates->pluck('email'))
                ->unique()
                ->values()
                ->all();

            // Notify HRM
            if (!empty($hrmEmails)) {

                Mail::to($hrmEmails[0])
                    ->cc(array_slice($hrmEmails, 1))
                    ->queue(new HrmApproveRequisition($requisition));

            } else {

                Log::warning('No HRM approvers found for requisition notification.', [
                    'requisition_id' => $id,
                ]);
            }


            $this->dispatch(
                'notify',
                type: 'success',
                title: 'Requisition Resubmitted',
                message: 'The edited requisition has been successfully resubmitted to HRM.'
            );

            // Remove stale Livewire input values so the table reloads
            // from the saved database values.
            unset(
                $this->rates[$id],
                $this->nssfRates[$id],
                $this->shaRates[$id]
            );

        } catch (\Throwable $e) {

            Log::error('HR Representative requisition resubmission failed.', [
                'requisition_id' => $id,
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            $this->dispatch(
                'notify',
                type: 'error',
                title: 'Resubmission Failed',
                message: $e instanceof \Exception
                    ? $e->getMessage()
                    : 'An error occurred while resubmitting the requisition.'
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

            // Pending HR Representative action
            ->when($this->view === 'pending', function ($q) {
                $q->where('status', 'pending')
                ->where('current_approval_stage', 'hr_rep');
            })

            // Approved by HRM, awaiting casual assignment
            ->when($this->view === 'hrm_approved', function ($q) {
                $q->where('status', 'approved')
                ->where('hrm_approval_status', 'approved')
                ->where('casual_assignment_status', false);
            })

            // Rejected requisitions
            ->when($this->view === 'rejected', function ($q) {
                $q->where('status', 'rejected');
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
        $hrRequisitions = $this->hrRequisitions;

        // Populate saved rates without overwriting current user input
        $this->loadExistingHrRates($hrRequisitions);

        return view('livewire.hr-casual-management', [
            'hrRequisitions' => $hrRequisitions,
            'casualsList' => $this->casuals,
        ]);
    }
}