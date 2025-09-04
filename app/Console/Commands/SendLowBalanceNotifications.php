<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Message;
use App\Models\Setting;
use Carbon\Carbon;

class SendLowBalanceNotifications extends Command
{
    protected $signature = 'notifications:low-balance';
    protected $description = 'Send notifications to users with low balance (< 100 RSD) on per-listing plan';

    public function handle()
    {
        $this->info('Checking for users with low balance...');
        
        $lowBalanceThreshold = 100; // 100 RSD
        $listingFee = Setting::get('listing_fee_amount', 10);
        
        // Find users with per-listing plan and low balance
        $lowBalanceUsers = User::where('payment_enabled', true)
            ->where('payment_plan', 'per_listing')
            ->where('balance', '<=', $lowBalanceThreshold)
            ->where('balance', '>', 0) // Still have some balance but low
            ->get();
            
        $count = 0;
        
        foreach ($lowBalanceUsers as $user) {
            // Check if notification was already sent today
            $existingNotification = Message::where('receiver_id', $user->id)
                ->where('is_system_message', true)
                ->where('subject', 'LIKE', '%nizak balans%')
                ->whereDate('created_at', Carbon::today())
                ->exists();
                
            if ($existingNotification) {
                $this->info("Low balance notification already sent today for user: {$user->name}");
                continue;
            }
            
            $remainingAds = floor($user->balance / $listingFee);
            
            Message::create([
                'sender_id' => 1, // Admin ID (adjust as needed)
                'receiver_id' => $user->id,
                'message' => "Vaš balans je nizak: " . number_format($user->balance, 0, ',', '.') . " RSD. Možete postaviti još samo {$remainingAds} oglasa. Preporučujemo da dopunite kredit ili promenite na mesečni/godišnji plan za neograničeno postavljanje oglasa.",
                'subject' => 'Nizak balans - ' . number_format($user->balance, 0, ',', '.') . ' RSD',
                'is_system_message' => true,
                'is_read' => false,
            ]);
            
            $this->info("Low balance notification sent to user: {$user->name} (Balance: {$user->balance} RSD)");
            $count++;
        }
        
        // Also check for users with 0 balance
        $zeroBalanceUsers = User::where('payment_enabled', true)
            ->where('payment_plan', 'per_listing')
            ->where('balance', '<=', 0)
            ->get();
            
        foreach ($zeroBalanceUsers as $user) {
            // Check if notification was already sent today
            $existingNotification = Message::where('receiver_id', $user->id)
                ->where('is_system_message', true)
                ->where('subject', 'LIKE', '%nema kredita%')
                ->whereDate('created_at', Carbon::today())
                ->exists();
                
            if ($existingNotification) {
                continue;
            }
            
            Message::create([
                'sender_id' => 1, // Admin ID
                'receiver_id' => $user->id,
                'message' => "Nemate kredita na vašem nalogu. Da biste mogli da postavljate oglase, molimo dopunite kredit ili aktivirajte mesečni/godišnji plan.",
                'subject' => 'Nema kredita - potrebna dopuna',
                'is_system_message' => true,
                'is_read' => false,
            ]);
            
            $count++;
        }
        
        $this->info("Successfully sent {$count} low balance notifications.");
        return 0;
    }
}