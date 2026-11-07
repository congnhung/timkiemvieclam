<div class="tw-sidebar-card p-4 text-center">
  @if (Auth::user()->image != '')
    <img src="{{ asset('assets/user/profile_picture/thumb/'.Auth::user()->image) }}" alt="avatar" class="rounded-circle mb-3 border" style="width: 100px; height: 100px; object-fit: cover;">
  @else
    <img src="{{ asset('assets/user/images/avatar7.png') }}" alt="avatar" class="rounded-circle mb-3 border" style="width: 100px; height: 100px; object-fit: cover;">
  @endif
  
  <h5 class="fw-bold mb-1" style="color: var(--c-text); font-size: var(--text-md);">{{ Auth::user()->fullname }}</h5>
  <p class="text-muted mb-3" style="font-size: var(--text-sm);">{{ Auth::user()->designation ?? (Auth::user()->role === 'employer' ? 'Nhà tuyển dụng' : 'Ứng viên') }}</p>

  @if (Auth::check() && Auth::user()->role === 'user')
    <button data-bs-toggle="modal" data-bs-target="#exampleModal" type="button" class="tw-btn tw-btn-outline w-100" style="font-size: var(--text-xs); padding: 5px 12px;">
      Đổi ảnh đại diện
    </button>
  @endif
  @if (Auth::check() && Auth::user()->role === 'employer')
    <button data-bs-toggle="modal" data-bs-target="#exampleModal" type="button" class="tw-btn tw-btn-outline w-100" style="font-size: var(--text-xs); padding: 5px 12px;">
      Đổi logo công ty
    </button>
  @endif
</div>

<div class="tw-sidebar-card">
  <ul class="tw-sidebar-nav">
    <li>
      <a href="{{ route("account.profile") }}" class="{{ request()->routeIs('account.profile') ? 'active' : '' }}">
        Thông tin tài khoản
      </a>
    </li>
    <li>
      <a href="{{ route("account.messages") }}" class="{{ request()->routeIs('account.messages') ? 'active' : '' }}">
        Tin nhắn trao đổi
      </a>
    </li>
    @if (Auth::check() && Auth::user()->role === 'employer')
      <li>
        <a href="{{ route("account.createJob") }}" class="{{ request()->routeIs('account.createJob') ? 'active' : '' }}">
          Đăng bài tuyển dụng
        </a>
      </li>
      <li>
        <a href="{{ route("account.myJobs") }}" class="{{ request()->routeIs('account.myJobs') ? 'active' : '' }}">
          Công việc đã đăng
        </a>
      </li>
    @endif
    @if (Auth::check() && Auth::user()->role === 'user')
      <li>
        <a href="{{ route("cv.myResumes") }}" class="{{ request()->routeIs('cv.myResumes') ? 'active' : '' }}">
          CV đã tạo của tôi
        </a>
      </li>
      <li>
        <a href="{{ route("account.myJobApplication") }}" class="{{ request()->routeIs('account.myJobApplication') ? 'active' : '' }}">
          Công việc đã ứng tuyển
        </a>
      </li>
      <li>
        <a href="{{ route("account.savedJobs") }}" class="{{ request()->routeIs('account.savedJobs') ? 'active' : '' }}">
          Công việc yêu thích
        </a>
      </li>   
    @endif
    <li>
      <a href="{{ route('account.logout') }}" style="color: #ef4444;">
        Đăng xuất
      </a>
    </li>                                                         
  </ul>
</div>
