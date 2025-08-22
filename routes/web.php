<?php

use App\Livewire\Listings\Create;

// Livewire Components
use App\Livewire\Listings\MyListings;
use Illuminate\Support\Facades\Route;
use App\Livewire\Home\Index as HomeIndex;
use App\Livewire\Search\Index as SearchIndex;
use App\Livewire\Listings\Edit as ListingEdit;
use App\Livewire\Listings\Show as ListingShow;
use App\Livewire\Messages\Show as MessageShow;
use App\Livewire\Profile\Index as ProfileIndex;
use App\Livewire\Categories\Show as CategoryShow;
use App\Livewire\Listings\Index as ListingsIndex;
use App\Livewire\Messages\Index as MessagesIndex;
use App\Livewire\Listings\Create as ListingCreate;
use App\Livewire\Dashboard\Index as DashboardIndex;
use App\Livewire\Favorites\Index as FavoritesIndex;
use App\Livewire\Categories\Index as CategoriesIndex;
use App\Livewire\Transactions\Balance as BalanceIndex;

// Public Routes
Route::get('/', HomeIndex::class)->name('home');
Route::get('/listings', ListingsIndex::class)->name('listings.index');
Route::get('/listings/{listing}', ListingShow::class)->name('listings.show');
Route::get('/categories', CategoriesIndex::class)->name('categories.index');
Route::get('/category/{category}', CategoryShow::class)->name('category.show');
Route::get('/search', SearchIndex::class)->name('search.index');

// Auth Routes (Laravel Breeze)
require __DIR__.'/auth.php';

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard & Profile
    Route::get('/dashboard', DashboardIndex::class)->name('dashboard');
    Route::get('/profile', ProfileIndex::class)->name('profile');
    
    // Listings Management
    Route::get('/my-listings', MyListings::class)->name('listings.my');
    Route::get('/listings/create', ListingCreate::class)->name('listings.create');
    Route::get('/listings/{listing}/edit', ListingEdit::class)->name('listings.edit');
    
    // Messages
    Route::get('/messages', MessagesIndex::class)->name('messages.index');
    Route::get('/messages/{conversation}', MessageShow::class)->name('messages.show');
    
    // Other Features
    Route::get('/favorites', FavoritesIndex::class)->name('favorites.index');
    Route::get('/balance', BalanceIndex::class)->name('balance.index');
    
    // Logout
    Route::post('/logout', function () {
        auth()->guard('web')->logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});

Route::get('/listings/create', Create::class)
    ->middleware('auth')
    ->name('listings.create');