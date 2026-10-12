<?php

namespace App\Http\Controllers;

use App\Mail\Commande;
use App\Models\commandes;
use App\Models\config;
use App\Models\contenu_commande;
use App\Models\Coupon;
use App\Models\notifications;
use App\Models\produits;
use Exception;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Throwable;
use App\Services\JaxService;
use App\Services\KonnectService;
use App\Services\StripeService;
use App\Services\LightSpeedService;
class PayementController extends Controller
{

    public $user;

    public function __construct(Request $request)
    {
        $this->user = Auth::check() ? Auth::user() : $this->createGuestUser($request);
    }


    private function createGuestUser($request)
    {
        return (object) [
            'nom' => $request->nom,
            'email' => $request->email,
            'phone' => $request->telephone,
            'adresse' => $request->adresse,
            'zipcode' => $request->zipcode,
            'city' => $request->city,
        ];
    }



    public function commander(Request $request)
    {
        //validation input
        $this->validate($request, [
            'adresse' => 'required|string',
            'code_postal' => 'nullable|string',
            'nom' => 'required|string',
            'note' => 'nullable|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'city' => ['required', 'string', \Illuminate\Validation\Rule::in(config('locations.emirates'))],
            'coupon' => 'nullable|string',
        ]);

        if (Auth::check()) {
            $user = Auth::user();
        } else {
            $user = $this->user;
        }

        $config = config::first();
        $panier_temporaire = session('panier_front') ?? [];
        if (count($panier_temporaire) <= 0) {
            // Si le panier est vide, on redirige vers la page de checkout
            return redirect()
                ->route('error-page')
                ->with('error', "Your cart is empty. Add products before placing an order.");
        }
        //Enregistrer la commande
        $commande = new commandes();
        $commande->adresse = $request->adresse;
        $commande->trackcode = strtolower(str()->random(45));
        $commande->nom = $request->nom;
        $commande->note = $request->note;
        $commande->email = $request->email;
        $commande->phone = $request->phone;
        $commande->city=$request->city;
        $commande->postalcode=$request->code_postal;
        $commande->id_user = $user->id ?? null;
        $commande->by = $user->id ?? null;
        $total_points_vente =0;
        if ($commande->save()) {
            foreach ($panier_temporaire as $key => $item) {
                $produit = produits::find($item['id_produit']);
                if ($produit) {
                    $contenu_commande = new contenu_commande();
                    $contenu_commande->id_commande = $commande->id;
                    $contenu_commande->id_produit = $produit->id;
                    $contenu_commande->quantite = $item['quantite'];
                    $contenu_commande->prix_unitaire = $produit->getPrice();
                    $contenu_commande->benefice = $produit->getPrice()  * $item['quantite'];
                    $contenu_commande->prix_total = $produit->getPrice()  * $item['quantite']+ $config->frais;
                    $contenu_commande->save();
                    $total_points_vente+=$produit->points_vente;
                }
            }

            if ($commande->montant() <= 0) {
                $commande->delete();
                return redirect()
                    ->route('error-page')
                    ->with("error", "An order cannot be placed with a zero total!");
            }

            if($request->coupon){
                $coupon = Coupon::where('code', $request->coupon)->first();
                if($coupon){
                    //verifier si le coupon est valide
                    if($coupon->valid_until > now()){
                        $valeurCoupon = $commande->montant() * $coupon->pourcentage / 100;
                        $commande->reduction = $valeurCoupon;
                        $commande->save();
                    }
                }
            }
            // 🔹 Paiement avec points (total ou partiel)
            $points_utilises = (int) $request->input('points_utilises', 0);

            if ($points_utilises > 0 && $user) {

                // Total de points requis pour payer tout le panier
                $total_points_achat = 0;
                foreach ($panier_temporaire as $pan) {
                    $produit = produits::find($pan['id_produit']);
                    if ($produit && $produit->points_achat) {
                        $total_points_achat += $produit->points_achat * $pan['quantite'];
                    }
                }

                // Sécurité : l’utilisateur a-t-il assez de points ?
                if ($user->points < $points_utilises) {
                    return redirect()
                        ->route('error-page')
                        ->with('error', "Vous n’avez pas assez de points.");
                }
                $config= config::first();
                // 🔥 Cas 1 : paiement 100% en points
                if ($points_utilises >= $total_points_achat) {

                    $user->points -= $total_points_achat;
                    $user->save();
                    $commande->montant_restant=$config->frais;
                    $commande->points_utilises=$total_points_achat;
                    $commande->is_point = true;
                   // $commande->montant_paye = 0;
                    $commande->save();
                }

                // 🔥 Cas 2 : paiement mixte (points + DT)
                else {

                    // Valeur monétaire des points utilisés
                    // ⚠️ règle : 1 point = 1 DT (à adapter si besoin)
                    $valeur_points = $points_utilises;

                    // Déduire les points
                    $user->points -= $points_utilises;
                    $user->save();
                    $total_prix = session('total_prix'); // en DT
                    $valeurCoupon = session('valeurCoupon', 0);
                    $total_prix = $total_prix - $valeurCoupon;
                    $montant_restant = max(0, $total_prix - $valeur_points);
                    $commande->points_utilises=$valeur_points;
                    $commande->montant_restant=$montant_restant;
                    // Réduction équivalente en DT
                    //$commande->reduction += $valeur_points;
                    $commande->is_point = true;
                    $commande->save();
                }
            }
            $this->make_notification($commande);
            /*$user->points += $total_points_vente;
            $user->save();*/
            //supprimer le panier
            session()->forget('panier_front');
            session()->forget('coupon');
            session()->forget('valeurCoupon');
            session()->forget('total_prix');
            session()->forget('total_points_achat');
            // ----------------------------
            // 1️⃣ Authentification API
            // ----------------------------




            $request->attributes->set('checkout_completed', true);
            session()->forget(['checkout_token', 'checkout_basket']);
            return redirect()->route('after_commande')->with('success', "Your order has been paid!");
        } else {
            return redirect()
                ->route('error-page')
                ->with("error", "Unable to create your order");
        }
    }


