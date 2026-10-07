<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Throwable;

class PreventDuplicateCheckout
{
    public function handle(Request $request, Closure $next)
    {
        $request->validate(['checkout_token' => 'required|uuid']);

        // Both payment methods share one key. A repeated POST reuses its result.
        $id = hash('sha256', $request->session()->getId().'|'.$request->input('checkout_token'));
        DB::beginTransaction();

        try {
            // The primary key serializes concurrent submissions at database level.
            DB::table('checkout_submissions')->insertOrIgnore([
                'id' => $id, 'created_at' => now(), 'updated_at' => now(),
            ]);
            $submission = DB::table('checkout_submissions')->where('id', $id)->lockForUpdate()->first();

            if ($submission->redirect_url !== null) {
                DB::commit();

                // A concurrent request may have loaded the old session before the first completed.
                if ($submission->clear_cart && $request->session()->get('checkout_token') === $request->input('checkout_token')) {
                    $request->session()->forget([
                        'panier_front', 'coupon', 'valeurCoupon', 'total_prix', 'total_points_achat',
                        'checkout_token', 'checkout_basket',
                    ]);
                }

                return redirect()->to($submission->redirect_url, $submission->status);
            }

            if ($request->session()->get('checkout_token') !== $request->input('checkout_token')) {
                throw ValidationException::withMessages([
                    'checkout_token' => 'Votre panier a changé. Rechargez la page avant de confirmer la commande.',
                ]);
            }

            $response = $next($request);

            if (!$request->attributes->get('checkout_completed') || !$response instanceof RedirectResponse) {
                // Validation/business failures must not leave a partial order behind.
                DB::rollBack();

                return $response;
            }

            DB::table('checkout_submissions')->where('id', $id)->update([
                'redirect_url' => $response->getTargetUrl(),
                'status' => $response->getStatusCode(),
                'clear_cart' => !$request->session()->has('panier_front'),
                'updated_at' => now(),
            ]);
            DB::commit();

            return $response;
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
