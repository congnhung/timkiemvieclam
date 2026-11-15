<header class="app-topbar">
  <div class="page-container topbar-menu">
    <div class="d-flex align-items-center gap-2">

      <!-- Brand Logo -->
      <a href="index.html" class="logo">
        <span class="logo-light">
          <span class="logo-lg"><img src="assets/images/logo.png" alt="logo"></span>
          <span class="logo-sm"><img src="assets/images/logo-sm.png" alt="small logo"></span>
        </span>

        <span class="logo-dark">
          <span class="logo-lg"><img src="assets/images/logo-dark.png" alt="dark logo"></span>
          <span class="logo-sm"><img src="assets/images/logo-sm.png" alt="small logo"></span>
        </span>
      </a>

      <!-- Sidebar Menu Toggle Button -->
      <button class="sidenav-toggle-button px-2">
        <i class="ti ti-menu-deep fs-24"></i>
      </button>

      <!-- Horizontal Menu Toggle Button -->
      <button class="topnav-toggle-button px-2" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
        <i class="ti ti-menu-deep fs-22"></i>
      </button>

      <!-- Button Trigger Search Modal -->



    </div>

    <div class="d-flex align-items-center gap-2">

      <!-- Search for small devices -->
      <div class="topbar-item d-flex d-xl-none">
        <button class="topbar-link" data-bs-toggle="modal" data-bs-target="#searchModal" type="button">
          <i class="ti ti-search fs-22"></i>
        </button>
      </div>
      <!-- Notification Dropdown -->
      <div class="topbar-item">
        <div class="dropdown">
          @php
            $unreadNotis = auth()->check() ? auth()->user()->unreadNotifications : collect();
            $unreadCount = $unreadNotis->count();
            $headerPendingJobs = $adminPendingJobsCount ?? \App\Models\Job::where('status', env('STATUS_PENDING', 0))->count();
            $headerPendingEmployers = $adminPendingEmployersCount ?? \App\Models\User::where('role', 'employer')->where('status', 0)->count();
            $totalPending = $headerPendingJobs + $headerPendingEmployers;
            $bellCount = max($unreadCount, $totalPending);
          @endphp
          <button class="topbar-link dropdown-toggle drop-arrow-none position-relative" data-bs-toggle="dropdown"
            data-bs-offset="0,25" type="button" data-bs-auto-close="outside" aria-haspopup="false"
            aria-expanded="false" title="Thông báo công việc & tài khoản cần duyệt">
            <i class="ti ti-bell animate-ring fs-22"></i>
            {!! file_get_contents(public_path('admin/icon/bell.svg')) !!}
            @if ($bellCount > 0)
              <span class="alert-count position-absolute">{{ $bellCount }}</span>
            @endif
          </button>
          <div class="dropdown-menu p-0 dropdown-menu-end dropdown-menu-lg" style="min-width: 340px;">
            <div class="p-3 border-bottom border-dashed d-flex align-items-center justify-content-between">
              <h6 class="m-0 fs-16 fw-semibold">Thông báo hệ thống</h6>
              <div class="d-flex gap-1">
                @if ($headerPendingJobs > 0)
                  <span class="badge bg-danger-subtle text-danger fs-11 px-2 py-0.5 rounded-pill fw-semibold">{{ $headerPendingJobs }} việc làm</span>
                @endif
                @if ($headerPendingEmployers > 0)
                  <span class="badge bg-warning-subtle text-warning fs-11 px-2 py-0.5 rounded-pill fw-semibold">{{ $headerPendingEmployers }} NTD</span>
                @endif
              </div>
            </div>
            <div class="position-relative z-2 card shadow-none rounded-0 mb-0" style="max-height: 320px;" data-simplebar>
              @if ($unreadCount == 0 && $totalPending == 0)
                <div class="dropdown-item notification-item py-4 text-wrap text-center">
                  <iconify-icon icon="line-md:bell-twotone-alert-loop" class="fs-48 text-secondary"></iconify-icon>
                  <h6 class="fw-medium text-muted mt-2 mb-0">Không có thông báo mới</h6>
                  <p class="fs-12 text-muted mb-0">Mọi tin việc làm & tài khoản đều đã xử lý.</p>
                </div>
              @else
                @if ($unreadCount > 0)
                  @foreach ($unreadNotis as $notification)
                    <a class="text-muted dropdown-item notification-item py-2.5 px-3 border-bottom"
                      href="{{ route('admin.notifications.markAsRead', $notification->id) }}">
                      @if (($notification->data['type'] ?? '') === 'new_employer')
                        <span class="flex-grow-1">
                          <span class="badge bg-warning-subtle text-warning fs-10 px-1.5 py-0.5 me-1">NTD mới</span>
                          <b class="text-primary">{{ $notification->data['employer_name'] ?? 'Nhà tuyển dụng' }}</b>
                          vừa đăng ký tài khoản &bull; <span class="badge bg-danger-subtle text-danger fs-10">Cần duyệt</span>
                        </span>
                      @else
                        <span class="flex-grow-1">
                          <span class="badge bg-danger-subtle text-danger fs-10 px-1.5 py-0.5 me-1">Chờ duyệt</span>
                          <b class="text-primary">{{ $notification->data['employer_name'] ?? 'Nhà tuyển dụng' }}</b>
                          vừa thêm: <b>{{ $notification->data['job_title'] ?? 'Công việc mới' }}</b>.
                        </span>
                      @endif
                      <br />
                      <span class="fs-11 text-muted">{{ $notification->created_at->diffForHumans() }}</span>
                    </a>
                  @endforeach
                @else
                  @php
                    $pendingJobsList = \App\Models\Job::where('status', env('STATUS_PENDING', 0))->latest()->take(3)->get();
                    $pendingEmployersList = \App\Models\User::where('role', 'employer')->where('status', 0)->latest()->take(3)->get();
                  @endphp
                  @foreach ($pendingEmployersList as $pEmp)
                    <a class="text-muted dropdown-item notification-item py-2.5 px-3 border-bottom"
                      href="{{ route('admin.edit.employer', $pEmp->id) }}">
                      <span class="flex-grow-1">
                        <span class="badge bg-warning-subtle text-warning fs-10 px-1.5 py-0.5 me-1">NTD mới</span>
                        <b class="text-primary">{{ $pEmp->fullname }}</b> ({{ $pEmp->email }}) &bull; <span class="badge bg-danger-subtle text-danger fs-10">Chờ duyệt</span>
                      </span>
                      <br />
                      <span class="fs-11 text-muted">{{ \Carbon\Carbon::parse($pEmp->created_at)->diffForHumans() }}</span>
                    </a>
                  @endforeach
                  @foreach ($pendingJobsList as $pJob)
                    <a class="text-muted dropdown-item notification-item py-2.5 px-3 border-bottom"
                      href="{{ route('admin.edit.job', $pJob->id) }}">
                      <span class="flex-grow-1">
                        <span class="badge bg-danger-subtle text-danger fs-10 px-1.5 py-0.5 me-1">Chờ duyệt</span>
                        <b class="text-primary">{{ $pJob->company_name ?: ($pJob->user->fullname ?? 'Nhà tuyển dụng') }}</b>
                        đăng: <b>{{ $pJob->title }}</b>.
                      </span>
                      <br />
                      <span class="fs-11 text-muted">{{ \Carbon\Carbon::parse($pJob->created_at)->diffForHumans() }}</span>
                    </a>
                  @endforeach
                @endif
              @endif
            </div>
            @if ($headerPendingEmployers > 0)
              <a href="{{ route('admin.employer', ['status' => 0]) }}" class="dropdown-item text-center text-primary py-1.5 fw-semibold fs-12 border-top bg-light">
                Xem NTD chờ duyệt ({{ $headerPendingEmployers }}) &rarr;
              </a>
            @endif
            @if ($headerPendingJobs > 0)
              <a href="{{ route('admin.job', ['status' => 0]) }}" class="dropdown-item text-center text-primary py-1.5 fw-semibold fs-12 border-top bg-light">
                Xem việc làm chờ duyệt ({{ $headerPendingJobs }}) &rarr;
              </a>
            @endif
          </div>
        </div>
      </div>
      <!-- User Dropdown -->
      <div class="topbar-item nav-user">
        <div class="dropdown">
          <a class="topbar-link dropdown-toggle drop-arrow-none px-2" data-bs-toggle="dropdown" data-bs-offset="0,19"
            type="button" aria-haspopup="false" aria-expanded="false">
            <img
              src="{{ Auth::user()->image ? asset('admin/upload/profile/' . Auth::user()->image) : asset('admin/images/avatar-default.png') }}"
              width="32" class="rounded-circle me-lg-2 d-flex" alt="user-image">
            <span class="d-lg-flex flex-column gap-1 d-none">
              <h5 class="my-0">{{ Auth::user()->fullname }}</h5>
              <h6 class="my-0 fw-normal text-capitalize">{{ Auth::user()->role }}</h6>
            </span>
            <i class="ti ti-chevron-down d-none d-lg-block align-middle ms-2"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-end">
            <!-- item-->
            <a href="{{ route('admin.profile') }}" class="dropdown-item">
              <i class="ti ti-user-hexagon me-1 fs-17 align-middle"></i>
              <span class="align-middle">Tài khoản</span>
            </a>

            <!-- item-->
            {{-- <a href="javascript:void(0);" class="dropdown-item">
              <i class="ti ti-wallet me-1 fs-17 align-middle"></i>
              <span class="align-middle">Wallet : <span class="fw-semibold">$985.25</span></span>
            </a>

            <!-- item-->
            <a href="javascript:void(0);" class="dropdown-item">
              <i class="ti ti-settings me-1 fs-17 align-middle"></i>
              <span class="align-middle">Settings</span>
            </a>

            <!-- item-->
            <a href="javascript:void(0);" class="dropdown-item">
              <i class="ti ti-lifebuoy me-1 fs-17 align-middle"></i>
              <span class="align-middle">Support</span>
            </a>

            <div class="dropdown-divider"></div>

            <!-- item-->
            <a href="javascript:void(0);" class="dropdown-item">
              <i class="ti ti-lock-square-rounded me-1 fs-17 align-middle"></i>
              <span class="align-middle">Lock Screen</span>
            </a> --}}

            <!-- item-->
            <a href="{{ route('account.logout') }}" class="dropdown-item active fw-semibold text-danger">
              <i class="ti ti-logout me-1 fs-17 align-middle"></i>
              <span class="align-middle">Đăng xuất</span>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</header>
