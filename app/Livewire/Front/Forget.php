<?php

namespace App\Livewire\Front;

use App\Mail\forget as MailForget;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Throwable;
use Livewire\Component;

class Forget extends Component
{
    public $email;
    public $end = false;

    public function render()
    {
        return view('livewire.front.forget');
    }

    public function forget(){
        $this->validate([
            'email' =>'required|email|exists:users,email'
        ],[
            'email.required' => 'Veuillez entrer votre email',
            'email.email' => 'Veuillez entrer un email valide',
            'email.exists' => 'Cet email n\'existe pas !'
        ]);

        $user = User::where('email', $this->email)->where('role','client')->first();
        if(!$user){
            session()->flash('error', 'This email address is not associated with a customer account');
            return;
        }
        
        $resetToken = $user->createToken('Reset Password');
        $token = $resetToken->plainTextToken;

        try {
            $user->update(['token' => $token]);
            $sent = Mail::to($this->email)->send(new MailForget($user, route('password.reset', ['token' => $token])));
            if (!$sent) {
                throw new \RuntimeException('Mail transport did not send the password reset email.');
            }

            $this->end = true;
        } catch (Throwable $e) {
            $user->update(['token' => null]);
            $resetToken->accessToken->delete();
            Log::error('Password reset email could not be sent.', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            session()->flash('error', 'We could not send the password reset email. Check your email address and try again.');
        }
    }
}
