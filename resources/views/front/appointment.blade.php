@extends('front.fixe')

@section('titre', 'Book a Skincare Appointment in Tunisia')

@section('meta')
    <meta name="description"
          content="Book a personalized skincare appointment in Tunisia with Korean Glam By Ameni. Professional Korean beauty treatments, expert advice, and tailored skincare routines.">

    <meta name="keywords"
          content="book skincare appointment tunisia, korean skincare consultation tunisia, beauty appointment tunisia, korean glam appointment">

    <!-- OPEN GRAPH -->
    <meta property="og:title"
          content="Book a Skincare Appointment | Korean Glam By Ameni">

    <meta property="og:description"
          content="Schedule a personalized Korean skincare consultation in Tunisia. Professional treatments and expert beauty advice.">

    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">

    <!-- TWITTER -->
    <meta name="twitter:title"
          content="Book a Skincare Appointment | Korean Glam By Ameni">

    <meta name="twitter:description"
          content="Book your personalized Korean skincare appointment in Tunisia with expert guidance.">
@endsection

@section('body')

    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">

    <style>


        h2 {
            color: #ff8fb3;
            font-weight: 700;
            letter-spacing: 1px;
        }

        /* 🌸 CALENDAR PINK STYLE */
        #calendar {
            background: white;
            border: 2px solid #ffc0cb;
            border-radius: 15px;
            padding: 10px;
            box-shadow: 0 4px 15px rgba(255, 192, 203, 0.4);
        }

        .fc-toolbar-title {
            color: #ff5c8d !important;
            font-weight: bold;
        }

        .fc-button-primary {
            background: #ffc0cb !important;
            border: none !important;
            color: white !important;
            font-weight: bold;
            border-radius: 8px !important;
        }

        .fc-button-primary:hover {
            background: #ff8fb3 !important;
        }

        .fc-daygrid-day:hover {
            background: #ffe5ed !important;
            cursor: pointer;
        }

        .fc-daygrid-day.fc-day-today {
            background: #ffe5ed !important;
            border: 1px solid #ff8fb3 !important;
        }

        /* 🌸 SELECTED DAY */
        .selected-day {
            background: #ffc0cb !important;
            color: white !important;
            border-radius: 8px;
        }

        /* 🌸 MODAL BEAUTY STYLE */
        .modal-content {
            border-radius: 20px;
            border: 2px solid #ffc0cb;

        }

        .modal-header {

            border-bottom: 2px solid #ffc0cb;
        }

        .modal-title {
            color: #ff5c8d;
            font-weight: bold;
        }

        .btn-rose {
            background: #ff8fb3;
            border: none;
            font-weight: bold;
            color: #FFFFFF;
            box-shadow: 0 4px 10px rgba(255, 143, 179, 0.4);
        }

        .btn-rose:hover {
            background: #ff5c8d;
            color: #FFFFFF;
            box-shadow: 0 5px 18px rgba(255, 92, 141, 0.5);
        }

        /* 🌸 FORM INPUTS */
        input, textarea, select {
            border-radius: 10px !important;
            border: 1px solid #ffc0cb !important;
            padding: 10px !important;
        }

        input:focus, textarea:focus, select:focus {
            border-color: #ff8fb3 !important;
            box-shadow: 0 0 5px rgba(255, 143, 179, 0.7) !important;
        }
        .hours-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }

        .hour-btn {
            padding: 15px;
            border: 2px solid #ff8fb3;
            border-radius: 12px;
            text-align: center;
            font-weight: bold;
            cursor: pointer;
            background: white;
            color: #ff5c8d;
            transition: 0.2s;
        }

        .hour-btn:hover {
            background: #ffe5ef;
        }

        .hour-btn.selected {
            background: #ff8fb3;
            color: white;
        }

        .hour-btn.disabled {
            background: #f5f5f5;
            border-color: #c9c9c9;
            color: #999;
            cursor: not-allowed;
        }

    </style>

    <div class="container py-5" style="max-width: 800px;">
        <h2 class="mb-4 text-center">Book an Appointment</h2>

        <!-- CALENDRIER -->
        <div id="calendar"></div>
        <input type="hidden" id="selected_date">

    </div>

    <div class="modal fade" id="appointmentModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Select an Appointment Hour</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <!-- GRID DES HEURES -->
                    <div id="hoursGrid" class="hours-grid mb-4"></div>

                    <!-- FORMULAIRE QUI S'AFFICHE APRÈS CHOIX DE L'HEURE -->
                    <form id="appointmentForm" class="d-none" enctype="multipart/form-data">

                        @csrf

                        <input type="hidden" name="date_appointment" id="selected_date_full">
                        <input type="hidden" name="user_id" value="{{ Auth::id() }}">

                        <div class="mb-3">
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="full_name" class="form-control" required
                                   value="{{ Auth::check() ? Auth::user()->nom : '' }}"
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" class="form-control" required
                                   value="{{ Auth::check() ? Auth::user()->email : '' }}"
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phone *</label>
                            <input type="text" name="telephone" class="form-control" required
                                   value="{{ Auth::check() ? Auth::user()->phone: '' }}"
                            >
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Upload File (optional)</label>
                            <input type="file" name="file" class="form-control">
                        </div>
                        <div class="mb-3 desktop-only">
                            <label class="form-label">Take a Photo</label>

                            <button type="button" class="btn btn-rose" id="openCameraBtn">
                                📸 Open Camera
                            </button>

                            <div class="mt-3">
                                <video id="cameraStream" class="d-none rounded w-100" autoplay></video>
                                <canvas id="cameraCapture" class="d-none w-100 rounded mt-3"></canvas>
                            </div>

                            <input type="hidden" name="photo_capture" id="capturedPhoto">

                            <div id="photoStatus" class="text-success mt-2 d-none fw-bold">
                                📸 Photo successfully captured!
                            </div>
                        </div>


                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control"></textarea>
                        </div>

                        <div id="message" class="alert d-none"></div>

                        <button type="submit" class="btn btn-rose w-100">Confirm Appointment</button>

                    </form>

                </div>

            </div>
        </div>
    </div>

