@extends('front.layouts.app')

@section('main')
<style>
/* ============================================================
   CV TEMPLATES GALLERY — VIEC LAM 24H STYLE
   ============================================================ */
.cvt-page {
  background: #f8fafc;
  min-height: calc(100vh - 70px);
  padding: 30px 0 60px;
}

/* Breadcrumbs */
.cvt-breadcrumb {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 13px;
  color: #64748b;
  margin-bottom: 20px;
}
.cvt-breadcrumb a { color: #64748b; text-decoration: none; }
.cvt-breadcrumb a:hover { color: #00b14f; }

/* Layout Grid: Sidebar + Main */
.cvt-layout {
  display: grid;
  grid-template-columns: 260px 1fr;
  gap: 28px;
  align-items: start;
}

/* Sidebar Card */
.cvt-sidebar {
  background: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 14px;
  padding: 20px 16px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.03);
  position: sticky;
  top: 80px;
}
.cvt-user-card {
  display: flex;
  align-items: center;
  gap: 12px;
  padding-bottom: 16px;
  border-bottom: 1px solid #f1f5f9;
  margin-bottom: 16px;
}
.cvt-user-avatar {
  width: 44px;
  height: 44px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid #e2e8f0;
}
.cvt-user-name {
  font-size: 14px;
  font-weight: 700;
  color: #1e293b;
  line-height: 1.3;
}
.cvt-user-role {
  font-size: 11px;
  color: #64748b;
}

/* Sidebar Nav */
.cvt-menu {
  list-style: none;
  padding: 0;
  margin: 0 0 20px;
}
.cvt-menu-item a {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px 12px;
  border-radius: 8px;
  color: #475569;
  font-size: 13.5px;
  font-weight: 500;
  text-decoration: none;
  transition: all 0.15s;
}
.cvt-menu-item a:hover {
  background: #f1f5f9;
  color: #00b14f;
}
.cvt-menu-item.active a {
  background: rgba(0, 177, 79, 0.1);
  color: #00b14f;
  font-weight: 700;
}
.cvt-menu-item a i {
  font-size: 15px;
  width: 18px;
  text-align: center;
}

/* Filter Groups in Sidebar */
.cvt-filter-title {
  font-size: 12px;
  font-weight: 700;
  text-transform: uppercase;
  color: #94a3b8;
  letter-spacing: 0.5px;
  margin-bottom: 10px;
  padding-left: 6px;
}
.cvt-category-list {
  list-style: none;
  padding: 0;
  margin: 0;
}
.cvt-category-list li a {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 7px 12px;
  border-radius: 6px;
  font-size: 13px;
  color: #475569;
  text-decoration: none;
  transition: all 0.15s;
}
.cvt-category-list li a:hover {
  background: #f8fafc;
  color: #00b14f;
}
.cvt-category-list li.active a {
  background: #ecfdf5;
  color: #00b14f;
  font-weight: 600;
}

/* Main Area Header */
.cvt-main-header {
  margin-bottom: 20px;
}
.cvt-main-title {
  font-size: 22px;
  font-weight: 800;
  color: #1e293b;
  margin-bottom: 14px;
}

/* Filter Pills */
.cvt-pills {
  display: flex;
  align-items: center;
  gap: 8px;
  flex-wrap: wrap;
  margin-bottom: 24px;
}
.cvt-pill {
  padding: 6px 14px;
  border-radius: 999px;
  background: #fff;
  border: 1px solid #e2e8f0;
  color: #475569;
  font-size: 12.5px;
  font-weight: 500;
  text-decoration: none;
  transition: all 0.15s;
}
.cvt-pill:hover {
  border-color: #00b14f;
  color: #00b14f;
  background: #f0fdf4;
}
.cvt-pill.active {
  background: #00b14f;
  border-color: #00b14f;
  color: #fff;
  font-weight: 600;
}

/* Templates Grid: 4 columns */
.cvt-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 20px;
  margin-bottom: 40px;
}
@media (max-width: 1200px) {
  .cvt-grid { grid-template-columns: repeat(3, 1fr); }
}
@media (max-width: 900px) {
  .cvt-grid { grid-template-columns: repeat(2, 1fr); }
  .cvt-layout { grid-template-columns: 1fr; }
  .cvt-sidebar { display: none; }
}
@media (max-width: 540px) {
  .cvt-grid { grid-template-columns: 1fr; }
}

