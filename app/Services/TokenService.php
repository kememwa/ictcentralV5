<?php

namespace App\Services;

use App\Models\ApiToken;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class TokenService
{
    public function getToken()
    {
        Log::channel('acumatica_mpesa')->info('Checking token');

        $token = ApiToken::first();

        if (
            !$token ||
            now()->gte($token->expires_at)
        ) {

            Log::channel('acumatica_mpesa')->warning(
                'Token missing or expired'
            );

            return $this->refreshToken();
        }

        Log::channel('acumatica_mpesa')->info('Using cached token');

        return $token->access_token;
    }

    public function refreshToken()
    {
        Log::channel('acumatica_mpesa')->info('Requesting new token');

        $response = Http::asForm()->post(
            config('services.acumatica_mpesa.login_url'),
            [
                'grant_type'    => 'password',
                'client_id'     => config('services.acumatica_mpesa.client_id'),
                'client_secret' => config('services.acumatica_mpesa.client_secret'),
                'username'      => config('services.acumatica_mpesa.username'),
                'password'      => config('services.acumatica_mpesa.password'),
                'scope'         => 'api',
            ]
        );

        Log::channel('acumatica_mpesa')->info('Login response', [
            'status' => $response->status()
        ]);

        if (!$response->successful()) {

            Log::channel('acumatica_mpesa')->error('Token refresh failed', [
                'response' => $response->body()
            ]);

            throw new \Exception(
                'Unable to obtain token'
            );
        }

        $token = $response->json('access_token');

        ApiToken::updateOrCreate(
            ['id' => 1],
            [   
                'service_name' => 'AcumaticaMpesa',
                'access_token' => $token,
                'expires_at' => now()->addMinutes(58)
            ]
        );

        Log::channel('acumatica_mpesa')->info('Token refreshed successfully');

        return $token;
    }
}