@extends('front.fixe')
@section('titre', "Payment successful")

@section('meta')
    <meta name="description" content="View and manage the items in your cart">
    <meta name="robots" content="noindex, follow">
    <link rel="canonical" href="{{ Request::url() }}">
@endsection

@section('body')
    <div class="flex items-center justify-center min-h-[70vh] bg-gray-50 px-4">
        <div class="bg-white  rounded-2xl p-8 max-w-lg w-full text-center">


            <h1 class="text-2xl font-bold text-gray-800 mb-3">Payment successful 🎉</h1>
            <p class="text-gray-600 mb-4">{{ $message }}</p>

            <div class="bg-gray-100 rounded-lg p-4 mb-6">
                <p class="text-gray-700">
                    <span class="font-semibold">Order:</span>
                    <span class="text-indigo-600">{{ $commande->trackcode }}</span>
                </p>
            </div>

            <div class="flex justify-center space-x-4">
                <a href="{{ route('home') }}"
                   class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl transition">
                    Back to home
                </a>
                <a href="{{ route('shop') }}"
                   class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium rounded-xl transition">
                    Continue shopping
                </a>
            </div>
        </div>
    </div>
@endsection

