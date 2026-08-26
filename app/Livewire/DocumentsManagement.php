<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Document;

class DocumentsManagement extends Component
{

    public $name;
    public $type;
    public $confirmingDelete = false;
    public $deleteId = null;
    public $documents;

    protected $rules = [
        'name' => 'required|string|max:255',
        'type' => 'required|string|max:50',
    ];

    public function mount()
        {
            $this->loadDocuments();
        }


    public function loadDocuments()
    {
        $this->documents = Document::latest()->get();
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->confirmingDelete = true;
    }

    public function delete()
    {
        Document::findOrFail($this->deleteId)->delete();

        $this->confirmingDelete = false;
        $this->deleteId = null;

        // Refresh the documents list
        $this->loadDocuments();

        $this->dispatch('notify', 
                type: 'success',
                title: 'Document Deleted',
                message: "Document Deleted successfully."
        );
    }

    public function addDocument()
    {
        $this->validate();

        Document::create([
            'name' => $this->name,
            'type' => $this->type,
        ]);

        // Reset fields
        $this->reset(['name', 'type']);

        // Refresh the documents list
        $this->loadDocuments();

        // Optional success message
        $this->dispatch('notify', 
                type: 'success',
                title: 'Document Added',
                message: "Document Added successfully."
        );
    }


    #[Layout('layouts.dashboard')]
    public function render()
    {
        return view('livewire.documents-management', [
            'documents' => Document::all()
        ]);
    }
}
