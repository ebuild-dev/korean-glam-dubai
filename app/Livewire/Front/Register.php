<?php

namespace App\Livewire\Front;

use App\Mail\register as MailRegister;
use App\Models\User;
use Throwable;
use Illuminate\Support\Facades\Hash;
use App\Services\RegistrationCaptcha;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Register extends Component
{

    public $nom, $email, $phone, $password,  $recaptchaToken;

    public function render()
    {
        return view('livewire.front.register');
    }



    public function register()
    {
        $this->resetErrorBag('recaptcha');
        // Validation des données
        $this->validate([
            'nom' => 'required|string|max:250',
            'email' => 'required|email|unique:users',
            'phone' => 'required|numeric',
            'password' => 'required|min:8',
            'recaptchaToken' => 'required|string', // validation obligatoire

        ], [
            'nom.required' => 'Please enter your name',
            'nom.string' => 'Please enter a valid name',
            'nom.max' => 'Your name must not exceed 250 characters',
            'email.required' => 'Please enter your email address',
            'email.email' => 'Please enter a valid email address',
            'email.unique' => 'This email address is already in use',
            'phone.required' => 'Please enter your phone number',
            'phone.numeric' => 'Please enter a valid phone number',
            'password.required' => 'Please enter your password',
            'password.min' => 'Your password must contain at least 8 characters',
            'recaptchaToken.required' => 'Anti-bot verification is missing. Please try again.',
            'recaptchaToken.string' => 'Anti-bot verification is invalid. Please try again.',
        ]);

        $captchaError = app(RegistrationCaptcha::class)->error($this->recaptchaToken, request()->ip());
        $this->recaptchaToken = null;
        if ($captchaError !== null) {
            $this->addError('recaptcha', $captchaError);
            return;
        }

        try {
            DB::beginTransaction();
            $user = new User();
            $user->nom = $this->nom;
            $user->email = $this->email;
            $user->phone = $this->phone;
            $user->password = Hash::make($this->password);
            $user->save();
            DB::commit();
        } catch (Throwable $e) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
            Log::error('Registration failed.', ['error' => $e->getMessage()]);
            session()->flash('error', 'An error occurred while creating your account. Please try again.');
            return ;
        }

        try {
            $sent = Mail::to($user->email)->send(new MailRegister($user));
            if (!$sent) {
                throw new \RuntimeException('Mail transport did not send the registration email.');
            }
        } catch (Throwable $e) {
            Log::error('Registration email could not be sent.', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            return redirect()->route('login')
                ->with('success', 'Your account has been created. You can now log in.')
                ->with('warning', 'The confirmation email could not be sent. Contact us if you need help.');
        }

        return redirect()->route('login')->with('success', 'Your account has been created! You can now log in.');
    }
}
