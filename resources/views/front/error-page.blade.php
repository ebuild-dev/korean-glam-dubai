@extends('front.fixe')
@section('titre', 'Erreur')
@section('body')


    <!-- NAVBAR AREA END -->
    <section class="py-4 bg-light">
        <div class="container">
            <nav aria-label="breadcrumb">

                <h2 class="breadcrumb-title mb-2">Erreur</h2>

                <ol class="breadcrumb justify-content-center">
                    <li class="breadcrumb-item"><a class="text-dark" href="{{ route('home') }}">Accueil</a>
                    </li>
                    <li class="text-muted breadcrumb-item active" aria-current="page">Erreur</li>
                </ol>
            </nav>
        </div>
    </section>
    <section class="best-product pro-hover">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 mx-auto text-center pb-5 pt-5">
                    <h1>
                        <i class="fas fa-exclamation-triangle"></i> <br>
                        @if (session()->has('success'))
                            {{ session('success') }}
                        @endif
                        @if (session()->has('error'))
                            {{ session('error') }}
                        @endif
                    </h1>
                    <br><br>
                    <a href="{{ route('home') }}" class="btn btn-primary btn-lg">Back to home</a>
                    <br><br>
                </div>
            </div>
        </div>
    </section>


@endsection
