<?php

namespace App\Services;

use App\Models\commandes;
use Illuminate\Support\Facades\DB;
use Stripe\StripeClient;


class StripeService
{
    public function PayementProcess($id_commande,$stripeToken)
    {
        $commande = commandes::find($id_commande);
        $amount = (int) round($commande->montant() * 100);
        if ($amount <= 0) {
            throw new \RuntimeException('Invalid order total.');
        }

        $session = $this->client()->checkout->sessions->create([
            'customer_email' => $commande->email,
            'client_reference_id' => (string) $commande->id,
            'metadata' => ['order_token' => $commande->trackcode],
            'payment_method_types' => ['card'], // Stripe gère les cartes ici
            'line_items' => [[
                'price_data' => [
                    'currency' => 'aed',
                    'product_data' => [
                        'name' => 'Commande #' . $commande->id, // Description de la commande
                    ],
                    'unit_amount' => $amount,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment', // Mode de paiement unique
            'success_url' => route('payment-success', ['token' => $commande->trackcode]).'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('payment-failure'), // Rediriger en cas d'annulation
        ], ['idempotency_key' => 'order-'.$stripeToken]);
        $commande->paymentRef = $session->id;
        $commande->stripe_amount = $amount;
        $commande->save();
    
      return $session;
    }

    private function client(): StripeClient
    {
        $secret = config('services.stripe.secret');
        if (!$secret) {
            throw new \RuntimeException('Stripe is not configured.');
        }
        return new StripeClient($secret);
    }

    public function retrieve(string $id)
    {
        return $this->client()->checkout->sessions->retrieve($id);
    }

    public function confirm($session): bool
    {
        if ($session->payment_status !== 'paid' || $session->mode !== 'payment') {
            return false;
        }
        return DB::transaction(function () use ($session) {
            $commande = commandes::where('paymentRef', $session->id)->lockForUpdate()->first();
            if (!$commande || (string) $commande->id !== (string) $session->client_reference_id
                || $commande->trackcode !== ($session->metadata->order_token ?? null)
                || $session->currency !== 'aed'
                || (int) $session->amount_total !== (int) $commande->stripe_amount
                || $commande->stripe_paid_at) {
                return false;
            }
            $commande->statut = 'payée';
            $commande->stripe_paid_at = now();
            $commande->save();
            return true;
        });
    }
}
