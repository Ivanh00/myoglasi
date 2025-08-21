<?php

use App\Livewire\HomeComponent;
use App\Livewire\UserDashboard;
use App\Livewire\LoginComponent;
use App\Livewire\SearchComponent;
use App\Livewire\BalanceComponent;
use App\Livewire\ProfileComponent;
use App\Livewire\MessagesComponent;
use App\Livewire\RegisterComponent;
use Illuminate\Support\Facades\Route;
use App\Livewire\EditListingComponent;
use App\Livewire\CreateListingComponent;
use App\Livewire\ListingDetailComponent;
use App\Livewire\MessageDetailComponent;
use App\Livewire\CategoryListingsComponent;



// Route::get('/', function () {    
//     return view('welcome');
// });

// Javne rute (dostupne svima)
Route::get('/', HomeComponent::class)->name('home');
Route::get('/listings/{listing}', ListingDetailComponent::class)->name('listings.show');
Route::get('/category/{category}', CategoryListingsComponent::class)->name('category.show');
Route::get('/search', SearchComponent::class)->name('search');

// Autentificirane rute (samo za registrovane korisnike)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', UserDashboard::class)->name('dashboard');
    Route::get('/listings/create', CreateListingComponent::class)->name('listings.create');
    Route::get('/listings/{listing}/edit', EditListingComponent::class)->name('listings.edit');
    Route::get('/messages', MessagesComponent::class)->name('messages');
    Route::get('/messages/{listing}', MessageDetailComponent::class)->name('messages.listing');
    Route::get('/balance', BalanceComponent::class)->name('balance');
    Route::get('/profile', ProfileComponent::class)->name('profile');
});

// Auth rute
Route::get('/login', LoginComponent::class)->name('login');
Route::get('/register', RegisterComponent::class)->name('register');