@endsection


@section('scripts')

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>

        document.addEventListener('DOMContentLoaded', function () {

            let calendarEl = document.getElementById('calendar');

            let calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                selectable: true,
                height: 550,

                dateClick: function(info) {

                    document.querySelectorAll('.fc-daygrid-day').forEach((d) =>
                        d.classList.remove("selected-day")
                    );

                    info.dayEl.classList.add("selected-day");

                    // Stocke la date choisie
                    $("#selected_date").val(info.dateStr);
                    $("#selected_date_full").val("");

                    // Cache le formulaire jusqu’à sélection de l’heure
                    $("#appointmentForm").addClass("d-none");

                    // Charger les heures
                    loadHours(info.dateStr);

                    // Ouvrir le modal
                    let modal = new bootstrap.Modal(document.getElementById('appointmentModal'));
                    modal.show();
                }
            });

            calendar.render();
        });


        const hours = [
            "09:00", "10:00", "11:00",
            "13:00", "14:00", "15:00",
            "16:00", "17:00", "18:00"
        ];


        function loadHours(date) {

            $.ajax({
                url: "/appointments/check/" + date,
                method: "GET",
                success: function(res) {

                    let taken = res.taken;
                    let grid = $("#hoursGrid");
                    grid.html("");

                    // 🔥 Date actuelle
                    let now = new Date();
                    let today = now.toISOString().split('T')[0];
                    let currentHour = now.getHours();
                    let currentMinutes = now.getMinutes();

                    hours.forEach(h => {

                        let isTaken = taken.includes(h);
                        let isPast = false;

                        let selectedDate = new Date(date);
                        let todayDate = new Date(today);

                        // ❌ CAS 1 : date passée
                        if (selectedDate < todayDate) {
                            isPast = true;
                        }

                        // ❌ CAS 2 : aujourd'hui → heures passées
                        if (date === today) {
                            let [hour, minute] = h.split(":").map(Number);

                            if (
                                hour < currentHour ||
                                (hour === currentHour && minute <= currentMinutes)
                            ) {
                                isPast = true;
                            }
                        }

                        let btn = $(`
                    <div class="hour-btn ${isTaken || isPast ? 'disabled' : ''}">
                        ${h}
                    </div>
                `);

                        // ✅ Cliquable seulement si dispo
                        if (!isTaken && !isPast) {
                            btn.on("click", function() {

                                $(".hour-btn").removeClass("selected");
                                btn.addClass("selected");

                                $("#appointmentForm").removeClass("d-none");
                                $("#selected_date_full").val(date + " " + h);
                            });
                        }

                        grid.append(btn);
                    });
                }
            });
        }


        $("#appointmentForm").on("submit", async function(e) {
            e.preventDefault();

            let fullDate = $("#selected_date_full").val();

            if (!fullDate) {
                Swal.fire({
                    icon: "warning",
                    title: "Missing Hour",
                    text: "Please choose an hour before submitting.",
                    confirmButtonColor: "#ff8fb3",
                });
                return;
            }

            let formData = new FormData(this);
            formData.set("date_appointment", fullDate);
            let base64 = $("#capturedPhoto").val();
            if (base64) {
                const blob = await fetch(base64).then(res => res.blob());
                formData.append("file", blob, "captured_photo.png");
            }
            $.ajax({
                url: "{{ route('appointments.store') }}",
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,

                success: function(res) {

                    Swal.fire({
                        icon: "success",
                        title: "Appointment Confirmed",
                        text: res.message,
                        confirmButtonColor: "#ff8fb3"
                    });

                    $("#appointmentForm")[0].reset();
                    setTimeout(() => location.reload(), 1800);
                },

                error: function(xhr) {

                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let msg = "Please fill all required fields.";

                        if (errors.file) {
                            msg = errors.file[0];  // Affiche l’erreur de taille ou type fichier
                        }

                        Swal.fire({
                            icon: "error",
                            title: "Validation Error",
                            text: msg,
                            confirmButtonColor: "#ff8fb3"
                        });

                        return;
                    }

                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Something went wrong. Please try again.",
                        confirmButtonColor: "#ff8fb3"
                    });
                }
            });
        });



    </script>
    <script>
        let video = document.getElementById("cameraStream");
        let canvas = document.getElementById("cameraCapture");
        let capturedInput = document.getElementById("capturedPhoto");

        document.getElementById("openCameraBtn").addEventListener("click", async () => {

            // Demande accès caméra
            const stream = await navigator.mediaDevices.getUserMedia({ video: true });

            video.srcObject = stream;
            video.classList.remove("d-none");

            video.onclick = function () {

                // Capture en respectant le ratio exact
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;

                const ctx = canvas.getContext("2d");
                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

                canvas.classList.remove("d-none");

                // Base64 pour envoyer au backend
                capturedInput.value = canvas.toDataURL("image/png");

                // Message pro
                let status = document.getElementById("photoStatus");
                status.textContent = "📸 Photo captured successfully!";
                status.classList.remove("d-none");
            };
        });
    </script>
    <script>
        if (window.innerWidth < 992) {
            document.querySelector('.desktop-only').remove();
        }
    </script>

@endsection
