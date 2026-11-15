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
    ? (Str::startsWith($resume->avatar, 'http')
        ? $resume->avatar
        : asset('assets/user/cv_avatars/' . $resume->avatar))
    : asset('assets/user/images/avatar_silhouette.svg');
@endphp
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>{{ $resume->title ?? 'CV' }} — {{ $resume->fullname }}</title>
  <link rel="stylesheet" href="{{ asset('assets/user/css/font-awesome.css') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700;800;900&family=Be+Vietnam+Pro:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Inter:wght@300;400;500;600;700;800&family=Montserrat:wght@400;500;600;700;800&family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
  <style>
    :root {
      --t: {{ $themeColor }};
      --cv-theme: {{ $themeColor }};
      --cv-accent: {{ $accentColor }};
    }
    *, *::before, *::after {
      box-sizing: border-box;
      -webkit-print-color-adjust: exact !important;
      print-color-adjust: exact !important;
    }

    body {
      margin: 0; padding: 0;
      font-family: '{{ $fontFamily }}', 'Lexend', -apple-system, BlinkMacSystemFont, sans-serif !important;
      background: #475569;
      color: #1e293b;
      font-size: 13px;
    }

    /* ---- toolbar (hidden on print) ---- */
    .toolbar {
      background: #1e293b; color: #fff;
      padding: 11px 20px;
      display: flex; align-items: center; gap: 10px;
      position: sticky; top: 0; z-index: 100;
      box-shadow: 0 3px 10px rgba(0,0,0,0.25);
    }
    .toolbar h6 { margin: 0; font-size: 14px; font-weight: 700; flex: 1; }
    .tbtn {
      background: var(--cv-theme); color: #fff; border: none;
      padding: 8px 18px; border-radius: 7px; font-weight: 700; font-size: 13px;
      cursor: pointer; display: inline-flex; align-items: center; gap: 6px; text-decoration: none;
    }
    .tbtn:hover { opacity: .88; color: #fff; }
    .tbtn-sec { background: #475569; }
    .tbtn-sec:hover { background: #334155; }

    /* ---- sheet wrapper ---- */
    .sheet-wrap { padding: 28px 0; display: flex; justify-content: center; }

    /* ---- A4 Paper ---- */
    .cv {
      width: 210mm; min-height: 297mm;
      background: #fff;
      box-shadow: 0 8px 32px rgba(0,0,0,0.14);
      display: flex;
      overflow: hidden;
      font-family: '{{ $fontFamily }}', 'Lexend', sans-serif !important;
      position: relative;
    }

    /* Common Entry */
    .entry { margin-bottom: 12px; page-break-inside: avoid; }
    .entry-pos { font-size: 13px; font-weight: 700; color: #1e293b; margin-bottom: 1px; }
    .entry-org { font-size: 12px; font-weight: 600; color: var(--cv-theme); margin-bottom: 1px; }
    .entry-period { font-size: 11px; color: #94a3b8; margin-bottom: 4px; display: flex; align-items: center; gap: 5px; }
    .entry-desc { font-size: 11.5px; color: #334155; line-height: 1.6; white-space: pre-wrap; }

    /* ============================================================
       LAYOUT 1: SIDEBAR LEFT
       ============================================================ */
    .cv-layout-sidebar_left { flex-direction: row; }
    .cv-sb {
      width: 35%;
      background: var(--cv-theme);
      color: #fff;
      padding: 30px 20px;
    }
    .cv-avatar { text-align: center; margin-bottom: 22px; }
    .cv-avatar img {
      width: 104px; height: 104px; border-radius: 50%;
      object-fit: cover; border: 4px solid rgba(255,255,255,0.9);
      box-shadow: 0 4px 14px rgba(0,0,0,0.15); background: #fff;
    }
    .cv-sb .s-title {
      font-size: 11px; font-weight: 800; text-transform: uppercase;
      letter-spacing: 1px; color: #fff; border-bottom: 1.5px solid rgba(255,255,255,0.25);
      padding-bottom: 4px; margin: 18px 0 10px;
    }
    .cv-sb .contact { display: flex; align-items: center; gap: 8px; margin-bottom: 8px; font-size: 11.5px; color: #f1f5f9; }
    .cv-sb .contact i { color: var(--cv-accent, #38bdf8); width: 14px; text-align: center; font-size: 11px; flex-shrink: 0; }
    .cv-sb .skill-label { display: flex; justify-content: space-between; font-size: 11.5px; margin-bottom: 2px; color: #f1f5f9; }
    .cv-sb .skill-bar { height: 4px; background: rgba(255,255,255,0.2); border-radius: 2px; margin-bottom: 8px; }
    .cv-sb .skill-fill { height: 100%; background: var(--cv-accent, #38bdf8); border-radius: 2px; }
    .cv-sb .cert-name { font-size: 12px; font-weight: 700; color: #fff; }
    .cv-sb .cert-sub { font-size: 11px; color: #cbd5e1; margin-bottom: 8px; }

    .cv-main { width: 65%; padding: 32px 26px; }
    .cv-name {
      font-size: 23px; font-weight: 800; color: #0f172a;
      text-transform: uppercase; line-height: 1.15; margin-bottom: 4px; letter-spacing: 0.5px;
    }
    .cv-pos {
      font-size: 11.5px; font-weight: 700; color: #64748b;
      text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 20px;
    }
    .s-title {
      font-size: 11.5px; font-weight: 800; text-transform: uppercase;
      letter-spacing: 1px; color: #0f172a; border-bottom: 2px solid var(--cv-theme);
      padding-bottom: 4px; margin: 18px 0 10px; display: flex; align-items: center; gap: 8px;
    }
    .s-title::before { content: ''; display: inline-block; width: 7px; height: 7px; border-radius: 2px; background: var(--cv-theme); }

    /* ============================================================
       LAYOUT 2: HEADER BANNER
       ============================================================ */
    .cv-layout-header_banner { flex-direction: column; }
    .cv-banner-top {
      width: 100%; background: var(--cv-theme); color: #fff;
      padding: 26px 30px; display: flex; align-items: center; gap: 20px;
    }
    .cv-banner-top img {
      width: 96px; height: 96px; border-radius: 50%; object-fit: cover;
      border: 3.5px solid #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.2); background: #fff; flex-shrink: 0;
    }
    .cv-banner-info { flex: 1; }
    .cv-banner-info .b-name { font-size: 24px; font-weight: 800; text-transform: uppercase; color: #fff; margin-bottom: 4px; letter-spacing: 0.5px; }
    .cv-banner-info .b-pos { font-size: 12px; font-weight: 700; text-transform: uppercase; color: rgba(255,255,255,0.92); letter-spacing: 1.2px; margin-bottom: 10px; }
    .cv-banner-contact { display: flex; flex-wrap: wrap; gap: 6px 12px; }
    .cv-banner-contact span {
      font-size: 11px; color: #fff; background: rgba(255,255,255,0.18);
      border: 1px solid rgba(255,255,255,0.25); padding: 3px 10px; border-radius: 20px;
      display: inline-flex; align-items: center; gap: 6px;
    }
    .cv-banner-body { width: 100%; padding: 26px 30px; display: grid; grid-template-columns: 35% 65%; gap: 24px; flex: 1; }

    /* ============================================================
       LAYOUT 3: HEADER SPLIT
       ============================================================ */
    .cv-layout-header_split { flex-direction: column; }
    .cv-split-top {
      width: 100%; padding: 28px 32px 20px; display: flex; justify-content: space-between;
      align-items: center; border-bottom: 3px solid var(--cv-theme); background: #fff;
    }
    .cv-split-top .s-name { font-size: 25px; font-weight: 800; color: var(--cv-theme); text-transform: uppercase; margin-bottom: 4px; }
    .cv-split-top .s-pos { font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 10px; }
    .cv-split-top .s-contact { display: flex; flex-wrap: wrap; gap: 8px 16px; font-size: 11.5px; color: #475569; }
    .cv-split-top img { width: 96px; height: 96px; border-radius: 12px; object-fit: cover; border: 3px solid var(--cv-theme); background: #fff; flex-shrink: 0; }
    .cv-split-body { width: 100%; padding: 24px 32px; display: grid; grid-template-columns: 60% 40%; gap: 24px; flex: 1; }

    /* ============================================================
       LAYOUT 4: SINGLE COLUMN (ATS)
       ============================================================ */
    .cv-layout-single_column { flex-direction: column; background: #fff; }
    .cv-single-top { width: 100%; padding: 32px 36px 16px; text-align: center; border-bottom: 2px solid #0f172a; }
    .cv-single-top .ats-name { font-size: 26px; font-weight: 800; color: #0f172a; text-transform: uppercase; margin-bottom: 4px; letter-spacing: 1px; }
    .cv-single-top .ats-pos { font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 10px; }
    .cv-single-top .ats-contact { display: flex; justify-content: center; flex-wrap: wrap; gap: 6px 14px; font-size: 11.5px; color: #334155; }
    .cv-single-body { width: 100%; padding: 22px 36px 40px; display: flex; flex-direction: column; gap: 14px; flex: 1; }
    .cv-single-body .ats-sec-title {
      font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: #0f172a;
      border-bottom: 1.5px solid #cbd5e1; padding-bottom: 3px; margin: 10px 0 8px;
    }

    /* ---- PRINT MEDIA ---- */
    @media print {
      .toolbar { display: none !important; }
      body { background: #fff; }
      .sheet-wrap { padding: 0; }
      .cv { box-shadow: none; width: 210mm; min-height: 297mm; }
      @page { size: A4 portrait; margin: 0; }
    }
  </style>
</head>
<body>

<!-- toolbar -->
<div class="toolbar">
  <h6><i class="fa fa-file-pdf-o me-2"></i>{{ $resume->fullname }} — {{ $resume->title ?? 'CV Chuyên Nghiệp' }}</h6>
  <button class="tbtn" onclick="window.print()"><i class="fa fa-print"></i> In / Lưu PDF</button>
  <a href="{{ route('cv.builder', ['id' => $resume->id]) }}" class="tbtn tbtn-sec"><i class="fa fa-edit"></i> Chỉnh sửa</a>
  <a href="{{ route('cv.myResumes') }}" class="tbtn tbtn-sec"><i class="fa fa-folder-open-o"></i> CV của tôi</a>
</div>

<!-- A4 sheet -->
<div class="sheet-wrap">
<div class="cv cv-layout-{{ $layout }}">

  @if($layout === 'header_banner')
    {{-- LAYOUT 2: HEADER BANNER --}}
    <div class="cv-banner-top">
      <img src="{{ $avatarSrc }}" alt="{{ $resume->fullname }}">
      <div class="cv-banner-info">
        <div class="b-name">{{ $resume->fullname }}</div>
        <div class="b-pos">{{ $resume->candidate_title }}</div>
        <div class="cv-banner-contact">
          <span><i class="fa fa-envelope"></i> {{ $resume->email }}</span>
          @if($resume->phone)<span><i class="fa fa-phone"></i> {{ $resume->phone }}</span>@endif
          @if($resume->birthday)<span><i class="fa fa-calendar"></i> {{ $resume->birthday }}</span>@endif
          @if($resume->address)<span><i class="fa fa-map-marker"></i> {{ $resume->address }}</span>@endif
        </div>
      </div>
    </div>
    <div class="cv-banner-body">
      <div>
        @if(!empty($resume->skills_data))
          <div class="s-title">Kỹ năng chuyên môn</div>
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
          <div class="s-title">Học vấn</div>
          @foreach($resume->education_data as $edu)
            <div class="entry">
              <div class="entry-pos">{{ $edu['school'] }}</div>
              <div class="entry-org">{{ $edu['major'] }}</div>
              <div class="entry-period"><i class="fa fa-calendar"></i>{{ $edu['period'] }}</div>
              <div class="entry-desc">{{ $edu['description'] }}</div>
            </div>
          @endforeach
        @endif
        @if(!empty($resume->certifications_data))
          <div class="s-title">Chứng chỉ</div>
          @foreach($resume->certifications_data as $cr)
            <div class="entry">
              <div style="font-weight:700;">{{ $cr['name'] }}</div>
              <div style="font-size:11px; color:#64748b;">{{ $cr['organization'] }} · {{ $cr['year'] ?? '' }}</div>
            </div>
          @endforeach
        @endif
      </div>
      <div>
        @if(!empty($resume->experience_data))
          <div class="s-title">Kinh nghiệm làm việc</div>
          @foreach($resume->experience_data as $exp)
            <div class="entry">
              <div class="entry-pos">{{ $exp['position'] }}</div>
              <div class="entry-org">{{ $exp['company'] }}</div>
              <div class="entry-period"><i class="fa fa-clock-o"></i>{{ $exp['period'] }}</div>
              <div class="entry-desc">{{ $exp['description'] }}</div>
            </div>
          @endforeach
        @endif
        @if(!empty($resume->projects_data))
          <div class="s-title">Hoạt động & Dự án</div>
          @foreach($resume->projects_data as $pr)
            <div style="font-size:11.5px; margin-bottom:4px;"><i class="fa fa-check-circle me-1" style="color:var(--cv-theme)"></i> {{ is_array($pr) ? ($pr['name'] ?? '') : $pr }}</div>
          @endforeach
        @endif
      </div>
    </div>

  @elseif($layout === 'header_split')
    {{-- LAYOUT 3: HEADER SPLIT --}}
    <div class="cv-split-top">
      <div>
        <div class="s-name">{{ $resume->fullname }}</div>
        <div class="s-pos">{{ $resume->candidate_title }}</div>
        <div class="s-contact">
          <span><i class="fa fa-envelope" style="color:var(--cv-theme)"></i> {{ $resume->email }}</span>
          @if($resume->phone)<span><i class="fa fa-phone" style="color:var(--cv-theme)"></i> {{ $resume->phone }}</span>@endif
          @if($resume->birthday)<span><i class="fa fa-calendar" style="color:var(--cv-theme)"></i> {{ $resume->birthday }}</span>@endif
          @if($resume->address)<span><i class="fa fa-map-marker" style="color:var(--cv-theme)"></i> {{ $resume->address }}</span>@endif
        </div>
      </div>
      <img src="{{ $avatarSrc }}" alt="{{ $resume->fullname }}">
    </div>
    <div class="cv-split-body">
      <div>
        @if(!empty($resume->experience_data))
          <div class="s-title">Kinh nghiệm làm việc</div>
          @foreach($resume->experience_data as $exp)
            <div class="entry">
              <div class="entry-pos">{{ $exp['position'] }}</div>
              <div class="entry-org">{{ $exp['company'] }}</div>
              <div class="entry-period"><i class="fa fa-clock-o"></i>{{ $exp['period'] }}</div>
              <div class="entry-desc">{{ $exp['description'] }}</div>
            </div>
          @endforeach
        @endif
        @if(!empty($resume->projects_data))
          <div class="s-title">Dự án & Hoạt động</div>
          @foreach($resume->projects_data as $pr)
            <div style="font-size:11.5px; margin-bottom:4px;"><i class="fa fa-check-circle me-1" style="color:var(--cv-theme)"></i> {{ is_array($pr) ? ($pr['name'] ?? '') : $pr }}</div>
          @endforeach
        @endif
      </div>
      <div>
        @if(!empty($resume->education_data))
          <div class="s-title">Học vấn</div>
          @foreach($resume->education_data as $edu)
            <div class="entry">
              <div class="entry-pos">{{ $edu['school'] }}</div>
              <div class="entry-org">{{ $edu['major'] }}</div>
              <div class="entry-period"><i class="fa fa-calendar"></i>{{ $edu['period'] }}</div>
              <div class="entry-desc">{{ $edu['description'] }}</div>
            </div>
          @endforeach
        @endif
        @if(!empty($resume->skills_data))
          <div class="s-title">Kỹ năng</div>
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
          <div class="s-title">Chứng chỉ</div>
          @foreach($resume->certifications_data as $cr)
            <div class="entry">
              <div style="font-weight:700;">{{ $cr['name'] }}</div>
              <div style="font-size:11px; color:#64748b;">{{ $cr['organization'] }} · {{ $cr['year'] ?? '' }}</div>
            </div>
          @endforeach
        @endif
      </div>
    </div>

  @elseif($layout === 'single_column')
    {{-- LAYOUT 4: SINGLE COLUMN (ATS) --}}
    <div class="cv-single-top">
      <div class="ats-name">{{ $resume->fullname }}</div>
      <div class="ats-pos">{{ $resume->candidate_title }}</div>
      <div class="ats-contact">
        <span><i class="fa fa-envelope me-1"></i>{{ $resume->email }}</span>
        @if($resume->phone)<span>•</span><span><i class="fa fa-phone me-1"></i>{{ $resume->phone }}</span>@endif
        @if($resume->birthday)<span>•</span><span><i class="fa fa-calendar me-1"></i>{{ $resume->birthday }}</span>@endif
        @if($resume->address)<span>•</span><span><i class="fa fa-map-marker me-1"></i>{{ $resume->address }}</span>@endif
      </div>
    </div>
    <div class="cv-single-body">
      @if(!empty($resume->experience_data))
        <div class="ats-sec-title">Kinh nghiệm làm việc</div>
        @foreach($resume->experience_data as $exp)
          <div class="entry">
            <div style="display:flex; justify-content:space-between; font-weight:700;">
              <span>{{ $exp['position'] }}</span>
              <span style="font-size:11px; color:#64748b;">{{ $exp['period'] }}</span>
            </div>
            <div style="color:var(--cv-theme); font-weight:600; margin-bottom:3px;">{{ $exp['company'] }}</div>
            <div class="entry-desc">{{ $exp['description'] }}</div>
          </div>
        @endforeach
      @endif
      @if(!empty($resume->education_data))
        <div class="ats-sec-title">Trình độ học vấn</div>
        @foreach($resume->education_data as $edu)
          <div class="entry">
            <div style="display:flex; justify-content:space-between; font-weight:700;">
              <span>{{ $edu['school'] }}</span>
              <span style="font-size:11px; color:#64748b;">{{ $edu['period'] }}</span>
            </div>
            <div style="color:var(--cv-theme); font-weight:600; margin-bottom:3px;">{{ $edu['major'] }}</div>
            <div class="entry-desc">{{ $edu['description'] }}</div>
          </div>
        @endforeach
      @endif
      @if(!empty($resume->skills_data))
        <div class="ats-sec-title">Kỹ năng chuyên môn</div>
        <div style="display:flex; flex-wrap:wrap; gap:6px;">
          @foreach($resume->skills_data as $sk)
            <span style="background:#f1f5f9; border:1px solid #e2e8f0; color:#1e293b; padding:3px 10px; border-radius:4px; font-size:11.5px; font-weight:600;">
              {{ $sk['name'] }} ({{ $sk['level'] ?? 80 }}%)
            </span>
          @endforeach
        </div>
      @endif
      @if(!empty($resume->certifications_data))
        <div class="ats-sec-title">Chứng chỉ & Giải thưởng</div>
        @foreach($resume->certifications_data as $cr)
          <div class="entry">
            <div style="font-weight:700;">{{ $cr['name'] }}</div>
            <div style="font-size:11px; color:#64748b;">{{ $cr['organization'] }} · {{ $cr['year'] ?? '' }}</div>
          </div>
        @endforeach
      @endif
      @if(!empty($resume->projects_data))
        <div class="ats-sec-title">Dự án & Hoạt động</div>
        @foreach($resume->projects_data as $pr)
          <div style="font-size:11.5px; margin-bottom:4px;"><i class="fa fa-check-circle me-1" style="color:var(--cv-theme)"></i> {{ is_array($pr) ? ($pr['name'] ?? '') : $pr }}</div>
        @endforeach
      @endif
    </div>

  @else
    {{-- DEFAULT: LAYOUT 1 SIDEBAR LEFT --}}
    <!-- SIDEBAR -->
    <div class="cv-sb">
      <div class="cv-avatar">
        <img src="{{ $avatarSrc }}" alt="{{ $resume->fullname }}">
      </div>

      <div class="s-title">Thông tin liên hệ</div>
      @if($resume->email)
        <div class="contact"><i class="fa fa-envelope"></i><span>{{ $resume->email }}</span></div>
      @endif
      @if($resume->phone)
        <div class="contact"><i class="fa fa-phone"></i><span>{{ $resume->phone }}</span></div>
      @endif
      @if($resume->birthday)
        <div class="contact"><i class="fa fa-calendar"></i><span>{{ $resume->birthday }}</span></div>
      @endif
      @if($resume->gender)
        <div class="contact"><i class="fa fa-user"></i><span>{{ $resume->gender }}</span></div>
      @endif
      @if($resume->address)
        <div class="contact"><i class="fa fa-map-marker"></i><span>{{ $resume->address }}</span></div>
      @endif
      @if($resume->website)
        <div class="contact"><i class="fa fa-globe"></i><span>{{ $resume->website }}</span></div>
      @endif

      @if(!empty($resume->skills_data))
        <div class="s-title">Kỹ năng</div>
        @foreach($resume->skills_data as $sk)
          @php $lvl = intval($sk['level'] ?? 80); @endphp
          <div class="skill">
            <div class="skill-label"><span>{{ $sk['name'] }}</span><span>{{ $lvl }}%</span></div>
            <div class="skill-bar"><div class="skill-fill" style="width:{{ $lvl }}%"></div></div>
          </div>
        @endforeach
      @endif

      @if(!empty($resume->certifications_data))
        <div class="s-title">Chứng chỉ</div>
        @foreach($resume->certifications_data as $cr)
          <div class="cert">
            <div class="cert-name">{{ $cr['name'] }}</div>
            <div class="cert-sub">{{ $cr['organization'] }} · {{ $cr['year'] ?? '' }}</div>
          </div>
        @endforeach
      @endif
    </div>

    <!-- MAIN -->
    <div class="cv-main">
      <div class="cv-name">{{ $resume->fullname }}</div>
      <div class="cv-pos">{{ $resume->candidate_title }}</div>

      @if(!empty($resume->experience_data))
        <div class="s-title">Kinh nghiệm làm việc</div>
        @foreach($resume->experience_data as $exp)
          <div class="entry">
            <div class="entry-pos">{{ $exp['position'] }}</div>
            <div class="entry-org">{{ $exp['company'] }}</div>
            <div class="entry-period"><i class="fa fa-clock-o"></i>{{ $exp['period'] }}</div>
            <div class="entry-desc">{{ $exp['description'] }}</div>
          </div>
        @endforeach
      @endif

      @if(!empty($resume->education_data))
        <div class="s-title">Trình độ học vấn</div>
        @foreach($resume->education_data as $edu)
          <div class="entry">
            <div class="entry-pos">{{ $edu['school'] }}</div>
            <div class="entry-org">{{ $edu['major'] }}</div>
            <div class="entry-period"><i class="fa fa-calendar"></i>{{ $edu['period'] }}</div>
            <div class="entry-desc">{{ $edu['description'] }}</div>
          </div>
        @endforeach
      @endif

      @if(!empty($resume->projects_data))
        <div class="s-title">Dự án & Hoạt động</div>
        @foreach($resume->projects_data as $pr)
          <div style="font-size:11.5px; margin-bottom:4px;"><i class="fa fa-check-circle me-1" style="color:var(--cv-theme)"></i> {{ is_array($pr) ? ($pr['name'] ?? '') : $pr }}</div>
        @endforeach
      @endif
    </div>
  @endif

</div><!-- /cv -->
</div><!-- /sheet-wrap -->

<script>
  // Tự động trigger print nếu có query param ?print=1
  if (new URLSearchParams(location.search).get('print') === '1') window.print();
</script>
</body>
</html>

<script>
  // Tự động trigger print nếu có query param ?print=1
  if (new URLSearchParams(location.search).get('print') === '1') window.print();
</script>
</body>
</html>
