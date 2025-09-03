<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\Listing;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\Message;
use Carbon\Carbon;

class Dashboard extends Component
{
    public $stats = [];
    public $recentUsers = [];
    public $recentListings = [];
    public $popularCategories = [];
    public $chartData = [];
    public $topUsers = [];

    public function mount()
    {
        $this->loadStats();
        $this->loadRecentData();
        $this->loadChartData();
    }

    public function loadStats()
    {
        $this->stats = [
            'total_users' => User::count(),
            'total_listings' => Listing::count(),
            'active_listings' => Listing::where('status', 'active')->count(),
            'pending_listings' => Listing::where('status', 'pending')->count(),
            'blocked_listings' => Listing::where('status', 'blocked')->count(),
            'total_categories' => Category::count(),
            'total_revenue' => Transaction::where('amount', '<', 0)->sum('amount') * -1,
            'total_transactions' => Transaction::count(),
            'recent_users' => User::where('created_at', '>=', Carbon::now()->subDays(7))->count(),
            'recent_listings' => Listing::where('created_at', '>=', Carbon::now()->subDays(7))->count(),
            'today_registrations' => User::whereDate('created_at', Carbon::today())->count(),
            'today_listings' => Listing::whereDate('created_at', Carbon::today())->count(),
            'unread_messages' => Message::where('is_read', false)->count(),
            'avg_user_balance' => User::avg('balance') ?? 0,
            'users_with_balance' => User::where('balance', '>', 0)->count(),
        ];
    }

    public function loadRecentData()
    {
        $this->recentUsers = User::latest()->take(5)->get();
        $this->recentListings = Listing::with('user', 'category')
            ->latest()
            ->take(5)
            ->get();
        
        $this->popularCategories = Category::withCount('listings')
            ->orderBy('listings_count', 'desc')
            ->take(5)
            ->get();
            
        $this->topUsers = User::withCount('listings')
            ->orderBy('listings_count', 'desc')
            ->take(10)
            ->get();
    }

    public function loadChartData()
    {
        // User registrations over last 30 days
        $userChartData = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $count = User::whereDate('created_at', $date)->count();
            $userChartData[] = [
                'date' => $date->format('m-d'),
                'count' => $count
            ];
        }

        // Listings created over last 30 days
        $listingChartData = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $count = Listing::whereDate('created_at', $date)->count();
            $listingChartData[] = [
                'date' => $date->format('m-d'),
                'count' => $count
            ];
        }

        // Revenue over last 30 days
        $revenueChartData = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $revenue = Transaction::whereDate('created_at', $date)
                ->where('amount', '<', 0)
                ->sum('amount') * -1;
            $revenueChartData[] = [
                'date' => $date->format('m-d'),
                'revenue' => $revenue
            ];
        }

        // Category distribution
        $categoryData = Category::withCount('listings')
            ->having('listings_count', '>', 0)
            ->orderBy('listings_count', 'desc')
            ->take(10)
            ->get()
            ->map(function ($category) {
                return [
                    'name' => $category->name,
                    'count' => $category->listings_count
                ];
            });

        $this->chartData = [
            'users' => $userChartData,
            'listings' => $listingChartData,
            'revenue' => $revenueChartData,
            'categories' => $categoryData,
        ];
    }

    public function refreshData()
    {
        $this->loadStats();
        $this->loadRecentData();
        $this->loadChartData();
        $this->dispatch('notify', type: 'success', message: 'Podaci osveženi!');
    }

    public function render()
    {
        return view('livewire.admin.dashboard')
            ->layout('layouts.admin');
    }
}