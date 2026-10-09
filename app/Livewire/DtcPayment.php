<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\MpesaTransaction;
use Livewire\WithPagination;

class DtcPayment extends Component
{
    use WithPagination;

public $search = '';
public $actionFilter = '';

public function updatedSearch()
{
    $this->resetPage();
}

public function actionFilter()
{
    $this->resetPage();
}


    #[layout('layouts.dashboard')]
    public function render()
    {
        return view('livewire.dtc-payment',[
            'mpesa_transactions' => MpesaTransaction::where('order_number', 'like', '%' . $this->search . '%')
                               ->orWhere('phone_number', 'like', '%' . $this->search . '%')
                            ->orWhere('amount', 'like', '%' . $this->search . '%')
                            ->orWhere('mpesa_receipt_number', 'like', '%' . $this->search . '%')
                            ->orWhere('status', 'like', '%' . $this->search . '%')
                              ->latest()
                            ->paginate(7),
        ]);
    }
}
