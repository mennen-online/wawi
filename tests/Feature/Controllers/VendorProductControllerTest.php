<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\VendorProduct;

use App\Models\Vendor;
use App\Models\Product;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VendorProductControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(
            User::factory()->create(['email' => 'admin@admin.com'])
        );

        $this->withoutExceptionHandling();
    }

    /**
     * @test
     */
    public function it_displays_index_view_with_vendor_products()
    {
        $vendorProducts = VendorProduct::factory()
            ->count(5)
            ->create();

        $response = $this->get(route('vendor-products.index'));

        $response
            ->assertOk()
            ->assertViewIs('app.vendor_products.index')
            ->assertViewHas('vendorProducts');
    }

    /**
     * @test
     */
    public function it_displays_create_view_for_vendor_product()
    {
        $response = $this->get(route('vendor-products.create'));

        $response->assertOk()->assertViewIs('app.vendor_products.create');
    }

    /**
     * @test
     */
    public function it_stores_the_vendor_product()
    {
        $data = VendorProduct::factory()
            ->make()
            ->toArray();

        $response = $this->post(route('vendor-products.store'), $data);

        $this->assertDatabaseHas('vendor_products', $data);

        $vendorProduct = VendorProduct::latest('id')->first();

        $response->assertRedirect(
            route('vendor-products.edit', $vendorProduct)
        );
    }

    /**
     * @test
     */
    public function it_displays_show_view_for_vendor_product()
    {
        $vendorProduct = VendorProduct::factory()->create();

        $response = $this->get(route('vendor-products.show', $vendorProduct));

        $response
            ->assertOk()
            ->assertViewIs('app.vendor_products.show')
            ->assertViewHas('vendorProduct');
    }

    /**
     * @test
     */
    public function it_displays_edit_view_for_vendor_product()
    {
        $vendorProduct = VendorProduct::factory()->create();

        $response = $this->get(route('vendor-products.edit', $vendorProduct));

        $response
            ->assertOk()
            ->assertViewIs('app.vendor_products.edit')
            ->assertViewHas('vendorProduct');
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

        $response = $this->put(
            route('vendor-products.update', $vendorProduct),
            $data
        );

        $data['id'] = $vendorProduct->id;

        $this->assertDatabaseHas('vendor_products', $data);

        $response->assertRedirect(
            route('vendor-products.edit', $vendorProduct)
        );
    }

    /**
     * @test
     */
    public function it_deletes_the_vendor_product()
    {
        $vendorProduct = VendorProduct::factory()->create();

        $response = $this->delete(
            route('vendor-products.destroy', $vendorProduct)
        );

        $response->assertRedirect(route('vendor-products.index'));

        $this->assertDeleted($vendorProduct);
    }
}
