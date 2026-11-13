<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>{{ $resume->title ?? 'CV' }} — {{ $resume->fullname }}</title>
  <link rel="stylesheet" href="{{ asset('assets/user/css/font-awesome.css') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@400;500;600;700;800&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
  <style>
    :root { --t: {{ $resume->theme_color ?? '#0f2942' }}; }
    *, *::before, *::after { box-sizing: border-box; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }

    body {
      margin: 0; padding: 0;
      font-family: 'Be Vietnam Pro', 'Segoe UI', Roboto, sans-serif;
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
      background: var(--t); color: #fff; border: none;
      padding: 8px 18px; border-radius: 7px; font-weight: 700; font-size: 13px;
      cursor: pointer; display: inline-flex; align-items: center; gap: 6px;
    }
    .tbtn:hover { opacity: .88; }
    .tbtn-sec { background: #475569; }
    .tbtn-sec:hover { background: #334155; }

    /* ---- sheet wrapper ---- */
    .sheet-wrap { padding: 28px 0; display: flex; justify-content: center; }

    /* ---- A4 ---- */
    .cv {
      width: 210mm; min-height: 297mm;
      background: #fff;
      box-shadow: 0 8px 32px rgba(0,0,0,0.14);
      display: flex;
      overflow: hidden;
    }

    /* ---- SIDEBAR ---- */
    .cv-sb {
      width: 36%;
      background: var(--t);
      color: #fff;
      padding: 30px 20px;
    }
    .cv-sb .s-title {
      color: #fff;
      border-bottom: 2px solid rgba(255,255,255,0.3);
    }
    .cv-sb .contact {
      color: #f1f5f9;
    }
    .cv-sb .contact i {
      color: #38bdf8;
    }
    .cv-sb .skill-label {
      color: #f1f5f9;
    }
    .cv-sb .skill-bar {
      background: rgba(255,255,255,0.2);
    }
    .cv-sb .skill-fill {
      background: #38bdf8;
    }
    .cv-sb .cert-name {
      color: #fff;
    }
    .cv-sb .cert-sub {
      color: #cbd5e1;
    }

    /* avatar */
    .cv-avatar { text-align: center; margin-bottom: 22px; }
    .cv-avatar img {
      width: 112px; height: 112px; border-radius: 50%;
      object-fit: cover;
      border: 4px solid var(--t);
      box-shadow: 0 4px 14px rgba(0,0,0,0.10);
    }

    /* section title */
    .s-title {
      font-size: 10.5px; font-weight: 800; text-transform: uppercase;
      letter-spacing: 1px; color: var(--t);
      border-bottom: 2px solid var(--t);
      padding-bottom: 4px; margin: 18px 0 10px;
    }
    .s-title:first-of-type { margin-top: 0; }

    /* contact */
    .contact { display: flex; align-items: flex-start; gap: 8px; margin-bottom: 8px; font-size: 11.5px; line-height: 1.4; }
    .contact i { color: var(--t); width: 14px; text-align: center; margin-top: 1px; flex-shrink: 0; }

    /* skill */
    .skill { margin-bottom: 10px; }
    .skill-label { display: flex; justify-content: space-between; font-size: 11.5px; font-weight: 600; margin-bottom: 3px; }
    .skill-bar { height: 5px; background: #e2e8f0; border-radius: 3px; }
    .skill-fill { height: 100%; background: var(--t); border-radius: 3px; }

    /* cert */
    .cert { margin-bottom: 10px; }
    .cert-name { font-size: 11.5px; font-weight: 700; }
    .cert-sub { font-size: 11px; color: #64748b; }

    /* ---- MAIN ---- */
    .cv-main { width: 64%; padding: 30px 24px; }

    .cv-name {
      font-size: 22px; font-weight: 800; color: var(--t);
      text-transform: uppercase; line-height: 1.15;
      margin-bottom: 3px; letter-spacing: 0.3px;
    }
    .cv-pos {
      font-size: 11px; font-weight: 700; color: #64748b;
      text-transform: uppercase; letter-spacing: 1.2px;
      margin-bottom: 14px;
    }
    .cv-obj { font-size: 12px; color: #334155; line-height: 1.65; margin-bottom: 18px; }

    /* entry */
    .entry { margin-bottom: 14px; page-break-inside: avoid; }
    .entry-pos { font-size: 13px; font-weight: 700; color: #1e293b; margin-bottom: 1px; }
    .entry-org { font-size: 12px; font-weight: 600; color: var(--t); margin-bottom: 1px; }
    .entry-period { font-size: 11px; color: #94a3b8; margin-bottom: 5px; display: flex; align-items: center; gap: 5px; }
    .entry-desc { font-size: 11.5px; color: #334155; line-height: 1.6; white-space: pre-wrap; }

    /* proj role pill */
    .proj-role {
      display: inline-block; font-size: 10px; font-weight: 700;
      background: #f0fdf4; color: var(--t);
      border: 1px solid var(--t); border-radius: 20px;
      padding: 1px 8px; margin-bottom: 4px;
    }

    /* ---- PRINT ---- */
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
  <a href="/cv-builder/{{ $resume->id }}" class="tbtn tbtn-sec"><i class="fa fa-edit"></i> Chỉnh sửa</a>
  <a href="/account/my-cvs" class="tbtn tbtn-sec"><i class="fa fa-folder-open-o"></i> CV của tôi</a>
</div>

<!-- A4 sheet -->
<div class="sheet-wrap">
<div class="cv">

  <!-- SIDEBAR -->
  <div class="cv-sb">
    <div class="cv-avatar">
      @php
        $avatarSrc = $resume->avatar
          ? (Str::startsWith($resume->avatar, 'http')
              ? $resume->avatar
              : asset('assets/user/cv_avatars/' . $resume->avatar))
          : asset('assets/user/images/avatar_silhouette.svg');
      @endphp
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
      <div class="s-title">Chứng chỉ & Giải thưởng</div>
      @foreach($resume->certifications_data as $cr)
        <div class="cert">
          <div class="cert-name">{{ $cr['name'] }}</div>
          <div class="cert-sub">{{ $cr['organization'] }} · {{ $cr['year'] }}</div>
        </div>
      @endforeach
    @endif
  </div>

  <!-- MAIN -->
  <div class="cv-main">
    <div class="cv-name">{{ $resume->fullname }}</div>
    <div class="cv-pos">{{ $resume->candidate_title }}</div>

    @if($resume->objective)
      <div class="cv-obj">{{ $resume->objective }}</div>
    @endif

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
          <div class="entry-period"><i class="fa fa-clock-o"></i>{{ $edu['period'] }}</div>
          <div class="entry-desc">{{ $edu['description'] }}</div>
        </div>
      @endforeach
    @endif

    @if(!empty($resume->projects_data))
      <div class="s-title">Dự án nổi bật</div>
      @foreach($resume->projects_data as $pr)
        <div class="entry">
          <div class="entry-pos">{{ $pr['name'] }}</div>
          <span class="proj-role">{{ $pr['role'] }}</span>
          <span style="font-size:11px;color:#94a3b8;margin-left:6px">{{ $pr['period'] }}</span>
          <div class="entry-desc" style="margin-top:4px">{{ $pr['description'] }}</div>
        </div>
      @endforeach
    @endif
  </div>

</div><!-- /cv -->
</div><!-- /sheet-wrap -->

<script>
  // Tự động trigger print nếu có query param ?print=1
  if (new URLSearchParams(location.search).get('print') === '1') window.print();
</script>
</body>
</html>
