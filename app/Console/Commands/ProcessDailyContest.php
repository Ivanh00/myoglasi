<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Listing;
use App\Models\User;
use App\Models\DailyEarning;
use App\Models\Transaction;
use App\Models\Setting;

class ProcessDailyContest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contest:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process daily contest for most listings posted';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!Setting::get('daily_contest_enabled', true)) {
            $this->info('Daily contest is disabled.');
            return;
        }

        $yesterday = now()->subDay();
        $contestAmount = Setting::get('daily_contest_amount', 100);

        // Find user who posted most listings yesterday
        $winner = User::whereHas('listings', function($query) use ($yesterday) {
            $query->whereDate('created_at', $yesterday->toDateString());
        })
        ->withCount(['listings' => function($query) use ($yesterday) {
            $query->whereDate('created_at', $yesterday->toDateString());
        }])
        ->orderBy('listings_count', 'desc')
        ->first();

        if (!$winner || $winner->listings_count == 0) {
            $this->info('No listings posted yesterday - no contest winner.');
            return;
        }

        // Check if winner already got reward for this date
        $alreadyRewarded = DailyEarning::where('user_id', $winner->id)
            ->where('date', $yesterday->toDateString())
            ->where('type', 'daily_contest_winner')
            ->exists();

        if ($alreadyRewarded) {
            $this->info('Contest winner already rewarded for ' . $yesterday->toDateString());
            return;
        }

        // Award the winner
        $winner->increment('balance', $contestAmount);

        // Create daily earning record
        DailyEarning::create([
            'user_id' => $winner->id,
            'date' => $yesterday->toDateString(),
            'type' => 'daily_contest_winner',
            'amount' => $contestAmount,
            'description' => 'Pobednik dnevnog konkursa - najviše oglasa (' . $winner->listings_count . ')',
            'details' => json_encode([
                'contest_date' => $yesterday->toDateString(),
                'listings_posted' => $winner->listings_count,
                'contest_type' => 'most_listings'
            ])
        ]);

        // Create transaction record
        Transaction::create([
            'user_id' => $winner->id,
            'type' => 'daily_contest_winner',
            'amount' => $contestAmount,
            'status' => 'completed',
            'description' => 'Dnevni konkurs - najviše oglasa (' . $winner->listings_count . ' oglasa)',
            'reference_number' => 'CONTEST-' . $yesterday->format('Ymd'),
        ]);

        // Send notification to winner
        \App\Models\Message::create([
            'sender_id' => 1, // System
            'receiver_id' => $winner->id,
            'listing_id' => null,
            'message' => "Čestitamo! Pobedili ste u dnevnom konkursu!\n\nPostavili ste najviše oglasa ({$winner->listings_count}) dana " . $yesterday->format('d.m.Y') . " i zaradili ste {$contestAmount} RSD!\n\nNastavi ovako!",
            'subject' => 'Pobeda u dnevnom konkursu!',
            'is_system_message' => true,
            'is_read' => false
        ]);

        $this->info("Contest winner: {$winner->name} with {$winner->listings_count} listings - awarded {$contestAmount} RSD");
    }
}
