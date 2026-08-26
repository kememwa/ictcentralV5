<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Onboarding;
use App\Models\OnboardingStep;
use App\Models\Document;
use App\Models\OnboardingDocument;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\OnboardUser;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Models\UserAnswer;
use App\Models\Question;

class OnboardNewUser extends Component
{
    public $search = '';
    public $current = 1;
    public $onboarding;
    public $internalId;
    public $onboardingId;
    public $selectedDocIds = [];
    public $documents; 
    public $onboardingDocuments = []; //documents already selected for the onboarding
    public $personalInfo = []; //personal information
    public $users;
    public $searchUser = '';
    public $selectedTeamMembers = [];
    public $supportStaffNumbers = [];
    public $selectedDepartments = [];
    
    // Add this property to store all users for client-side filtering
    public $allTeamMembers = [];

    public $showDocumentsModal = false; // modal visibility
    public bool $documentsConfirmed = false; //to be changed to false

    // IT Requirements
    public $laptop = false;
    public $desktop = false;
    public $monitor = false;
    public $mobile = false;

    // Software Access
    public $email = false;
    public $acumatica = false;
    public $vpn = false;
    public $shared_drives = false;

    // Facility Access
    public $main_gate = false;
    public $production = false;
    public $warehouse = false;
    public $office = false;

    // Admin / PPE
    public $safety_shoes = false;
    public $hard_hat = false;
    public $safety_glasses = false;
    public $uniform = false;
    public $id_card = false;
    public $access_card = false;

    // Notes
    public $notes_it;
    public $notes_admin;

    public function toggleSelectAll()
    {
        if (count($this->selectedDocIds) === count($this->documents)) {
            // Deselect all
            $this->selectedDocIds = [];
        } else {
            // Select all
            $this->selectedDocIds = $this->documents->pluck('id')->toArray();
        }
    }

    public $steps = [
        ['id' => 1, 'title' => 'Personal Info', 'description' => 'Enter employee details'],
        ['id' => 2, 'title' => 'Documentation', 'description' => 'Select Documents required duiring onboarding'],
        ['id' => 3, 'title' => 'IT & ADMIN Requirements Declaration', 'description' => 'Review IT and ADMIN Requirements for the staff'],
        ['id' => 4, 'title' => 'Completion and Notification', 'description' => 'Review And Send Notifications'],
    ];

    public function mount($onboardingId = null)
    {
        $this->documents = Document::all();

        // Load ALL users for client-side searching (you may want to limit this if you have thousands)
        $this->allTeamMembers = User::select('id', 'name', 'email')
            ->orderBy('name')
            ->get()
            ->toArray();

        if ($onboardingId) {
            // Resolve UUID → onboarding record
            $onboarding = Onboarding::where('uuid', $onboardingId)->firstOrFail();

            // Store UUID publicly
            $this->onboardingId = $onboarding->uuid;

            // Internal numeric ID for relations
            $this->internalId = $onboarding->id;

            // Load onboarding documents
            $this->onboardingDocuments = OnboardingDocument::with('document')
                ->where('onboarding_id', $this->internalId)
                ->get();

            // Load personal info
            $this->personalInfo = $onboarding;

            // Find first incomplete step
            $pendingStep = OnboardingStep::where('onboarding_id', $this->internalId)
                ->whereIn('status', ['pending', 'in_progress'])
                ->orderBy('step_number')
                ->first();

            $this->current = $pendingStep ? $pendingStep->step_number : 1;
        } else {
            // Fresh onboarding
            $this->current = 1;
        }
    }

    public $formData = [
        'personal' => [
            'firstName' => '',
            'lastName' => '',
            'email' => '',
            'phone' => '',
            'position' => '',
            'department' => '',
            'startDate' => '',
            'manager' => '',
            'salary' => '',
        ]
    ];

    public function messages()
    {
        return [
            'formData.personal.firstName.required' => 'First Name is required.',
            'formData.personal.lastName.required'  => 'Last Name is required.',
            'formData.personal.email.required'     => 'We need an email to proceed.',
            'formData.personal.email.email'        => 'Please enter a valid email address.',
            'formData.personal.email.unique'        => 'This email is already taken.',
            'formData.personal.position.required'  => 'Please specify the employee position.',
            'formData.personal.department.required'=> 'You must select a department.',
            'formData.personal.startDate.required' => 'Start Date cannot be empty.',
            'formData.personal.phone.required'   => 'Please provide a valid phone number for the employee.',
            'formData.personal.manager.required'   => 'Assigning a manager is required.',
            'selectedDocIds.required' => 'Please select at least one document to proceed.',
        ];
    }

