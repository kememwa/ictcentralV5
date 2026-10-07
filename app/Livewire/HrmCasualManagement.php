<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Requisition;
use Illuminate\Support\Facades\Mail;
use App\Mail\HrmApproveReq;
use Illuminate\Support\Facades\Log;
use Livewire\WithPagination;

class HrmCasualManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $actionFilter = '';


    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function approveHrm($id)
    {
        try {

            $req = Requisition::with('hrRep')->findOrFail($id);

            $req->update([
                'hrm_approval_status' => 'approved',
                'hrm_approval_date' => now(),
                'hrm_id' => auth()->user()->id,
            ]);

            // Get the HR representative who submitted the rate
            $hrUser = $req->hrRep;

            // Send notification back to HR
            if ($hrUser && !empty($hrUser->email)) {

                Mail::to($hrUser->email)
                    ->queue(new HrmApproveReq($req));
            }

            // Flash message for Livewire UI
            $this->dispatch(
                'notify',
                type: 'success',
                title: 'Approved',
                message: 'Requisition approved successfully.'
            );

        } catch (\Throwable $e) {

            Log::error('HRM requisition approval failed', [
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

    #[layout('layouts.dashboard')]
    public function render()
    {
        return view('livewire.hrm-casual-management',[
            'hrm_requisitions' => Requisition::where('hr_approval_status', true)
            ->where('hrm_approval_status', 'pending')
            ->when($this->search, function ($query) {
                $search = '%' . $this->search . '%';

                $query->where(function ($q) use ($search) {
                    $q->where('ref_number', 'like', $search)
                        ->orWhereHas('requester', function ($q) use ($search) {
                            $q->where('name', 'like', $search);
                        });
                });
            })->latest()->paginate(10)
        ]);
    }
}
