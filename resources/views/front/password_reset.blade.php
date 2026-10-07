@extends('front.fixe')
@section('titre', 'Changer de mot de passe')
@section('body')


    <br><br><br>
    <div class="container">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 mx-auto">
                    <div class="p-5">
                        <div class="text-center">
                            <h4>
                                <b>New password</b>
                            </h4>
                        </div>
                        <div class="card mt-3">
                            <div class="bg-primary-color text-center p-3 text-white">
                                Nouveau mot de passe
                            </div>
                            <div class="p-3">
                                <b class="text-danger">Conseils</b>
                                <p>
                                    1. Use numbers and special characters. <br>
                                    2. Choose a strong password that is difficult to guess. <br>
                                    3. Never share your password online. <br>
                                </p>
                                <br>
                                <div>
                                    @livewire('Front.Reset', ['token' => $token])
                                    <p class="fs-7 text-center mt-3">Remember your password?
                                        <a href="{{ route('login') }}" class="a">Sign in</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
