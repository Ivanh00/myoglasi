<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Listing;
use Carbon\Carbon;

class ExpireListings extends Command
{
    protected $signature = 'listings:expire';
    protected $description = 'Mark listings as expired after 60 days';

    public function handle()
    {
        $this->info('Checking for expired listings...');
        
        // Find all active listings that have passed their expiry date
        $expiredListings = Listing::where('status', 'active')
            ->where('expires_at', '<', Carbon::now())
            ->get();
            
        $count = 0;
        
        foreach ($expiredListings as $listing) {
            $listing->update([
                'status' => 'expired'
            ]);
            
            $this->info("Expired listing: {$listing->title} (ID: {$listing->id}) for user: {$listing->user->name}");
            $count++;
        }
        
        $this->info("Successfully expired {$count} listings.");
        
        // Also update existing listings that don't have expires_at set
        $this->info('Setting expiry dates for listings without expires_at...');
        
        $listingsWithoutExpiry = Listing::whereNull('expires_at')
            ->where('status', 'active')
            ->get();
            
        foreach ($listingsWithoutExpiry as $listing) {
            $listing->update([
                'expires_at' => $listing->created_at->addDays(60)
            ]);
        }
        
        $this->info("Set expiry dates for {$listingsWithoutExpiry->count()} existing listings.");
        
        return 0;
    }
}