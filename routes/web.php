<?php

use App\Livewire\Home;

// Livewire komponente
use Livewire\Livewire;
use App\Models\Listing;
use App\Livewire\MessagesList;
use App\Livewire\HomeComponent;
use App\Livewire\Listings\Show;
use App\Livewire\Notifications;
use App\Livewire\UserDashboard;
use App\Livewire\SearchComponent;
use App\Livewire\BalanceComponent;
use App\Livewire\ProfileComponent;
use App\Livewire\MessagesComponent;
use Illuminate\Support\Facades\Route;
use App\Livewire\EditListingComponent;
use App\Livewire\ShowListingComponent;
use App\Livewire\ConversationComponent;
use App\Livewire\CreateListingComponent;
use App\Livewire\ListingDetailComponent;
use App\Livewire\MessageDetailComponent;
use App\Livewire\Home\Index as HomeIndex;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\ProfileController;
use App\Livewire\Categories\ShowCategories;
use App\Livewire\CategoryListingsComponent;
use App\Livewire\Search\Index as SearchIndex;
use App\Livewire\Listings\Edit as ListingEdit;
use App\Livewire\Listings\Show as ListingShow;
use App\Livewire\Messages\Show as MessageShow;
use App\Http\Controllers\NotificationController;
use App\Livewire\Categories\Show as CategoryShow;
use App\Livewire\Listings\Index as ListingsIndex;
use App\Livewire\Messages\Index as MessagesIndex;
use App\Livewire\Listings\Create as ListingCreate;
use App\Livewire\Dashboard\Index as DashboardIndex;
use App\Livewire\Favorites\Index as FavoritesIndex;
use App\Livewire\Listings\MyListings as MyListings;
use App\Livewire\Categories\Index as CategoriesIndex;
use App\Livewire\Transactions\Balance as BalanceIndex;

// Maintenance route (only accessible when maintenance mode is on)
Route::get('/maintenance', function () {
    // Prevent direct access when maintenance is off
    if (!\App\Models\Setting::get('maintenance_mode', false)) {
        return redirect()->route('home');
    }
    return view('maintenance');
})->name('maintenance');

// Javne rute
Route::get('/', HomeComponent::class)->name('home');
Route::get('/listings', ListingsIndex::class)->name('listings.index');
Route::get('/listings/create', ListingCreate::class)
    ->middleware(['auth', 'check.listing'])
    ->name('listings.create');
// Route::get('/listings/{listing}', ListingShow::class)->name('listings.show');

Route::get('/categories', CategoriesIndex::class)->name('categories.index');
// Route::get('/category/{category}', CategoryShow::class)->name('category.show');
Route::get('/search', SearchIndex::class)->name('search.index');

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    // Route::get('/dashboard', DashboardIndex::class)->name('dashboard');
    // Route::get('/profile', ProfileIndex::class)->name('profile');

    // Route::get('/my-listings', MyListings::class)->name('listings.my');
    Route::get('/listings/{listing}/edit', ListingEdit::class)->name('listings.edit');

    Route::get('/messages', MessagesIndex::class)->name('messages.index');
    Route::get('/messages/{conversation}', MessageShow::class)->name('messages.show');

    Route::get('/favorites', FavoritesIndex::class)->name('favorites.index');
    Route::get('/balance', BalanceIndex::class)->name('balance.index');
    
    // Payment routes
    Route::get('/balance/payment-options', \App\Livewire\Balance\PaymentOptions::class)->name('balance.payment-options');
    Route::get('/balance/plan-selection', \App\Livewire\Balance\PlanSelection::class)->name('balance.plan-selection');
    Route::get('/balance/card-payment/{transaction}', \App\Livewire\Balance\CardPayment::class)->name('balance.card-payment');
    Route::get('/balance/bank-transfer/{transaction}', \App\Livewire\Balance\BankTransfer::class)->name('balance.bank-transfer');
    
    // Rating routes  
    Route::get('/rating/create', \App\Livewire\Ratings\Create::class)->name('ratings.create');
    Route::get('/my-ratings', \App\Livewire\Ratings\MyRatings::class)->name('ratings.my');
    Route::get('/user/{user}/ratings', \App\Livewire\Ratings\UserRatings::class)->name('user.ratings');

    Route::post('/logout', function () {
        auth()->guard('web')->logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});
Route::get('/profile', function () {
    $user = auth()->user();

    $cities = [
        'Beograd',
        'Novi Sad',
        'Niš',
        'Kragujevac',
        'Subotica'
    ];

    return view('profile', compact('user', 'cities')); // sada se prosleđuje i $user i $cities
})->middleware(['auth'])->name('profile.edit');

    

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::post('logout', function () {
    auth()->guard('web')->logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/');
})->middleware(['auth'])->name('logout');

require __DIR__.'/auth.php';

// API routes for frontend
Route::get('/api/categories/{category}/subcategories', function($categoryId) {
    $subcategories = \App\Models\Category::where('parent_id', $categoryId)
        ->where('is_active', true)
        ->orderBy('sort_order')
        ->get(['id', 'name']);
    
    return response()->json($subcategories);
});

// Maintenance status API (for auto-refresh)
Route::get('/api/maintenance-status', function() {
    return response()->json([
        'maintenance_mode' => \App\Models\Setting::get('maintenance_mode', false)
    ]);
});


// Route::get('/category/{category}', ShowCategories::class)->name('category.show');
// Route::get('/category/{category}/{subcategory}', ShowCategories::class)->name('category.show.subcategory');

// Za sve oglase
Route::get('/listings', \App\Livewire\Listings\Index::class)->name('listings.index');

