<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle Commande - {{ config('app.name') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fff; /* Fond global blanc */
            color: #333;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff; /* Fond blanc */
            border: 2px solid #ffc0cb; /* Bordure rose */
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .header {
            background-color: #ffc0cb; /* Rose clair */
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        .header img {
            max-width: 150px;
        }

        .content {
            padding: 20px;
            background-color: #ffffff; /* Contenu blanc */
            color: #333;
        }

        .footer {
            background-color: #ffc0cb; /* Rose clair */
            color: #fff;
            padding: 10px;
            text-align: center;
            font-size: 14px;
        }

        /* Optionnel : pour ajouter une touche douce aux boutons */
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #ffc0cb;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }

        .btn:hover {
            background-color: #ffb6c1;
        }
    </style>

</head>

<body>
<div class="email-container">
    <div class="header">
        <img src="{{ url('/icons/L8s5vItyfLF8pq2BEUEUfcYURb6NWBMDHL36J2r8.png') }}" alt="{{ config('app.name') }} Logo">
        <h1>Merci pour votre commande</h1>
        <h4>
            Référence : {{ $commande->id }}
        </h4>
    </div>
    <div class="content">
        <p>Bonjour {{ $commande->prenom }},</p>
        <p>Merci pour votre commande chez {{ config('app.name') }}. Nous préparons votre commande avec soin et vous
            tiendrons informé dès qu'elle sera expédiée.</p>
        <p>Détails de la commande :</p>
        <ul>
            @php
                $total = $commande->frais + $commande->timbre;
                $hasPoints = false;
                $total_points = 0;
            @endphp
            @foreach ($commande->contenus as $item)
                @php
                    $produit = $item->produit;

                    $points = $produit->points_vente ?? 0;
                    $points_total = $points * $item->quantite;
                    $total_points += $points_total;
                           $total += $item->prix_unitaire * $item->quantite;
            if (!empty($item->produit->points_vente) && $item->produit->points_vente > 0) {
                $hasPoints = true;
            }
                @endphp
                <li>
                    {{ $item->produit->nom }} ( x {{ $item->quantite }} ) :
                    {{ $item->prix_unitaire * $item->quantite }}

                    {{ in_array($commande->devise, ['DT', 'AED'], true) ? 'AED' : '€' }}
                    <br>
                    @if($item->produit->points_vente > 0)
                    <small>
                        🎁 Points fidélité : <b>{{  $points * $item->quantite }}</b>
                    </small>
                    @endif
                </li>

            @endforeach
        </ul>
        <p><b>Frais de livraison</b> : {{ $config->frais }}
            {{ in_array($commande->devise, ['DT', 'AED'], true) ? 'AED' : '€' }} </p>
        {{--  <p><b>Timbre</b> : {{ $commande->timbre }}
              {{ in_array($commande->devise, ['DT', 'AED'], true) ? 'AED' : '€' }} </p>--}}
        <p><b>Total</b> : {{ $total+$config->frais }}
            {{ in_array($commande->devise, ['DT', 'AED'], true) ? 'AED' : '€' }} </p>
        {{-- 🔹 Message si la commande contient des produits avec points --}}

            @if ($total_points > 0)
                <div style="background-color:#f8f9fa; border-left:4px solid #28a745; padding:10px; margin-top:10px;">
                    <b>🎉 Vous allez gagner {{ $total_points }} points de fidélité</b>
                    qui seront ajoutés à votre compte après la livraison.
                </div>
            @endif

        <p>{{ $commande->adresse ?? '' }}</p>
        <center>
            <p>Merci et à bientôt sur {{ config('app.name') }}.</p>
        </center>
    </div>
    <div class="footer">
        <p>&copy; 2025 {{ config('app.name') }}. Tous droits réservés.</p>
        </div>
    </div>
</body>

</html>
