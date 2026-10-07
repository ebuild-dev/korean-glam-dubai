@extends('front.fixe')
@section('titre', "Payment failed")

@section('meta')
    <meta name="description" content="View and manage the items in your cart">
    <meta name="robots" content="noindex, follow">
    <link rel="canonical" href="{{ Request::url() }}">
@endsection

@section('body')
    <div class="flex items-center justify-center min-h-[70vh] bg-gray-50 px-4">
        <div class="bg-white  rounded-2xl p-8 max-w-lg w-full text-center">

            <h1 class="text-2xl font-bold text-gray-800 mb-3">Payment failed ❌</h1>
            <p class="text-gray-600 mb-4">{{ $message }}</p>

            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <p class="text-gray-700">
                    <span class="font-semibold">Payment reference:</span>
                    <span class="text-red-600">{{ $payment_ref }}</span>
                </p>
            </div>

            <div class="flex justify-center space-x-4">
                <a href="{{ route('home') }}"
                   class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium rounded-xl transition">
                    Back to home
                </a>
                <a href="{{ route('checkout') }}"
                   class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-xl transition">
                    Retry payment
                </a>
            </div>
        </div>
    </div>
@endsection