/* Template Card */
.cvt-card {
  background: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  overflow: hidden;
  transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
  display: flex;
  flex-direction: column;
  position: relative;
  box-shadow: 0 1px 3px rgba(0,0,0,0.04);
}
.cvt-card:hover {
  transform: translateY(-5px);
  border-color: #00b14f;
  box-shadow: 0 12px 28px -6px rgba(0, 177, 79, 0.18), 0 4px 6px -2px rgba(0,0,0,0.05);
}

/* Mockup preview area */
.cvt-mockup {
  height: 250px;
  background: #f8fafc;
  position: relative;
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 12px;
  border-bottom: 1px solid #f1f5f9;
}

/* Micro-preview sheet (CSS rendered miniature CV) */
.cvt-sheet {
  width: 154px;
  height: 218px;
  background: #fff;
  box-shadow: 0 4px 14px rgba(0,0,0,0.12);
  border-radius: 4px;
  overflow: hidden;
  position: relative;
  display: flex;
  transition: transform 0.25s;
}
.cvt-card:hover .cvt-sheet {
  transform: scale(1.04);
}

/* Micro CV: Left Sidebar style */
.cvt-ms-sidebar {
  width: 35%;
  background: var(--mc-bg, #0f2942);
  padding: 8px 6px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 5px;
}
.cvt-ms-avatar {
  width: 22px;
  height: 22px;
  border-radius: 50%;
  background: rgba(255,255,255,0.25);
  border: 1.5px solid #fff;
  margin-bottom: 2px;
}
.cvt-ms-line-w {
  height: 3px;
  border-radius: 2px;
  background: rgba(255,255,255,0.4);
  width: 80%;
  margin-bottom: 2px;
}
.cvt-ms-line-accent {
  height: 3px;
  border-radius: 2px;
  background: var(--mc-accent, #38bdf8);
  width: 60%;
  margin-bottom: 4px;
}
.cvt-ms-main {
  width: 65%;
  padding: 8px;
  display: flex;
  flex-direction: column;
  gap: 4px;
}
.cvt-ms-title {
  height: 6px;
  width: 75%;
  background: var(--mc-bg, #0f2942);
  border-radius: 2px;
  margin-bottom: 1px;
}
.cvt-ms-subtitle {
  height: 4px;
  width: 50%;
  background: #94a3b8;
  border-radius: 2px;
  margin-bottom: 6px;
}
.cvt-ms-block {
  display: flex;
  flex-direction: column;
  gap: 2px;
  margin-bottom: 4px;
}
.cvt-ms-line-dark {
  height: 3px;
  background: #cbd5e1;
  border-radius: 2px;
}

/* Micro CV: Top Header style */
.cvt-ms-top-wrap {
  width: 100%;
  display: flex;
  flex-direction: column;
}
.cvt-ms-header {
  height: 40px;
  background: var(--mc-bg, #0d9488);
  padding: 6px;
  display: flex;
  align-items: center;
  gap: 6px;
}
.cvt-ms-header-avatar {
  width: 24px;
  height: 24px;
  border-radius: 50%;
  background: #fff;
  border: 1.5px solid #fff;
}
.cvt-ms-body {
  padding: 8px;
  display: flex;
  gap: 6px;
  flex: 1;
}

/* Badge on Mockup */
.cvt-badge-pos {
  position: absolute;
  top: 10px;
  right: 10px;
  z-index: 2;
}

/* Card Body */
.cvt-body {
  padding: 14px 16px;
  flex: 1;
  display: flex;
  flex-direction: column;
}
.cvt-name {
  font-size: 14.5px;
  font-weight: 700;
  color: #1e293b;
  margin-bottom: 6px;
  line-height: 1.3;
}
.cvt-tags {
  display: flex;
  gap: 5px;
  flex-wrap: wrap;
  margin-bottom: 10px;
}
.cvt-tag {
  font-size: 11px;
  padding: 2px 8px;
  border-radius: 4px;
  background: #f1f5f9;
  color: #475569;
  font-weight: 500;
}
.cvt-desc {
  font-size: 12px;
  color: #64748b;
  line-height: 1.45;
  margin-bottom: 12px;
  flex: 1;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* Action button on hover */
.cvt-btn-use {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  width: 100%;
  padding: 8px 14px;
  border-radius: 8px;
  background: #00b14f;
  color: #fff;
  font-size: 13px;
  font-weight: 600;
  text-decoration: none;
  transition: all 0.2s;
  box-shadow: 0 2px 6px rgba(0, 177, 79, 0.25);
}
.cvt-btn-use:hover {
  background: #009140;
  color: #fff;
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(0, 177, 79, 0.35);
}

/* SEO / FAQ Section */
.cvt-guide {
  background: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 14px;
  padding: 32px 36px;
  margin-top: 30px;
}
.cvt-guide-title {
  font-size: 20px;
  font-weight: 800;
  color: #1e293b;
  margin-bottom: 16px;
  padding-bottom: 12px;
  border-bottom: 2px solid #f1f5f9;
}
.cvt-guide-intro {
  font-size: 14px;
  color: #475569;
  line-height: 1.7;
  margin-bottom: 24px;
}
.cvt-faq-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
}
@media (max-width: 768px) {
  .cvt-faq-grid { grid-template-columns: 1fr; }
}
.cvt-faq-card {
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 10px;
  padding: 18px 20px;
}
.cvt-faq-card h4 {
  font-size: 14.5px;
  font-weight: 700;
  color: #1e293b;
  margin-bottom: 8px;
  display: flex;
  align-items: center;
  gap: 8px;
}
.cvt-faq-card h4 i {
  color: #00b14f;
}
.cvt-faq-card p {
  font-size: 13px;
  color: #475569;
  line-height: 1.6;
  margin: 0;
}
</style>

<div class="cvt-page">
  <div class="container">

    {{-- Breadcrumb --}}
    <div class="cvt-breadcrumb">
      <a href="{{ route('home') }}"><i class="fa fa-home"></i> Trang chủ</a>
      <span>/</span>
      <span>Tạo CV</span>
      <span>/</span>
      <span class="text-dark fw-bold">Mẫu CV đẹp</span>
    </div>

    <div class="cvt-layout">

      {{-- LEFT SIDEBAR --}}
      <aside class="cvt-sidebar">
        <div class="cvt-user-card">
          @php
            $uAvatar = Auth::check() && Auth::user()->image 
              ? asset('assets/user/profile_picture/thumb/' . Auth::user()->image)
              : asset('assets/user/images/avatar7.png');
            $uName = Auth::check() ? (Auth::user()->fullname ?? Auth::user()->name) : 'Khách ứng viên';
          @endphp
          <img src="{{ $uAvatar }}" alt="{{ $uName }}" class="cvt-user-avatar"
               onerror="this.src='{{ asset('assets/user/images/avatar7.png') }}'">
          <div>
            <div class="cvt-user-name">{{ Str::limit($uName, 18) }}</div>
            <div class="cvt-user-role">{{ Auth::check() ? 'Ứng viên tìm việc' : 'Đăng nhập để lưu CV' }}</div>
          </div>
        </div>

        <ul class="cvt-menu">
          <li class="cvt-menu-item active">
            <a href="{{ route('cv.templates') }}">
              <i class="fa fa-pencil-square-o"></i> Tạo CV mới
            </a>
          </li>
          <li class="cvt-menu-item">
            <a href="{{ route('cv.myResumes') }}">
              <i class="fa fa-folder-open-o"></i> Quản lý CV đã tạo
            </a>
          </li>
          <li class="cvt-menu-item">
            <a href="{{ route('account.profile') }}">
              <i class="fa fa-user-circle-o"></i> Hồ sơ của tôi
            </a>
          </li>
          <li class="cvt-menu-item">
            <a href="{{ route('account.myJobApplication') }}">
              <i class="fa fa-paper-plane-o"></i> Việc làm đã ứng tuyển
            </a>
          </li>
          <li class="cvt-menu-item">
            <a href="{{ route('account.savedJobs') }}">
              <i class="fa fa-heart-o"></i> Việc làm đã lưu
            </a>
          </li>
        </ul>
      </aside>

      {{-- MAIN CONTENT --}}
      <main class="cvt-main">
        <div class="cvt-main-header">
          <h1 class="cvt-main-title">Danh sách các mẫu CV được Top nhà tuyển dụng ưa thích</h1>
          
          {{-- Category filter pills --}}
          <div class="cvt-pills">
            <a href="{{ route('cv.templates') }}" class="cvt-pill {{ $currentCategory === 'all' ? 'active' : '' }}">Tất cả</a>
            <a href="{{ route('cv.templates', ['category' => 'modern']) }}" class="cvt-pill {{ $currentCategory === 'modern' ? 'active' : '' }}">Hiện đại</a>
            <a href="{{ route('cv.templates', ['category' => 'professional']) }}" class="cvt-pill {{ $currentCategory === 'professional' ? 'active' : '' }}">Chuyên nghiệp</a>
            <a href="{{ route('cv.templates', ['category' => 'simple']) }}" class="cvt-pill {{ $currentCategory === 'simple' ? 'active' : '' }}">Đơn giản</a>
            <a href="{{ route('cv.templates', ['category' => 'elegant']) }}" class="cvt-pill {{ $currentCategory === 'elegant' ? 'active' : '' }}">Thanh lịch</a>
            <a href="{{ route('cv.templates', ['category' => 'creative']) }}" class="cvt-pill {{ $currentCategory === 'creative' ? 'active' : '' }}">Sáng tạo</a>
            <a href="{{ route('cv.templates', ['category' => 'it']) }}" class="cvt-pill {{ $currentCategory === 'it' ? 'active' : '' }}">IT & Lập trình</a>
            <a href="{{ route('cv.templates', ['category' => 'classic']) }}" class="cvt-pill {{ $currentCategory === 'classic' ? 'active' : '' }}">Cổ điển</a>
          </div>
        </div>

        {{-- 4 Columns Grid --}}
        <div class="cvt-grid">
          @foreach($templates as $key => $tpl)
            <div class="cvt-card">
              {{-- Mockup Preview --}}
              <div class="cvt-mockup">
                {{-- Badge --}}
                <div class="cvt-badge-pos">
                  @if($tpl['badge'] === 'HOT')
                    <span class="badge bg-danger fw-bold shadow-sm" style="font-size: 10px;">{{ $tpl['badge'] }}</span>
                  @elseif($tpl['badge'] === 'MỚI')
                    <span class="badge bg-success fw-bold shadow-sm" style="font-size: 10px;">{{ $tpl['badge'] }}</span>
                  @elseif($tpl['badge'] === 'VIP')
                    <span class="badge bg-dark fw-bold shadow-sm" style="font-size: 10px; background: #7c3aed !important;">{{ $tpl['badge'] }}</span>
                  @elseif($tpl['badge'] === 'ATS CHUẨN')
                    <span class="badge bg-dark fw-bold shadow-sm" style="font-size: 10px;">{{ $tpl['badge'] }}</span>
                  @else
                    <span class="badge bg-primary fw-bold shadow-sm" style="font-size: 10px;">{{ $tpl['badge'] }}</span>
                  @endif
                </div>

                {{-- Miniature Sheet Preview --}}
                <div class="cvt-sheet" style="--mc-bg: {{ $tpl['primary_color'] }}; --mc-accent: {{ $tpl['accent_color'] }};">
                  @if($tpl['layout'] === 'sidebar_left')
                    <div class="cvt-ms-sidebar">
                      <div class="cvt-ms-avatar"></div>
                      <div class="cvt-ms-line-w"></div>
                      <div class="cvt-ms-line-accent"></div>
                      <div class="cvt-ms-line-w" style="width: 90%;"></div>
                      <div class="cvt-ms-line-w" style="width: 70%;"></div>
                      <div class="cvt-ms-line-w" style="width: 85%; margin-top: 6px;"></div>
                      <div class="cvt-ms-line-accent" style="width: 75%;"></div>
                    </div>
                    <div class="cvt-ms-main">
                      <div class="cvt-ms-title"></div>
                      <div class="cvt-ms-subtitle"></div>
                      <div class="cvt-ms-block">
                        <div class="cvt-ms-line-dark" style="width: 95%;"></div>
                        <div class="cvt-ms-line-dark" style="width: 85%;"></div>
                        <div class="cvt-ms-line-dark" style="width: 90%;"></div>
                      </div>
                      <div class="cvt-ms-block" style="margin-top: 4px;">
                        <div class="cvt-ms-line-dark" style="width: 90%;"></div>
                        <div class="cvt-ms-line-dark" style="width: 75%;"></div>
                      </div>
                      <div class="cvt-ms-block" style="margin-top: 4px;">
                        <div class="cvt-ms-line-dark" style="width: 85%;"></div>
                        <div class="cvt-ms-line-dark" style="width: 65%;"></div>
                      </div>
                    </div>
                  @elseif($tpl['layout'] === 'header_banner')
                    <div class="cvt-ms-top-wrap">
                      <div class="cvt-ms-header">
                        <div class="cvt-ms-header-avatar"></div>
                        <div style="flex:1;">
                          <div style="height:5px; background:#fff; width:65%; border-radius:2px; margin-bottom:2px;"></div>
                          <div style="height:3px; background:rgba(255,255,255,0.7); width:45%; border-radius:2px;"></div>
                        </div>
                      </div>
                      <div class="cvt-ms-body">
                        <div style="width: 40%; display:flex; flex-direction:column; gap:3px;">
                          <div class="cvt-ms-line-dark" style="width: 80%;"></div>
                          <div class="cvt-ms-line-dark" style="width: 90%;"></div>
                          <div class="cvt-ms-line-dark" style="width: 70%;"></div>
                        </div>
                        <div style="width: 60%; display:flex; flex-direction:column; gap:3px;">
                          <div class="cvt-ms-line-dark" style="width: 95%;"></div>
                          <div class="cvt-ms-line-dark" style="width: 85%;"></div>
                          <div class="cvt-ms-line-dark" style="width: 90%;"></div>
                          <div class="cvt-ms-line-dark" style="width: 80%;"></div>
                        </div>
                      </div>
                    </div>
                  @elseif($tpl['layout'] === 'header_split')
                    <div class="cvt-ms-top-wrap">
                      <div style="padding: 8px; border-bottom: 2px solid {{ $tpl['primary_color'] }}; display:flex; justify-content:space-between; align-items:center;">
                        <div>
                          <div style="height:6px; background:{{ $tpl['primary_color'] }}; width:60px; border-radius:2px; margin-bottom:2px;"></div>
                          <div style="height:3px; background:#94a3b8; width:40px; border-radius:2px;"></div>
                        </div>
                        <div style="width: 20px; height: 20px; border-radius: 4px; background: {{ $tpl['accent_color'] }};"></div>
                      </div>
                      <div style="padding: 8px; display:flex; flex-direction:column; gap:4px;">
                        <div class="cvt-ms-line-dark" style="width: 95%;"></div>
                        <div class="cvt-ms-line-dark" style="width: 85%;"></div>
                        <div class="cvt-ms-line-dark" style="width: 90%;"></div>
                        <div style="height:1px; background:#e2e8f0; margin: 4px 0;"></div>
                        <div class="cvt-ms-line-dark" style="width: 92%;"></div>
                        <div class="cvt-ms-line-dark" style="width: 80%;"></div>
                      </div>
                    </div>
                  @else
                    {{-- Single column / Minimalist / ATS --}}
                    <div style="padding: 10px; width:100%; display:flex; flex-direction:column; gap:4px;">
                      <div style="text-align:center; margin-bottom:4px;">
                        <div style="height:6px; background:#1e293b; width:60%; margin:0 auto 2px; border-radius:2px;"></div>
                        <div style="height:3px; background:#64748b; width:40%; margin:0 auto; border-radius:2px;"></div>
                      </div>
                      <div style="height:1px; background:#e2e8f0; margin: 2px 0;"></div>
                      <div class="cvt-ms-line-dark" style="width: 98%;"></div>
                      <div class="cvt-ms-line-dark" style="width: 92%;"></div>
                      <div class="cvt-ms-line-dark" style="width: 88%;"></div>
                      <div style="height:1px; background:#e2e8f0; margin: 3px 0;"></div>
                      <div class="cvt-ms-line-dark" style="width: 95%;"></div>
                      <div class="cvt-ms-line-dark" style="width: 85%;"></div>
                    </div>
                  @endif
                </div>
              </div>

              {{-- Card Info --}}
              <div class="cvt-body">
                <div class="cvt-name">{{ $tpl['name'] }}</div>
                <div class="cvt-tags">
                  @foreach($tpl['tags'] as $tag)
                    <span class="cvt-tag">{{ $tag }}</span>
                  @endforeach
                </div>
                <div class="cvt-desc">{{ $tpl['description'] }}</div>
                <a href="{{ route('cv.builder', ['template' => $key]) }}" class="cvt-btn-use">
                  <i class="fa fa-magic"></i> Dùng mẫu này
                </a>
              </div>
            </div>
          @endforeach
        </div>

        {{-- SEO Guide / FAQ Box matching Image 1 --}}
        <div class="cvt-guide">
          <h2 class="cvt-guide-title">CV là gì? Những điều cần lưu ý khi viết CV xin việc</h2>
          <p class="cvt-guide-intro">
            CV (Curriculum Vitae) là bản tóm tắt quá trình học tập, kinh nghiệm làm việc, kỹ năng chuyên môn và các thành tích nổi bật của bạn. Một mẫu CV được thiết kế đẹp, bố cục chuẩn mực và nội dung súc tích sẽ giúp bạn nổi bật trong mắt nhà tuyển dụng và nâng cao 80% cơ hội được mời phỏng vấn.
          </p>

          <div class="cvt-faq-grid">
            <div class="cvt-faq-card">
              <h4><i class="fa fa-check-circle"></i> CV gồm những thông tin quan trọng nào?</h4>
              <p>Một bản CV chuẩn cần có: Thông tin cá nhân & liên hệ, Mục tiêu nghề nghiệp, Kinh nghiệm làm việc thực tế, Trình độ học vấn, Kỹ năng chuyên môn và các chứng chỉ liên quan.</p>
            </div>

            <div class="cvt-faq-card">
              <h4><i class="fa fa-filter"></i> Mẫu CV chuẩn ATS là gì?</h4>
              <p>Mẫu CV chuẩn ATS là CV được định dạng khoa học, sử dụng phông chữ tiêu chuẩn, tiêu đề rõ ràng, giúp hệ thống sàng lọc hồ sơ tự động của doanh nghiệp dễ dàng đọc và đánh giá điểm số cao.</p>
            </div>

            <div class="cvt-faq-card">
              <h4><i class="fa fa-star"></i> Nên chọn mẫu CV nào khi chưa có kinh nghiệm?</h4>
              <p>Đối với sinh viên mới ra trường hoặc người mới chuyển ngành, nên chọn các mẫu CV làm nổi bật phần Học vấn, Hoạt động ngoại khóa, Kỹ năng mềm và các Dự án cá nhân đã từng thực hiện.</p>
            </div>

            <div class="cvt-faq-card">
              <h4><i class="fa fa-file-pdf-o"></i> Tải CV dạng nào là chuyên nghiệp nhất?</h4>
              <p>Nên tải và gửi CV dưới định dạng PDF. Định dạng PDF giúp giữ nguyên phông chữ, bố cục và màu sắc trên mọi thiết bị máy tính lẫn điện thoại của nhà tuyển dụng.</p>
            </div>
          </div>
        </div>

      </main>

    </div>
  </div>
</div>
@endsection

