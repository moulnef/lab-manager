<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Device;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DeviceSeeder extends Seeder
{
    /**
     * Danh sách mẫu thiết bị theo từng danh mục (tiền tố mã => [số lượng mỗi mẫu, danh sách tên]).
     * Mỗi dòng sản phẩm có từ 2–4 thiết bị vật lý với mã tài sản riêng.
     * Tổng cộng: 27 + 16 + 24 + 36 + 24 + 16 = 143 thiết bị.
     */
    private const MODELS = [
        'Laptop & Máy tính' => ['LT', 3, [
            'Laptop Dell Latitude 5420', 'Laptop HP ProBook 450 G9', 'Laptop Lenovo ThinkPad E14',
            'Laptop ASUS ExpertBook B1', 'MacBook Air M1', 'Mini PC Intel NUC 11',
            'Laptop Acer Aspire 7', 'Laptop Dell Vostro 3520', 'Laptop HP 240 G8',
        ]],
        'Máy chiếu & Trình chiếu' => ['MC', 2, [
            'Máy chiếu Epson EB-X51', 'Máy chiếu ViewSonic PA503S', 'Máy chiếu BenQ MX560',
            'Máy chiếu Sony VPL-DX221', 'Bút trình chiếu Logitech R400', 'Màn chiếu treo tường 100 inch',
            'Máy chiếu mini Xiaomi Wanbo T2', 'Bút laser Logitech Spotlight',
        ]],
        'Thiết bị mạng' => ['MG', 3, [
            'Router Cisco ISR 4321', 'Switch Cisco Catalyst 2960', 'Router MikroTik hEX S',
            'Access Point Ubiquiti UniFi 6 Lite', 'Switch TP-Link TL-SG108', 'Router Wi-Fi TP-Link Archer C6',
            'Bộ bấm mạng & Tester cáp', 'Firewall Fortinet FortiGate 40F',
        ]],
        'Vi điều khiển & IoT' => ['IO', 4, [
            'Arduino Uno R3', 'Raspberry Pi 4 Model B 4GB', 'Kit ESP32 DevKit V1',
            'Bộ kit cảm biến 37 trong 1', 'Arduino Mega 2560', 'Raspberry Pi Pico W',
            'Module LoRa SX1278', 'Kit STM32 Nucleo F401RE', 'Jetson Nano Developer Kit',
        ]],
        'Thiết bị đo lường' => ['DL', 3, [
            'Đồng hồ vạn năng Fluke 17B+', 'Máy hiện sóng Rigol DS1054Z', 'Nguồn DC Lodestar 30V-5A',
            'Máy phát xung UNI-T UTG932', 'Đồng hồ kẹp dòng Kyoritsu 2117R', 'Mỏ hàn Hakko FX-888D',
            'Máy đo LCR UNI-T UT612', 'Logic Analyzer Saleae 8 kênh',
        ]],
        'Âm thanh & Ghi hình' => ['AT', 2, [
            'Micro không dây Shure BLX24', 'Loa kéo JBL PartyBox 110', 'Webcam Logitech C920',
            'Máy quay Sony HDR-CX405', 'Micro thu âm Boya BY-M1', 'Tai nghe Sony WH-1000XM4',
            'Chân máy quay Benro T880', 'Loa hội nghị Jabra Speak 510',
        ]],
    ];

    public function run(): void
    {
        $this->ensureImagesAreCopied();

        $categories = Category::pluck('id', 'name');

        foreach (self::MODELS as $categoryName => [$prefix, $quantity, $names]) {
            foreach ($names as $i => $name) {
                // Tên file ảnh đã được chuẩn hóa theo slug
                $slug = Str::slug($name);
                $imageRelPath = 'devices/' . $slug . '.png';
                $image = file_exists(storage_path('app/public/' . $imageRelPath)) ? $imageRelPath : null;

                for ($u = 1; $u <= $quantity; $u++) {
                    $code = sprintf('LAB-%s-%03d-%02d', $prefix, $i + 1, $u);
                    $unitName = sprintf('%s #%02d', $name, $u);

                    Device::create([
                        'category_id' => $categories[$categoryName],
                        'code' => $code,
                        'name' => $unitName,
                        'description' => "Thiết bị {$name} (Mã tài sản: {$code}, Đơn vị {$u}/{$quantity}) thuộc nhóm {$categoryName}, đã kiểm tra hoạt động tốt, "
                            .'đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.',
                        'image' => $image,
                        // Một vài thiết bị bảo trì để minh họa tính năng lọc
                        'status' => ($i === 5 && $u === $quantity) ? Device::STATUS_MAINTENANCE : Device::STATUS_AVAILABLE,
                    ]);
                }
            }
        }
    }

    /**
     * Tự động sao chép ảnh từ thư mục gốc vào storage/app/public/devices nếu chưa có.
     */
    private function ensureImagesAreCopied(): void
    {
        $targetDir = storage_path('app/public/devices');
        if (! is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $sourceDirs = [
            base_path('../images/images'),
            base_path('../images'),
            base_path('images'),
        ];

        foreach ($sourceDirs as $sourceDir) {
            if (is_dir($sourceDir)) {
                $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($sourceDir));
                foreach ($iterator as $file) {
                    if ($file->isFile() && strtolower($file->getExtension()) === 'png') {
                        $baseName = pathinfo($file->getFilename(), PATHINFO_FILENAME);
                        $slug = Str::slug($baseName);
                        $destSlug = $targetDir . '/' . $slug . '.png';
                        $destOrig = $targetDir . '/' . $file->getFilename();

                        if (! file_exists($destSlug)) {
                            copy($file->getPathname(), $destSlug);
                        }
                        if (! file_exists($destOrig)) {
                            copy($file->getPathname(), $destOrig);
                        }
                    }
                }
                break;
            }
        }
    }
}

