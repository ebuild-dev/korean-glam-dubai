<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AppointmentConfirmedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    public function build()
    {
        $meetUrl = "https://meet.jit.si/" . $this->appointment->meeting_code;


        return $this->subject('Your Appointment is Confirmed')
            ->markdown('Mail.appointment_confirmed')
            ->with([
                'full_name' => $this->appointment->full_name,
                'date'      => \Carbon\Carbon::parse($this->appointment->date_appointment)->format('d/m/Y H:i'),
                'meet_url'  => $meetUrl,
            ]);

    }
}
