<?php

namespace Tests\Feature;

use App\Models\Device;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LabManagerTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Tạo Admin và Regular User
        $this->adminUser = User::factory()->create([
            'name' => 'Quản trị viên Huy',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('12345678'),
            'role' => 'admin',
        ]);

        $this->regularUser = User::factory()->create([
            'name' => 'Sinh viên A',
            'email' => 'user@gmail.com',
            'password' => bcrypt('12345678'),
            'role' => 'user',
        ]);
    }

    // ==========================================
    // TEST NGÀY 1: AUTHENTICATION & MIDDLEWARE
    // ==========================================

    public function test_guest_can_view_login_page(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Đăng nhập');
    }

    public function test_guest_cannot_access_admin_dashboard(): void
    {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_regular_user_cannot_access_admin_dashboard(): void
    {
        $response = $this->actingAs($this->regularUser)->get('/admin/dashboard');
        $response->assertStatus(403);
    }

    public function test_admin_can_login_and_redirect_to_dashboard(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@gmail.com',
            'password' => '12345678',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($this->adminUser);
    }

    public function test_guest_can_register_new_account(): void
    {
        $response = $this->post('/register', [
            'name' => 'Trần Văn Mới',
            'email' => 'newuser@example.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ]);

        $response->assertRedirect('/devices');
        $this->assertDatabaseHas('users', [
            'email' => 'newuser@example.com',
            'role' => 'user',
        ]);
    }

    public function test_authenticated_user_can_logout(): void
    {
        $response = $this->actingAs($this->adminUser)->post('/logout');
        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    // ==========================================
    // TEST NGÀY 2: GIAO DIỆN ADMIN DASHBOARD
    // ==========================================

    public function test_admin_can_access_dashboard_with_layout_and_elements(): void
    {
        Device::factory()->create(['name' => 'Máy chiếu Sony', 'status' => 'available']);
        Device::factory()->create(['name' => 'Laptop Dell', 'status' => 'borrowed']);

        $response = $this->actingAs($this->adminUser)->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Lab Admin');
        $response->assertSee('Bảng điều khiển');
        $response->assertSee('Tổng số thiết bị');
    }

    // ==========================================
    // TEST NGÀY 3: HIỂN THỊ DỮ LIỆU & MYSQL GROUP BY
    // ==========================================

    public function test_admin_devices_index_displays_data_table_and_group_by_counts(): void
    {
        Device::factory()->count(3)->create(['status' => 'available']);
        Device::factory()->count(2)->create(['status' => 'borrowed']);
        Device::factory()->count(1)->create(['status' => 'maintenance']);

        $response = $this->actingAs($this->adminUser)->get('/admin/devices');
        $response->assertStatus(200);
        $response->assertSee('Danh sách Thiết bị');
        $response->assertSee('Đang rảnh');
        $response->assertSee('Đang mượn');
        $response->assertSee('Bảo trì');
    }

    // ==========================================
    // TEST NGÀY 4: FORM THÊM / SỬA & VALIDATION RESTFUL
    // ==========================================

    public function test_admin_can_view_create_device_form(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/admin/devices/create');
        $response->assertStatus(200);
        $response->assertSee('Nhập thông tin thiết bị');
    }

    public function test_admin_cannot_create_device_with_invalid_data(): void
    {
        $response = $this->actingAs($this->adminUser)->post('/admin/devices', [
            'name' => '', // Tên trống
            'type' => '',
            'status' => 'invalid_status',
        ]);

        $response->assertSessionHasErrors(['name', 'type', 'status']);
    }

    public function test_admin_can_create_device_successfully(): void
    {
        $response = $this->actingAs($this->adminUser)->post('/admin/devices', [
            'name' => 'Máy chiếu Epson EB-X06',
            'type' => 'Máy chiếu',
            'status' => 'available',
        ]);

        $response->assertRedirect(route('admin.devices.index'));
        $this->assertDatabaseHas('devices', [
            'name' => 'Máy chiếu Epson EB-X06',
            'type' => 'Máy chiếu',
            'status' => 'available',
        ]);
    }

    public function test_admin_can_update_device_via_put(): void
    {
        $device = Device::factory()->create([
            'name' => 'Thiết bị Cũ',
            'type' => 'Laptop',
            'status' => 'available',
        ]);

        $response = $this->actingAs($this->adminUser)->put("/admin/devices/{$device->id}", [
            'name' => 'Thiết bị Đã Sửa Tên',
            'type' => 'Laptop',
            'status' => 'maintenance',
        ]);

        $response->assertRedirect(route('admin.devices.index'));
        $this->assertDatabaseHas('devices', [
            'id' => $device->id,
            'name' => 'Thiết bị Đã Sửa Tên',
            'status' => 'maintenance',
        ]);
    }

    public function test_admin_can_delete_device_via_delete(): void
    {
        $device = Device::factory()->create(['name' => 'Thiết bị Sắp Xóa']);

        $response = $this->actingAs($this->adminUser)->delete("/admin/devices/{$device->id}");

        $response->assertRedirect(route('admin.devices.index'));
        $this->assertDatabaseMissing('devices', ['id' => $device->id]);
    }

    // ==========================================
    // TEST TÍCH HỢP FLOW USER (LOAN & VIỆT)
    // ==========================================

    public function test_user_devices_page_loads_successfully(): void
    {
        Device::factory()->create(['name' => 'Micro không dây Shure']);

        $response = $this->get('/devices');
        $response->assertStatus(200);
        $response->assertSee('Danh sách thiết bị');
        $response->assertSee('Micro không dây Shure');
    }

    public function test_api_devices_returns_json(): void
    {
        Device::factory()->count(3)->create();

        $response = $this->getJson('/api/devices');
        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }

    public function test_api_borrow_device_success(): void
    {
        $device = Device::factory()->create(['status' => 'available']);

        $response = $this->actingAs($this->regularUser)->postJson('/api/devices/borrow', [
            'device_id' => $device->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Mượn thiết bị thành công!']);

        $this->assertDatabaseHas('devices', [
            'id' => $device->id,
            'status' => 'borrowed',
        ]);

        $this->assertDatabaseHas('transactions', [
            'device_id' => $device->id,
            'user_id' => $this->regularUser->id,
            'status' => 'active',
        ]);
    }

    public function test_api_cannot_borrow_already_borrowed_device(): void
    {
        $device = Device::factory()->create(['status' => 'borrowed']);

        $response = $this->actingAs($this->regularUser)->postJson('/api/devices/borrow', [
            'device_id' => $device->id,
        ]);

        $response->assertStatus(400);
        $response->assertJson(['error' => 'Thiết bị này không sẵn sàng để mượn.']);
    }
}
