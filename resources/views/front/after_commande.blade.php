@extends('front.fixe')
@section('titre', "Thank you for your purchase")

@section('meta')
    <meta name="description" content="View and manage the items in your cart">
    <meta name="robots" content="noindex, follow">
    <link rel="canonical" href="{{ Request::url() }}">

@endsection

@section('body')

    <!-- CART AREA START -->
<br><br><br><br>
    <div class="container">
        <div class="col-sm-6 mx-auto">
           <div class="card p-5 text-center">
            <x-alert />
            <h3>
                <b>
                    Order <span class="color">placed!</span>
                </b>
            </h3>
            <br>
            <p>
                Thank you for your order. Our team will arrange delivery as soon as possible.
                <br>
                You can also print your invoice using the button below.
                <br> <br>
            </p>  
            <div class="text-center mt-3">
                <a href="/">
                    Back to home
                </a>
            </div>
           </div>
        </div>
    </div>
    <br><br><br>
    </section>

@endsection
