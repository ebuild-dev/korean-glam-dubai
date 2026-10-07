@section('titre', 'Liste des commentaires')
@extends('admin.fixe')

@section('body')
    <div class="page-content-wrapper">
        <div class="page-content">

            <div class="row mb-3">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item">
                                    <a href="javascript: void(0);">{{ config('app.name') }}</a>
                                </li>
                                <li class="breadcrumb-item active">Commentaires</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Livewire Comments -->
            @livewire('comments')

        </div>
    </div>
@endsection
