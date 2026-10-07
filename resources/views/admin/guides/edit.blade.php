@extends('admin.fixe')

@section('titre', 'Modifier le Guide de Skincare')

@section('body')
    <div class="page-content-wrapper">
        <div class="page-content">
    @if (session('success'))
        <div class="alert alert-success">
            <i class="ri-check-line"></i> {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            <i class="ri-error-warning-line"></i> {{ session('error') }}
        </div>
    @endif


    <div class="card radius-15">
        <div class="card-body">

            <h3 class="mb-3">Modifier le Skincare Guide</h3>

            <form action="{{ route('guides.update', $guide->id) }}" method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" name="client_id" value="{{ $guide->user_id }}">

                {{-- Skin Profile --}}
                <h4>Skin Profile</h4>

                <div class="mb-3">
                    <label>Skin Type</label>
                    <select name="skin_type" class="form-control" required>
                        <option value="">-- Select Skin Type --</option>
                        <option value="dry"         {{ $guide->skin_type == 'dry' ? 'selected' : '' }}>Dry</option>
                        <option value="oily"        {{ $guide->skin_type == 'oily' ? 'selected' : '' }}>Oily</option>
                        <option value="combination" {{ $guide->skin_type == 'combination' ? 'selected' : '' }}>Combination</option>
                        <option value="sensitive"   {{ $guide->skin_type == 'sensitive' ? 'selected' : '' }}>Sensitive</option>
                        <option value="normal"      {{ $guide->skin_type == 'normal' ? 'selected' : '' }}>Normal</option>
                        <option value="aging"       {{ $guide->skin_type == 'aging' ? 'selected' : '' }}>Aging</option>
                    </select>
                </div>

                {{-- Skin Concern --}}
                <div class="mb-3">
                    <label>Skin Concern</label>
                    <select id="skin_concern" name="skin_concern[]" multiple>
                        @php $concerns = explode(',', $guide->skin_concern); @endphp

                        <option value="acne_blemishes" @selected(in_array('acne_blemishes', $concerns))>Acne & Blemishes</option>
                        <option value="dehydration"    @selected(in_array('dehydration', $concerns))>Dehydration</option>
                        <option value="fine_lines"     @selected(in_array('fine_lines', $concerns))>Fine Lines</option>
                        <option value="sensitized"     @selected(in_array('sensitized', $concerns))>Sensitized</option>
                        <option value="texture"        @selected(in_array('texture', $concerns))>Texture</option>
                        <option value="uneven_skin_tone" @selected(in_array('uneven_skin_tone', $concerns))>
                            Uneven Skin Tone & Dark Spots
                        </option>
                        <option value="others" @selected(in_array('others', $concerns))>Others</option>
                    </select>

                    {{-- Input Others --}}
                    <input type="text" id="skin_concern_other"
                           name="skin_concern_other"
                           class="form-control mt-2"
                           placeholder="Enter other concern..."
                           style="{{ in_array('others', $concerns) ? '' : 'display:none;' }}"
                           value="{{ in_array('others', $concerns) ? end($concerns) : '' }}">
                </div>


                {{-- Skin Condition --}}
                <div class="mb-3">
                    <label>Skin Condition</label>
                    <select id="skin_condition" name="skin_condition[]" multiple>
                        @php $conditions = explode(',', $guide->skin_condition); @endphp

                        <option value="eczema"    @selected(in_array('eczema', $conditions))>Eczema</option>
                        <option value="rosacea"   @selected(in_array('rosacea', $conditions))>Rosacea</option>
                        <option value="psoriasis" @selected(in_array('psoriasis', $conditions))>Psoriasis</option>
                        <option value="others"    @selected(in_array('others', $conditions))>Others</option>
                    </select>

                    <input type="text" id="skin_condition_other"
                           name="skin_condition_other"
                           class="form-control mt-2"
                           placeholder="Enter other condition..."
                           style="{{ in_array('others', $conditions) ? '' : 'display:none;' }}"
                           value="{{ in_array('others', $conditions) ? end($conditions) : '' }}">
                </div>

                <hr>

                {{-- Routines --}}
                {{-- ROUTINES — MORNING + NIGHT --}}

                <h4 class="mt-4">Morning Routine</h4>

                @php
                    $morning = $guide->routines->where('type', 'morning')->first();
                @endphp

                <div class="mb-3">
                    <label>Cleanser</label>
                    <select id="morning_cleanser" name="morning[cleanser][]" multiple>
                        @foreach ($produits as $p)
                            <option value="{{ $p->id }}"
                                    data-image="{{ Storage::url($p->photo) }}"
                                @selected(in_array($p->id, explode(',', $morning->cleanser ?? ''))) >
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
                                    data-image="{{ Storage::url($p->photo) }}"
                                @selected(in_array($p->id, explode(',', $morning->toner ?? ''))) >
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
                                    data-image="{{ Storage::url($p->photo) }}"
                                @selected(in_array($p->id, explode(',', $morning->serum ?? ''))) >
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
                                    data-image="{{ Storage::url($p->photo) }}"
                                @selected(in_array($p->id, explode(',', $morning->eye_cream ?? ''))) >
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
                                    data-image="{{ Storage::url($p->photo) }}"
                                @selected(in_array($p->id, explode(',', $morning->cream ?? ''))) >
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
                                    data-image="{{ Storage::url($p->photo) }}"
                                @selected(in_array($p->id, explode(',', $morning->spf ?? ''))) >
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
                                    data-image="{{ Storage::url($p->photo) }}"
                                @selected(in_array($p->id, explode(',', $morning->others ?? ''))) >
                                {{ $p->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <hr>

                <h4>Night Routine</h4>

                @php
                    $night = $guide->routines->where('type', 'night')->first();
                @endphp

                <div class="mb-3">
                    <label>First Cleanser</label>
                    <select id="night_first" name="night[first_cleanser][]" multiple>
                        @foreach ($produits as $p)
                            <option value="{{ $p->id }}"
                                    data-image="{{ Storage::url($p->photo) }}"
                                @selected(in_array($p->id, explode(',', $night->cleanser ?? ''))) >
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
                                    data-image="{{ Storage::url($p->photo) }}"
                                @selected(in_array($p->id, explode(',', $night->second_cleanser ?? ''))) >
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
                                    data-image="{{ Storage::url($p->photo) }}"
                                @selected(in_array($p->id, explode(',', $night->toner ?? ''))) >
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
                                    data-image="{{ Storage::url($p->photo) }}"
                                @selected(in_array($p->id, explode(',', $night->serum ?? ''))) >
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
                                    data-image="{{ Storage::url($p->photo) }}"
                                @selected(in_array($p->id, explode(',', $night->eye_cream ?? ''))) >
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
                                    data-image="{{ Storage::url($p->photo) }}"
                                @selected(in_array($p->id, explode(',', $night->cream ?? ''))) >
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
                                    data-image="{{ Storage::url($p->photo) }}"
                                @selected(in_array($p->id, explode(',', $night->spot_treatment ?? ''))) >
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
                                    data-image="{{ Storage::url($p->photo) }}"
                                @selected(in_array($p->id, explode(',', $night->others ?? ''))) >
                                {{ $p->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
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
                <button type="submit" class="btn btn-success mt-3">Update Guide</button>

            </form>
        </div>
    </div>
        </div></div>
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {

            // Activation TomSelect simple pour Skin Concern
            new TomSelect("#skin_concern", {
                plugins: ['remove_button'],
                maxItems: null,
                placeholder: "Select concerns..."
            });

            // Activation TomSelect simple pour Skin Condition
            new TomSelect("#skin_condition", {
                plugins: ['remove_button'],
                maxItems: null,
                placeholder: "Select conditions..."
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {

            const concernSelect = document.getElementById('skin_concern');
            const concernOther = document.getElementById('skin_concern_other');

            const conditionSelect = document.getElementById('skin_condition');
            const conditionOther = document.getElementById('skin_condition_other');

            function toggleInput(select, input) {
                if ([...select.selectedOptions].some(o => o.value === "others")) {
                    input.style.display = "block";
                } else {
                    input.style.display = "none";
                    input.value = "";
                }
            }

            concernSelect.addEventListener('change', () =>
                toggleInput(concernSelect, concernOther)
            );

            conditionSelect.addEventListener('change', () =>
                toggleInput(conditionSelect, conditionOther)
            );

            // Au chargement (utile pour EDIT)
            toggleInput(concernSelect, concernOther);
            toggleInput(conditionSelect, conditionOther);
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {

            const routineSelects = [
                "morning_cleanser", "morning_toner", "morning_serum", "morning_eye",
                "morning_cream", "morning_spf", "morning_others",
                "night_first", "night_second", "night_toner", "night_serum",
                "night_eye", "night_cream", "night_spot", "night_others",
                // 🆕 WEEKLY
                "weekly_exfoliator", "weekly_face_mask", "weekly_wash_mask"
            ];

            routineSelects.forEach(function (id) {

                if (document.getElementById(id)) {

                    new TomSelect(`#${id}`, {
                        plugins: ['remove_button'],
                        maxItems: null,
                        hideSelected: true,
                        placeholder: "Select product...",

                        render: {
                            option: function(data, escape) {
                                return `
                            <div class="d-flex align-items-center">
                                <img src="${data.image}"
                                     style="width:35px;height:35px;border-radius:6px;object-fit:cover;margin-right:8px;">
                                <span>${escape(data.text)}</span>
                            </div>
                        `;
                            },
                            item: function(data, escape) {
                                return `
                            <div class="d-flex align-items-center">
                                <img src="${data.image}"
                                     style="width:28px;height:28px;border-radius:6px;object-fit:cover;margin-right:6px;">
                                <span>${escape(data.text)}</span>
                            </div>
                        `;
                            }
                        },

                        dataAttr: {
                            image: "data-image"  // Récupère notre data-image=""
                        }
                    });

                }
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
