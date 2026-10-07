@section('titre', 'Historique du stock')
@extends('admin.fixe')

@section('body')
    <div class="page-content-wrapper">
        <div class="page-content">


            <!-- start page title -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item">
                                    <a href="javascript: void(0);">{{ config('app.name') }}</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ route('produits') }}">Produits</a>
                                </li>
                                <li class="breadcrumb-item active">
                                    Historique du stock {{ $produit->nom }}
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="header-title">
                                Historique du stock.
                            </h5>
                        </div>
                        <div class="card-body">
                            <div>
                                <form action="{{ route('produits.historique', ['id' => $produit->id]) }}" method="get">
                                    @csrf
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="btn-group">
                                                <input type="date" aria-label="First name" class="form-control"
                                                    name="date">
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    Filtrer
                                                </button>
                                            </div>
                                        </div>
                                        <div class="my-auto">
                                        </div>
                                    </div>
                                </form>
                                <br>

                                <div class="table-responsive-sm">

                                    <table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
                                        <thead class="table-dark cusor">
                                            <tr>
                                                <th>
                                                    <span wire:loading>
                                                        <img src="https://i.gifer.com/ZKZg.gif" height="15"
                                                            alt="" srcset="">
                                                    </span>
                                                    Type de mouvement
                                                </th>
                                                <th>Nom</th>
                                                <th>Quantite</th>
                                                <th>Date</th>
                                                <th>Auteur</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($historique_stock as $historique)
                                                <tr>
                                                    <td>
                                                        <span class="text-success">
                                                            <i class="ri-download-2-line"></i>
                                                            <b> {{ $historique->type }}</b>
                                                        </span>

                                                    </td>
                                                    <td> {{ $historique->produit->nom }} </td>
                                                    <td> {{ $historique->quantite }} Unité(s) </td>
                                                    <td> {{ $historique->created_at }} </td>
                                                    <td>
                                                        @if ($historique->auteur)
                                                            {{ $historique->auteur->nom }}
                                                        @else
                                                            <i>Introuvable !</i>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center">
                                                        <div>
                                                            <img src="/icons/icons8-ticket-100.png" height="100"
                                                                width="100" alt="" srcset="">
                                                        </div>
                                                        Aucun historique trouvé.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    <div class="d-flex justify-content-center">
                                        {{ $historique_stock->links('pagination::bootstrap-4') }}
                                    </div>
                                </div> <!-- end card body-->

                            </div>


                        </div> <!-- end card -->
                    </div><!-- end col-->
                </div> <!-- end row-->
            </div> <!-- container -->
        </div>
    @endsection
