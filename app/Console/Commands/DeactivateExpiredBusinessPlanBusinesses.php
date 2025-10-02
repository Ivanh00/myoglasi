<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Business;
use App\Models\User;

class DeactivateExpiredBusinessPlanBusinesses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'businesses:deactivate-expired-plan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deactivate businesses created with expired business plans';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for businesses from expired business plans...');

        // Get all active businesses that were created with business plan
        $businesses = Business::where('status', 'active')
            ->where('is_from_business_plan', true)
            ->with('user')
            ->get();

        $deactivatedCount = 0;

        foreach ($businesses as $business) {
            $user = $business->user;

            // Check if user's business plan has expired or they no longer have business plan
            $planExpired = !$user->plan_expires_at || $user->plan_expires_at->isPast();
            $noPlan = $user->payment_plan !== 'business';

            if ($planExpired || $noPlan) {
                $business->update(['status' => 'inactive']);
                $deactivatedCount++;

                $this->line("Deactivated: {$business->name} (User: {$user->name})");
            }
        }

        $this->info("Deactivated {$deactivatedCount} business(es) from expired plans.");

        return Command::SUCCESS;
    }
}
