<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class RegistrationCaptcha
{
    public function error(string $token, ?string $ip): ?string
    {
        if (!config('captcha.sitekey') || !config('captcha.secret')) {
            Log::warning('Registration reCAPTCHA keys are missing.');
            return 'Anti-bot verification is unavailable. Please contact support.';
        }

        try {
            $response = Http::asForm()->connectTimeout(5)->timeout(15)
                ->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => config('captcha.secret'),
                    'response' => $token,
                    'remoteip' => $ip,
                ])->throw()->json();
        } catch (Throwable $e) {
            Log::warning('Registration reCAPTCHA service unavailable.');
            return 'The anti-bot service is not responding. Please try again.';
        }

        if (($response['success'] ?? false) !== true
            || ($response['action'] ?? null) !== 'register'
            || !is_numeric($response['score'] ?? null)
            || (float) $response['score'] < 0.5) {
            Log::warning('Registration reCAPTCHA rejected.', [
                'codes' => $response['error-codes'] ?? [],
                'action' => $response['action'] ?? null,
                'score' => $response['score'] ?? null,
            ]);
            return 'Anti-bot verification failed. Please try again.';
        }

        return null;
    }
}
