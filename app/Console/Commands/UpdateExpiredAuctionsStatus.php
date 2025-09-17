<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Auction;

class UpdateExpiredAuctionsStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auctions:update-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update status of expired auctions from active to ended';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Find all auctions that are still marked as active but have expired
        $expiredAuctions = Auction::where('status', 'active')
            ->where('ends_at', '<=', now())
            ->get();

        $count = 0;
        foreach ($expiredAuctions as $auction) {
            // Set winner if there are bids
            if ($auction->total_bids > 0) {
                $winningBid = $auction->bids()->where('is_winning', true)->first();
                if ($winningBid) {
                    $auction->winner_id = $winningBid->user_id;
                }
            }

            // Update status to ended
            $auction->status = 'ended';
            $auction->save();
            $count++;
        }

        $this->info("Updated {$count} expired auctions to 'ended' status.");

        return Command::SUCCESS;
    }
}
