<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Test trang chủ chuyển hướng đến trang danh sách thiết bị.
     */
    public function test_the_application_redirects_to_devices(): void
    {
        $response = $this->get('/');
        $response->assertRedirect('/devices');

        $deviceResponse = $this->get('/devices');
        $deviceResponse->assertStatus(200);
    }
}
