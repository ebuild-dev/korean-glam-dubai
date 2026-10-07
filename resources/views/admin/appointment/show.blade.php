@extends('admin.fixe')
@section('titre', 'Détails du rendez-vous')

@section('body')

    <div class="page-content-wrapper">
        <div class="page-content">

            <!-- Flash messages -->
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

            <!-- Titre -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="javascript:void(0);">{{ config('app.name') }}</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ route('admin.appointments.index') }}">Appointments</a>
                                </li>
                                <li class="breadcrumb-item active">
                                    Appointment #{{ $appointment->id }}
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CARD -->
            <div class="card radius-15">
                <div class="card-body">

                    <h4 class="mb-3">
                        <i class="ri-calendar-check-line"></i>
                        Appointment Details
                    </h4>

                    <div class="row">

                        <!-- Informations -->
                        <div class="col-md-8">
                            <div class="card p-3">

                                <h5><b>Appointment Information</b></h5>
                                <hr>
                                @if($appointment->user)
                                    <p><b>Client Account:</b> {{ $appointment->user->nom }} (ID: {{ $appointment->user->id }})</p>
                                @endif

                                <p><b>Full Name :</b> {{ $appointment->full_name }}</p>
                                <p><b>Email :</b> {{ $appointment->email }}</p>
                                <p><b>Phone :</b> {{ $appointment->telephone }}</p>

                                <p>
                                    <b>Status :</b>
                                    @if ($appointment->availability)
                                        <span class="badge bg-success">Confirmed</span>
                                    @else
                                        <span class="badge bg-danger">Not Confirmed</span>
                                    @endif
                                </p>

                                <p>
                                    <b>Date & Hour :</b>
                                    {{ \Carbon\Carbon::parse($appointment->date_appointment)->format('d/m/Y H:i') }}
                                </p>

                                @if ($appointment->notes)
                                    <p><b>Notes :</b><br>{{ $appointment->notes }}</p>
                                @endif

                                @if ($appointment->file)
                                    @php
                                        $ext = strtolower(pathinfo($appointment->file, PATHINFO_EXTENSION));
                                        $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                    @endphp

                                    @if ($isImage)
                                        <p class="mt-2">
                                            <b>Attached Photo :</b><br>

                                            <!-- IMAGE CLIQUABLE -->
                                            <img src="{{ asset('storage/' . $appointment->file) }}"
                                                 alt="Captured Photo"
                                                 class="img-thumbnail mt-2"
                                                 style="max-width: 250px; cursor:pointer;"
                                                 data-bs-toggle="modal"
                                                 data-bs-target="#imageModal">
                                        </p>

                                        <!-- MODAL -->
                                        <div class="modal fade" id="imageModal" tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                <div class="modal-content bg-dark">
                                                    <div class="modal-body text-center p-0">
                                                        <img src="{{ asset('storage/' . $appointment->file) }}"
                                                             class="img-fluid"
                                                             alt="Full size"
                                                             style="max-height:90vh;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    @else
                                        <!-- NOT IMAGE -->
                                        <p class="mt-2">
                                            <b>Attached File :</b><br>
                                            <a href="{{ asset('storage/' . $appointment->file) }}"
                                               class="btn btn-sm btn-outline-primary"
                                               target="_blank">
                                                <i class="ri-file-line"></i> View File
                                            </a>
                                        </p>
                                    @endif

                                @endif




                                <!-- BOUTONS CONFIRMATION -->
                                @if (!$appointment->availability)

                                    <!-- BOUTON JAUNE (DEMANDE DE CONFIRMATION) -->
                                    <button id="showConfirmBtn" class="btn btn-warning mt-3">
                                        <i class="ri-alert-line"></i> Confirm appointment
                                    </button>

                                    <!-- BOUTON VERT (FORMULAIRE PATCH – CACHÉ PAR DÉFAUT) -->
                                    <form id="confirmForm"
                                          action="{{ route('admin.appointments.confirm', $appointment->id) }}"
                                          method="POST"
                                          class="d-none mt-2">
                                        @csrf
                                        @method('PATCH')

                                        <button type="submit" class="btn btn-success">
                                            <i class="ri-checkbox-circle-line"></i>Are you sure you want to confirm this appointment?
                                        </button>
                                    </form>

                                @else
                                    <button class="btn btn-success mt-3" disabled>
                                        <i class="ri-check-double-line"></i> Appointment Confirmed
                                    </button>
                                @endif
                                <script>
                                    document.getElementById('showConfirmBtn')?.addEventListener('click', function () {
                                        document.getElementById('confirmForm').classList.remove('d-none'); // Affiche le vrai bouton
                                        this.classList.add('d-none'); // Cache le bouton jaune
                                    });
                                </script>





                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card p-3">
                                {{-- 🔹 Affichage du lien de réunion si existe --}}
                                @if ($appointment->meeting_code)
                                    <p class="mt-3">
                                        <b>Online Meeting :</b><br>
                                        <a href="https://meet.jit.si/{{ $appointment->meeting_code }}"
                                           target="_blank"
                                           class="btn btn-info btn-sm mt-1">
                                            <i class="ri-video-chat-line"></i> Join Meeting
                                        </a>
                                        <br>
                                        <small class="text-muted">
                                            Meeting Link: https://meet.jit.si/{{ $appointment->meeting_code }}
                                        </small>
                                    </p>

                                    <hr>
                                    <h5><b>Skin Guide</b></h5>

                                    @php
                                        // Guide de l'utilisateur du rendez-vous (s'il existe)
                                        $userGuide = optional(optional($appointment->user)->guides)->first();

                                        // Guide lié au rendez-vous (s'il existe)
                                        $appointmentGuide = optional($appointment->guides)->first();
                                    @endphp


                                @if ($userGuide)
                                        <button class="btn btn-warning btn-sm w-100 mt-2"
                                                onclick="window.location.href='{{ route('guides.edit', $userGuide->id) }}'">
                                            <i class="ri-edit-line"></i> Edit Guide
                                        </button>
                                    @elseif ($appointmentGuide && $appointmentGuide->user_id == $appointment->user_id)
                                        <button class="btn btn-warning btn-sm w-100 mt-2"
                                                onclick="window.location.href='{{ route('guides.edit', $appointmentGuide->id) }}'">
                                            <i class="ri-edit-line"></i> Edit Guide
                                        </button>
                                    @else
                                        <button class="btn btn-primary w-100 mt-2"
                                                onclick="window.location.href='{{ route('guides.create', ['appointment' => $appointment->id, 'client' => optional($appointment->user)->id]) }}'">
                                            <i class="ri-magic-line"></i> Create Guide
                                        </button>
                                    @endif
                                @endif


                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

@endsection
