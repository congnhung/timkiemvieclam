<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;
use App\Models\Job;
use App\Models\JobApply;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   */
  public function register(): void
  {
    //
  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void
  {
    // Thiết lập ngôn ngữ mặc định của Carbon là tiếng Việt
    Carbon::setLocale('vi');
    Paginator::useBootstrapFive();

    // Chia sẻ số lượng thông báo cần duyệt cho các trang admin
    View::composer(['admin.body.sidebar', 'admin.body.header', 'admin.*'], function ($view) {
      try {
        $pendingJobsCount = Job::where('status', env('STATUS_PENDING', 0))->count();
        $pendingApplicationsCount = JobApply::where('status', env('STATUS_PENDING', 0))->count();
        $pendingEmployersCount = \App\Models\User::where('role', 'employer')->where('status', 0)->count();
      } catch (\Throwable $e) {
        $pendingJobsCount = 0;
        $pendingApplicationsCount = 0;
        $pendingEmployersCount = 0;
      }

      $view->with([
        'adminPendingJobsCount' => $pendingJobsCount,
        'adminPendingApplicationsCount' => $pendingApplicationsCount,
        'adminPendingEmployersCount' => $pendingEmployersCount,
      ]);
    });
  }
}