    public function commander_payment(Request $request, StripeService $stripe)
    {
        if ((int) $request->input('points_utilises', 0) > 0) {
            return redirect()->route('checkout')->withErrors([
                'payment' => 'Stripe payment cannot be combined with loyalty points. Choose cash on delivery or checkout without points.',
            ]);
        }
        //validation input
        $this->validate($request, [
            'adresse' => 'required|string',
            'zipcode' => 'nullable|string',
            'nom' => 'required|string',
            'note' => 'nullable|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'city' => ['required', 'string', \Illuminate\Validation\Rule::in(config('locations.emirates'))],
            'coupon' => 'nullable|string',
        ]);

        if (Auth::check()) {
            $user = Auth::user();
        } else {
            $user = $this->user;
        }

        $config = config::first();
        $panier_temporaire = session('panier_front') ?? [];
        if (count($panier_temporaire) <= 0) {
            // Si le panier est vide, on redirige vers la page de checkout
            return redirect()
                ->route('error-page')
                ->with('error', "Your cart is empty. Add products before placing an order.");
        }
        //Enregistrer la commande
        $commande = new commandes();
        $commande->adresse = $request->adresse;
        $commande->trackcode = strtolower(str()->random(45));
        $commande->nom = $request->nom;
        $commande->note = $request->note;
        $commande->email = $request->email;
        $commande->phone = $request->phone;
        $commande->city=$request->city;
        $commande->postalcode=$request->code_postal;
        $commande->id_user = $user->id ?? null;
        $commande->by = $user->id ?? null;
        $total_points_vente =0;
        if ($commande->save()) {
            foreach ($panier_temporaire as $key => $item) {
                $produit = produits::find($item['id_produit']);
                if ($produit) {
                    $contenu_commande = new contenu_commande();
                    $contenu_commande->id_commande = $commande->id;
                    $contenu_commande->id_produit = $produit->id;
                    $contenu_commande->quantite = $item['quantite'];
                    $contenu_commande->prix_unitaire = $produit->getPrice();
                    $contenu_commande->benefice = $produit->getPrice()  * $item['quantite'];
                    $contenu_commande->prix_total = $produit->getPrice()  * $item['quantite']+ $config->frais;
                    $contenu_commande->save();
                    $total_points_vente+=$produit->points_vente;

                }
            }

            if ($commande->montant() <= 0) {
                $commande->delete();
                return redirect()
                    ->route('error-page')
                    ->with("error", "An order cannot be placed with a zero total!");
            }

            if($request->coupon){
                $coupon = Coupon::where('code', $request->coupon)->first();
                if($coupon){
                    //verifier si le coupon est valide
                    if($coupon->valid_until > now()){
                        $valeurCoupon = $commande->montant() * $coupon->pourcentage / 100;
                        $commande->reduction = $valeurCoupon;
                        $commande->save();
                    }
                }
            }
            $token = $commande->trackcode; // ou un token unique
            try {
                $stripeSession = $stripe->PayementProcess($commande->id, $token);
                if (!$stripeSession->url) {
                    throw new \RuntimeException('Stripe checkout URL is missing.');
                }
            } catch (Throwable $e) {
                Log::error('Stripe checkout could not be created.', ['order_id' => $commande->id, 'error' => $e->getMessage()]);
                return redirect()->route('checkout')->withErrors(['payment' => 'Online payment is temporarily unavailable. Please try again.']);
            }
            session(['stripe_checkout_token' => $request->input('checkout_token')]);
            $request->attributes->set('checkout_completed', true);
            return redirect()->away($stripeSession->url);
        } else {
            return redirect()
                ->route('error-page')
                ->with("error", "Unable to create your order");
        }
    }

