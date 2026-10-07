@section('titre', 'Nouvelle commande')
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
                                    <a href="{{ route('commandes') }}">Commandes</a>
                                </li>
                                <li class="breadcrumb-item active">Nouvelle commande</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- end page title -->
            <div class="card radius-15">
                <div class="card-body">

                    <div class="card-title">
                        <div class="d-flex justify-content-between">
                            <h5 class="header-title">
                                Nouvelle commande
                            </h5>
                            <button class="btn btn-sm btn-dark" type="button" data-bs-toggle="modal"
                                data-bs-target="#AddProduit">
                                Ajouter un produit
                            </button>
                        </div>
                    </div>
                    <hr>
                    @livewire('Commandes.AjouterCommande')
                </div>
            </div>


            <!-- Center modal content -->
            <div class="modal fade" id="AddProduit" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title" id="myCenterModalLabel">
                                Ajout un produit
                            </h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            @livewire('Commandes.Add')
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

        </div>
    </div>



@endsection
