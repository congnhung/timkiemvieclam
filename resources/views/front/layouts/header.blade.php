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
          <li><a href="{{ route('cv.builder') }}" class="tw-nav-link tw-nav-cv {{ request()->routeIs('cv.*') ? 'active' : '' }}">Tạo CV</a></li>
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
            @if(Auth::user()->role === 'user')
              <a href="{{ route('account.profile') }}" class="tw-btn tw-btn-outline">Tài khoản</a>
            @elseif(Auth::user()->role === 'admin')
              <a href="{{ route('admin.home') }}" class="tw-btn tw-btn-outline">Quản trị</a>
            @elseif(Auth::user()->role === 'employer')
              <a href="{{ route('account.profile') }}" class="tw-btn tw-btn-outline">Tài khoản</a>
              <a href="{{ route('account.createJob') }}" class="tw-btn tw-btn-primary">Đăng tuyển</a>
            @endif
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