    public function make_notification($commande)
    {
        $this->config = config::first();

        //generer la notification
        $notif = new notifications();
        $notif->type = "commande";
        $notif->titre = $commande->nom;
        $notif->url = "/admin/commande/" . $commande->id;
        $notif->message = "Nouvelle commande";
        $notif->save();

        //envoyer le mail de confirmation
        try {
            $sent = Mail::to($commande->email)->send(new Commande($commande->id, $this->config));
            if (!$sent) {
                throw new \RuntimeException('Mail transport did not send the order confirmation email.');
            }

            return true;
        } catch (\Throwable $e) {
            Log::error('Order confirmation email could not be sent.', [
                'order_id' => $commande->id,
                'error' => $e->getMessage(),
            ]);
            session()->flash('warning', 'Your order has been saved, but the confirmation email could not be sent.');

            return false;
        }
    }




    public function payment_success(Request $request, $token, StripeService $stripe)
    {
        // retrouver la commande
        $commande = commandes::where('trackcode', $token)->firstOrFail();
        if (!$request->query('session_id') || $commande->paymentRef !== $request->query('session_id')) {
            return redirect()->route('payment-failure');
        }
        try {
            $stripeSession = $stripe->retrieve($commande->paymentRef);
            if ($stripe->confirm($stripeSession)) {
                $this->make_notification($commande);
            }
        } catch (Throwable $e) {
            Log::error('Stripe payment verification failed.', ['order_id' => $commande->id]);
            return redirect()->route('payment-failure');
        }
        $commande->refresh();
        if (!$commande->stripe_paid_at) {
            return redirect()->route('payment-failure');
        }
        if (session('stripe_checkout_token') && session('stripe_checkout_token') === session('checkout_token')) {
            session()->forget(['panier_front', 'coupon', 'valeurCoupon', 'total_prix', 'total_points_achat', 'checkout_token', 'checkout_basket', 'stripe_checkout_token']);
        }

        return view('front.payment-success', [
            'commande' => $commande,
            'message' => "✅ Your payment was successful!"
        ]);
    }

    public function payment_failure(Request $request)
    {
        $paymentRef = $request->query('payment_ref'); // récupère payment_ref envoyé par Konnect

        return view('front.payment-failure', [
            'payment_ref' => $paymentRef,
            'message' => "❌ Your payment failed. Please try again."
        ]);
    }

    public function stripeWebhook(Request $request, StripeService $stripe)
    {
        $secret = config('services.stripe.webhook_secret');
        abort_unless($secret, 503);
        try {
            $event = \Stripe\Webhook::constructEvent($request->getContent(), $request->header('Stripe-Signature', ''), $secret);
        } catch (\UnexpectedValueException | \Stripe\Exception\SignatureVerificationException $e) {
            return response('Invalid signature', 400);
        }
        if (in_array($event->type, ['checkout.session.completed', 'checkout.session.async_payment_succeeded'], true)) {
            $stripeSession = $event->data->object;
            if ($stripe->confirm($stripeSession)) {
                $this->make_notification(commandes::where('paymentRef', $stripeSession->id)->firstOrFail());
            }
        }
        return response('OK');
    }

}
