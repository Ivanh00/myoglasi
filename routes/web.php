<?php

use App\Livewire\Home;

// Livewire komponente
use App\Livewire\HomeComponent;
use App\Livewire\UserDashboard;
use App\Livewire\SearchComponent;
use App\Livewire\BalanceComponent;
use App\Livewire\ProfileComponent;
use App\Livewire\MessagesComponent;
use Illuminate\Support\Facades\Route;
use App\Livewire\EditListingComponent;
use App\Livewire\CreateListingComponent;
use App\Livewire\ListingDetailComponent;
use App\Livewire\MessageDetailComponent;
use App\Livewire\Home\Index as HomeIndex;
use App\Http\Controllers\ProfileController;
use App\Livewire\CategoryListingsComponent;
use App\Livewire\Search\Index as SearchIndex;
use App\Livewire\Listings\Edit as ListingEdit;
use App\Livewire\Listings\Show as ListingShow;
use App\Livewire\Messages\Show as MessageShow;
use App\Livewire\Categories\Show as CategoryShow;
use App\Livewire\Listings\Index as ListingsIndex;
use App\Livewire\Messages\Index as MessagesIndex;
use App\Livewire\Listings\Create as ListingCreate;
use App\Livewire\Dashboard\Index as DashboardIndex;
use App\Livewire\Favorites\Index as FavoritesIndex;
use App\Livewire\Listings\MyListings as MyListings;
use App\Livewire\Categories\Index as CategoriesIndex;
use App\Livewire\Transactions\Balance as BalanceIndex;


// Javne rute
Route::get('/', HomeComponent::class)->name('home');
Route::get('/listings', ListingsIndex::class)->name('listings.index');
Route::get('/listings/create', ListingCreate::class)
    ->middleware('auth')
    ->name('listings.create');
Route::get('/listings/{listing}', ListingShow::class)->name('listings.show');

Route::get('/categories', CategoriesIndex::class)->name('categories.index');
Route::get('/category/{category}', CategoryShow::class)->name('category.show');
Route::get('/search', SearchIndex::class)->name('search.index');

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    // Route::get('/dashboard', DashboardIndex::class)->name('dashboard');
    // Route::get('/profile', ProfileIndex::class)->name('profile');

    Route::get('/my-listings', MyListings::class)->name('listings.my');
    Route::get('/listings/{listing}/edit', ListingEdit::class)->name('listings.edit');

    Route::get('/messages', MessagesIndex::class)->name('messages.index');
    Route::get('/messages/{conversation}', MessageShow::class)->name('messages.show');

    Route::get('/favorites', FavoritesIndex::class)->name('favorites.index');
    Route::get('/balance', BalanceIndex::class)->name('balance.index');

    Route::post('/logout', function () {
        auth()->guard('web')->logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/');
    })->name('logout');

    Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::post('logout', function () {
    auth()->guard('web')->logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/');
})->middleware(['auth'])->name('logout');

});

