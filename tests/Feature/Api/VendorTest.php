<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Vendor;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VendorTest extends TestCase
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
    public function it_gets_vendors_list()
    {
        $vendors = Vendor::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(route('api.vendors.index'));

        $response->assertOk()->assertSee($vendors[0]->resource_id);
    }

    /**
     * @test
     */
    public function it_stores_the_vendor()
    {
        $data = Vendor::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(route('api.vendors.store'), $data);

        unset($data['resource_id']);

        $this->assertDatabaseHas('vendors', $data);

        $response->assertStatus(201)->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_updates_the_vendor()
    {
        $vendor = Vendor::factory()->create();

        $data = [
            'resource_id' => $this->faker->text(255),
            'company' => $this->faker->text(255),
            'email' => $this->faker->email,
            'phone' => $this->faker->phoneNumber,
            'salutation' => $this->faker->text(255),
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
        ];

        $response = $this->putJson(route('api.vendors.update', $vendor), $data);

        unset($data['resource_id']);

        $data['id'] = $vendor->id;

        $this->assertDatabaseHas('vendors', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_vendor()
    {
        $vendor = Vendor::factory()->create();

        $response = $this->deleteJson(route('api.vendors.destroy', $vendor));

        $this->assertSoftDeleted($vendor);

        $response->assertNoContent();
    }
}
