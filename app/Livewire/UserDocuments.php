<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\OnboardingDocument;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Mail;
use App\Mail\DocumentsApproved;

class UserDocuments extends Component
{

    use WithPagination;

    public $search = '';
    public $statusFilter = 'all';


    public function updatingSearch()
    {
        $this->resetPage();
    }

     public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public $showPreview = false;
    public $previewPath;

    public function viewDocument($id)
    {
        $doc = OnboardingDocument::findOrFail($id);

    
        // Make sure it's the correct path (storage/app/public/documents/...)
        $this->previewPath = $doc->document_path; 
        $this->showPreview = true;
    }

    public function approveDocument($id, $onboarding_id)
    {
        
        $doc = OnboardingDocument::findOrFail($id);
        $name = $doc->onboarding->name;
        $email = $doc->onboarding->email;
        // update hr_approved to true
        $doc->update(['hr_approved' => true]);

        $pendingDoc = OnboardingDocument::where('onboarding_id', $onboarding_id)
                        ->where('hr_approved', false)
                        ->where('submitted', true)
                        ->get();

        if($pendingDoc->isNotEmpty()){

            $this->dispatch('notify-multiple', notifications: [
                [
                    'type' => 'success',
                    'title' => 'Document Approved',
                    'message' => "Your document has been approved."
                ],
                [
                    'type' => 'error',
                    'title' => 'Pending Documents',
                    'message' => "There are still pending documents for $name"
                ]
            ]);

        }
        else{
            //send mail to user that all documents are approved
            Mail::to($email)->send(new DocumentsApproved([
                'name' => $name,
            ]));

            $this->dispatch('notify-multiple', notifications: [
                [
                    'type' => 'success',
                    'title' => 'Document Approved',
                    'message' => "Your document has been approved."
                ],
                [
                    'type' => 'success',
                    'title' => 'Notification Sent',
                    'message' => "A notification email has been sent to $name."
                ]
            ]);
        }
    }

    public function disapproveDocument($id){
        $doc = OnboardingDocument::findOrFail($id);
        // update hr_approved to false and clear submitted fields
        $doc->update([
            'hr_approved' => false,
            'submitted' => false,
            'submitted_at' => null,
            'submitted_by' => null,
            'document_path' => null,
        ]);

        $this->dispatch('notify',
            type: 'success',
            title: 'Document Disapproved',
            message: "The document has been marked as not approved."
        );
    }


    #[layout('layouts.dashboard')]
    public function render()
    {


        $query = OnboardingDocument::with(['onboarding']);

        // Search filter
        $query->when($this->search, function ($q) {
            $q->whereHas('onboarding', function ($sub) {
                $sub->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        });

        // Status filter
        if ($this->statusFilter !== 'all') {
            if ($this->statusFilter === 'approved') {
                $query->where('hr_approved', true);
            } elseif ($this->statusFilter === 'pending') {
                $query->where('hr_approved', false);
            }
        }

        // Finally paginate
        $onboardingDocuments = $query->paginate(5);



        return view('livewire.user-documents', [
            'onboardingDocuments' => $onboardingDocuments,
        ]);
    }
}
