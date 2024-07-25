<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\NotificationPreference;
use Tymon\JWTAuth\Facades\JWTAuth;

class NotificationPreferenceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test set preference setting
     */

    public function testSetNotificationPreferences()
    {
        // Create a user and generate a token
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        // Send a POST request with the token
        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/v1/settings/notification-settings', [
                'email_notifications' => true,
                'push_notifications' => false,
                'sms_notifications' => true,
            ]);

        // Assert that the response is as expected
        $response->assertStatus(201)
                 ->assertJson([
                     'message' => 'Notification preferences created successfully.',
                 ]);

        // Check if preferences are created in the database
        $this->assertDatabaseHas('notification_preferences', [
            'user_id' => $user->id,
            'email_notifications' => true,
            'push_notifications' => false,
            'sms_notifications' => true,
        ]);
    }
}