<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Device;
use App\Models\Transaction;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LabManagerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $user;
    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->admin()->create(['email' => 'admin@test.vn']);
        $this->user = User::factory()->create(['email' => 'user@test.vn']);
        $this->category = Category::factory()->create(['name' => 'Laptop & Máy tính', 'icon' => 'laptop']);
    }

    private function device(array $attrs = []): Device
    {
        return Device::factory()->create(array_merge(['category_id' => $this->category->id], $attrs));
    }

    // =========================================================
    // NGÀY 1 – CSDL, đăng nhập & phân quyền
    // =========================================================

    public function test_seeder_tao_du_du_lieu_mau(): void
    {
        $this->category->delete();
        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseHas('users', ['email' => 'admin@gmail.com', 'role' => 'admin']);
        $this->assertDatabaseHas('users', ['email' => 'user@gmail.com', 'role' => 'user']);
        $this->assertSame(6, Category::count());
        $this->assertSame(143, Device::count());
        $this->assertSame(15, Transaction::count());

        // Trạng thái thiết bị nhất quán với phiếu đang mượn
        $approved = Transaction::where('status', 'approved')->pluck('device_id');
        $this->assertSame($approved->count(), Device::whereIn('id', $approved)->where('status', 'borrowed')->count());
    }

    public function test_dang_ky_tao_tai_khoan_role_user(): void
    {
        $this->post('/register', [
            'name' => 'Sinh viên Mới',
            'email' => 'moi@test.vn',
            'password' => 'matkhau123',
            'password_confirmation' => 'matkhau123',
        ])->assertRedirect(route('home'));

        $this->assertDatabaseHas('users', ['email' => 'moi@test.vn', 'role' => 'user']);
        $this->assertAuthenticated();
    }

    public function test_dang_nhap_admin_chuyen_ve_dashboard(): void
    {
        $this->post('/login', ['email' => 'admin@test.vn', 'password' => 'password'])
            ->assertRedirect(route('admin.dashboard'));
    }

    public function test_dang_nhap_sai_bao_loi_tieng_viet(): void
    {
        $this->from('/login')->post('/login', ['email' => 'admin@test.vn', 'password' => 'sai'])
            ->assertRedirect('/login')
            ->assertSessionHasErrors(['email' => 'Email hoặc mật khẩu không chính xác.']);
    }

    public function test_khach_vao_admin_bi_chuyen_ve_login(): void
    {
        $this->get('/admin/dashboard')->assertRedirect('/login');
    }

    public function test_user_thuong_bi_chan_toan_bo_route_admin(): void
    {
        $device = $this->device();
        $tx = Transaction::factory()->create(['user_id' => $this->user->id, 'device_id' => $device->id]);

        $routes = [
            ['get', '/admin/dashboard'],
            ['get', '/admin/categories'],
            ['get', '/admin/categories/create'],
            ['post', '/admin/categories'],
            ['get', "/admin/categories/{$this->category->id}/edit"],
            ['put', "/admin/categories/{$this->category->id}"],
            ['delete', "/admin/categories/{$this->category->id}"],
            ['get', '/admin/devices'],
            ['get', '/admin/devices/create'],
            ['post', '/admin/devices'],
            ['get', "/admin/devices/{$device->id}"],
            ['get', "/admin/devices/{$device->id}/edit"],
            ['put', "/admin/devices/{$device->id}"],
            ['delete', "/admin/devices/{$device->id}"],
            ['get', '/admin/devices/trash'],
            ['patch', "/admin/devices/{$device->id}/restore"],
            ['get', '/admin/transactions'],
            ['patch', "/admin/transactions/{$tx->id}/approve"],
            ['patch', "/admin/transactions/{$tx->id}/reject"],
            ['patch', "/admin/transactions/{$tx->id}/return"],
        ];

        foreach ($routes as [$method, $uri]) {
            $this->actingAs($this->user)->{$method}($uri)->assertForbidden();
        }
        // Khách: mọi route admin phải chuyển về trang đăng nhập
        auth()->logout();
        foreach ($routes as [$method, $uri]) {
            $this->{$method}($uri)->assertRedirect('/login');
        }

        $this->assertDatabaseHas('devices', ['id' => $device->id, 'deleted_at' => null]);
    }

    // =========================================================
    // NGÀY 2 – Dashboard, danh sách, chi tiết
    // =========================================================

    public function test_admin_xem_dashboard_dung_so_lieu(): void
    {
        $this->device();
        $this->device(['status' => 'maintenance']);

        $this->actingAs($this->admin)->get('/admin/dashboard')
            ->assertOk()
            ->assertSee('Tổng quan')
            ->assertViewHas('stats', fn ($s) => $s['devices'] === 2 && $s['maintenance'] === 1 && $s['available'] === 1);
    }

    public function test_trang_chu_va_chi_tiet_thiet_bi(): void
    {
        $device = $this->device(['name' => 'Arduino Uno R3']);

        $this->get('/')->assertOk()->assertSee('Arduino Uno R3');
        $this->get("/devices/{$device->id}")->assertOk()->assertSee($device->code);
        $this->get('/devices/99999')->assertNotFound();
    }

    public function test_danh_sach_danh_muc_hien_so_thiet_bi(): void
    {
        $this->device();
        $this->device();

        $this->actingAs($this->admin)->get('/admin/categories')
            ->assertOk()
            ->assertViewHas('categories', fn ($c) => $c->first()->devices_count === 2);
    }

    // =========================================================
    // NGÀY 3 – CRUD 2 bảng, upload, xóa mềm, tìm kiếm + phân trang
    // =========================================================

    public function test_crud_danh_muc(): void
    {
        $this->actingAs($this->admin)->post('/admin/categories', ['name' => 'Thiết bị mạng', 'icon' => 'router'])
            ->assertRedirect(route('admin.categories.index'))
            ->assertSessionHas('success');
        $cat = Category::where('name', 'Thiết bị mạng')->firstOrFail();

        $this->actingAs($this->admin)->put("/admin/categories/{$cat->id}", ['name' => 'Thiết bị mạng LAN'])
            ->assertSessionHas('success');
        $this->assertDatabaseHas('categories', ['id' => $cat->id, 'name' => 'Thiết bị mạng LAN']);

        $this->actingAs($this->admin)->delete("/admin/categories/{$cat->id}")->assertSessionHas('success');
        $this->assertDatabaseMissing('categories', ['id' => $cat->id]);
    }

    public function test_validate_danh_muc_bang_tieng_viet(): void
    {
        $this->actingAs($this->admin)->post('/admin/categories', ['name' => ''])
            ->assertSessionHasErrors(['name' => 'Vui lòng nhập tên danh mục.']);

        $this->actingAs($this->admin)->post('/admin/categories', ['name' => $this->category->name])
            ->assertSessionHasErrors(['name' => 'Tên danh mục này đã tồn tại.']);
    }

    public function test_chan_xoa_danh_muc_dang_co_thiet_bi(): void
    {
        $device = $this->device();
        $device->delete(); // kể cả thiết bị đã xóa mềm

        $this->actingAs($this->admin)->delete("/admin/categories/{$this->category->id}")
            ->assertSessionHas('error');
        $this->assertDatabaseHas('categories', ['id' => $this->category->id]);
    }

    public function test_them_thiet_bi_co_upload_anh(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin)->post('/admin/devices', [
            'category_id' => $this->category->id,
            'code' => 'lab-lt-100',
            'name' => 'Laptop Test',
            'status' => 'available',
            'image' => UploadedFile::fake()->image('laptop.jpg', 800, 500),
        ])->assertRedirect(route('admin.devices.index'))->assertSessionHas('success');

        $device = Device::where('code', 'LAB-LT-100')->firstOrFail(); // mã đã được viết hoa
        $this->assertNotNull($device->image);
        Storage::disk('public')->assertExists($device->image);
    }

    public function test_validate_thiet_bi(): void
    {
        $existing = $this->device();

        $this->actingAs($this->admin)->post('/admin/devices', [
            'category_id' => 999,
            'code' => $existing->code,
            'name' => 'ab',
            'status' => 'hong',
            'image' => UploadedFile::fake()->create('virus.pdf', 10, 'application/pdf'),
        ])->assertSessionHasErrors([
            'category_id' => 'Danh mục đã chọn không tồn tại.',
            'code' => 'Mã thiết bị này đã được sử dụng.',
            'name' => 'Tên thiết bị phải có ít nhất 3 ký tự.',
            'status' => 'Trạng thái không hợp lệ.',
            'image' => 'Tệp tải lên phải là hình ảnh.',
        ]);
    }

    public function test_sua_thiet_bi_va_thay_anh(): void
    {
        Storage::fake('public');
        $device = $this->device();

        $this->actingAs($this->admin)->put("/admin/devices/{$device->id}", [
            'category_id' => $this->category->id,
            'code' => $device->code,
            'name' => 'Tên mới',
            'status' => 'maintenance',
            'image' => UploadedFile::fake()->image('new.png'),
        ])->assertSessionHas('success');

        $device->refresh();
        $this->assertSame('Tên mới', $device->name);
        $this->assertSame('maintenance', $device->status);
        Storage::disk('public')->assertExists($device->image);
    }

    public function test_xoa_mem_va_khoi_phuc_thiet_bi(): void
    {
        $device = $this->device();

        $this->actingAs($this->admin)->delete("/admin/devices/{$device->id}")->assertSessionHas('success');
        $this->assertSoftDeleted('devices', ['id' => $device->id]);
        $this->get("/devices/{$device->id}")->assertNotFound();

        $this->actingAs($this->admin)->get('/admin/devices/trash')->assertOk()->assertSee($device->name);

        $this->actingAs($this->admin)->patch("/admin/devices/{$device->id}/restore")->assertSessionHas('success');
        $this->assertNotSoftDeleted('devices', ['id' => $device->id]);
    }

    public function test_khong_xoa_thiet_bi_dang_duoc_muon(): void
    {
        $device = $this->device(['status' => 'borrowed']);

        $this->actingAs($this->admin)->delete("/admin/devices/{$device->id}")->assertSessionHas('error');
        $this->assertNotSoftDeleted('devices', ['id' => $device->id]);
    }

    public function test_tim_kiem_phan_trang_giu_tu_khoa(): void
    {
        Device::factory()->count(12)->create(['category_id' => $this->category->id, 'name' => 'Laptop Dell']);
        Device::factory()->count(3)->create(['category_id' => $this->category->id, 'name' => 'Máy chiếu Epson']);

        $response = $this->get("/devices?keyword=Laptop&category_id={$this->category->id}");
        $response->assertOk()->assertSee('Laptop Dell')->assertDontSee('Máy chiếu Epson');

        $devices = $response->viewData('devices');
        $this->assertSame(12, $devices->total());
        $this->assertStringContainsString('keyword=Laptop', $devices->nextPageUrl());
        $this->assertStringContainsString('category_id='.$this->category->id, $devices->nextPageUrl());

        $this->get("/devices?keyword=Laptop&category_id={$this->category->id}&page=2")
            ->assertOk()
            ->assertViewHas('devices', fn ($p) => $p->currentPage() === 2 && $p->count() === 3);
    }

    public function test_ajax_chi_tra_ve_phan_danh_sach(): void
    {
        $this->device(['name' => 'Raspberry Pi 4']);

        $this->get('/devices?keyword=Raspberry', ['X-Requested-With' => 'XMLHttpRequest'])
            ->assertOk()
            ->assertSee('Raspberry Pi 4')
            ->assertDontSee('<html', false);
    }

    public function test_admin_tim_kiem_thiet_bi(): void
    {
        $this->device(['name' => 'Router Cisco', 'code' => 'LAB-MG-001']);
        $this->device(['name' => 'Laptop HP', 'code' => 'LAB-LT-001']);

        $this->actingAs($this->admin)->get('/admin/devices?keyword=MG-001')
            ->assertOk()->assertSee('Router Cisco')->assertDontSee('Laptop HP');
    }

    // =========================================================
    // NGÀY 4 – Nghiệp vụ mượn – trả
    // =========================================================

    private function borrowPayload(Device $device, array $override = []): array
    {
        return array_merge([
            'device_id' => $device->id,
            'borrow_date' => today()->toDateString(),
            'return_date' => today()->addDays(3)->toDateString(),
            'purpose' => 'Thực hành môn học',
        ], $override);
    }

    public function test_khach_phai_dang_nhap_moi_muon_duoc(): void
    {
        $this->get('/borrow/create')->assertRedirect('/login');
        $this->post('/borrow', [])->assertRedirect('/login');
    }

    public function test_luong_muon_tra_khep_kin(): void
    {
        $device = $this->device();

        // 1. User gửi yêu cầu
        $this->actingAs($this->user)->post('/borrow', $this->borrowPayload($device))
            ->assertRedirect(route('borrow.history'));
        $tx = Transaction::firstOrFail();
        $this->assertSame('pending', $tx->status);
        $this->assertSame('available', $device->fresh()->status);

        // 2. Admin duyệt → thiết bị "Đang mượn"
        $this->actingAs($this->admin)->patch("/admin/transactions/{$tx->id}/approve")->assertSessionHas('success');
        $this->assertSame('approved', $tx->fresh()->status);
        $this->assertSame('borrowed', $device->fresh()->status);

        // 3. Admin xác nhận trả → thiết bị "Sẵn sàng"
        $this->actingAs($this->admin)->patch("/admin/transactions/{$tx->id}/return")->assertSessionHas('success');
        $this->assertSame('returned', $tx->fresh()->status);
        $this->assertNotNull($tx->fresh()->returned_at);
        $this->assertSame('available', $device->fresh()->status);
    }

    public function test_chan_ngay_qua_khu_va_ngay_tra_nho_hon_ngay_muon(): void
    {
        $device = $this->device();

        $this->actingAs($this->user)->post('/borrow', $this->borrowPayload($device, [
            'borrow_date' => today()->subDay()->toDateString(),
        ]))->assertSessionHasErrors(['borrow_date' => 'Ngày mượn không được ở trong quá khứ.']);

        $this->actingAs($this->user)->post('/borrow', $this->borrowPayload($device, [
            'borrow_date' => today()->addDays(3)->toDateString(),
            'return_date' => today()->addDay()->toDateString(),
        ]))->assertSessionHasErrors(['return_date' => 'Ngày trả phải lớn hơn hoặc bằng ngày mượn.']);

        $this->actingAs($this->user)->post('/borrow', $this->borrowPayload($device, [
            'return_date' => today()->addDays(30)->toDateString(),
        ]))->assertSessionHasErrors('return_date');

        $this->assertSame(0, Transaction::count());
    }

    public function test_chan_muon_thiet_bi_dang_ban(): void
    {
        $busy = $this->device(['status' => 'borrowed']);
        $maintenance = $this->device(['status' => 'maintenance']);

        foreach ([$busy, $maintenance] as $device) {
            $this->actingAs($this->user)->post('/borrow', $this->borrowPayload($device))
                ->assertSessionHasErrors('device_id');
        }
        $this->assertSame(0, Transaction::count());
    }

    public function test_chan_gui_trung_yeu_cau(): void
    {
        $device = $this->device();
        $this->actingAs($this->user)->post('/borrow', $this->borrowPayload($device));
        $this->actingAs($this->user)->post('/borrow', $this->borrowPayload($device))
            ->assertSessionHasErrors(['device_id' => 'Bạn đã có yêu cầu đang chờ duyệt cho thiết bị này.']);
        $this->assertSame(1, Transaction::count());
    }

    public function test_duyet_mot_phieu_tu_choi_cac_phieu_cho_khac_cung_thiet_bi(): void
    {
        $device = $this->device();
        $other = User::factory()->create();
        $tx1 = Transaction::factory()->create(['user_id' => $this->user->id, 'device_id' => $device->id]);
        $tx2 = Transaction::factory()->create(['user_id' => $other->id, 'device_id' => $device->id]);

        $this->actingAs($this->admin)->patch("/admin/transactions/{$tx1->id}/approve");

        $this->assertSame('approved', $tx1->fresh()->status);
        $this->assertSame('rejected', $tx2->fresh()->status);

        // Không thể duyệt lại phiếu đã bị từ chối
        $this->actingAs($this->admin)->patch("/admin/transactions/{$tx2->id}/approve")->assertSessionHas('error');
    }

    public function test_tu_choi_phieu_kem_ly_do(): void
    {
        $tx = Transaction::factory()->create(['user_id' => $this->user->id, 'device_id' => $this->device()->id]);

        $this->actingAs($this->admin)->patch("/admin/transactions/{$tx->id}/reject", ['admin_note' => 'Đã hết lượt'])
            ->assertSessionHas('success');
        $this->assertDatabaseHas('transactions', ['id' => $tx->id, 'status' => 'rejected', 'admin_note' => 'Đã hết lượt']);
    }

    public function test_lich_su_va_huy_yeu_cau(): void
    {
        $device = $this->device(['name' => 'Webcam Logitech C920']);
        $tx = Transaction::factory()->create(['user_id' => $this->user->id, 'device_id' => $device->id]);

        $this->actingAs($this->user)->get('/my-borrows')->assertOk()->assertSee('Webcam Logitech C920');

        $this->actingAs($this->user)->patch("/my-borrows/{$tx->id}/cancel")->assertSessionHas('success');
        $this->assertSame('cancelled', $tx->fresh()->status);

        // Không hủy được phiếu đã hủy / phiếu của người khác
        $this->actingAs($this->user)->patch("/my-borrows/{$tx->id}/cancel")->assertSessionHas('error');
        $otherTx = Transaction::factory()->create(['device_id' => $device->id]);
        $this->actingAs($this->user)->patch("/my-borrows/{$otherTx->id}/cancel")->assertForbidden();
    }

    public function test_lich_su_khong_hien_phieu_cua_nguoi_khac(): void
    {
        $other = User::factory()->create();
        Transaction::factory()->create(['user_id' => $other->id, 'device_id' => $this->device(['name' => 'Máy của người khác'])->id]);

        $this->actingAs($this->user)->get('/my-borrows')->assertOk()->assertDontSee('Máy của người khác');
    }

    public function test_admin_xem_lich_su_muon_cua_thiet_bi(): void
    {
        $device = $this->device();
        Transaction::factory()->create(['user_id' => $this->user->id, 'device_id' => $device->id]);

        $this->actingAs($this->admin)->get("/admin/devices/{$device->id}")
            ->assertOk()->assertSee($this->user->name);
    }

    public function test_cac_trang_admin_tai_khong_loi(): void
    {
        $this->category->delete();
        $this->seed(DatabaseSeeder::class);
        $device = Device::first();

        foreach ([
            '/admin/dashboard', '/admin/categories', '/admin/categories/create',
            '/admin/devices', '/admin/devices/create', "/admin/devices/{$device->id}",
            "/admin/devices/{$device->id}/edit", '/admin/devices/trash',
            '/admin/transactions', '/admin/transactions?status=pending', '/admin/transactions?overdue=1',
            '/admin/transactions?keyword=Laptop',
        ] as $uri) {
            $this->actingAs($this->admin)->get($uri)->assertOk();
        }

        foreach (['/', '/devices', '/devices?status=available&page=2', "/devices/{$device->id}", '/borrow/create', '/my-borrows'] as $uri) {
            $this->actingAs($this->user)->get($uri)->assertOk();
        }
    }
}
