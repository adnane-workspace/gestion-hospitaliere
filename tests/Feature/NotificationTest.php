<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_a_user_can_fetch_their_notifications()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson(route('notifications.index'));

        $response->assertStatus(200);
        $response->assertJsonStructure(['unread_count', 'notifications']);
    }

    /** @test */
    public function test_a_user_can_mark_all_notifications_as_read()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson(route('notifications.readAll'));

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
    }
}
