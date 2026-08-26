<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PushPosOrderService{

    public function __construct(
        protected TokenService $tokenService
    ) {}
    
    public function pushPosOrder(string $token, string $endpoint, array $payload)
    {
      
            Log::channel('acumatica_mpesa')->info('Pushing POS order to Acumatica STARTED..', [
                'endpoint' => $endpoint,
                'payload' => $payload
            ]);

            $response = $this->makeRequest($token, $endpoint, $payload);

            if ($response->status() === 401) {

                Log::channel('acumatica_mpesa')->warning(
                    'Token expired. Refreshing token.'
                );

                $token = $this->tokenService->refreshToken();

                $response = $this->makeRequest($token, $endpoint, $payload);
            }

            if (! $response->successful()) {

                Log::channel('acumatica_mpesa')->error(
                    'Failed to push POS order to Acumatica..',
                    [
                        'status' => $response->status(),
                        'body' => $response->body()
                    ]
                );

                throw new Exception(
                    'Failed to push POS order: ' . $response->body()
                );
            }

            Log::channel('acumatica_mpesa')->info('POS order pushed successfully', [
                'status' => $response->status(),
            ]);

            return $response;
    }


    private function makeRequest(string $token, string $endpoint, array $payload)
    {
        return Http::withToken($token)
            ->put(
                config('services.acumatica_mpesa.base_url') . $endpoint,
                $payload
            );
    }
}