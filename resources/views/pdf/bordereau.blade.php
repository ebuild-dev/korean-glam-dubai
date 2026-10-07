@php
    $config = DB::table('configs')->select('icon', 'logo','matricule')->first();
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>borderau des commandes</title>
    <style>
        body {
            font-family: Arial, sans-serif, 'bangla';
        }

        .table,
        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
        }

        table {
            width: 100% !important;
            text-align: left !important
        }

        thead {
            background-color: #416f10 !important;
            color: white !important;
        }
    </style>
</head>

<body>
    @foreach ($ids as $id_item)
        @php
            $commande = App\Models\commandes::find($id_item);
        @endphp
        @if ($commande)
            <div style="page-break-after: always;">
                <center>
                    <img src="{{ config('app.app_url') }}{{ asset(Storage::url($config->icon)) }}" alt="logo"
                        height="70" srcset="">
                </center>
                <h2>Commande No : #{{ $commande->id }}</h2>
                <div>
                    <br>
                    <center>
                        <h3>Informations sur le client</h3>
                    </center>
                    <table>
                        <tr>
                            <td>
                                <b>Nom : </b> {{ $commande->nom ?? '/' }} <br>
                                <b>Prénom :</b> {{ $commande->prenom ?? '/' }} <br>
                                <b>Téléphone :</b> {{ $commande->phone ?? '/' }} <br>
                                <b>Email :</b> {{ $commande->email ?? '/' }}
                            </td>
                            <td>
                                <b>Adresee :</b> {{ $commande->adresse ?? '/' }}<br>
                                <b>Pays :</b> {{ $commande->pays ?? '/' }}<br>
                                @if ($commande->gouvernorat)
                                   <b>Gouvernorat :</b> {{ $commande->gouvernorat->nom ?? '/' }}  
                                @endif
                               
                            </td>
                        </tr>
                    </table>
                </div>
                <br>
                <center>
                    <h3>Informations de la commande</h3>
                </center>
                <div>
                    <b>Date de reception de la commande : </b> {{ $commande->created_at ?? '/' }}
                </div>
                <center>
                    <h3>
                        Contenu de la commande
                    </h3>
                </center>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Référence</th>
                            <th>Article</th>
                            <th>Prix u</th>
                            <th>Quantité</th>
                            <th>Montant</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($commande->contenus as $article)
                            <tr>
                                <td>{{ $article->produit->reference }}</td>
                                <td>{{ $article->produit->nom }}</td>
                                <td>{{ $article->prix_unitaire }} <x-devise></x-devise></td>
                                <td>x{{ $article->quantite }}</td>
                                <td>{{ $article->prix_unitaire * $article->quantite }} <x-devise></x-devise></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <br>
                <div>
                    <b>Frais de livraison :</b> {{ $commande->frais ?? 0 }} <x-devise></x-devise> <br>
                    <b>Montant a payer :</b> {{ $commande->montant() ?? '/' }} <x-devise></x-devise>
                </div>
                <br><br><br>
                <i>
                    Merci pour votre commande !
                </i>
            </div>
        @endif
    @endforeach
</body>

</html>
