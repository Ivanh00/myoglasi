<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Listing;
use App\Models\Message;
use Carbon\Carbon;

class SendListingExpiryNotifications extends Command
{
    protected $signature = 'notifications:listing-expiry';
    protected $description = 'Send notifications to users whose listings will expire in 5 days';

    public function handle()
    {
        $this->info('Checking for listings expiring in 5 days...');
        
        $fiveDaysFromNow = Carbon::now()->addDays(5)->startOfDay();
        $sixDaysFromNow = Carbon::now()->addDays(6)->startOfDay();
        
        // Find active listings that expire in exactly 5 days
        $expiringListings = Listing::with('user')
            ->where('status', 'active')
            ->whereBetween('expires_at', [$fiveDaysFromNow, $sixDaysFromNow])
            ->get();
            
        $count = 0;
        
        foreach ($expiringListings as $listing) {
            // Check if notification was already sent today for this listing
            $existingNotification = Message::where('receiver_id', $listing->user_id)
                ->where('listing_id', $listing->id)
                ->where('is_system_message', true)
                ->where('subject', 'LIKE', '%oglas ističe%')
                ->whereDate('created_at', Carbon::today())
                ->exists();
                
            if ($existingNotification) {
                $this->info("Listing expiry notification already sent today for listing: {$listing->title}");
                continue;
            }
            
            $expiryDate = $listing->expires_at->format('d.m.Y');
            
            Message::create([
                'sender_id' => 1, // Admin ID (adjust as needed)
                'receiver_id' => $listing->user_id,
                'listing_id' => $listing->id,
                'message' => "Vaš oglas '{$listing->title}' ističe {$expiryDate}. Da biste zadržali oglas aktivan, možete ga obnoviti za narednih 60 dana.",
                'subject' => "Oglas ističe za 5 dana - {$listing->title}",
                'is_system_message' => true,
                'is_read' => false,
            ]);
            
            $this->info("Listing expiry notification sent for: {$listing->title} to user: {$listing->user->name}");
            $count++;
        }
        
        $this->info("Successfully sent {$count} listing expiry notifications.");
        return 0;
    }
}