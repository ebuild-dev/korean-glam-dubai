@extends('front.fixe')
@section('titre', 'Mon Guide Skincare')
@section('body')

    <br><br>
    <section>
        <div class="container">
            <h4 class="breadcrumb-title mb-2">Orders</h4>
        </div>
    </section>
    <section>
        <div class="container py-4">
            <div class="row">
                <div class="col-lg-3 col-xxl-3 mb-4 mb-lg-0 ">
                    <x-ProfileOption></x-ProfileOption>
                </div>
                <div class="col-lg-9 col-xxl-9">
                    <div class="step active" id="step1">

                        <!-- HEADER -->
                        <div class="text-center mb-4">
                            <img src="{{ $config->getLogo() }}" class="guide-logo mb-3" style="width:140px;">
                            <h2 class="fw-bold" style="font-size:28px; text-transform:capitalize;">
                                Korean glam's Skincare Essentials Guide
                            </h2>

                            <p class="text-muted mt-3" style="max-width:600px; margin:auto;">
                                This guide will provide the key details on how to create a skincare routine
                                tailored to your skin type and concerns.
                            </p>
                        </div>

                        <div class="border-guide">
                            <!-- SKIN TYPE -->
                            <div class="mt-4">
                                <h4 class="fw-bold mb-2" style="font-size:20px;">SKIN TYPE</h4>

                                @php $types = explode(',', $guide->skin_type); @endphp

                                @foreach($types as $t)
                                    <div class="d-flex align-items-center mb-1">
                                        <span class="pink-bullet"></span>
                                        <span style="font-size:18px;">{{ trim($t) }}</span>
                                    </div>
                                @endforeach
                            </div>

                            @php
                                $concernLabels = [
                                    'acne_blemishes'   => 'Acne & Blemishes',
                                    'dehydration'      => 'Dehydration',
                                    'fine_lines'       => 'Fine Lines',
                                    'sensitized'       => 'Sensitized',
                                    'texture'          => 'Texture',
                                    'uneven_skin_tone' => 'Uneven Skin Tone & Dark Spots',
                                    'others'           => 'Others',
                                ];
                            @endphp

                                <!-- SKIN CONCERN -->
                            <div class="mt-4">
                                <h4 class="fw-bold mb-2" style="font-size:20px;">SKIN CONCERN</h4>

                                @foreach(explode(',', $guide->skin_concern) as $c)
                                    <div class="d-flex align-items-center mb-1">
                                        <span class="pink-bullet"></span>

                                        {{-- Si la valeur existe dans la liste, on affiche son label --}}
                                        <span style="font-size:18px;">
                {{ $concernLabels[trim($c)] ?? trim($c) }}
            </span>
                                    </div>
                                @endforeach
                            </div>


                            <!-- SKIN CONDITION -->
                            <div class="mt-4 mb-4">
                                <h4 class="fw-bold mb-2" style="font-size:20px;">SKIN CONDITION</h4>

                                @foreach(explode(',', $guide->skin_condition) as $c)
                                    <div class="d-flex align-items-center mb-1">
                                        <span class="pink-bullet"></span>
                                        <span style="font-size:18px;">{{ trim($c) }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- STEP 2 : MORNING -->
                    <div class="step" id="step2">

                        <h3 class="text-center mb-3">🌞 Morning Skincare Routine</h3>
                        <div class="routine-grid">

                            @foreach(['cleanser'=>'Cleanser','toner'=>'Toner','serum'=>'Serum','eye_cream'=>'Eye Cream','cream'=>'Cream','spf'=>'SPF','others'=>'Others'] as $key => $label)
                                <div class="routine-item">
                                    <b>{{ $label }} :</b><br>
                                    @foreach ($morningProducts[$key] as $p)
                                        <div class="d-flex align-items-center mb-1"><img
                                                src="{{ Storage::url($p->photo) }}" width="50" class="rounded me-2">
                                            <span>{{ $p->nom }}</span>
                                        </div>
                                    <div class=" ">
                                        <button type="button"
                                                class="btn btn-primary-color w-100 mt-2 btn-add-to-cart"
                                                data-id="{{ $p->id }}">
                                            Add to cart
                                        </button>
                                        <!-- ADD TO FAVORIS -->
                                        <button type="button"
                                                class="btn  w-100 mt-2 btn-add-to-favoris"
                                                data-id="{{ $p->id }}">
                                            <i class="fa-solid fa-heart"></i> Add to favoris
                                        </button></div>
                                    @endforeach

                                </div>
                            @endforeach

                        </div>

                    </div>
                    <!-- STEP 3 : NIGHT -->
                    <div class="step" id="step3">

                        <h3 class="text-center mb-3">🌙 Night Skincare Routine</h3>
                        <div class="routine-grid">

                            @foreach(['cleanser'=>'First Cleanser',
                                      'second_cleanser'=>'Second Cleanser',
                                      'toner'=>'Toner',
                                      'serum'=>'Serum',
                                      'eye_cream'=>'Eye Cream',
                                      'cream'=>'Cream',
                                      'spot_treatment'=>'Spot Treatment',
                                      'others'=>'Others'] as $key => $label)

                                <div class="routine-item">
                                    <b>{{ $label }} :</b><br>
                                    @foreach ($nightProducts[$key] as $p)
                                        <div class="d-flex align-items-center mb-1"><img
                                                src="{{ Storage::url($p->photo) }}" width="50" class="rounded me-2">
                                            <span>{{ $p->nom }}</span></div>
                                        <button type="button"
                                                class="btn btn-primary-color w-100 mt-2 btn-add-to-cart"
                                                data-id="{{ $p->id }}">
                                            Add to cart
                                        </button>
                                        <button type="button"
                                                class="btn  w-100 mt-2 btn-add-to-favoris"
                                                data-id="{{ $p->id }}">
                                            <i class="fa-solid fa-heart"></i> Add to favoris
                                        </button>
                                    @endforeach
                                </div>

                            @endforeach

                        </div>

                    </div>

                    <!-- STEP 4 : WEEKLY -->
                    <div class="step" id="step4">

                        <h3 class="text-center mb-3">💗 Weekly Night Routine</h3>

                        <div class="routine-grid">
                            <div class="routine-item">
                                <!-- Exfoliator -->
                                <p><strong>Exfoliator :</strong></p>
                                @forelse($weeklyProducts['exfoliator'] as $p)
                                    <div class="d-flex align-items-center mb-1"><img
                                            src="{{ Storage::url($p->photo) }}" width="50" class="rounded me-2">
                                        <span>{{ $p->nom }}</span></div>
                                    <button type="button"
                                            class="btn btn-primary-color w-100 mt-2 btn-add-to-cart"
                                            data-id="{{ $p->id }}">
                                        Add to cart
                                    </button>
                                    <button type="button"
                                            class="btn  w-100 mt-2 btn-add-to-favoris"
                                            data-id="{{ $p->id }}">
                                        <i class="fa-solid fa-heart"></i> Add to favoris
                                    </button>
                                @empty
                                    <p>—</p>
                                @endforelse
                            </div>
                            <div class="routine-item">
                                <!-- Face Mask -->
                                <p class="mt-3"><strong>Face Mask :</strong></p>
                                @forelse($weeklyProducts['face_mask'] as $p)
                                    <div class="d-flex align-items-center mb-1"><img
                                            src="{{ Storage::url($p->photo) }}" width="50" class="rounded me-2">
                                        <span>{{ $p->nom }}</span></div>
                                    <button type="button"
                                            class="btn btn-primary-color w-100 mt-2 btn-add-to-cart"
                                            data-id="{{ $p->id }}">
                                        Add to cart
                                    </button>
                                    <button type="button"
                                            class="btn  w-100 mt-2 btn-add-to-favoris"
                                            data-id="{{ $p->id }}">
                                        <i class="fa-solid fa-heart"></i> Add to favoris
                                    </button>
                                @empty
                                    <p>—</p>
                                @endforelse
                            </div>
                            <!-- Wash-off Mask -->
                            <div class="routine-item">
                                <p class="mt-3"><strong>Wash-off Mask :</strong></p>
                                @forelse($weeklyProducts['wash_mask'] as $p)
                                    <div class="d-flex align-items-center mb-1"><img
                                            src="{{ Storage::url($p->photo) }}" width="50" class="rounded me-2">
                                        <span>{{ $p->nom }}</span></div>
                                    <button type="button"
                                            class="btn btn-primary-color w-100 mt-2 btn-add-to-cart"
                                            data-id="{{ $p->id }}">
                                        Add to cart
                                    </button>
                                    <button type="button"
                                            class="btn  w-100 mt-2 btn-add-to-favoris"
                                            data-id="{{ $p->id }}">
                                        <i class="fa-solid fa-heart"></i> Add to favoris
                                    </button>
                                @empty
                                    <p>—</p>
                                @endforelse
                            </div>
                        </div>


                        <h4 class="text-center mt-4">Contact Us</h4>
                        <p class="text-center">
                            <b>Instagram :</b><a href=" {{ $config->instagram }}{{----}}"
                                                 target="_blank"> {{ $config->instagram }}</a><br>
                            <b>Site :</b> <a href="{{ url('/') }}" target="_blank">{{ url('/') }}</a> <br>

                            <b>Phone:</b> {{ $config->telephone}}
                        </p>

                    </div>

                    <!-- NAVIGATION BUTTONS -->
                    <div class="text-center mt-4">
                        <button id="prevBtn" class="nav-btn" onclick="nextStep(-1)" disabled>⬅ Back</button>
                        <button id="nextBtn" class="nav-btn" onclick="nextStep(1)">Next ➡</button>
                    </div>



                </div>
            </div>
        </div>
    </section>
    <script>
        let step = 1;

        function showStep(n) {
            document.querySelectorAll('.step').forEach((el, idx) => {
                el.classList.toggle('active', idx === n - 1);
            });

            document.getElementById('prevBtn').disabled = (n === 1);
            document.getElementById('nextBtn').innerText = (n === 4 ? 'Finish' : 'Next ➡');
        }

        function nextStep(dir) {
            step = step + dir;
            if (step < 1) step = 1;
            if (step > 4) step = 4;
            showStep(step);
        }
    </script>
    <style>
        .step {
            display: none;
        }

        .step.active {
            display: block;
        }

        .nav-btn {
            background: #ffb3c9;
            border: none;
            padding: 10px 20px;
            color: white;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }

        .nav-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .guide-logo {
            width: 120px;
        }

        .routine-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            border: 1px solid #eee;
            padding: 15px;
            border-radius: 10px;
        }

        .routine-item {
            border: 1px solid #f0c0d9;
            padding: 10px;
            border-radius: 8px;

        }

        .pink-bullet {
            display: inline-block;
            width: 22px;
            height: 12px;
            background: #ffb3c9;
            border-radius: 20px;
            margin-right: 8px;
        }

        .guide-logo {
            width: 150px;
        }

        .btn-pink {
            background-color: #ff6fa8;
            color: white;
            border-radius: 6px;
            padding: 4px 8px;
            border: none;
        }

        .btn-pink:hover {
            background-color: #ff4f95;
            color: white;
        }

        .btn-add-to-favoris {
            border: 1px solid #ffc0cb;

        }

        .btn-add-to-favoris:hover {
            background-color: #ffc0cb;
            color: #FFFFFF;
        }

        .border-guide {
            border: 1px solid #ffc0cb;
            border-radius: 6px;
            padding: 10px;
        }
    </style>

@endsection
