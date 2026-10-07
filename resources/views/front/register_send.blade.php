@extends('front.fixe')
@section('titre', 'Thank you for your registrations')
@section('body')
    <div class="container">
        <div class="container text-center">
           <center>
            <h1 class="h1">Wel<span class="color">come</span> </h1>
            <div>
                Thank you for your registration <br>
                You need to confirm your email address <br>
                If you do not receive this email please check your spam
            </div>
            <br>
            <a href="{{ route('home') }}" class="btn btn-primary-color mt-2 btn-add-to-cart">Go to home</a>
           </center>
        </div>
    </div>


    <style>
        .h1{
            font-size: 150px;
            font-weight: bold;
        }
        @media (max-width: 768px) {
           .h1{
                font-size: 40px;
            }
        }
    </style>
@endsection
