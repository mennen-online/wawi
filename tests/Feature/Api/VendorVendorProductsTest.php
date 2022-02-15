<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorProduct;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VendorVendorProductsTest extends TestCase
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
    public function it_gets_vendor_vendor_products()
    {
        $vendor = Vendor::factory()->create();
        $vendorProducts = VendorProduct::factory()
            ->count(2)
            ->create([
                'vendor_id' => $vendor->id,
            ]);

        $response = $this->getJson(
            route('api.vendors.vendor-products.index', $vendor)
        );

        $response->assertOk()->assertSee($vendorProducts[0]->id);
    }

    /**
     * @test
     */
    public function it_stores_the_vendor_vendor_products()
    {
        $vendor = Vendor::factory()->create();
        $data = VendorProduct::factory()
            ->make([
                'vendor_id' => $vendor->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.vendors.vendor-products.store', $vendor),
            $data
        );

        $this->assertDatabaseHas('vendor_products', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $vendorProduct = VendorProduct::latest('id')->first();

        $this->assertEquals($vendor->id, $vendorProduct->vendor_id);
    }
}
