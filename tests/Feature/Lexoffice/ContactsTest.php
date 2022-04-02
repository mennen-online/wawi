<?php

namespace Tests\Feature\Lexoffice;

use App\Services\Lexoffice\Endpoints\Contacts;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class ContactsTest extends TestCase
{
    use WithFaker;

    private Contacts $contacts;

    protected function setUp(): void {
        parent::setUp();

        $this->contacts = new Contacts();

        Http::fake([
            '*' => Http::response()
        ]);
    }

    public function testContactsEndpointIndexReceivesCorrectEndpointString() {
        $response = $this->contacts->index();

        $url = $response->effectiveUri()->getPath();

        $this->assertSame('/v1/contacts', $url);
    }

    public function testContactsEndpointShowReceivesCorrectEndpointString() {
        $id = 'idString';

        $response = $this->contacts->show($id);

        $url = $response->effectiveUri()->getPath();

        $this->assertSame('/v1/contacts/'.$id, $url);
    }

    public function testContactsEndpointStoreReceivesCorrectEndpointString() {
        $data = [];

        $response = $this->contacts->store($data);

        $url = $response->effectiveUri()->getPath();

        $this->assertSame('/v1/contacts', $url);
    }

    public function testContactsEndpointUpdateReceiveCorrectEndpointString() {
        $id = 'idString';

        $data = [];

        $response = $this->contacts->update($id, $data);

        $url = $response->effectiveUri()->getPath();

        $this->assertSame('/v1/contacts/'.$id, $url);
    }

    public function testContactsEndpointIndexEmailFilter() {
        $email = $this->faker->email;

        $query = [
            'email' => $email
        ];

        $response = $this->contacts->setEmail($email)->index();

        $url = $response->effectiveUri()->getPath().'?'.$response->effectiveUri()->getQuery();

        $this->assertSame('/v1/contacts?'.Arr::query($query), $url);
    }

    public function testContactsEndpointIndexVendorFilter() {
        $response = $this->contacts->onlyVendor()->index();

        $url = $response->effectiveUri()->getPath().'?'.$response->effectiveUri()->getQuery();

        $this->assertStringContainsString('/v1/contacts?', $url);

        $this->assertStringContainsString('vendor=1', $url);

        $this->assertStringContainsString('customer=0', $url);
    }

    public function testContactsEndpointIndexCustomerFilter() {
        $response = $this->contacts->onlyCustomer()->index();

        $url = $response->effectiveUri()->getPath().'?'.$response->effectiveUri()->getQuery();

        $this->assertStringContainsString('/v1/contacts?', $url);

        $this->assertStringContainsString('customer=1', $url);

        $this->assertStringContainsString('vendor=0', $url);

        $this->assertSame('/v1/contacts?customer=1&vendor=0', $url);
    }

    public function testContactsEndpointIndexNumberFilter() {
        $number = "10111";

        $response = $this->contacts->setNumber($number)->index();

        $url = $response->effectiveUri()->getPath().'?'.$response->effectiveUri()->getQuery();

        $this->assertStringContainsString('/v1/contacts?', $url);

        $this->assertStringContainsString('number='.$number, $url);
    }

    public function testContactsEndpointIndexNameFilter() {
        $name = $this->faker->name;

        $response = $this->contacts->setName($name)->index();

        $url = $response->effectiveUri()->getPath().'?'.$response->effectiveUri()->getQuery();

        $this->assertStringContainsString('/v1/contacts?', $url);

        $this->assertStringContainsString('name='.utf8_encode(Str::replace(' ', '%20', $name)), $url);
    }
}
