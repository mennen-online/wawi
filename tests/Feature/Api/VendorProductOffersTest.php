<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Offer;
use App\Models\VendorProduct;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VendorProductOffersTest extends TestCase
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
    public function it_gets_vendor_product_offers()
    {
        $vendorProduct = VendorProduct::factory()->create();
        $offer = Offer::factory()->create();

        $vendorProduct->offers()->attach($offer);

        $response = $this->getJson(
            route('api.vendor-products.offers.index', $vendorProduct)
        );

        $response->assertOk()->assertSee($offer->resource_id);
    }

    /**
     * @test
     */
    public function it_can_attach_offers_to_vendor_product()
    {
        $vendorProduct = VendorProduct::factory()->create();
        $offer = Offer::factory()->create();

        $response = $this->postJson(
            route('api.vendor-products.offers.store', [$vendorProduct, $offer])
        );

        $response->assertNoContent();

        $this->assertTrue(
            $vendorProduct
                ->offers()
                ->where('offers.id', $offer->id)
                ->exists()
        );
    }

    /**
     * @test
     */
    public function it_can_detach_offers_from_vendor_product()
    {
        $vendorProduct = VendorProduct::factory()->create();
        $offer = Offer::factory()->create();

        $response = $this->deleteJson(
            route('api.vendor-products.offers.store', [$vendorProduct, $offer])
        );

        $response->assertNoContent();

        $this->assertFalse(
            $vendorProduct
                ->offers()
                ->where('offers.id', $offer->id)
                ->exists()
        );
    }
}
