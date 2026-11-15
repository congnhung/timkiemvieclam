@extends('front.layouts.app')

@php
  $allTemplates = App\Http\Controllers\user\CvBuilderController::getTemplatesList();
  $templateKey = $resume->template ?? 'emerald_startup';
  $tpl = $allTemplates[$templateKey] ?? ($allTemplates['emerald_startup'] ?? [
    'layout' => 'sidebar_left',
    'primary_color' => '#00b14f',
    'accent_color' => '#4ade80',
    'font' => 'Lexend'
  ]);
  $layout = $tpl['layout'] ?? 'sidebar_left';
  $themeColor = $resume->theme_color ?? ($tpl['primary_color'] ?? '#00b14f');
  $accentColor = $tpl['accent_color'] ?? '#4ade80';
  $fontFamily = $tpl['font'] ?? 'Lexend';
  $avatarSrc = $resume->avatar 
      ? (Str::startsWith($resume->avatar, 'http') ? $resume->avatar : asset('assets/user/cv_avatars/' . $resume->avatar))
      : asset('assets/user/images/avatar_silhouette.svg');
@endphp

@section('main')
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700;800;900&family=Be+Vietnam+Pro:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Inter:wght@300;400;500;600;700;800&family=Montserrat:wght@400;500;600;700;800&family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">

  <style>
    .cv-sheet-wrapper {
      background: #475569;
      padding: 30px 0;
      display: flex;
      justify-content: center;
    }
    .cv-a4-sheet {
      width: 794px;
      min-height: 1123px;
      background: #fff;
      box-shadow: 0 10px 30px rgba(0,0,0,0.2);
      display: flex;
      position: relative;
      overflow: hidden;
      font-size: 13px;
      color: #1e293b;
      font-family: '{{ $fontFamily }}', 'Lexend', sans-serif !important;
      --cv-theme: {{ $themeColor }};
      --cv-sidebar-bg: {{ $themeColor }};
      --cv-accent: {{ $accentColor }};
    }
    .cva-item { margin-bottom: 12px; page-break-inside: avoid; }
    .cva-item-title { font-size: 13px; font-weight: 700; color: #0f172a; margin-bottom: 2px; }
    .cva-item-sub { font-size: 12px; color: var(--cv-theme); font-weight: 600; margin-bottom: 2px; }
    .cva-item-period { font-size: 11px; color: #94a3b8; margin-bottom: 4px; }
    .cva-item-desc { font-size: 11.5px; color: #334155; line-height: 1.6; white-space: pre-wrap; }

    /* Layout 1: sidebar_left */
    .cv-layout-sidebar_left { flex-direction: row; }
    .cv-layout-sidebar_left .cva-sidebar {
      width: 34%; background: var(--cv-sidebar-bg, #004C82);
      color: #fff; padding: 32px 18px; display: flex; flex-direction: column;
    }
    .cv-layout-sidebar_left .cva-avatar-wrap { text-align: center; margin-bottom: 22px; }
    .cv-layout-sidebar_left .cva-avatar-wrap img {
      width: 104px; height: 104px; border-radius: 50%;
      object-fit: cover; border: 4px solid rgba(255, 255, 255, 0.9);
      box-shadow: 0 4px 12px rgba(0,0,0,0.2); background: #fff;
    }
    .cv-layout-sidebar_left .cva-sb-title {
      font-size: 11.5px; font-weight: 800; text-transform: uppercase;
      letter-spacing: 1.2px; color: #fff;
      border-bottom: 1.5px solid rgba(255, 255, 255, 0.25);
      padding-bottom: 4px; margin: 18px 0 10px;
    }
    .cv-layout-sidebar_left .cva-contact-item {
      display: flex; align-items: center; gap: 8px; margin-bottom: 8px; font-size: 11.5px; color: #e2e8f0;
    }
    .cv-layout-sidebar_left .cva-contact-item i { color: var(--cv-accent, #60a5fa); width: 14px; text-align: center; }
    .cv-layout-sidebar_left .cva-main { width: 66%; padding: 32px 26px; background: #fff; display: flex; flex-direction: column; }
    .cv-layout-sidebar_left .cva-name { font-size: 23px; font-weight: 800; color: #0f172a; text-transform: uppercase; line-height: 1.2; margin-bottom: 5px; }
    .cv-layout-sidebar_left .cva-jobtitle { font-size: 11.5px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 20px; }
    .cv-layout-sidebar_left .cva-sec-title {
      font-size: 11.5px; font-weight: 800; text-transform: uppercase;
      letter-spacing: 1px; color: #0f172a; border-bottom: 2px solid var(--cv-theme);
      padding-bottom: 4px; margin: 18px 0 10px; display: flex; align-items: center; gap: 8px;
    }
    .cv-layout-sidebar_left .cva-sec-title::before { content: ''; display: inline-block; width: 7px; height: 7px; border-radius: 2px; background: var(--cv-theme); }

    /* Layout 2: header_banner */
    .cv-layout-header_banner { flex-direction: column; }
    .cva-banner-top {
      width: 100%; background: var(--cv-theme, #0d9488);
      color: #fff; padding: 26px 30px; display: flex; align-items: center; gap: 20px;
    }
    .cva-banner-avatar {
      width: 96px; height: 96px; border-radius: 50%;
      object-fit: cover; border: 3.5px solid #fff;
      box-shadow: 0 4px 12px rgba(0,0,0,0.2); background: #fff; flex-shrink: 0;
    }
    .cva-banner-name { font-size: 24px; font-weight: 800; color: #fff; text-transform: uppercase; margin-bottom: 4px; }
    .cva-banner-title { font-size: 12px; font-weight: 700; color: rgba(255,255,255,0.92); text-transform: uppercase; letter-spacing: 1.2px; margin-bottom: 10px; }
    .cva-b-pill {
      font-size: 11px; color: #fff; background: rgba(255,255,255,0.18);
      border: 1px solid rgba(255,255,255,0.25); padding: 3px 10px; border-radius: 20px;
      display: inline-flex; align-items: center; gap: 6px;
    }
    .cva-banner-body { width: 100%; padding: 26px 30px; display: grid; grid-template-columns: 35% 65%; gap: 24px; background: #fff; flex: 1; }
    .cva-banner-sec-title {
      font-size: 11.5px; font-weight: 800; text-transform: uppercase;
      letter-spacing: 1px; color: var(--cv-theme); border-bottom: 2px solid var(--cv-theme);
      padding-bottom: 4px; margin: 16px 0 10px;
    }

    /* Layout 3: header_split */
    .cv-layout-header_split { flex-direction: column; }
    .cva-split-top {
      width: 100%; padding: 28px 32px 20px; display: flex; justify-content: space-between;
      align-items: center; border-bottom: 3px solid var(--cv-theme); background: #fff;
    }
    .cva-split-name { font-size: 25px; font-weight: 800; color: var(--cv-theme); text-transform: uppercase; margin-bottom: 4px; }
    .cva-split-title { font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 12px; }
    .cva-split-avatar { width: 96px; height: 96px; border-radius: 12px; object-fit: cover; border: 3px solid var(--cv-theme); background: #fff; flex-shrink: 0; }
    .cva-split-body { width: 100%; padding: 24px 32px; display: grid; grid-template-columns: 60% 40%; gap: 24px; background: #fff; flex: 1; }
    .cva-split-sec-title {
      font-size: 11.5px; font-weight: 800; text-transform: uppercase;
      letter-spacing: 1px; color: #1e293b; border-bottom: 2px solid var(--cv-theme);
      padding-bottom: 4px; margin: 16px 0 10px;
    }

    /* Layout 4: single_column */
    .cv-layout-single_column { flex-direction: column; background: #fff; }
    .cva-single-top { width: 100%; padding: 32px 36px 16px; text-align: center; border-bottom: 2px solid #0f172a; }
    .cva-single-name { font-size: 26px; font-weight: 800; color: #0f172a; text-transform: uppercase; margin-bottom: 4px; letter-spacing: 1px; }
    .cva-single-title { font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 10px; }
    .cva-single-contact { display: flex; justify-content: center; flex-wrap: wrap; gap: 6px 14px; font-size: 11.5px; color: #334155; }
    .cva-single-body { width: 100%; padding: 22px 36px 40px; display: flex; flex-direction: column; gap: 14px; background: #fff; flex: 1; }
    .cva-single-sec-title {
      font-size: 12px; font-weight: 800; text-transform: uppercase;
      letter-spacing: 1px; color: #0f172a; border-bottom: 1.5px solid #cbd5e1;
      padding-bottom: 3px; margin: 10px 0 8px;
    }
  </style>

  <div class="cv-builder-toolbar py-2 bg-white border-bottom shadow-sm">
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
    <div class="cv-a4-sheet cv-layout-{{ $layout }}">

      @if($layout === 'header_banner')
        {{-- LAYOUT 2: HEADER BANNER --}}
        <div class="cva-banner-top">
          <img src="{{ $avatarSrc }}" alt="{{ $resume->fullname }}" class="cva-banner-avatar">
          <div>
            <div class="cva-banner-name">{{ $resume->fullname }}</div>
            <div class="cva-banner-title">{{ $resume->candidate_title }}</div>
            <div class="d-flex flex-wrap gap-2">
              <span class="cva-b-pill"><i class="fa fa-envelope"></i> {{ $resume->email }}</span>
              @if($resume->phone)<span class="cva-b-pill"><i class="fa fa-phone"></i> {{ $resume->phone }}</span>@endif
              @if($resume->birthday)<span class="cva-b-pill"><i class="fa fa-calendar"></i> {{ $resume->birthday }}</span>@endif
              @if($resume->address)<span class="cva-b-pill"><i class="fa fa-map-marker"></i> {{ $resume->address }}</span>@endif
            </div>
          </div>
        </div>
        <div class="cva-banner-body">
          <div>
            @if(!empty($resume->skills_data))
              <div class="cva-banner-sec-title">Kỹ năng</div>
              @foreach($resume->skills_data as $sk)
                @php $lvl = intval($sk['level'] ?? 80); @endphp
                <div style="margin-bottom: 8px;">
                  <div style="display:flex; justify-content:space-between; font-size:11.5px; margin-bottom:2px;">
                    <span>{{ $sk['name'] }}</span><span>{{ $lvl }}%</span>
                  </div>
                  <div style="height:4px; background:#e2e8f0; border-radius:2px;">
                    <div style="height:100%; width:{{ $lvl }}%; background:var(--cv-theme); border-radius:2px;"></div>
                  </div>
                </div>
              @endforeach
            @endif
            @if(!empty($resume->education_data))
              <div class="cva-banner-sec-title">Học vấn</div>
              @foreach($resume->education_data as $edu)
                <div class="cva-item">
                  <div class="cva-item-title">{{ $edu['school'] }}</div>
                  <div class="cva-item-sub">{{ $edu['major'] }}</div>
                  <div class="cva-item-period">{{ $edu['period'] }}</div>
                  <div class="cva-item-desc">{{ $edu['description'] }}</div>
                </div>
              @endforeach
            @endif
            @if(!empty($resume->certifications_data))
              <div class="cva-banner-sec-title">Chứng chỉ</div>
              @foreach($resume->certifications_data as $cr)
                <div class="cva-item">
                  <div style="font-weight:700;">{{ $cr['name'] }}</div>
                  <div style="font-size:11px; color:#64748b;">{{ $cr['organization'] }} · {{ $cr['year'] ?? '' }}</div>
                </div>
              @endforeach
            @endif
          </div>
          <div>
            @if(!empty($resume->experience_data))
              <div class="cva-banner-sec-title">Kinh nghiệm làm việc</div>
              @foreach($resume->experience_data as $exp)
                <div class="cva-item">
                  <div class="cva-item-title">{{ $exp['position'] }}</div>
                  <div class="cva-item-sub">{{ $exp['company'] }}</div>
                  <div class="cva-item-period"><i class="fa fa-clock-o me-1"></i>{{ $exp['period'] }}</div>
                  <div class="cva-item-desc">{{ $exp['description'] }}</div>
                </div>
              @endforeach
            @endif
            @if(!empty($resume->projects_data))
              <div class="cva-banner-sec-title">Hoạt động & Dự án</div>
              @foreach($resume->projects_data as $pr)
                <div style="font-size:11.5px; margin-bottom:4px;"><i class="fa fa-check-circle me-1" style="color:var(--cv-theme)"></i> {{ is_array($pr) ? ($pr['name'] ?? '') : $pr }}</div>
              @endforeach
            @endif
          </div>
        </div>

      @elseif($layout === 'header_split')
        {{-- LAYOUT 3: HEADER SPLIT --}}
        <div class="cva-split-top">
          <div>
            <div class="cva-split-name">{{ $resume->fullname }}</div>
            <div class="cva-split-title">{{ $resume->candidate_title }}</div>
            <div class="d-flex flex-wrap gap-3" style="font-size:11.5px; color:#475569;">
              <span><i class="fa fa-envelope" style="color:var(--cv-theme)"></i> {{ $resume->email }}</span>
              @if($resume->phone)<span><i class="fa fa-phone" style="color:var(--cv-theme)"></i> {{ $resume->phone }}</span>@endif
              @if($resume->birthday)<span><i class="fa fa-calendar" style="color:var(--cv-theme)"></i> {{ $resume->birthday }}</span>@endif
              @if($resume->address)<span><i class="fa fa-map-marker" style="color:var(--cv-theme)"></i> {{ $resume->address }}</span>@endif
            </div>
          </div>
          <img src="{{ $avatarSrc }}" alt="{{ $resume->fullname }}" class="cva-split-avatar">
        </div>
        <div class="cva-split-body">
          <div>
            @if(!empty($resume->experience_data))
              <div class="cva-split-sec-title">Kinh nghiệm làm việc</div>
              @foreach($resume->experience_data as $exp)
                <div class="cva-item">
                  <div class="cva-item-title">{{ $exp['position'] }}</div>
                  <div class="cva-item-sub">{{ $exp['company'] }}</div>
                  <div class="cva-item-period"><i class="fa fa-clock-o me-1"></i>{{ $exp['period'] }}</div>
                  <div class="cva-item-desc">{{ $exp['description'] }}</div>
                </div>
              @endforeach
            @endif
            @if(!empty($resume->projects_data))
              <div class="cva-split-sec-title">Dự án & Hoạt động</div>
              @foreach($resume->projects_data as $pr)
                <div style="font-size:11.5px; margin-bottom:4px;"><i class="fa fa-check-circle me-1" style="color:var(--cv-theme)"></i> {{ is_array($pr) ? ($pr['name'] ?? '') : $pr }}</div>
              @endforeach
            @endif
          </div>
          <div>
            @if(!empty($resume->education_data))
              <div class="cva-split-sec-title">Học vấn</div>
              @foreach($resume->education_data as $edu)
                <div class="cva-item">
                  <div class="cva-item-title">{{ $edu['school'] }}</div>
                  <div class="cva-item-sub">{{ $edu['major'] }}</div>
                  <div class="cva-item-period">{{ $edu['period'] }}</div>
                  <div class="cva-item-desc">{{ $edu['description'] }}</div>
                </div>
              @endforeach
            @endif
            @if(!empty($resume->skills_data))
              <div class="cva-split-sec-title">Kỹ năng</div>
              @foreach($resume->skills_data as $sk)
                @php $lvl = intval($sk['level'] ?? 80); @endphp
                <div style="margin-bottom: 8px;">
                  <div style="display:flex; justify-content:space-between; font-size:11.5px; margin-bottom:2px;">
                    <span>{{ $sk['name'] }}</span><span>{{ $lvl }}%</span>
                  </div>
                  <div style="height:4px; background:#e2e8f0; border-radius:2px;">
                    <div style="height:100%; width:{{ $lvl }}%; background:var(--cv-theme); border-radius:2px;"></div>
                  </div>
                </div>
              @endforeach
            @endif
            @if(!empty($resume->certifications_data))
              <div class="cva-split-sec-title">Chứng chỉ</div>
              @foreach($resume->certifications_data as $cr)
                <div class="cva-item">
                  <div style="font-weight:700;">{{ $cr['name'] }}</div>
                  <div style="font-size:11px; color:#64748b;">{{ $cr['organization'] }} · {{ $cr['year'] ?? '' }}</div>
                </div>
              @endforeach
            @endif
          </div>
        </div>

      @elseif($layout === 'single_column')
        {{-- LAYOUT 4: SINGLE COLUMN (ATS) --}}
        <div class="cva-single-top">
          <div class="cva-single-name">{{ $resume->fullname }}</div>
          <div class="cva-single-title">{{ $resume->candidate_title }}</div>
          <div class="cva-single-contact">
            <span><i class="fa fa-envelope me-1"></i>{{ $resume->email }}</span>
            @if($resume->phone)<span>•</span><span><i class="fa fa-phone me-1"></i>{{ $resume->phone }}</span>@endif
            @if($resume->birthday)<span>•</span><span><i class="fa fa-calendar me-1"></i>{{ $resume->birthday }}</span>@endif
            @if($resume->address)<span>•</span><span><i class="fa fa-map-marker me-1"></i>{{ $resume->address }}</span>@endif
          </div>
        </div>
        <div class="cva-single-body">
          @if(!empty($resume->experience_data))
            <div class="cva-single-sec-title">Kinh nghiệm làm việc</div>
            @foreach($resume->experience_data as $exp)
              <div class="cva-item">
                <div style="display:flex; justify-content:space-between; font-weight:700;">
                  <span>{{ $exp['position'] }}</span>
                  <span style="font-size:11px; color:#64748b;">{{ $exp['period'] }}</span>
                </div>
                <div style="color:var(--cv-theme); font-weight:600; margin-bottom:3px;">{{ $exp['company'] }}</div>
                <div class="cva-item-desc">{{ $exp['description'] }}</div>
              </div>
            @endforeach
          @endif
          @if(!empty($resume->education_data))
            <div class="cva-single-sec-title">Trình độ học vấn</div>
            @foreach($resume->education_data as $edu)
              <div class="cva-item">
                <div style="display:flex; justify-content:space-between; font-weight:700;">
                  <span>{{ $edu['school'] }}</span>
                  <span style="font-size:11px; color:#64748b;">{{ $edu['period'] }}</span>
                </div>
                <div style="color:var(--cv-theme); font-weight:600; margin-bottom:3px;">{{ $edu['major'] }}</div>
                <div class="cva-item-desc">{{ $edu['description'] }}</div>
              </div>
            @endforeach
          @endif
          @if(!empty($resume->skills_data))
            <div class="cva-single-sec-title">Kỹ năng chuyên môn</div>
            <div style="display:flex; flex-wrap:wrap; gap:6px;">
              @foreach($resume->skills_data as $sk)
                <span style="background:#f1f5f9; border:1px solid #e2e8f0; color:#1e293b; padding:3px 10px; border-radius:4px; font-size:11.5px; font-weight:600;">
                  {{ $sk['name'] }} ({{ $sk['level'] ?? 80 }}%)
                </span>
              @endforeach
            </div>
          @endif
          @if(!empty($resume->certifications_data))
            <div class="cva-single-sec-title">Chứng chỉ & Giải thưởng</div>
            @foreach($resume->certifications_data as $cr)
              <div class="cva-item">
                <div style="font-weight:700;">{{ $cr['name'] }}</div>
                <div style="font-size:11px; color:#64748b;">{{ $cr['organization'] }} · {{ $cr['year'] ?? '' }}</div>
              </div>
            @endforeach
          @endif
          @if(!empty($resume->projects_data))
            <div class="cva-single-sec-title">Dự án & Hoạt động</div>
            @foreach($resume->projects_data as $pr)
              <div style="font-size:11.5px; margin-bottom:4px;"><i class="fa fa-check-circle me-1" style="color:var(--cv-theme)"></i> {{ is_array($pr) ? ($pr['name'] ?? '') : $pr }}</div>
            @endforeach
          @endif
        </div>

      @else
        {{-- DEFAULT: LAYOUT 1 SIDEBAR LEFT --}}
        <div class="cva-sidebar">
          <div class="cva-avatar-wrap">
            <img src="{{ $avatarSrc }}" alt="{{ $resume->fullname }}">
          </div>
          <div class="cva-sb-title">LIÊN HỆ</div>
          <div class="cva-contact-item"><i class="fa fa-envelope"></i><span>{{ $resume->email }}</span></div>
          @if($resume->phone)<div class="cva-contact-item"><i class="fa fa-phone"></i><span>{{ $resume->phone }}</span></div>@endif
          @if($resume->birthday)<div class="cva-contact-item"><i class="fa fa-calendar"></i><span>{{ $resume->birthday }}</span></div>@endif
          @if($resume->address)<div class="cva-contact-item"><i class="fa fa-map-marker"></i><span>{{ $resume->address }}</span></div>@endif

          @if(!empty($resume->skills_data))
            <div class="cva-sb-title">KỸ NĂNG</div>
            @foreach($resume->skills_data as $sk)
              @php $lvl = intval($sk['level'] ?? 80); @endphp
              <div style="margin-bottom: 8px;">
                <div style="display:flex; justify-content:space-between; font-size:11.5px; color:#e2e8f0; margin-bottom:2px;">
                  <span>{{ $sk['name'] }}</span><span>{{ $lvl }}%</span>
                </div>
                <div style="height:4px; background:rgba(255,255,255,0.2); border-radius:2px;">
                  <div style="height:100%; width:{{ $lvl }}%; background:var(--cv-accent, #38bdf8); border-radius:2px;"></div>
                </div>
              </div>
            @endforeach
          @endif

          @if(!empty($resume->certifications_data))
            <div class="cva-sb-title">CHỨNG CHỈ</div>
            @foreach($resume->certifications_data as $cr)
              <div style="margin-bottom:8px;">
                <div style="font-size:12px; font-weight:700; color:#fff;">{{ $cr['name'] }}</div>
                <div style="font-size:11px; color:#cbd5e1;">{{ $cr['organization'] }} · {{ $cr['year'] ?? '' }}</div>
              </div>
            @endforeach
          @endif
        </div>

        <div class="cva-main">
          <div class="cva-name">{{ $resume->fullname }}</div>
          <div class="cva-jobtitle">{{ $resume->candidate_title }}</div>

          @if(!empty($resume->experience_data))
            <div class="cva-sec-title">KINH NGHIỆM LÀM VIỆC</div>
            @foreach($resume->experience_data as $exp)
              <div class="cva-item">
                <div class="cva-item-title">{{ $exp['position'] }}</div>
                <div class="cva-item-sub">{{ $exp['company'] }}</div>
                <div class="cva-item-period"><i class="fa fa-clock-o me-1"></i>{{ $exp['period'] }}</div>
                <div class="cva-item-desc">{{ $exp['description'] }}</div>
              </div>
            @endforeach
          @endif

          @if(!empty($resume->education_data))
            <div class="cva-sec-title">HỌC VẤN</div>
            @foreach($resume->education_data as $edu)
              <div class="cva-item">
                <div class="cva-item-title">{{ $edu['school'] }}</div>
                <div class="cva-item-sub">{{ $edu['major'] }}</div>
                <div class="cva-item-period"><i class="fa fa-calendar me-1"></i>{{ $edu['period'] }}</div>
                <div class="cva-item-desc">{{ $edu['description'] }}</div>
              </div>
            @endforeach
          @endif

          @if(!empty($resume->projects_data))
            <div class="cva-sec-title">HOẠT ĐỘNG / DỰ ÁN</div>
            @foreach($resume->projects_data as $pr)
              <div style="font-size:11.5px; margin-bottom:4px;"><i class="fa fa-check-circle me-1" style="color:var(--cv-theme)"></i> {{ is_array($pr) ? ($pr['name'] ?? '') : $pr }}</div>
            @endforeach
          @endif
        </div>
      @endif

    </div>
  </div>
@endsection

