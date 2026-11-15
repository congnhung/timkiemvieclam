<div class="tw-sidebar-card p-4 text-center">
  @if (Auth::user()->image != '')
    <img src="{{ asset('assets/user/profile_picture/thumb/'.Auth::user()->image) }}" alt="avatar" class="rounded-circle mb-3 border shadow-sm" style="width: 96px; height: 96px; object-fit: cover; border-color: #e2e8f0 !important;">
  @else
    <img src="{{ asset('assets/user/images/avatar7.png') }}" alt="avatar" class="rounded-circle mb-3 border shadow-sm" style="width: 96px; height: 96px; object-fit: cover; border-color: #e2e8f0 !important;">
  @endif
  
  <h5 class="fw-bold mb-1" style="color: #1e293b; font-size: 16px;">{{ Auth::user()->fullname }}</h5>
  <p class="text-muted mb-3" style="font-size: 13px; font-weight: 500;">{{ Auth::user()->designation ?? (Auth::user()->role === 'employer' ? 'Nhà tuyển dụng' : 'Ứng viên') }}</p>

  @if (Auth::check() && Auth::user()->role === 'user')
    <button data-bs-toggle="modal" data-bs-target="#exampleModal" type="button" class="tw-btn tw-btn-outline w-100">
      <i class="fa fa-camera me-1.5"></i> Đổi ảnh đại diện
    </button>
  @endif
  @if (Auth::check() && Auth::user()->role === 'employer')
    <button data-bs-toggle="modal" data-bs-target="#exampleModal" type="button" class="tw-btn tw-btn-outline w-100">
      <i class="fa fa-camera me-1.5"></i> Đổi logo công ty
    </button>
  @endif
</div>

<div class="tw-sidebar-card">
  <ul class="tw-sidebar-nav">
    <li>
      <a href="{{ route("account.profile") }}" class="{{ request()->routeIs('account.profile') ? 'active' : '' }}">
        <i class="fa fa-user-circle-o" style="width: 18px; text-align: center; color: #00b14f;"></i>
        <span>Thông tin tài khoản</span>
      </a>
    </li>
    <li>
      <a href="{{ route("account.messages") }}" class="{{ request()->routeIs('account.messages') ? 'active' : '' }}">
        <i class="fa fa-comments-o" style="width: 18px; text-align: center; color: #00b14f;"></i>
        <span>Tin nhắn trao đổi</span>
      </a>
    </li>
    @if (Auth::check() && Auth::user()->role === 'employer')
      <li>
        <a href="{{ route("account.createJob") }}" class="{{ request()->routeIs('account.createJob') ? 'active' : '' }}">
          <i class="fa fa-plus-circle" style="width: 18px; text-align: center; color: #00b14f;"></i>
          <span>Đăng bài tuyển dụng</span>
        </a>
      </li>
      <li>
        <a href="{{ route("account.myJobs") }}" class="{{ request()->routeIs('account.myJobs') ? 'active' : '' }}">
          <i class="fa fa-briefcase" style="width: 18px; text-align: center; color: #00b14f;"></i>
          <span>Công việc đã đăng</span>
        </a>
      </li>
    @endif
    @if (Auth::check() && Auth::user()->role === 'user')
      <li>
        <a href="{{ route("cv.myResumes") }}" class="{{ request()->routeIs('cv.myResumes') ? 'active' : '' }}">
          <i class="fa fa-file-text-o" style="width: 18px; text-align: center; color: #00b14f;"></i>
          <span>CV đã tạo của tôi</span>
        </a>
      </li>
      <li>
        <a href="{{ route("account.myJobApplication") }}" class="{{ request()->routeIs('account.myJobApplication') ? 'active' : '' }}">
          <i class="fa fa-paper-plane-o" style="width: 18px; text-align: center; color: #00b14f;"></i>
          <span>Công việc đã ứng tuyển</span>
        </a>
      </li>
      <li>
        <a href="{{ route("account.savedJobs") }}" class="{{ request()->routeIs('account.savedJobs') ? 'active' : '' }}">
          <i class="fa fa-heart-o" style="width: 18px; text-align: center; color: #00b14f;"></i>
          <span>Công việc yêu thích</span>
        </a>
      </li>   
    @endif
    <li>
      <a href="{{ route('account.logout') }}" style="color: #ef4444;">
        <i class="fa fa-sign-out" style="width: 18px; text-align: center; color: #ef4444;"></i>
        <span>Đăng xuất</span>
      </a>
    </li>                                                         
  </ul>
</div>
