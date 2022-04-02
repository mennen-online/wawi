<?php

namespace Tests\Feature\Lexoffice;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ConfigTest extends TestCase
{
    public function testLexofficeTokenIsNull() {
        $this->app['config']->set('lexoffice.token', null);

        $this->assertNull(config('lexoffice.token'));
    }

    public function testLexofficeTokenIsNotNull() {
        $token = 'Test123';

        $this->app['config']->set('lexoffice.token', $token);

        $this->assertSame($token, config('lexoffice.token'));
    }
}
