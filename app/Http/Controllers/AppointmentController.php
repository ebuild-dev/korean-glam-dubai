<?php

namespace App\Http\Controllers;

//use App\Mail\AppointmentMail;
use App\Mail\AppointmentConfirmedMail;
use App\Mail\AppointmentMail;
use App\Mail\AppointmentReminderMail;
use App\Models\Appointment;
/*use App\Models\Notification;*/

use App\Models\notifications;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class AppointmentController extends Controller
{
    public function index()
    {

        $appointments = Appointment::latest()->paginate(10);
        return view('admin.appointment.index', compact('appointments',));
    }

    public function show(Appointment $appointment)
    {
        return view('admin.appointment.show', compact('appointment'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email',
            'telephone' => 'required|string|max:20',
            'file' => 'nullable|file',
            'notes' => 'nullable|string',

            'date_appointment' => 'required|date_format:Y-m-d H:i',
        ]);

        // Sauvegarde du fichier si présent
        if ($request->hasFile('file')) {
            $validated['file'] = $request->file('file')->store('appointments', 'public');
        }
        if (Auth::check()) {
            $validated['user_id'] = Auth::id();
        }

        // Enregistrement
        $appointment = Appointment::create($validated);
        //generer la notification
       $notif = new notifications();
        $notif->type = "appointment";
        $notif->titre = auth()->user()->nom ?? $validated['full_name'];
        $notif->url = "/admin/appointments/" . $appointment->id;
        $notif->message = "Nouvelle rendez vous";
        $notif->save();
        try {
            Mail::to($appointment->email)->send(new AppointmentMail($appointment));
        } catch (\Throwable $e) {

            // 📝 Log l’erreur dans laravel.log
            Log::error("Erreur lors de l'envoi du mail de rendez-vous : " . $e->getMessage());

            // 👉 Optionnel : renvoyer un message d’erreur visible
            return response()->json([
                'success' => false,
                'message' => 'The request has been saved, but the confirmation email could not be sent.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Appointment successfully booked!',
            'appointment' => $appointment
        ]);
    }


    public function check($date)
    {
        // Récupère uniquement la date (pas l'heure)
        $day = Carbon::parse($date)->format('Y-m-d');

        // Récupère les heures réservées pour cette date
        $taken = Appointment::whereDate('date_appointment', $day)
            ->where('availability', true) // 🔥 FILTRE IMPORTANT
            ->pluck('date_appointment')
            ->map(fn($d) => Carbon::parse($d)->format('H:i'))
            ->toArray();

        return response()->json([
            'taken' => $taken
        ]);
    }

    public function getAppointments(Request $request)
    {
        try {
            $filter = $request->get('filter', 'all');

            $query = Appointment::query();

            if ($filter === 'confirmed') {
                $query->where('availability', true);
            } elseif ($filter === 'pending') {
                $query->where('availability', false);
            }

            $appointments = $query->get();

            $events = $appointments->map(function ($appointment) {
                $start = \Carbon\Carbon::parse($appointment->date_appointment)
                    ->setTimezone('Africa/Tunis')
                    ->toIso8601String();

                return [
                    'id' => $appointment->id,
                    'title' => $appointment->full_name,
                    'start' => $start,
                    'end' => $start,
                    'backgroundColor' => $appointment->availability ? '#28a745' : '#dc3545', // vert / rouge
                    'borderColor' => $appointment->availability ? '#28a745' : '#dc3545',
                    'textColor' => '#fff',
                ];
            });

            return response()->json($events);

        } catch (\Exception $e) {
            Log::error('Error loading appointments: ' . $e->getMessage());
            return response()->json(['error' => 'Error loading appointments'], 500);
        }
    }


    public function confirm(Appointment $appointment)
    {
        try {

            // Génère un meeting seulement si absent
            if (!$appointment->meeting_code) {
                $appointment->meeting_code = 'korean_glam_meet_' . uniqid();
            }

            // Mise à jour
            $appointment->availability = true;
            $appointment->save();

            // Tentative d’envoi email
            try {
                Mail::to($appointment->email)->send(new AppointmentConfirmedMail($appointment));

            } catch (\Throwable $e) {

                Log::error("Erreur mail : " . $e->getMessage());

                return redirect()
                    ->route('admin.appointments.show', $appointment->id)
                    ->with('warning', "Appointment confirmed but the email could not be sent.")
                    ->with('success', 'Appointment confirmed successfully.');
            }
            // 2️⃣ Email de rappel 1 heure avant
            $meetingTime   = Carbon::parse($appointment->date_appointment, 'Africa/Tunis');
            $reminderTime  = $meetingTime->copy()->subHour(); // ⬅️ Rappel -1h

            // ⏰ Envoi au client
            Mail::to($appointment->email)
                ->later($reminderTime, new AppointmentReminderMail($appointment));

            // ⏰ Envoi à l'admin
            Mail::to("contact@korean-glam.tn")
                ->later($reminderTime, new AppointmentReminderMail($appointment));

            // OK avec email
            return redirect()
                ->route('admin.appointments.show', $appointment->id)
                ->with('success', 'Appointment confirmed successfully.');

        } catch (\Exception $e) {

            return redirect()
                ->route('admin.appointments.show', $appointment->id)
                ->with('error', 'An error occurred while confirming the appointment.');
        }
    }

    public function destroy(Appointment $appointment)
    {
        try {
            // Supprimer le fichier si présent
            if ($appointment->file && Storage::disk('public')->exists($appointment->file)) {
                Storage::disk('public')->delete($appointment->file);
            }

            // Supprimer le rendez-vous
            $appointment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Rendez-vous supprimé avec succès !'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression du rendez-vous : ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la suppression du rendez-vous.'
            ], 500);
        }
    }
}
