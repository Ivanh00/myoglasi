<?php

use App\Http\Livewire\Home;

// Livewire komponente
use App\Livewire\HomeComponent;
use App\Livewire\UserDashboard;
use App\Livewire\SearchComponent;
use App\Livewire\BalanceComponent;
use App\Livewire\ProfileComponent;
use App\Livewire\MessagesComponent;
use Illuminate\Support\Facades\Route;
use App\Livewire\EditListingComponent;
use App\Http\Controllers\HomeController;
use App\Livewire\CreateListingComponent;
use App\Livewire\ListingDetailComponent;
use App\Livewire\MessageDetailComponent;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Livewire\CategoryListingsComponent;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\TransactionController;
use App\Livewire\Home\Index as HomeIndex;
use App\Livewire\Listings\Index as ListingsIndex;
use App\Livewire\Listings\Show as ListingShow;
use App\Livewire\Listings\MyListings as MyListings;
use App\Livewire\Listings\Create as ListingCreate;
use App\Livewire\Listings\Edit as ListingEdit;
use App\Livewire\Categories\Show as CategoryShow;
use App\Livewire\Search\Index as SearchIndex;
use App\Livewire\Messages\Index as MessagesIndex;
use App\Livewire\Messages\Show as MessageShow;
use App\Livewire\Favorites\Index as FavoritesIndex;
use App\Livewire\Transactions\Balance as BalanceIndex;
use App\Livewire\Categories\Index as CategoriesIndex;

// Javne rute (svima dostupne)
Route::get('/', HomeComponent::class)->name('home');
Route::get('/listings/{listing}', ListingDetailComponent::class)->name('listings.show');
Route::get('/category/{category}', CategoryListingsComponent::class)->name('category.show');
Route::get('/search', SearchComponent::class)->name('search');

// Autentifikovane rute (samo registrovani korisnici)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', UserDashboard::class)->name('dashboard');
    Route::get('/listings/create', CreateListingComponent::class)->name('listings.create');
    Route::get('/listings/{listing}/edit', EditListingComponent::class)->name('listings.edit');
    Route::get('/messages', MessagesComponent::class)->name('messages');
    Route::get('/messages/{listing}', MessageDetailComponent::class)->name('messages.listing');
    Route::get('/balance', BalanceComponent::class)->name('balance');
    Route::get('/profile', ProfileComponent::class)->name('profile');
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

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

// Breeze auth rute (login, register, forgot password...)
require __DIR__.'/auth.php';

// Javne rute
Route::get('/', HomeIndex::class)->name('home');
Route::get('/listings', ListingsIndex::class)->name('listings.index');
Route::get('/listings/{listing}', ListingShow::class)->name('listings.show');
Route::get('/category/{category}', CategoryShow::class)->name('category.show');
Route::get('/search', SearchIndex::class)->name('search.index');

Route::middleware(['auth'])->group(function () {
    // User dashboard i profil
    Route::get('/my-listings', MyListings::class)->name('listings.my');
    Route::get('/my-messages', MessagesIndex::class)->name('messages.index');
    Route::get('/my-messages/{conversation}', MessageShow::class)->name('messages.show');
    Route::get('/my-favorites', FavoritesIndex::class)->name('favorites.index');
    Route::get('/my-balance', BalanceIndex::class)->name('balance.index');
    
    // Kreiranje i upravljanje oglasima
    Route::get('/listings/create', ListingCreate::class)->name('listings.create');
    Route::get('/listings/{listing}/edit', ListingEdit::class)->name('listings.edit');
});
Route::get('/search', \App\Livewire\Search\Index::class)->name('search.index');
Route::get('/categories', CategoriesIndex::class)->name('categories.index');