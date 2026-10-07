<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Log;

class LightSpeedService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.lightspeed.base_url'), '/');
    }

    /**
     * Authentification → Récupérer token
     */
    public function authenticate()
    {
        $response = $this->post('/authenticate', [
            "login"    => config('services.lightspeed.login'),
            "password" => config('services.lightspeed.password')
        ]);

        if (!$response || !$response->successful() || $response->json('status') !== "success"
            || !$response->json('type_token') || !$response->json('token')) {
            return false;
        }

        return $response->json()['type_token'] . $response->json()['token'];
    }

    /**
     * Ajouter commande
     */
    public function addOrder($token, array $data)
    {
        $response = $this->post('/AddOrders', [
            "orders" => $data
        ], $token, true);
        return $response;

    }
    public function getStatusCmd($token, string $barcode)
    {
        return $this->post('/GetStatusCmd?Barcode=' . urlencode($barcode), [], $token);
    }

    private function post(string $path, array $data = [], $token = null, bool $asForm = false)
    {
        $request = Http::connectTimeout(5)->timeout(15);

        if ($token !== null) {
            $request = $request->withHeaders(['Authorization' => $token]);
        }
        if ($asForm) {
            $request = $request->asForm();
        }

        try {
            return $request->post($this->baseUrl . $path, $data);
        } catch (ConnectionException $e) {
            Log::warning('LightSpeed connection unavailable', [
                'host' => parse_url($this->baseUrl, PHP_URL_HOST),
            ]);

            return null;
        }
    }

}
