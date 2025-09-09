<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Auction;
use App\Models\Message;
use Carbon\Carbon;

class ProcessAuctions extends Command
{
    protected $signature = 'auctions:process';
    protected $description = 'Process auction endings, extensions, and notifications';

    public function handle()
    {
        $this->info('Processing auctions...');
        
        $endedCount = 0;
        $extendedCount = 0;
        $notificationCount = 0;

        // Check for auctions that need to end
        $endingAuctions = Auction::where('status', 'active')
            ->where('ends_at', '<=', Carbon::now())
            ->with(['listing', 'winningBid.user', 'seller'])
            ->get();

        foreach ($endingAuctions as $auction) {
            $this->info("Processing auction #{$auction->id}: {$auction->listing->title}");
            
            // Check if auction needs extension first
            if ($auction->needsExtension()) {
                if ($auction->extendAuction()) {
                    $this->info("Extended auction #{$auction->id} by 3 minutes");
                    $extendedCount++;
                    
                    // Notify all bidders about extension
                    $this->sendExtensionNotifications($auction);
                    continue;
                }
            }

            // End the auction
            $auction->update(['status' => 'ended']);
            $endedCount++;

            if ($auction->winningBid) {
                $auction->update(['winner_id' => $auction->winningBid->user_id]);
                
                // Send winner notification
                Message::create([
                    'sender_id' => $auction->user_id,
                    'receiver_id' => $auction->winningBid->user_id,
                    'listing_id' => $auction->listing_id,
                    'message' => "Čestitamo! Pobedili ste na aukciji za '{$auction->listing->title}' sa ponudom od " . 
                                number_format($auction->current_price, 0, ',', '.') . " RSD. Molimo kontaktirajte prodavca u roku od 48h.",
                    'subject' => 'Pobeda na aukciji - ' . $auction->listing->title,
                    'is_system_message' => true,
                    'is_read' => false
                ]);

                // Send seller notification
                Message::create([
                    'sender_id' => $auction->winningBid->user_id,
                    'receiver_id' => $auction->user_id,
                    'listing_id' => $auction->listing_id,
                    'message' => "Aukcija za '{$auction->listing->title}' je završena. Pobednik je {$auction->winningBid->user->name} sa ponudom od " . 
                                number_format($auction->current_price, 0, ',', '.') . " RSD.",
                    'subject' => 'Aukcija završena - ' . $auction->listing->title,
                    'is_system_message' => true,
                    'is_read' => false
                ]);
                
                $notificationCount += 2;
                $this->info("Auction ended with winner: {$auction->winningBid->user->name}");
            } else {
                // No bids - notify seller
                Message::create([
                    'sender_id' => 1, // System
                    'receiver_id' => $auction->user_id,
                    'listing_id' => $auction->listing_id,
                    'message' => "Aukcija za '{$auction->listing->title}' je završena bez ponuda.",
                    'subject' => 'Aukcija završena bez ponuda - ' . $auction->listing->title,
                    'is_system_message' => true,
                    'is_read' => false
                ]);
                
                $notificationCount++;
                $this->info("Auction ended with no bids");
            }
        }

        // Send ending soon notifications (24h, 1h, 10min warnings)
        $this->sendEndingSoonNotifications();

        $this->info("Processed {$endedCount} ended auctions, {$extendedCount} extensions, sent {$notificationCount} notifications");
        return 0;
    }

    private function sendExtensionNotifications($auction)
    {
        $bidders = $auction->bids()->distinct('user_id')->pluck('user_id');
        
        foreach ($bidders as $bidderId) {
            Message::create([
                'sender_id' => 1, // System
                'receiver_id' => $bidderId,
                'listing_id' => $auction->listing_id,
                'message' => "Aukcija za '{$auction->listing->title}' je produžena za 3 minuta zbog ponude u poslednji trenutak.",
                'subject' => 'Aukcija produžena - ' . $auction->listing->title,
                'is_system_message' => true,
                'is_read' => false
            ]);
        }
    }

    private function sendEndingSoonNotifications()
    {
        // 1 hour warning
        $endingSoonAuctions = Auction::where('status', 'active')
            ->whereBetween('ends_at', [Carbon::now()->addMinutes(55), Carbon::now()->addMinutes(65)])
            ->with(['listing', 'bids.user'])
            ->get();

        foreach ($endingSoonAuctions as $auction) {
            $bidders = $auction->bids()->distinct('user_id')->pluck('user_id');
            
            foreach ($bidders as $bidderId) {
                // Check if notification was already sent today
                $existingNotification = Message::where('receiver_id', $bidderId)
                    ->where('listing_id', $auction->listing_id)
                    ->where('subject', 'LIKE', '%završava za sat vremena%')
                    ->whereDate('created_at', Carbon::today())
                    ->exists();
                    
                if (!$existingNotification) {
                    Message::create([
                        'sender_id' => 1, // System
                        'receiver_id' => $bidderId,
                        'listing_id' => $auction->listing_id,
                        'message' => "Aukcija za '{$auction->listing->title}' završava za sat vremena. Trenutna cena: " . 
                                    number_format($auction->current_price, 0, ',', '.') . " RSD.",
                        'subject' => 'Aukcija završava za sat vremena - ' . $auction->listing->title,
                        'is_system_message' => true,
                        'is_read' => false
                    ]);
                }
            }
        }
    }
}