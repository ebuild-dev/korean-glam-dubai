@extends('front.fixe')
@section('titre', 'Mon profile')
@section('body')


    <br><br>
    <section >
        <div class="container">
            <h4 class="breadcrumb-title mb-2">Modifier le profil</h4>
        </div>
    </section>
    <section>
        <div class="container py-4">
            <div class="row">
                <div class="col-lg-3 col-xxl-3 mb-4 mb-lg-0 ">
                    <x-ProfileOption></x-ProfileOption>
                </div>
                <div class="col-lg-8 col-xxl-9">
                    <div class="card p-3">
                        <h5 class="text-dark m-0 mb-3 profile-title">Modifier le profil</h5>
                        @livewire('Front.Profile.Update')
                    </div>

                </div>
            </div>
        </div>
    </section>



@endsection
