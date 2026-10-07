@section('titre', 'Informations')
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
                                    <li class="breadcrumb-item active">Informations</li>
                                </ol>
                            </div>
                            <div>
                                <a href="{{ route('contact-admin') }}" class="btn btn-dark btn-sm">
                                    <i class="ri-arrow-left-line me-1 fs-16 lh-1"></i>
                                    Retour
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

<br>
                            <form method="POST" action="{{ route('update_home_infos') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="text-center bg-primary card my-auto p-1 mb-3">
                                            <h6 class="text-white">
                                                Section 1
                                            </h6>
                                        </div>
                                        <div class="mb-3">
                                            <label for="">Texte</label>
                                            <input type="text" name="home_text_1" value="{{ $config->home_text_1 }}"
                                                class="form-control">
                                        </div>
                                        <div class="mb-3">
                                            <label for="">Lien</label>
                                            <input type="text" name="home_lien_1" value="{{ $config->home_lien_1 }}"
                                                class="form-control">
                                        </div>
                                        <div class="mb-3">
                                            <label for="">Image</label>
                                            <div class="small text-warning">
                                                La taille de l'image doit être de 573x349
                                            </div>
                                            <input type="file" name="home_photo_1" class="form-control">
                                            @if ($config->home_photo_1)
                                                <img src="{{ Storage::url($config->home_photo_1) }}" alt="Image 1"
                                                    class="w-100 mt-2">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="text-center bg-primary card my-auto p-1 mb-3">
                                            <h6 class="text-white">
                                                Section 2
                                            </h6>
                                        </div>
                                        <div class="mb-3">
                                            <label for="">Texte</label>
                                            <input type="text" name="home_text_2" value="{{ $config->home_text_2 }}"
                                                class="form-control">
                                        </div>
                                        <div class="mb-3">
                                            <label for="">Lien</label>
                                            <input type="text" name="home_lien_2" value="{{ $config->home_lien_2 }}"
                                                class="form-control">
                                        </div>
                                        <div class="mb-3">
                                            <label for="">Image</label>
                                            <div class="small text-warning">
                                                La taille de l'image doit être de 573x349
                                            </div>
                                            <input type="file" name="home_photo_2" class="form-control">
                                            @if ($config->home_photo_2)
                                                <img src="{{ Storage::url($config->home_photo_2) }}" alt="Image 2"
                                                    class="w-100 mt-2">
                                            @endif
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
