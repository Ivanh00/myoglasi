<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserPhoneVisibilityTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_phone_visible_when_enabled_and_user_authenticated()
    {
        $user = User::factory()->create([
            'phone' => '064/123-456',
            'phone_visible' => true
        ]);
        
        $viewer = User::factory()->create();
        
        $this->assertTrue($user->canViewPhone($viewer));
    }
    
    public function test_phone_not_visible_when_disabled()
    {
        $user = User::factory()->create([
            'phone' => '064/123-456',
            'phone_visible' => false
        ]);
        
        $viewer = User::factory()->create();
        
        $this->assertFalse($user->canViewPhone($viewer));
    }
    
    public function test_phone_not_visible_to_guest()
    {
        $user = User::factory()->create([
            'phone' => '064/123-456',
            'phone_visible' => true
        ]);
        
        $this->assertFalse($user->canViewPhone(null));
    }
}
