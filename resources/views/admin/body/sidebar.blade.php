<div class="sidenav-menu">
  <!-- Brand Logo -->
  <a href="{{ route('admin.home') }}" class="logo">
    <span class="logo-light">
      <span class="logo-lg"><img src="{{ asset('admin/images/logo-sm.png') }}" alt="logo"></span>
      <span class="logo-sm"><img src="{{ asset('admin/images/logo-sm.png') }}" alt="small logo"></span>
    </span>
    <span class="logo-dark">
      <span class="logo-lg"><img src="{{ asset('admin/images/logo-dark.png') }}" alt="dark logo"></span>
      <span class="logo-sm"><img src="{{ asset('admin/images/logo-sm.png') }}" alt="small logo"></span>
    </span>
  </a>
  <!-- Sidebar Hover Menu Toggle Button -->
  <button class="button-sm-hover">
    {!! file_get_contents(public_path('admin/icon/circle-arrow-left.svg')) !!}
  </button>
  <!-- Full Sidebar Menu Close Button -->
  <button class="button-close-fullsidebar">
    <i class="ti ti-x align-middle"></i>
  </button>
  <div data-simplebar>
    <!--- Sidenav Menu -->
    <ul class="side-nav">
      <li class="side-nav-item">
        <a href="{{ route('admin.home') }}" class="side-nav-link {{ request()->routeIs('admin.home') ? 'active' : '' }}">
          <span class="menu-icon">
            {!! file_get_contents(public_path('admin/icon/dashboard.svg')) !!}
          </span>
          <span class="menu-text"> Tổng quan </span>
        </a>
      </li>
      <li class="side-nav-item">
        <a href="{{ route('admin.career') }}" class="side-nav-link {{ request()->routeIs('admin.career*') || request()->routeIs('admin.*career*') ? 'active' : '' }}">
          <span class="menu-icon">
            {!! file_get_contents(public_path('admin/icon/category.svg')) !!}
          </span>
          <span class="menu-text"> Ngành nghề </span>
        </a>
      </li>
      <li class="side-nav-item">
        <a href="{{ route('admin.job') }}" class="side-nav-link {{ request()->routeIs('admin.job*') || request()->routeIs('admin.edit.job*') ? 'active' : '' }}">
          <span class="menu-icon">
            {!! file_get_contents(public_path('admin/icon/briefcase-2.svg')) !!}
          </span>
          <span class="menu-text"> Việc làm </span>
          @php
            $pendingJobs = $adminPendingJobsCount ?? \App\Models\Job::where('status', env('STATUS_PENDING', 0))->count();
          @endphp
          @if ($pendingJobs > 0)
            <span class="badge rounded-pill bg-danger ms-auto fs-11 px-2 py-0.5 fw-semibold" title="{{ $pendingJobs }} việc làm chờ duyệt">{{ $pendingJobs }}</span>
          @endif
        </a>
      </li>
      <li class="side-nav-item">
        <a href="{{ route('admin.banner.index') }}" class="side-nav-link {{ request()->routeIs('admin.banner.*') ? 'active' : '' }}">
          <span class="menu-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
              <line x1="8" y1="21" x2="16" y2="21"></line>
              <line x1="12" y1="17" x2="12" y2="21"></line>
            </svg>
          </span>
          <span class="menu-text"> Banner quảng cáo </span>
        </a>
      </li>

      <li class="side-nav-item">
        <a href="{{ route('admin.user') }}" class="side-nav-link {{ request()->routeIs('admin.user*') || request()->routeIs('admin.edit.user*') ? 'active' : '' }}">
          <span class="menu-icon">
            {!! file_get_contents(public_path('admin/icon/users.svg')) !!}
          </span>
          <span class="menu-text"> Ứng viên </span>
        </a>
      </li>
      <li class="side-nav-item">
        <a href="{{ route('admin.employer') }}" class="side-nav-link {{ request()->routeIs('admin.employer*') || request()->routeIs('admin.edit.employer*') ? 'active' : '' }}">
          <span class="menu-icon">
            {!! file_get_contents(public_path('admin/icon/building.svg')) !!}
          </span>
          <span class="menu-text"> Nhà tuyển dụng </span>
          @php
            $pendingEmployers = $adminPendingEmployersCount ?? \App\Models\User::where('role', 'employer')->where('status', 0)->count();
          @endphp
          @if ($pendingEmployers > 0)
            <span class="badge rounded-pill bg-danger ms-auto fs-11 px-2 py-0.5 fw-semibold" title="{{ $pendingEmployers }} nhà tuyển dụng chờ duyệt">{{ $pendingEmployers }}</span>
          @endif
        </a>
      </li>
      <li class="side-nav-item">
        <a href="{{ route('admin.apply.job') }}" class="side-nav-link {{ request()->routeIs('admin.apply.job*') ? 'active' : '' }}">
          <span class="menu-icon">
            {!! file_get_contents(public_path('admin/icon/file-cv.svg')) !!}
          </span>
          <span class="menu-text"> Ứng tuyển </span>
          @php
            $pendingApplies = $adminPendingApplicationsCount ?? \App\Models\JobApply::where('status', env('STATUS_PENDING', 0))->count();
          @endphp
          @if ($pendingApplies > 0)
            <span class="badge rounded-pill bg-warning text-dark ms-auto fs-11 px-2 py-0.5 fw-semibold" title="{{ $pendingApplies }} hồ sơ chờ duyệt">{{ $pendingApplies }}</span>
          @endif
        </a>
      </li>
      <li class="side-nav-item">
        <a href="{{ route('admin.cv.index') }}" class="side-nav-link {{ request()->routeIs('admin.cv.*') ? 'active' : '' }}">
          <span class="menu-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
              <polyline points="14 2 14 8 20 8"></polyline>
              <line x1="16" y1="13" x2="8" y2="13"></line>
              <line x1="16" y1="17" x2="8" y2="17"></line>
              <polyline points="10 9 9 9 8 9"></polyline>
            </svg>
          </span>
          <span class="menu-text"> Quản lý CV </span>
        </a>
      </li>
      {{-- <li class="side-nav-item">
        <a data-bs-toggle="collapse" href="#sidebarHospital" aria-expanded="false" aria-controls="sidebarHospital"
          class="side-nav-link">
          <span class="menu-icon">
            {!! file_get_contents(public_path('admin/icon/dashboard.svg')) !!}
          </span>
          <span class="menu-text"> Hospital</span>
          <span class="menu-arrow"></span>
        </a>
        <div class="collapse" id="sidebarHospital">
          <ul class="sub-menu">
            <li class="side-nav-item">
              <a href="apps-hospital-doctors.html" class="side-nav-link">
                <span class="menu-text">Doctors</span>
              </a>
            </li>
          </ul>
        </div>
      </li> --}}
    </ul>
    <div class="clearfix"></div>
  </div>
</div>
