<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Product;
use App\Models\VendorProduct;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductVendorProductsTest extends TestCase
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
    public function it_gets_product_vendor_products()
    {
        $product = Product::factory()->create();
        $vendorProducts = VendorProduct::factory()
            ->count(2)
            ->create([
                'product_id' => $product->id,
            ]);

        $response = $this->getJson(
            route('api.products.vendor-products.index', $product)
        );

        $response->assertOk()->assertSee($vendorProducts[0]->id);
    }

    /**
     * @test
     */
    public function it_stores_the_product_vendor_products()
    {
        $product = Product::factory()->create();
        $data = VendorProduct::factory()
            ->make([
                'product_id' => $product->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.products.vendor-products.store', $product),
            $data
        );

        $this->assertDatabaseHas('vendor_products', $data);

        $data['price'] *= 1.25;

        $response->assertStatus(201)->assertJsonFragment($data);

        $vendorProduct = VendorProduct::latest('id')->first();

        $this->assertEquals($product->id, $vendorProduct->product_id);
    }
}
