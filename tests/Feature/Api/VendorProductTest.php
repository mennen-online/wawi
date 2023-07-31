<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\VendorProduct;

use App\Models\Vendor;
use App\Models\Product;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VendorProductTest extends TestCase
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
    public function it_gets_vendor_products_list()
    {
        $vendorProducts = VendorProduct::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(route('api.vendor-products.index'));

        $response->assertOk()->assertSee($vendorProducts[0]->id);
    }

    /**
     * @test
     */
    public function it_stores_the_vendor_product()
    {
        $data = VendorProduct::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(route('api.vendor-products.store'), $data);

        $this->assertDatabaseHas('vendor_products', $data);

        $data['price'] *= 1.25;

        $response->assertStatus(201)->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_updates_the_vendor_product()
    {
        $vendorProduct = VendorProduct::factory()->create();

        $vendor = Vendor::factory()->create();
        $product = Product::factory()->create();

        $data = [
            'price' => $this->faker->randomFloat(2, 0, 9999),
            'available' => $this->faker->boolean,
            'vendor_id' => $vendor->id,
            'product_id' => $product->id,
        ];

        $response = $this->putJson(
            route('api.vendor-products.update', $vendorProduct),
            $data
        );

        $data['id'] = $vendorProduct->id;

        $data['price'] *= 1.25;

        $this->assertDatabaseHas('vendor_products', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_vendor_product()
    {
        $vendorProduct = VendorProduct::factory()->create();

        $response = $this->deleteJson(
            route('api.vendor-products.destroy', $vendorProduct)
        );

        $this->assertDatabaseMissing('vendor_products', $vendorProduct->toArray());

        $response->assertNoContent();
    }
}
