<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Message;
use App\Models\VerificationDocument;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VerificationRequest extends Component
{
    use WithFileUploads;

    public $idFront;
    public $idBack;
    public $firstName = '';
    public $lastName = '';
    public $streetAddress = '';
    public $streetNumber = '';
    public $city = '';

    public $status;
    public $rejectionComment;
    public $existingDocument;

    public function mount()
    {
        $user = Auth::user();
        $this->status = $user->verification_status;
        $this->city = $user->city ?? '';
        $this->existingDocument = $user->verificationDocument;

        if ($this->existingDocument) {
            $this->firstName = $this->existingDocument->first_name;
            $this->lastName = $this->existingDocument->last_name;
            $this->streetAddress = $this->existingDocument->street_address;
            $this->streetNumber = $this->existingDocument->street_number;
            $this->city = $this->existingDocument->city;
        }

        if ($this->status === 'rejected') {
            $this->rejectionComment = $user->verification_comment;
        }
    }

    public function submit()
    {
        $user = Auth::user();

        if (in_array($user->verification_status, ['pending', 'verified'])) {
            session()->flash('error', 'Zahtev je već podnet ili je nalog verifikovan.');
            return;
        }

        $this->validate([
            'firstName' => ['required', 'string', 'max:255'],
            'lastName' => ['required', 'string', 'max:255'],
            'idFront' => ['required', 'image', 'max:5120'],
            'idBack' => ['required', 'image', 'max:5120'],
            'streetAddress' => ['required', 'string', 'max:255'],
            'streetNumber' => ['required', 'string', 'max:20'],
            'city' => ['required', 'string', 'max:255'],
        ], [
            'firstName.required' => 'Ime je obavezno.',
            'lastName.required' => 'Prezime je obavezno.',
            'idFront.required' => 'Slika prednje strane lične karte je obavezna.',
            'idFront.image' => 'Fajl mora biti slika.',
            'idFront.max' => 'Slika ne sme biti veća od 5MB.',
            'idBack.required' => 'Slika zadnje strane lične karte je obavezna.',
            'idBack.image' => 'Fajl mora biti slika.',
            'idBack.max' => 'Slika ne sme biti veća od 5MB.',
            'streetAddress.required' => 'Ulica je obavezna.',
            'streetNumber.required' => 'Broj je obavezan.',
            'city.required' => 'Grad je obavezan.',
        ]);

        // Delete old files if re-submitting
        $existing = $user->verificationDocument;
        if ($existing) {
            Storage::disk('local')->delete($existing->id_front_path);
            Storage::disk('local')->delete($existing->id_back_path);
        }

        $dir = 'verification_documents/' . $user->id;
        $frontPath = $this->idFront->store($dir, 'local');
        $backPath = $this->idBack->store($dir, 'local');

        VerificationDocument::updateOrCreate(
            ['user_id' => $user->id],
            [
                'first_name' => $this->firstName,
                'last_name' => $this->lastName,
                'id_front_path' => $frontPath,
                'id_back_path' => $backPath,
                'street_address' => $this->streetAddress,
                'street_number' => $this->streetNumber,
                'city' => $this->city,
            ]
        );

        $user->requestVerification();

        // Notify admin
        Message::create([
            'sender_id' => $user->id,
            'receiver_id' => 1,
            'message' => "Korisnik {$this->firstName} {$this->lastName} ({$user->email}) je zatražio verifikaciju naloga.",
            'subject' => 'Zahtev za verifikaciju - ' . $this->firstName . ' ' . $this->lastName,
            'is_system_message' => true,
            'is_read' => false,
        ]);

        $this->status = 'pending';
        $this->existingDocument = $user->fresh()->verificationDocument;
        $this->idFront = null;
        $this->idBack = null;

        session()->flash('success', 'Zahtev za verifikaciju je uspešno podnet. Bićete obavešteni o odluci.');
    }

    public function render()
    {
        return view('livewire.verification-request')
            ->layout('layouts.app');
    }
}
