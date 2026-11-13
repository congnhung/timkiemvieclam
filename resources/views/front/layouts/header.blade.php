<header class="tw-header">
  <nav class="tw-navbar">
    <div class="container tw-nav-inner">
      <a class="tw-logo" href="{{ route('home') }}">
        <img src="{{ asset('assets/user/images/logo_web.jpg') }}" alt="TopWork" class="tw-logo-img">
      </a>

      <button class="tw-hamburger" type="button" aria-label="Menu">
        <span></span><span></span><span></span>
      </button>

      <div class="tw-nav-collapse" id="twNavCollapse">
        <ul class="tw-nav-links">
          <li><a href="{{ route('home') }}" class="tw-nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Trang chủ</a></li>
          <li><a href="{{ route('jobs') }}" class="tw-nav-link {{ request()->routeIs('jobs') ? 'active' : '' }}">Tìm việc</a></li>
          <li><a href="{{ route('cv.templates') }}" class="tw-nav-link tw-nav-cv {{ request()->routeIs('cv.*') ? 'active' : '' }}">Tạo CV</a></li>
          @if(Auth::check() && Auth::user()->role === 'user')
            <li><a href="{{ route('account.notification') }}" class="tw-nav-link {{ request()->routeIs('account.notification') ? 'active' : '' }}">Thông báo</a></li>
          @endif
          @if(Auth::check() && Auth::user()->role === 'employer')
            <li><a href="{{ route('account.notificationEmployer') }}" class="tw-nav-link {{ request()->routeIs('account.notificationEmployer') ? 'active' : '' }}">Thông báo</a></li>
          @endif
          @if(Auth::check())
            <li>
              <a href="{{ route('account.messages') }}" class="tw-nav-link position-relative {{ request()->routeIs('account.messages') ? 'active' : '' }}">
                Tin nhắn
                <span class="badge rounded-pill bg-danger chat-unread-badge" style="display: none; font-size: 10px; margin-left: 2px;"></span>
              </a>
            </li>
          @endif
        </ul>

        <div class="tw-nav-actions">
          @if(Auth::check())
            @php
              $userImg = Auth::user()->image ?? '';
              if (!empty($userImg)) {
                  if (str_starts_with($userImg, 'http://') || str_starts_with($userImg, 'https://')) {
                      $userAvatar = $userImg;
                  } elseif (file_exists(public_path('assets/user/profile_picture/thumb/' . $userImg))) {
                      $userAvatar = asset('assets/user/profile_picture/thumb/' . $userImg);
                  } elseif (file_exists(public_path('assets/user/profile_picture/' . $userImg))) {
                      $userAvatar = asset('assets/user/profile_picture/' . $userImg);
                  } elseif (file_exists(public_path($userImg))) {
                      $userAvatar = asset($userImg);
                  } else {
                      $userAvatar = asset('assets/user/images/avatar7.png');
                  }
              } else {
                  $userAvatar = asset('assets/user/images/avatar7.png');
              }
              $displayName = !empty(Auth::user()->fullname)
                  ? Auth::user()->fullname
                  : (!empty(Auth::user()->name)
                      ? Auth::user()->name
                      : (!empty(Auth::user()->company_name) ? Auth::user()->company_name : 'Tài khoản'));
            @endphp

            @if(Auth::user()->role === 'employer')
              <a href="{{ route('account.createJob') }}" class="tw-btn tw-btn-primary d-none d-sm-inline-flex align-items-center gap-1">
                <i class="fa fa-plus-circle"></i> Đăng tuyển
              </a>
            @endif

            <div class="dropdown tw-user-dropdown">
              <a href="javascript:void(0);" class="tw-user-badge d-flex align-items-center gap-2 text-decoration-none dropdown-toggle"
                data-bs-toggle="dropdown" aria-expanded="false">
                <img src="{{ $userAvatar }}" alt="{{ $displayName }}" class="rounded-circle border"
                  style="width: 32px; height: 32px; object-fit: cover; border-color: var(--c-brand) !important;">
                <span class="fw-semibold text-dark text-truncate d-none d-sm-inline-block" style="max-width: 140px; font-size: var(--text-sm);">
                  {{ $displayName }}
                </span>
              </a>
              <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" style="min-width: 200px; border-radius: 12px; font-size: var(--text-sm);">
                <li class="px-3 py-2 border-bottom">
                  <div class="fw-bold text-dark text-truncate">{{ $displayName }}</div>
                  <small class="text-muted text-truncate d-block">{{ Auth::user()->email }}</small>
                </li>
                <li>
                  <a class="dropdown-item py-2" href="{{ route('account.profile') }}">
                    <i class="fa fa-user me-2 text-muted"></i> Trang cá nhân
                  </a>
                </li>
                @if(Auth::user()->role === 'employer')
                  <li>
                    <a class="dropdown-item py-2" href="{{ route('account.myJobs') }}">
                      <i class="fa fa-briefcase me-2 text-muted"></i> Việc làm đã đăng
                    </a>
                  </li>
                  <li>
                    <a class="dropdown-item py-2" href="{{ route('account.createJob') }}">
                      <i class="fa fa-plus me-2 text-muted"></i> Đăng tin mới
                    </a>
                  </li>
                @elseif(Auth::user()->role === 'user')
                  <li>
                    <a class="dropdown-item py-2" href="{{ route('cv.myResumes') }}">
                      <i class="fa fa-file-text-o me-2 text-muted"></i> CV của tôi
                    </a>
                  </li>
                  <li>
                    <a class="dropdown-item py-2" href="{{ route('account.savedJobs') }}">
                      <i class="fa fa-heart-o me-2 text-muted"></i> Việc làm đã lưu
                    </a>
                  </li>
                @elseif(Auth::user()->role === 'admin')
                  <li>
                    <a class="dropdown-item py-2" href="{{ route('admin.home') }}">
                      <i class="fa fa-dashboard me-2 text-muted"></i> Trang quản trị
                    </a>
                  </li>
                @endif
                <li><hr class="dropdown-divider my-1"></li>
                <li>
                  <a class="dropdown-item py-2 text-danger" href="{{ route('account.logout') }}">
                    <i class="fa fa-sign-out me-2"></i> Đăng xuất
                  </a>
                </li>
              </ul>
            </div>
          @else
            <a href="{{ route('account.login') }}" class="tw-btn tw-btn-outline">Đăng nhập</a>
            <a href="{{ route('account.registration') }}" class="tw-btn tw-btn-primary">Đăng ký</a>
          @endif
        </div>
      </div>
    </div>
  </nav>
  <div class="tw-progress-bar-wrap">
    <div id="progressBar" class="tw-progress-bar"></div>
  </div>
</header>

<script>
document.querySelector('.tw-hamburger')?.addEventListener('click', function() {
  document.getElementById('twNavCollapse')?.classList.toggle('open');
});
</script>
