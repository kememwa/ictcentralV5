<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MpesaTransaction extends Model
{
       protected $fillable = [

        'phone_number',
        'order_number',
        'amount',
        'status',

        'merchant_request_id',
        'checkout_request_id',
        'initial_description',
        'initial_response_code',

        'mpesa_receipt_number',
        'initial_transaction_date',

        'result_code',
        'result_description',

        'stk_request_payload',
        'stk_response_payload',
        'callback_payload',

        'paid_at',
    ];

    protected $casts = [
    'paid_at' => 'datetime',
];
}
