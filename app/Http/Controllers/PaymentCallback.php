<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\MpesaTransaction;
use App\Services\ExternalApiService;

class PaymentCallback extends Controller
{
    public function callback(Request $request)
    {
        $data = $request->all();

        Log::channel('mpesa')->info(
            'MPESA CALLBACK RECEIVED',
            $data
        );

        $callback = $data['Body']['stkCallback'] ?? [];

        $merchantRequestID =
            $callback['MerchantRequestID'] ?? null;

        $checkoutRequestID =
            $callback['CheckoutRequestID'] ?? null;

        $resultCode =
            $callback['ResultCode'] ?? null;

        $resultDesc =
            $callback['ResultDesc'] ?? null;

        /*
        |--------------------------------------------------------------------------
        | Default values
        |--------------------------------------------------------------------------
        */

        $amount = null;
        $mpesaReceiptNumber = null;
        $transactionDate = null;
        $phoneNumber = null;
        $orderNumber = null;
        

        /*
        |--------------------------------------------------------------------------
        | Extract Callback Metadata
        |--------------------------------------------------------------------------
        */

        if (isset($callback['CallbackMetadata']['Item'])) {

            foreach ($callback['CallbackMetadata']['Item'] as $item) {

                $name = $item['Name'];
                $value = $item['Value'] ?? null;

                switch ($name) {

                    case 'Amount':
                        $amount = $value;
                        break;

                    case 'MpesaReceiptNumber':
                        $mpesaReceiptNumber = $value;
                        break;

                    case 'TransactionDate':
                        $transactionDate = $value;
                        break;

                    case 'PhoneNumber':
                        $phoneNumber = $value;
                        break;
                }
            }
        }
        /*
        |--------------------------------------------------------------------------
        | Find Transaction
        |--------------------------------------------------------------------------
        */

        $transaction = MpesaTransaction::where(
            'checkout_request_id',
            $checkoutRequestID
        )->first();

        $statusMap = [
            0    => 'SUCCESS',
            1032 => 'CANCELLED',
            1037 => 'TIMEOUT',
            2001 => 'FAILED',
        ];


        if($transaction && $transaction->status !== 'PENDING') {
            Log::channel('mpesa')->info(
                'Transaction Found and Already Processed',
                [
                    'checkout_request_id' =>
                        $checkoutRequestID
                ]
          
            );

            // return response to Safaricom to acknowledge receipt of the callback
            return response()->json([
            'ResultCode' => 0,
            'ResultDesc' => 'Callback received successfully'
            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Update Transaction if found and send data to Acumatica if status is still PENDING
        |--------------------------------------------------------------------------
        */

        if ($transaction) {

            $transaction->update([


                'mpesa_receipt_number' =>
                    $mpesaReceiptNumber,

                'result_code' =>
                    $resultCode,

                'result_description' =>
                    $resultDesc,

                'callback_payload' =>
                    json_encode($data),

                'status' =>
                    $statusMap[$resultCode] ?? 'FAILED',

                'paid_at' => ($resultCode == 0)
                    ? now()
                    : null,

                'updated_at' =>
                        now(),
                    
            ]);

            Log::channel('mpesa')->info(
                'MPESA TRANSACTION UPDATED',
                [
                    'checkout_request_id' =>
                        $checkoutRequestID,

                    'status' =>
                        $statusMap[$resultCode] ?? 'FAILED',
                ]
            );

        $orderNumber = $data['ordernumber'] ?? null;

        // send data to acumatica
        $AcumaticaPayload = [
            'order_number' => $orderNumber,
            'mpesa_receipt_number' => $mpesaReceiptNumber,
            'result_desc' => $statusMap[$resultCode] ?? 'FAILED',
        ];

        try {

        app(ExternalApiService::class)
            ->post('/POSOrder', $AcumaticaPayload);

        Log::channel('acumatica_mpesa')->info(
            'Transaction Sent to Acumatica',
            [
                'checkout_request_id' => $checkoutRequestID,
            ]
        );

        } catch (\Exception $e) {

            Log::channel('acumatica_mpesa')->error(
                '===== Failed to Send Transaction to Acumatica =====',
                [
                    'checkout_request_id' => $checkoutRequestID,
                    'error' => $e->getMessage(),
                ]
            );
        }

        } else {

            Log::channel('mpesa')->warning(
                'TRANSACTION NOT FOUND',
                [
                    'checkout_request_id' =>
                        $checkoutRequestID
                ]
            );
        }       

        
        /*
        |--------------------------------------------------------------------------
        | Response To Safaricom
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'ResultCode' => 0,
            'ResultDesc' => 'Callback received successfully'
        ]);
    }
}
