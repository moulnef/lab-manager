<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /** Trang chủ và trang danh sách thiết bị hoạt động. */
    public function test_trang_chu_va_danh_sach_thiet_bi_hoat_dong(): void
    {
        $this->get('/')->assertOk();
        $this->get('/devices')->assertOk();
    }
}
