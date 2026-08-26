<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GetPosOrderService
{
    public function __construct(
        protected TokenService $tokenService
    ) {}

    public function getPosOrder(string $token, string $orderNumber)
    {
        Log::channel('acumatica_mpesa')->info(
                'Starting to retrieve POS order from Acumatica..'
            );

        $response = $this->makeRequest($token, $orderNumber);

        if ($response->status() === 401) {

            Log::channel('acumatica_mpesa')->warning(
                'Token expired. Refreshing token.'
            );

            $token = $this->tokenService->refreshToken();

            $response = $this->makeRequest($token, $orderNumber);
        }

        if (! $response->successful()) {

          Log::channel('acumatica_mpesa')->error(
                'Failed to retrieve POS order',
                [
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]
            );

            throw new Exception(
                'Failed to retrieve POS order: ' . $response->body()
            );
        }

        Log::channel('acumatica_mpesa')->info(
            'POS order retrieved successfully',
            [
                'status' => $response->status()
            ]
        );
        return $response->json();
    }

    private function makeRequest(string $token, string $orderNumber)
    {
        return Http::withToken($token)->get(
            config('services.acumatica_mpesa.base_get_pos_url'),
            [
                '$filter' => "OrderNbr eq '{$orderNumber}'",
                '$expand' => 'Details,Payment',
                '$select' => implode(',', [
                    'OrderNbr',
                    'Status',
                    'Currency',
                    'WorkstationInfo',
                    'OrderDate',
                    'OrderQty',
                    'OrderTotal',
                    'Details/InventoryID',
                    'Details/Amount',
                    'Details/UnitPrice',
                    'Details/Quantity',
                    'Details/Warehouse',
                    'Details/TaxCategory',
                    'Payment/BalanceToPay',
                    'Payment/CashAccount',
                    'Payment/PaymentRef',
                    'Payment/PaymentMethod',
                    'Payment/MpesaNumber',
                    'Payment/ResultCode',
                    'Payment/ResultDesc',
                ]),
            ]
        );
    }
}