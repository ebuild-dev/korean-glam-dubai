@extends('front.fixe')
@section('titre', 'Checkout')

@section('meta')
    <meta name="description" content="Proceed to checkout">
    <meta name="robots" content="noindex, nofollow">
    <link rel="canonical" href="{{ Request::url() }}">

@endsection

@section('body')

    <br><br>
    <div class="container">
        <div class="container">
            <h3><b>Cart totals</b></h3>
            <hr>
            <div class="d-flex justify-content-between">
                <div>
                    <h6><b>Subtotal</b></h6>
                </div>

                <div class="text-end">

                    {{-- 🔥 CAS POINTS OU MIXTE --}}
                    @if(isset($points_utilises) && $points_utilises > 0)

                        <span class="text-success">
                Paying with <b>{{ $points_utilises }}</b> points
            </span>
                        <br>

                        @if($mode_paiement === 'points')
                            {{-- Produits en points, shipping en AED --}}
                            <b>
                                Amount due:
                                {{ number_format($config->frais, 2) }} <sup>AED</sup>
                            </b>

                        @elseif($mode_paiement === 'mixte')
                            {{-- Mixte : points + argent --}}
                            @if(isset($montant_sans_coupon) && $coupon)
                                <strike>
                                    <b>{{ number_format($montant_sans_coupon, 2) }} <sup>AED</sup></b>
                                </strike>
                                <br>
                                <small class="text-success">
                                    Coupon -{{ $coupon->pourcentage }}%
                                    ( -{{ number_format($valeurCoupon, 2) }} AED )
                                </small>
                                <br>
                            @endif

                            <b>
                                Amount due:
                                {{ number_format($montant_final, 2) }} <sup>AED</sup>
                            </b>
                        @endif

                        {{-- 🔥 CAS ARGENT --}}
                    @else

                        @if(isset($montant_sans_coupon) && $coupon)
                            <strike>
                                <b>{{ number_format($montant_sans_coupon, 2) }} <sup>AED</sup></b>
                            </strike>
                            <br>
                            <small class="text-success">
                                Coupon -{{ $coupon->pourcentage }}%
                                ( -{{ number_format($valeurCoupon, 2) }} AED )
                            </small>
                            <br>
                        @endif

                        <b>
                            {{ number_format($montant_final, 2) }}
                            <sup>AED</sup>
                        </b>

                    @endif

                </div>
            </div>
            <hr>

            <h6><b>Get shipping estimates</b></h6>
            <br>
            @php
                // Keep the same token on reload and across payment methods for this basket.
                $basketFingerprint = hash('sha256', json_encode(session('panier_front', [])));
                if (!session('checkout_token') || session('checkout_basket') !== $basketFingerprint) {
                    session(['checkout_token' => (string) \Illuminate\Support\Str::uuid(), 'checkout_basket' => $basketFingerprint]);
                }
            @endphp
            <form id="checkoutForm" method="post" action="{{ route('commander') }}">
                @csrf
                <input type="hidden" name="checkout_token" value="{{ session('checkout_token') }}">
                @error('checkout_token')
                    <p class="alert alert-warning" role="alert">{{ $message }}</p>
                @enderror
                <div class="row">
                    @if(isset($points_utilises) && $points_utilises > 0)
                        <input type="hidden" name="points_utilises" id="points_utilises" value="{{ $points_utilises }}">
                    @endif
                    <div class="col-sm-4">
                        <div class="form-group mb-2">
                            <label for="">Full Name</label>
                            <input type="text" class="form-control" id="nom" value="{{ $user ? $user->nom : '' }}"
                                   name="nom" required>
                            @error('nom')
                            <span class="small text-danger"> {{ $message }} </span>
                            @enderror
                        </div>
                        <div class="form-group mb-2">
                            <label for="">Full address</label>
                            <input type="text" class="form-control" id="adresse" name="adresse"
                                   value="{{ $user ? $user->adresse : '' }}" placeholder="exp : 123 Av.Ibn Khouldoun" required>
                            @error('adresse')
                            <span class="small text-danger"> {{ $message }} </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group mb-2">
                            <label for="">Phone Number</label>
                            <input type="tel" class="form-control" id="phone"
                                   value="{{ $user ? $user->phone : '' }}" name="phone" required>
                            @error('phone')
                            <span class="small text-danger"> {{ $message }} </span>
                            @enderror
                        </div>
                        <div class="form-group mb-2">
                            <label for="">Emirate</label>
                            <select class="form-select" name="city" required>
                                <option value="">-- Select an emirate --</option>
                                @foreach (config('locations.emirates') as $emirate)
                                    <option value="{{ $emirate }}" @selected(old('city', $user?->city) === $emirate)>{{ $emirate }}</option>
                                @endforeach
                            </select>
                            @error('city')
                            <span class="small text-danger"> {{ $message }} </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group mb-2">
                            <label for="">Your Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                   value="{{ $user ? $user->email : '' }}" required>
                            @error('email')
                            <span class="small text-danger"> {{ $message }} </span>
                            @enderror
                        </div>
                        <div class="form-group mb-2">
                            <label for="">Zip / postal code</label>
                            <input type="text" class="form-control" id="code_postal" name="code_postal"
                                   value="{{ $user ? $user->zipcode : '' }}" placeholder="0000" >
                            @error('code_postal')
                            <span class="small text-danger"> {{ $message }} </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-2">
                            <label for="">Additional comments</label>
                            <textarea name="note" id="note" class="form-control" rows="5" placeholder="How can we help you?"></textarea>
                            @error('note')
                            <span class="small text-danger"> {{ $message }} </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6 my-auto">
                        @if ($coupon)
                            <label for="">Applied coupon code</label>
                            <input type="text" name="coupon" class="form-control" readonly
                                   value="{{ $coupon->code }}">
                            <br>
                        @endif

                        <!-- Boutons -->
                            <div class="d-flex flex-column gap-3">
                                <!-- Cash on delivery -->
                                <button type="submit" formaction="{{ route('commander') }}"
                                        class="btn btn-outline-dark w-100 py-3 fw-bold rounded-3 shadow-sm d-flex align-items-center justify-content-center gap-2">
                                    <i class="fa-solid fa-truck-fast fa-lg text-secondary"></i>
                                    <span>Cash on delivery</span>
                                </button>

                                <!-- Paiement en ligne -->
                                <button type="submit" formaction="{{ route('commander_payment') }}"
                                        class="btn btn-primary-color w-100 py-3 fw-bold rounded-3 shadow-sm d-flex align-items-center justify-content-center gap-2">
                                    <i class="fa-solid fa-credit-card fa-lg text-white"></i>
                                    <span>Payer en ligne</span>
                                </button>
                            </div>

                    </div>
                </div>
            </form>
        </div>
    </div>


    <script>
        (() => {
            const form = document.getElementById('checkoutForm');
            let submitting = false;
            form.addEventListener('submit', (event) => {
                if (submitting) {
                    event.preventDefault();
                    return;
                }
                submitting = true;
                // Preserve the clicked payment method before disabling both submitters.
                form.action = event.submitter?.formAction || form.action;
                form.setAttribute('aria-busy', 'true');
                form.querySelectorAll('button[type="submit"]').forEach(button => button.disabled = true);
            });
            window.addEventListener('pageshow', () => {
                submitting = false;
                form.removeAttribute('aria-busy');
                form.querySelectorAll('button[type="submit"]').forEach(button => button.disabled = false);
            });
        })();
    </script>
    <style>
        label {
            font-weight: 500;
            margin-bottom: 5px;
        }
    </style>


@endsection
