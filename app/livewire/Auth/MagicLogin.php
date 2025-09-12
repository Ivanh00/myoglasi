<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\MagicLink;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class MagicLogin extends Component
{
    public $email = '';
    public $showEmailSent = false;

    public function sendMagicLink()
    {
        $this->validate([
            'email' => 'required|email|max:255'
        ]);

        // Check if SMTP is configured (basic check)
        if (!config('mail.mailers.smtp.host') && Setting::get('email_verification_enabled', false)) {
            session()->flash('error', 'Email servis nije podešen. Koristite obični login.');
            return;
        }

        // Create magic link
        $magicLink = MagicLink::createForEmail($this->email);

        try {
            // Send email
            Mail::send('emails.magic-link', ['magicLink' => $magicLink], function ($message) {
                $message->to($this->email)
                        ->subject('Prijavite se na MyOglasi - Magic Link');
            });

            $this->showEmailSent = true;
            session()->flash('success', 'Magic link je poslat na ' . $this->email);
            
        } catch (\Exception $e) {
            session()->flash('error', 'Greška pri slanju email-a. Probajte obični login.');
        }
    }

    public function resetForm()
    {
        $this->email = '';
        $this->showEmailSent = false;
    }

    public function render()
    {
        return view('livewire.auth.magic-login');
    }
}
