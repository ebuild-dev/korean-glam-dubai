<?php

namespace App\Services;

use App\Models\commandes;
use App\Models\config;
use Illuminate\Support\Facades\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;


class StripeService
{
    public function PayementProcess($id_commande,$stripeToken)
    {
        $commande = commandes::find($id_commande);
        $STRIPE_SECRET = config("app.stripeSecret");
        \Stripe\Stripe::setApiKey($STRIPE_SECRET);

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'], // Stripe gère les cartes ici
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => 'Commande #' . $commande->id, // Description de la commande
                    ],
                    'unit_amount' => $commande->montant()*100  , // Montant en centimes
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment', // Mode de paiement unique
            'success_url' => route('payment-success', ['token' => $commande->token]), // Rediriger en cas de succès
            'cancel_url' => route('payment-failure'), // Rediriger en cas d'annulation
        ]);
    
      return $session;
    }
}
