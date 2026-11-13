@extends('front.layouts.app')

@php
  $currentTemplateKey = request()->get('template', $resume->template ?? ($defaultData['template'] ?? 'modern_navy'));
  $selectedTpl = $allTemplates[$currentTemplateKey] ?? ($allTemplates['modern_navy'] ?? [
    'name' => 'Hiện Đại (Navy)',
    'primary_color' => '#0f2942',
    'font' => 'Be Vietnam Pro',
    'layout' => 'sidebar_left'
  ]);

  $cd = $resume ? [
    'id'               => $resume->id,
    'title'            => $resume->title,
    'fullname'         => $resume->fullname,
    'candidate_title'  => $resume->candidate_title,
    'email'            => $resume->email,
    'phone'            => $resume->phone,
    'birthday'         => $resume->birthday,
    'gender'           => $resume->gender,
    'address'          => $resume->address,
    'website'          => $resume->website,
    'avatar'           => $resume->avatar
                           ? (Str::startsWith($resume->avatar, 'http')
                               ? $resume->avatar
                               : asset('assets/user/cv_avatars/' . $resume->avatar))
                           : $defaultData['avatar'],
    'theme_color'      => $resume->theme_color ?? ($selectedTpl['primary_color'] ?? '#0f2942'),
    'template'         => $resume->template ?? $currentTemplateKey,
    'font'             => $selectedTpl['font'] ?? 'Be Vietnam Pro',
    'objective'        => $resume->objective,
    'experience'       => !empty($resume->experience_data)     ? $resume->experience_data     : $defaultData['experience'],
    'education'        => !empty($resume->education_data)      ? $resume->education_data      : $defaultData['education'],
    'skills'           => !empty($resume->skills_data)         ? $resume->skills_data         : $defaultData['skills'],
    'projects'         => !empty($resume->projects_data)       ? $resume->projects_data       : $defaultData['projects'],
    'certifications'   => !empty($resume->certifications_data) ? $resume->certifications_data : $defaultData['certifications'],
  ] : array_merge($defaultData, [
    'id' => '',
    'template' => $currentTemplateKey,
    'theme_color' => $selectedTpl['primary_color'] ?? '#0f2942',
    'font' => $selectedTpl['font'] ?? 'Be Vietnam Pro'
  ]);
@endphp

@section('main')
<!-- Google Fonts for CV Templates -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@400;500;600;700;800&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

<style>
/* ============================================================
   CV BUILDER & EDITOR — VIEC LAM 24H / TOPCV ADVANCED
   ============================================================ */
