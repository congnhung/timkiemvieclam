@extends('front.layouts.app')

@php
  // Ưu tiên template từ query string, nếu không có lấy từ resume, cuối cùng là emerald_startup (TopWork chuẩn)
  $reqTpl = request()->get('template');
  $currentTemplateKey = ($reqTpl && isset($allTemplates[$reqTpl]))
      ? $reqTpl
      : ($resume->template ?? ($defaultData['template'] ?? 'emerald_startup'));
  if (!isset($allTemplates[$currentTemplateKey])) {
      $currentTemplateKey = 'emerald_startup';
  }
  $selectedTpl = $allTemplates[$currentTemplateKey];

  $chosenThemeColor = request()->has('template')
      ? ($selectedTpl['primary_color'] ?? '#00b14f')
      : ($resume->theme_color ?? ($selectedTpl['primary_color'] ?? '#00b14f'));

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
    'theme_color'      => $chosenThemeColor,
    'template'         => $currentTemplateKey,
    'font'             => $selectedTpl['font'] ?? 'Lexend',
    'objective'        => $resume->objective,
    'experience'       => !empty($resume->experience_data)     ? $resume->experience_data     : [],
    'education'        => !empty($resume->education_data)      ? $resume->education_data      : [],
    'skills'           => !empty($resume->skills_data)         ? $resume->skills_data         : [],
    'projects'         => !empty($resume->projects_data)       ? $resume->projects_data       : [],
    'certifications'   => !empty($resume->certifications_data) ? $resume->certifications_data : [],
  ] : array_merge($defaultData, [
    'id' => '',
    'template' => $currentTemplateKey,
    'theme_color' => $chosenThemeColor,
    'font' => $selectedTpl['font'] ?? 'Lexend'
  ]);
@endphp

@section('main')
<!-- Google Fonts: Lexend and CV Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700;800;900&family=Be+Vietnam+Pro:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Inter:wght@300;400;500;600;700;800&family=Montserrat:wght@400;500;600;700;800&family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">

<style>
/* ============================================================
   CV BUILDER — TOPWORK BRAND GREEN & MODERN INTERACTION
   ============================================================ */
