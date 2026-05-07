<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_a_user_can_view_their_messages()
    {
        $user = User::factory()->create(['role' => 'patient']);
        
        $response = $this->actingAs($user)->get(route('messages.index'));

        $response->assertStatus(200);
        $response->assertViewIs('messages.index');
    }

    /** @test */
    public function test_a_user_can_send_a_message()
    {
        $sender = User::factory()->create(['role' => 'patient']);
        $receiver = User::factory()->create(['role' => 'medecin']);

        $data = [
            'receiver_id' => $receiver->id,
            'contenu' => 'Bonjour docteur, j\'ai une question.',
        ];

        $response = $this->actingAs($sender)->post(route('messages.store'), $data);

        $response->assertRedirect();
        $this->assertDatabaseHas('messages', [
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'contenu' => 'Bonjour docteur, j\'ai une question.',
        ]);
    }
}
