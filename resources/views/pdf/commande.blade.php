@php
    $config = DB::table('configs')->select('icon', 'logo', 'adresse', 'telephone', 'matricule')->first();
@endphp

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture</title>
    <style>
        .text-center {
            text-align: center;
        }

        body {
            font-family: Arial, sans-serif;
        }

        .text-end {
            text-align: right !important;
        }

        .container {
            padding: 20px;
        }

        .table {
            width: 100%;
        }



        .div-infos {
            width: auto;
            border: solid 1px black;
            padding: 10px;
            display: inline-block;
            margin-bottom: 60px;
        }

        .titre {
            font-weight: 800;
        }

        .titre-div {
            margin-bottom: 60px;
        }

        .direction {
            margin-top: 60px;
            text-align: right;
            padding-right: 60px;
        }

        .footer {
            text-align: center;
            width: 100%;
            border-top: solid 1px black;
            padding: 10px;
            margin-top: 60px;
            bottom: 0px;
            position: fixed;
        }

        .no-border {
            border-collapse: collapse;
            border: none;
        }

        .header-tb {
            width: 100% !important;
        }

        .div-border {
            border: solid 1px black;
            padding: 05px;
        }

        table,
        th,
        td {
            border-collapse: collapse;
        }
    </style>
</head>

<body>
    <div class="container">
        <table class="header-tb">
            <tr>
                <td>
                    @if ($config->logo)
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path(Storage::url($config->logo)))) }}"
                    height="50" alt="logo" srcset="">
                    @endif
                </td>
                <td>
                    <div class="text-end">
                        Tunis le {{ $commande->created_at->format('d-m-Y') }}
                    </div>
                </td>
            </tr>
        </table>

        <div class="text-center titre-div">
            <h2>
                <b class="titre">
                    Facture N<sup>o</sup> 00000{{ $commande->id }}
                </b>
            </h2>
        </div>
        <div class="div-infos">
            Client(e) : {{ $commande->nom }} {{ $commande->prenom ?? '' }}<br>
            Numéro téléphone : {{ $commande->phone }} <br>
            Adresse : {{ $commande->adresse }} , {{ $commande->gouvernorat->nom ?? '' }}<br>
            ID de la commande : {{ $commande->id }} <br>
        </div>
        <div>
            <table class="table">
                <tr>
                    <td>
                        <div class="div-border">
                            <b>Nom du Produit</b>
                        </div>
                    </td>
                    <td>
                        <div class="div-border">
                            <b>Unité</b>
                        </div>
                    </td>
                    <td>
                        <div class="div-border">
                            <b>Prix</b>
                        </div>
                    </td>
                </tr>
                @php
                    $prix_ttc = $commande->frais ?? (0 + $commande->timbre ?? 0);
                    $prix_ht = 0;
                    $prix_tva_total = 0;
                @endphp
                @foreach ($commande->contenus as $item)
                    <tr>
                        <td>
                            <div class="div-border">
                                {{ $item->produit->nom }}
                            </div>
                        </td>
                        <td>
                            <div class="div-border">
                                {{ $item->quantite }}
                            </div>
                        </td>
                        <td>
                            <div class="div-border">
                                {{ $item->produit->Get_price_sans_tva($commande->tva, $item->prix_unitaire) * $item->quantite }}
                                <x-devise></x-devise>
                            </div>
                        </td>
                    </tr>
                    @php
                        $prix_ttc += $item->prix_unitaire * $item->quantite;
                        $prix_ht +=
                            $item->produit->Get_price_sans_tva($commande->tva, $item->prix_unitaire) * $item->quantite;
                        $prix_tva_total += $item->produit->Get_valeur_tva($commande->tva, $item->prix_unitaire);
                    @endphp
                @endforeach
                <tr>
                    <td rowspan="6" class="no-border" border="0"></td>
                    <td>
                        <div class="div-border">
                            Sous-Total HT
                        </div>
                    </td>
                    <td>
                        <div class="div-border">
                            {{ $prix_ht }}
                            <x-devise></x-devise>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="div-border">
                            Frais de livraison
                        </div>
                    </td>
                    <td>
                        <div class="div-border">
                            {{ $frais_livraison }}
                            <x-devise></x-devise>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="div-border">
                            Timbre
                        </div>
                    </td>
                    <td>
                        <div class="div-border">
                            {{ $commande->timbre }}
                            <x-devise></x-devise>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="div-border">
                            TVA
                        </div>
                    </td>
                    <td>
                        <div class="div-border">
                            {{ $commande->tva }} % ( {{ $prix_tva_total }} <x-devise></x-devise> )
                        </div>
                    </td>
                </tr>
                @if ($commande->coupon)
                    <tr>
                        <td>
                            <div class="div-border">
                                Coupon ( {{ $commande->coupon->code }} )
                            </div>
                        </td>
                        <td>
                            <div class="div-border">
                                - {{ $commande->coupon->pourcentage }} %
                            </div>
                        </td>
                    </tr>
                @endif
                <tr>
                    <td>
                        <div class="div-border">
                            Total TTC
                        </div>
                    </td>
                    <td>
                        <div class="div-border">
                            @if ($commande->coupon)
                                    <strike>
                                        {{ $prix_ttc +$frais_livraison }}
                                        <x-devise></x-devise>
                                    </strike>
                                <b>
                                    {{ ($prix_ttc - ($prix_ttc * $commande->coupon->pourcentage / 100))+$frais_livraison  }}
                                    <x-devise></x-devise>
                                </b>
                            @elseif($commande->is_point)
                                {{-- 🔹 Si la commande est payée avec des points --}}
                                <b>
                                    {{ $commande->montant_restant }}
                                    <x-devise></x-devise>
                                </b>
                            @else
                                <b>
                                    {{ $prix_ttc +$frais_livraison }}
                                    <x-devise></x-devise>
                                </b>
                            @endif
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="direction">
            <b>Direction</b>
        </div>
        <footer class="footer">
            {{ config('app.name') }} <br> <br>
            {{ $config->adresse ?? '' }} <br>
            Tél : {{ $config->telephone ?? '' }} <br>
            @if ($config->matricule)
                Matricule : {{ $config->matricule }}
            @endif
        </footer>
    </div>
</body>

</html>
