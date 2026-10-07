@section('titre', 'Détails de la commande')
@extends('admin.fixe')

@section('body')
    <div class="page-content-wrapper">
        <div class="page-content">

            <!-- start page title -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb ">
                                <li class="breadcrumb-item">
                                    <a href="javascript: void(0);">{{ config('app.name') }}</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ route('commandes') }}">Commandes</a>
                                </li>
                                <li class="breadcrumb-item active">Commande #{{ $commande->id }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->


            <div class="card radius-15">
                <div class="card-body">
                    <div class="card-header">
                        @php
                            $config = \App\Models\config::first();
                        @endphp
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="header-title">
                                    <span class="badge bg-dark cusor" title="Canal de vente">
                                        <i class="ri-bookmark-3-fill"></i>
                                        {{ $commande->canal_vente }}
                                    </span>
                                    <span class="text-capitalize">
                                        {{ $commande->nom }}
                                    </span>
                                </h5>
                            </div>
                            <div>
                                <button class="btn btn-primary btn-sm" type="button"
                                    onclick="url('{{ route('print_commande', ['id' => $commande->id]) }}')">
                                    <i class="ri-printer-line"></i>
                                    Imprimer la facture
                                </button>
                            </div>
                        </div>
                        <br>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-8 table-responsive-sm">
                                <div>
                                    @if ($commande->Auteur)
                                        <p>
                                            Cette commande a été créé par : <b>{{ $commande->Auteur->nom }}</b>
                                        </p>
                                    @endif
                                </div>
                                <table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
                                    <thead class="table-dark">
                                        <tr>
                                            <th></th>
                                            <th>Product</th>
                                            <th>Type</th>
                                            <th>Prix</th>
                                            <th>Qty</th>
                                            <th>Sub-Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($commande->contenus as $contenu)
                                            <tr>

                                                <td style="width: 50px;">
                                                    @if ($contenu->type == 'produit')
                                                        <img src="{{ $contenu->produit->FirstImage() }}" width="40 "
                                                            height="40 " class="rounded shadow" alt="photo">
                                                    @else
                                                        <img src="{{ $contenu->pack->photo() }}" width="40"
                                                            height="40" class="rounded shadow" alt="photo">
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($contenu->type == 'produit')
                                                        {{ $contenu->produit->nom }}
                                                    @else
                                                        {{ $contenu->pack->nom }}
                                                    @endif
                                                </td>
                                                <td>
                                                    <b class="text-capitalize">
                                                        {{ $contenu->type }}
                                                    </b>
                                                </td>
                                                <td>
                                                    @if($commande->is_point)
                                                        {{ $contenu->produit->points_achat }} Points
                                                    @else
                                                    {{ $contenu->prix_unitaire }} {{ in_array($commande->devise, ['DT', 'AED'], true) ? 'AED' : '€' }}
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $contenu->quantite }}
                                                </td>
                                                <td>
                                                    @if($commande->is_point)
                                                        {{ $contenu->quantite * $contenu->produit->points_achat }} Points
                                                    @else
                                                    {{ $contenu->quantite * $contenu->prix_unitaire }} {{ in_array($commande->devise, ['DT', 'AED'], true) ? 'AED' : '€' }}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td>
                                                <img width="30" height="30"
                                                     src="https://img.icons8.com/ios/30/40C057/delivery--v1.png" alt="delivery--v1" />
                                            </td>
                                            <td> <b>Frais de livraison</b> </td>
                                            <td>

                                            </td>
                                            <td>{{ \App\Models\Config::first()->frais ?? 0 }} AED</td>
                                            <td> 1 </td>

                                            <td>
                                                @if($commande->is_point)
                                                    {{ $commande->montant_restant }} {{ $commande->devise === 'DT' ? 'AED' : $commande->devise }}
                                                @else

                                                    {{ $commande->montant_with_coupon() }}</td>


                                            @endif
                                        </tr>
                                    </tbody>
                                </table>
                                <br>
                                <h6 class="card-title mt-3">
                                    <b>Notes</b>
                                </h6>
                                <div>
                                    @if ($commande->note)
                                        {{ $commande->note }}
                                    @else
                                        <i class="text-muted">
                                            <i class="ri-information-line"></i>
                                            Aucune note disponible pour cette commande.
                                        </i>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-4 text-capitalize">
                                <div class="card p-2">

                                    @php
                                        $config = \App\Models\config::first();
                                    @endphp

                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title mb-3">
                                                <b>Résumé de la commande</b>
                                            </h6>

                                            {{-- 🔹 Montant total --}}
                                            @if($commande->is_point)
                                                <b>Montant restant à payer :</b>
                                                <b class="text-success">
                                                   {{ $commande->montant_restant }} {{ in_array($commande->devise, ['DT', 'AED'], true) ? 'AED' : '€' }}
                                                </b>
                                                <b>Paiement par point:</b>
                                                <b class="text-success">
                                                    {{ $commande->points_utilises }} pts
                                                </b>

                                            @else
                                                <b>Montant total :</b>
                                                <b>
                                                    {{ $commande->montant_with_coupon() }}
                                                    {{ in_array($commande->devise, ['DT', 'AED'], true) ? 'AED' : '€' }}
                                                </b>
                                            @endif

                                            {{-- 🔹 Coupon de réduction --}}
                                            @if ($commande->reduction > 0)
                                                <div class="alert alert-info mt-2">
                                                    <b>Coupon de réduction :</b> -{{ $commande->reduction }}
                                                    {{ in_array($commande->devise, ['DT', 'AED'], true) ? 'AED' : '€' }}
                                                </div>
                                            @endif

                                            {{-- 🔹 Frais de livraison --}}
                                            @if ($commande->frais > 0)
                                                <br>
                                                <b>Frais de livraison :</b> {{ $commande->frais ?? 0 }}
                                                {{ in_array($commande->devise, ['DT', 'AED'], true) ? 'AED' : '€' }}
                                            @endif

                                            <br><b>Date :</b> {{ $commande->created_at }}
                                            <br><b>TVA :</b> {{ $commande->tva }} {{ in_array($commande->devise, ['DT', 'AED'], true) ? 'AED' : '€' }}
                                            <br><b>Timbre :</b> {{ $commande->timbre }} {{ in_array($commande->devise, ['DT', 'AED'], true) ? 'AED' : '€' }}
                                            <br><b>Canal de vente :</b> {{ $commande->canal_vente }}
                                            <br>
                                            {{-- 🔹 Indicateur si commande payée par points --}}
                                            <b>Paiement via points :</b>
                                            @if($commande->is_point)
                                                <span class="badge bg-success">Oui</span>
                                            @else
                                                <span class="badge bg-secondary">Non</span>
                                            @endif
                                        </div>

                                        <div class="text-center">
                                            {!! QrCode::size(70)->generate(route('print_commande', ['id' => $commande->id])) !!}
                                        </div>
                                    </div>

                                    <hr>
                                    <h6 class="card-title mt-3">
                                        <b>Informations du client</b>
                                    </h6>
                                    Nom : {{ $commande->nom }} <br>
                                    Phone : {{ $commande->phone }} <br>
                                    Adresse : {{ $commande->adresse }} <br>
                                    City : {{ $commande->city }}
                                </div>
                            </div>
                        </div>
                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div><!-- end col-->
        </div> <!-- end row-->


    </div>
@endsection