body { background: #f8fafc; font-family: 'Lexend', sans-serif !important; }

/* ---- Top bar ---- */
.cvb-topbar {
  position: sticky; top: 60px; z-index: 900;
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
.cvb-btn-back:hover { background: #ecfdf5; color: #00b14f; border-color: #00b14f; }

/* Control buttons above preview */
.cvb-ctrl-btn {
  background: #fff; border: 1px solid #e2e8f0; border-radius: 6px;
  padding: 5px 10px; font-size: 11.5px; font-weight: 600; color: #334155;
  display: inline-flex; align-items: center; gap: 5px; cursor: pointer;
  transition: all 0.15s; text-decoration: none; line-height: 1.4;
}
.cvb-ctrl-btn:hover { background: #ecfdf5; border-color: #00b14f; color: #00b14f; }
.cvb-ctrl-btn::after { font-size: 9px; margin-left: 2px; }
.cvb-ctrl-btn i { font-size: 11px; }

.cvb-color-dot {
  width: 10px; height: 10px; border-radius: 50%; display: inline-block; flex-shrink: 0;
}

.cvb-btn-finish {
  background: #00b14f; color: #fff; border: 1px solid #009140;
  font-weight: 700; border-radius: 6px; padding: 5px 14px; font-size: 12px;
  display: inline-flex; align-items: center; gap: 5px; cursor: pointer;
  box-shadow: 0 2px 6px rgba(0, 177, 79, 0.25);
  transition: all 0.15s; text-decoration: none; line-height: 1.4;
}
.cvb-btn-finish:hover { background: #009140; color: #fff; transform: translateY(-1px); }

/* Language pills */
.cvb-lang-pill {
  padding: 4px 8px; border-radius: 999px; border: 1px solid #e2e8f0;
  font-size: 11px; font-weight: 600; color: #64748b; background: #fff;
  cursor: pointer; display: inline-flex; align-items: center; gap: 3px; line-height: 1.4;
  transition: all 0.15s;
}
.cvb-lang-pill.active {
  background: #ecfdf5; border-color: #a7f3d0; color: #00b14f;
}
.cvb-lang-pill:hover {
  border-color: #00b14f; color: #00b14f;
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
  height: calc(100vh - 118px);
  position: sticky; top: 118px;
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
  border-color: #00b14f; box-shadow: 0 0 0 3px rgba(0, 177, 79, 0.15);
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
  background: #ecfdf5; color: #00b14f; border-color: #00b14f;
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
  color: #00b14f; font-size: 13.5px; font-weight: 600; text-decoration: none;
  display: inline-flex; align-items: center; gap: 6px; background: none; border: none; padding: 0; cursor: pointer;
  transition: all 0.15s;
}
.cvb-link-add:hover { color: #009140; text-decoration: underline; }

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
  height: calc(100vh - 118px);
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
.cvb-zoom-btn:hover { background: #ecfdf5; color: #00b14f; }
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
  font-size: 13px; color: #1e293b;
  --cv-theme: #00b14f;
  --cv-sidebar-bg: #00b14f;
  --cv-accent: #4ade80;
  position: relative; overflow: hidden;
  transform-origin: top center;
  transition: transform 0.15s ease;
  flex-shrink: 0;
  box-sizing: border-box;
}

/* Common Item Entry */
.cva-item { margin-bottom: 12px; page-break-inside: avoid; }
.cva-item-title { font-size: 13px; font-weight: 700; color: #0f172a; margin-bottom: 2px; }
.cva-item-sub { font-size: 12px; color: var(--cv-theme); font-weight: 600; margin-bottom: 2px; }
.cva-item-period { font-size: 11px; color: #94a3b8; margin-bottom: 4px; }
.cva-item-desc { font-size: 11.5px; color: #334155; line-height: 1.6; white-space: pre-wrap; }

/* ============================================================
   TEMPLATE-SPECIFIC DISTINCT STYLING
   ============================================================ */
/* 1. Emerald Startup (TopWork Brand Green) */
.cv-template-emerald_startup .cva-avatar-wrap img {
  border: 3.5px solid #fff;
  box-shadow: 0 0 0 3px var(--cv-theme, #00b14f), 0 8px 20px rgba(0,0,0,0.2);
}
.cv-template-emerald_startup .cva-contact-item {
  background: rgba(255, 255, 255, 0.12);
  padding: 5px 10px; border-radius: 6px;
}
.cv-template-emerald_startup .cva-sec-title::before {
  border-radius: 50%;
  background: #00b14f;
}

/* 2. Tech Slate (IT & Developers) */
.cv-template-tech_slate .cva-avatar-wrap img {
  border-radius: 12px;
  border: 2px solid #38bdf8;
  box-shadow: 0 4px 14px rgba(56, 189, 248, 0.3);
}
.cv-template-tech_slate .cva-sb-title {
  font-family: 'Courier New', monospace;
  letter-spacing: 2px;
  color: #38bdf8;
}
.cva-tech-badge {
  background: rgba(255, 255, 255, 0.12);
  border: 1px solid rgba(56, 189, 248, 0.4);
  color: #e0f2fe;
  padding: 3px 8px; border-radius: 4px;
  font-size: 11px; font-weight: 600;
  font-family: 'Courier New', monospace;
  display: inline-flex; align-items: center; gap: 4px;
}

/* 3. Executive Dark (Senior & Managers) */
.cv-template-executive_dark .cva-avatar-wrap img {
  border: 3px solid #f59e0b;
}
.cv-template-executive_dark .cva-sec-title {
  border-bottom: 2px solid #f59e0b;
  color: #1e293b;
}
.cv-template-executive_dark .cva-sec-title::before {
  background: #f59e0b;
}
.cv-template-executive_dark .cva-item {
  border-left: 2px solid #e2e8f0;
  padding-left: 12px;
  margin-left: 4px;
  position: relative;
}
.cv-template-executive_dark .cva-item::before {
  content: ''; position: absolute; left: -6px; top: 4px;
  width: 10px; height: 10px; border-radius: 50%; background: #f59e0b;
  border: 2px solid #fff;
}

/* 4. Fresh Graduate (Students & New Grads) */
.cv-template-fresh_graduate .cva-avatar-wrap img {
  border: 3px dashed rgba(255, 255, 255, 0.8);
  padding: 2px;
}
.cv-template-fresh_graduate .cva-sec-title {
  color: #059669;
  border-bottom: 2px solid #059669;
}
.cv-template-fresh_graduate .cva-sec-title::before {
  background: #059669;
  border-radius: 50%;
}

/* 5. Modern Navy */
.cv-template-modern_navy .cva-avatar-wrap img {
  border-radius: 16px;
  border: 4px solid #fff;
}

/* 6. Creative Coral */
.cv-template-creative_coral .cva-split-avatar {
  border-radius: 20px;
  border: 4px solid #ea580c;
  box-shadow: 0 8px 20px rgba(234, 88, 12, 0.25);
}
.cv-template-creative_coral .cva-split-body > div:last-child {
  background: #fff7ed;
  padding: 16px;
  border-radius: 12px;
}

/* 7. Classic Serif */
.cv-template-classic_serif {
  font-family: 'Georgia', serif !important;
}
.cv-template-classic_serif .cva-split-name {
  font-family: 'Georgia', serif;
  font-style: italic;
  letter-spacing: 1px;
}
.cv-template-classic_serif .cva-split-avatar {
  border-radius: 50%;
  border: 2px solid #475569;
}

/* 8. Royal Purple */
.cv-template-royal_purple .cva-banner-top {
  flex-direction: column;
  text-align: center;
  padding: 24px 20px;
}
.cv-template-royal_purple .cva-banner-contact {
  justify-content: center;
}
.cv-template-royal_purple .cva-banner-body {
  grid-template-columns: 1fr 1fr;
}

/* 9. Burgundy Luxury */
.cv-template-burgundy_luxury .cva-split-top {
  background: linear-gradient(to right, #881337, #4c0519);
  color: #fff;
  border-bottom: none;
}
.cv-template-burgundy_luxury .cva-split-name {
  color: #fff;
}
.cv-template-burgundy_luxury .cva-split-title {
  color: #fda4af;
}
.cv-template-burgundy_luxury .cva-split-contact {
  color: #ffe4e6;
}
.cv-template-burgundy_luxury .cva-split-contact i {
  color: #fda4af !important;
}

/* 10. ATS Standard */
.cv-template-ats_standard .cva-single-top {
  border-bottom: 2px solid #000;
  padding-bottom: 12px;
}
.cv-template-ats_standard .cva-single-name {
  font-family: Arial, sans-serif;
  letter-spacing: 2px;
}
.cv-template-ats_standard .cva-single-sec-title {
  border-bottom: 1.5px solid #000;
  color: #000;
  font-weight: 800;
}

/* ============================================================
   LAYOUT 1: SIDEBAR LEFT (Modern Navy, Executive, Tech, Startup)
   ============================================================ */
.cv-layout-sidebar_left { flex-direction: row; }
.cv-layout-sidebar_left .cva-sidebar {
  width: 34%; background: var(--cv-sidebar-bg, #00b14f);
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
.cv-layout-sidebar_left .cva-sb-title:first-of-type { margin-top: 0; }
.cv-layout-sidebar_left .cva-contact-item {
  display: flex; align-items: center; gap: 8px;
  margin-bottom: 8px; font-size: 11.5px; color: #e2e8f0; word-break: break-all;
}
.cv-layout-sidebar_left .cva-contact-item i {
  color: var(--cv-accent, #60a5fa); width: 14px; text-align: center; font-size: 11px; flex-shrink: 0;
}
.cv-layout-sidebar_left .cva-contact-placeholder { color: #93c5fd; opacity: 0.8; font-style: normal; }
.cv-layout-sidebar_left .cva-main {
  width: 66%; padding: 32px 26px; background: #fff; display: flex; flex-direction: column;
}
.cv-layout-sidebar_left .cva-name {
  font-size: 23px; font-weight: 800; color: #0f172a;
  text-transform: uppercase; line-height: 1.2; margin-bottom: 5px; letter-spacing: 0.5px;
}
.cv-layout-sidebar_left .cva-jobtitle {
  font-size: 11.5px; font-weight: 700; color: #64748b;
  text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 22px;
}
.cv-layout-sidebar_left .cva-jobtitle.is-placeholder { color: #94a3b8; letter-spacing: 2px; }
.cv-layout-sidebar_left .cva-sec-title {
  font-size: 11.5px; font-weight: 800; text-transform: uppercase;
  letter-spacing: 1px; color: #0f172a; border-bottom: 2px solid var(--cv-theme);
  padding-bottom: 4px; margin: 18px 0 10px; display: flex; align-items: center; gap: 8px;
}
.cv-layout-sidebar_left .cva-sec-title::before {
  content: ''; display: inline-block; width: 7px; height: 7px; border-radius: 2px; background: var(--cv-theme);
}
.cv-layout-sidebar_left .cva-sec-title:first-of-type { margin-top: 0; }

/* ============================================================
   LAYOUT 2: HEADER BANNER (Teal Mint, Royal Purple)
   ============================================================ */
.cv-layout-header_banner { flex-direction: column; }
.cva-banner-top {
  width: 100%; background: var(--cv-theme, #0d9488);
  color: #fff; padding: 26px 30px; display: flex; align-items: center; gap: 20px;
  box-shadow: 0 4px 14px rgba(0,0,0,0.08);
}
.cva-banner-avatar {
  width: 96px; height: 96px; border-radius: 50%;
  object-fit: cover; border: 3.5px solid #fff;
  box-shadow: 0 4px 12px rgba(0,0,0,0.2); background: #fff; flex-shrink: 0;
}
.cva-banner-info { flex: 1; }
.cva-banner-name {
  font-size: 24px; font-weight: 800; color: #fff;
  text-transform: uppercase; line-height: 1.2; margin-bottom: 4px; letter-spacing: 0.5px;
}
.cva-banner-title {
  font-size: 12px; font-weight: 700; color: rgba(255,255,255,0.92);
  text-transform: uppercase; letter-spacing: 1.2px; margin-bottom: 10px;
}
.cva-banner-title.is-placeholder { color: rgba(255,255,255,0.7); }
.cva-banner-contact { display: flex; flex-wrap: wrap; gap: 6px 12px; }
.cva-b-pill {
  font-size: 11px; color: #fff; background: rgba(255,255,255,0.18);
  border: 1px solid rgba(255,255,255,0.25); padding: 3px 10px; border-radius: 20px;
  display: inline-flex; align-items: center; gap: 6px;
}
.cva-banner-body {
  width: 100%; padding: 26px 30px; display: grid; grid-template-columns: 35% 65%;
  gap: 24px; background: #fff; flex: 1;
}
.cva-banner-sec-title {
  font-size: 11.5px; font-weight: 800; text-transform: uppercase;
  letter-spacing: 1px; color: var(--cv-theme); border-bottom: 2px solid var(--cv-theme);
  padding-bottom: 4px; margin: 16px 0 10px;
}
.cva-banner-sec-title:first-of-type { margin-top: 0; }

/* ============================================================
   LAYOUT 3: HEADER SPLIT (Creative Coral, Classic Serif, Burgundy)
   ============================================================ */
.cv-layout-header_split { flex-direction: column; }
.cva-split-top {
  width: 100%; padding: 28px 32px 20px; display: flex; justify-content: space-between;
  align-items: center; border-bottom: 3px solid var(--cv-theme); background: #fff;
}
.cva-split-info { flex: 1; }
.cva-split-name {
  font-size: 25px; font-weight: 800; color: var(--cv-theme);
  text-transform: uppercase; line-height: 1.2; margin-bottom: 4px; letter-spacing: 0.5px;
}
.cva-split-title {
  font-size: 12px; font-weight: 700; color: #64748b;
  text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 12px;
}
.cva-split-title.is-placeholder { color: #94a3b8; }
.cva-split-contact { display: flex; flex-wrap: wrap; gap: 8px 16px; font-size: 11.5px; color: #475569; }
.cva-split-avatar-wrap { flex-shrink: 0; margin-left: 20px; }
.cva-split-avatar {
  width: 96px; height: 96px; border-radius: 12px; object-fit: cover;
  border: 3px solid var(--cv-theme); box-shadow: 0 4px 12px rgba(0,0,0,0.1); background: #fff;
}
.cva-split-body {
  width: 100%; padding: 24px 32px; display: grid; grid-template-columns: 60% 40%;
  gap: 24px; background: #fff; flex: 1;
}
.cva-split-sec-title {
  font-size: 11.5px; font-weight: 800; text-transform: uppercase;
  letter-spacing: 1px; color: #1e293b; border-bottom: 2px solid var(--cv-theme);
  padding-bottom: 4px; margin: 16px 0 10px;
}
.cva-split-sec-title:first-of-type { margin-top: 0; }

/* ============================================================
   LAYOUT 4: SINGLE COLUMN / ATS MINIMALIST (ATS Standard, Minimalist)
   ============================================================ */
.cv-layout-single_column { flex-direction: column; background: #fff; }
.cva-single-top {
  width: 100%; padding: 32px 36px 16px; text-align: center; border-bottom: 2px solid #0f172a;
}
.cva-single-name {
  font-size: 26px; font-weight: 800; color: #0f172a;
  text-transform: uppercase; line-height: 1.2; margin-bottom: 4px; letter-spacing: 1px;
}
.cva-single-title {
  font-size: 12px; font-weight: 700; color: #475569;
  text-transform: uppercase; letter-spacing: 2px; margin-bottom: 10px;
}
.cva-single-title.is-placeholder { color: #94a3b8; }
.cva-single-contact {
  display: flex; justify-content: center; flex-wrap: wrap; gap: 6px 14px;
  font-size: 11.5px; color: #334155;
}
.cva-single-body {
  width: 100%; padding: 22px 36px 40px; display: flex; flex-direction: column;
  gap: 14px; background: #fff; flex: 1;
}
.cva-single-sec-title {
  font-size: 12px; font-weight: 800; text-transform: uppercase;
  letter-spacing: 1px; color: #0f172a; border-bottom: 1.5px solid #cbd5e1;
  padding-bottom: 3px; margin: 10px 0 8px;
}
.cva-single-sec-title:first-of-type { margin-top: 0; }

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
            '#00b14f' => 'TopWork (Xanh lục chuẩn)',
            '#059669' => 'Xanh ngọc lục bảo',
            '#0d9488' => 'Teal Mint',
            '#0f2942' => 'Navy',
            '#1e293b' => 'Xám than',
            '#ea580c' => 'Cam san hô',
            '#881337' => 'Đỏ đô',
            '#7c3aed' => 'Tím',
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
        <span id="currentTemplateName">{{ $selectedTpl['name'] }}</span>
      </button>
      <ul class="dropdown-menu shadow cvb-template-menu" aria-labelledby="dropdownTemplate">
        @foreach($allTemplates as $k => $tpl)
          <li>
            <button type="button" class="dropdown-item d-flex justify-content-between align-items-center py-1.5 px-2.5 cvb-template-option {{ $cd['template'] === $k ? 'active' : '' }}"
                    data-template="{{ $k }}"
                    onclick="selectTemplate('{{ $k }}')">
              <span>{{ $tpl['name'] }}</span>
              <span class="badge bg-light text-dark" style="font-size: 10px;">{{ $tpl['badge'] }}</span>
            </button>
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
      <div class="cv-a4 cv-layout-{{ $selectedTpl['layout'] ?? 'sidebar_left' }} cv-template-{{ $currentTemplateKey }}" id="cvA4" style="--cv-theme: {{ $cd['theme_color'] }}; --cv-sidebar-bg: {{ $cd['theme_color'] }}; font-family: '{{ $selectedTpl['font'] ?? 'Lexend' }}', sans-serif !important;">
        {{-- Nội dung mẫu CV được render tự động và thời gian thực qua renderPreview() --}}
      </div><!-- /cv-a4 -->
    </div><!-- /cv-a4-scale-container -->
  </div><!-- /cvb-preview-pane -->

</div><!-- /cvb-layout -->

@endsection

@section('customJs')
<script>
/* ============================================================
   REAL-TIME CV LIVE PREVIEW & MULTI-TEMPLATE ENGINE
============================================================ */
const CV_TEMPLATES = @json($allTemplates);

function selectTemplate(tplKey) {
  if (!CV_TEMPLATES[tplKey]) return;
  const tpl = CV_TEMPLATES[tplKey];

  // 1. Cập nhật hidden inputs
  document.getElementById('cv_template_key').value = tplKey;
  document.getElementById('cv_theme_color').value = tpl.primary_color;

  // 2. Cập nhật nút Đổi màu
  const colorDot = document.getElementById('colorDot');
  if (colorDot) colorDot.style.background = tpl.primary_color;

  // 3. Cập nhật nút Đổi mẫu & danh sách dropdown
  const nameEl = document.getElementById('currentTemplateName');
  if (nameEl) nameEl.textContent = tpl.name;

  document.querySelectorAll('.cvb-template-option').forEach(el => {
    if (el.getAttribute('data-template') === tplKey) {
      el.classList.add('active');
    } else {
      el.classList.remove('active');
    }
  });

  // 4. Cập nhật swatch màu tương ứng
  document.querySelectorAll('.cvb-color-swatch').forEach(swatch => {
    if (swatch.getAttribute('data-color').toLowerCase() === tpl.primary_color.toLowerCase()) {
      swatch.classList.add('active');
    } else {
      swatch.classList.remove('active');
    }
  });

  // 5. Cập nhật query parameter trên URL mà không reload trang
  const url = new URL(window.location.href);
  url.searchParams.set('template', tplKey);
  window.history.replaceState({}, '', url.toString());

  // 6. Đóng dropdown bootstrap
  const tplBtn = document.getElementById('dropdownTemplate');
  if (tplBtn && window.bootstrap) {
    const bsDropdown = bootstrap.Dropdown.getInstance(tplBtn);
    if (bsDropdown) bsDropdown.hide();
  }

  // 7. Render lại preview thời gian thực với layout mới
  renderPreview();

  if (typeof toastr !== 'undefined') {
    toastr.success('Đã chuyển sang mẫu: ' + tpl.name);
  }
}

/* Helpers lấy dữ liệu từ form */
function getExpData() {
  const items = [];
  document.querySelectorAll('#exp_list .cvb-entry-card').forEach(card => {
    const pos = (card.querySelector('.exp-position')?.value || '').trim();
    const comp = (card.querySelector('.exp-company')?.value || '').trim();
    const per = (card.querySelector('.exp-period')?.value || '').trim();
    const desc = (card.querySelector('.exp-desc')?.value || '').trim();
    if (pos || comp || per || desc) {
      items.push({ position: pos, company: comp, period: per, description: desc });
    }
  });
  return items;
}

function getEduData() {
  const items = [];
  document.querySelectorAll('#edu_list .cvb-entry-card').forEach(card => {
    const sch = (card.querySelector('.edu-school')?.value || '').trim();
    const maj = (card.querySelector('.edu-major')?.value || '').trim();
    const per = (card.querySelector('.edu-period')?.value || '').trim();
    const desc = (card.querySelector('.edu-desc')?.value || '').trim();
    if (sch || maj || per || desc) {
      items.push({ school: sch, major: maj, period: per, description: desc });
    }
  });
  return items;
}

function getSkillsData() {
  const items = [];
  document.querySelectorAll('#skill_list .skill-row').forEach(row => {
    const name = (row.querySelector('.skill-name')?.value || '').trim();
    const lvl = row.querySelector('.skill-range')?.value || '80';
    if (name) {
      items.push({ name: name, level: lvl });
    }
  });
  return items;
}

function getLanguagesData() {
  const items = [];
  document.querySelectorAll('#lang_list .lang-item-text').forEach(el => {
    const txt = el.textContent.trim();
    if (txt) items.push(txt);
  });
  return items;
}

function getItSkillsData() {
  const items = [];
  document.querySelectorAll('#it_list .it-item-text').forEach(el => {
    const txt = el.textContent.trim();
    if (txt) items.push(txt);
  });
  return items;
}

function getReferencesData() {
  const items = [];
  document.querySelectorAll('#ref_list .ref-item-text').forEach(el => {
    const txt = el.textContent.trim();
    if (txt) items.push(txt);
  });
  return items;
}

function getActivitiesData() {
  const items = [];
  document.querySelectorAll('#act_list .act-item-text').forEach(el => {
    const txt = el.textContent.trim();
    if (txt) items.push(txt);
  });
  return items;
}

function getCertsData() {
  const items = [];
  document.querySelectorAll('#cert_list .cvb-entry-card').forEach(card => {
    const name = (card.querySelector('.cert-name')?.value || '').trim();
    const org = (card.querySelector('.cert-org')?.value || '').trim();
    const year = (card.querySelector('.cert-year')?.value || '').trim();
    if (name || org || year) {
      items.push({ name: name, organization: org, year: year });
    }
  });
  return items;
}

function esc(str) {
  return String(str || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

/* Renderers các khối con */
function renderSkillsHtml(skills, isSidebar, barColor) {
  return skills.map(s => `
    <div style="margin-bottom: 8px;">
      <div style="display:flex; justify-content:space-between; font-size:11.5px; color:${isSidebar ? '#e2e8f0' : '#334155'}; margin-bottom:2px;">
        <span>${esc(s.name)}</span><span>${s.level}%</span>
      </div>
      <div style="height:4px; background:${isSidebar ? 'rgba(255,255,255,0.2)' : '#e2e8f0'}; border-radius:2px;">
        <div style="height:100%; width:${s.level}%; background:${barColor || 'var(--cv-accent, #38bdf8)'}; border-radius:2px;"></div>
      </div>
    </div>
  `).join('');
}

function renderSkillsAtsHtml(skills) {
  return `
    <div style="display:flex; flex-wrap:wrap; gap:6px;">
      ${skills.map(s => `
        <span style="background:#f1f5f9; border:1px solid #e2e8f0; color:#1e293b; padding:3px 10px; border-radius:4px; font-size:11.5px; font-weight:600;">
          ${esc(s.name)} (${s.level}%)
        </span>
      `).join('')}
    </div>
  `;
}

function renderExperienceHtml(expList) {
  return expList.map(item => `
    <div class="cva-item">
      <div class="cva-item-title">${esc(item.position)}</div>
      <div class="cva-item-sub">${esc(item.company)}</div>
      <div class="cva-item-period"><i class="fa fa-clock-o me-1"></i>${esc(item.period)}</div>
      <div class="cva-item-desc">${esc(item.description)}</div>
    </div>
  `).join('');
}

function renderExperienceAtsHtml(expList) {
  return expList.map(item => `
    <div class="cva-item" style="margin-bottom:12px;">
      <div style="display:flex; justify-content:space-between; align-items:baseline; margin-bottom:2px;">
        <span style="font-weight:700; font-size:13px; color:#0f172a;">${esc(item.position)}</span>
        <span style="font-size:11px; color:#64748b;">${esc(item.period)}</span>
      </div>
      <div style="font-size:12px; font-weight:600; color:var(--cv-theme); margin-bottom:4px;">${esc(item.company)}</div>
      <div class="cva-item-desc">${esc(item.description)}</div>
    </div>
  `).join('');
}

function renderEducationHtml(eduList) {
  return eduList.map(item => `
    <div class="cva-item">
      <div class="cva-item-title">${esc(item.school)}</div>
      <div class="cva-item-sub">${esc(item.major)}</div>
      <div class="cva-item-period"><i class="fa fa-calendar me-1"></i>${esc(item.period)}</div>
      <div class="cva-item-desc">${esc(item.description)}</div>
    </div>
  `).join('');
}

function renderEducationAtsHtml(eduList) {
  return eduList.map(item => `
    <div class="cva-item" style="margin-bottom:12px;">
      <div style="display:flex; justify-content:space-between; align-items:baseline; margin-bottom:2px;">
        <span style="font-weight:700; font-size:13px; color:#0f172a;">${esc(item.school)}</span>
        <span style="font-size:11px; color:#64748b;">${esc(item.period)}</span>
      </div>
      <div style="font-size:12px; font-weight:600; color:var(--cv-theme); margin-bottom:4px;">${esc(item.major)}</div>
      <div class="cva-item-desc">${esc(item.description)}</div>
    </div>
  `).join('');
}

function renderCertsHtml(certs) {
  return certs.map(c => `
    <div class="cva-item" style="margin-bottom:8px;">
      <div style="font-size:12px; font-weight:700; color:#0f172a;">${esc(c.name)}</div>
      <div style="font-size:11px; color:#64748b;">${esc(c.organization)}${c.year ? ' · ' + esc(c.year) : ''}</div>
    </div>
  `).join('');
}

function renderTagsHtml(tags, isSidebar) {
  return `
    <div style="display:flex; flex-direction:column; gap:4px; margin-bottom:8px;">
      ${tags.map(t => `
        <div style="font-size:11.5px; color:${isSidebar ? '#e2e8f0' : '#334155'};">
          <i class="fa fa-check-circle me-1" style="font-size:10px; color:${isSidebar ? 'var(--cv-accent, #38bdf8)' : 'var(--cv-theme)'}"></i> ${esc(t)}
        </div>
      `).join('')}
    </div>
  `;
}

/* ============================================================
   BỐ CỤC 1: SIDEBAR LEFT (Modern Navy, Executive, Tech, Startup)
   ============================================================ */
function renderSidebarLeftHtml(d) {
  const isFreshGrad = (d.tplKey === 'fresh_graduate');
  const isTech = (d.tplKey === 'tech_slate');
  const isExec = (d.tplKey === 'executive_dark');
  const isStartup = (d.tplKey === 'emerald_startup');

  let skillsHtml = '';
  if (d.skills.length > 0) {
    if (isTech) {
      skillsHtml = `
        <div class="cva-sb-title">// TECH STACK</div>
        <div style="display:flex; flex-wrap:wrap; gap:5px; margin-bottom:12px;">
          ${d.skills.map(s => `
            <span class="cva-tech-badge"><i class="fa fa-code"></i> ${esc(s.name)} <strong>${s.level}%</strong></span>
          `).join('')}
        </div>
      `;
    } else {
      skillsHtml = `
        <div class="cva-sb-title">KỸ NĂNG</div>
        <div>${renderSkillsHtml(d.skills, true, d.accent)}</div>
      `;
    }
  }

  // Executive summary banner if executive_dark
  const execSummaryHtml = isExec ? `
    <div style="background:#f8fafc; border-left:4px solid #f59e0b; padding:10px 14px; margin-bottom:18px; border-radius:0 6px 6px 0; font-size:11.5px; color:#475569; font-style:italic;">
      <i class="fa fa-quote-left me-2 text-warning"></i>${esc(d.title || 'Chuyên viên cao cấp')} với định hướng chiến lược, năng lực lãnh đạo đội ngũ và tối ưu hóa hiệu suất vận hành doanh nghiệp.
    </div>
  ` : '';

  // Order of sections:
  // For Fresh Graduate: HỌC VẤN & HOẠT ĐỘNG được ưu tiên lên hàng đầu trước kinh nghiệm!
  let mainSectionsHtml = '';
  if (isFreshGrad) {
    mainSectionsHtml = `
      ${d.educations.length > 0 ? `
        <div class="cva-sec-title">HỌC VẤN & BẰNG CẤP</div>
        <div>${renderEducationHtml(d.educations)}</div>
      ` : ''}
      ${d.activities.length > 0 ? `
        <div class="cva-sec-title">HOẠT ĐỘNG NGOẠI KHÓA & DỰ ÁN</div>
        <div>${renderTagsHtml(d.activities, false)}</div>
      ` : ''}
      ${d.experiences.length > 0 ? `
        <div class="cva-sec-title">KINH NGHIỆM THỰC TẬP / LÀM VIỆC</div>
        <div>${renderExperienceHtml(d.experiences)}</div>
      ` : ''}
      ${d.certifications.length > 0 ? `
        <div class="cva-sec-title">CHỨNG CHỈ & GIẢI THƯỞNG</div>
        <div>${renderCertsHtml(d.certifications)}</div>
      ` : ''}
      ${d.references.length > 0 ? `
        <div class="cva-sec-title">NGƯỜI LIÊN HỆ / THAM CHIẾU</div>
        <div>${renderTagsHtml(d.references, false)}</div>
      ` : ''}
    `;
  } else {
    mainSectionsHtml = `
      ${d.experiences.length > 0 ? `
        <div class="cva-sec-title">KINH NGHIỆM LÀM VIỆC</div>
        <div>${renderExperienceHtml(d.experiences)}</div>
      ` : ''}
      ${d.educations.length > 0 ? `
        <div class="cva-sec-title">HỌC VẤN</div>
        <div>${renderEducationHtml(d.educations)}</div>
      ` : ''}
      ${d.certifications.length > 0 ? `
        <div class="cva-sec-title">CHỨNG CHỈ</div>
        <div>${renderCertsHtml(d.certifications)}</div>
      ` : ''}
      ${d.activities.length > 0 ? `
        <div class="cva-sec-title">HOẠT ĐỘNG / DỰ ÁN</div>
        <div>${renderTagsHtml(d.activities, false)}</div>
      ` : ''}
      ${d.references.length > 0 ? `
        <div class="cva-sec-title">NGƯỜI LIÊN HỆ</div>
        <div>${renderTagsHtml(d.references, false)}</div>
      ` : ''}
    `;
  }

  return `
    <div class="cva-sidebar">
      <div class="cva-avatar-wrap">
        <img src="${esc(d.avatar)}" alt="avatar">
      </div>
      <div class="cva-sb-title">${isTech ? '// LIÊN HỆ' : 'LIÊN HỆ'}</div>
      <div class="cva-contact-item">
        <i class="fa fa-phone"></i>
        <span class="${d.phone ? '' : 'cva-contact-placeholder'}">${esc(d.phone || 'Điền số điện thoại')}</span>
      </div>
      <div class="cva-contact-item">
        <i class="fa fa-envelope"></i>
        <span>${esc(d.email)}</span>
      </div>
      <div class="cva-contact-item">
        <i class="fa fa-calendar"></i>
        <span class="${d.birthday ? '' : 'cva-contact-placeholder'}">${esc(d.birthday || 'Chọn ngày sinh')}</span>
      </div>
      <div class="cva-contact-item">
        <i class="fa fa-map-marker"></i>
        <span class="${d.address ? '' : 'cva-contact-placeholder'}">${esc(d.address || 'VD: Trần Bình Trọng, Phường 1')}</span>
      </div>
      ${skillsHtml}
      ${d.languages.length > 0 ? `
        <div class="cva-sb-title">${isTech ? '// NGOẠI NGỮ' : 'NGOẠI NGỮ'}</div>
        <div>${renderTagsHtml(d.languages, true)}</div>
      ` : ''}
      ${d.itSkills.length > 0 ? `
        <div class="cva-sb-title">${isTech ? '// CÔNG CỤ DEV' : 'TIN HỌC'}</div>
        <div>${renderTagsHtml(d.itSkills, true)}</div>
      ` : ''}
    </div>
    <div class="cva-main">
      <div class="cva-name">${esc(d.name.toUpperCase())}</div>
      <div class="cva-jobtitle ${d.isTitlePlaceholder ? 'is-placeholder' : ''}">${esc(d.title.toUpperCase())}</div>
      ${execSummaryHtml}
      ${mainSectionsHtml}
    </div>
  `;
}

/* ============================================================
   BỐ CỤC 2: HEADER BANNER (Teal Mint, Royal Purple)
   ============================================================ */
function renderHeaderBannerHtml(d) {
  const isRoyal = (d.tplKey === 'royal_purple');
  if (isRoyal) {
    return `
      <div class="cva-banner-top" style="flex-direction:column; text-align:center; padding:28px 24px;">
        <img src="${esc(d.avatar)}" alt="avatar" class="cva-banner-avatar" style="margin:0 auto 10px; width:92px; height:92px;">
        <div class="cva-banner-name" style="font-size:24px; letter-spacing:1px;">${esc(d.name.toUpperCase())}</div>
        <div class="cva-banner-title ${d.isTitlePlaceholder ? 'is-placeholder' : ''}" style="margin-bottom:12px;">${esc(d.title.toUpperCase())}</div>
        <div class="cva-banner-contact" style="justify-content:center;">
          <span class="cva-b-pill"><i class="fa fa-envelope"></i> ${esc(d.email)}</span>
          <span class="cva-b-pill"><i class="fa fa-phone"></i> ${esc(d.phone || 'Điền số điện thoại')}</span>
          ${d.birthday ? `<span class="cva-b-pill"><i class="fa fa-calendar"></i> ${esc(d.birthday)}</span>` : ''}
          ${d.address ? `<span class="cva-b-pill"><i class="fa fa-map-marker"></i> ${esc(d.address)}</span>` : ''}
        </div>
      </div>
      <div class="cva-banner-body" style="grid-template-columns: 50% 50%; gap:24px;">
        <div>
          ${d.experiences.length > 0 ? `
            <div class="cva-banner-sec-title">KINH NGHIỆM LÀM VIỆC</div>
            <div>${renderExperienceHtml(d.experiences)}</div>
          ` : ''}
          ${d.activities.length > 0 ? `
            <div class="cva-banner-sec-title">HOẠT ĐỘNG & DỰ ÁN</div>
            <div>${renderTagsHtml(d.activities, false)}</div>
          ` : ''}
        </div>
        <div>
          ${d.educations.length > 0 ? `
            <div class="cva-banner-sec-title">HỌC VẤN</div>
            <div>${renderEducationHtml(d.educations)}</div>
          ` : ''}
          ${d.skills.length > 0 ? `
            <div class="cva-banner-sec-title">KỸ NĂNG CHUYÊN MÔN</div>
            <div>${renderSkillsHtml(d.skills, false, d.theme)}</div>
          ` : ''}
          ${d.languages.length > 0 ? `
            <div class="cva-banner-sec-title">NGOẠI NGỮ</div>
            <div>${renderTagsHtml(d.languages, false)}</div>
          ` : ''}
          ${d.certifications.length > 0 ? `
            <div class="cva-banner-sec-title">CHỨNG CHỈ</div>
            <div>${renderCertsHtml(d.certifications)}</div>
          ` : ''}
        </div>
      </div>
    `;
  }

  // Teal Mint (Standard Banner)
  return `
    <div class="cva-banner-top">
      <img src="${esc(d.avatar)}" alt="avatar" class="cva-banner-avatar">
      <div class="cva-banner-info">
        <div class="cva-banner-name">${esc(d.name.toUpperCase())}</div>
        <div class="cva-banner-title ${d.isTitlePlaceholder ? 'is-placeholder' : ''}">${esc(d.title.toUpperCase())}</div>
        <div class="cva-banner-contact">
          <span class="cva-b-pill"><i class="fa fa-envelope"></i> ${esc(d.email)}</span>
          <span class="cva-b-pill"><i class="fa fa-phone"></i> ${esc(d.phone || 'Điền số điện thoại')}</span>
          ${d.birthday ? `<span class="cva-b-pill"><i class="fa fa-calendar"></i> ${esc(d.birthday)}</span>` : ''}
          ${d.address ? `<span class="cva-b-pill"><i class="fa fa-map-marker"></i> ${esc(d.address)}</span>` : ''}
        </div>
      </div>
    </div>
    <div class="cva-banner-body">
      <div>
        ${d.skills.length > 0 ? `
          <div class="cva-banner-sec-title">KỸ NĂNG CHUYÊN MÔN</div>
          <div>${renderSkillsHtml(d.skills, false, d.theme)}</div>
        ` : ''}
        ${d.educations.length > 0 ? `
          <div class="cva-banner-sec-title">HỌC VẤN</div>
          <div>${renderEducationHtml(d.educations)}</div>
        ` : ''}
        ${d.languages.length > 0 ? `
          <div class="cva-banner-sec-title">NGOẠI NGỮ</div>
          <div>${renderTagsHtml(d.languages, false)}</div>
        ` : ''}
        ${d.itSkills.length > 0 ? `
          <div class="cva-banner-sec-title">TIN HỌC</div>
          <div>${renderTagsHtml(d.itSkills, false)}</div>
        ` : ''}
        ${d.certifications.length > 0 ? `
          <div class="cva-banner-sec-title">CHỨNG CHỈ</div>
          <div>${renderCertsHtml(d.certifications)}</div>
        ` : ''}
      </div>
      <div>
        ${d.experiences.length > 0 ? `
          <div class="cva-banner-sec-title">KINH NGHIỆM LÀM VIỆC</div>
          <div>${renderExperienceHtml(d.experiences)}</div>
        ` : ''}
        ${d.activities.length > 0 ? `
          <div class="cva-banner-sec-title">HOẠT ĐỘNG & DỰ ÁN</div>
          <div>${renderTagsHtml(d.activities, false)}</div>
        ` : ''}
        ${d.references.length > 0 ? `
          <div class="cva-banner-sec-title">NGƯỜI LIÊN HỆ</div>
          <div>${renderTagsHtml(d.references, false)}</div>
        ` : ''}
      </div>
    </div>
  `;
}

/* ============================================================
   BỐ CỤC 3: HEADER SPLIT (Creative Coral, Classic Serif, Burgundy)
   ============================================================ */
function renderHeaderSplitHtml(d) {
  return `
    <div class="cva-split-top">
      <div class="cva-split-info">
        <div class="cva-split-name">${esc(d.name.toUpperCase())}</div>
        <div class="cva-split-title ${d.isTitlePlaceholder ? 'is-placeholder' : ''}">${esc(d.title.toUpperCase())}</div>
        <div class="cva-split-contact">
          <span><i class="fa fa-envelope" style="color:var(--cv-theme)"></i> ${esc(d.email)}</span>
          <span><i class="fa fa-phone" style="color:var(--cv-theme)"></i> ${esc(d.phone || 'Điền số điện thoại')}</span>
          ${d.birthday ? `<span><i class="fa fa-calendar" style="color:var(--cv-theme)"></i> ${esc(d.birthday)}</span>` : ''}
          ${d.address ? `<span><i class="fa fa-map-marker" style="color:var(--cv-theme)"></i> ${esc(d.address)}</span>` : ''}
        </div>
      </div>
      <div class="cva-split-avatar-wrap">
        <img src="${esc(d.avatar)}" alt="avatar" class="cva-split-avatar">
      </div>
    </div>
    <div class="cva-split-body">
      <div>
        ${d.experiences.length > 0 ? `
          <div class="cva-split-sec-title">KINH NGHIỆM LÀM VIỆC</div>
          <div>${renderExperienceHtml(d.experiences)}</div>
        ` : ''}
        ${d.activities.length > 0 ? `
          <div class="cva-split-sec-title">DỰ ÁN & HOẠT ĐỘNG</div>
          <div>${renderTagsHtml(d.activities, false)}</div>
        ` : ''}
      </div>
      <div>
        ${d.educations.length > 0 ? `
          <div class="cva-split-sec-title">HỌC VẤN</div>
          <div>${renderEducationHtml(d.educations)}</div>
        ` : ''}
        ${d.skills.length > 0 ? `
          <div class="cva-split-sec-title">KỸ NĂNG</div>
          <div>${renderSkillsHtml(d.skills, false, d.theme)}</div>
        ` : ''}
        ${d.certifications.length > 0 ? `
          <div class="cva-split-sec-title">CHỨNG CHỈ</div>
          <div>${renderCertsHtml(d.certifications)}</div>
        ` : ''}
        ${d.languages.length > 0 ? `
          <div class="cva-split-sec-title">NGOẠI NGỮ</div>
          <div>${renderTagsHtml(d.languages, false)}</div>
        ` : ''}
        ${d.references.length > 0 ? `
          <div class="cva-split-sec-title">NGƯỜI LIÊN HỆ</div>
          <div>${renderTagsHtml(d.references, false)}</div>
        ` : ''}
      </div>
    </div>
  `;
}

/* ============================================================
   BỐ CỤC 4: SINGLE COLUMN / ATS MINIMALIST (ATS Standard, Minimalist)
   ============================================================ */
function renderSingleColumnHtml(d) {
  const isAts = (d.tplKey === 'ats_standard');
  if (isAts) {
    return `
      <div class="cva-single-top" style="border-bottom: 2px solid #000; padding: 28px 36px 14px; text-align:center;">
        <div class="cva-single-name" style="font-size:24px; font-weight:800; letter-spacing:1px; color:#000;">${esc(d.name.toUpperCase())}</div>
        <div class="cva-single-title ${d.isTitlePlaceholder ? 'is-placeholder' : ''}" style="color:#333; margin-bottom:8px; font-weight:600;">${esc(d.title.toUpperCase())}</div>
        <div class="cva-single-contact" style="justify-content:center; gap:8px 14px; font-size:11.5px; color:#111;">
          <span><strong>Email:</strong> ${esc(d.email)}</span>
          <span>|</span>
          <span><strong>Phone:</strong> ${esc(d.phone || 'Chưa điền')}</span>
          ${d.address ? `<span>|</span><span><strong>Địa chỉ:</strong> ${esc(d.address)}</span>` : ''}
          ${d.birthday ? `<span>|</span><span><strong>Ngày sinh:</strong> ${esc(d.birthday)}</span>` : ''}
        </div>
      </div>
      <div class="cva-single-body" style="padding: 20px 36px 40px;">
        ${d.experiences.length > 0 ? `
          <div class="cva-single-sec-title" style="border-bottom:1.5px solid #000; color:#000;">KINH NGHIỆM LÀM VIỆC</div>
          <div>${renderExperienceAtsHtml(d.experiences)}</div>
        ` : ''}
        ${d.educations.length > 0 ? `
          <div class="cva-single-sec-title" style="border-bottom:1.5px solid #000; color:#000;">TRÌNH ĐỘ HỌC VẤN</div>
          <div>${renderEducationAtsHtml(d.educations)}</div>
        ` : ''}
        ${d.skills.length > 0 ? `
          <div class="cva-single-sec-title" style="border-bottom:1.5px solid #000; color:#000;">KỸ NĂNG CHUYÊN MÔN</div>
          <div>${renderSkillsAtsHtml(d.skills)}</div>
        ` : ''}
        ${d.certifications.length > 0 ? `
          <div class="cva-single-sec-title" style="border-bottom:1.5px solid #000; color:#000;">CHỨNG CHỈ</div>
          <div>${renderCertsHtml(d.certifications)}</div>
        ` : ''}
        ${d.activities.length > 0 ? `
          <div class="cva-single-sec-title" style="border-bottom:1.5px solid #000; color:#000;">DỰ ÁN & HOẠT ĐỘNG</div>
          <div>${renderTagsHtml(d.activities, false)}</div>
        ` : ''}
        ${d.languages.length > 0 ? `
          <div class="cva-single-sec-title" style="border-bottom:1.5px solid #000; color:#000;">NGOẠI NGỮ</div>
          <div>${renderTagsHtml(d.languages, false)}</div>
        ` : ''}
      </div>
    `;
  }

  // Minimalist clean with avatar
  return `
    <div class="cva-single-top" style="display:flex; align-items:center; justify-content:space-between; padding:28px 36px 18px; text-align:left; border-bottom:2px solid var(--cv-theme);">
      <div>
        <div class="cva-single-name" style="color:var(--cv-theme);">${esc(d.name.toUpperCase())}</div>
        <div class="cva-single-title ${d.isTitlePlaceholder ? 'is-placeholder' : ''}">${esc(d.title.toUpperCase())}</div>
        <div class="cva-single-contact" style="justify-content:flex-start; margin-top:8px;">
          <span><i class="fa fa-envelope me-1" style="color:var(--cv-theme)"></i>${esc(d.email)}</span>
          <span>•</span>
          <span><i class="fa fa-phone me-1" style="color:var(--cv-theme)"></i>${esc(d.phone || 'Chưa điền SĐT')}</span>
          ${d.address ? `<span>•</span><span><i class="fa fa-map-marker me-1" style="color:var(--cv-theme)"></i>${esc(d.address)}</span>` : ''}
        </div>
      </div>
      <img src="${esc(d.avatar)}" alt="avatar" style="width:76px; height:76px; border-radius:50%; object-fit:cover; border:2.5px solid var(--cv-theme); box-shadow:0 3px 10px rgba(0,0,0,0.1);">
    </div>
    <div class="cva-single-body">
      ${d.experiences.length > 0 ? `
        <div class="cva-single-sec-title" style="color:var(--cv-theme); border-bottom:1.5px solid var(--cv-theme);">KINH NGHIỆM LÀM VIỆC</div>
        <div>${renderExperienceAtsHtml(d.experiences)}</div>
      ` : ''}
      ${d.educations.length > 0 ? `
        <div class="cva-single-sec-title" style="color:var(--cv-theme); border-bottom:1.5px solid var(--cv-theme);">TRÌNH ĐỘ HỌC VẤN</div>
        <div>${renderEducationAtsHtml(d.educations)}</div>
      ` : ''}
      ${d.skills.length > 0 ? `
        <div class="cva-single-sec-title" style="color:var(--cv-theme); border-bottom:1.5px solid var(--cv-theme);">KỸ NĂNG CHUYÊN MÔN</div>
        <div>${renderSkillsAtsHtml(d.skills)}</div>
      ` : ''}
      ${d.certifications.length > 0 ? `
        <div class="cva-single-sec-title" style="color:var(--cv-theme); border-bottom:1.5px solid var(--cv-theme);">CHỨNG CHỈ & GIẢI THƯỞNG</div>
        <div>${renderCertsHtml(d.certifications)}</div>
      ` : ''}
      ${d.activities.length > 0 ? `
        <div class="cva-single-sec-title" style="color:var(--cv-theme); border-bottom:1.5px solid var(--cv-theme);">DỰ ÁN & HOẠT ĐỘNG</div>
        <div>${renderTagsHtml(d.activities, false)}</div>
      ` : ''}
      ${d.languages.length > 0 ? `
        <div class="cva-single-sec-title" style="color:var(--cv-theme); border-bottom:1.5px solid var(--cv-theme);">NGOẠI NGỮ</div>
        <div>${renderTagsHtml(d.languages, false)}</div>
      ` : ''}
      ${d.references.length > 0 ? `
        <div class="cva-single-sec-title" style="color:var(--cv-theme); border-bottom:1.5px solid var(--cv-theme);">NGƯỜI LIÊN HỆ</div>
        <div>${renderTagsHtml(d.references, false)}</div>
      ` : ''}
    </div>
  `;
}

/* ============================================================
   MAIN RENDER FUNCTION
   ============================================================ */
function renderPreview() {
  const theme = document.getElementById('cv_theme_color').value || '#00b14f';
  const tplKey = document.getElementById('cv_template_key').value || 'emerald_startup';
  const tpl = (typeof CV_TEMPLATES !== 'undefined' && CV_TEMPLATES[tplKey]) 
      ? CV_TEMPLATES[tplKey] 
      : { layout: 'sidebar_left', primary_color: '#00b14f', accent_color: '#4ade80', font: 'Lexend' };

  const cvA4 = document.getElementById('cvA4');
  if (!cvA4) return;

  cvA4.style.setProperty('--cv-theme', theme);
  cvA4.style.setProperty('--cv-sidebar-bg', theme);
  cvA4.style.setProperty('--cv-accent', tpl.accent_color || '#4ade80');
  cvA4.style.fontFamily = `'${tpl.font || 'Lexend'}', sans-serif`;

  const layout = tpl.layout || 'sidebar_left';
  cvA4.className = 'cv-a4 cv-layout-' + layout + ' cv-template-' + tplKey;

  const fName = (document.getElementById('f_fullname').value || '').trim();
  const fTitle = (document.getElementById('f_candidate_title').value || '').trim();
  const fEmail = (document.getElementById('f_email').value || '').trim() || 'email@example.com';
  const fPhone = (document.getElementById('f_phone').value || '').trim();
  const fBirthday = (document.getElementById('f_birthday').value || '').trim();
  const fAddress = (document.getElementById('f_address').value || '').trim();
  const avatarUrl = document.getElementById('cv_avatar_url').value;

  const data = {
    tplKey: tplKey,
    name: fName || 'HỌ VÀ TÊN',
    title: fTitle || 'TIÊU ĐỀ HỒ SƠ',
    isTitlePlaceholder: !fTitle,
    email: fEmail,
    phone: fPhone,
    birthday: fBirthday,
    address: fAddress,
    avatar: avatarUrl,
    theme: theme,
    accent: tpl.accent_color || '#4ade80',
    experiences: getExpData(),
    educations: getEduData(),
    skills: getSkillsData(),
    languages: getLanguagesData(),
    itSkills: getItSkillsData(),
    references: getReferencesData(),
    activities: getActivitiesData(),
    certifications: getCertsData(),
  };

  if (layout === 'header_banner') {
    cvA4.innerHTML = renderHeaderBannerHtml(data);
  } else if (layout === 'header_split') {
    cvA4.innerHTML = renderHeaderSplitHtml(data);
  } else if (layout === 'single_column') {
    cvA4.innerHTML = renderSingleColumnHtml(data);
  } else {
    cvA4.innerHTML = renderSidebarLeftHtml(data);
  }

  if (typeof fitA4Preview === 'function') {
    fitA4Preview();
  }
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
  } else if (type === 'cert') {
    html = `
      <div class="cvb-entry-card" data-section="cert">
        <button type="button" class="cvb-del-btn" onclick="delCard(this)"><i class="fa fa-times"></i></button>
        <div class="mb-2">
          <label class="cvb-label">Tên chứng chỉ / Giải thưởng</label>
          <input class="cvb-input cert-name" placeholder="VD: Chứng chỉ IELTS 7.5">
        </div>
        <div class="row g-2">
          <div class="col-7">
            <label class="cvb-label">Tổ chức cấp</label>
            <input class="cvb-input cert-org" placeholder="VD: British Council">
          </div>
          <div class="col-5">
            <label class="cvb-label">Năm cấp</label>
            <input class="cvb-input cert-year" placeholder="VD: 2023">
          </div>
        </div>
      </div>
    `;
    document.getElementById('cert_list').insertAdjacentHTML('beforeend', html);
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
      <span class="fw-bold lang-item-text">${esc(name)}</span>
      <button type="button" class="btn btn-sm text-danger" onclick="this.parentElement.remove(); renderPreview()"><i class="fa fa-times"></i></button>
    </div>
  `;
  document.getElementById('lang_list').insertAdjacentHTML('beforeend', html);
  renderPreview();
}

function addItSkill() {
  const name = prompt('Nhập kỹ năng tin học (VD: MS Word, Excel, Photoshop):');
  if (!name) return;
  const html = `
    <div class="d-flex align-items-center justify-content-between p-2 mb-2 bg-light border rounded">
      <span class="fw-bold it-item-text">${esc(name)}</span>
      <button type="button" class="btn btn-sm text-danger" onclick="this.parentElement.remove(); renderPreview()"><i class="fa fa-times"></i></button>
    </div>
  `;
  document.getElementById('it_list').insertAdjacentHTML('beforeend', html);
  renderPreview();
}

function addReference() {
  const name = prompt('Nhập thông tin người liên hệ (Họ tên, chức vụ, SĐT):');
  if (!name) return;
  const html = `
    <div class="d-flex align-items-center justify-content-between p-2 mb-2 bg-light border rounded">
      <span class="ref-item-text">${esc(name)}</span>
      <button type="button" class="btn btn-sm text-danger" onclick="this.parentElement.remove(); renderPreview()"><i class="fa fa-times"></i></button>
    </div>
  `;
  document.getElementById('ref_list').insertAdjacentHTML('beforeend', html);
  renderPreview();
}

function addActivity() {
  const name = prompt('Nhập tên hoạt động / dự án:');
  if (!name) return;
  const html = `
    <div class="d-flex align-items-center justify-content-between p-2 mb-2 bg-light border rounded">
      <span class="act-item-text">${esc(name)}</span>
      <button type="button" class="btn btn-sm text-danger" onclick="this.parentElement.remove(); renderPreview()"><i class="fa fa-times"></i></button>
    </div>
  `;
  document.getElementById('act_list').insertAdjacentHTML('beforeend', html);
  renderPreview();
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
        document.getElementById('cv_avatar_url').value = res.avatar_url;
        renderPreview();
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
  const expData = getExpData();
  const eduData = getEduData();
  const skillsData = getSkillsData();
  const certData = getCertsData();
  const actData = getActivitiesData();

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
    projects_data: JSON.stringify(actData),
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
