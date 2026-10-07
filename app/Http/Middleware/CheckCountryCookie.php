<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cookie;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Cache;


class CheckCountryCookie
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $cookieName = 'countryName';

        // Vérifier si le cookie existe
        if (!$request->hasCookie($cookieName)) {
            $countryName = Cache::remember("country_for_ip_{$request->ip()}", 60 * 24, function () use ($request) {
                try {
                    $client = new Client();

                    // Obtenir l'adresse IP du client
                    $clientIp = $request->ip();

                    // Utiliser ipinfo.io pour obtenir les détails du pays
                    $response = $client->get("https://ipinfo.io/{$clientIp}/json");
                    $locationData = json_decode($response->getBody(), true);

                    return $locationData['country'] ?? 'Inconnu';
                } catch (RequestException $e) {
                    // Gérer les erreurs de la requête API
                    return 'Inconnu';
                }
            });

            // Créer un cookie avec une durée de vie de 1 an
            $cookie = Cookie::make($cookieName, $countryName, 60 * 24 * 365, null, null, false, false);
            Cookie::queue($cookie);
        }

        return $next($request);
    }
}
