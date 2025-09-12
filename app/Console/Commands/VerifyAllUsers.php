<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class VerifyAllUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:verify-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verify email for all users (useful when disabling email verification)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $unverifiedUsers = \App\Models\User::whereNull('email_verified_at')->get();
        
        if ($unverifiedUsers->count() === 0) {
            $this->info('All users are already verified.');
            return;
        }

        $this->info('Found ' . $unverifiedUsers->count() . ' unverified users.');
        
        if ($this->confirm('Do you want to verify all unverified users?')) {
            $updated = \App\Models\User::whereNull('email_verified_at')->update([
                'email_verified_at' => now()
            ]);
            
            $this->info('Successfully verified ' . $updated . ' users.');
        } else {
            $this->info('Operation cancelled.');
        }
    }
}
