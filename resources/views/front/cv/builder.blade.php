@extends('front.layouts.app')

@php
  $currentTemplateKey = request()->get('template', $resume->template ?? ($defaultData['template'] ?? 'modern_navy'));
  $selectedTpl = $allTemplates[$currentTemplateKey] ?? ($allTemplates['modern_navy'] ?? [
    'name' => 'Hiện Đại (Navy)',
    'primary_color' => '#004C82',
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
                           : asset('assets/user/images/avatar_silhouette.svg'),
    'theme_color'      => $resume->theme_color ?? ($selectedTpl['primary_color'] ?? '#004C82'),
    'template'         => $resume->template ?? $currentTemplateKey,
    'font'             => $selectedTpl['font'] ?? 'Be Vietnam Pro',
    'objective'        => $resume->objective,
    'experience'       => !empty($resume->experience_data)     ? $resume->experience_data     : [],
    'education'        => !empty($resume->education_data)      ? $resume->education_data      : [],
    'skills'           => !empty($resume->skills_data)         ? $resume->skills_data         : [],
    'projects'         => !empty($resume->projects_data)       ? $resume->projects_data       : [],
    'certifications'   => !empty($resume->certifications_data) ? $resume->certifications_data : [],
  ] : array_merge($defaultData, [
    'id' => '',
    'template' => $currentTemplateKey,
    'theme_color' => $selectedTpl['primary_color'] ?? '#004C82',
    'font' => $selectedTpl['font'] ?? 'Be Vietnam Pro'
  ]);
@endphp

@section('main')
<!-- Google Fonts for CV Templates -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Inter:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

<style>
/* ============================================================
   CV BUILDER — VIEC LAM 24H EXACT CLONE
   ============================================================ */
body { background: #f8fafc; font-family: 'Be Vietnam Pro', -apple-system, BlinkMacSystemFont, sans-serif; }

/* ---- Top bar ---- */
.cvb-topbar {
  position: sticky; top: 0; z-index: 1000;
  background: #fff;
  border-bottom: 1px solid #e2e8f0;
  padding: 10px 24px;
  display: flex; align-items: center; justify-content: space-between;
  box-shadow: 0 1px 3px rgba(0,0,0,0.04);
}
.cvb-btn-back {
  width: 36px; height: 36px; border-radius: 8px;
  border: 1px solid #e2e8f0; background: #fff; color: #475569;
  display: flex; align-items: center; justify-content: center;
  text-decoration: none; font-size: 14px; transition: all 0.15s;
}
.cvb-btn-back:hover { background: #f1f5f9; color: #1e293b; }

/* Progress score */
.cvb-score-wrap {
  display: flex; flex-direction: column; min-width: 200px;
}
.cvb-score-header {
  display: flex; align-items: center; gap: 8px; font-size: 12px; margin-bottom: 4px;
}
.cvb-score-badge {
  color: #ef4444; font-weight: 700;
}
.cvb-score-label {
  color: #475569; font-weight: 600;
}
.cvb-score-tip {
  color: #059669; font-weight: 600; font-size: 11.5px;
}
.cvb-progress-bar {
  height: 4px; background: #e2e8f0; border-radius: 2px; overflow: hidden; width: 140px;
}
.cvb-progress-fill {
  height: 100%; background: #ef4444; width: 10%; border-radius: 2px; transition: width 0.3s;
}

/* Control buttons above preview */
.cvb-ctrl-btn {
  background: #fff; border: 1px solid #e2e8f0; border-radius: 6px;
  padding: 5px 10px; font-size: 11.5px; font-weight: 600; color: #334155;
  display: inline-flex; align-items: center; gap: 5px; cursor: pointer;
  transition: all 0.15s; text-decoration: none; line-height: 1.4;
}
.cvb-ctrl-btn:hover { background: #f8fafc; border-color: #cbd5e1; color: #5e35b1; }
.cvb-ctrl-btn::after { font-size: 9px; margin-left: 2px; }
.cvb-ctrl-btn i { font-size: 11px; }

.cvb-color-dot {
  width: 10px; height: 10px; border-radius: 50%; display: inline-block; flex-shrink: 0;
}

.cvb-btn-finish {
  background: #5e35b1; color: #fff; border: 1px solid #512da8;
  font-weight: 700; border-radius: 6px; padding: 5px 14px; font-size: 12px;
  display: inline-flex; align-items: center; gap: 5px; cursor: pointer;
  box-shadow: 0 2px 6px rgba(94, 53, 177, 0.25);
  transition: all 0.15s; text-decoration: none; line-height: 1.4;
}
.cvb-btn-finish:hover { background: #512da8; color: #fff; transform: translateY(-1px); }

/* Language pills */
.cvb-lang-pill {
  padding: 4px 8px; border-radius: 999px; border: 1px solid #e2e8f0;
  font-size: 11px; font-weight: 600; color: #64748b; background: #fff;
  cursor: pointer; display: inline-flex; align-items: center; gap: 3px; line-height: 1.4;
}
.cvb-lang-pill.active {
  background: #f3e8ff; border-color: #d8b4fe; color: #7c3aed;
}

/* Palette swatches: strictly hidden until dropdown toggle triggers */
.dropdown-menu.cvb-color-menu {
  display: none !important;
  min-width: 176px; padding: 10px; border-radius: 8px;
  border: 1px solid #e2e8f0; box-shadow: 0 8px 24px rgba(0,0,0,0.12);
}
.dropdown-menu.cvb-color-menu.show {
  display: grid !important;
  grid-template-columns: repeat(4, 1fr);
  gap: 8px;
}
.cvb-color-swatch {
  width: 28px; height: 28px; border-radius: 50%;
  cursor: pointer; border: 2px solid transparent;
  transition: transform 0.15s; display: flex; align-items: center; justify-content: center;
}
.cvb-color-swatch:hover, .cvb-color-swatch.active {
  transform: scale(1.15); border-color: #fff; outline: 2px solid currentColor;
}
.cvb-color-swatch.active::after {
  content: '✓'; color: #fff; font-size: 12px; font-weight: 900;
}

/* Template dropdown menu styling */
.dropdown-menu.cvb-template-menu {
  font-size: 12px; min-width: 200px; max-height: 320px; overflow-y: auto;
  border-radius: 8px; border: 1px solid #e2e8f0; padding: 4px;
}
.dropdown-menu.cvb-template-menu .dropdown-item {
  font-size: 12px; padding: 6px 10px; border-radius: 6px;
}

/* ---- Layout Grid ---- */
.cvb-layout {
  display: grid;
  grid-template-columns: 460px 1fr;
  min-height: calc(100vh - 58px);
}
@media (max-width: 1100px) {
  .cvb-layout { grid-template-columns: 420px 1fr; }
}
@media (max-width: 860px) {
  .cvb-layout { grid-template-columns: 1fr; }
  .cvb-preview-pane { display: none; }
}

/* ---- Left Form Panel ---- */
.cvb-panel {
  background: #fff;
  border-right: 1px solid #e2e8f0;
  overflow-y: auto;
  height: calc(100vh - 58px);
  position: sticky; top: 58px;
  padding: 24px 28px 80px;
}
.cvb-panel::-webkit-scrollbar { width: 5px; }
.cvb-panel::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }

/* Sections */
.cvb-main-section-title {
  font-size: 16px; font-weight: 800; color: #1e293b; margin-bottom: 20px;
  display: flex; align-items: center; justify-content: space-between;
}
.cvb-label {
  font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;
  display: flex; align-items: center; gap: 4px;
}
.cvb-input {
  width: 100%; border: 1px solid #d1d5db; border-radius: 8px;
  padding: 10px 14px; font-size: 13.5px; color: #1e293b;
  outline: none; transition: all 0.15s; background: #fff;
}
.cvb-input:focus {
  border-color: #5e35b1; box-shadow: 0 0 0 3px rgba(94, 53, 177, 0.12);
}
.cvb-input::placeholder { color: #9ca3af; font-size: 13px; }

/* Avatar sidebox (Matching Image 1) */
.cvb-avatar-sidebox {
  display: flex; flex-direction: column; align-items: center; gap: 10px;
  padding-top: 4px;
}
.cvb-avatar-img {
  width: 84px; height: 84px; border-radius: 50%;
  object-fit: cover; border: 2px solid #e2e8f0;
}
.cvb-btn-upload {
  background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;
  padding: 6px 14px; font-size: 12px; font-weight: 600; color: #475569;
  cursor: pointer; display: inline-flex; align-items: center; gap: 6px;
  transition: all 0.15s; text-decoration: none;
}
.cvb-btn-upload:hover {
  background: #f1f5f9; color: #1e293b; border-color: #cbd5e1;
}

/* Add section links (Matching Image 1) */
.cvb-add-section-block {
  border-top: 1px solid #f1f5f9;
  padding: 18px 0;
}
.cvb-section-header-title {
  font-size: 15px; font-weight: 700; color: #1e293b; margin-bottom: 12px;
}
.cvb-link-add {
  color: #2563eb; font-size: 13.5px; font-weight: 600; text-decoration: none;
  display: inline-flex; align-items: center; gap: 6px; background: none; border: none; padding: 0; cursor: pointer;
}
.cvb-link-add:hover { color: #1d4ed8; text-decoration: underline; }

/* Entry Card for added items */
.cvb-entry-card {
  background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px;
  padding: 14px; margin-bottom: 12px; position: relative;
}
.cvb-del-btn {
  position: absolute; top: 10px; right: 10px;
  background: #fff; border: 1px solid #e2e8f0; color: #94a3b8;
  border-radius: 50%; width: 24px; height: 24px;
  cursor: pointer; font-size: 11px; display: flex; align-items: center; justify-content: center;
}
.cvb-del-btn:hover { background: #fee2e2; color: #ef4444; border-color: #fecaca; }

/* ---- Right Live Preview Pane (Matching Image 1) ---- */
.cvb-preview-pane {
  background: #edf2f7;
  overflow-y: auto; overflow-x: hidden;
  padding: 16px 16px 60px;
  display: flex; flex-direction: column; align-items: center;
  height: calc(100vh - 58px);
  position: relative;
}
.cvb-preview-pane::-webkit-scrollbar { width: 6px; }
.cvb-preview-pane::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }

/* Zoom toolbar */
.cvb-zoom-bar {
  display: flex; align-items: center; gap: 6px;
  background: #ffffff; padding: 4px 10px; border-radius: 20px;
  box-shadow: 0 1px 4px rgba(0,0,0,0.08); margin-bottom: 14px;
  z-index: 10; border: 1px solid #e2e8f0;
}
.cvb-zoom-btn {
  background: none; border: none; color: #475569;
  font-size: 11px; font-weight: 600; padding: 3px 7px;
  border-radius: 4px; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;
  transition: all 0.15s;
}
.cvb-zoom-btn:hover { background: #f1f5f9; color: #1e293b; }
.cvb-zoom-level {
  font-size: 11px; font-weight: 700; color: #334155; min-width: 38px; text-align: center;
}

/* Scaled A4 Container to preserve aspect ratio & scrolling */
.cv-a4-scale-container {
  display: flex; justify-content: center; align-items: flex-start;
  transition: width 0.15s ease, height 0.15s ease;
  position: relative;
}

/* A4 Paper */
.cv-a4 {
  width: 794px; min-height: 1123px;
  background: #fff;
  box-shadow: 0 10px 30px rgba(0,0,0,0.12);
  display: flex;
  font-family: 'Be Vietnam Pro', sans-serif;
  font-size: 13px; color: #1e293b;
  --cv-theme: #004C82;
  --cv-sidebar-bg: #004C82;
  position: relative; overflow: hidden;
  transform-origin: top center;
  transition: transform 0.15s ease;
  flex-shrink: 0;
}

/* Sidebar (Navy Blue matching Image 1) */
.cva-sidebar {
  width: 33%;
  background: var(--cv-sidebar-bg, #004C82);
  color: #fff;
  padding: 32px 18px;
  display: flex; flex-direction: column;
}
.cva-avatar-wrap { text-align: center; margin-bottom: 24px; }
.cva-avatar-wrap img {
  width: 104px; height: 104px; border-radius: 50%;
  object-fit: cover;
  border: 4px solid rgba(255, 255, 255, 0.9);
  box-shadow: 0 4px 12px rgba(0,0,0,0.2);
  background: #fff;
}

.cva-sb-title {
  font-size: 11.5px; font-weight: 800; text-transform: uppercase;
  letter-spacing: 1.2px; color: #fff;
  border-bottom: 1.5px solid rgba(255, 255, 255, 0.25);
  padding-bottom: 4px; margin: 18px 0 10px;
}
.cva-sb-title:first-of-type { margin-top: 0; }

.cva-contact-item {
  display: flex; align-items: center; gap: 8px;
  margin-bottom: 8px; font-size: 11.5px; color: #e2e8f0;
  word-break: break-all;
}
.cva-contact-item i {
  color: #60a5fa; width: 14px; text-align: center; font-size: 11px; flex-shrink: 0;
}
.cva-contact-placeholder {
  color: #93c5fd; opacity: 0.8; font-style: normal;
}

/* Main Column */
.cva-main {
  width: 67%;
  padding: 32px 26px;
  background: #fff;
  display: flex; flex-direction: column;
}
.cva-name {
  font-size: 23px; font-weight: 800; color: #0f172a;
  text-transform: uppercase; line-height: 1.2; margin-bottom: 5px;
  letter-spacing: 0.5px;
}
.cva-jobtitle {
  font-size: 11.5px; font-weight: 700; color: #64748b;
  text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 22px;
}
.cva-jobtitle.is-placeholder {
  color: #94a3b8; letter-spacing: 2px;
}

.cva-sec-title {
  font-size: 11.5px; font-weight: 800; text-transform: uppercase;
  letter-spacing: 1px; color: #0f172a;
  border-bottom: 2px solid var(--cv-theme);
  padding-bottom: 4px; margin: 18px 0 10px;
  display: flex; align-items: center; gap: 8px;
}
.cva-sec-title::before {
  content: ''; display: inline-block; width: 7px; height: 7px;
  border-radius: 2px; background: var(--cv-theme);
}
.cva-sec-title:first-of-type { margin-top: 0; }

.cva-item {
  margin-bottom: 12px; page-break-inside: avoid;
}
.cva-item-title {
  font-size: 13px; font-weight: 700; color: #0f172a; margin-bottom: 2px;
}
.cva-item-sub {
  font-size: 12px; color: var(--cv-theme); font-weight: 600; margin-bottom: 2px;
}
.cva-item-period {
  font-size: 11px; color: #94a3b8; margin-bottom: 4px;
}
.cva-item-desc {
  font-size: 11.5px; color: #334155; line-height: 1.6; white-space: pre-wrap;
}

/* Print styles */
@media print {
  .cvb-topbar, .cvb-panel, .tw-header, .tw-footer, #aiChatWrapper, .cvb-zoom-bar { display: none !important; }
  .cvb-layout { display: block; }
  .cvb-preview-pane { padding: 0; background: #fff; height: auto; overflow: visible; }
  .cv-a4-scale-container { width: auto !important; height: auto !important; display: block; }
  .cv-a4 { transform: none !important; box-shadow: none; width: 210mm; min-height: 297mm; }
  body { background: #fff; }
}
</style>

<!-- TOP CONTROL BAR (Matching Image 1) -->
<div class="cvb-topbar">
  <div class="d-flex align-items-center gap-3">
    <a href="{{ route('cv.templates') }}" class="cvb-btn-back" title="Quay lại danh sách mẫu">
      <i class="fa fa-arrow-left"></i>
    </a>

    {{-- Điểm hồ sơ progress bar --}}
    <div class="cvb-score-wrap">
      <div class="cvb-score-header">
        <span class="cvb-score-badge" id="scoreBadge">10%</span>
        <span class="cvb-score-label">Điểm hồ sơ</span>
        <span class="cvb-score-tip" id="scoreTip">+90% Thêm thông tin cơ bản <i class="fa fa-info-circle"></i></span>
      </div>
      <div class="cvb-progress-bar">
        <div class="cvb-progress-fill" id="scoreBar" style="width: 10%;"></div>
      </div>
    </div>
  </div>

  {{-- Right Controls (Positioned directly above preview like Image 1) --}}
  <div class="d-flex align-items-center gap-2">
    {{-- Đổi màu dropdown --}}
    <div class="dropdown">
      <button class="cvb-ctrl-btn dropdown-toggle" type="button" id="dropdownColor" data-bs-toggle="dropdown" aria-expanded="false">
        <span class="cvb-color-dot" style="background: {{ $cd['theme_color'] }};" id="colorDot"></span>
        <span>Đổi màu</span>
      </button>
      <div class="dropdown-menu shadow cvb-color-menu" aria-labelledby="dropdownColor">
        @php
          $swatches = [
            '#004C82' => 'Navy (Vieclam24h)',
            '#00b14f' => 'Xanh lá (TopCV)',
            '#2563eb' => 'Xanh dương',
            '#881337' => 'Đỏ đô',
            '#ea580c' => 'Cam san hô',
            '#7c3aed' => 'Tím',
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

    {{-- Đổi mẫu button --}}
    <div class="dropdown">
      <button class="cvb-ctrl-btn dropdown-toggle" type="button" id="dropdownTemplate" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fa fa-th-large"></i>
        <span>Đổi mẫu</span>
      </button>
      <ul class="dropdown-menu shadow cvb-template-menu" aria-labelledby="dropdownTemplate">
        @foreach($allTemplates as $k => $tpl)
          <li>
            <a class="dropdown-item d-flex justify-content-between align-items-center py-1.5 px-2.5 {{ $cd['template'] === $k ? 'active' : '' }}"
               href="{{ route('cv.builder', ['template' => $k]) }}">
              <span>{{ $tpl['name'] }}</span>
              <span class="badge bg-light text-dark" style="font-size: 10px;">{{ $tpl['badge'] }}</span>
            </a>
          </li>
        @endforeach
      </ul>
    </div>

    {{-- Ngôn ngữ Việt / Anh --}}
    <span class="cvb-lang-pill active" title="Tiếng Việt">🇻🇳 Việt</span>
    <span class="cvb-lang-pill" title="Tiếng Anh">🇬🇧 Anh</span>

    {{-- Hoàn tất / In PDF --}}
    <button type="button" id="btnExportPdf" class="cvb-btn-finish">
      <i class="fa fa-check"></i> Hoàn tất
    </button>
  </div>
</div>

<!-- BUILDER LAYOUT -->
<div class="cvb-layout">

  <!-- ========== LEFT PANEL (FORM MATCHING IMAGE 1) ========== -->
  <div class="cvb-panel">
    <input type="hidden" id="resume_id" value="{{ $cd['id'] }}">
    <input type="hidden" id="cv_title" value="{{ $cd['title'] }}">
    <input type="hidden" id="cv_theme_color" value="{{ $cd['theme_color'] }}">
    <input type="hidden" id="cv_template_key" value="{{ $cd['template'] }}">
    <input type="hidden" id="cv_avatar_url" value="{{ $cd['avatar'] }}">

    <!-- THÔNG TIN CƠ BẢN (Exact Image 1 layout) -->
    <div class="mb-4">
      <h3 class="cvb-main-section-title">Thông tin cơ bản</h3>

      <div class="row g-3 mb-3">
        <div class="col-8">
          <div class="mb-3">
            <label class="cvb-label">Tiêu đề hồ sơ <i class="fa fa-info-circle text-muted" title="Vị trí bạn muốn ứng tuyển"></i></label>
            <input type="text" class="cvb-input" id="f_candidate_title" value="{{ $cd['candidate_title'] }}"
                   placeholder="VD: Nhân viên kinh doanh bất động sản">
          </div>
          <div>
            <label class="cvb-label">Họ và tên <span class="text-danger">*</span></label>
            <input type="text" class="cvb-input" id="f_fullname" value="{{ $cd['fullname'] }}"
                   placeholder="Họ và tên">
          </div>
        </div>

        <div class="col-4">
          <label class="cvb-label text-center d-block">Ảnh hồ sơ <i class="fa fa-info-circle text-muted"></i></label>
          <div class="cvb-avatar-sidebox">
            <img src="{{ $cd['avatar'] }}" id="avatarPreview" alt="avatar" class="cvb-avatar-img">
            <label for="avatarFileInput" class="cvb-btn-upload">
              <i class="fa fa-upload"></i> Tải lên hình mới
            </label>
            <input type="file" id="avatarFileInput" class="d-none" accept="image/*">
          </div>
        </div>
      </div>

      <div class="row g-3 mb-3">
        <div class="col-6">
          <label class="cvb-label">Email <span class="text-danger">*</span></label>
          <input type="email" class="cvb-input" id="f_email" value="{{ $cd['email'] }}" placeholder="Điền email">
        </div>
        <div class="col-6">
          <label class="cvb-label">Số điện thoại</label>
          <input type="tel" class="cvb-input" id="f_phone" value="{{ $cd['phone'] }}" placeholder="Điền số điện thoại">
        </div>
      </div>

      <div class="mb-3">
        <label class="cvb-label">Địa chỉ <i class="fa fa-info-circle text-muted"></i></label>
        <input type="text" class="cvb-input" id="f_address" value="{{ $cd['address'] }}" placeholder="VD: Trần Bình Trọng, Phường 1">
      </div>

      <div class="row g-3 mb-2">
        <div class="col-6">
          <label class="cvb-label">Ngày sinh</label>
          <input type="text" class="cvb-input" id="f_birthday" value="{{ $cd['birthday'] }}" placeholder="Chọn ngày sinh (VD: 15/08/1998)">
        </div>
        <div class="col-6">
          <label class="cvb-label">Giới tính</label>
          <select class="cvb-input" id="f_gender">
            <option value="">Chọn giới tính</option>
            <option value="Nam" {{ $cd['gender'] === 'Nam' ? 'selected' : '' }}>Nam</option>
            <option value="Nữ" {{ $cd['gender'] === 'Nữ' ? 'selected' : '' }}>Nữ</option>
          </select>
        </div>
      </div>
    </div>

    <!-- KINH NGHIỆM LÀM VIỆC (Matching Image 1: clean link '+ Thêm kinh nghiệm') -->
    <div class="cvb-add-section-block">
      <div class="cvb-section-header-title">Kinh nghiệm làm việc</div>
      <div id="exp_list">
        @foreach ($cd['experience'] as $exp)
        <div class="cvb-entry-card" data-section="exp">
          <button type="button" class="cvb-del-btn" onclick="delCard(this)"><i class="fa fa-times"></i></button>
          <div class="mb-2">
            <label class="cvb-label">Vị trí đảm nhiệm</label>
            <input class="cvb-input exp-position" value="{{ $exp['position'] ?? '' }}" placeholder="VD: Nhân viên kinh doanh">
          </div>
          <div class="mb-2">
            <label class="cvb-label">Tên công ty</label>
            <input class="cvb-input exp-company" value="{{ $exp['company'] ?? '' }}" placeholder="VD: Công ty TNHH XYZ">
          </div>
          <div class="mb-2">
            <label class="cvb-label">Thời gian làm việc</label>
            <input class="cvb-input exp-period" value="{{ $exp['period'] ?? '' }}" placeholder="01/2022 - Hiện tại">
          </div>
          <div>
            <label class="cvb-label">Mô tả công việc</label>
            <textarea class="cvb-input exp-desc" rows="3" placeholder="Mô tả công việc và thành tích...">{{ $exp['description'] ?? '' }}</textarea>
          </div>
        </div>
        @endforeach
      </div>
      <button type="button" class="cvb-link-add" onclick="addCard('exp')">
        <i class="fa fa-plus"></i> Thêm kinh nghiệm làm việc
      </button>
    </div>

    <!-- HỌC VẤN (Matching Image 1) -->
    <div class="cvb-add-section-block">
      <div class="cvb-section-header-title">Học vấn</div>
      <div id="edu_list">
        @foreach ($cd['education'] as $edu)
        <div class="cvb-entry-card" data-section="edu">
          <button type="button" class="cvb-del-btn" onclick="delCard(this)"><i class="fa fa-times"></i></button>
          <div class="mb-2">
            <label class="cvb-label">Trường học</label>
            <input class="cvb-input edu-school" value="{{ $edu['school'] ?? '' }}" placeholder="VD: Đại học Kinh tế Quốc dân">
          </div>
          <div class="mb-2">
            <label class="cvb-label">Chuyên ngành</label>
            <input class="cvb-input edu-major" value="{{ $edu['major'] ?? '' }}" placeholder="VD: Quản trị Kinh doanh">
          </div>
          <div class="mb-2">
            <label class="cvb-label">Niên khóa</label>
            <input class="cvb-input edu-period" value="{{ $edu['period'] ?? '' }}" placeholder="2018 - 2022">
          </div>
          <div>
            <label class="cvb-label">Mô tả thêm</label>
            <textarea class="cvb-input edu-desc" rows="2" placeholder="Xếp loại tốt nghiệp...">{{ $edu['description'] ?? '' }}</textarea>
          </div>
        </div>
        @endforeach
      </div>
      <button type="button" class="cvb-link-add" onclick="addCard('edu')">
        <i class="fa fa-plus"></i> Thêm học vấn
      </button>
    </div>

    <!-- KỸ NĂNG (Matching Image 1) -->
    <div class="cvb-add-section-block">
      <div class="cvb-section-header-title">Kỹ năng</div>
      <div id="skill_list">
        @foreach ($cd['skills'] as $sk)
        @php $lvl = intval($sk['level'] ?? 80); @endphp
        <div class="d-flex align-items-center gap-2 mb-2 skill-row">
          <input class="cvb-input skill-name" value="{{ $sk['name'] ?? '' }}" placeholder="Tên kỹ năng..." style="flex:1">
          <input type="range" class="skill-range" min="10" max="100" value="{{ $lvl }}" style="width: 80px;"
            oninput="this.nextElementSibling.textContent=this.value+'%'; renderPreview()">
          <span class="skill-pct" style="font-size:12px;width:32px;">{{ $lvl }}%</span>
          <button type="button" class="btn btn-sm btn-light border p-1" onclick="this.closest('.skill-row').remove(); renderPreview()">
            <i class="fa fa-times text-danger"></i>
          </button>
        </div>
        @endforeach
      </div>
      <button type="button" class="cvb-link-add" onclick="addSkill()">
        <i class="fa fa-plus"></i> Thêm kỹ năng
      </button>
    </div>

    <!-- NGOẠI NGỮ -->
    <div class="cvb-add-section-block">
      <div class="cvb-section-header-title">Ngoại ngữ</div>
      <div id="lang_list"></div>
      <button type="button" class="cvb-link-add" onclick="addLanguage()">
        <i class="fa fa-plus"></i> Thêm ngoại ngữ
      </button>
    </div>

    <!-- TIN HỌC -->
    <div class="cvb-add-section-block">
      <div class="cvb-section-header-title">Tin học</div>
      <div id="it_list"></div>
      <button type="button" class="cvb-link-add" onclick="addItSkill()">
        <i class="fa fa-plus"></i> Thêm tin học
      </button>
    </div>

    <!-- NGƯỜI LIÊN HỆ -->
    <div class="cvb-add-section-block">
      <div class="cvb-section-header-title">Người liên hệ</div>
      <div id="ref_list"></div>
      <button type="button" class="cvb-link-add" onclick="addReference()">
        <i class="fa fa-plus"></i> Thêm người liên hệ
      </button>
    </div>

    <!-- HOẠT ĐỘNG -->
    <div class="cvb-add-section-block">
      <div class="cvb-section-header-title">Hoạt động</div>
      <div id="act_list"></div>
      <button type="button" class="cvb-link-add" onclick="addActivity()">
        <i class="fa fa-plus"></i> Thêm hoạt động
      </button>
    </div>

    <!-- CHỨNG CHỈ -->
    <div class="cvb-add-section-block">
      <div class="cvb-section-header-title">Chứng chỉ</div>
      <div id="cert_list"></div>
      <button type="button" class="cvb-link-add" onclick="addCard('cert')">
        <i class="fa fa-plus"></i> Thêm chứng chỉ
      </button>
    </div>

    <!-- SỞ THÍCH -->
    <div class="cvb-add-section-block">
      <div class="cvb-section-header-title">Sở thích</div>
      <textarea class="cvb-input" id="f_hobby" rows="3" placeholder="Sở thích cá nhân..."></textarea>
    </div>

  </div><!-- /cvb-panel -->

  <!-- ========== RIGHT LIVE PREVIEW (MATCHING IMAGE 1) ========== -->
  <div class="cvb-preview-pane" id="cvbPreviewPane">

    <!-- Zoom toolbar -->
    <div class="cvb-zoom-bar">
      <button type="button" class="cvb-zoom-btn" onclick="adjustZoom(-0.05)" title="Thu nhỏ"><i class="fa fa-minus"></i></button>
      <span class="cvb-zoom-level" id="zoomLevel">100%</span>
      <button type="button" class="cvb-zoom-btn" onclick="adjustZoom(0.05)" title="Phóng to"><i class="fa fa-plus"></i></button>
      <button type="button" class="cvb-zoom-btn" onclick="resetZoom()" title="Tự căn vừa màn hình"><i class="fa fa-arrows-alt"></i> Vừa màn hình</button>
    </div>

    <div class="cv-a4-scale-container" id="cvA4ScaleContainer">
      <div class="cv-a4" id="cvA4" style="--cv-theme: {{ $cd['theme_color'] }}; --cv-sidebar-bg: {{ $cd['theme_color'] }};">
        
        <!-- LEFT NAVY SIDEBAR (Matching Image 1) -->
        <div class="cva-sidebar" id="p_sidebar">
          <div class="cva-avatar-wrap">
            <img id="p_avatar" src="{{ $cd['avatar'] }}" alt="avatar">
          </div>

          <div class="cva-sb-title">LIÊN HỆ</div>
          
          {{-- Phone --}}
          <div class="cva-contact-item">
            <i class="fa fa-phone"></i>
            <span id="p_phone" class="{{ empty($cd['phone']) ? 'cva-contact-placeholder' : '' }}">
              {{ !empty($cd['phone']) ? $cd['phone'] : 'Điền số điện thoại' }}
            </span>
          </div>

          {{-- Email --}}
          <div class="cva-contact-item">
            <i class="fa fa-envelope"></i>
            <span id="p_email">{{ $cd['email'] }}</span>
          </div>

          {{-- Birthday --}}
          <div class="cva-contact-item">
            <i class="fa fa-calendar"></i>
            <span id="p_birthday" class="{{ empty($cd['birthday']) ? 'cva-contact-placeholder' : '' }}">
              {{ !empty($cd['birthday']) ? $cd['birthday'] : 'Chọn ngày sinh' }}
            </span>
          </div>

          {{-- Address --}}
          <div class="cva-contact-item">
            <i class="fa fa-map-marker"></i>
            <span id="p_address" class="{{ empty($cd['address']) ? 'cva-contact-placeholder' : '' }}">
              {{ !empty($cd['address']) ? $cd['address'] : 'VD: Trần Bình Trọng, Phường 1' }}
            </span>
          </div>

          {{-- Skills (only if added) --}}
          <div id="p_skills_wrap" style="display: none;">
            <div class="cva-sb-title">KỸ NĂNG</div>
            <div id="p_skills"></div>
          </div>

          {{-- Languages / IT (only if added) --}}
          <div id="p_lang_wrap" style="display: none;">
            <div class="cva-sb-title">NGOẠI NGỮ</div>
            <div id="p_languages"></div>
          </div>
        </div>

        <!-- MAIN RIGHT COLUMN (Matching Image 1) -->
        <div class="cva-main">
          <div id="p_fullname" class="cva-name">{{ mb_strtoupper($cd['fullname'], 'UTF-8') }}</div>
          <div id="p_candidate_title" class="cva-jobtitle {{ empty($cd['candidate_title']) ? 'is-placeholder' : '' }}">
            {{ !empty($cd['candidate_title']) ? mb_strtoupper($cd['candidate_title'], 'UTF-8') : 'TIÊU ĐỀ HỒ SƠ' }}
          </div>

          {{-- Experience Section --}}
          <div id="p_exp_wrap" style="display: none;">
            <div class="cva-sec-title">KINH NGHIỆM LÀM VIỆC</div>
            <div id="p_experience"></div>
          </div>

          {{-- Education Section --}}
          <div id="p_edu_wrap" style="display: none;">
            <div class="cva-sec-title">HỌC VẤN</div>
            <div id="p_education"></div>
          </div>

          {{-- Certifications Section --}}
          <div id="p_cert_wrap" style="display: none;">
            <div class="cva-sec-title">CHỨNG CHỈ</div>
            <div id="p_certs"></div>
          </div>

          {{-- Projects Section --}}
          <div id="p_proj_wrap" style="display: none;">
            <div class="cva-sec-title">HOẠT ĐỘNG / DỰ ÁN</div>
            <div id="p_projects"></div>
          </div>

          {{-- Reference Section --}}
          <div id="p_ref_wrap" style="display: none;">
            <div class="cva-sec-title">NGƯỜI LIÊN HỆ</div>
            <div id="p_references"></div>
          </div>
        </div>

      </div><!-- /cv-a4 -->
    </div><!-- /cv-a4-scale-container -->
  </div><!-- /cvb-preview-pane -->

</div><!-- /cvb-layout -->

@endsection

@section('customJs')
<script>
/* ============================================================
   REAL-TIME CV LIVE PREVIEW & VIECLAM24H REPLICA ENGINE
============================================================ */

function renderPreview() {
  const theme = document.getElementById('cv_theme_color').value || '#004C82';
  const cvA4 = document.getElementById('cvA4');
  cvA4.style.setProperty('--cv-theme', theme);
  cvA4.style.setProperty('--cv-sidebar-bg', theme);

  // 1. Candidate Name
  const fName = (document.getElementById('f_fullname').value || '').trim();
  document.getElementById('p_fullname').textContent = fName ? fName.toUpperCase() : 'HỌ VÀ TÊN';

  // 2. Candidate Title (Placeholder "TIÊU ĐỀ HỒ SƠ" if empty like Image 1)
  const fTitle = (document.getElementById('f_candidate_title').value || '').trim();
  const pTitle = document.getElementById('p_candidate_title');
  if (fTitle) {
    pTitle.textContent = fTitle.toUpperCase();
    pTitle.classList.remove('is-placeholder');
  } else {
    pTitle.textContent = 'TIÊU ĐỀ HỒ SƠ';
    pTitle.classList.add('is-placeholder');
  }

  // 3. Email
  const fEmail = (document.getElementById('f_email').value || '').trim();
  document.getElementById('p_email').textContent = fEmail || 'email@example.com';

  // 4. Phone (Placeholder "Điền số điện thoại" if empty like Image 1)
  const fPhone = (document.getElementById('f_phone').value || '').trim();
  const pPhone = document.getElementById('p_phone');
  if (fPhone) {
    pPhone.textContent = fPhone;
    pPhone.classList.remove('cva-contact-placeholder');
  } else {
    pPhone.textContent = 'Điền số điện thoại';
    pPhone.classList.add('cva-contact-placeholder');
  }

  // 5. Birthday (Placeholder "Chọn ngày sinh" if empty like Image 1)
  const fBirthday = (document.getElementById('f_birthday').value || '').trim();
  const pBirthday = document.getElementById('p_birthday');
  if (fBirthday) {
    pBirthday.textContent = fBirthday;
    pBirthday.classList.remove('cva-contact-placeholder');
  } else {
    pBirthday.textContent = 'Chọn ngày sinh';
    pBirthday.classList.add('cva-contact-placeholder');
  }

  // 6. Address (Placeholder "VD: Trần Bình Trọng, Phường 1" if empty like Image 1)
  const fAddress = (document.getElementById('f_address').value || '').trim();
  const pAddress = document.getElementById('p_address');
  if (fAddress) {
    pAddress.textContent = fAddress;
    pAddress.classList.remove('cva-contact-placeholder');
  } else {
    pAddress.textContent = 'VD: Trần Bình Trọng, Phường 1';
    pAddress.classList.add('cva-contact-placeholder');
  }

  // 7. Experience List
  const expCards = document.querySelectorAll('#exp_list .cvb-entry-card');
  const expWrap = document.getElementById('p_exp_wrap');
  if (expCards.length > 0) {
    expWrap.style.display = 'block';
    let expHtml = '';
    expCards.forEach(card => {
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
  } else {
    expWrap.style.display = 'none';
  }

  // 8. Education List
  const eduCards = document.querySelectorAll('#edu_list .cvb-entry-card');
  const eduWrap = document.getElementById('p_edu_wrap');
  if (eduCards.length > 0) {
    eduWrap.style.display = 'block';
    let eduHtml = '';
    eduCards.forEach(card => {
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
  } else {
    eduWrap.style.display = 'none';
  }

  // 9. Skills List
  const skillRows = document.querySelectorAll('#skill_list .skill-row');
  const skillsWrap = document.getElementById('p_skills_wrap');
  if (skillRows.length > 0) {
    skillsWrap.style.display = 'block';
    let skHtml = '';
    skillRows.forEach(row => {
      const name = row.querySelector('.skill-name')?.value || '';
      const lvl = row.querySelector('.skill-range')?.value || '80';
      skHtml += `
        <div style="margin-bottom: 8px;">
          <div style="display:flex; justify-content:space-between; font-size:11.5px; color:#e2e8f0; margin-bottom:2px;">
            <span>${esc(name)}</span><span>${lvl}%</span>
          </div>
          <div style="height:4px; background:rgba(255,255,255,0.2); border-radius:2px;">
            <div style="height:100%; width:${lvl}%; background:#60a5fa; border-radius:2px;"></div>
          </div>
        </div>
      `;
    });
    document.getElementById('p_skills').innerHTML = skHtml;
  } else {
    skillsWrap.style.display = 'none';
  }

  // Calculate profile completeness score
  updateScore();

  // Adjust A4 scale to fit viewport
  if (typeof fitA4Preview === 'function') {
    fitA4Preview();
  }
}

function updateScore() {
  let score = 10;
  if (document.getElementById('f_candidate_title').value.trim()) score += 20;
  if (document.getElementById('f_phone').value.trim()) score += 15;
  if (document.getElementById('f_address').value.trim()) score += 15;
  if (document.querySelectorAll('#exp_list .cvb-entry-card').length > 0) score += 20;
  if (document.querySelectorAll('#edu_list .cvb-entry-card').length > 0) score += 10;
  if (document.querySelectorAll('#skill_list .skill-row').length > 0) score += 10;

  score = Math.min(score, 100);
  document.getElementById('scoreBadge').textContent = score + '%';
  document.getElementById('scoreBar').style.width = score + '%';
  if (score >= 80) {
    document.getElementById('scoreBar').style.background = '#059669';
    document.getElementById('scoreTip').textContent = 'Hồ sơ đạt chuẩn xuất sắc';
  } else if (score >= 40) {
    document.getElementById('scoreBar').style.background = '#eab308';
    document.getElementById('scoreTip').textContent = `+${100 - score}% Điền thêm kinh nghiệm`;
  } else {
    document.getElementById('scoreBar').style.background = '#ef4444';
    document.getElementById('scoreTip').textContent = `+${100 - score}% Thêm thông tin cơ bản`;
  }
}

function esc(str) {
  return String(str || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
}

/* Event binding */
document.querySelectorAll('.cvb-panel input, .cvb-panel textarea, .cvb-panel select').forEach(el => {
  el.addEventListener('input', renderPreview);
});

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
          <label class="cvb-label">Vị trí đảm nhiệm</label>
          <input class="cvb-input exp-position" placeholder="VD: Nhân viên kinh doanh">
        </div>
        <div class="mb-2">
          <label class="cvb-label">Tên công ty</label>
          <input class="cvb-input exp-company" placeholder="VD: Công ty TNHH XYZ">
        </div>
        <div class="mb-2">
          <label class="cvb-label">Thời gian làm việc</label>
          <input class="cvb-input exp-period" placeholder="01/2022 - Hiện tại">
        </div>
        <div>
          <label class="cvb-label">Mô tả công việc</label>
          <textarea class="cvb-input exp-desc" rows="3" placeholder="Mô tả trách nhiệm và kết quả..."></textarea>
        </div>
      </div>
    `;
    document.getElementById('exp_list').insertAdjacentHTML('beforeend', html);
  } else if (type === 'edu') {
    html = `
      <div class="cvb-entry-card" data-section="edu">
        <button type="button" class="cvb-del-btn" onclick="delCard(this)"><i class="fa fa-times"></i></button>
        <div class="mb-2">
          <label class="cvb-label">Trường học</label>
          <input class="cvb-input edu-school" placeholder="VD: Đại học Kinh tế Quốc dân">
        </div>
        <div class="mb-2">
          <label class="cvb-label">Chuyên ngành</label>
          <input class="cvb-input edu-major" placeholder="VD: Quản trị Kinh doanh">
        </div>
        <div class="mb-2">
          <label class="cvb-label">Niên khóa</label>
          <input class="cvb-input edu-period" placeholder="2018 - 2022">
        </div>
        <div>
          <label class="cvb-label">Mô tả thêm</label>
          <textarea class="cvb-input edu-desc" rows="2" placeholder="Xếp loại tốt nghiệp..."></textarea>
        </div>
      </div>
    `;
    document.getElementById('edu_list').insertAdjacentHTML('beforeend', html);
  }

  document.querySelectorAll('.cvb-panel input, .cvb-panel textarea').forEach(el => {
    el.removeEventListener('input', renderPreview);
    el.addEventListener('input', renderPreview);
  });
  renderPreview();
}

function addSkill() {
  const html = `
    <div class="d-flex align-items-center gap-2 mb-2 skill-row">
      <input class="cvb-input skill-name" placeholder="Tên kỹ năng..." style="flex:1">
      <input type="range" class="skill-range" min="10" max="100" value="80" style="width: 80px;"
        oninput="this.nextElementSibling.textContent=this.value+'%'; renderPreview()">
      <span class="skill-pct" style="font-size:12px;width:32px;">80%</span>
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

function addLanguage() {
  const name = prompt('Nhập tên ngoại ngữ (VD: Tiếng Anh, Tiếng Nhật):');
  if (!name) return;
  const html = `
    <div class="d-flex align-items-center justify-content-between p-2 mb-2 bg-light border rounded">
      <span class="fw-bold">${esc(name)}</span>
      <button type="button" class="btn btn-sm text-danger" onclick="this.parentElement.remove()"><i class="fa fa-times"></i></button>
    </div>
  `;
  document.getElementById('lang_list').insertAdjacentHTML('beforeend', html);
}

function addItSkill() {
  const name = prompt('Nhập kỹ năng tin học (VD: MS Word, Excel, Photoshop):');
  if (!name) return;
  const html = `
    <div class="d-flex align-items-center justify-content-between p-2 mb-2 bg-light border rounded">
      <span class="fw-bold">${esc(name)}</span>
      <button type="button" class="btn btn-sm text-danger" onclick="this.parentElement.remove()"><i class="fa fa-times"></i></button>
    </div>
  `;
  document.getElementById('it_list').insertAdjacentHTML('beforeend', html);
}

function addReference() {
  const name = prompt('Nhập thông tin người liên hệ (Họ tên, chức vụ, SĐT):');
  if (!name) return;
  const html = `
    <div class="d-flex align-items-center justify-content-between p-2 mb-2 bg-light border rounded">
      <span>${esc(name)}</span>
      <button type="button" class="btn btn-sm text-danger" onclick="this.parentElement.remove()"><i class="fa fa-times"></i></button>
    </div>
  `;
  document.getElementById('ref_list').insertAdjacentHTML('beforeend', html);
}

function addActivity() {
  const name = prompt('Nhập tên hoạt động / dự án:');
  if (!name) return;
  const html = `
    <div class="d-flex align-items-center justify-content-between p-2 mb-2 bg-light border rounded">
      <span>${esc(name)}</span>
      <button type="button" class="btn btn-sm text-danger" onclick="this.parentElement.remove()"><i class="fa fa-times"></i></button>
    </div>
  `;
  document.getElementById('act_list').insertAdjacentHTML('beforeend', html);
}

/* Color Palette: click swatch to change color and close dropdown */
document.querySelectorAll('.cvb-color-swatch').forEach(swatch => {
  swatch.addEventListener('click', (e) => {
    e.stopPropagation();
    document.querySelectorAll('.cvb-color-swatch').forEach(s => s.classList.remove('active'));
    swatch.classList.add('active');
    const color = swatch.getAttribute('data-color');
    document.getElementById('cv_theme_color').value = color;
    document.getElementById('colorDot').style.background = color;
    renderPreview();

    // Close Bootstrap dropdown after pick
    const colorBtn = document.getElementById('dropdownColor');
    if (colorBtn && window.bootstrap) {
      const bsDropdown = bootstrap.Dropdown.getInstance(colorBtn);
      if (bsDropdown) bsDropdown.hide();
    }
  });
});

/* A4 Auto-Scale & Zoom Engine */
let currentScale = 1.0;
let userAdjustedZoom = false;

function fitA4Preview(forceAuto = false) {
  const pane = document.getElementById('cvbPreviewPane');
  const a4 = document.getElementById('cvA4');
  const container = document.getElementById('cvA4ScaleContainer');
  if (!pane || !a4 || !container) return;

  const availableWidth = pane.clientWidth - 48; // safety margin for padding & scrollbar
  const baseWidth = 794;

  if (!userAdjustedZoom || forceAuto) {
    let autoScale = availableWidth / baseWidth;
    autoScale = Math.min(1.0, Math.max(0.4, autoScale));
    currentScale = Math.round(autoScale * 100) / 100;
    if (forceAuto) userAdjustedZoom = false;
  }

  a4.style.transform = `scale(${currentScale})`;
  container.style.width = `${Math.round(baseWidth * currentScale)}px`;
  container.style.height = `${Math.round((a4.offsetHeight || 1123) * currentScale + 20)}px`;

  const zoomText = document.getElementById('zoomLevel');
  if (zoomText) {
    zoomText.textContent = `${Math.round(currentScale * 100)}%`;
  }
}

function adjustZoom(delta) {
  userAdjustedZoom = true;
  currentScale = Math.min(1.3, Math.max(0.4, currentScale + delta));
  currentScale = Math.round(currentScale * 100) / 100;
  const a4 = document.getElementById('cvA4');
  const container = document.getElementById('cvA4ScaleContainer');
  if (a4 && container) {
    a4.style.transform = `scale(${currentScale})`;
    container.style.width = `${Math.round(794 * currentScale)}px`;
    container.style.height = `${Math.round((a4.offsetHeight || 1123) * currentScale + 20)}px`;
    const zoomText = document.getElementById('zoomLevel');
    if (zoomText) zoomText.textContent = `${Math.round(currentScale * 100)}%`;
  }
}

function resetZoom() {
  userAdjustedZoom = false;
  fitA4Preview(true);
}

window.addEventListener('resize', () => {
  if (!userAdjustedZoom) {
    fitA4Preview();
  }
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
      toastr.error('Lỗi khi tải ảnh lên.');
    }
  });
});

/* Save CV and Finish */
document.getElementById('btnExportPdf').addEventListener('click', function() {
  const expData = [];
  document.querySelectorAll('#exp_list .cvb-entry-card').forEach(card => {
    expData.push({
      position: card.querySelector('.exp-position')?.value || '',
      company: card.querySelector('.exp-company')?.value || '',
      period: card.querySelector('.exp-period')?.value || '',
      description: card.querySelector('.exp-desc')?.value || '',
    });
  });

  const eduData = [];
  document.querySelectorAll('#edu_list .cvb-entry-card').forEach(card => {
    eduData.push({
      school: card.querySelector('.edu-school')?.value || '',
      major: card.querySelector('.edu-major')?.value || '',
      period: card.querySelector('.edu-period')?.value || '',
      description: card.querySelector('.edu-desc')?.value || '',
    });
  });

  const skillsData = [];
  document.querySelectorAll('#skill_list .skill-row').forEach(row => {
    skillsData.push({
      name: row.querySelector('.skill-name')?.value || '',
      level: row.querySelector('.skill-range')?.value || '80',
    });
  });

  const payload = {
    _token: '{{ csrf_token() }}',
    resume_id: document.getElementById('resume_id').value,
    title: document.getElementById('cv_title').value.trim() || 'CV Ứng Tuyển',
    fullname: document.getElementById('f_fullname').value.trim(),
    candidate_title: document.getElementById('f_candidate_title').value.trim() || 'Ứng viên tìm việc',
    email: document.getElementById('f_email').value.trim(),
    phone: document.getElementById('f_phone').value.trim(),
    birthday: document.getElementById('f_birthday').value.trim(),
    gender: document.getElementById('f_gender').value,
    address: document.getElementById('f_address').value.trim(),
    avatar: document.getElementById('cv_avatar_url').value,
    theme_color: document.getElementById('cv_theme_color').value,
    template: document.getElementById('cv_template_key').value,
    experience_data: JSON.stringify(expData),
    education_data: JSON.stringify(eduData),
    skills_data: JSON.stringify(skillsData),
    projects_data: '[]',
    certifications_data: '[]',
  };

  $.ajax({
    url: '{{ route("cv.save") }}',
    type: 'POST',
    data: payload,
    dataType: 'json',
    success: function(res) {
      if (res.status) {
        document.getElementById('resume_id').value = res.resume_id;
        toastr.success('Đã lưu CV thành công!');
        window.open('{{ url("/cv-print") }}/' + res.resume_id, '_blank');
      } else if (res.require_login) {
        toastr.warning(res.message);
        setTimeout(() => { window.location.href = '{{ route("account.login") }}'; }, 1500);
      } else if (res.errors) {
        toastr.error(Object.values(res.errors)[0][0]);
      }
    },
    error: function() {
      toastr.error('Lỗi lưu CV.');
    }
  });
});

// Initial run
renderPreview();
setTimeout(fitA4Preview, 60);
window.addEventListener('load', fitA4Preview);
</script>
@endsection
