@extends('front.fixe')
@section('titre', 'Create an account')
@section('body')

<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-sm-7 mx-auto">
            <div class="row">
                <div class="col-sm-6">
                    <div class="card p-3">
                        <div class="text-center">
                            <h4>
                                <b>Create an account</b>
                            </h4>
                            <p>Please fill in the details below to create your account.</p>
                        </div>
                        @livewire('Front.register')
                    </div>
                </div>
                <div class="col-sm-6 my-auto">
                    <div class="text-center ">
                        <h4>
                            <b>Already have an account?</b>
                        </h4>
                        <a href="{{ route('login') }}" class="btn btn-outline-primary w-100 mt-2 mb-3 p-3">
                            <b>Log in</b>
                        </a>
                    </div>
                    <p>
                        <b class="color">* Terms and conditions.</b> <br>
                        Your privacy and security are important to us. To learn how we use your data,
                        read our <a href="" class="a"><b>privacy policy</b></a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
