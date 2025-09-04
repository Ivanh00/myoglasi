<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Transaction;
use Carbon\Carbon;

class ExpirePlans extends Command
{
    protected $signature = 'plans:expire';
    protected $description = 'Handle expired user plans and downgrade them to per-listing payment';

    public function handle()
    {
        $this->info('Checking for expired plans...');
        
        $expiredUsers = User::where('payment_enabled', true)
            ->whereIn('payment_plan', ['monthly', 'yearly'])
            ->where('plan_expires_at', '<', Carbon::now())
            ->get();
            
        $count = 0;
        
        foreach ($expiredUsers as $user) {
            $this->info("Processing expired plan for user: {$user->name} (ID: {$user->id})");
            
            $oldPlan = $user->payment_plan;
            
            // Downgrade to per-listing payment
            $user->update([
                'payment_plan' => 'per_listing',
                'plan_expires_at' => null,
                'free_listings_used' => 0,
                'free_listings_reset_at' => Carbon::now()->addMonth(),
            ]);
            
            // Create transaction record
            Transaction::create([
                'user_id' => $user->id,
                'type' => 'plan_expired',
                'amount' => 0,
                'status' => 'completed',
                'description' => 'Plan ' . ucfirst($oldPlan) . ' je istekao - prebačeno na plaćanje po oglasu',
            ]);
            
            $count++;
        }
        
        $this->info("Successfully processed {$count} expired plans.");
        
        // Also handle users who need their free listings counter reset
        $this->info('Resetting monthly free listings counters...');
        
        $usersToReset = User::where('payment_enabled', true)
            ->where('payment_plan', 'per_listing')
            ->where(function ($query) {
                $query->whereNull('free_listings_reset_at')
                      ->orWhere('free_listings_reset_at', '<', Carbon::now());
            })
            ->get();
            
        foreach ($usersToReset as $user) {
            $user->update([
                'free_listings_used' => 0,
                'free_listings_reset_at' => Carbon::now()->addMonth(),
            ]);
        }
        
        $this->info("Reset free listings counter for {$usersToReset->count()} users.");
        
        return 0;
    }
}