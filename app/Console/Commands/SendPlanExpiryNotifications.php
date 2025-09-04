<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Message;
use Carbon\Carbon;

class SendPlanExpiryNotifications extends Command
{
    protected $signature = 'notifications:plan-expiry';
    protected $description = 'Send notifications to users whose plans will expire in 5 days';

    public function handle()
    {
        $this->info('Checking for plans expiring in 5 days...');
        
        $fiveDaysFromNow = Carbon::now()->addDays(5)->startOfDay();
        $sixDaysFromNow = Carbon::now()->addDays(6)->startOfDay();
        
        // Find users whose plans expire in exactly 5 days
        $expiringUsers = User::where('payment_enabled', true)
            ->whereIn('payment_plan', ['monthly', 'yearly'])
            ->whereBetween('plan_expires_at', [$fiveDaysFromNow, $sixDaysFromNow])
            ->get();
            
        $count = 0;
        
        foreach ($expiringUsers as $user) {
            // Check if notification was already sent today
            $existingNotification = Message::where('receiver_id', $user->id)
                ->where('is_system_message', true)
                ->where('subject', 'LIKE', '%plan ističe%')
                ->whereDate('created_at', Carbon::today())
                ->exists();
                
            if ($existingNotification) {
                $this->info("Notification already sent today for user: {$user->name}");
                continue;
            }
            
            $planType = $user->payment_plan === 'monthly' ? 'mesečni' : 'godišnji';
            $expiryDate = $user->plan_expires_at->format('d.m.Y');
            
            Message::create([
                'sender_id' => 1, // Admin ID (adjust as needed)
                'receiver_id' => $user->id,
                'message' => "Vaš {$planType} plan ističe {$expiryDate}. Da biste nastavili sa neograničenim postavljanjem oglasa, molimo produžite svoj plan ili dopunite kredit za plaćanje po oglasu.",
                'subject' => "Vaš {$planType} plan ističe za 5 dana",
                'is_system_message' => true,
                'is_read' => false,
            ]);
            
            $this->info("Plan expiry notification sent to user: {$user->name} (ID: {$user->id})");
            $count++;
        }
        
        $this->info("Successfully sent {$count} plan expiry notifications.");
        return 0;
    }
}