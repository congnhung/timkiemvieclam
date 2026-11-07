@extends('front.layouts.app')

@section('main')
  <div class="cv-builder-toolbar">
    <div class="container d-flex justify-content-between align-items-center">
      <div class="d-flex align-items-center gap-2">
        <a href="{{ route('cv.myResumes') }}" class="btn btn-outline-secondary btn-sm">
          <i class="fa fa-arrow-left me-1"></i> CV của tôi
        </a>
        <h5 class="mb-0 fw-bold text-dark">{{ $resume->title }}</h5>
      </div>
      <div class="d-flex gap-2">
        <a href="{{ route('cv.builder', ['id' => $resume->id]) }}" class="btn btn-outline-success btn-sm">
          <i class="fa fa-pencil me-1"></i> Chỉnh sửa CV
        </a>
        <a href="{{ route('cv.print', $resume->id) }}" target="_blank" class="btn btn-topcv-primary btn-sm">
          <i class="fa fa-download me-1"></i> Tải PDF / In
        </a>
      </div>
    </div>
  </div>

  <div class="cv-sheet-wrapper">
    <div class="cv-a4-sheet" style="--cv-theme: {{ $resume->theme_color ?? '#00b14f' }};">
      <!-- SIDEBAR -->
      <div class="cv-sidebar-col">
        <div class="cv-avatar-container">
          @php
            $avatarUrl = $resume->avatar 
                ? (Str::startsWith($resume->avatar, 'http') ? $resume->avatar : asset('assets/user/cv_avatars/' . $resume->avatar))
                : asset('assets/user/images/avatar_default.png');
          @endphp
          <img src="{{ $avatarUrl }}" alt="{{ $resume->fullname }}" class="cv-avatar-img">
        </div>

        <div class="mb-4">
          <div class="cv-section-heading"><span>Thông tin cá nhân</span></div>
          @if ($resume->email)
            <div class="cv-contact-row"><i class="fa fa-envelope"></i><span>{{ $resume->email }}</span></div>
          @endif
          @if ($resume->phone)
            <div class="cv-contact-row"><i class="fa fa-phone"></i><span>{{ $resume->phone }}</span></div>
          @endif
          @if ($resume->birthday)
            <div class="cv-contact-row"><i class="fa fa-calendar"></i><span>{{ $resume->birthday }}</span></div>
          @endif
          @if ($resume->gender)
            <div class="cv-contact-row"><i class="fa fa-user"></i><span>{{ $resume->gender }}</span></div>
          @endif
          @if ($resume->address)
            <div class="cv-contact-row"><i class="fa fa-map-marker"></i><span>{{ $resume->address }}</span></div>
          @endif
          @if ($resume->website)
            <div class="cv-contact-row"><i class="fa fa-globe"></i><span>{{ $resume->website }}</span></div>
          @endif
        </div>

        @if (!empty($resume->skills_data))
          <div class="mb-4">
            <div class="cv-section-heading"><span>Kỹ năng</span></div>
            @foreach ($resume->skills_data as $skill)
              <div class="cv-skill-item">
                <div class="cv-skill-header">
                  <span>{{ $skill['name'] }}</span>
                  <span class="small text-muted">{{ $skill['level'] ?? 80 }}%</span>
                </div>
                <div class="cv-skill-bar-bg">
                  <div class="cv-skill-bar-fill" style="width: {{ $skill['level'] ?? 80 }}%;"></div>
                </div>
              </div>
            @endforeach
          </div>
        @endif

        @if (!empty($resume->certifications_data))
          <div class="mb-3">
            <div class="cv-section-heading"><span>Chứng chỉ</span></div>
            @foreach ($resume->certifications_data as $cert)
              <div class="mb-2">
                <div class="cv-item-title">{{ $cert['name'] }}</div>
                <div class="small text-muted">{{ $cert['organization'] }} - {{ $cert['year'] }}</div>
              </div>
            @endforeach
          </div>
        @endif
      </div>

      <!-- MAIN CONTENT -->
      <div class="cv-main-col">
        <div class="mb-4">
          <div class="cv-candidate-name">{{ $resume->fullname }}</div>
          <div class="cv-candidate-title">{{ $resume->candidate_title }}</div>
        </div>

        @if (!empty($resume->objective))
          <div class="mb-4">
            <div class="cv-section-heading"><span>Mục tiêu nghề nghiệp</span></div>
            <div class="cv-item-desc">{{ $resume->objective }}</div>
          </div>
        @endif

        @if (!empty($resume->experience_data))
          <div class="mb-4">
            <div class="cv-section-heading"><span>Kinh nghiệm làm việc</span></div>
            @foreach ($resume->experience_data as $exp)
              <div class="mb-3">
                <div class="cv-item-title">{{ $exp['position'] }}</div>
                <div class="cv-item-sub">{{ $exp['company'] }}</div>
                <div class="cv-item-date">{{ $exp['period'] }}</div>
                <div class="cv-item-desc">{{ $exp['description'] }}</div>
              </div>
            @endforeach
          </div>
        @endif

        @if (!empty($resume->education_data))
          <div class="mb-4">
            <div class="cv-section-heading"><span>Trình độ học vấn</span></div>
            @foreach ($resume->education_data as $edu)
              <div class="mb-3">
                <div class="cv-item-title">{{ $edu['school'] }}</div>
                <div class="cv-item-sub">{{ $edu['major'] }}</div>
                <div class="cv-item-date">{{ $edu['period'] }}</div>
                <div class="cv-item-desc">{{ $edu['description'] }}</div>
              </div>
            @endforeach
          </div>
        @endif

        @if (!empty($resume->projects_data))
          <div class="mb-3">
            <div class="cv-section-heading"><span>Dự án nổi bật</span></div>
            @foreach ($resume->projects_data as $proj)
              <div class="mb-3">
                <div class="cv-item-title">{{ $proj['name'] }}</div>
                <div class="small text-muted mb-1">{{ $proj['role'] }} | {{ $proj['period'] }}</div>
                <div class="cv-item-desc">{{ $proj['description'] }}</div>
              </div>
            @endforeach
          </div>
        @endif
      </div>
    </div>
  </div>
@endsection

