<?php

use App\Livewire\HomeComponent;

// Livewire komponente
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
use App\Http\Controllers\ProfileController;
use App\Livewire\CategoryListingsComponent;

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