body { background: #f8fafc; }

/* ---- Top bar ---- */
.cvb-topbar {
  position: sticky; top: 0; z-index: 1000;
  background: #fff;
  border-bottom: 1px solid #e2e8f0;
  padding: 10px 24px;
  display: flex; align-items: center; gap: 12px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
  flex-wrap: wrap;
}
.cvb-title-wrap {
  display: flex; align-items: center; gap: 8px;
}
.cv-title-input {
  font-weight: 700; border: none; background: #f1f5f9;
  border-radius: 8px; padding: 7px 14px;
  font-size: 14px; width: 220px;
  outline: none; border: 1.5px solid #e2e8f0;
  transition: all 0.15s;
}
.cv-title-input:focus { border-color: #00b14f; background: #fff; }

/* Dropdown control buttons */
.cvb-btn-control {
  background: #fff; border: 1px solid #e2e8f0; border-radius: 8px;
  padding: 6px 12px; font-size: 13px; font-weight: 600; color: #334155;
  display: inline-flex; align-items: center; gap: 6px; cursor: pointer;
  transition: all 0.15s;
}
.cvb-btn-control:hover { background: #f8fafc; border-color: #cbd5e1; color: #00b14f; }
.cvb-btn-control .color-indicator {
  width: 14px; height: 14px; border-radius: 50%;
  border: 1.5px solid #fff; box-shadow: 0 0 0 1px #cbd5e1;
}

/* Palette Dropdown */
.cvb-color-menu {
  display: grid; grid-template-columns: repeat(4, 1fr);
  gap: 8px; padding: 12px; min-width: 180px;
}
.cvb-color-swatch {
  width: 32px; height: 32px; border-radius: 50%;
  cursor: pointer; border: 2.5px solid transparent;
  transition: transform 0.15s, border-color 0.15s;
  display: flex; align-items: center; justify-content: center;
}
.cvb-color-swatch:hover, .cvb-color-swatch.active {
  transform: scale(1.15); border-color: #fff;
  outline: 2px solid currentColor;
}
.cvb-color-swatch.active::after {
  content: '✓'; color: #fff; font-size: 14px; font-weight: 900;
  text-shadow: 0 1px 2px rgba(0,0,0,0.5);
}

/* Top bar actions right */
.gap-auto { flex: 1; }
.btn-cv-save {
  background: #f1f5f9; color: #334155; border: 1px solid #cbd5e1;
  font-weight: 600; border-radius: 8px; padding: 7px 16px; font-size: 13px;
  display: inline-flex; align-items: center; gap: 6px;
  transition: all 0.15s;
}
.btn-cv-save:hover { background: #e2e8f0; color: #0f172a; }
.btn-cv-finish {
  background: #00b14f; color: #fff; border: 1px solid #009140;
  font-weight: 700; border-radius: 8px; padding: 7px 18px; font-size: 13.5px;
  display: inline-flex; align-items: center; gap: 6px;
  box-shadow: 0 2px 8px rgba(0, 177, 79, 0.28);
  transition: all 0.15s; cursor: pointer;
}
.btn-cv-finish:hover { background: #009140; color: #fff; transform: translateY(-1px); }

/* ---- Layout Grid ---- */
.cvb-layout {
  display: grid;
  grid-template-columns: 440px 1fr;
  min-height: calc(100vh - 60px);
}
@media (max-width: 1024px) {
  .cvb-layout { grid-template-columns: 380px 1fr; }
}
@media (max-width: 860px) {
  .cvb-layout { grid-template-columns: 1fr; }
  .cvb-preview-pane { display: none; }
}

/* ---- Left form panel ---- */
.cvb-panel {
  background: #fff;
  border-right: 1px solid #e2e8f0;
  overflow-y: auto;
  height: calc(100vh - 60px);
  position: sticky; top: 60px;
  padding: 16px 20px 80px;
}
.cvb-panel::-webkit-scrollbar { width: 5px; }
.cvb-panel::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }

/* Collapsible Form Section Card */
.cvb-card-section {
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  margin-bottom: 14px;
  background: #fff;
  box-shadow: 0 1px 2px rgba(0,0,0,0.02);
  overflow: hidden;
  transition: border-color 0.15s;
}
.cvb-card-section:hover { border-color: #cbd5e1; }
.cvb-card-section.active { border-color: #00b14f; }
.cvb-card-header {
  display: flex; align-items: center; justify-content: space-between;
  padding: 14px 16px; cursor: pointer;
  font-weight: 700; font-size: 14px; color: #1e293b;
  user-select: none; background: #fff;
}
.cvb-card-header:hover { background: #f8fafc; }
.cvb-card-header .toggle-icon {
  color: #94a3b8; font-size: 12px; transition: transform 0.2s ease;
}
.cvb-card-header.collapsed .toggle-icon { transform: rotate(-90deg); }
.cvb-card-body {
  padding: 4px 16px 18px;
  border-top: 1px solid #f1f5f9;
}
.cvb-card-body.collapsed { display: none; }

/* Input fields */
.cvb-label {
  font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 4px;
  display: flex; align-items: center; justify-content: space-between;
}
.cvb-input {
  width: 100%; border: 1.5px solid #e2e8f0; border-radius: 8px;
  padding: 8px 12px; font-size: 13.5px; color: #1e293b;
  outline: none; transition: all 0.15s;
  background: #fff; font-family: inherit;
}
.cvb-input:focus {
  border-color: #00b14f; box-shadow: 0 0 0 3px rgba(0, 177, 79, 0.12);
}
.cvb-row { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }

/* Entry cards (Experience, Education, Projects) */
.cvb-entry-card {
  background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px;
  padding: 14px; margin-bottom: 12px; position: relative;
}
.cvb-entry-card .cvb-del-btn {
  position: absolute; top: 10px; right: 10px;
  background: #fff; border: 1px solid #e2e8f0; color: #94a3b8;
  border-radius: 50%; width: 24px; height: 24px;
  cursor: pointer; font-size: 11px; display: flex; align-items: center; justify-content: center;
  transition: all 0.15s;
}
.cvb-entry-card .cvb-del-btn:hover {
  background: #fee2e2; border-color: #fecaca; color: #ef4444;
}
.cvb-btn-add {
  width: 100%; border: 1.5px dashed #cbd5e1; border-radius: 8px;
  background: #f8fafc; padding: 9px; font-size: 13px; font-weight: 600;
  color: #00b14f; cursor: pointer; transition: all 0.15s; display: flex; align-items: center; justify-content: center; gap: 6px;
}
.cvb-btn-add:hover {
  border-color: #00b14f; background: #ecfdf5;
}

/* Avatar upload matching Image 2 */
.cvb-avatar-box {
  display: flex; align-items: center; gap: 16px;
  padding: 8px 0 12px;
}
.cvb-avatar-box img {
  width: 76px; height: 76px; border-radius: 50%;
  object-fit: cover; border: 3px solid #e2e8f0;
}
.cvb-avatar-actions {
  display: flex; flex-direction: column; gap: 6px;
}
.btn-upload-avatar {
  background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0;
  font-size: 12.5px; font-weight: 600; padding: 6px 14px; border-radius: 6px;
  cursor: pointer; display: inline-flex; align-items: center; gap: 6px;
  transition: all 0.15s;
}
.btn-upload-avatar:hover {
  background: #00b14f; color: #fff;
}

/* Skill row */
.skill-row {
  display: flex; align-items: center; gap: 10px; margin-bottom: 10px;
}
.skill-row input[type="text"] { flex: 1; }
.skill-row input[type="range"] { width: 90px; accent-color: #00b14f; }
.skill-row .skill-pct { font-size: 12px; color: #64748b; width: 34px; text-align: right; font-weight: 600; }

/* ---- Right Live Preview ---- */
.cvb-preview-pane {
  background: #e2e8f0;
  overflow-y: auto; padding: 32px 24px;
  display: flex; flex-direction: column; align-items: center;
  height: calc(100vh - 60px);
}
.cvb-preview-pane::-webkit-scrollbar { width: 6px; }
.cvb-preview-pane::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }

/* ---- A4 SHEET CONTAINER (Matching Image 2 Live Preview) ---- */
.cv-a4 {
  width: 794px;            /* 210mm @ 96dpi */
  min-height: 1123px;      /* 297mm @ 96dpi */
  background: #fff;
  box-shadow: 0 12px 36px rgba(0,0,0,0.18);
  display: flex;
  font-family: var(--cv-font, 'Be Vietnam Pro', sans-serif);
  font-size: var(--cv-font-size, 13px);
  color: #1e293b;
  --cv-theme: #0f2942;
  --cv-sidebar-bg: #0f2942;
  position: relative;
  overflow: hidden;
  transition: font-size 0.2s;
}

/* Sidebar Left Template (Navy signature style from Image 2) */
.cva-sidebar {
  width: 35%;
  background: var(--cv-sidebar-bg, #0f2942);
  color: #fff;
  padding: 32px 22px;
  display: flex; flex-direction: column;
}
.cva-avatar-wrap { text-align: center; margin-bottom: 24px; }
.cva-avatar-wrap img {
  width: 110px; height: 110px; border-radius: 50%;
  object-fit: cover;
  border: 4px solid rgba(255, 255, 255, 0.85);
  box-shadow: 0 4px 14px rgba(0,0,0,0.25);
}

.cva-sb-title {
  font-size: 11px; font-weight: 800; text-transform: uppercase;
  letter-spacing: 1.2px; color: #fff;
  border-bottom: 1.5px solid rgba(255, 255, 255, 0.25);
  padding-bottom: 4px; margin: 20px 0 10px;
}
.cva-sb-title:first-of-type { margin-top: 0; }
.cva-contact-item {
  display: flex; align-items: center; gap: 10px;
  margin-bottom: 9px; font-size: 11.5px; color: #e2e8f0;
  word-break: break-word;
}
.cva-contact-item i {
  color: #38bdf8; width: 14px; text-align: center; font-size: 12px;
}

/* Skill bars in sidebar */
.cva-sb-skill { margin-bottom: 10px; }
.cva-sb-skill-label {
  display: flex; justify-content: space-between; font-size: 11.5px;
  color: #e2e8f0; font-weight: 500; margin-bottom: 3px;
}
.cva-sb-skill-bar { height: 5px; background: rgba(255, 255, 255, 0.18); border-radius: 3px; }
.cva-sb-skill-fill { height: 100%; background: #38bdf8; border-radius: 3px; }

/* Main Right Column */
.cva-main {
  width: 65%;
  padding: 32px 28px;
  background: #fff;
  display: flex; flex-direction: column;
}
.cva-name {
  font-size: 24px; font-weight: 800; color: #0f172a;
  text-transform: uppercase; line-height: 1.2; margin-bottom: 4px;
  letter-spacing: 0.5px;
}
.cva-jobtitle {
  font-size: 12px; font-weight: 700; color: var(--cv-theme);
  text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 20px;
}

.cva-sec-title {
  font-size: 12px; font-weight: 800; text-transform: uppercase;
  letter-spacing: 1px; color: #0f172a;
  border-bottom: 2px solid var(--cv-theme);
  padding-bottom: 4px; margin: 20px 0 12px;
  display: flex; align-items: center; gap: 8px;
}
.cva-sec-title::before {
  content: ''; display: inline-block; width: 8px; height: 8px;
  border-radius: 2px; background: var(--cv-theme);
}
.cva-sec-title:first-of-type { margin-top: 0; }

.cva-objective {
  font-size: 12.5px; color: #475569; line-height: 1.65;
  margin-bottom: 16px;
}

.cva-item {
  margin-bottom: 14px; page-break-inside: avoid;
}
.cva-item-title {
  font-size: 13.5px; font-weight: 700; color: #0f172a; margin-bottom: 2px;
}
.cva-item-sub {
  font-size: 12.5px; color: var(--cv-theme); font-weight: 600; margin-bottom: 2px;
}
.cva-item-period {
  font-size: 11px; color: #94a3b8; margin-bottom: 5px;
}
.cva-item-desc {
  font-size: 12px; color: #334155; line-height: 1.6; white-space: pre-wrap;
}

/* Print styles */
@media print {
  .cvb-topbar, .cvb-panel, .tw-header, .tw-footer, #aiChatWrapper { display: none !important; }
  .cvb-layout { display: block; }
  .cvb-preview-pane { padding: 0; background: #fff; height: auto; overflow: visible; }
  .cv-a4 { box-shadow: none; width: 210mm; min-height: 297mm; }
  body { background: #fff; }
}
</style>

<!-- TOP CONTROL BAR (Matching Image 2) -->
<div class="cvb-topbar">
  <a href="{{ route('cv.templates') }}" class="btn btn-sm btn-light border" title="Xem tất cả mẫu CV">
    <i class="fa fa-arrow-left me-1"></i> Mẫu CV
  </a>

  <div class="cvb-title-wrap">
    <input type="text" id="cv_title" class="cv-title-input" value="{{ $cd['title'] }}" placeholder="Đặt tên CV...">
  </div>

  {{-- 1. [🎨 Đổi màu] Button Dropdown --}}
  <div class="dropdown">
    <button class="cvb-btn-control dropdown-toggle" type="button" id="dropdownColor" data-bs-toggle="dropdown" aria-expanded="false">
      <span class="color-indicator" id="currentColorDot" style="background: {{ $cd['theme_color'] }};"></span>
      <span>Đổi màu</span>
    </button>
    <div class="dropdown-menu shadow cvb-color-menu" aria-labelledby="dropdownColor">
      @php
        $swatches = [
          '#0f2942' => 'Navy',
          '#00b14f' => 'Xanh lá',
          '#2563eb' => 'Xanh dương',
          '#881337' => 'Đỏ đô',
          '#ea580c' => 'Cam san hô',
          '#7c3aed' => 'Tím hoàng gia',
          '#1e293b' => 'Xám than',
          '#0d9488' => 'Xanh ngọc',
        ];
      @endphp
      @foreach($swatches as $hex => $name)
        <span class="cvb-color-swatch {{ $cd['theme_color'] === $hex ? 'active' : '' }}"
              data-color="{{ $hex }}"
              style="background: {{ $hex }}; color: {{ $hex }};"
              title="{{ $name }}"></span>
      @endforeach
    </div>
  </div>

  {{-- 2. [🔤 Phông chữ] Button Dropdown --}}
  <div class="dropdown">
    <button class="cvb-btn-control dropdown-toggle" type="button" id="dropdownFont" data-bs-toggle="dropdown" aria-expanded="false">
      <i class="fa fa-font"></i>
      <span id="currentFontLabel">{{ $cd['font'] ?? 'Be Vietnam Pro' }}</span>
    </button>
    <ul class="dropdown-menu shadow" aria-labelledby="dropdownFont">
      <li><a class="dropdown-item cv-font-opt" href="javascript:void(0)" data-font="Be Vietnam Pro">Be Vietnam Pro (Chuẩn TV)</a></li>
      <li><a class="dropdown-item cv-font-opt" href="javascript:void(0)" data-font="Roboto">Roboto (Hiện đại)</a></li>
      <li><a class="dropdown-item cv-font-opt" href="javascript:void(0)" data-font="Inter">Inter (Tối giản)</a></li>
      <li><a class="dropdown-item cv-font-opt" href="javascript:void(0)" data-font="Montserrat">Montserrat (Thanh lịch)</a></li>
      <li><a class="dropdown-item cv-font-opt" href="javascript:void(0)" data-font="Georgia">Georgia (Cổ điển)</a></li>
    </ul>
  </div>

  {{-- 3. [📐 Cỡ chữ] Button Dropdown --}}
  <div class="dropdown">
    <button class="cvb-btn-control dropdown-toggle" type="button" id="dropdownSize" data-bs-toggle="dropdown" aria-expanded="false">
      <i class="fa fa-text-height"></i>
      <span id="currentSizeLabel">Cỡ chữ</span>
    </button>
    <ul class="dropdown-menu shadow" aria-labelledby="dropdownSize">
      <li><a class="dropdown-item cv-size-opt" href="javascript:void(0)" data-size="12px">Nhỏ (12px)</a></li>
      <li><a class="dropdown-item cv-size-opt active" href="javascript:void(0)" data-size="13px">Tiêu chuẩn (13px)</a></li>
      <li><a class="dropdown-item cv-size-opt" href="javascript:void(0)" data-size="14px">Lớn (14px)</a></li>
    </ul>
  </div>

  {{-- 4. [🖼️ Đổi mẫu nhanh] Dropdown --}}
  <div class="dropdown">
    <button class="cvb-btn-control dropdown-toggle" type="button" id="dropdownTemplate" data-bs-toggle="dropdown" aria-expanded="false">
      <i class="fa fa-th-large"></i>
      <span>{{ $selectedTpl['name'] ?? 'Mẫu CV' }}</span>
    </button>
    <ul class="dropdown-menu shadow" style="max-height: 320px; overflow-y: auto;" aria-labelledby="dropdownTemplate">
      @foreach($allTemplates as $k => $tpl)
        <li>
          <a class="dropdown-item d-flex justify-content-between align-items-center py-2 {{ $cd['template'] === $k ? 'active' : '' }}"
             href="{{ route('cv.builder', ['template' => $k]) }}">
            <span>{{ $tpl['name'] }}</span>
            <span class="badge bg-light text-dark">{{ $tpl['badge'] }}</span>
          </a>
        </li>
      @endforeach
    </ul>
  </div>

  <span class="gap-auto"></span>

  {{-- 5. Action Buttons --}}
  <button id="btnSaveCv" class="btn-cv-save">
    <i class="fa fa-floppy-o"></i> Lưu CV
  </button>
  <button id="btnExportPdf" class="btn-cv-finish">
    <i class="fa fa-check-circle"></i> Hoàn tất / In PDF
  </button>
</div>

<!-- BUILDER LAYOUT -->
<div class="cvb-layout">

  <!-- ========== LEFT PANEL (FORM) ========== -->
  <div class="cvb-panel">
    <input type="hidden" id="resume_id" value="{{ $cd['id'] }}">
    <input type="hidden" id="cv_theme_color" value="{{ $cd['theme_color'] }}">
    <input type="hidden" id="cv_font_family" value="{{ $cd['font'] ?? 'Be Vietnam Pro' }}">
    <input type="hidden" id="cv_template_key" value="{{ $cd['template'] }}">
    <input type="hidden" id="cv_avatar_url" value="{{ $cd['avatar'] }}">

    <!-- 1. THÔNG TIN CƠ BẢN -->
    <div class="cvb-card-section active">
      <div class="cvb-card-header" data-target="sec-basic">
        <span><i class="fa fa-user-circle text-success me-2"></i>Thông tin cơ bản</span>
        <i class="fa fa-chevron-down toggle-icon"></i>
      </div>
      <div class="cvb-card-body" id="sec-basic">
        {{-- Tiêu đề hồ sơ --}}
        <div class="mb-3">
          <div class="cvb-label">Tiêu đề hồ sơ (Vị trí ứng tuyển) <span class="text-danger">*</span></div>
          <input class="cvb-input" id="f_candidate_title" value="{{ $cd['candidate_title'] }}"
                 placeholder="VD: Lập trình viên Fullstack / Nhân viên kinh doanh..." data-target="p_candidate_title">
        </div>

        {{-- Ảnh hồ sơ --}}
        <div class="cvb-label">Ảnh hồ sơ</div>
        <div class="cvb-avatar-box">
          <img src="{{ $cd['avatar'] }}" id="avatarPreview" alt="avatar"
               onerror="this.src='{{ asset('assets/user/images/avatar7.png') }}'">
          <div class="cvb-avatar-actions">
            <label for="avatarFileInput" class="btn-upload-avatar">
              <i class="fa fa-camera"></i> Tải ảnh mới
            </label>
            <input type="file" id="avatarFileInput" class="d-none" accept="image/*">
            <small class="text-muted" style="font-size: 11px;">Hỗ trợ JPG, PNG, WEBP tối đa 3MB</small>
          </div>
        </div>

        {{-- Họ và tên --}}
        <div class="mb-3">
          <div class="cvb-label">Họ và tên <span class="text-danger">*</span></div>
          <input class="cvb-input" id="f_fullname" value="{{ $cd['fullname'] }}"
                 placeholder="Họ và tên đầy đủ..." data-target="p_fullname">
        </div>

        {{-- Email & Số điện thoại --}}
        <div class="cvb-row mb-3">
          <div>
            <div class="cvb-label">Email <span class="text-danger">*</span></div>
            <input class="cvb-input" id="f_email" value="{{ $cd['email'] }}"
                   placeholder="email@gmail.com" data-target="p_email">
          </div>
          <div>
            <div class="cvb-label">Số điện thoại <span class="text-danger">*</span></div>
            <input class="cvb-input" id="f_phone" value="{{ $cd['phone'] }}"
                   placeholder="0987 654 321" data-target="p_phone">
          </div>
        </div>

        {{-- Địa chỉ --}}
        <div class="mb-3">
          <div class="cvb-label">Địa chỉ</div>
          <input class="cvb-input" id="f_address" value="{{ $cd['address'] }}"
                 placeholder="VD: Trần Kim Xuyến, Yên Hòa, Cầu Giấy, Hà Nội..." data-target="p_address">
        </div>

        {{-- Ngày sinh & Giới tính --}}
        <div class="cvb-row mb-3">
          <div>
            <div class="cvb-label">Ngày sinh</div>
            <input class="cvb-input" id="f_birthday" value="{{ $cd['birthday'] }}"
                   placeholder="15/08/1998" data-target="p_birthday">
          </div>
          <div>
            <div class="cvb-label">Giới tính</div>
            <input class="cvb-input" id="f_gender" value="{{ $cd['gender'] }}"
                   placeholder="Nam / Nữ" data-target="p_gender">
          </div>
        </div>

        {{-- Website / Portfolio --}}
        <div class="mb-2">
          <div class="cvb-label">Website / LinkedIn / GitHub</div>
          <input class="cvb-input" id="f_website" value="{{ $cd['website'] }}"
                 placeholder="https://linkedin.com/in/..." data-target="p_website">
        </div>
      </div>
    </div>

    <!-- 2. MỤC TIÊU NGHỀ NGHIỆP -->
    <div class="cvb-card-section">
      <div class="cvb-card-header" data-target="sec-obj">
        <span><i class="fa fa-bullseye text-success me-2"></i>Mục tiêu nghề nghiệp</span>
        <i class="fa fa-chevron-down toggle-icon"></i>
      </div>
      <div class="cvb-card-body" id="sec-obj">
        <div class="cvb-label">Tóm tắt thế mạnh & mục tiêu sự nghiệp</div>
        <textarea class="cvb-input" id="f_objective" rows="4"
                  placeholder="Giới thiệu kinh nghiệm, điểm mạnh và mục tiêu đóng góp cho doanh nghiệp..."
                  data-target="p_objective">{{ $cd['objective'] }}</textarea>
      </div>
    </div>

    <!-- 3. KINH NGHIỆM LÀM VIỆC -->
    <div class="cvb-card-section">
      <div class="cvb-card-header" data-target="sec-exp">
        <span><i class="fa fa-briefcase text-success me-2"></i>Kinh nghiệm làm việc</span>
        <i class="fa fa-chevron-down toggle-icon"></i>
      </div>
      <div class="cvb-card-body" id="sec-exp">
        <div id="exp_list">
          @foreach ($cd['experience'] as $exp)
          <div class="cvb-entry-card" data-section="exp">
            <button type="button" class="cvb-del-btn" onclick="delCard(this)"><i class="fa fa-times"></i></button>
            <div class="mb-2">
              <div class="cvb-label">Vị trí đảm nhiệm</div>
              <input class="cvb-input exp-position" value="{{ $exp['position'] ?? '' }}" placeholder="VD: Senior Backend Developer">
            </div>
            <div class="mb-2">
              <div class="cvb-label">Tên công ty / Doanh nghiệp</div>
              <input class="cvb-input exp-company" value="{{ $exp['company'] ?? '' }}" placeholder="VD: Công ty Cổ phần Công nghệ ABC">
            </div>
            <div class="mb-2">
              <div class="cvb-label">Thời gian làm việc</div>
              <input class="cvb-input exp-period" value="{{ $exp['period'] ?? '' }}" placeholder="03/2022 - Hiện tại">
            </div>
            <div>
              <div class="cvb-label">Mô tả chi tiết & Kết quả công việc</div>
              <textarea class="cvb-input exp-desc" rows="4" placeholder="• Mô tả trách nhiệm chính và thành quả nổi bật...">{{ $exp['description'] ?? '' }}</textarea>
            </div>
          </div>
          @endforeach
        </div>
        <button class="cvb-btn-add" onclick="addCard('exp')">
          <i class="fa fa-plus"></i> Thêm kinh nghiệm làm việc
        </button>
      </div>
    </div>

    <!-- 4. TRÌNH ĐỘ HỌC VẤN -->
    <div class="cvb-card-section">
      <div class="cvb-card-header" data-target="sec-edu">
        <span><i class="fa fa-graduation-cap text-success me-2"></i>Trình độ học vấn</span>
        <i class="fa fa-chevron-down toggle-icon"></i>
      </div>
      <div class="cvb-card-body" id="sec-edu">
        <div id="edu_list">
          @foreach ($cd['education'] as $edu)
          <div class="cvb-entry-card" data-section="edu">
            <button type="button" class="cvb-del-btn" onclick="delCard(this)"><i class="fa fa-times"></i></button>
            <div class="mb-2">
              <div class="cvb-label">Trường học / Đơn vị đào tạo</div>
              <input class="cvb-input edu-school" value="{{ $edu['school'] ?? '' }}" placeholder="VD: Đại học Bách Khoa Hà Nội">
            </div>
            <div class="mb-2">
              <div class="cvb-label">Chuyên ngành đào tạo</div>
              <input class="cvb-input edu-major" value="{{ $edu['major'] ?? '' }}" placeholder="VD: Công nghệ Thông tin">
            </div>
            <div class="mb-2">
              <div class="cvb-label">Niên khóa / Năm tốt nghiệp</div>
              <input class="cvb-input edu-period" value="{{ $edu['period'] ?? '' }}" placeholder="2016 - 2020">
            </div>
            <div>
              <div class="cvb-label">Xếp loại / Thành tích học tập</div>
              <textarea class="cvb-input edu-desc" rows="2" placeholder="Tốt nghiệp loại Giỏi (GPA 3.4/4.0)...">{{ $edu['description'] ?? '' }}</textarea>
            </div>
          </div>
          @endforeach
        </div>
        <button class="cvb-btn-add" onclick="addCard('edu')">
          <i class="fa fa-plus"></i> Thêm học vấn
        </button>
      </div>
    </div>

    <!-- 5. KỸ NĂNG CHUYÊN MÔN -->
    <div class="cvb-card-section">
      <div class="cvb-card-header" data-target="sec-skill">
        <span><i class="fa fa-star text-success me-2"></i>Kỹ năng chuyên môn</span>
        <i class="fa fa-chevron-down toggle-icon"></i>
      </div>
      <div class="cvb-card-body" id="sec-skill">
        <div id="skill_list">
          @foreach ($cd['skills'] as $sk)
          @php $lvl = intval($sk['level'] ?? 80); @endphp
          <div class="skill-row">
            <input class="cvb-input skill-name" value="{{ $sk['name'] ?? '' }}" placeholder="Tên kỹ năng...">
            <input type="range" class="skill-range" min="10" max="100" value="{{ $lvl }}"
              oninput="this.nextElementSibling.textContent=this.value+'%'; renderPreview()">
            <span class="skill-pct">{{ $lvl }}%</span>
            <button type="button" class="btn btn-sm btn-light border p-1" onclick="this.closest('.skill-row').remove(); renderPreview()">
              <i class="fa fa-times text-danger"></i>
            </button>
          </div>
          @endforeach
        </div>
        <button class="cvb-btn-add" onclick="addSkill()">
          <i class="fa fa-plus"></i> Thêm kỹ năng
        </button>
      </div>
    </div>

    <!-- 6. DỰ ÁN NỔI BẬT -->
    <div class="cvb-card-section">
      <div class="cvb-card-header" data-target="sec-proj">
        <span><i class="fa fa-code text-success me-2"></i>Dự án nổi bật</span>
        <i class="fa fa-chevron-down toggle-icon"></i>
      </div>
      <div class="cvb-card-body" id="sec-proj">
        <div id="proj_list">
          @foreach ($cd['projects'] as $pr)
          <div class="cvb-entry-card" data-section="proj">
            <button type="button" class="cvb-del-btn" onclick="delCard(this)"><i class="fa fa-times"></i></button>
            <div class="mb-2">
              <div class="cvb-label">Tên dự án</div>
              <input class="cvb-input proj-name" value="{{ $pr['name'] ?? '' }}" placeholder="VD: Nền tảng thương mại điện tử">
            </div>
            <div class="cvb-row mb-2">
              <div>
                <div class="cvb-label">Vai trò</div>
                <input class="cvb-input proj-role" value="{{ $pr['role'] ?? '' }}" placeholder="Tech Lead / Fullstack">
              </div>
              <div>
                <div class="cvb-label">Thời gian</div>
                <input class="cvb-input proj-period" value="{{ $pr['period'] ?? '' }}" placeholder="01/2023 - 08/2023">
              </div>
            </div>
            <div>
              <div class="cvb-label">Mô tả công nghệ & Kết quả</div>
              <textarea class="cvb-input proj-desc" rows="3" placeholder="Quy mô dự án, công nghệ sử dụng và kết quả đạt được...">{{ $pr['description'] ?? '' }}</textarea>
            </div>
          </div>
          @endforeach
        </div>
        <button class="cvb-btn-add" onclick="addCard('proj')">
          <i class="fa fa-plus"></i> Thêm dự án
        </button>
      </div>
    </div>

    <!-- 7. CHỨNG CHỈ & GIẢI THƯỞNG -->
    <div class="cvb-card-section">
      <div class="cvb-card-header" data-target="sec-cert">
        <span><i class="fa fa-certificate text-success me-2"></i>Chứng chỉ & Giải thưởng</span>
        <i class="fa fa-chevron-down toggle-icon"></i>
      </div>
      <div class="cvb-card-body" id="sec-cert">
        <div id="cert_list">
          @foreach ($cd['certifications'] as $cr)
          <div class="cvb-entry-card" data-section="cert">
            <button type="button" class="cvb-del-btn" onclick="delCard(this)"><i class="fa fa-times"></i></button>
            <div class="mb-2">
              <div class="cvb-label">Tên chứng chỉ / Giải thưởng</div>
              <input class="cvb-input cert-name" value="{{ $cr['name'] ?? '' }}" placeholder="VD: Chứng chỉ TOEIC 750">
            </div>
            <div class="cvb-row">
              <div>
                <div class="cvb-label">Tổ chức cấp</div>
                <input class="cvb-input cert-org" value="{{ $cr['organization'] ?? '' }}" placeholder="IIG Việt Nam">
              </div>
              <div>
                <div class="cvb-label">Năm</div>
                <input class="cvb-input cert-year" value="{{ $cr['year'] ?? '' }}" placeholder="2022">
              </div>
            </div>
          </div>
          @endforeach
        </div>
        <button class="cvb-btn-add" onclick="addCard('cert')">
          <i class="fa fa-plus"></i> Thêm chứng chỉ
        </button>
      </div>
    </div>

  </div><!-- /cvb-panel -->

  <!-- ========== RIGHT LIVE PREVIEW PANE (Matching Image 2) ========== -->
  <div class="cvb-preview-pane">
    <div class="cv-a4" id="cvA4" style="--cv-theme: {{ $cd['theme_color'] }}; --cv-sidebar-bg: {{ $cd['theme_color'] }}; font-family: '{{ $cd['font'] ?? 'Be Vietnam Pro' }}', sans-serif;">
      
      <!-- LEFT SIDEBAR -->
      <div class="cva-sidebar" id="p_sidebar">
        <div class="cva-avatar-wrap">
          <img id="p_avatar" src="{{ $cd['avatar'] }}" alt="avatar"
               onerror="this.src='{{ asset('assets/user/images/avatar7.png') }}'">
        </div>

        <div class="cva-sb-title">Thông tin liên hệ</div>
        <div class="cva-contact-item"><i class="fa fa-envelope"></i><span id="p_email">{{ $cd['email'] }}</span></div>
        <div class="cva-contact-item"><i class="fa fa-phone"></i><span id="p_phone">{{ $cd['phone'] }}</span></div>
        <div class="cva-contact-item"><i class="fa fa-calendar"></i><span id="p_birthday">{{ $cd['birthday'] }}</span></div>
        <div class="cva-contact-item"><i class="fa fa-user"></i><span id="p_gender">{{ $cd['gender'] }}</span></div>
        <div class="cva-contact-item"><i class="fa fa-map-marker"></i><span id="p_address">{{ $cd['address'] }}</span></div>
        <div class="cva-contact-item"><i class="fa fa-globe"></i><span id="p_website">{{ $cd['website'] }}</span></div>

        <div class="cva-sb-title">Kỹ năng chuyên môn</div>
        <div id="p_skills">
          @foreach ($cd['skills'] as $sk)
          @php $lvl = intval($sk['level'] ?? 80); @endphp
          <div class="cva-sb-skill">
            <div class="cva-sb-skill-label"><span>{{ $sk['name'] ?? '' }}</span><span>{{ $lvl }}%</span></div>
            <div class="cva-sb-skill-bar"><div class="cva-sb-skill-fill" style="width:{{ $lvl }}%"></div></div>
          </div>
          @endforeach
        </div>

        <div class="cva-sb-title">Chứng chỉ & Giải thưởng</div>
        <div id="p_certs">
          @foreach ($cd['certifications'] as $cr)
          <div style="margin-bottom: 9px;">
            <div style="font-size: 11.5px; font-weight: 700; color: #fff;">{{ $cr['name'] ?? '' }}</div>
            <div style="font-size: 11px; color: #94a3b8;">{{ $cr['organization'] ?? '' }} · {{ $cr['year'] ?? '' }}</div>
          </div>
          @endforeach
        </div>
      </div>

      <!-- MAIN COLUMN -->
      <div class="cva-main">
        <div id="p_fullname" class="cva-name">{{ $cd['fullname'] }}</div>
        <div id="p_candidate_title" class="cva-jobtitle">{{ $cd['candidate_title'] }}</div>

        <div class="cva-sec-title">Mục tiêu nghề nghiệp</div>
        <div id="p_objective" class="cva-objective">{{ $cd['objective'] }}</div>

        <div class="cva-sec-title">Kinh nghiệm làm việc</div>
        <div id="p_experience">
          @foreach ($cd['experience'] as $exp)
          <div class="cva-item">
            <div class="cva-item-title">{{ $exp['position'] ?? '' }}</div>
            <div class="cva-item-sub">{{ $exp['company'] ?? '' }}</div>
            <div class="cva-item-period"><i class="fa fa-clock-o me-1"></i>{{ $exp['period'] ?? '' }}</div>
            <div class="cva-item-desc">{{ $exp['description'] ?? '' }}</div>
          </div>
          @endforeach
        </div>

        <div class="cva-sec-title">Trình độ học vấn</div>
        <div id="p_education">
          @foreach ($cd['education'] as $edu)
          <div class="cva-item">
            <div class="cva-item-title">{{ $edu['school'] ?? '' }}</div>
            <div class="cva-item-sub">{{ $edu['major'] ?? '' }}</div>
            <div class="cva-item-period"><i class="fa fa-calendar me-1"></i>{{ $edu['period'] ?? '' }}</div>
            <div class="cva-item-desc">{{ $edu['description'] ?? '' }}</div>
          </div>
          @endforeach
        </div>

        <div class="cva-sec-title">Dự án nổi bật</div>
        <div id="p_projects">
          @foreach ($cd['projects'] as $pr)
          <div class="cva-item">
            <div class="cva-item-title">{{ $pr['name'] ?? '' }}</div>
            <span class="badge bg-light text-dark border me-2">{{ $pr['role'] ?? '' }}</span>
            <span style="font-size: 11px; color: #94a3b8;">{{ $pr['period'] ?? '' }}</span>
            <div class="cva-item-desc" style="margin-top: 4px;">{{ $pr['description'] ?? '' }}</div>
          </div>
          @endforeach
        </div>
      </div>

    </div><!-- /cv-a4 -->
  </div><!-- /cvb-preview-pane -->

</div><!-- /cvb-layout -->

@endsection

@section('customJs')
<script>
/* ============================================================
   REAL-TIME CV LIVE PREVIEW & CONTROLS
============================================================ */

function renderPreview() {
  const theme = document.getElementById('cv_theme_color').value || '#0f2942';
  const cvA4 = document.getElementById('cvA4');
  cvA4.style.setProperty('--cv-theme', theme);
  cvA4.style.setProperty('--cv-sidebar-bg', theme);

  // Sync basic text fields
  const textMap = {
    f_fullname: 'p_fullname',
    f_candidate_title: 'p_candidate_title',
    f_email: 'p_email',
    f_phone: 'p_phone',
    f_birthday: 'p_birthday',
    f_gender: 'p_gender',
    f_address: 'p_address',
    f_website: 'p_website',
    f_objective: 'p_objective'
  };
  Object.entries(textMap).forEach(([fid, pid]) => {
    const el = document.getElementById(pid);
    const src = document.getElementById(fid);
    if (el && src) el.textContent = src.value || '';
  });

  // Render Experience list
  let expHtml = '';
  document.querySelectorAll('#exp_list .cvb-entry-card').forEach(card => {
    const pos = card.querySelector('.exp-position')?.value || '';
    const comp = card.querySelector('.exp-company')?.value || '';
    const per = card.querySelector('.exp-period')?.value || '';
    const desc = card.querySelector('.exp-desc')?.value || '';
    expHtml += `
      <div class="cva-item">
        <div class="cva-item-title">${esc(pos)}</div>
        <div class="cva-item-sub">${esc(comp)}</div>
        <div class="cva-item-period"><i class="fa fa-clock-o me-1"></i>${esc(per)}</div>
        <div class="cva-item-desc">${esc(desc)}</div>
      </div>
    `;
  });
  document.getElementById('p_experience').innerHTML = expHtml;

  // Render Education list
  let eduHtml = '';
  document.querySelectorAll('#edu_list .cvb-entry-card').forEach(card => {
    const sch = card.querySelector('.edu-school')?.value || '';
    const maj = card.querySelector('.edu-major')?.value || '';
    const per = card.querySelector('.edu-period')?.value || '';
    const desc = card.querySelector('.edu-desc')?.value || '';
    eduHtml += `
      <div class="cva-item">
        <div class="cva-item-title">${esc(sch)}</div>
        <div class="cva-item-sub">${esc(maj)}</div>
        <div class="cva-item-period"><i class="fa fa-calendar me-1"></i>${esc(per)}</div>
        <div class="cva-item-desc">${esc(desc)}</div>
      </div>
    `;
  });
  document.getElementById('p_education').innerHTML = eduHtml;

  // Render Skills list
  let skHtml = '';
  document.querySelectorAll('#skill_list .skill-row').forEach(row => {
    const name = row.querySelector('.skill-name')?.value || '';
    const lvl = row.querySelector('.skill-range')?.value || '80';
    skHtml += `
      <div class="cva-sb-skill">
        <div class="cva-sb-skill-label"><span>${esc(name)}</span><span>${lvl}%</span></div>
        <div class="cva-sb-skill-bar"><div class="cva-sb-skill-fill" style="width:${lvl}%"></div></div>
      </div>
    `;
  });
  document.getElementById('p_skills').innerHTML = skHtml;

  // Render Projects list
  let prHtml = '';
  document.querySelectorAll('#proj_list .cvb-entry-card').forEach(card => {
    const name = card.querySelector('.proj-name')?.value || '';
    const role = card.querySelector('.proj-role')?.value || '';
    const per = card.querySelector('.proj-period')?.value || '';
    const desc = card.querySelector('.proj-desc')?.value || '';
    prHtml += `
      <div class="cva-item">
        <div class="cva-item-title">${esc(name)}</div>
        <span class="badge bg-light text-dark border me-2">${esc(role)}</span>
        <span style="font-size: 11px; color: #94a3b8;">${esc(per)}</span>
        <div class="cva-item-desc" style="margin-top: 4px;">${esc(desc)}</div>
      </div>
    `;
  });
  document.getElementById('p_projects').innerHTML = prHtml;

  // Render Certifications list
  let crHtml = '';
  document.querySelectorAll('#cert_list .cvb-entry-card').forEach(card => {
    const name = card.querySelector('.cert-name')?.value || '';
    const org = card.querySelector('.cert-org')?.value || '';
    const yr = card.querySelector('.cert-year')?.value || '';
    crHtml += `
      <div style="margin-bottom: 9px;">
        <div style="font-size: 11.5px; font-weight: 700; color: #fff;">${esc(name)}</div>
        <div style="font-size: 11px; color: #94a3b8;">${esc(org)} · ${esc(yr)}</div>
      </div>
    `;
  });
  document.getElementById('p_certs').innerHTML = crHtml;
}

function esc(str) {
  return String(str || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
}

/* Bind inputs to real-time rendering */
document.querySelectorAll('.cvb-panel input, .cvb-panel textarea').forEach(el => {
  el.addEventListener('input', renderPreview);
});

/* Collapsible Section Cards */
document.querySelectorAll('.cvb-card-header').forEach(header => {
  header.addEventListener('click', () => {
    const targetId = header.getAttribute('data-target');
    const body = document.getElementById(targetId);
    if (!body) return;
    const isCollapsed = body.classList.toggle('collapsed');
    header.classList.toggle('collapsed', isCollapsed);
    header.closest('.cvb-card-section').classList.toggle('active', !isCollapsed);
  });
});

/* Dynamic Card Addition / Deletion */
function delCard(btn) {
  btn.closest('.cvb-entry-card').remove();
  renderPreview();
}

function addCard(type) {
  let html = '';
  if (type === 'exp') {
    html = `
      <div class="cvb-entry-card" data-section="exp">
        <button type="button" class="cvb-del-btn" onclick="delCard(this)"><i class="fa fa-times"></i></button>
        <div class="mb-2">
          <div class="cvb-label">Vị trí đảm nhiệm</div>
          <input class="cvb-input exp-position" placeholder="VD: Senior Backend Developer">
        </div>
        <div class="mb-2">
          <div class="cvb-label">Tên công ty / Doanh nghiệp</div>
          <input class="cvb-input exp-company" placeholder="VD: Công ty Cổ phần Công nghệ ABC">
        </div>
        <div class="mb-2">
          <div class="cvb-label">Thời gian làm việc</div>
          <input class="cvb-input exp-period" placeholder="01/2022 - Hiện tại">
        </div>
        <div>
          <div class="cvb-label">Mô tả chi tiết & Kết quả công việc</div>
          <textarea class="cvb-input exp-desc" rows="4" placeholder="• Mô tả chi tiết..."></textarea>
        </div>
      </div>
    `;
    document.getElementById('exp_list').insertAdjacentHTML('beforeend', html);
  } else if (type === 'edu') {
    html = `
      <div class="cvb-entry-card" data-section="edu">
        <button type="button" class="cvb-del-btn" onclick="delCard(this)"><i class="fa fa-times"></i></button>
        <div class="mb-2">
          <div class="cvb-label">Trường học / Đơn vị đào tạo</div>
          <input class="cvb-input edu-school" placeholder="VD: Đại học Bách Khoa Hà Nội">
        </div>
        <div class="mb-2">
          <div class="cvb-label">Chuyên ngành đào tạo</div>
          <input class="cvb-input edu-major" placeholder="VD: Công nghệ Thông tin">
        </div>
        <div class="mb-2">
          <div class="cvb-label">Niên khóa / Năm tốt nghiệp</div>
          <input class="cvb-input edu-period" placeholder="2018 - 2022">
        </div>
        <div>
          <div class="cvb-label">Xếp loại / Thành tích học tập</div>
          <textarea class="cvb-input edu-desc" rows="2" placeholder="Xếp loại tốt nghiệp..."></textarea>
        </div>
      </div>
    `;
    document.getElementById('edu_list').insertAdjacentHTML('beforeend', html);
  } else if (type === 'proj') {
    html = `
      <div class="cvb-entry-card" data-section="proj">
        <button type="button" class="cvb-del-btn" onclick="delCard(this)"><i class="fa fa-times"></i></button>
        <div class="mb-2">
          <div class="cvb-label">Tên dự án</div>
          <input class="cvb-input proj-name" placeholder="VD: Nền tảng thương mại điện tử">
        </div>
        <div class="cvb-row mb-2">
          <div>
            <div class="cvb-label">Vai trò</div>
            <input class="cvb-input proj-role" placeholder="Tech Lead / Fullstack">
          </div>
          <div>
            <div class="cvb-label">Thời gian</div>
            <input class="cvb-input proj-period" placeholder="01/2023 - 08/2023">
          </div>
        </div>
        <div>
          <div class="cvb-label">Mô tả công nghệ & Kết quả</div>
          <textarea class="cvb-input proj-desc" rows="3" placeholder="Mô tả dự án..."></textarea>
        </div>
      </div>
    `;
    document.getElementById('proj_list').insertAdjacentHTML('beforeend', html);
  } else if (type === 'cert') {
    html = `
      <div class="cvb-entry-card" data-section="cert">
        <button type="button" class="cvb-del-btn" onclick="delCard(this)"><i class="fa fa-times"></i></button>
        <div class="mb-2">
          <div class="cvb-label">Tên chứng chỉ / Giải thưởng</div>
          <input class="cvb-input cert-name" placeholder="VD: Chứng chỉ TOEIC 750">
        </div>
        <div class="cvb-row">
          <div>
            <div class="cvb-label">Tổ chức cấp</div>
            <input class="cvb-input cert-org" placeholder="IIG Việt Nam">
          </div>
          <div>
            <div class="cvb-label">Năm</div>
            <input class="cvb-input cert-year" placeholder="2023">
          </div>
        </div>
      </div>
    `;
    document.getElementById('cert_list').insertAdjacentHTML('beforeend', html);
  }

  // Re-bind input events
  document.querySelectorAll('.cvb-panel input, .cvb-panel textarea').forEach(el => {
    el.removeEventListener('input', renderPreview);
    el.addEventListener('input', renderPreview);
  });
  renderPreview();
}

function addSkill() {
  const html = `
    <div class="skill-row">
      <input class="cvb-input skill-name" placeholder="Tên kỹ năng mới...">
      <input type="range" class="skill-range" min="10" max="100" value="80"
        oninput="this.nextElementSibling.textContent=this.value+'%'; renderPreview()">
      <span class="skill-pct">80%</span>
      <button type="button" class="btn btn-sm btn-light border p-1" onclick="this.closest('.skill-row').remove(); renderPreview()">
        <i class="fa fa-times text-danger"></i>
      </button>
    </div>
  `;
  document.getElementById('skill_list').insertAdjacentHTML('beforeend', html);
  document.querySelectorAll('.skill-row input').forEach(el => {
    el.addEventListener('input', renderPreview);
  });
  renderPreview();
}

/* Color Swatches Interaction */
document.querySelectorAll('.cvb-color-swatch').forEach(swatch => {
  swatch.addEventListener('click', () => {
    document.querySelectorAll('.cvb-color-swatch').forEach(s => s.classList.remove('active'));
    swatch.classList.add('active');
    const color = swatch.getAttribute('data-color');
    document.getElementById('cv_theme_color').value = color;
    document.getElementById('currentColorDot').style.background = color;
    renderPreview();
  });
});

/* Font Selector Interaction */
document.querySelectorAll('.cv-font-opt').forEach(opt => {
  opt.addEventListener('click', () => {
    const font = opt.getAttribute('data-font');
    document.getElementById('cv_font_family').value = font;
    document.getElementById('currentFontLabel').textContent = font;
    document.getElementById('cvA4').style.fontFamily = `'${font}', sans-serif`;
  });
});

/* Font Size Selector Interaction */
document.querySelectorAll('.cv-size-opt').forEach(opt => {
  opt.addEventListener('click', () => {
    document.querySelectorAll('.cv-size-opt').forEach(s => s.classList.remove('active'));
    opt.classList.add('active');
    const size = opt.getAttribute('data-size');
    document.getElementById('cvA4').style.setProperty('--cv-font-size', size);
    document.getElementById('currentSizeLabel').textContent = opt.textContent;
  });
});

/* Avatar Upload via AJAX */
document.getElementById('avatarFileInput').addEventListener('change', function() {
  if (!this.files || !this.files[0]) return;
  const formData = new FormData();
  formData.append('avatar_file', this.files[0]);
  formData.append('_token', '{{ csrf_token() }}');

  toastr.info('Đang tải ảnh đại diện...');
  $.ajax({
    url: '{{ route("cv.uploadAvatar") }}',
    type: 'POST',
    data: formData,
    processData: false,
    contentType: false,
    success: function(res) {
      if (res.status) {
        document.getElementById('avatarPreview').src = res.avatar_url;
        document.getElementById('p_avatar').src = res.avatar_url;
        document.getElementById('cv_avatar_url').value = res.avatar_url;
        toastr.success(res.message);
      } else {
        toastr.error(res.message);
      }
    },
    error: function() {
      toastr.error('Lỗi khi tải ảnh lên server.');
    }
  });
});

/* Save CV via AJAX */
function saveCvData(callback) {
  // Collect experience
  const expData = [];
  document.querySelectorAll('#exp_list .cvb-entry-card').forEach(card => {
    expData.push({
      position: card.querySelector('.exp-position')?.value || '',
      company: card.querySelector('.exp-company')?.value || '',
      period: card.querySelector('.exp-period')?.value || '',
      description: card.querySelector('.exp-desc')?.value || '',
    });
  });

  // Collect education
  const eduData = [];
  document.querySelectorAll('#edu_list .cvb-entry-card').forEach(card => {
    eduData.push({
      school: card.querySelector('.edu-school')?.value || '',
      major: card.querySelector('.edu-major')?.value || '',
      period: card.querySelector('.edu-period')?.value || '',
      description: card.querySelector('.edu-desc')?.value || '',
    });
  });

  // Collect skills
  const skillsData = [];
  document.querySelectorAll('#skill_list .skill-row').forEach(row => {
    skillsData.push({
      name: row.querySelector('.skill-name')?.value || '',
      level: row.querySelector('.skill-range')?.value || '80',
    });
  });

  // Collect projects
  const projData = [];
  document.querySelectorAll('#proj_list .cvb-entry-card').forEach(card => {
    projData.push({
      name: card.querySelector('.proj-name')?.value || '',
      role: card.querySelector('.proj-role')?.value || '',
      period: card.querySelector('.proj-period')?.value || '',
      description: card.querySelector('.proj-desc')?.value || '',
    });
  });

  // Collect certifications
  const certData = [];
  document.querySelectorAll('#cert_list .cvb-entry-card').forEach(card => {
    certData.push({
      name: card.querySelector('.cert-name')?.value || '',
      organization: card.querySelector('.cert-org')?.value || '',
      year: card.querySelector('.cert-year')?.value || '',
    });
  });

  const payload = {
    _token: '{{ csrf_token() }}',
    resume_id: document.getElementById('resume_id').value,
    title: document.getElementById('cv_title').value.trim() || 'CV Ứng Tuyển Chuyên Nghiệp',
    fullname: document.getElementById('f_fullname').value.trim(),
    candidate_title: document.getElementById('f_candidate_title').value.trim(),
    email: document.getElementById('f_email').value.trim(),
    phone: document.getElementById('f_phone').value.trim(),
    birthday: document.getElementById('f_birthday').value.trim(),
    gender: document.getElementById('f_gender').value.trim(),
    address: document.getElementById('f_address').value.trim(),
    website: document.getElementById('f_website').value.trim(),
    avatar: document.getElementById('cv_avatar_url').value,
    theme_color: document.getElementById('cv_theme_color').value,
    template: document.getElementById('cv_template_key').value,
    objective: document.getElementById('f_objective').value.trim(),
    experience_data: JSON.stringify(expData),
    education_data: JSON.stringify(eduData),
    skills_data: JSON.stringify(skillsData),
    projects_data: JSON.stringify(projData),
    certifications_data: JSON.stringify(certData),
  };

  $.ajax({
    url: '{{ route("cv.save") }}',
    type: 'POST',
    data: payload,
    dataType: 'json',
    success: function(res) {
      if (res.status) {
        document.getElementById('resume_id').value = res.resume_id;
        toastr.success(res.message);
        if (typeof callback === 'function') callback(res.resume_id);
      } else if (res.require_login) {
        toastr.warning(res.message);
        setTimeout(() => { window.location.href = '{{ route("account.login") }}'; }, 1500);
      } else if (res.errors) {
        const firstErr = Object.values(res.errors)[0][0];
        toastr.error(firstErr);
      }
    },
    error: function() {
      toastr.error('Đã xảy ra lỗi khi lưu CV.');
    }
  });
}

document.getElementById('btnSaveCv').addEventListener('click', function() {
  saveCvData();
});

document.getElementById('btnExportPdf').addEventListener('click', function() {
  saveCvData(function(resumeId) {
    window.open('{{ url("/cv-print") }}/' + resumeId, '_blank');
  });
});

// Initial render
renderPreview();
</script>
@endsection
