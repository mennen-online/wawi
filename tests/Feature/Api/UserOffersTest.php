<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Offer;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserOffersTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create(['email' => 'admin@admin.com']);

        Sanctum::actingAs($user, [], 'web');

        $this->withoutExceptionHandling();
    }

    /**
     * @test
     */
    public function it_gets_user_offers()
    {
        $user = User::factory()->create();
        $offers = Offer::factory()
            ->count(2)
            ->create([
                'user_id' => $user->id,
            ]);

        $response = $this->getJson(route('api.users.offers.index', $user));

        $response->assertOk()->assertSee($offers[0]->resource_id);
    }

    /**
     * @test
     */
    public function it_stores_the_user_offers()
    {
        $user = User::factory()->create();
        $data = Offer::factory()
            ->make([
                'user_id' => $user->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.users.offers.store', $user),
            $data
        );

        unset($data['resource_id']);
        unset($data['user_id']);

        $this->assertDatabaseHas('offers', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $offer = Offer::latest('id')->first();

        $this->assertEquals($user->id, $offer->user_id);
    }
}
