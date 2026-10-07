<?php

namespace App\Services;

use App\Models\commandes;
use App\Models\config;
use GuzzleHttp\Client;

class KonnectService
{
    public  $konnect_wallet, $konnect_key;

    public function __construct()
    {
        $this->konnect_key = config("app.konnect_key");
        $this->konnect_wallet = config("app.konnect_wallet");
    }
    public function PayementProcess($id_commande, $token)
    {
        $config = config::first();

        $commande = commandes::find($id_commande);
        $client = new Client();
        if ($commande->devise == "DT") {
            $devise = "TND";
            $montant = intval($commande->montant_with_coupon()) * 1000;
        } else {
            $devise = "EUR";
            $montant = intval($commande->montant_with_coupon()) * 100;
        }

        $response = $client->request('POST', "https://api.konnect.network/api/v2/payments/init-payment", [
            'headers' => [
                'Content-Type' => 'application/json',
                'x-api-key' => $this->konnect_key,
            ],
            'json' => [
                "receiverWalletId" => $this->konnect_wallet,
                "token" => $devise,
                "amount" => $montant,
                "type" => "immediate",
                "acceptedPaymentMethods" => ["wallet", "bank_card", "e-DINAR"],
                "lifespan" => 10,
                "checkoutForm" => false,
                "addPaymentFeesToAmount" => true,
                "firstName" => $commande->nom,
                "phoneNumber" => $commande->phone,
                "email" => $commande->email,
                "orderId" => $commande->id,
                "webhook" => config("app.url"),
                "silentWebhook" => true,
                "successUrl" =>  route("payment-success", ['token' => $token]),
                "failUrl" =>  route("payment-failure"),
                "theme" => "light",
            ],
        ]);
        return $response;
    }
}
