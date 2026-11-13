<header class="tw-header">
  <nav class="tw-navbar">
    <div class="container-fluid tw-nav-inner">
      
      {{-- Brand Logo (Vieclam24h purple style) --}}
      <a class="tw-logo" href="{{ route('home') }}">
        <img src="{{ asset('assets/user/images/logo_web.jpg') }}" alt="TopWork" class="tw-logo-img">
        <span class="tw-logo-text">TopWork<small>NHANH HƠN, DỄ DÀNG HƠN</small></span>
      </a>

      {{-- Mobile hamburger --}}
      <button class="tw-hamburger" type="button" aria-label="Menu">
        <span></span><span></span><span></span>
      </button>

      <div class="tw-nav-collapse" id="twNavCollapse">
        
        {{-- Navigation Links --}}
        <ul class="tw-nav-links">

          {{-- 1. Việc làm ▾ (Matches Image 2) --}}
          <li class="nav-item dropdown tw-nav-dropdown">
            <a href="javascript:void(0);" class="tw-nav-link dropdown-toggle {{ request()->routeIs('jobs*') ? 'active' : '' }}" 
               id="navDropdownJobs" data-bs-toggle="dropdown" aria-expanded="false">
              Việc làm <i class="fa fa-caret-down ms-1"></i>
            </a>
            <div class="dropdown-menu shadow-lg tw-dropdown-vieclam" aria-labelledby="navDropdownJobs">
              <a class="dropdown-item tw-dd-main-link" href="{{ route('jobs') }}">
                <i class="fa fa-search text-primary me-2"></i> <span>Tìm việc làm</span>
              </a>
              <div class="dropdown-divider my-2"></div>
              <div class="tw-dd-group-header">
                <span><i class="fa fa-briefcase text-primary me-2"></i> Quản lý việc làm</span>
                <i class="fa fa-chevron-down text-muted" style="font-size: 11px;"></i>
              </div>
              <ul class="list-unstyled mb-0 mt-1 tw-dd-sublist">
                <li>
                  <a class="dropdown-item tw-dd-subitem d-flex align-items-center justify-content-between" href="{{ route('jobs') }}">
                    <span>• Việc làm dành riêng cho bạn</span>
                    <span class="badge bg-danger rounded-pill" style="font-size: 9.5px; padding: 2px 6px;">Mới</span>
                  </a>
                </li>
                <li>
                  <a class="dropdown-item tw-dd-subitem" href="{{ route('account.myJobApplication') }}">
                    • Việc làm đã ứng tuyển
                  </a>
                </li>
                <li>
                  <a class="dropdown-item tw-dd-subitem" href="{{ route('account.savedJobs') }}">
                    • Việc làm đã lưu
                  </a>
                </li>
                <li>
                  <a class="dropdown-item tw-dd-subitem" href="{{ route('account.savedJobs') }}">
                    • Việc làm chờ ứng tuyển
                  </a>
                </li>
                <li>
                  <a class="dropdown-item tw-dd-subitem" href="{{ route('cv.myResumes') }}">
                    • Nhà tuyển dụng xem hồ sơ bạn
                  </a>
                </li>
              </ul>
            </div>
          </li>

          {{-- 2. Công cụ ▾ (Shows Tạo CV) --}}
          <li class="nav-item dropdown tw-nav-dropdown">
            <a href="javascript:void(0);" class="tw-nav-link dropdown-toggle {{ request()->routeIs('cv.*') ? 'active' : '' }}" 
               id="navDropdownTools" data-bs-toggle="dropdown" aria-expanded="false">
              Công cụ <i class="fa fa-caret-down ms-1"></i>
            </a>
            <div class="dropdown-menu shadow-lg tw-dropdown-congcu" aria-labelledby="navDropdownTools">
              <a class="dropdown-item py-2" href="{{ route('cv.templates') }}">
                <i class="fa fa-file-text-o text-primary me-2" style="width: 16px;"></i> Mẫu CV xin việc
              </a>
              <a class="dropdown-item py-2" href="{{ route('cv.builder') }}">
                <i class="fa fa-pencil-square-o text-success me-2" style="width: 16px;"></i> Tạo CV online
              </a>
              <a class="dropdown-item py-2" href="{{ route('cv.myResumes') }}">
                <i class="fa fa-folder-open-o text-warning me-2" style="width: 16px;"></i> Quản lý CV đã tạo
              </a>
              <div class="dropdown-divider my-1"></div>
              <a class="dropdown-item py-2" href="{{ route('jobs') }}">
                <i class="fa fa-calculator text-info me-2" style="width: 16px;"></i> Tính lương GROSS - NET
              </a>
            </div>
          </li>

          {{-- 3. Cẩm nang nghề nghiệp --}}
          <li>
            <a href="{{ route('jobs') }}" class="tw-nav-link">Cẩm nang nghề nghiệp</a>
          </li>

          {{-- 4. HR Nexus --}}
          <li>
            <a href="{{ route('jobs') }}" class="tw-nav-link">HR Nexus</a>
          </li>

          {{-- 5. Miền Bắc ▾ --}}
          <li class="nav-item dropdown d-none d-xl-block tw-nav-dropdown">
            <a href="javascript:void(0);" class="tw-nav-link dropdown-toggle" id="navDropdownLoc" data-bs-toggle="dropdown" aria-expanded="false">
              Miền Bắc <i class="fa fa-caret-down ms-1"></i>
            </a>
            <ul class="dropdown-menu shadow-sm" aria-labelledby="navDropdownLoc">
              <li><a class="dropdown-item py-1.5" href="{{ route('jobs') }}">Tất cả khu vực</a></li>
              <li><a class="dropdown-item py-1.5 active" href="{{ route('jobs') }}">Miền Bắc</a></li>
              <li><a class="dropdown-item py-1.5" href="{{ route('jobs') }}">Miền Trung</a></li>
              <li><a class="dropdown-item py-1.5" href="{{ route('jobs') }}">Miền Nam</a></li>
            </ul>
          </li>

        </ul>

        {{-- Right Side Actions --}}
        <div class="tw-nav-actions">
          
          {{-- Bell icon (Thông báo thành cái chuông) --}}
          @php
            $notifUrl = Auth::check() 
              ? (Auth::user()->role === 'employer' ? route('account.notificationEmployer') : route('account.notification')) 
              : route('account.login');
          @endphp
          <a href="{{ $notifUrl }}" class="tw-nav-bell position-relative" title="Thông báo">
            <i class="fa fa-bell-o"></i>
          </a>

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

            {{-- User Profile Pill (Matches Image 3) --}}
            <div class="dropdown tw-user-dropdown">
              <a href="javascript:void(0);" class="tw-user-pill dropdown-toggle"
                data-bs-toggle="dropdown" aria-expanded="false">
                <img src="{{ $userAvatar }}" alt="{{ $displayName }}" class="tw-user-pill-avatar">
                <span class="tw-user-pill-name">{{ $displayName }}</span>
                <i class="fa fa-caret-down tw-user-pill-caret"></i>
              </a>

              {{-- Dropdown Card (Matches Image 3) --}}
              <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 tw-dropdown-user-card">
                <li class="px-3 py-2.5 border-bottom">
                  <div class="fw-bold text-dark fs-6">{{ $displayName }}</div>
                  <div class="text-muted small text-truncate" style="font-size: 12px;">{{ Auth::user()->email }}</div>
                </li>
                <li>
                  <a class="dropdown-item py-2" href="{{ route('account.profile') }}">
                    <i class="fa fa-user me-2 text-muted" style="width: 16px;"></i> Trang cá nhân
                  </a>
                </li>
                {{-- Tin nhắn moved here into Image 3 dropdown as requested --}}
                <li>
                  <a class="dropdown-item py-2 d-flex align-items-center justify-content-between" href="{{ route('account.messages') }}">
                    <span><i class="fa fa-comments-o me-2 text-muted" style="width: 16px;"></i> Tin nhắn</span>
                    <span class="badge rounded-pill bg-danger chat-unread-badge" style="display: none; font-size: 10px;"></span>
                  </a>
                </li>
                @if(Auth::user()->role === 'user')
                  <li>
                    <a class="dropdown-item py-2" href="{{ route('cv.myResumes') }}">
                      <i class="fa fa-file-text-o me-2 text-muted" style="width: 16px;"></i> CV của tôi
                    </a>
                  </li>
                  <li>
                    <a class="dropdown-item py-2" href="{{ route('account.savedJobs') }}">
                      <i class="fa fa-heart-o me-2 text-muted" style="width: 16px;"></i> Việc làm đã lưu
                    </a>
                  </li>
                  <li>
                    <a class="dropdown-item py-2" href="{{ route('account.myJobApplication') }}">
                      <i class="fa fa-paper-plane-o me-2 text-muted" style="width: 16px;"></i> Việc làm đã ứng tuyển
                    </a>
                  </li>
                @elseif(Auth::user()->role === 'employer')
                  <li>
                    <a class="dropdown-item py-2" href="{{ route('account.myJobs') }}">
                      <i class="fa fa-briefcase me-2 text-muted" style="width: 16px;"></i> Việc làm đã đăng
                    </a>
                  </li>
                  <li>
                    <a class="dropdown-item py-2" href="{{ route('account.createJob') }}">
                      <i class="fa fa-plus me-2 text-muted" style="width: 16px;"></i> Đăng tin mới
                    </a>
                  </li>
                @elseif(Auth::user()->role === 'admin')
                  <li>
                    <a class="dropdown-item py-2" href="{{ route('admin.home') }}">
                      <i class="fa fa-dashboard me-2 text-muted" style="width: 16px;"></i> Trang quản trị
                    </a>
                  </li>
                @endif
                <li><hr class="dropdown-divider my-1"></li>
                <li>
                  <a class="dropdown-item py-2 text-danger fw-semibold" href="{{ route('account.logout') }}">
                    <i class="fa fa-sign-out me-2" style="width: 16px;"></i> Đăng xuất
                  </a>
                </li>
              </ul>
            </div>

          @else
            {{-- Guest buttons --}}
            <a href="{{ route('account.login') }}" class="tw-btn-guest-login">Đăng nhập</a>
            <a href="{{ route('account.registration') }}" class="tw-btn-guest-reg">Đăng ký</a>
          @endif

          {{-- Button DÀNH CHO Nhà Tuyển Dụng --}}
          <a href="{{ route('account.createJob') }}" class="tw-btn-ntd d-none d-sm-inline-flex">
            <i class="fa fa-briefcase"></i>
            <div class="tw-ntd-text">
              <small>DÀNH CHO</small>
              <span>Nhà Tuyển Dụng</span>
            </div>
          </a>

          {{-- Flag 🇻🇳 --}}
          <span class="tw-lang-pill" title="Tiếng Việt">
            <img src="https://flagcdn.com/w20/vn.png" alt="VN" class="tw-flag-img">
          </span>

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
