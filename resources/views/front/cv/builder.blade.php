@extends('front.layouts.app')

@php
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
    'theme_color'      => $resume->theme_color ?? '#00b14f',
    'objective'        => $resume->objective,
    'experience'       => !empty($resume->experience_data)     ? $resume->experience_data     : $defaultData['experience'],
    'education'        => !empty($resume->education_data)      ? $resume->education_data      : $defaultData['education'],
    'skills'           => !empty($resume->skills_data)         ? $resume->skills_data         : $defaultData['skills'],
    'projects'         => !empty($resume->projects_data)       ? $resume->projects_data       : $defaultData['projects'],
    'certifications'   => !empty($resume->certifications_data) ? $resume->certifications_data : $defaultData['certifications'],
  ] : array_merge($defaultData, ['id' => '']);
@endphp

@section('main')
<style>
/* ============================================================
   CV BUILDER — TOPCV STYLE
   ============================================================ */

/* Reset page padding from app layout */
body { background: #f1f5f9; }

/* ---- Top bar ---- */
.cvb-topbar {
  position: sticky; top: 0; z-index: 999;
  background: #fff;
  border-bottom: 1px solid #e2e8f0;
  padding: 10px 20px;
  display: flex; align-items: center; gap: 10px;
  flex-wrap: wrap;
  box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}
.cvb-topbar .cv-title-input {
  font-weight: 700; border: none; background: #f8fafc;
  border-radius: 6px; padding: 6px 12px;
  font-size: 14px; width: 220px;
  outline: none;
  border: 1.5px solid #e2e8f0;
}
.cvb-topbar .cv-title-input:focus { border-color: #00b14f; }
.cvb-colors { display: flex; gap: 6px; align-items: center; }
.cvb-color-dot {
  width: 22px; height: 22px; border-radius: 50%;
  cursor: pointer; border: 2.5px solid transparent;
  transition: transform 0.15s;
}
.cvb-color-dot:hover, .cvb-color-dot.active {
  border-color: #fff;
  outline: 2.5px solid currentColor;
  transform: scale(1.15);
}
.cvb-topbar .gap-auto { flex: 1; }
.btn-topcv { background: #00b14f; color: #fff; border: none; }
.btn-topcv:hover { background: #009140; color: #fff; }

/* ---- Builder layout ---- */
.cvb-layout {
  display: grid;
  grid-template-columns: 380px 1fr;
  min-height: calc(100vh - 56px);
}

/* ---- Left sidebar (form panel) ---- */
.cvb-panel {
  background: #fff;
  border-right: 1px solid #e2e8f0;
  overflow-y: auto;
  height: calc(100vh - 56px);
  position: sticky; top: 56px;
}
.cvb-panel-section {
  border-bottom: 1px solid #f1f5f9;
  padding: 0;
}
.cvb-section-header {
  display: flex; align-items: center; justify-content: space-between;
  padding: 13px 18px;
  cursor: pointer;
  font-weight: 700; font-size: 13px; color: #1e293b;
  background: #fff;
  user-select: none;
  transition: background 0.15s;
}
.cvb-section-header:hover { background: #f8fafc; }
.cvb-section-header .toggle-icon { color: #94a3b8; font-size: 11px; transition: transform 0.2s; }
.cvb-section-header.collapsed .toggle-icon { transform: rotate(-90deg); }
.cvb-section-body { padding: 4px 18px 16px; }
.cvb-section-body.collapsed { display: none; }

/* form inputs */
.cvb-label { font-size: 11.5px; font-weight: 600; color: #64748b; margin-bottom: 3px; }
.cvb-input {
  width: 100%; border: 1.5px solid #e2e8f0; border-radius: 7px;
  padding: 7px 10px; font-size: 13px; color: #1e293b;
  outline: none; transition: border-color 0.15s;
  background: #fff; resize: vertical;
}
.cvb-input:focus { border-color: #00b14f; }
.cvb-row { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
.cvb-row-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 6px; }

/* entry cards */
.cvb-entry-card {
  background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 9px;
  padding: 12px 14px; margin-bottom: 10px; position: relative;
}
.cvb-entry-card .cvb-del-btn {
  position: absolute; top: 10px; right: 10px;
  background: none; border: none; color: #94a3b8;
  cursor: pointer; font-size: 14px; line-height: 1;
  padding: 2px 4px;
}
.cvb-entry-card .cvb-del-btn:hover { color: #ef4444; }
.cvb-add-btn {
  width: 100%; border: 1.5px dashed #cbd5e1; border-radius: 7px;
  background: none; padding: 7px; font-size: 12.5px; font-weight: 600;
  color: #64748b; cursor: pointer; transition: all 0.15s; margin-top: 4px;
}
.cvb-add-btn:hover { border-color: #00b14f; color: #00b14f; background: #f0fdf4; }

/* skill slider row */
.skill-row {
  display: flex; align-items: center; gap: 8px; margin-bottom: 9px;
}
.skill-row input[type="text"] { flex: 1; }
.skill-row input[type="range"] { width: 80px; accent-color: #00b14f; }
.skill-row .skill-pct { font-size: 12px; color: #64748b; width: 32px; text-align: right; }

/* avatar upload area */
.avatar-upload-area {
  display: flex; flex-direction: column; align-items: center; gap: 8px;
  padding: 12px 0 6px;
}
.avatar-upload-area img {
  width: 88px; height: 88px; border-radius: 50%;
  object-fit: cover; border: 3px solid #00b14f;
}
.btn-avatar-change {
  font-size: 12px; color: #00b14f; background: none; border: none;
  cursor: pointer; text-decoration: underline;
}

/* ---- Right preview pane ---- */
.cvb-preview-pane {
  overflow-y: auto; padding: 28px 24px;
  display: flex; flex-direction: column; align-items: center;
}
.cvb-preview-label {
  font-size: 11.5px; font-weight: 700; color: #94a3b8;
  letter-spacing: 1.5px; text-transform: uppercase; margin-bottom: 12px;
}

/* ---- A4 Sheet ---- */
.cv-a4 {
  width: 794px;            /* 210mm @ 96dpi */
  min-height: 1123px;      /* 297mm @ 96dpi */
  background: #fff;
  box-shadow: 0 8px 32px rgba(0,0,0,0.12);
  display: flex;
  font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
  font-size: 13px; color: #1e293b;
  --cv-theme: #00b14f;
  position: relative;
  overflow: hidden;
}

/* sidebar column */
.cva-sidebar {
  width: 36%;
  background: #f8fafc;
  border-right: 1px solid #e2e8f0;
  padding: 28px 20px;
  display: flex; flex-direction: column; gap: 0;
}

/* main column */
.cva-main {
  width: 64%;
  padding: 28px 24px;
}

/* --- A4: Avatar --- */
.cva-avatar-wrap { text-align: center; margin-bottom: 20px; }
.cva-avatar-wrap img {
  width: 108px; height: 108px; border-radius: 50%;
  object-fit: cover;
  border: 4px solid var(--cv-theme);
  box-shadow: 0 3px 10px rgba(0,0,0,0.10);
}

/* --- A4: Section title --- */
.cva-section-title {
  font-size: 10.5px; font-weight: 800; text-transform: uppercase;
  letter-spacing: 1px; color: var(--cv-theme);
  border-bottom: 2px solid var(--cv-theme);
  padding-bottom: 3px; margin: 16px 0 8px;
}
.cva-section-title:first-of-type { margin-top: 0; }

/* --- A4: Contact items --- */
.cva-contact { display: flex; align-items: center; gap: 8px; margin-bottom: 7px; font-size: 11.5px; }
.cva-contact i { color: var(--cv-theme); width: 13px; text-align: center; }

/* --- A4: Skills --- */
.cva-skill { margin-bottom: 9px; }
.cva-skill-label { display: flex; justify-content: space-between; font-size: 11.5px; font-weight: 600; margin-bottom: 3px; }
.cva-skill-bar { height: 5px; background: #e2e8f0; border-radius: 3px; }
.cva-skill-fill { height: 100%; background: var(--cv-theme); border-radius: 3px; }

/* --- A4: Cert --- */
.cva-cert { margin-bottom: 8px; }
.cva-cert-name { font-size: 11.5px; font-weight: 700; color: #1e293b; }
.cva-cert-sub { font-size: 11px; color: #64748b; }

/* --- A4: Name / Title --- */
.cva-name {
  font-size: 22px; font-weight: 800; color: var(--cv-theme);
  text-transform: uppercase; line-height: 1.15; margin-bottom: 3px;
  letter-spacing: 0.3px;
}
.cva-jobtitle {
  font-size: 11px; font-weight: 600; color: #64748b;
  text-transform: uppercase; letter-spacing: 1.2px; margin-bottom: 18px;
}

/* --- A4: Content sections --- */
.cva-item { margin-bottom: 13px; page-break-inside: avoid; }
.cva-item-title { font-size: 13px; font-weight: 700; color: #1e293b; margin-bottom: 1px; }
.cva-item-sub { font-size: 12px; color: var(--cv-theme); font-weight: 600; margin-bottom: 1px; }
.cva-item-period { font-size: 11px; color: #94a3b8; margin-bottom: 4px; }
.cva-item-desc { font-size: 11.5px; color: #334155; line-height: 1.55; white-space: pre-wrap; }

/* Objective */
.cva-objective { font-size: 12px; color: #334155; line-height: 1.6; margin-bottom: 18px; }

/* Project role pill */
.cva-proj-role {
  display: inline-block; font-size: 10px; font-weight: 700;
  background: #f0fdf4; color: var(--cv-theme);
  border: 1px solid var(--cv-theme); border-radius: 20px;
  padding: 1px 8px; margin-bottom: 3px;
}

/* ---- Preset modal tweaks ---- */
.preset-card {
  border: 2px solid #e2e8f0; border-radius: 12px; padding: 16px;
  cursor: pointer; transition: all 0.2s;
}
.preset-card:hover { border-color: #00b14f; background: #f0fdf4; }

@media print {
  .cvb-topbar, .cvb-panel, .cvb-preview-label { display: none !important; }
  .cvb-layout { display: block; }
  .cvb-preview-pane { padding: 0; }
  .cv-a4 { box-shadow: none; width: 210mm; min-height: 297mm; }
}
</style>

<!-- TOP BAR -->
<div class="cvb-topbar">
  <a href="{{ route('jobs') }}" class="btn btn-sm btn-light border me-1" title="Quay lại">
    <i class="fa fa-arrow-left"></i>
  </a>
  <input type="text" id="cv_title" class="cv-title-input" value="{{ $cd['title'] }}" placeholder="Đặt tên CV...">

  <span class="small text-muted d-none d-md-inline">Màu chủ đạo:</span>
  <div class="cvb-colors">
    @php $colors = ['#00b14f'=>'TopCV Xanh','#1e40af'=>'Navy','#0ea5e9'=>'Sky Blue','#b91c1c'=>'Đỏ','#374151'=>'Đen','#ea580c'=>'Cam','#7c3aed'=>'Tím']; @endphp
    @foreach ($colors as $hex => $name)
      <span class="cvb-color-dot {{ $cd['theme_color'] == $hex ? 'active' : '' }}"
        data-color="{{ $hex }}" style="background:{{ $hex }};color:{{ $hex }}" title="{{ $name }}"></span>
    @endforeach
  </div>

  <span class="gap-auto"></span>

  <button class="btn btn-sm btn-outline-success fw-bold" data-bs-toggle="modal" data-bs-target="#presetModal">
    <i class="fa fa-magic me-1"></i> Mẫu Thực Chiến
  </button>
  @if(Auth::check())
    <a href="{{ route('cv.myResumes') }}" class="btn btn-sm btn-outline-secondary">
      <i class="fa fa-folder-open-o me-1"></i> CV của tôi
    </a>
  @endif
  <button id="btnSaveCv" class="btn btn-sm btn-topcv fw-bold px-3">
    <i class="fa fa-floppy-o me-1"></i> Lưu
  </button>
  <button id="btnExportPdf" class="btn btn-sm btn-primary px-3">
    <i class="fa fa-file-pdf-o me-1"></i> Xuất PDF
  </button>
</div>

<!-- BUILDER LAYOUT -->
<div class="cvb-layout">

  <!-- ========== LEFT PANEL (FORM) ========== -->
  <div class="cvb-panel">
    <input type="hidden" id="resume_id" value="{{ $cd['id'] }}">
    <input type="hidden" id="cv_theme_color" value="{{ $cd['theme_color'] }}">
    <input type="hidden" id="cv_avatar_url" value="{{ $cd['avatar'] }}">

    <!-- Avatar -->
    <div class="cvb-panel-section">
      <div class="cvb-section-header" data-target="sec-avatar">
        <span><i class="fa fa-user-circle-o me-2 text-success"></i>Ảnh đại diện</span>
        <i class="fa fa-chevron-down toggle-icon"></i>
      </div>
      <div class="cvb-section-body" id="sec-avatar">
        <div class="avatar-upload-area">
          <img src="{{ $cd['avatar'] }}" id="avatarPreview" alt="avatar">
          <label for="avatarFileInput" class="btn-avatar-change">
            <i class="fa fa-camera me-1"></i> Đổi ảnh
          </label>
          <input type="file" id="avatarFileInput" class="d-none" accept="image/*">
        </div>
      </div>
    </div>

    <!-- Thông tin cá nhân -->
    <div class="cvb-panel-section">
      <div class="cvb-section-header" data-target="sec-info">
        <span><i class="fa fa-id-card-o me-2 text-success"></i>Thông tin cá nhân</span>
        <i class="fa fa-chevron-down toggle-icon"></i>
      </div>
      <div class="cvb-section-body" id="sec-info">
        <div class="mb-2">
          <div class="cvb-label">Họ và tên <span class="text-danger">*</span></div>
          <input class="cvb-input" id="f_fullname" value="{{ $cd['fullname'] }}" placeholder="NGUYỄN VĂN A" data-target="p_fullname">
        </div>
        <div class="mb-2">
          <div class="cvb-label">Vị trí ứng tuyển</div>
          <input class="cvb-input" id="f_candidate_title" value="{{ $cd['candidate_title'] }}" placeholder="Senior Backend Developer" data-target="p_candidate_title">
        </div>
        <div class="cvb-row mb-2">
          <div>
            <div class="cvb-label">Email</div>
            <input class="cvb-input" id="f_email" value="{{ $cd['email'] }}" placeholder="email@gmail.com" data-target="p_email">
          </div>
          <div>
            <div class="cvb-label">Điện thoại</div>
            <input class="cvb-input" id="f_phone" value="{{ $cd['phone'] }}" placeholder="0912 345 678" data-target="p_phone">
          </div>
        </div>
        <div class="cvb-row mb-2">
          <div>
            <div class="cvb-label">Ngày sinh</div>
            <input class="cvb-input" id="f_birthday" value="{{ $cd['birthday'] }}" placeholder="01/01/1995" data-target="p_birthday">
          </div>
          <div>
            <div class="cvb-label">Giới tính</div>
            <input class="cvb-input" id="f_gender" value="{{ $cd['gender'] }}" placeholder="Nam / Nữ" data-target="p_gender">
          </div>
        </div>
        <div class="mb-2">
          <div class="cvb-label">Địa chỉ</div>
          <input class="cvb-input" id="f_address" value="{{ $cd['address'] }}" placeholder="Hà Nội / TP.HCM" data-target="p_address">
        </div>
        <div class="mb-2">
          <div class="cvb-label">Website / LinkedIn / GitHub</div>
          <input class="cvb-input" id="f_website" value="{{ $cd['website'] }}" placeholder="linkedin.com/in/..." data-target="p_website">
        </div>
      </div>
    </div>

    <!-- Mục tiêu -->
    <div class="cvb-panel-section">
      <div class="cvb-section-header" data-target="sec-obj">
        <span><i class="fa fa-bullseye me-2 text-success"></i>Mục tiêu nghề nghiệp</span>
        <i class="fa fa-chevron-down toggle-icon"></i>
      </div>
      <div class="cvb-section-body" id="sec-obj">
        <textarea class="cvb-input" id="f_objective" rows="4" placeholder="Mô tả ngắn về kinh nghiệm, thế mạnh và mục tiêu trong 1-2 năm tới..." data-target="p_objective">{{ $cd['objective'] }}</textarea>
      </div>
    </div>

    <!-- Kinh nghiệm -->
    <div class="cvb-panel-section">
      <div class="cvb-section-header" data-target="sec-exp">
        <span><i class="fa fa-briefcase me-2 text-success"></i>Kinh nghiệm làm việc</span>
        <i class="fa fa-chevron-down toggle-icon"></i>
      </div>
      <div class="cvb-section-body" id="sec-exp">
        <div id="exp_list">
          @foreach ($cd['experience'] as $i => $exp)
          <div class="cvb-entry-card" data-section="exp">
            <button type="button" class="cvb-del-btn" onclick="delCard(this)"><i class="fa fa-times"></i></button>
            <div class="mb-2">
              <div class="cvb-label">Vị trí</div>
              <input class="cvb-input exp-position" value="{{ $exp['position'] }}">
            </div>
            <div class="mb-2">
              <div class="cvb-label">Công ty</div>
              <input class="cvb-input exp-company" value="{{ $exp['company'] }}">
            </div>
            <div class="mb-2">
              <div class="cvb-label">Thời gian</div>
              <input class="cvb-input exp-period" value="{{ $exp['period'] }}" placeholder="01/2022 - Hiện tại">
            </div>
            <div>
              <div class="cvb-label">Mô tả công việc & Thành tích (áp dụng công thức XYZ: Đạt [X] đo bằng [Y] bằng cách [Z])</div>
              <textarea class="cvb-input exp-desc" rows="4">{{ $exp['description'] }}</textarea>
            </div>
          </div>
          @endforeach
        </div>
        <button class="cvb-add-btn" onclick="addCard('exp')"><i class="fa fa-plus me-1"></i> Thêm kinh nghiệm</button>
      </div>
    </div>

    <!-- Học vấn -->
    <div class="cvb-panel-section">
      <div class="cvb-section-header" data-target="sec-edu">
        <span><i class="fa fa-graduation-cap me-2 text-success"></i>Học vấn</span>
        <i class="fa fa-chevron-down toggle-icon"></i>
      </div>
      <div class="cvb-section-body" id="sec-edu">
        <div id="edu_list">
          @foreach ($cd['education'] as $edu)
          <div class="cvb-entry-card" data-section="edu">
            <button type="button" class="cvb-del-btn" onclick="delCard(this)"><i class="fa fa-times"></i></button>
            <div class="mb-2">
              <div class="cvb-label">Trường</div>
              <input class="cvb-input edu-school" value="{{ $edu['school'] }}">
            </div>
            <div class="mb-2">
              <div class="cvb-label">Chuyên ngành</div>
              <input class="cvb-input edu-major" value="{{ $edu['major'] }}">
            </div>
            <div class="mb-2">
              <div class="cvb-label">Năm học</div>
              <input class="cvb-input edu-period" value="{{ $edu['period'] }}" placeholder="2018 - 2022">
            </div>
            <div>
              <div class="cvb-label">Mô tả</div>
              <textarea class="cvb-input edu-desc" rows="2">{{ $edu['description'] }}</textarea>
            </div>
          </div>
          @endforeach
        </div>
        <button class="cvb-add-btn" onclick="addCard('edu')"><i class="fa fa-plus me-1"></i> Thêm học vấn</button>
      </div>
    </div>

    <!-- Kỹ năng -->
    <div class="cvb-panel-section">
      <div class="cvb-section-header" data-target="sec-skill">
        <span><i class="fa fa-star-o me-2 text-success"></i>Kỹ năng</span>
        <i class="fa fa-chevron-down toggle-icon"></i>
      </div>
      <div class="cvb-section-body" id="sec-skill">
        <div id="skill_list">
          @foreach ($cd['skills'] as $sk)
          @php $lvl = intval($sk['level'] ?? 80); @endphp
          <div class="skill-row">
            <input class="cvb-input skill-name" style="flex:1" value="{{ $sk['name'] }}">
            <input type="range" class="skill-range" min="10" max="100" value="{{ $lvl }}"
              oninput="this.nextElementSibling.textContent=this.value+'%'; renderPreview()">
            <span class="skill-pct">{{ $lvl }}%</span>
            <button type="button" class="cvb-del-btn" style="position:static;margin-left:2px" onclick="this.closest('.skill-row').remove(); renderPreview()">
              <i class="fa fa-times"></i>
            </button>
          </div>
          @endforeach
        </div>
        <button class="cvb-add-btn" onclick="addSkill()"><i class="fa fa-plus me-1"></i> Thêm kỹ năng</button>
      </div>
    </div>

    <!-- Dự án -->
    <div class="cvb-panel-section">
      <div class="cvb-section-header" data-target="sec-proj">
        <span><i class="fa fa-code me-2 text-success"></i>Dự án nổi bật</span>
        <i class="fa fa-chevron-down toggle-icon"></i>
      </div>
      <div class="cvb-section-body" id="sec-proj">
        <div id="proj_list">
          @foreach ($cd['projects'] as $pr)
          <div class="cvb-entry-card" data-section="proj">
            <button type="button" class="cvb-del-btn" onclick="delCard(this)"><i class="fa fa-times"></i></button>
            <div class="mb-2">
              <div class="cvb-label">Tên dự án</div>
              <input class="cvb-input proj-name" value="{{ $pr['name'] }}">
            </div>
            <div class="cvb-row mb-2">
              <div>
                <div class="cvb-label">Vai trò</div>
                <input class="cvb-input proj-role" value="{{ $pr['role'] }}">
              </div>
              <div>
                <div class="cvb-label">Thời gian</div>
                <input class="cvb-input proj-period" value="{{ $pr['period'] }}">
              </div>
            </div>
            <div>
              <div class="cvb-label">Mô tả</div>
              <textarea class="cvb-input proj-desc" rows="3">{{ $pr['description'] }}</textarea>
            </div>
          </div>
          @endforeach
        </div>
        <button class="cvb-add-btn" onclick="addCard('proj')"><i class="fa fa-plus me-1"></i> Thêm dự án</button>
      </div>
    </div>

    <!-- Chứng chỉ -->
    <div class="cvb-panel-section">
      <div class="cvb-section-header" data-target="sec-cert">
        <span><i class="fa fa-certificate me-2 text-success"></i>Chứng chỉ & Giải thưởng</span>
        <i class="fa fa-chevron-down toggle-icon"></i>
      </div>
      <div class="cvb-section-body" id="sec-cert">
        <div id="cert_list">
          @foreach ($cd['certifications'] as $cr)
          <div class="cvb-entry-card" data-section="cert">
            <button type="button" class="cvb-del-btn" onclick="delCard(this)"><i class="fa fa-times"></i></button>
            <div class="mb-2">
              <div class="cvb-label">Tên chứng chỉ</div>
              <input class="cvb-input cert-name" value="{{ $cr['name'] }}">
            </div>
            <div class="cvb-row">
              <div>
                <div class="cvb-label">Tổ chức cấp</div>
                <input class="cvb-input cert-org" value="{{ $cr['organization'] }}">
              </div>
              <div>
                <div class="cvb-label">Năm</div>
                <input class="cvb-input cert-year" value="{{ $cr['year'] }}" placeholder="2024">
              </div>
            </div>
          </div>
          @endforeach
        </div>
        <button class="cvb-add-btn" onclick="addCard('cert')"><i class="fa fa-plus me-1"></i> Thêm chứng chỉ</button>
      </div>
    </div>
  </div><!-- /cvb-panel -->

  <!-- ========== RIGHT PREVIEW ========== -->
  <div class="cvb-preview-pane">
    <div class="cvb-preview-label">Xem trước A4 · Cập nhật tự động</div>

    <!-- A4 Sheet -->
    <div class="cv-a4" id="cvA4">
      <!-- SIDEBAR -->
      <div class="cva-sidebar">
        <div class="cva-avatar-wrap">
          <img id="p_avatar" src="{{ $cd['avatar'] }}" alt="avatar">
        </div>

        <div class="cva-section-title">Thông tin liên hệ</div>
        <div class="cva-contact"><i class="fa fa-envelope"></i><span id="p_email">{{ $cd['email'] }}</span></div>
        <div class="cva-contact"><i class="fa fa-phone"></i><span id="p_phone">{{ $cd['phone'] }}</span></div>
        <div class="cva-contact"><i class="fa fa-calendar"></i><span id="p_birthday">{{ $cd['birthday'] }}</span></div>
        <div class="cva-contact"><i class="fa fa-user"></i><span id="p_gender">{{ $cd['gender'] }}</span></div>
        <div class="cva-contact"><i class="fa fa-map-marker"></i><span id="p_address">{{ $cd['address'] }}</span></div>
        <div class="cva-contact"><i class="fa fa-globe"></i><span id="p_website">{{ $cd['website'] }}</span></div>

        <div class="cva-section-title" id="p_skills_section">Kỹ năng</div>
        <div id="p_skills">
          @foreach ($cd['skills'] as $sk)
          @php $lvl = intval($sk['level'] ?? 80); @endphp
          <div class="cva-skill">
            <div class="cva-skill-label"><span>{{ $sk['name'] }}</span><span>{{ $lvl }}%</span></div>
            <div class="cva-skill-bar"><div class="cva-skill-fill" style="width:{{ $lvl }}%"></div></div>
          </div>
          @endforeach
        </div>

        <div class="cva-section-title" id="p_certs_section">Chứng chỉ</div>
        <div id="p_certs">
          @foreach ($cd['certifications'] as $cr)
          <div class="cva-cert">
            <div class="cva-cert-name">{{ $cr['name'] }}</div>
            <div class="cva-cert-sub">{{ $cr['organization'] }} · {{ $cr['year'] }}</div>
          </div>
          @endforeach
        </div>
      </div>

      <!-- MAIN COLUMN -->
      <div class="cva-main">
        <div id="p_fullname" class="cva-name">{{ $cd['fullname'] }}</div>
        <div id="p_candidate_title" class="cva-jobtitle">{{ $cd['candidate_title'] }}</div>

        <div id="p_objective" class="cva-objective">{{ $cd['objective'] }}</div>

        <div class="cva-section-title">Kinh nghiệm làm việc</div>
        <div id="p_experience">
          @foreach ($cd['experience'] as $exp)
          <div class="cva-item">
            <div class="cva-item-title">{{ $exp['position'] }}</div>
            <div class="cva-item-sub">{{ $exp['company'] }}</div>
            <div class="cva-item-period"><i class="fa fa-clock-o me-1"></i>{{ $exp['period'] }}</div>
            <div class="cva-item-desc">{{ $exp['description'] }}</div>
          </div>
          @endforeach
        </div>

        <div class="cva-section-title">Trình độ học vấn</div>
        <div id="p_education">
          @foreach ($cd['education'] as $edu)
          <div class="cva-item">
            <div class="cva-item-title">{{ $edu['school'] }}</div>
            <div class="cva-item-sub">{{ $edu['major'] }}</div>
            <div class="cva-item-period"><i class="fa fa-clock-o me-1"></i>{{ $edu['period'] }}</div>
            <div class="cva-item-desc">{{ $edu['description'] }}</div>
          </div>
          @endforeach
        </div>

        <div class="cva-section-title">Dự án nổi bật</div>
        <div id="p_projects">
          @foreach ($cd['projects'] as $pr)
          <div class="cva-item">
            <div class="cva-item-title">{{ $pr['name'] }}</div>
            <span class="cva-proj-role">{{ $pr['role'] }}</span>
            <span style="font-size:11px;color:#94a3b8;margin-left:6px">{{ $pr['period'] }}</span>
            <div class="cva-item-desc" style="margin-top:3px">{{ $pr['description'] }}</div>
          </div>
          @endforeach
        </div>
      </div>
    </div><!-- /cv-a4 -->
  </div><!-- /cvb-preview-pane -->
</div><!-- /cvb-layout -->

<!-- ============================================================
     PRESET MODAL
============================================================ -->
<div class="modal fade" id="presetModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0" style="border-radius:14px; box-shadow:0 16px 48px rgba(0,0,0,0.14);">
      <div class="modal-header border-0 pb-0 pt-4 px-4">
        <div>
          <h5 class="fw-bold mb-1"><i class="fa fa-magic text-success me-2"></i>Mẫu nội dung thực chiến chuẩn TopCV</h5>
          <p class="text-muted small mb-0">Viết theo công thức <strong>Google XYZ + STAR</strong> · Có số liệu cụ thể · Không sáo rỗng</p>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body px-4 py-3">
        <div class="row g-3">
          <div class="col-md-4">
            <div class="preset-card" onclick="applyPreset('it')">
              <span class="badge bg-success mb-2">IT / Công nghệ</span>
              <h6 class="fw-bold mb-1">Senior Backend Developer</h6>
              <p class="text-muted small mb-3">PHP · Laravel · MySQL · Redis · 25.000 CCU · Giảm API 6x</p>
              <button type="button" class="btn btn-sm btn-outline-success w-100 fw-bold">Nạp mẫu này</button>
            </div>
          </div>
          <div class="col-md-4">
            <div class="preset-card" onclick="applyPreset('marketing')">
              <span class="badge bg-primary mb-2">Marketing</span>
              <h6 class="fw-bold mb-1">Performance Marketing Specialist</h6>
              <p class="text-muted small mb-3">Meta · Google Ads · TikTok · ROAS 4.2 · 350tr ngân sách</p>
              <button type="button" class="btn btn-sm btn-outline-primary w-100 fw-bold">Nạp mẫu này</button>
            </div>
          </div>
          <div class="col-md-4">
            <div class="preset-card" onclick="applyPreset('sales')">
              <span class="badge bg-warning text-dark mb-2">Kinh doanh B2B</span>
              <h6 class="fw-bold mb-1">Senior B2B Sales</h6>
              <p class="text-muted small mb-3">130% KPI · 3.2 tỷ doanh số · Chốt deal 38% · 80+ khách</p>
              <button type="button" class="btn btn-sm btn-outline-warning text-dark w-100 fw-bold">Nạp mẫu này</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@section('customJs')
<script>
/* ============================================================
   DATA — preset content
============================================================ */
const PRESETS = {
  it: {
    title:"CV Kỹ Sư Phần Mềm - Nguyễn Văn A", fullname:"NGUYỄN VĂN A",
    candidate_title:"SENIOR BACKEND DEVELOPER (PHP / LARAVEL)", theme_color:"#00b14f",
    objective:"Kỹ sư phần mềm với 3 năm kinh nghiệm thực chiến phát triển hệ thống web quy mô lớn bằng Laravel, MySQL và Vue.js. Đã tối ưu hạ tầng chịu tải 25.000 CCU, giảm 75% thời gian phản hồi API. Mục tiêu trong 2 năm tới: đảm nhiệm Technical Lead, dẫn dắt kiến trúc module cốt lõi nền tảng SaaS.",
    experience:[{
      position:"Senior Backend Developer (PHP / Laravel)", company:"Công ty Cổ phần Công nghệ ABC",
      period:"03/2022 - Hiện tại",
      description:"• Thiết kế kiến trúc DB và triển khai 30+ Core API cho sàn TMĐT phục vụ 120.000 MAU.\n• Tối ưu SQL + Redis Caching → giảm API từ 750ms → 120ms (tăng tốc 6 lần).\n• Tích hợp VNPAY, VietQR, GHN, Viettel Post — xử lý 8.000 giao dịch/ngày, uptime 99.9%.\n• Code Review + hướng dẫn Clean Code + PHPUnit (80% coverage) cho 4 Junior."
    },{
      position:"Web Developer (Fullstack)", company:"Công ty TNHH Giải pháp Phần mềm XYZ",
      period:"06/2020 - 02/2022",
      description:"• Xây dựng 5+ cổng thông tin và web bán hàng bằng Laravel, MySQL, jQuery/Bootstrap.\n• Tái cấu trúc CSDL kho/đơn hàng — xử lý 1.000+ đơn đồng thời không còn lỗi thắt cổ chai.\n• Phối hợp UI/UX chuyển đổi Figma → responsive chuẩn pixel mọi thiết bị."
    }],
    education:[{school:"Đại học Bách Khoa Hà Nội",major:"Công nghệ Thông tin & Kỹ thuật Phần mềm",period:"2016 - 2020",description:"Tốt nghiệp loại Giỏi (GPA 3.45/4.0) · Giải Nhì Olympic Tin học Sinh viên cấp Viện 2019."}],
    skills:[{name:"PHP / Laravel Framework",level:"95"},{name:"MySQL / Query Optimization",level:"90"},{name:"RESTful API / Microservices",level:"85"},{name:"Redis / Queue / Docker",level:"80"},{name:"Vue.js / Bootstrap / Git",level:"80"}],
    projects:[{name:"Nền tảng Tuyển dụng & Matching ứng viên thông minh",role:"Technical Lead",period:"01/2023 - 09/2023",description:"Chủ trì kiến trúc hệ thống 50.000 ứng viên + 1.500 doanh nghiệp. CV Builder xuất PDF < 1.5s. Thuật toán lọc tự động giảm 60% thời gian sàng lọc."}],
    certifications:[{name:"Laravel Certified Developer",organization:"Laravel LLC",year:"2022"},{name:"TOEIC 780/990",organization:"IIG Việt Nam",year:"2021"}]
  },
  marketing: {
    title:"CV Performance Marketing - Trần Thị B", fullname:"TRẦN THỊ B",
    candidate_title:"PERFORMANCE MARKETING & GROWTH SPECIALIST", theme_color:"#1e40af",
    objective:"Chuyên viên Marketing 3 năm kinh nghiệm hoạch định và thực thi chiến dịch Performance đa kênh (Facebook, Google, TikTok). Quản lý ngân sách >300 triệu/tháng, ROAS trung bình 3.8. Mục tiêu: dẫn dắt Growth Marketing team, tối ưu CAC và mở kênh tiếp cận mới.",
    experience:[{
      position:"Senior Performance Marketing", company:"Tập đoàn E-Retail",
      period:"04/2022 - Hiện tại",
      description:"• Quản lý 250-400 triệu/tháng ngân sách trên Meta Ads, Google Search/Shopping, TikTok Ads.\n• Chiến dịch Q4/2023: 4.5 tỷ doanh thu, ROAS 4.2 (vượt 25% KPI).\n• A/B testing hàng tuần → CPA từ 110.000đ → 72.000đ (tiết kiệm 34%).\n• Thiết lập GA4 + GTM + Meta Pixel — tracking accuracy 98%."
    },{
      position:"Digital Marketing Executive", company:"Digital Agency MediaMax",
      period:"07/2020 - 03/2022",
      description:"• Quản lý chiến dịch cho 10+ khách hàng (Giáo dục, F&B, BĐS).\n• SEO On/Off-page: 45+ từ khóa Top 3 Google, Organic Traffic tăng 180%.\n• TikTok: 5.2 triệu lượt xem tự nhiên trong 3 tháng."
    }],
    education:[{school:"Đại học Kinh tế Quốc dân (NEU)",major:"Marketing & Quản trị Kinh doanh",period:"2016 - 2020",description:"Tốt nghiệp loại Giỏi (GPA 3.5/4.0) · Đội trưởng CLB Marketing NEU 2018-2019."}],
    skills:[{name:"Meta Ads (Facebook/Instagram)",level:"95"},{name:"Google Ads (Search, Shopping)",level:"90"},{name:"Google Analytics 4 / GTM",level:"85"},{name:"TikTok Ads & KOC",level:"80"},{name:"Phân tích dữ liệu & CRO",level:"80"}],
    projects:[{name:"Mega Sale Mùa Hè 2023",role:"Campaign Leader",period:"05/2023-07/2023",description:"18.000 đơn thành công · Tiếp cận 2.5 triệu user · Tiết kiệm 15% ngân sách so với năm trước."}],
    certifications:[{name:"Google Ads Search & Measurement",organization:"Google Skillshop",year:"2023"},{name:"Meta Certified Digital Marketing Associate",organization:"Meta",year:"2022"}]
  },
  sales: {
    title:"CV B2B Sales - Lê Hoàng C", fullname:"LÊ HOÀNG C",
    candidate_title:"B2B ACCOUNT EXECUTIVE / SENIOR SALES", theme_color:"#ea580c",
    objective:"Chuyên viên B2B Sales 4 năm kinh nghiệm mảng giải pháp công nghệ SaaS. Kỹ năng đàm phán và chốt hợp đồng lớn. Đạt 130% KPI doanh số 2023: 3.2 tỷ đồng. Mục tiêu: trở thành Sales Manager dẫn dắt đội 5-8 người.",
    experience:[{
      position:"Senior Account Executive (B2B Sales)", company:"Công ty CP Giải pháp SaaS Global",
      period:"01/2022 - Hiện tại",
      description:"• Đàm phán hợp đồng phần mềm cho 40+ doanh nghiệp 50-500 nhân sự.\n• Doanh số 2023: 3.2 tỷ (đạt 130% KPI) · Best Seller Q2 & Q4.\n• Duy trì 80+ giám đốc trong network · Retention Rate 92%.\n• Tỷ lệ chuyển đổi Demo → Ký hợp đồng: 38% (ngành 22%)."
    },{
      position:"B2B Sales Executive", company:"Công ty TNHH Thương mại Alpha",
      period:"08/2019 - 12/2021",
      description:"• Khai thác khách hàng B2B mới qua cold calling, email, hội thảo xúc tiến.\n• Ký 25+ hợp đồng dịch vụ 60-150 triệu/hợp đồng.\n• Chuẩn hóa Sales Script → rút ngắn Sales Cycle từ 45 ngày → 25 ngày."
    }],
    education:[{school:"Đại học Thương Mại",major:"Thương mại Quốc tế & Marketing",period:"2015 - 2019",description:"Tốt nghiệp loại Khá · Tích cực kết nối doanh nghiệp-sinh viên."}],
    skills:[{name:"Đàm phán & Thuyết trình giải pháp B2B",level:"95"},{name:"CRM: HubSpot, Salesforce",level:"90"},{name:"Xây dựng phễu bán hàng & Xử lý từ chối",level:"90"},{name:"Nghiên cứu thị trường & Phân tích đối thủ",level:"85"},{name:"Tiếng Anh thương mại",level:"80"}],
    projects:[{name:"Triển khai phần mềm chuỗi 30 cửa hàng",role:"Key Account Manager",period:"03/2023-07/2023",description:"Hợp đồng 450 triệu · Triển khai 3 tháng · Khách hàng tiết kiệm 20% chi phí vận hành."}],
    certifications:[{name:"Chứng chỉ Quản trị Bán hàng B2B",organization:"Học viện PACE",year:"2022"},{name:"HubSpot Inbound Sales Certification",organization:"HubSpot Academy",year:"2021"}]
  }
};

/* ============================================================
   RENDER PREVIEW — rebuild the A4 DOM from form inputs
============================================================ */
function renderPreview() {
  const theme = document.getElementById('cv_theme_color').value || '#00b14f';
  document.getElementById('cvA4').style.setProperty('--cv-theme', theme);

  // Simple text fields
  const textMap = {f_fullname:'p_fullname', f_candidate_title:'p_candidate_title',
    f_email:'p_email', f_phone:'p_phone', f_birthday:'p_birthday',
    f_gender:'p_gender', f_address:'p_address', f_website:'p_website', f_objective:'p_objective'};
  Object.entries(textMap).forEach(([fid, pid]) => {
    const el = document.getElementById(pid);
    if (el) el.textContent = (document.getElementById(fid)||{}).value || '';
  });

  // Experience
  let expHtml = '';
  document.querySelectorAll('#exp_list .cvb-entry-card').forEach(card => {
    const pos  = card.querySelector('.exp-position')?.value  || '';
    const comp = card.querySelector('.exp-company')?.value   || '';
    const per  = card.querySelector('.exp-period')?.value    || '';
    const desc = card.querySelector('.exp-desc')?.value      || '';
    expHtml += `<div class="cva-item">
      <div class="cva-item-title">${esc(pos)}</div>
      <div class="cva-item-sub">${esc(comp)}</div>
      <div class="cva-item-period"><i class="fa fa-clock-o me-1"></i>${esc(per)}</div>
      <div class="cva-item-desc">${esc(desc)}</div>
    </div>`;
  });
  document.getElementById('p_experience').innerHTML = expHtml;

  // Education
  let eduHtml = '';
  document.querySelectorAll('#edu_list .cvb-entry-card').forEach(card => {
    const sch  = card.querySelector('.edu-school')?.value  || '';
    const maj  = card.querySelector('.edu-major')?.value   || '';
    const per  = card.querySelector('.edu-period')?.value  || '';
    const desc = card.querySelector('.edu-desc')?.value    || '';
    eduHtml += `<div class="cva-item">
      <div class="cva-item-title">${esc(sch)}</div>
      <div class="cva-item-sub">${esc(maj)}</div>
      <div class="cva-item-period"><i class="fa fa-clock-o me-1"></i>${esc(per)}</div>
      <div class="cva-item-desc">${esc(desc)}</div>
    </div>`;
  });
  document.getElementById('p_education').innerHTML = eduHtml;

  // Skills
  let skHtml = '';
  document.querySelectorAll('#skill_list .skill-row').forEach(row => {
    const name = row.querySelector('.skill-name')?.value   || '';
    const lvl  = row.querySelector('.skill-range')?.value  || '80';
    skHtml += `<div class="cva-skill">
      <div class="cva-skill-label"><span>${esc(name)}</span><span>${lvl}%</span></div>
      <div class="cva-skill-bar"><div class="cva-skill-fill" style="width:${lvl}%"></div></div>
    </div>`;
  });
  document.getElementById('p_skills').innerHTML = skHtml;

  // Projects
  let prHtml = '';
  document.querySelectorAll('#proj_list .cvb-entry-card').forEach(card => {
    const name = card.querySelector('.proj-name')?.value   || '';
    const role = card.querySelector('.proj-role')?.value   || '';
    const per  = card.querySelector('.proj-period')?.value || '';
    const desc = card.querySelector('.proj-desc')?.value   || '';
    prHtml += `<div class="cva-item">
      <div class="cva-item-title">${esc(name)}</div>
      <span class="cva-proj-role">${esc(role)}</span>
      <span style="font-size:11px;color:#94a3b8;margin-left:6px">${esc(per)}</span>
      <div class="cva-item-desc" style="margin-top:3px">${esc(desc)}</div>
    </div>`;
  });
  document.getElementById('p_projects').innerHTML = prHtml;

  // Certifications
  let crHtml = '';
  document.querySelectorAll('#cert_list .cvb-entry-card').forEach(card => {
    const name = card.querySelector('.cert-name')?.value || '';
    const org  = card.querySelector('.cert-org')?.value  || '';
    const yr   = card.querySelector('.cert-year')?.value || '';
    crHtml += `<div class="cva-cert">
      <div class="cva-cert-name">${esc(name)}</div>
      <div class="cva-cert-sub">${esc(org)} · ${esc(yr)}</div>
    </div>`;
  });
  document.getElementById('p_certs').innerHTML = crHtml;
}

function esc(str) {
  return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

/* ============================================================
   SECTION COLLAPSE
============================================================ */
document.querySelectorAll('.cvb-section-header').forEach(header => {
  header.addEventListener('click', () => {
    const target = document.getElementById(header.dataset.target);
    const isCollapsed = target.classList.toggle('collapsed');
    header.classList.toggle('collapsed', isCollapsed);
  });
});

/* ============================================================
   COLOR SWITCHER
============================================================ */
document.querySelectorAll('.cvb-color-dot').forEach(dot => {
  dot.addEventListener('click', () => {
    document.querySelectorAll('.cvb-color-dot').forEach(d => d.classList.remove('active'));
    dot.classList.add('active');
    const c = dot.dataset.color;
    document.getElementById('cv_theme_color').value = c;
    renderPreview();
  });
});

/* ============================================================
   AVATAR UPLOAD
============================================================ */
document.getElementById('avatarFileInput').addEventListener('change', function() {
  const file = this.files[0];
  if (!file) return;
  const fd = new FormData();
  fd.append('avatar_file', file);
  fd.append('_token', '{{ csrf_token() }}');
  toastr.info('Đang tải ảnh...');
  $.ajax({
    url: '{{ route("cv.uploadAvatar") }}', type: 'POST', data: fd,
    contentType: false, processData: false,
    success(res) {
      if (res.status) {
        document.getElementById('avatarPreview').src = res.avatar_url;
        document.getElementById('p_avatar').src = res.avatar_url;
        document.getElementById('cv_avatar_url').value = res.avatar_url;
        toastr.success(res.message);
      } else { toastr.error(res.message); }
    },
    error() { toastr.error('Không thể tải ảnh lên.'); }
  });
});

/* ============================================================
   ADD / DELETE CARDS
============================================================ */
function delCard(btn) {
  btn.closest('.cvb-entry-card').remove();
  renderPreview();
}

function addCard(type) {
  let html = '';
  if (type === 'exp') {
    html = `<div class="cvb-entry-card" data-section="exp">
      <button type="button" class="cvb-del-btn" onclick="delCard(this)"><i class="fa fa-times"></i></button>
      <div class="mb-2"><div class="cvb-label">Vị trí</div><input class="cvb-input exp-position" placeholder="Senior Developer"></div>
      <div class="mb-2"><div class="cvb-label">Công ty</div><input class="cvb-input exp-company" placeholder="Tên công ty"></div>
      <div class="mb-2"><div class="cvb-label">Thời gian</div><input class="cvb-input exp-period" placeholder="01/2023 - Hiện tại"></div>
      <div><div class="cvb-label">Mô tả & Thành tích</div><textarea class="cvb-input exp-desc" rows="4" placeholder="• Mô tả bằng công thức XYZ..."></textarea></div>
    </div>`;
    document.getElementById('exp_list').insertAdjacentHTML('beforeend', html);
  } else if (type === 'edu') {
    html = `<div class="cvb-entry-card" data-section="edu">
      <button type="button" class="cvb-del-btn" onclick="delCard(this)"><i class="fa fa-times"></i></button>
      <div class="mb-2"><div class="cvb-label">Trường</div><input class="cvb-input edu-school" placeholder="Đại học Bách Khoa Hà Nội"></div>
      <div class="mb-2"><div class="cvb-label">Chuyên ngành</div><input class="cvb-input edu-major" placeholder="Công nghệ Thông tin"></div>
      <div class="mb-2"><div class="cvb-label">Năm học</div><input class="cvb-input edu-period" placeholder="2018 - 2022"></div>
      <div><div class="cvb-label">Mô tả</div><textarea class="cvb-input edu-desc" rows="2" placeholder="Tốt nghiệp loại..."></textarea></div>
    </div>`;
    document.getElementById('edu_list').insertAdjacentHTML('beforeend', html);
  } else if (type === 'proj') {
    html = `<div class="cvb-entry-card" data-section="proj">
      <button type="button" class="cvb-del-btn" onclick="delCard(this)"><i class="fa fa-times"></i></button>
      <div class="mb-2"><div class="cvb-label">Tên dự án</div><input class="cvb-input proj-name" placeholder="Tên dự án"></div>
      <div class="cvb-row mb-2">
        <div><div class="cvb-label">Vai trò</div><input class="cvb-input proj-role" placeholder="Tech Lead"></div>
        <div><div class="cvb-label">Thời gian</div><input class="cvb-input proj-period" placeholder="2023"></div>
      </div>
      <div><div class="cvb-label">Mô tả</div><textarea class="cvb-input proj-desc" rows="3" placeholder="Mô tả dự án..."></textarea></div>
    </div>`;
    document.getElementById('proj_list').insertAdjacentHTML('beforeend', html);
  } else if (type === 'cert') {
    html = `<div class="cvb-entry-card" data-section="cert">
      <button type="button" class="cvb-del-btn" onclick="delCard(this)"><i class="fa fa-times"></i></button>
      <div class="mb-2"><div class="cvb-label">Tên chứng chỉ</div><input class="cvb-input cert-name" placeholder="Laravel Certified"></div>
      <div class="cvb-row">
        <div><div class="cvb-label">Tổ chức</div><input class="cvb-input cert-org" placeholder="Laravel LLC"></div>
        <div><div class="cvb-label">Năm</div><input class="cvb-input cert-year" placeholder="2024"></div>
      </div>
    </div>`;
    document.getElementById('cert_list').insertAdjacentHTML('beforeend', html);
  }
  bindInputEvents();
  renderPreview();
}

function addSkill() {
  const row = `<div class="skill-row">
    <input class="cvb-input skill-name" style="flex:1" placeholder="Tên kỹ năng">
    <input type="range" class="skill-range" min="10" max="100" value="80"
      oninput="this.nextElementSibling.textContent=this.value+'%'; renderPreview()">
    <span class="skill-pct">80%</span>
    <button type="button" class="cvb-del-btn" style="position:static;margin-left:2px"
      onclick="this.closest('.skill-row').remove(); renderPreview()"><i class="fa fa-times"></i></button>
  </div>`;
  document.getElementById('skill_list').insertAdjacentHTML('beforeend', row);
  bindInputEvents();
  renderPreview();
}

/* ============================================================
   BIND EVENTS — re-attach after dynamic add
============================================================ */
function bindInputEvents() {
  document.querySelectorAll('.cvb-input, .skill-range').forEach(el => {
    el.removeEventListener('input', renderPreview);
    el.addEventListener('input', renderPreview);
  });
}
bindInputEvents();

/* ============================================================
   COLLECT DATA for save
============================================================ */
function collectData() {
  const experiences = [...document.querySelectorAll('#exp_list .cvb-entry-card')].map(c => ({
    position:    c.querySelector('.exp-position')?.value || '',
    company:     c.querySelector('.exp-company')?.value  || '',
    period:      c.querySelector('.exp-period')?.value   || '',
    description: c.querySelector('.exp-desc')?.value     || ''
  }));
  const educations = [...document.querySelectorAll('#edu_list .cvb-entry-card')].map(c => ({
    school:      c.querySelector('.edu-school')?.value || '',
    major:       c.querySelector('.edu-major')?.value  || '',
    period:      c.querySelector('.edu-period')?.value || '',
    description: c.querySelector('.edu-desc')?.value   || ''
  }));
  const skills = [...document.querySelectorAll('#skill_list .skill-row')].map(r => ({
    name:  r.querySelector('.skill-name')?.value  || '',
    level: r.querySelector('.skill-range')?.value || '80'
  }));
  const projects = [...document.querySelectorAll('#proj_list .cvb-entry-card')].map(c => ({
    name:        c.querySelector('.proj-name')?.value   || '',
    role:        c.querySelector('.proj-role')?.value   || '',
    period:      c.querySelector('.proj-period')?.value || '',
    description: c.querySelector('.proj-desc')?.value  || ''
  }));
  const certifications = [...document.querySelectorAll('#cert_list .cvb-entry-card')].map(c => ({
    name:         c.querySelector('.cert-name')?.value || '',
    organization: c.querySelector('.cert-org')?.value  || '',
    year:         c.querySelector('.cert-year')?.value || ''
  }));

  return {
    resume_id:          document.getElementById('resume_id').value,
    title:              document.getElementById('cv_title').value.trim() || 'CV chưa đặt tên',
    fullname:           document.getElementById('f_fullname').value.trim(),
    candidate_title:    document.getElementById('f_candidate_title').value.trim(),
    email:              document.getElementById('f_email').value.trim(),
    phone:              document.getElementById('f_phone').value.trim(),
    birthday:           document.getElementById('f_birthday').value.trim(),
    gender:             document.getElementById('f_gender').value.trim(),
    address:            document.getElementById('f_address').value.trim(),
    website:            document.getElementById('f_website').value.trim(),
    avatar:             document.getElementById('cv_avatar_url').value,
    theme_color:        document.getElementById('cv_theme_color').value || '#00b14f',
    template:           'modern',
    objective:          document.getElementById('f_objective').value.trim(),
    experience_data:    JSON.stringify(experiences),
    education_data:     JSON.stringify(educations),
    skills_data:        JSON.stringify(skills),
    projects_data:      JSON.stringify(projects),
    certifications_data:JSON.stringify(certifications),
    _token:             '{{ csrf_token() }}'
  };
}

/* ============================================================
   SAVE CV
============================================================ */
document.getElementById('btnSaveCv').addEventListener('click', function() {
  @if (!Auth::check())
    toastr.warning('Vui lòng đăng nhập để lưu CV!');
    setTimeout(() => window.location.href = '{{ route("account.login") }}', 1200);
    return;
  @endif

  const btn = this;
  btn.disabled = true;
  btn.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i> Đang lưu...';

  $.ajax({
    url: '{{ route("cv.save") }}', type: 'POST',
    data: collectData(),
    success(res) {
      btn.disabled = false;
      btn.innerHTML = '<i class="fa fa-floppy-o me-1"></i> Lưu';
      if (res.status) {
        document.getElementById('resume_id').value = res.resume_id;
        toastr.success(res.message || 'Lưu CV thành công!');
      } else {
        if (res.require_login) { window.location.href = '{{ route("account.login") }}'; }
        else { toastr.error('Vui lòng kiểm tra lại.'); }
      }
    },
    error() {
      btn.disabled = false;
      btn.innerHTML = '<i class="fa fa-floppy-o me-1"></i> Lưu';
      toastr.error('Lỗi kết nối. Thử lại nhé!');
    }
  });
});

/* ============================================================
   EXPORT PDF
============================================================ */
document.getElementById('btnExportPdf').addEventListener('click', function() {
  const resumeId = document.getElementById('resume_id').value;
  if (resumeId) {
    window.open('/cv-print/' + resumeId, '_blank');
    return;
  }
  @if (Auth::check())
    $.ajax({
      url: '{{ route("cv.save") }}', type: 'POST',
      data: collectData(),
      success(res) {
        if (res.status) {
          document.getElementById('resume_id').value = res.resume_id;
          window.open('/cv-print/' + res.resume_id, '_blank');
        }
      }
    });
  @else
    toastr.info('Đăng nhập để lưu & xuất PDF hoặc in trực tiếp trang này.');
    window.print();
  @endif
});

/* ============================================================
   APPLY PRESET
============================================================ */
function applyPreset(type) {
  const d = PRESETS[type]; if (!d) return;

  document.getElementById('cv_title').value = d.title;
  document.getElementById('f_fullname').value = d.fullname;
  document.getElementById('f_candidate_title').value = d.candidate_title;
  document.getElementById('f_objective').value = d.objective;
  document.getElementById('cv_theme_color').value = d.theme_color;
  document.querySelectorAll('.cvb-color-dot').forEach(el => {
    el.classList.toggle('active', el.dataset.color === d.theme_color);
  });

  // rebuild exp
  document.getElementById('exp_list').innerHTML = d.experience.map(e => `
    <div class="cvb-entry-card" data-section="exp">
      <button type="button" class="cvb-del-btn" onclick="delCard(this)"><i class="fa fa-times"></i></button>
      <div class="mb-2"><div class="cvb-label">Vị trí</div><input class="cvb-input exp-position" value="${e.position}"></div>
      <div class="mb-2"><div class="cvb-label">Công ty</div><input class="cvb-input exp-company" value="${e.company}"></div>
      <div class="mb-2"><div class="cvb-label">Thời gian</div><input class="cvb-input exp-period" value="${e.period}"></div>
      <div><div class="cvb-label">Mô tả</div><textarea class="cvb-input exp-desc" rows="4">${e.description}</textarea></div>
    </div>`).join('');

  // rebuild edu
  document.getElementById('edu_list').innerHTML = d.education.map(e => `
    <div class="cvb-entry-card" data-section="edu">
      <button type="button" class="cvb-del-btn" onclick="delCard(this)"><i class="fa fa-times"></i></button>
      <div class="mb-2"><div class="cvb-label">Trường</div><input class="cvb-input edu-school" value="${e.school}"></div>
      <div class="mb-2"><div class="cvb-label">Chuyên ngành</div><input class="cvb-input edu-major" value="${e.major}"></div>
      <div class="mb-2"><div class="cvb-label">Năm học</div><input class="cvb-input edu-period" value="${e.period}"></div>
      <div><div class="cvb-label">Mô tả</div><textarea class="cvb-input edu-desc" rows="2">${e.description}</textarea></div>
    </div>`).join('');

  // rebuild skills
  document.getElementById('skill_list').innerHTML = d.skills.map(s => `
    <div class="skill-row">
      <input class="cvb-input skill-name" style="flex:1" value="${s.name}">
      <input type="range" class="skill-range" min="10" max="100" value="${s.level}"
        oninput="this.nextElementSibling.textContent=this.value+'%'; renderPreview()">
      <span class="skill-pct">${s.level}%</span>
      <button type="button" class="cvb-del-btn" style="position:static;margin-left:2px"
        onclick="this.closest('.skill-row').remove(); renderPreview()"><i class="fa fa-times"></i></button>
    </div>`).join('');

  // rebuild projects
  document.getElementById('proj_list').innerHTML = d.projects.map(p => `
    <div class="cvb-entry-card" data-section="proj">
      <button type="button" class="cvb-del-btn" onclick="delCard(this)"><i class="fa fa-times"></i></button>
      <div class="mb-2"><div class="cvb-label">Tên dự án</div><input class="cvb-input proj-name" value="${p.name}"></div>
      <div class="cvb-row mb-2">
        <div><div class="cvb-label">Vai trò</div><input class="cvb-input proj-role" value="${p.role}"></div>
        <div><div class="cvb-label">Thời gian</div><input class="cvb-input proj-period" value="${p.period}"></div>
      </div>
      <div><div class="cvb-label">Mô tả</div><textarea class="cvb-input proj-desc" rows="3">${p.description}</textarea></div>
    </div>`).join('');

  // rebuild certs
  document.getElementById('cert_list').innerHTML = d.certifications.map(c => `
    <div class="cvb-entry-card" data-section="cert">
      <button type="button" class="cvb-del-btn" onclick="delCard(this)"><i class="fa fa-times"></i></button>
      <div class="mb-2"><div class="cvb-label">Tên chứng chỉ</div><input class="cvb-input cert-name" value="${c.name}"></div>
      <div class="cvb-row">
        <div><div class="cvb-label">Tổ chức</div><input class="cvb-input cert-org" value="${c.organization}"></div>
        <div><div class="cvb-label">Năm</div><input class="cvb-input cert-year" value="${c.year}"></div>
      </div>
    </div>`).join('');

  bindInputEvents();
  renderPreview();
  bootstrap.Modal.getInstance(document.getElementById('presetModal'))?.hide();
  toastr.success('Đã nạp mẫu thực chiến! Hãy chỉnh thông tin của bạn.');
}
</script>
@endsection
