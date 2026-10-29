# Lab Manager – Hệ thống mượn thiết bị phòng Lab

Đồ án cuối môn **Lập trình mã nguồn mở** – xây dựng bằng **Laravel 13 + MySQL + Blade/Bootstrap 5**.

Sinh viên/giảng viên tra cứu thiết bị phòng thí nghiệm, gửi yêu cầu mượn trực tuyến và theo dõi lịch sử mượn. Quản trị viên quản lý danh mục, thiết bị, duyệt phiếu mượn và xác nhận trả.

| Thành viên | Vai trò chính |
|---|---|
| Loan | Team lead · CSDL (Migration, Model, Seeder) · CRUD Danh mục · Nghiệp vụ duyệt/trả (TransactionController) |
| Huy | Xác thực & RoleMiddleware · Layout Admin · Dashboard · CRUD Thiết bị (upload ảnh, xóa mềm, khôi phục) |
| Việt | Layout User · Component · Danh sách/Chi tiết thiết bị · Tìm kiếm + phân trang + AJAX · Form mượn & lịch sử |

---

## 1. Chức năng

**Khách (chưa đăng nhập)**
- Trang chủ, danh sách thiết bị theo danh mục, trang chi tiết `/devices/{id}`
- Tìm kiếm theo tên/mã + lọc danh mục, trạng thái – **cập nhật tức thì bằng AJAX**, phân trang **giữ nguyên từ khóa** (`?keyword=...&category_id=...&page=2`)
- Đăng ký, đăng nhập

**Người dùng (role = `user`)**
- Gửi yêu cầu mượn (kiểm tra: không chọn ngày quá khứ, ngày trả ≥ ngày mượn, tối đa 14 ngày, thiết bị phải đang *Sẵn sàng*, không gửi trùng)
- Trang *Lịch sử mượn của tôi*, hủy yêu cầu đang chờ duyệt

**Quản trị viên (role = `admin`, khu vực `/admin/*`)**
- Bảng điều khiển thống kê bằng `COUNT` + `GROUP BY` (theo trạng thái, theo danh mục, top thiết bị)
- CRUD **Danh mục** (Bảng 1) – chặn xóa danh mục đang có thiết bị
- CRUD **Thiết bị** (Bảng 2) – upload ảnh (Laravel Storage), **xóa mềm** (SoftDeletes), thùng rác & khôi phục, lịch sử mượn của từng thiết bị
- Phiếu mượn: lọc theo trạng thái/quá hạn, tìm kiếm, **Duyệt** (thiết bị → *Đang mượn*), **Từ chối**, **Xác nhận trả** (thiết bị → *Sẵn sàng*) – xử lý trong `DB::transaction`

Kỹ thuật Laravel áp dụng: Eloquent ORM (hasMany/belongsTo), Migration, Factory/Seeder, Form Request (`CategoryRequest`, `DeviceRequest`, `BorrowRequest`), Middleware phân quyền (`RoleMiddleware`), Blade Layout + Component (`x-flash-message`, `x-device-card`...), Resource Controller, Route Model Binding, Eager Loading chống N+1 (`Model::preventLazyLoading`), Storage, SoftDeletes, Feature Test.

---

## 2. Yêu cầu hệ thống

- PHP **8.3+** (bật extension `pdo_mysql`, `mbstring`, `fileinfo`, `gd`)
- Composer 2
- MySQL 8 / MariaDB 10.6+
- Windows: **Laragon** (khuyến nghị) · macOS: **MAMP** hoặc Laravel Herd

> Giao diện dùng Bootstrap 5 và Bootstrap Icons đặt sẵn trong `public/vendor` → chạy được **không cần Internet**, không cần `npm`.

---

## 3. Cài đặt & chạy dự án

### Bước 1 – Lấy mã nguồn
```bash
# Cách 1: từ GitHub
git clone <link-repo> lab-manager
# Cách 2: giải nén file lab-manager.zip
cd lab-manager
```

### Bước 2 – Cài thư viện PHP
```bash
composer install
```

### Bước 3 – Tạo file cấu hình `.env`
```bash
# Windows (PowerShell / CMD)
copy .env.example .env
# macOS / Linux
cp .env.example .env

php artisan key:generate
```

### Bước 4 – Tạo CSDL MySQL
Tạo database rỗng tên `lab_manager` (utf8mb4_unicode_ci) bằng HeidiSQL/phpMyAdmin, rồi kiểm tra `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306          # MAMP mặc định: 8889
DB_DATABASE=lab_manager
DB_USERNAME=root
DB_PASSWORD=          # MAMP mặc định: root
```

