<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetCountryCookie
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

      /*   // Check if the country cookie is already set
        if ($request->hasCookie('countryName')) {
            // Set the cookie if it doesn't exist
            $countryName = 'France'; // You can dynamically set this value as needed
            $cookie = cookie('countryName', $countryName, 60 * 24 * 365); // 1 year in minutes
            $response->headers->setCookie($cookie);
        } */

        return $response;
    }
}
