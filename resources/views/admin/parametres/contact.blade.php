@section('titre', 'Contacts - Frais')
@extends('admin.fixe')

@section('body')

    <div class="page-content-wrapper">
        <div class="page-content">

            <!-- start page title -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item">
                                        <a href="javascript: void(0);">{{ config('app.name') }}</a>
                                    </li>
                                    <li class="breadcrumb-item active">Contacts - Frais</li>
                                </ol>
                            </div>
                            <div>
                                <a href="{{ route('home_infos') }}" class="btn btn-dark btn-sm">
                                    Informations de l'accueil
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <!--end breadcrumb-->
            <div class="user-profile-page">
                <div class="card radius-15">
                    <div class="card-body">
                        <div>
                            @include('components.alert')


                            <form method="POST" action="{{ route('contact-admin.post') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="text-center bg-primary card my-auto p-1 mb-3">
                                    <h6 class="text-white">
                                        Logo et images
                                    </h6>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label for="">Logo </label>
                                            <input type="file" name="logo" accept="image/*" class="form-control">
                                            @error('logo')
                                                <span class="text-danger small"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label for="">Icone</label>
                                            <input type="file" name="icon" accept="image/*" class="form-control">
                                            @error('icon')
                                                <span class="text-danger small"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>




                                <div class="text-center bg-primary card my-auto p-1 mb-3">
                                    <h6 class="text-white">
                                        Réseaux sociaux / societe
                                    </h6>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="mb-3">
                                            <label for="">Adresse</label>
                                            <input type="text" name="adresse"
                                                value="{{ old('adresse', $config->adresse) }}" class="form-control">
                                            @error('adresse')
                                                <span class="text-danger small"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="mb-3">
                                            <label for="">Numéro de téléphone</label>
                                            <input type="text" name="telephone"
                                                value="{{ old('telephone', $config->telephone) }}" class="form-control">
                                            @error('telephone')
                                                <span class="text-danger small"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="mb-3">
                                            <label for="">Matricule Fiscal</label>
                                            <input type="text" name="matricule"
                                                value="{{ old('matricule', $config->matricule) }}" class="form-control">
                                            @error('matricule')
                                                <span class="text-danger small"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="mb-3">
                                            <label for="">Lien Facebook</label>
                                            <input type="url" name="facebook"
                                                value="{{ old('facebook', $config->facebook) }}" class="form-control">
                                            @error('facebook')
                                                <span class="text-danger small"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="mb-3">
                                            <label for="">Lien Tiktok</label>
                                            <input type="url" name="tiktok"
                                                value="{{ old('tiktok', $config->tiktok) }}" class="form-control">
                                            @error('tiktok')
                                                <span class="text-danger small"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="mb-3">
                                            <label for="">Lien instagram</label>
                                            <input type="url" name="instagram"
                                                value="{{ old('instagram', $config->instagram) }}" class="form-control">
                                            @error('instagram')
                                                <span class="text-danger small"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="mb-3">
                                            <label for="">Email</label>
                                            <input type="email" name="email"
                                                value="{{ old('email', $config->email) }}" class="form-control">
                                            @error('email')
                                                <span class="text-danger small"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>


                                <div class="text-center bg-dark card my-auto p-1 mb-3">
                                    <h6 class="text-white">
                                        En-tête du site web
                                    </h6>
                                </div>
                                <div>
                                    <input type="text" name="text_header" class="form-control" placeholder="Phrase a afficher dans l'en-tête du site web" wire:model='text_header' value="{{ old('text_header', $config->text_header) }}" id="">
                                </div>
                                <div class="mt-4">
                                    <input type="text" name="text_header_center" class="form-control" placeholder="Phrase a afficher dans l'en-tête du site web au centre " wire:model='text_header_center' value="{{ old('text_header_center', $config->text_header_center) }}" id="">
                                </div>
                                <br>


                                <div class="text-center bg-primary card my-auto p-1 mb-3">
                                    <h6 class="text-white">
                                        Frais
                                    </h6>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="mb-3">
                                            <label for="">Frais de livraison</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        TN
                                                    </span>
                                                </div>
                                                <input type="number" name="frais" step="0.1"
                                                    value="{{ old('frais', $config->frais) }}" class="form-control">
                                            </div>
                                            @error('frais')
                                                <span class="text-danger small"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="mb-3">
                                            <label for="">Valeur de la TVA</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        TN
                                                    </span>
                                                </div>
                                                <input type="text" name="tva" step="0.1"
                                                    value="{{ old('tva', $config->tva) }}" class="form-control">

                                            </div>
                                        </div>
                                        @error('tva')
                                            <span class="text-danger small"> {{ $message }} </span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="mb-3">
                                            <label for="">Prix du timbre</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        TN
                                                    </span>
                                                </div>
                                                <input type="number" name="timbre" step="0.1"
                                                    value="{{ old('timbre', $config->timbre) }}" class="form-control">
                                            </div>
                                            @error('timbre')
                                                <span class="text-danger small"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <br>
                                <div class="modal-footer">
                                    <button class="btn btn-primary btn-sm" type="submit">
                                        <i class="ri-save-line me-1 fs-16 lh-1"></i>
                                        Enregistrer les changements
                                    </button>
                                </div>
                            </form>

                        </div>

                    </div>
                </div>
            </div>
        </div>

    @endsection
