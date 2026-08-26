<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\MpesaTransaction;

class PaymentController extends Controller
{
    public function index(Request $request)
    {

    $phone_number = $request->phonenumber;
    $amount = $request->amount;
    $order_number = $request->ordernumber;

  
    if(ISSET($order_number)){
        
        $inputphone = $phone_number;
        $phonenumber = "254" . substr($inputphone, 1);

        $amount = $amount;
        $intNum = round($amount,0);//intval($amount);
        $ordernumber = $order_number;


        $consumerKey = config('services.mpesa.consumer_key');
        $consumerSecret = config('services.mpesa.consumer_secret');

        $headers = ['Content-type: application/json; charset=utf-8'];
        
        $access_token_url = 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $access_token_url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_HEADER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_USERPWD, $consumerKey.':'.$consumerSecret);
        $result = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $result = json_decode($result);
        $access_token = $result->access_token;
        curl_close($curl);
    
        
        $initiate_url = 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
        $BusinessShortCode = config('services.mpesa.shortcode');
        $Timestamp = date('YmdHis');
        $PartyA = $phonenumber;
    
        $CallBackURL =  'https://zodiac-evergreen-anyway.ngrok-free.dev/api/payments/callback?ordernumber='.$ordernumber;
        $AccountReference = $ordernumber;
        $TransactionDesc = 'Lipa Na Mpesa Online';
        $Amount = $intNum;
        $Passkey = config('services.mpesa.passkey');
        $Password = base64_encode($BusinessShortCode.$Passkey.$Timestamp);


        $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $initiate_url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$access_token)); //setting custom header
            $curl_post_data = array(
        
            'BusinessShortCode' => $BusinessShortCode,
            'Password' => $Password,
            'Timestamp' => $Timestamp,
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => $Amount,
            'PartyA' => $PartyA,
            'PartyB' => $BusinessShortCode,
            'PhoneNumber' => $PartyA,
            'CallBackURL' => $CallBackURL,
            'AccountReference' => $AccountReference,
            'TransactionDesc' => $TransactionDesc
            );
            
            $data_string = json_encode($curl_post_data);
            
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);


            $curl_response = curl_exec($curl);

            $response = json_decode($curl_response, true);

            $merchantRequestID =
            $response['MerchantRequestID'] ?? null;

            $checkoutRequestID =
            $response['CheckoutRequestID'] ?? null;

            $responseDescription =
            $response['ResponseDescription'] ?? null;

            $responseCode =
            $response['ResponseCode'] ?? null;

            //create the transaction record in the database
            MpesaTransaction::create([
                'phone_number' => $PartyA,
                'amount' => $Amount,
                'order_number' => $ordernumber,
                'status' => 'PENDING',
                'merchant_request_id' => $merchantRequestID,
                'checkout_request_id' => $checkoutRequestID,
                'initial_description' => $responseDescription,
                'initial_response_code' => $responseCode,
                'stk_request_payload' => $data_string,
                'stk_response_payload' => $curl_response,  
                'initial_transaction_date' => $Timestamp,
                'created_at' => now(),
            ]);
    


            // log the STK push request details
            Log::channel('mpesa')->info(
                'STK Push Request',
                [
                    'phone' => $PartyA,
                    'amount' => $Amount,
                    'order_number' => $ordernumber,
                    'timestamp' => $Timestamp,
                    'merchantRequestID' => $merchantRequestID,
                    'checkoutRequestID' => $checkoutRequestID,
                    'responseCode' => $responseCode
                ]
            );


            return response()->json(
                json_decode($curl_response),
                200
            );
        }

}
}
