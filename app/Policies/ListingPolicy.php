<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Listing;

class ListingPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function viewAny(?User $user)
    {
        // Svi mogu da vide oglase
        return true;
    }
    
    public function view(?User $user, Listing $listing)
    {
        // Svi mogu da vide pojedinačne oglase
        return true;
    }
    
    public function create(User $user)
    {
        // Samo registrovani mogu da kreiraju
        return $user !== null;
    }
    
    public function update(User $user, Listing $listing)
    {
        // Samo vlasnik može da edituje
        if ($user->id !== $listing->user_id) {
            return false;
        }

        // Ne može da edituje ako ima aukciju sa ponudama
        if ($listing->auction && $listing->auction->total_bids > 0) {
            return false;
        }

        return true;
    }
    
    public function contact(?User $user, Listing $listing)
    {
        // Samo registrovani mogu da kontaktiraju
        // Ali ne mogu sami sebe
        return $user !== null && $user->id !== $listing->user_id;
    }
}
