@extends('front.fixe')
@section('titre', 'Sign in')
@section('body')

    <div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-sm-7 mx-auto">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="card p-3">
                            <div class="text-center">
                                <h4>
                                    <b>Login</b>
                                </h4>
                                <p>Please login below account detail</p>
                            </div>
                            @livewire('Front.Login')
                            <div class="text-center mt-3">
                                <a href="{{ route('forgotpassword') }}" class="a">
                                    <b>
                                        Forgot your password?
                                    </b>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 my-auto">
                        <div class="text-center ">
                            <h4>
                                <b>Don't have an account?</b>
                            </h4>
                            <a href="{{ route('register') }}" class="btn btn-outline-primary w-100 mt-2 mb-3 p-3">
                                <b>Create an account</b>
                            </a>
                        </div>
                        <p>
                            <b class="color">* Terms & conditions.</b> <br>
                            Your privacy and security are important to us. For more information on how we use your data
                            read our <a href="" class="a"><b>privacy policy</b></a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
