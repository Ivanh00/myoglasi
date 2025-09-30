<?php

namespace App\Livewire\Services;

use Livewire\Component;
use App\Models\Service;
use App\Models\User;
use App\Models\Message;
use App\Models\ServiceReport;

class ReportService extends Component
{
    public $service;
    public $reportReason = '';
    public $reportDetails = '';
    public $showSuccessModal = false;

    public $reportReasons = [
        'inappropriate_content' => 'Neprikladan sadržaj',
        'fake_service' => 'Lažna usluga',
        'spam' => 'Spam',
        'wrong_category' => 'Pogrešna kategorija',
        'overpriced' => 'Previsoka cena',
        'scam' => 'Prevara',
        'duplicate' => 'Duplikat usluga',
        'other' => 'Ostalo'
    ];

    protected $rules = [
        'reportReason' => 'required|string',
        'reportDetails' => 'required|string|min:10|max:1000'
    ];

    protected $messages = [
        'reportReason.required' => 'Molimo odaberite razlog prijave.',
        'reportDetails.required' => 'Molimo unesite detaljne informacije.',
        'reportDetails.min' => 'Opis mora imati najmanje 10 karaktera.',
        'reportDetails.max' => 'Opis može imati maksimalno 1000 karaktera.'
    ];

    public function mount($slug)
    {
        $this->service = Service::where('slug', $slug)->with('user')->firstOrFail();

        // Check if user is trying to report their own service
        if (auth()->id() === $this->service->user_id) {
            session()->flash('error', 'Ne možete prijaviti svoju uslugu.');
            return redirect()->route('services.show', $this->service);
        }

        // Check if user has already reported this service
        $existingReport = ServiceReport::where('user_id', auth()->id())
            ->where('service_id', $this->service->id)
            ->first();

        if ($existingReport) {
            session()->flash('info', 'Već ste prijavili ovu uslugu.');
            return redirect()->route('services.show', $this->service);
        }
    }

    public function submitReport()
    {
        $this->validate();

        try {
            // Find admin user (first admin or specific admin)
            $admin = User::where('is_admin', true)->first();

            if (!$admin) {
                session()->flash('error', 'Greška: Admin nije pronađen. Kontaktirajte podršku.');
                return;
            }

            // Create report record
            $report = ServiceReport::create([
                'user_id' => auth()->id(),
                'service_id' => $this->service->id,
                'reason' => $this->reportReason,
                'details' => $this->reportDetails,
                'status' => 'pending'
            ]);

            // Create report message to admin
            $reportMessage = "PRIJAVA USLUGE\n\n" .
                           "Korisnik: " . auth()->user()->name . " (" . auth()->user()->email . ")\n" .
                           "Usluga: " . $this->service->title . "\n" .
                           "Razlog: " . $this->reportReasons[$this->reportReason] . "\n\n" .
                           "Detalji:\n" . $this->reportDetails . "\n\n" .
                           "Link usluge: " . route('services.show', $this->service) . "\n" .
                           "Report ID: " . $report->id;

            Message::create([
                'sender_id' => auth()->id(),
                'receiver_id' => $admin->id,
                'listing_id' => null,
                'message' => $reportMessage,
                'subject' => 'Prijava usluge: ' . $this->service->title,
                'is_system_message' => false,
                'is_read' => false
            ]);

            // Show success modal instead of redirect
            $this->showSuccessModal = true;

            // Reset form
            $this->reportReason = '';
            $this->reportDetails = '';

        } catch (\Exception $e) {
            session()->flash('error', 'Greška pri slanju prijave. Molimo pokušajte ponovo.');
        }
    }

    public function closeSuccessModal()
    {
        $this->showSuccessModal = false;
        return redirect()->route('services.show', $this->service);
    }

    public function render()
    {
        return view('livewire.services.report-service')
            ->layout('layouts.app');
    }
}
