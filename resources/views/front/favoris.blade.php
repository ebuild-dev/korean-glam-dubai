@extends('front.fixe')
@section('titre', 'Mes favoris')
@section('body')


    <br><br>
    <section >
        <div class="container">
            <h4 class="breadcrumb-title mb-2">Ma liste de favoris</h4>
        </div>
    </section>
    <section>
        <div class="container py-4">
            <div class="row">
                <div class="col-lg-3 col-xxl-3 mb-4 mb-lg-0 ">
                    <x-ProfileOption></x-ProfileOption>
                </div>
                <div class="col-lg-8 col-xxl-9">
                    <div class="border rounded-2 p-3 h-100">
                        <h5 class="text-dark m-0 mb-4 profile-title">Ma
                            liste de favoris</h5>
                        @livewire('Front.Favoris.Liste')
                        <div class="d-flex justify-content-center mt-3">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



@endsection
