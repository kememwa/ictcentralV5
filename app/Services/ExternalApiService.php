<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExternalApiService
{
    public function __construct(
        protected TokenService $tokenService,
        protected GetPosOrderService $getPosOrderService,
        protected PushPosOrderService $pushPosOrderService
    ) {}

    public function post(string $endpoint, array $payload)
    {

        Log::channel('acumatica_mpesa')->info('============ SENDING MPESA DETAILS TO ACUMATICA STARTED ============', [
            'Order Number' => $payload['order_number'],
        ]);

        try {

            $token = $this->tokenService->getToken();

            Log::channel('acumatica_mpesa')->info('Token retrieved');

            Log::channel('acumatica_mpesa')->info('Sending payload', [
                'payload' => $payload
            ]);

            $getPosOrderDetail = $this->getPosOrderService->getPosOrder(
                $token,
                $payload['order_number']
            );

            $orderId = $getPosOrderDetail[0]['id'] ?? null;
            $orderPaymentId = $getPosOrderDetail[0]['Payment'][0]['id'] ?? null;

            $pushPosOrderPayload = [
                'id' => $orderId,
                'Payment' => [
                    [
                        'id' => $orderPaymentId,
                        'TransactionCode' => [
                                'value' => $payload['mpesa_receipt_number']
                            ],
                        'ResultDesc' => [
                                'value' => $payload['result_desc']
                            ],
                    ]
                ]
            ];

            Log::channel('acumatica_mpesa')->info('POS order Details received');

            Log::channel('acumatica_mpesa')->info('Starting to push POS order to Acumatica');

            $response = $this->pushPosOrderService->pushPosOrder(
                $token,
                $endpoint,
                $pushPosOrderPayload
            );
        

            Log::channel('acumatica_mpesa')->info('Pushing Data to Acumatica successful', [
                'status' => $response->status(),
            ]);

            return $response->json();

        } catch (\Exception $e) {

            Log::channel('acumatica_mpesa')->error(
                'External API Exception',
                [
                    'message' => $e->getMessage(),
                    'payload' => $payload
                ]
            );

            throw $e;
        }
    }
}