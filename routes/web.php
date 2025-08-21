<?php

use Illuminate\Support\Facades\Route;

// Livewire komponente
use App\Livewire\HomeComponent;
use App\Livewire\ListingDetailComponent;
use App\Livewire\CategoryListingsComponent;
use App\Livewire\SearchComponent;
use App\Livewire\UserDashboard;
use App\Livewire\CreateListingComponent;
use App\Livewire\EditListingComponent;
use App\Livewire\MessagesComponent;
use App\Livewire\MessageDetailComponent;
use App\Livewire\BalanceComponent;
use App\Livewire\ProfileComponent;

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
});

// Breeze auth rute (login, register, forgot password...)
require __DIR__.'/auth.php';
