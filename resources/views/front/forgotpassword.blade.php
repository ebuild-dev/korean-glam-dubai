@extends('front.fixe')
@section('titre', 'Forgot password')
@section('body')

    <br><br><br>
    <div class="container">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 mx-auto">
                    <div class="p-5">
                        <div class="text-center">
                            <h4>
                                <b>Forgot password?</b>
                            </h4>
                            <div class="card mt-3">
                                <div class="bg-primary-color p-3 text-white">
                                    Reset your password here
                                </div>
                                <br>
                               <div class="p-3">
                                @livewire('Front.Forget')
                               </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
