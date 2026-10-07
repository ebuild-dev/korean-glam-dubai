<?php

namespace App\Http\Controllers;

use App\Models\commandes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    // Initier un paiement
    public function init()
    {
        $response = Http::withHeaders([
            'x-api-key' => env('KONNECT_API_KEY'),
        ])->post('https://api.konnect.network/api/v2/payments/init-payment', [
            'receiverWalletId' => env('KONNECT_WALLET_ID'),
            'amount' => 1000, // 1000 millimes = 1 TND
            'type' => 'immediate',
            'currency' => 'TND',
            'successUrl' => route('payment.success'),
            'failUrl'   => route('payment.fail'),
        ]);

        $data = $response->json();

        if (isset($data['payUrl'])) {
            return redirect($data['payUrl']);
        }

        return back()->with('error', 'Unable to initiate payment');
    }

    // Callback succès
    public function success($token)
    {
        // retrouver la commande
        $commande = commandes::where('trackcode', $token)->first();

        if ($commande) {
            $commande->status = 'payé';
            $commande->save();
        }

        return view('payment.success');
    }

    // Callback échec
    public function fail(Request $request)
    {
        return "❌ Paiement échoué.";
    }
}
