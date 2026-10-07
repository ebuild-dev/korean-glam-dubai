@extends('front.fixe')
@section('titre', 'My cart')

@section('meta')
    <meta name="description" content="View and manage the items in your cart">
    <meta name="robots" content="noindex, follow">
    <link rel="canonical" href="{{ Request::url() }}">

@endsection

@section('body')

    <div class="container"><br><br><br>
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <div class="p-3">
                        <div class="">
                            <table class="w-100">
                                <tbody>
                                @forelse ($produits as $produit)

                                    <tr>
                                        <td>
                                            <div class="card-img-cart">
                                                <img src="{{ $produit['photo'] }}" alt="" class="w-100"
                                                     srcset="">
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <b> {{ $produit['nom'] }}</b>
                                            </div>
                                            <div class="mb-2">
                                                @if ($produit['en_promotion'])
                                                    <b>
                                                        {{ $produit['prix'] }} <sup>AED</sup>
                                                    </b>
                                                    <strike>
                                                        {{ $produit['prix_before'] }} <sup>AED</sup>
                                                    </strike>
                                                @else
                                                    <b>
                                                        {{ $produit['prix'] }} <sup>AED</sup>
                                                    </b>
                                                @endif
                                            </div>

                                        </td>
                                        <td>
                                            <input type="number" name="" value="{{ $produit['quantite'] }}"
                                                   style="width: 50px !important" id="qte-{{ $produit['produit_id'] }}"
                                                   min="1" max="10">
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-success btnUpdateElementCart"
                                                    data-id="{{ $produit['produit_id'] }}">
                                                Update
                                            </button>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-danger btnDeleteElementCart"
                                                    data-id="{{ $produit['produit_id'] }}">
                                                Delete
                                            </button>
                                        </td>
                                        <td>
                                            <b>
                                                @if ($produit['en_promotion'])
                                                    <b>
                                                        {{ $produit['prix']*$produit['quantite']  }} <sup>AED</sup>
                                                    </b>
                                                    <strike>
                                                        {{ $produit['prix_before'] * $produit['quantite'] }}
                                                        <sup>AED</sup>
                                                    </strike>
                                                @else
                                                    <b>
                                                        {{ $produit['prix'] * $produit['quantite']   }} <sup>AED</sup>
                                                    </b>
                                                @endif
                                            </b>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td>
                                            <div class="card p-3">
                                                <p class="text-center">No items in your cart.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                            <br><br>
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('shop') }}">
                                    <button class="btn btn-primary-color">
                                        <i class="fa-solid fa-arrow-left"></i>
                                        <b>Continue Shopping</b>
                                    </button>
                                </a>
                                <a href="{{ route('clear-cart') }}" class="btn btn-secondary text-white">
                                    <i class="fa-solid fa-trash-can"></i>
                                    <b>Clear Cart</b>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card p-3 bg-gray">
                        <br>
                        <table class="w-100">
                            <tr>
                                <td>Subtotal:</td>
                                <td class="color  text-end">
                                    <b>{{ $total_prix_sans_coupon }} <sup>AED</sup></b>

                                    @if($if_promotion)
                                        <strike>
                                            {{ $total_avant_promo }} <sup>AED</sup>
                                        </strike>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Shipping:</td>
                                <td class="color  text-end">
                                    <b>{{$configs->frais}} <sup>AED</sup></b>
                                </td>
                            </tr>
                            <tr>
                                <td><b>Total:</b></td>
                                <td class="color text-end">

                                    {{-- 🔥 AVANT COUPON --}}
                                    @if($coupon)
                                        <strike>
                                            <b>{{ number_format($total_prix_with_shipping_sans_coupon, 2) }}
                                                <sup>AED</sup>
                                            </b>
                                        </strike>

                                        <br>
                                        <small class="text-success">
                                            Coupon -{{ $coupon->pourcentage }}%
                                            ( -{{ number_format($valeurCoupon, 2) }} AED )
                                        </small>


                                        {{-- 🔥 APRÈS COUPON --}}
                                        <b>
                                            {{ number_format($total_prix_with_shipping, 2) }}
                                            <sup>AED</sup>
                                        </b>
                                    @else
                                        <b>
                                            {{ number_format($total_prix_with_shipping, 2) }}
                                            <sup>AED</sup>
                                        </b>
                                    @endif

                                </td>
                            </tr>
                            @if($total_points_achat > 0)
                                <tr>
                                    <td><b>Total Points:</b></td>
                                    <td class="color text-end">
                                        <b>{{ $total_points_achat }} pts</b>
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <td colspan="2">
                                    <br>
                                    <form action="{{ route('cart') }}" method="get">
                                        <label for="coupon-code">Coupon Code</label>
                                        <div class="input-group">
                                            <input
                                                type="text"
                                                class="form-control"
                                                placeholder="Enter coupon code"
                                                name="coupon"
                                                id="coupon-code"
                                                value="{{ request('coupon') }}"
                                            >
                                            <button class="btn btn-primary-color">Apply</button>
                                        </div>
                                    </form>
                                    @if($total_points_achat >0)
                                        @if($can_checkout_with_points)
                                            <form action="{{ route('checkout.with.points') }}" method="post">
                                                @csrf
                                                <button type="submit" class="btn btn-warning w-100 mt-2">
                                                    <b>Checkout with Points ({{ $total_points_achat }} points)</b>
                                                </button>
                                            </form>
                                        @endif
                                        @if($can_checkout_mixed)
                                            <form action="{{ route('checkout.mixed') }}" method="post">
                                                @csrf
                                                <button class="btn btn-info w-100 mt-2">
                                                    <b>
                                                        Use {{ auth()->user()->points }} points +
                                                        {{ number_format($reste_a_payer,2) }} AED
                                                    </b>
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        </table>
                        <br>
                        <a href="{{ route('checkout') }}" class="btn btn-primary-color w-100 mt-3 p-2   text-white">
                            <b>Checkout</b>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
