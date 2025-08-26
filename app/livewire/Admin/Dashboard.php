<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\Listing;
use App\Models\Category;
use App\Models\Transaction;
use Carbon\Carbon;

class Dashboard extends Component
{
    public $stats = [];
    public $recentUsers = [];
    public $recentListings = [];
    public $popularCategories = [];

    public function mount()
    {
        $this->loadStats();
        $this->loadRecentData();
    }

    public function loadStats()
    {
        $this->stats = [
            'total_users' => User::count(),
            'total_listings' => Listing::count(),
            'active_listings' => Listing::where('status', 'active')->count(),
            'pending_listings' => Listing::where('status', 'pending')->count(),
            'total_categories' => Category::count(),
            'revenue' => Transaction::sum('amount'),
            'recent_users' => User::where('created_at', '>=', Carbon::now()->subDays(7))->count(),
            'recent_listings' => Listing::where('created_at', '>=', Carbon::now()->subDays(7))->count(),
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
    }

    public function refreshData()
    {
        $this->loadStats();
        $this->loadRecentData();
        $this->dispatch('notify', type: 'success', message: 'Podaci osveženi!');
    }

    public function render()
    {
        return view('livewire.admin.dashboard')
            ->layout('layouts.admin');
    }
}