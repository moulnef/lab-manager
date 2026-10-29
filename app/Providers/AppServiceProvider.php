<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Phân trang dùng giao diện Bootstrap 5
        Paginator::useBootstrapFive();

        // Hiển thị ngày tháng tiếng Việt (vd: "2 ngày trước")
        Carbon::setLocale('vi');

        // Chống N+1 query: khi chạy local/test, mọi lần lazy loading quan hệ
        // sẽ báo lỗi ngay → buộc phải dùng eager loading with()/load().
        Model::preventLazyLoading(! $this->app->isProduction());
    }
}