    public function rules()
    {
        if ($this->current == 1) {
            return [
                'formData.personal.firstName' => 'required|string|max:255',
                'formData.personal.lastName'  => 'required|string|max:255',
                'formData.personal.email'     => 'required|email|unique:users,email|unique:onboardings,email',
                'formData.personal.phone'     => 'required|string|max:20',
                'formData.personal.position'  => 'required|string|max:255',
                'formData.personal.department'=> 'required|string|max:255',
                'formData.personal.startDate' => 'required|date',      
                
            ];
        }

        if ($this->current == 2) {
            return [
                 'selectedDocIds' => 'required|array|min:1',
            ];
        }

        return [];
    }

    public function next()
    {
        $rules = $this->rules();
        if (!empty($rules)) {
            $this->validate();
        }

        $this->persistStep();

        $nextStep = OnboardingStep::where('onboarding_id', $this->internalId)
            ->whereIn('status', ['pending', 'in_progress'])
            ->orderBy('step_number')
            ->first();

        $this->current = $nextStep ? $nextStep->step_number : $this->current;
    }

    public function prev()
    {
        if ($this->current > 1) {
            $this->current--;
        }
    }

    //this is where all logic to persist each step goes
    public function persistStep()
    {
        $randomPassword = Str::random(12);

        if ($this->current == 1) {
            if (!$this->onboardingId) {
                // Create onboarding
                $onboarding = Onboarding::create([
                    'email' => $this->formData['personal']['email'],
                    'name' => $this->formData['personal']['firstName'] . ' ' . $this->formData['personal']['lastName'],
                    'phone' => $this->formData['personal']['phone'],
                    'start_date' => $this->formData['personal']['startDate'],
                    'position' => $this->formData['personal']['position'],
                    'department' => $this->formData['personal']['department'],
                    'password' => Hash::make('12345'), //temporary password
                        
                ]);
                $this->internalId = $onboarding->id;

                // Initialize steps
                foreach ($this->steps as $step) {
                    OnboardingStep::create([
                        'onboarding_id' => $onboarding->id,
                        'step_number'   => $step['id'],
                        'step_name'     => $step['title'],
                        'status'        => $step['id'] === 1 ? 'completed' : ($step['id'] === 2 ? 'in_progress' : 'pending'),
                        'updated_by'    => auth()->id(),
                    ]);
                }
            } else {
                // Update onboarding info
                $onboarding = Onboarding::find($this->internalId);
                $onboarding->update([
                    'email' => $this->formData['personal']['email'],
                ]);

                // Update step statuses
                OnboardingStep::where('onboarding_id', $this->internalId)
                    ->where('step_number', 1)
                    ->update(['status' => 'completed']);
                OnboardingStep::where('onboarding_id', $this->internalId)
                    ->where('step_number', 2)
                    ->update(['status' => 'in_progress']);
            }

            $this->current = 2;
        }

        elseif ($this->current == 2) {
            OnboardingStep::where('onboarding_id', $this->internalId)
                ->where('step_number', 2)
                ->update(['status' => 'completed']);
            OnboardingStep::where('onboarding_id', $this->internalId)
                ->where('step_number', 3)
                ->update(['status' => 'in_progress']);

            $this->current = 3;
        }

        elseif ($this->current == 3) {
            $this->submitRequirements();
            OnboardingStep::where('onboarding_id', $this->internalId)
                ->where('step_number', 3)
                ->update(['status' => 'completed']);
            
            OnboardingStep::where('onboarding_id', $this->internalId)
                ->where('step_number', 4)
                ->update(['status' => 'in_progress']);

            $this->personalInfo = Onboarding::findOrFail($this->internalId);

            $this->current = 4;
        }

        elseif ($this->current == 4) {
            $this->onboardingDocuments = OnboardingDocument::where('onboarding_id', $this->internalId)->get();
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function saveSelection()
    {
        foreach ($this->selectedDocIds as $docId) {
            OnboardingDocument::updateOrCreate(
                ['onboarding_id' => $this->internalId, 'document_id' => $docId],
                ['submitted' => false]
            );
        }

        $this->documentsConfirmed = true;

        $this->dispatch('notify',
            type: 'success',
            title: 'Documents Saved',
            message: "Your document selections have been saved."
        );
    }

    //this captures the IT and Admin requirements
    public function submitRequirements()
    {
        $requirements = [
            'hardware' => [
                'laptop' => $this->laptop,
                'desktop' => $this->desktop,
                'monitor' => $this->monitor,
                'mobile' => $this->mobile,
            ],
            'software' => [
                'email' => $this->email,
                'acumatica' => $this->acumatica,
                'vpn' => $this->vpn,
                'shared_drives' => $this->shared_drives,
            ],
            'facility' => [
                'main_gate' => $this->main_gate,
                'production' => $this->production,
                'warehouse' => $this->warehouse,
                'office' => $this->office,
            ],
            'admin' => [
                'safety_shoes' => $this->safety_shoes,
                'hard_hat' => $this->hard_hat,
                'safety_glasses' => $this->safety_glasses,
                'uniform' => $this->uniform,
                'id_card' => $this->id_card,
                'access_card' => $this->access_card,
            ],
            'notes' => array_filter([
                'it' => $this->notes_it,
                'admin' => $this->notes_admin,
            ]), // 🔑 removes null/empty automatically
        ];

        // update the onboarding record with the requirements
        Onboarding::where('id', $this->internalId)->update([
            'requirements' => $requirements,
        ]);
    }

    public function addMember($memberData)
    {
        // Prevent duplicates by checking ID
        if (!collect($this->selectedTeamMembers)->contains('id', $memberData['id'])) {
            $this->selectedTeamMembers[] = $memberData;
        }
    }

    public function removeMember($id)
    {
        $this->selectedTeamMembers = collect($this->selectedTeamMembers)
            ->reject(fn($member) => $member['id'] === $id)
            ->values()
            ->toArray();
    }

    public function submit()
    {
        $this->persistStep();
        session()->flash('success', 'Onboarding completed successfully!');
        return redirect()->route('dashboard');
    }

    public function sendSupportNotification(){
        if(empty($this->supportStaffNumbers)){
            $this->dispatch('notify',
                type: 'error',
                title: 'Number Not found',
                message: "Please enter a valid support staff number"
            );
            return;
        }else {
            // Logic to send notification to support staff using cURL
            $url = 'https://api.mobilesasa.com/v1/send/bulk';
            $data = [
                "senderID" => "MOBILESASA",
                "message" => "Hello KimFay, this is a notification that we will be expecting a new staff member by the name of ".$this->personalInfo[0]->name." to join us on ".$this->personalInfo[0]->start_date.". Please make the necessary arrangements to ensure a smooth onboarding process. Thank you.",
                "phones" => implode(",", $this->supportStaffNumbers),
            ];

            $headers = [
                'Accept: application/json',
                'Content-Type: application/json',
                'Authorization: Bearer '. config('app.API_KEY'),
            ];

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $response_string = curl_exec($ch);
            $response_array = json_decode($response_string, true);

            if ($response_array['status']===false) {
                $this->dispatch('notify',
                    type: 'error',
                    title: 'Sending Notification Failed',
                    message: $response_array['message'],
                );
                return;
            } else {
                $this->dispatch('notify',
                    type: 'success',
                    title: 'Notification Sent',
                    message: $response_array['message']." Message Sent",
                );
            }

            curl_close($ch);
        }
    }

    public function sendNewHireNotification(){

        $questions = Question::all(); // Assuming you have this relationship

        if ($questions->isEmpty()) {
            $this->dispatch('notify',
                type: 'error',
                title: 'Quiz Not Found',
                message: "No quiz questions found. Please create quiz questions before sending notifications."
            );
            return;
        }
        else
        {

            // create questions to be answered by the new hire
            foreach ($questions as $question) {
                UserAnswer::firstOrCreate([
                    'user_id' => $this->internalId,
                    'question_id' => $question->id,
                ]);
            }
            // Send password reset link to the new hire
            Password::broker('onboarding')->sendResetLink([
                'email' => $this->personalInfo->email,
            ]);

            $this->dispatch('notify',
                type: 'success',
                title: 'Notification Sent',
                message: 'Password reset link sent successfully',
            );
        }

    }

    public function sendEmailNotifications()
    {


        if (empty($this->selectedTeamMembers) && empty($this->selectedDepartments)) {
            $this->dispatch('notify',
                type: 'error',
                title: 'Sending Notification Failed',
                message: 'No email address or department selected',
            );
        } else {
            // loop through the selected team members and get email addresses
            $emails = collect($this->selectedTeamMembers)->pluck('email')->toArray();

            
            $email = implode(",", $emails);
            // requirements in json format
            $req_json = json_decode($this->personalInfo->requirements, true); //convert to array

            $user_requirements = [];

            foreach ($req_json as $category => $items) {
                foreach ($items as $item => $value) {
                    if ($value===true){
                        $user_requirements[$category][] = $item;
                    }
                }
            }

            //send email to selected team members
            Mail::to($email)
                ->cc($email)
                ->queue(new OnboardUser([
                    'onboarding' => $this->personalInfo,
                    'requirements' => $user_requirements,
                ]));

            $this->selectedTeamMembers = [];
            $this->searchUser='';
            
            //display the success notification
            $this->dispatch('notify',
                type: 'success',
                title: 'Email Sent',
                message: 'Email notifications sent successfully',
            );
        }
    }

    #[layout('layouts.dashboard')]
    public function render()
    {
        // We no longer need to paginate here since we're using client-side filtering
        // But we'll keep $documents for the documents modal if needed
        $documents = Document::query()
            ->when($this->search, fn($q) =>
                $q->where('name', 'like', '%' . $this->search . '%')
            )
            ->get();

        return view('livewire.onboard-new-user', [
            'documents' => $documents,
            'allTeamMembers' => $this->allTeamMembers, // Pass all users to the view
        ]);
    }
}