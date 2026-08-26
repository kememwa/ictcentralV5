<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\MpesaTransaction;

class DtcPayment extends Component
{

public $search = '';


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
                            ->paginate(6),
        ]);
    }
}
