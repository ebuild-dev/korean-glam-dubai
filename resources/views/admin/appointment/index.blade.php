@extends('admin.fixe')

@section('titre', 'Gestion rendez vous')

@section('body')

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>


    <div class="page-content-wrapper">
        <div class="page-content">
            <div class="container py-4">

                <div class="row mb-3">
                    <div class="col-12">
                        <div class="page-title-box">
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item">
                                        <a href="javascript: void(0);">{{ config('app.name') }}</a>
                                    </li>

                                    <li class="breadcrumb-item active">
                                        Gestion des Rendez vous
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card radius-15 shadow-sm">
                    <div class="card-body">

                        <!-- 🔍 Filtre -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="mb-0">
                                <i class="ri-filter-3-line"></i> Filtrer les rendez-vous
                            </h5>

                            <select id="filterStatus" class="form-select" style="max-width: 220px;">
                                <option value="all">🔄 Tous</option>
                                <option value="confirmed">🟢 Confirmés</option>
                                <option value="pending">🔴 Non Confirmés</option>
                            </select>
                        </div>

                        <!-- 🗓️ Calendrier -->
                        <div id="calendar" ></div>

                    </div>
                </div>


            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'fr',
                height: 650,

                events: "{{ route('admin.appointments.calendar') }}",

                eventClick: function (info) {
                    window.location.href = "/admin/appointments/" + info.event.id;
                }
            });

            calendar.render();
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            var calendarEl = document.getElementById('calendar');
            var filter = 'all';

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'fr',
                height: 650,

                events: function(fetchInfo, successCallback, failureCallback) {
                    fetch("{{ route('admin.appointments.calendar') }}?filter=" + filter)
                        .then(res => res.json())
                        .then(data => successCallback(data))
                        .catch(err => failureCallback(err));
                },

                eventClick: function (info) {

                    Swal.fire({
                        title: "Rendez-vous",
                        html: `
            <b>${info.event.title}</b><br>
            ${info.event.start.toLocaleString()}<br><br>
            <button id="viewBtn" class="btn btn-primary w-100 mb-2">
                Voir le rendez-vous
            </button>
            <button id="deleteBtn" class="btn btn-danger w-100">
                Supprimer
            </button>
        `,
                        showConfirmButton: false,
                        didOpen: () => {

                            // Bouton voir
                            document.getElementById("viewBtn").onclick = () => {
                                window.location.href = "/admin/appointments/" + info.event.id;
                            };

                            // Bouton supprimer
                            document.getElementById("deleteBtn").onclick = () => {

                                Swal.fire({
                                    title: "Supprimer ce rendez-vous ?",
                                    text: "Action irréversible.",
                                    icon: "warning",
                                    showCancelButton: true,
                                    confirmButtonText: "Supprimer",
                                    cancelButtonText: "Annuler",
                                    confirmButtonColor: "#e3342f"
                                }).then((result) => {

                                    if (result.isConfirmed) {
                                        fetch("/admin/appointments/" + info.event.id, {
                                            method: "DELETE",
                                            headers: {
                                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                            }
                                        })
                                            .then(res => res.json())
                                            .then(data => {

                                                if (data.success) {
                                                    Swal.fire("Supprimé !", data.message, "success");
                                                    info.event.remove(); // enlève du calendrier
                                                }

                                            });
                                    }
                                });
                            };
                        }
                    });

                }

            });

            calendar.render();

            // 🔥 Filtres
            document.getElementById('filterStatus').addEventListener('change', function () {
                filter = this.value;
                calendar.refetchEvents(); // recharge les événements
            });
        });
    </script>


@endsection

