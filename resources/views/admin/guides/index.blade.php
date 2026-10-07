@section('titre', 'Liste des gammes')
@extends('admin.fixe')

@section('body')
    <!--page-content-wrapper-->
    <div class="page-content-wrapper">
        <div class="page-content">

            <!-- start page title -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item">
                                    <a href="javascript: void(0);">{{ config('app.name') }}</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ route('gammes.index') }}">Gammes</a>
                                </li>
                                <li class="breadcrumb-item active">Liste</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->
                @if (session('success'))
                    <div class="alert alert-success">
                        <i class="ri-check-line"></i> {{ session('success') }}
                    </div>
                @endif
                @if(session('warning'))
                    <div class="alert alert-warning">{{ session('warning') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">
                        <i class="ri-error-warning-line"></i> {{ session('error') }}
                    </div>
                @endif
            <div class="card radius-15">
                <div class="card-body">
                    <form action="{{ route('guides.store') }}" method="POST">
                        @csrf

                        <h4>Skin Profile</h4>
                        @if($client)
                        <input type="hidden" name="client_id" value="{{ $client->id }}">
                        @endif

                        @if($user)
                            <input type="hidden" name="client_id" value="{{ $user->id }}">
                        @endif
                        @if(isset($appointment))
                            <input type="hidden" name="id_appointment" value="{{ $appointment->id }}">
                        @endif

                        <div class="mb-3">
                            <label>Skin Type</label>
                            <select name="skin_type" class="form-control" required>
                                <option value="">-- Select Skin Type --</option>
                                <option value="dry">Dry</option>
                                <option value="oily">Oily</option>
                                <option value="combination">Combination</option>
                                <option value="sensitive">Sensitive</option>
                                <option value="normal">Normal</option>
                                <option value="aging">Aging</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Skin Concern</label>
                            <select id="skin_concern" name="skin_concern[]" multiple>
                                <option value="acne_blemishes">Acne & Blemishes</option>
                                <option value="dehydration">Dehydration</option>
                                <option value="fine_lines">Fine Lines</option>
                                <option value="sensitized">Sensitized</option>
                                <option value="texture">Texture</option>
                                <option value="uneven_skin_tone">Uneven Skin Tone & Dark Spots</option>
                                <option value="others">Others</option>
                            </select>

                            <!-- INPUT affiché SEULEMENT si Others est choisi -->
                            <input type="text" id="skin_concern_other" name="skin_concern_other"
                                   class="form-control mt-2" placeholder="Enter other concern..."
                                   style="display:none;">
                        </div>

                        <div class="mb-3">
                            <label>Skin Condition</label>
                            <select id="skin_condition" name="skin_condition[]" multiple>
                                <option value="eczema">Eczema</option>
                                <option value="rosacea">Rosacea</option>
                                <option value="psoriasis">Psoriasis</option>
                                <option value="others">Others</option>
                            </select>

                            <!-- INPUT affiché SEULEMENT si Others est choisi -->
                            <input type="text" id="skin_condition_other" name="skin_condition_other"
                                   class="form-control mt-2" placeholder="Enter other condition..."
                                   style="display:none;">
                        </div>

                        <hr>

                        <h4>Morning Routine</h4>

                        <div class="mb-3">
                            <label>Cleanser</label>
                            <select id="morning_cleanser" name="morning[cleanser][]" multiple>
                                @foreach ($produits as $p)
                                    <option value="{{ $p->id }}"
                                            data-image="{{ Storage::url($p->photo) }}">
                                        {{ $p->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Toner</label>
                            <select id="morning_toner" name="morning[toner][]" multiple>
                                @foreach ($produits as $p)
                                     <option value="{{ $p->id }}"
                data-image="{{ Storage::url($p->photo) }}">
            {{ $p->nom }}
        </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Serum</label>
                            <select id="morning_serum" name="morning[serum][]" multiple>
                                @foreach ($produits as $p)
                                    <option value="{{ $p->id }}"
                                            data-image="{{ Storage::url($p->photo) }}">
                                        {{ $p->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Eye Cream</label>
                            <select id="morning_eye" name="morning[eye_cream][]" multiple>
                                @foreach ($produits as $p)
                                    <option value="{{ $p->id }}"
                                            data-image="{{ Storage::url($p->photo) }}">
                                        {{ $p->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Cream</label>
                            <select id="morning_cream" name="morning[cream][]" multiple>
                                @foreach ($produits as $p)
                                    <option value="{{ $p->id }}"
                                            data-image="{{ Storage::url($p->photo) }}">
                                        {{ $p->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>SPF</label>
                            <select id="morning_spf" name="morning[spf][]" multiple>
                                @foreach ($produits as $p)
                                    <option value="{{ $p->id }}"
                                            data-image="{{ Storage::url($p->photo) }}">
                                        {{ $p->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Others</label>
                            <select id="morning_others" name="morning[others][]" multiple>
                                @foreach ($produits as $p)
                                    <option value="{{ $p->id }}"
                                            data-image="{{ Storage::url($p->photo) }}">
                                        {{ $p->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <hr>

                        <h4>Night Routine</h4>

                        <div class="mb-3">
                            <label>First Cleanser</label>
                            <select id="night_first" name="night[first_cleanser][]" multiple>
                                @foreach ($produits as $p)
                                    <option value="{{ $p->id }}"
                                            data-image="{{ Storage::url($p->photo) }}">
                                        {{ $p->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Second Cleanser</label>
                            <select id="night_second" name="night[second_cleanser][]" multiple>
                                @foreach ($produits as $p)
                                    <option value="{{ $p->id }}"
                                            data-image="{{ Storage::url($p->photo) }}">
                                        {{ $p->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Toner</label>
                            <select id="night_toner" name="night[toner][]" multiple>
                                @foreach ($produits as $p)
                                    <option value="{{ $p->id }}"
                                            data-image="{{ Storage::url($p->photo) }}">
                                        {{ $p->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Serum</label>
                            <select id="night_serum" name="night[serum][]" multiple>
                                @foreach ($produits as $p)
                                    <option value="{{ $p->id }}"
                                            data-image="{{ Storage::url($p->photo) }}">
                                        {{ $p->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Eye Cream</label>
                            <select id="night_eye" name="night[eye_cream][]" multiple>
                                @foreach ($produits as $p)
                                    <option value="{{ $p->id }}"
                                            data-image="{{ Storage::url($p->photo) }}">
                                        {{ $p->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Cream</label>
                            <select id="night_cream" name="night[cream][]" multiple>
                                @foreach ($produits as $p)
                                    <option value="{{ $p->id }}"
                                            data-image="{{ Storage::url($p->photo) }}">
                                        {{ $p->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Spot Treatment</label>
                            <select id="night_spot" name="night[spot_treatment][]" multiple>
                                @foreach ($produits as $p)
                                    <option value="{{ $p->id }}"
                                            data-image="{{ Storage::url($p->photo) }}">
                                        {{ $p->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Others</label>
                            <select id="night_others" name="night[others][]" multiple>
                                @foreach ($produits as $p)
                                    <option value="{{ $p->id }}"
                                            data-image="{{ Storage::url($p->photo) }}">
                                        {{ $p->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <hr>
                        <div class="guide-box mb-4">
                            <h4 class="box-title">WEEKLY NIGHT ROUTINE</h4>

                            <!-- EXFOLIATOR -->
                            <div class="mb-3">
                                <label>Exfoliator</label>
                                <select id="weekly_exfoliator" name="weekly_exfoliator[]" multiple>
                                    @foreach ($produits as $p)
                                        <option value="{{ $p->id }}"
                                                data-image="{{ Storage::url($p->photo) }}"
                                                @if(isset($guide) && in_array($p->id, explode(',', $guide->weekly_exfoliator ?? ''))) selected @endif>
                                            {{ $p->nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- FACE MASK -->
                            <div class="mb-3">
                                <label>Face Mask</label>
                                <select id="weekly_face_mask" name="weekly_face_mask[]" multiple>
                                    @foreach ($produits as $p)
                                        <option value="{{ $p->id }}"
                                                data-image="{{ Storage::url($p->photo) }}"
                                                @if(isset($guide) && in_array($p->id, explode(',', $guide->weekly_face_mask ?? ''))) selected @endif>
                                            {{ $p->nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- WASH-OFF MASK -->
                            <div class="mb-3">
                                <label>Wash-off Mask</label>
                                <select id="weekly_wash_mask" name="weekly_wash_mask[]" multiple>
                                    @foreach ($produits as $p)
                                        <option value="{{ $p->id }}"
                                                data-image="{{ Storage::url($p->photo) }}"
                                                @if(isset($guide) && in_array($p->id, explode(',', $guide->weekly_wash_mask ?? ''))) selected @endif>
                                            {{ $p->nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>



                        <button type="submit" class="btn btn-primary mt-3">Save Guide</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
    <!-- TomSelect CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">

    <!-- TomSelect JS -->
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {

            // === SKIN CONCERN ===
            const concernSelect = new TomSelect("#skin_concern", {
                plugins: ['remove_button'],
                maxItems: null,
                hideSelected: true,
                placeholder: "Select concerns...",
            });

            concernSelect.on('change', function(values) {
                if (values.includes('others')) {
                    document.getElementById('skin_concern_other').style.display = "block";
                } else {
                    document.getElementById('skin_concern_other').style.display = "none";
                    document.getElementById('skin_concern_other').value = "";
                }
            });


            // === SKIN CONDITION ===
            const conditionSelect = new TomSelect("#skin_condition", {
                plugins: ['remove_button'],
                maxItems: null,
                hideSelected: true,
                placeholder: "Select conditions...",
            });

            conditionSelect.on('change', function(values) {
                if (values.includes('others')) {
                    document.getElementById('skin_condition_other').style.display = "block";
                } else {
                    document.getElementById('skin_condition_other').style.display = "none";
                    document.getElementById('skin_condition_other').value = "";
                }
            });

        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {

            const selects = [
                "morning_cleanser", "morning_toner", "morning_serum", "morning_eye",
                "morning_cream", "morning_spf", "morning_others",
                "night_first", "night_second", "night_toner", "night_serum",
                "night_eye", "night_cream", "night_spot", "night_others",

                // 🆕 WEEKLY
                "weekly_exfoliator", "weekly_face_mask", "weekly_wash_mask"
            ];

            selects.forEach(function(id){
                new TomSelect(`#${id}`, {
                    plugins: ['remove_button'],
                    maxItems: null,
                    hideSelected: true,
                    placeholder: "Select a product...",

                    // 👇 CUSTOM RENDERING WITH IMAGE
                    render: {
                        option: function(data, escape) {
                            return `
                        <div class="d-flex align-items-center">
                            <img src="${data.image}"
                                 style="width:35px;height:35px;border-radius:6px;object-fit:cover;margin-right:8px;">
                            <span>${escape(data.text)}</span>
                        </div>`;
                        },
                        item: function(data, escape) {
                            return `
                        <div class="d-flex align-items-center">
                            <img src="${data.image}"
                                 style="width:28px;height:28px;border-radius:6px;object-fit:cover;margin-right:6px;">
                            <span>${escape(data.text)}</span>
                        </div>`;
                        }
                    },

                    // 👇 Pass custom data from <option data-image="">
                    dataAttr: {
                        image: "data-image",
                    }
                });
            });

        });
    </script>

    <style>
    .ts-wrapper.multi .ts-control .item {
        background-color: #ffb6c1 !important; /* Rose pastel */
        border-radius: 12px;
        color: #fff;
        font-weight: 600;
    }

    .ts-wrapper.multi .ts-control .remove {
        color: #fff !important;
    }

</style>
@endsection
