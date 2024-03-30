<?php

namespace Tests\Unit;

use App\Models\Card;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CardControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a dummy user
        $user = User::factory()->create();

        // Authenticate the user
        $this->actingAs($user);
    }

    /** @test */
    public function it_returns_user_cards()
    {
        $user = User::factory()->create();

        // Create some cards for the user
        $cards = Card::factory()->count(3)->create(['users_id' => $user->id]);

        // Make a GET request to the index method
        $response = $this->getJson('/api/cards');

        // Assert the response status is 200
        $response->assertStatus(200);

        // Assert the response contains the user's cards data
        $response->assertJsonFragment(['data' => $cards->toArray()]);
    }

    /** @test */
    public function it_creates_a_new_card()
    {
        $user = User::factory()->create();

        // Card data
        $cardData = Card::factory()->raw(['users_id' => $user->id]);

        // Make a POST request to the store method with card data
        $response = $this->postJson('/api/cards', $cardData);

        // Assert the response status is 200
        $response->assertStatus(200);

        // Assert the response contains the message confirming the card creation
        $response->assertJson(['message' => 'Business card created successfully']);
    }

    /** @test */
    public function it_updates_an_existing_card()
    {
        $user = User::factory()->create();

        // Create a card for the user
        $card = Card::factory()->create(['user_id' => $user->id]);

        // Updated card data
        $updatedData = ['name' => 'Updated Name'];

        // Make a PUT request to the update method with updated card data
        $response = $this->putJson("/api/cards/{$card->id}", $updatedData);

        // Assert the response status is 200
        $response->assertStatus(200);

        // Assert the response contains the message confirming the card update
        $response->assertJson(['message' => 'Business card updated successfully']);
    }

    /** @test */
    public function it_deletes_an_existing_card()
    {
        $user = User::factory()->create();

        // Create a card for the user
        $card = Card::factory()->create(['user_id' => $user->id]);

        // Make a DELETE request to the delete method
        $response = $this->deleteJson("/api/cards/{$card->id}");

        // Assert the response status is 200
        $response->assertStatus(200);

        // Assert the response contains the message confirming the card deletion
        $response->assertJson(['message' => 'Business card deleted successfully']);
    }
}
