<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class httpTests extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function testFirstEndpoint(): void
    {
        $response = $this->get(config('http-client.host') . '/first-endpoint');

        $response->assertStatus(200);
    }

    public function testPostEndpointValid(): void
    {
        $response = $this->post(config('http-client.host') . '/post-endpoint', [
            'name' => 'xxx yyy',
            'email' => 'ahoj@gmail.com',
            'password' => 'ahoj xxx',
        ]);

        $response->assertStatus(200);
    }

    public function testPostEndpointInvalidEmail(): void
    {
        $response = $this->post(config('http-client.host') . '/post-endpoint', [
            'name' => 'xxx yyy',
            'email' => 'ahojatgmail.com',
            'password' => 'ahoj xxx',
        ]);

        $response->assertStatus(400);
    }

    public function testPostEndpointMissingEmail(): void
    {
        $response = $this->post(config('http-client.host') . '/post-endpoint', [
            'name' => 'xxx yyy',
            'password' => 'ahoj xxx',
        ]);

        $response->assertStatus(400);
    }
}