// Za pojedinačne kategorije (ako još uvek koristite)
Route::get('/category/{category}', \App\Livewire\Categories\ShowCategories::class)->name('category.show');

// Za kategorije
Route::get('/category/{category}', ShowCategories::class)->name('category.show');
Route::get('/category/{category}/{subcategory}', ShowCategories::class)->name('category.show.subcategory');

// Za pojedinačne oglase
Route::get('/listings/{listing}', Show::class)->name('listings.show');

// Za moje oglase
Route::get('/my-listings', MyListings::class)
    ->middleware('auth')
    ->name('listings.my');

// Za editovanje oglasa (ako vam treba)
Route::get('/listings/{listing}/edit', \App\Livewire\Listings\Edit::class)
    ->middleware('auth')
    ->name('listings.edit');

    // // Za editovanje oglasa
    // Route::get('/listings/{listing:slug}/edit', \App\Livewire\Listings\Edit::class)
    // ->middleware('auth')
    // ->name('listings.edit');
    Route::view('/profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('/listings', ListingsIndex::class)->name('listings.index');

// Route::get('/search', [App\Http\Controllers\SearchController::class, 'index'])->name('search.index');
// Route::get('/search', [SearchController::class, 'index'])->name('search.index');



// Search ruta
Route::get('/search', [SearchController::class, 'index'])->name('search.index');

// Listings ruta (Livewire komponenta)
Route::get('/listings', \App\Livewire\Listings\Index::class)->name('listings.index');

// Route::get('/oglasi/{listing}/chat', \App\Livewire\ConversationComponent::class)->name('listing.chat');// Prvi način: Korišćenje Livewire rute

// // Ili drugi način: Eksplicitno navođenje parametra
// Route::get('/oglasi/{listingId}/chat', function ($listingId) {
//     return Livewire::render(ConversationComponent::class, ['listingId' => $listingId]);
// })->name('listing.chat');

// // Probajte oba formata da vidite koji radi
// Route::get('/oglasi/{listing}/chat', ConversationComponent::class)->name('listing.chat');
// // ILI
// Route::get('/oglasi/{listingId}/chat', ConversationComponent::class)->name('listing.chat');
// Route::get('/oglasi/{listingSlug}/chat', ConversationComponent::class)->name('listing.chat');
// // Koristite {slug} kao parametar
// Route::get('/oglasi/{slug}/chat', ConversationComponent::class)->name('listing.chat');

// // Proverite da li imate ovakvu rutu
// Route::get('/oglasi/{listing}', [ListingController::class, 'show'])->name('listing.show');

// Ruta za listu poruka
Route::middleware('auth')->get('/moj-kp/poruke', MessagesList::class)->name('messages.inbox');

// Ruta za konverzaciju sa dodatnim parametrom za korisnika
Route::middleware('auth')->get('/oglasi/{slug}/poruka/{user?}', ConversationComponent::class)->name('listing.chat');

// Ruta za prikaz pojedinačnog oglasa
Route::get('/oglasi/{listing:slug}', [App\Http\Controllers\ListingController::class, 'show'])->name('listing.show');

// Alternativno, ako želite da koristite jedan namespace:
Route::middleware('auth')->group(function () {
    Route::get('/moj-kp/poruke', MessagesList::class)->name('messages.inbox');
    Route::get('/oglasi/{slug}/poruka/{user?}', ConversationComponent::class)->name('listing.chat');
});

// // Za kupca (bez user parametra)
// Route::get('/oglasi/{slug}/poruka', ConversationComponent::class)->name('listing.chat');

// // Za vlasnika oglasa (sa user parametrom)
// Route::get('/oglasi/{slug}/poruka/{user}', ConversationComponent::class)->name('listing.chat.user');

// Ruta za favorites stranicu
Route::middleware('auth')->get('/moj-kp/omiljeni', FavoritesIndex::class)->name('favorites.index');

// Opciono: API rute za AJAX pozive
Route::middleware('auth')->group(function () {
    Route::post('/favorites/{listing}', function (Listing $listing) {
        Auth::user()->addToFavorites($listing);
        return response()->json(['status' => 'added']);
    })->name('favorites.add');
    
    Route::delete('/favorites/{listing}', function (Listing $listing) {
        Auth::user()->removeFromFavorites($listing);
        return response()->json(['status' => 'removed']);
    })->name('favorites.remove');
});

// Notifikacije
Route::middleware('auth')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
});

// Sistemske konverzacije (obaveštenja)
Route::middleware('auth')->get('/poruke/{slug}/system', [ConversationComponent::class, 'mount'])
    ->name('listing.system-chat');

    // Obaveštenja
Route::middleware('auth')->get('/obavestenja', Notifications::class)->name('notifications.index');


// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', \App\Livewire\Admin\Dashboard::class)->name('dashboard');
    Route::get('/users', \App\Livewire\Admin\UserManagement::class)->name('users.index');
    Route::get('/listings', \App\Livewire\Admin\ListingManagement::class)->name('listings.index');
    Route::get('/categories', \App\Livewire\Admin\CategoryManagement::class)->name('categories.index');
    Route::get('/conditions', \App\Livewire\Admin\ConditionManagement::class)->name('conditions.index');
    Route::get('/messages', \App\Livewire\Admin\MessageManagement::class)->name('messages.index');
    Route::get('/transactions', \App\Livewire\Admin\TransactionManagement::class)->name('transactions.index');
    Route::get('/notifications', \App\Livewire\Admin\NotificationManagement::class)->name('notifications.index');
    Route::get('/images', \App\Livewire\Admin\ImageManagement::class)->name('images.index');
    Route::get('/settings', \App\Livewire\Admin\Settings::class)->name('settings');
});