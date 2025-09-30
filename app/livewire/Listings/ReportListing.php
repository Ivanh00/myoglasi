<?php

namespace App\Livewire\Listings;

use Livewire\Component;
use App\Models\Listing;
use App\Models\User;
use App\Models\Message;
use App\Models\ListingReport;

class ReportListing extends Component
{
    public $listing;
    public $reportReason = '';
    public $reportDetails = '';
    public $showSuccessModal = false;
    
    public $reportReasons = [
        'inappropriate_content' => 'Neprikladan sadržaj',
        'fake_listing' => 'Lažan oglas',
        'spam' => 'Spam',
        'wrong_category' => 'Pogrešna kategorija',
        'overpriced' => 'Previsoka cena',
        'scam' => 'Prevara',
        'duplicate' => 'Duplikat oglas',
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
        $this->listing = Listing::where('slug', $slug)->with('user')->firstOrFail();
        
        // Check if user is trying to report their own listing
        if (auth()->id() === $this->listing->user_id) {
            session()->flash('error', 'Ne možete prijaviti svoj oglas.');
            return redirect()->route('listings.show', $this->listing);
        }
        
        // Check if user has already reported this listing
        $existingReport = ListingReport::where('user_id', auth()->id())
            ->where('listing_id', $this->listing->id)
            ->first();
            
        if ($existingReport) {
            session()->flash('info', 'Već ste prijavili ovaj oglas.');
            return redirect()->route('listings.show', $this->listing);
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
            $report = ListingReport::create([
                'user_id' => auth()->id(),
                'listing_id' => $this->listing->id,
                'reason' => $this->reportReason,
                'details' => $this->reportDetails,
                'status' => 'pending'
            ]);
            
            // Create report message to admin
            $reportMessage = "PRIJAVA OGLASA\n\n" .
                           "Korisnik: " . auth()->user()->name . " (" . auth()->user()->email . ")\n" .
                           "Oglas: " . $this->listing->title . "\n" .
                           "Razlog: " . $this->reportReasons[$this->reportReason] . "\n\n" .
                           "Detalji:\n" . $this->reportDetails . "\n\n" .
                           "Link oglasa: " . route('listings.show', $this->listing) . "\n" .
                           "Report ID: " . $report->id;

            Message::create([
                'sender_id' => auth()->id(),
                'receiver_id' => $admin->id,
                'listing_id' => $this->listing->id,
                'message' => $reportMessage,
                'subject' => 'Prijava oglasa: ' . $this->listing->title,
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
        return redirect()->route('listings.show', $this->listing);
    }

    public function render()
    {
        return view('livewire.listings.report-listing')
            ->layout('layouts.app');
    }
}