### Bước 5 – Tạo bảng + dữ liệu mẫu
```bash
php artisan migrate:fresh --seed
```
Kết quả: 8 người dùng, 6 danh mục, 143 thiết bị (mỗi mẫu từ 2–4 thiết bị vật lý có ảnh đầy đủ), 15 phiếu mượn.

> **Cách khác:** import file `database/database.sql` (đã có sẵn cấu trúc + dữ liệu) bằng HeidiSQL → *File › Run SQL file*, hoặc `mysql -u root -p < database/database.sql`.

### Bước 6 – Liên kết thư mục ảnh upload
```bash
php artisan storage:link
```

### Bước 7 – Chạy
```bash
php artisan serve
```
Mở **http://127.0.0.1:8000**. (Với Laragon có thể dùng luôn `http://lab-manager.test`.)

### Tài khoản demo
| Vai trò | Email | Mật khẩu |
|---|---|---|
| Quản trị viên | `admin@gmail.com` | `12345678` |
| Người dùng | `user@gmail.com` | `12345678` |
| Sinh viên mẫu | `sv1@gmail.com` … `sv6@gmail.com` | `12345678` |

---

## 4. Kiểm thử tự động
```bash
php artisan test
```
35 test (Feature + Unit) kiểm tra: phân quyền toàn bộ route `/admin/*`, CRUD 2 bảng, upload ảnh, xóa mềm/khôi phục, tìm kiếm + phân trang giữ từ khóa, AJAX, luồng mượn → duyệt → trả, các trường hợp chặn (ngày quá khứ, thiết bị đang bận, gửi trùng, hủy phiếu của người khác).
Test dùng SQLite in-memory nên không ảnh hưởng dữ liệu MySQL.

---

## 5. Cấu trúc thư mục chính
```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/DashboardController.php     # Thống kê COUNT + GROUP BY
│   │   ├── Admin/CategoryController.php      # CRUD danh mục
│   │   ├── Admin/DeviceController.php        # CRUD thiết bị, upload, xóa mềm, khôi phục
│   │   ├── Admin/TransactionController.php   # Duyệt / từ chối / xác nhận trả
│   │   ├── AuthController.php                # Đăng nhập, đăng ký, đăng xuất
│   │   ├── HomeController.php                # Trang chủ
│   │   ├── DeviceCatalogController.php       # Danh sách + chi tiết (User), AJAX
│   │   └── BorrowController.php              # Gửi yêu cầu, lịch sử, hủy
│   ├── Middleware/RoleMiddleware.php         # Phân quyền theo vai trò ('role:admin')
│   └── Requests/                             # CategoryRequest, DeviceRequest, BorrowRequest
├── Models/                                   # User, Category, Device (SoftDeletes), Transaction
└── Services/BorrowService.php                # Nghiệp vụ mượn–trả trong DB::transaction
database/
├── migrations/  factories/  seeders/
└── database.sql                              # File CSDL MySQL xuất sẵn
resources/views/
├── layouts/app.blade.php  layouts/admin.blade.php
├── components/  (flash-message, device-card, device-thumb, status-badge)
├── home/  devices/  borrow/  auth/  errors/
└── admin/ (dashboard, categories, devices, transactions)
docs/   erd.png · usecase.png                 # Sơ đồ ERD và UML Use Case
tests/Feature/LabManagerTest.php              # Bộ kiểm thử
```

## 6. Một số route tiêu biểu
| Method | URI | Mô tả | Quyền |
|---|---|---|---|
| GET | `/devices?keyword=&category_id=&status=` | Danh sách + tìm kiếm (AJAX) | Công khai |
| GET | `/devices/{id}` | Chi tiết thiết bị | Công khai |
| GET/POST | `/borrow/create`, `/borrow` | Gửi yêu cầu mượn | Đã đăng nhập |
| GET | `/my-borrows` | Lịch sử mượn của tôi | Đã đăng nhập |
| GET | `/admin/dashboard` | Thống kê | Admin |
| RESOURCE | `/admin/categories`, `/admin/devices` | CRUD 2 bảng | Admin |
| PATCH | `/admin/devices/{id}/restore` | Khôi phục thiết bị đã xóa mềm | Admin |
| PATCH | `/admin/transactions/{id}/approve` · `/reject` · `/return` | Duyệt / từ chối / trả | Admin |

## 7. Lỗi thường gặp
- **`SQLSTATE[HY000] [1049] Unknown database`** → chưa tạo database `lab_manager`.
- **`SQLSTATE[HY000] [2002]`** → MySQL chưa chạy (bật Laragon/MAMP) hoặc sai `DB_PORT`.
- **Ảnh upload không hiển thị** → chạy `php artisan storage:link`; kiểm tra `APP_URL` khớp địa chỉ đang mở.
- **Đã sửa `.env` mà không ăn** → `php artisan config:clear`.
