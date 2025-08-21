<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Listing;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GuestAccessTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    public function test_guest_can_view_home_page()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }
    
    public function test_guest_can_view_listing_detail()
    {
        $listing = Listing::factory()->create();
        
        $response = $this->get(route('listings.show', $listing));
        $response->assertStatus(200);
        $response->assertSee($listing->title);
    }
    
    public function test_guest_cannot_see_phone_if_not_visible()
    {
        $user = User::factory()->create(['phone_visible' => false, 'phone' => '064/123-456']);
        $listing = Listing::factory()->create(['user_id' => $user->id]);
        
        $response = $this->get(route('listings.show', $listing));
        $response->assertDontSee('064/123-456');
    }
    
    public function test_guest_cannot_contact_seller()
    {
        $listing = Listing::factory()->create();
        
        $response = $this->post(route('messages.store'), [
            'listing_id' => $listing->id,
            'message' => 'Test message'
        ]);
        
        $response->assertRedirect(route('login'));
    }
}
