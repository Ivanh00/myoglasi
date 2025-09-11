<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Listing;
use App\Models\Auction;
use App\Models\Message;
use App\Models\Transaction;
use Carbon\Carbon;

class Dashboard extends Component
{
    public function getUserStats()
    {
        $user = auth()->user();
        
        return [
            // Listing statistics
            'total_listings' => $user->listings()->count(),
            'active_listings' => $user->listings()->where('status', 'active')->count(),
            'expired_listings' => $user->listings()->where('status', 'expired')->count(),
            
            // Auction statistics  
            'total_auctions' => Auction::where('user_id', $user->id)->count(),
            'active_auctions' => Auction::where('user_id', $user->id)->where('status', 'active')->count(),
            'won_auctions' => Auction::where('winner_id', $user->id)->count(),
            
            // Financial data
            'current_balance' => $user->balance ?? 0,
            'total_spent' => Transaction::where('user_id', $user->id)->where('amount', '<', 0)->sum('amount') * -1,
            'total_earned' => Transaction::where('user_id', $user->id)->where('amount', '>', 0)->sum('amount'),
            
            // Communication
            'unread_messages' => Message::where('receiver_id', $user->id)->where('is_read', false)->where('is_system_message', false)->count(),
            'unread_notifications' => Message::where('receiver_id', $user->id)->where('is_read', false)->where('is_system_message', true)->count(),
            
            // Favorites and ratings
            'favorites_count' => $user->favorites()->count(),
            'total_ratings' => $user->total_ratings_count ?? 0,
            'positive_ratings' => $user->positive_ratings_count ?? 0,
        ];
    }

    public function getRecentActivity()
    {
        $user = auth()->user();
        
        return [
            'recent_listings' => $user->listings()->with('category', 'images')->latest()->limit(5)->get(),
            'recent_auctions' => Auction::where('user_id', $user->id)->with('listing.images')->latest()->limit(3)->get(),
            'recent_transactions' => Transaction::where('user_id', $user->id)->latest()->limit(5)->get(),
            'recent_messages' => Message::where('receiver_id', $user->id)->with('sender')->latest()->limit(5)->get(),
        ];
    }

    public function getMonthlyStats()
    {
        $user = auth()->user();
        $startOfMonth = Carbon::now()->startOfMonth();
        
        return [
            'listings_this_month' => $user->listings()->where('created_at', '>=', $startOfMonth)->count(),
            'auctions_this_month' => Auction::where('user_id', $user->id)->where('created_at', '>=', $startOfMonth)->count(),
            'spent_this_month' => Transaction::where('user_id', $user->id)->where('amount', '<', 0)->where('created_at', '>=', $startOfMonth)->sum('amount') * -1,
            'messages_this_month' => Message::where('receiver_id', $user->id)->where('created_at', '>=', $startOfMonth)->count(),
        ];
    }

    public function render()
    {
        $stats = $this->getUserStats();
        $activity = $this->getRecentActivity();
        $monthlyStats = $this->getMonthlyStats();
        
        return view('livewire.dashboard', compact('stats', 'activity', 'monthlyStats'))
            ->layout('layouts.app');
    }
}
