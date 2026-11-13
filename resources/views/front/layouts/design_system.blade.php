{{-- ===================================================
  TOPWORK — Unified Design System CSS + Modern Animations
  Inspired by: GitHub Primer, Atlassian Design System, IBM Carbon
  Principles: 4px grid spacing, clear type scale, neutral palette + green accent
  Enhanced: Smooth animations, hover effects, modern gradients
  Applied globally to all front-end layouts
  ================================================= --}}
<style>
/* ===================================================
   DESIGN TOKENS + ANIMATION TOKENS
   =================================================
   Spacing: 4px base grid (4, 8, 12, 16, 24, 32, 48, 64)
   Type:    14px body, 12px small, 16px lead, scale up to 36px headings
   Color:   Neutral grays + TopWork green #00b14f
   Animations: Smooth transitions, fade-in effects
*/

:root {
  /* Colors — Neutral */
  --c-white:   #ffffff;
  --c-gray-50: #f9fafb;
  --c-gray-100:#f3f4f6;
  --c-gray-200:#e5e7eb;
  --c-gray-300:#d1d5db;
  --c-gray-400:#9ca3af;
  --c-gray-500:#6b7280;
  --c-gray-600:#4b5563;
  --c-gray-700:#374151;
  --c-gray-800:#1f2937;
  --c-gray-900:#111827;

  /* Colors — Brand (Enhanced gradient) */
  --c-brand:       #00b14f;
  --c-brand-dark:  #009140;
  --c-brand-light: #ecfdf5;
  --c-brand-muted: rgba(0,177,79,0.12);
  --c-brand-gradient: linear-gradient(135deg, #00b14f 0%, #00d66a 100%);
  --c-accent-blue: #0066ff;
  --c-accent-purple: #9333ea;

  /* Colors — Semantic */
  --c-text:        var(--c-gray-900);
  --c-text-sub:    var(--c-gray-500);
  --c-text-inv:    var(--c-white);
  --c-border:      var(--c-gray-200);
  --c-bg:          var(--c-gray-50);
  --c-bg-white:    var(--c-white);

  /* Typography — Lexend */
  --font:                'Lexend', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  --bs-body-font-family: 'Lexend', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
  --bs-font-sans-serif:  'Lexend', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
  --text-xs:   11px;
  --text-sm:   12px;
  --text-base: 14px;
  --text-md:   15px;
  --text-lg:   16px;
  --text-xl:   20px;
  --text-2xl:  24px;
  --text-3xl:  30px;
  --text-4xl:  38px;
  --lh-tight:  1.25;
  --lh-base:   1.5;
  --lh-loose:  1.7;

  /* Spacing (4px grid) */
  --sp-1:  4px;
  --sp-2:  8px;
  --sp-3:  12px;
  --sp-4:  16px;
  --sp-5:  20px;
  --sp-6:  24px;
  --sp-8:  32px;
  --sp-10: 40px;
  --sp-12: 48px;
  --sp-16: 64px;

  /* Radius */
  --r-sm:  4px;
  --r-md:  6px;
  --r-lg:  8px;
  --r-xl:  12px;

  /* Shadow — Enhanced */
  --shadow-sm: 0 1px 3px rgba(0,0,0,0.08), 0 1px 2px rgba(0,0,0,0.04);
  --shadow-md: 0 4px 12px rgba(0,0,0,0.10);
  --shadow-lg: 0 8px 28px rgba(0,0,0,0.12);
  --shadow-hero: 0 16px 48px rgba(0,0,0,0.22);
  --shadow-xl: 0 20px 60px rgba(0,0,0,0.15);

  /* Transitions */
  --transition-fast: 0.15s ease-in-out;
  --transition-base: 0.25s ease-in-out;
  --transition-smooth: 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
}

/* ===================================================
   ANIMATIONS (Keyframes)
   ================================================= */
@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes slideDown {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes slideInLeft {
  from {
    opacity: 0;
    transform: translateX(-30px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

@keyframes slideInRight {
  from {
    opacity: 0;
    transform: translateX(30px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

@keyframes scaleIn {
  from {
    opacity: 0;
    transform: scale(0.95);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}

@keyframes pulse {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.5; }
}

@keyframes float {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-10px); }
}

@keyframes glow {
  0%, 100% { box-shadow: 0 0 10px rgba(0, 177, 79, 0.3); }
  50% { box-shadow: 0 0 20px rgba(0, 177, 79, 0.5); }
}

/* ===================================================
   BASE RESET & TYPOGRAPHY (Global font: Lexend, Lexend-Bold)
   ================================================= */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

html, body,
button, input, select, textarea, optgroup,
h1, h2, h3, h4, h5, h6,
p, span, a, label, li, td, th,
.btn, .form-control, .form-select, .nav-link,
.dropdown-menu, .dropdown-item, .card, .badge {
  font-family: 'Lexend', var(--font) !important;
}

body {
  font-family: 'Lexend', var(--font) !important;
  font-size: var(--text-base);
  line-height: var(--lh-base);
  color: var(--c-text);
  background: var(--c-white);
  -webkit-font-smoothing: antialiased;
  scroll-behavior: smooth;
}

h1, h2, h3, h4, h5, h6, b, strong, th, .fw-bold, .font-weight-bold {
  font-family: 'Lexend', var(--font) !important;
  font-weight: 700;
}

html { scroll-behavior: smooth; }

/* ===================================================
   HEADER — Vieclam24h Deep Purple Theme (#4a0d8e)
   ================================================= */
.tw-header {
  position: sticky; top: 0; z-index: 2000 !important;
  background: #4a0d8e;
  border-bottom: 1px solid rgba(255, 255, 255, 0.08);
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
}
.tw-navbar { width: 100%; position: relative; }
.tw-nav-inner {
  display: flex; align-items: center; justify-content: space-between;
  height: 60px;
  max-width: 1320px; margin: 0 auto; padding: 0 var(--sp-6);
  position: relative;
}
.tw-logo {
  display: flex; align-items: center; gap: 8px; text-decoration: none; flex-shrink: 0;
}
.tw-logo-img {
  height: 36px; width: 36px; object-fit: cover; border-radius: 8px;
  border: 1.5px solid rgba(255, 255, 255, 0.3);
}
.tw-logo-text {
  color: #fff; font-weight: 800; font-size: 19px; display: flex; flex-direction: column;
  line-height: 1; letter-spacing: -0.5px;
}
.tw-logo-text small {
  font-size: 8px; font-weight: 700; color: #fde047; letter-spacing: 0.8px; margin-top: 2px;
}

.tw-hamburger {
  display: none; flex-direction: column; justify-content: center; gap: 5px;
  background: none; border: none; cursor: pointer; padding: var(--sp-2); margin-left: auto;
}
.tw-hamburger span { display: block; width: 20px; height: 2px; background: #fff; border-radius: 2px; }

.tw-nav-collapse { display: flex !important; align-items: center; flex: 1; }
.tw-nav-links {
  display: flex; align-items: center; gap: 4px;
  list-style: none; margin: 0 0 0 24px; padding: 0; flex: 1;
}
.tw-nav-link {
  display: inline-flex; align-items: center;
  padding: 8px 12px; border-radius: 8px;
  font-size: 14px; font-weight: 600; color: #ffffff !important;
  text-decoration: none; transition: all 0.15s; white-space: nowrap;
}
.tw-nav-link:hover, .tw-nav-dropdown:hover > .tw-nav-link {
  background: rgba(255, 255, 255, 0.14); color: #ffffff !important;
}
.tw-nav-link.active {
  background: rgba(255, 255, 255, 0.2); color: #ffffff !important;
}
.tw-nav-dropdown .dropdown-toggle::after,
.tw-user-pill.dropdown-toggle::after,
.tw-user-dropdown .dropdown-toggle::after {
  display: none !important;
}
.tw-nav-dropdown { position: relative; }

/* Desktop hover opening for nav dropdowns */
@media (min-width: 992px) {
  .tw-nav-dropdown:hover > .dropdown-menu,
  .tw-user-dropdown:hover > .dropdown-menu {
    display: block;
    animation: twDropdownFade 0.15s ease;
  }
}
@keyframes twDropdownFade {
  from { opacity: 0; transform: translateY(4px); }
  to { opacity: 1; transform: translateY(0); }
}

.tw-nav-dropdown .dropdown-menu,
.tw-user-dropdown .dropdown-menu,
.tw-dropdown-vieclam,
.tw-dropdown-congcu,
.tw-dropdown-user-card {
  z-index: 2100 !important;
  position: absolute !important;
  font-size: 12.5px;
}
.tw-nav-dropdown .dropdown-menu .dropdown-item,
.tw-user-dropdown .dropdown-menu .dropdown-item {
  font-size: 12.5px !important;
}

/* Invisible bridge so mouse hover doesn't break between button and menu */
.tw-nav-dropdown .dropdown-menu::before,
.tw-user-dropdown .dropdown-menu::before {
  content: '';
  position: absolute;
  top: -12px;
  left: 0;
  right: 0;
  height: 12px;
  background: transparent;
}

/* Dropdown Việc làm (Matching Image 2) */
.tw-dropdown-vieclam {
  min-width: 260px;
  border-radius: 14px;
  border: 1px solid #e2e8f0;
  box-shadow: 0 14px 35px rgba(0, 0, 0, 0.14);
  padding: 8px 6px;
  margin-top: 6px !important;
  background: #ffffff;
}
.tw-dd-main-link {
  font-size: 12.5px !important; font-weight: 600 !important; color: #1e293b;
  padding: 6px 10px !important; border-radius: 6px; display: flex; align-items: center; gap: 8px;
  transition: all 0.15s ease;
}
.tw-dd-main-link:hover {
  background: #4a0d8e !important;
  color: #ffffff !important;
}
.tw-dd-main-link:hover i,
.tw-dd-main-link:hover span {
  color: #ffffff !important;
}

.tw-dd-group-header {
  font-size: 12px !important; font-weight: 600 !important; color: #1e293b;
  padding: 5px 10px !important; display: flex; align-items: center; justify-content: space-between;
}
.tw-dd-sublist { padding-left: 6px; }
.tw-dd-subitem {
  font-size: 11.5px !important; font-weight: 400 !important; color: #475569; padding: 5px 10px !important; border-radius: 6px;
  transition: all 0.15s ease;
}
.tw-dd-subitem:hover {
  background: #4a0d8e !important;
  color: #ffffff !important;
  font-weight: 500 !important;
}
.tw-dd-subitem:hover span:not(.badge) {
  color: #ffffff !important;
}

/* Dropdown Công cụ */
.tw-dropdown-congcu {
  min-width: 210px;
  border-radius: 12px;
  border: 1px solid #e2e8f0;
  box-shadow: 0 14px 35px rgba(0, 0, 0, 0.14);
  padding: 6px 4px;
  margin-top: 6px !important;
  background: #ffffff;
}
.tw-dropdown-congcu .dropdown-item {
  font-size: 12px !important; font-weight: 500 !important; color: #334155;
  padding: 6px 10px !important; border-radius: 6px;
  transition: all 0.15s ease;
}
.tw-dropdown-congcu .dropdown-item:hover {
  background: #4a0d8e !important;
  color: #ffffff !important;
}
.tw-dropdown-congcu .dropdown-item:hover i {
  color: #ffffff !important;
}

/* Nav Actions on the right */
.tw-nav-actions { display: flex; align-items: center; gap: 10px; flex-shrink: 0; }

/* Bell icon (Thông báo) */
.tw-nav-bell {
  width: 38px; height: 38px; border-radius: 50%;
  display: inline-flex; align-items: center; justify-content: center;
  color: #ffffff; background: rgba(255, 255, 255, 0.12);
  text-decoration: none; font-size: 16px; transition: all 0.15s; position: relative;
}
.tw-nav-bell:hover { background: rgba(255, 255, 255, 0.22); color: #fff; }

/* User Profile Pill & Dropdown (Matching Image 3) */
.tw-user-pill {
  display: inline-flex; align-items: center; gap: 8px;
  padding: 3px 12px 3px 4px; background: #ffffff;
  color: #1e293b !important; border-radius: 999px;
  text-decoration: none; font-weight: 600; font-size: 13px;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.12); transition: all 0.15s;
}
.tw-user-pill:hover { background: #f8fafc; transform: translateY(-1px); }
.tw-user-pill-avatar {
  width: 32px; height: 32px; border-radius: 50%; object-fit: cover;
  border: 1.5px solid #e2e8f0;
}
.tw-user-pill-name {
  max-width: 120px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
}
.tw-user-pill-caret {
  font-size: 10px; color: #00b14f; margin-left: 2px;
}
.tw-dropdown-user-card {
  min-width: 220px;
  box-shadow: 0 14px 35px rgba(0, 0, 0, 0.14);
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  padding: 4px 0;
  margin-top: 6px !important;
}
.tw-dropdown-user-card .dropdown-item {
  font-size: 12px !important; font-weight: 500 !important; color: #334155; padding: 6px 14px !important;
  transition: all 0.15s ease;
}
.tw-dropdown-user-card .dropdown-item:hover {
  background: #4a0d8e !important;
  color: #ffffff !important;
}
.tw-dropdown-user-card .dropdown-item:hover i,
.tw-dropdown-user-card .dropdown-item:hover span:not(.badge) {
  color: #ffffff !important;
}
.tw-dropdown-user-card .dropdown-item.text-danger:hover {
  background: #fee2e2 !important;
  color: #dc2626 !important;
}
.tw-dropdown-user-card .dropdown-item.text-danger:hover i {
  color: #dc2626 !important;
}

/* Nav dropdown general hover */
.tw-nav-dropdown .dropdown-menu .dropdown-item:hover {
  background: #4a0d8e !important;
  color: #ffffff !important;
}

/* Button DÀNH CHO Nhà Tuyển Dụng */
.tw-btn-ntd {
  display: inline-flex; align-items: center; gap: 8px;
  color: #ffffff; text-decoration: none; padding: 4px 10px;
  border-radius: 8px; transition: all 0.15s; font-size: 13px;
}
.tw-btn-ntd:hover { background: rgba(255, 255, 255, 0.14); color: #fff; }
.tw-btn-ntd i { font-size: 20px; color: #38bdf8; }
.tw-ntd-text { display: flex; flex-direction: column; line-height: 1.15; }
.tw-ntd-text small { font-size: 9px; opacity: 0.8; text-transform: uppercase; font-weight: 600; }
.tw-ntd-text span { font-size: 12px; font-weight: 700; white-space: nowrap; }

/* Guest buttons */
.tw-btn-guest-login {
  color: #fff; text-decoration: none; font-size: 13.5px; font-weight: 600;
  padding: 6px 14px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.4);
  transition: all 0.15s;
}
.tw-btn-guest-login:hover { background: rgba(255,255,255,0.15); color: #fff; }
.tw-btn-guest-reg {
  background: #fff; color: #4a0d8e !important; text-decoration: none;
  font-size: 13.5px; font-weight: 700; padding: 6px 14px; border-radius: 8px;
  transition: all 0.15s;
}
.tw-btn-guest-reg:hover { background: #f3e8ff; }

/* Language Flag */
.tw-lang-pill {
  display: inline-flex; align-items: center; padding: 4px 6px;
  background: rgba(255, 255, 255, 0.12); border-radius: 6px; cursor: pointer;
}
.tw-flag-img { width: 20px; height: 14px; border-radius: 2px; }

/* Mobile navigation collapse */
@media (max-width: 991px) {
  .tw-hamburger { display: flex; }
  .tw-nav-collapse {
    display: none !important;
    position: absolute; top: 60px; left: 0; right: 0;
    background: #4a0d8e; padding: 16px 20px;
    flex-direction: column; align-items: flex-start;
    box-shadow: 0 10px 20px rgba(0,0,0,0.25);
  }
  .tw-nav-collapse.open { display: flex !important; }
  .tw-nav-links { flex-direction: column; margin: 0; width: 100%; gap: 6px; }
  .tw-nav-link { width: 100%; }
  .tw-nav-actions { margin-top: 14px; width: 100%; flex-wrap: wrap; }
}

/* Hero Carousel Banners */
.tw-hero-carousel {
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 12px 30px rgba(0, 0, 0, 0.25);
  background: #000;
}
.tw-hero-carousel .carousel-indicators [data-bs-target] {
  width: 10px;
  height: 10px;
  border-radius: 50%;
  margin: 0 4px;
  background-color: rgba(255, 255, 255, 0.6);
  border: none;
  transition: all 0.25s ease;
}
.tw-hero-carousel .carousel-indicators .active {
  width: 24px;
  border-radius: 5px;
  background-color: var(--c-brand);
}
.tw-hero-carousel .carousel-caption {
  left: 0;
  right: 0;
  bottom: 0;
  padding: 16px 20px;
  background: linear-gradient(180deg, transparent 0%, rgba(10, 20, 15, 0.75) 40%, rgba(6, 15, 10, 0.95) 100%);
  text-align: left;
}
.tw-hero-banner-img {
  width: 100%;
  height: 330px;
  object-fit: cover;
  transition: transform 0.4s ease;
}
.tw-hero-carousel:hover .tw-hero-banner-img {
  transform: scale(1.02);
}

/* Scroll progress */
.tw-progress-bar-wrap { height: 2px; background: transparent; }
.tw-progress-bar { height: 2px; background: var(--c-brand); width: 0%; transition: width 0.2s; }

@media (max-width: 991px) {
  .tw-hamburger { display: flex; }
  .tw-nav-collapse {
    display: none !important;
    flex-direction: column; align-items: flex-start;
    position: absolute; top: 57px; left: 0; right: 0;
    background: var(--c-white); border-top: 1px solid var(--c-border);
    padding: var(--sp-3) var(--sp-4) var(--sp-4); box-shadow: var(--shadow-md);
    z-index: 999;
  }
  .tw-nav-collapse.open { display: flex !important; }
  .tw-nav-links { flex-direction: column; margin: 0; width: 100%; }
  .tw-nav-link { width: 100%; padding: var(--sp-2) var(--sp-3); }
  .tw-nav-actions { margin-top: var(--sp-3); width: 100%; }
}

/* ===================================================
   HOME — HERO  (dark green gradient + floating blobs)
   ================================================= */
.tw-hero {
  background: linear-gradient(135deg, #06281a 0%, #0a3d24 45%, #0f172a 100%);
  padding: var(--sp-16) 0;
  position: relative; overflow: hidden;
}
/* Animated brand glow */
.tw-hero::before {
  content: '';
  position: absolute; inset: 0; pointer-events: none;
  background: radial-gradient(ellipse at 70% 30%, rgba(0,177,79,0.28) 0%, transparent 55%);
  animation: heroGlow 6s ease-in-out infinite;
}
@keyframes heroGlow {
  0%, 100% { opacity: 0.6; transform: scale(1); }
  50% { opacity: 1; transform: scale(1.15); }
}
/* Floating decoration circles */
.tw-hero::after {
  content: '';
  position: absolute; bottom: -100px; right: -100px;
  width: 300px; height: 300px; border-radius: 50%;
  background: radial-gradient(circle, rgba(0,177,79,0.18) 0%, transparent 70%);
  animation: float 6s ease-in-out infinite;
}
.tw-hero-inner {
  position: relative; z-index: 1;
  animation: slideInLeft 0.8s ease-out;
}
/* Decorative floating blobs (markup: .tw-hero-blob) */
.tw-hero-blob {
  position: absolute; border-radius: 50%; pointer-events: none; z-index: 0;
  background: radial-gradient(circle at 30% 30%, rgba(0,214,106,0.35), rgba(0,177,79,0.05) 70%);
  filter: blur(2px);
}
.tw-hero-blob-1 { width: 90px; height: 90px; top: 12%; left: 46%; animation: float 5s ease-in-out infinite; }
.tw-hero-blob-2 { width: 46px; height: 46px; top: 68%; left: 40%; animation: float 7s ease-in-out 1s infinite; }
.tw-hero-blob-3 { width: 26px; height: 26px; top: 24%; left: 88%; animation: float 6s ease-in-out 2s infinite; }

.tw-hero-eyebrow {
  display: inline-block; margin-bottom: var(--sp-4);
  font-size: var(--text-sm); font-weight: 600; letter-spacing: 0.08em;
  color: #4ade80; text-transform: uppercase;
  padding: 6px 14px; border: 1px solid rgba(74,222,128,0.35); border-radius: 999px;
  background: rgba(0,177,79,0.12);
  animation: fadeIn 0.6s ease-out;
}
.tw-hero h1 {
  font-size: clamp(30px, 4.6vw, 46px);
  font-weight: 800; line-height: var(--lh-tight);
  color: var(--c-white); margin-bottom: var(--sp-4);
  letter-spacing: -0.02em;
  animation: slideUp 0.8s ease-out 0.2s both;
}
.tw-hero h1 em {
  font-style: normal;
  background: linear-gradient(90deg, #4ade80 0%, #00d66a 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}
.tw-hero-sub {
  font-size: var(--text-md); color: #9fb3a8;
  line-height: var(--lh-loose); margin-bottom: var(--sp-10);
  max-width: 480px;
  animation: slideUp 0.8s ease-out 0.4s both;
}
.tw-hero-stats {
  display: flex; gap: var(--sp-3); flex-wrap: wrap;
  animation: slideUp 0.8s ease-out 0.6s both;
}
.tw-stat-card {
  flex: 1;
  min-width: 130px;
  background: rgba(255, 255, 255, 0.08);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  border: 1px solid rgba(255, 255, 255, 0.14);
  border-radius: 12px;
  padding: 10px 14px;
  transition: transform 0.2s, background 0.2s, border-color 0.2s;
}
.tw-stat-card:hover {
  transform: translateY(-2px);
  background: rgba(255, 255, 255, 0.14);
  border-color: rgba(74, 222, 128, 0.4);
}
.tw-stat-num { font-size: 20px; font-weight: 800; color: var(--c-white); line-height: 1.2; }
.tw-stat-label { font-size: 13px; color: #a7f3d0; margin-top: 3px; display: flex; align-items: center; gap: 5px; }

/* Search box — card style + entrance animation */
.tw-search-box {
  background: var(--c-white);
  border-radius: 20px;
  padding: 24px;
  box-shadow: 0 20px 45px -10px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(255, 255, 255, 0.2);
  position: relative; z-index: 10;
  animation: slideUp 0.9s cubic-bezier(0.22, 1, 0.36, 1) 0.3s both;
}
.tw-search-box:hover {
  box-shadow: 0 24px 50px -10px rgba(0, 0, 0, 0.35);
}
.tw-search-box-title {
  font-size: var(--text-base); font-weight: 600; color: var(--c-text);
  margin-bottom: var(--sp-4); padding-bottom: var(--sp-3);
  border-bottom: 1px solid var(--c-border);
}
.tw-search-field { margin-bottom: 12px; }
.tw-search-field label {
  display: block; font-size: 13px; font-weight: 600;
  color: var(--c-gray-700); margin-bottom: 5px;
}
.tw-search-input-wrap {
  position: relative;
  display: flex;
  align-items: center;
}
.tw-search-input-wrap .tw-input-icon {
  position: absolute;
  left: 14px;
  color: #94a3b8;
  pointer-events: none;
  font-size: 14px;
  z-index: 2;
}
.tw-search-input-wrap input,
.tw-search-input-wrap select {
  width: 100%; border: 1px solid #e2e8f0; border-radius: 10px;
  padding: 10px 14px 10px 38px; font-size: 14px; color: var(--c-text);
  font-family: var(--font); background: #f8fafc; outline: none;
  transition: border-color 0.15s, box-shadow 0.15s, background 0.15s; appearance: auto;
}
.tw-search-input-wrap input:focus,
.tw-search-input-wrap select:focus {
  border-color: var(--c-brand);
  background: #ffffff;
  box-shadow: 0 0 0 3px rgba(0, 177, 79, 0.16);
}
.tw-search-btn-primary {
  width: 100%; margin-top: 10px;
  background: linear-gradient(135deg, #00b14f 0%, #009944 100%);
  color: #ffffff; border: none;
  padding: 12px 20px; border-radius: 10px; font-size: 15px;
  font-weight: 700; cursor: pointer; font-family: var(--font);
  box-shadow: 0 4px 14px rgba(0, 177, 79, 0.35);
  transition: all 0.2s ease;
  display: flex; align-items: center; justify-content: center; gap: 8px;
  text-decoration: none;
}
.tw-search-btn-primary:hover {
  background: linear-gradient(135deg, #009944 0%, #007a36 100%);
  box-shadow: 0 6px 20px rgba(0, 177, 79, 0.45);
  transform: translateY(-2px);
  color: #ffffff;
}
.tw-quick-tags {
  display: flex; flex-wrap: wrap; gap: 6px; align-items: center;
  margin-top: 14px; font-size: 12px; color: #64748b;
}
.tw-quick-tag {
  background: #f1f5f9; color: #475569; padding: 2px 9px;
  border-radius: 999px; text-decoration: none; font-size: 11px;
  font-weight: 500; transition: all 0.15s ease; border: 1px solid #e2e8f0;
  cursor: pointer;
}
.tw-quick-tag:hover {
  background: var(--c-brand-light); color: var(--c-brand-dark);
  border-color: var(--c-brand);
}

.tw-search-btn {
  width: 100%; margin-top: var(--sp-2);
  background: var(--c-brand); color: var(--c-white); border: none;
  padding: 10px; border-radius: var(--r-md); font-size: var(--text-base);
  font-weight: 600; cursor: pointer; font-family: var(--font);
  transition: background 0.15s;
}
.tw-search-btn:hover { background: var(--c-brand-dark); }

/* ===================================================
   SECTION WRAPPER
   ================================================= */
.tw-section { padding: var(--sp-16) 0; }
.tw-section-alt { background: var(--c-gray-50); }

.tw-section-head { margin-bottom: var(--sp-8); }
.tw-section-head h2 {
  font-size: var(--text-2xl); font-weight: 700;
  color: var(--c-text); margin-bottom: var(--sp-2);
  position: relative; display: inline-block;
}
/* Accent underline */
.tw-section-head h2::after {
  content: ''; display: block;
  width: 44px; height: 3px; border-radius: 3px;
  background: linear-gradient(90deg, var(--c-brand), #4ade80);
  margin-top: 8px;
}
.tw-section-head p { font-size: var(--text-md); color: var(--c-text-sub); }

/* ===================================================
   SCROLL REVEAL — elements fade/slide in on scroll
   ================================================= */
.tw-reveal-init { opacity: 0; transform: translateY(24px); }
.tw-reveal-in {
  opacity: 1; transform: translateY(0);
  transition: opacity 0.6s cubic-bezier(0.22, 1, 0.36, 1),
              transform 0.6s cubic-bezier(0.22, 1, 0.36, 1);
}
@media (prefers-reduced-motion: reduce) {
  .tw-reveal-init { opacity: 1; transform: none; }
  .tw-hero-blob, .tw-hero::before, .tw-hero::after, .tw-cta::before, .tw-cta::after {
    animation: none !important;
  }
}

/* ===================================================
   CAREER CHIPS / CARDS — Modern Bento-style category cards
   ================================================= */
.tw-career-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
  gap: 16px;
}
.tw-career-chip {
  display: flex; align-items: center; gap: 14px;
  background: var(--c-white); border: 1px solid #e2e8f0;
  border-radius: 14px; padding: 14px 16px;
  text-decoration: none; color: var(--c-text);
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
  transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
}
.tw-career-chip:hover {
  border-color: var(--c-brand); background: #ffffff;
  color: var(--c-brand); box-shadow: 0 10px 25px -5px rgba(0, 177, 79, 0.12), 0 4px 6px -2px rgba(0, 0, 0, 0.04);
  transform: translateY(-3px);
}
.tw-career-chip:active { transform: translateY(-1px); }
.tw-career-chip-icon {
  width: 44px; height: 44px; border-radius: 12px;
  display: flex; align-items: center; justify-content: center;
  font-size: 18px; background: var(--c-brand-light); color: var(--c-brand);
  flex-shrink: 0; transition: transform 0.2s ease, background 0.2s ease, color 0.2s ease;
}
.tw-career-chip:hover .tw-career-chip-icon {
  transform: scale(1.08); background: var(--c-brand); color: #ffffff;
}
.tw-career-chip-info { flex: 1; min-width: 0; }
.tw-career-chip-name {
  font-size: 14px; font-weight: 600; color: #1e293b;
  margin-bottom: 2px; white-space: nowrap; overflow: hidden;
  text-overflow: ellipsis; display: block; transition: color 0.15s;
}
.tw-career-chip:hover .tw-career-chip-name { color: var(--c-brand); }
.tw-career-chip-count { font-size: 12px; color: #64748b; font-weight: 500; display: block; }

/* ===================================================
   JOB CARD — Modern Polish & Clean Elevation
   ================================================= */
.tw-job-card {
  background: var(--c-white);
  border: 1px solid #e2e8f0;
  border-radius: 16px;
  padding: 18px;
  transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
  display: flex; flex-direction: column; height: 100%;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
}
.tw-job-card:hover {
  border-color: var(--c-brand);
  box-shadow: 0 14px 28px -6px rgba(0, 177, 79, 0.14), 0 8px 10px -6px rgba(0, 0, 0, 0.04);
  transform: translateY(-4px);
}
/* Card entrance animation (staggered via JS adding .tw-reveal) */
.tw-job-card.tw-reveal {
  animation: slideUp 0.6s cubic-bezier(0.22, 1, 0.36, 1) both;
}
.tw-job-card-top { display: flex; align-items: flex-start; gap: 14px; margin-bottom: 12px; }
.tw-job-logo {
  width: 50px; height: 50px; border-radius: 12px;
  object-fit: cover; border: 1px solid #e2e8f0; background: #f8fafc;
  flex-shrink: 0; padding: 2px; transition: transform 0.2s;
}
.tw-job-card:hover .tw-job-logo { transform: scale(1.05); }
.tw-job-title {
  font-size: 15px; font-weight: 700; color: #1e293b;
  line-height: 1.4; margin-bottom: 4px;
  display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
  overflow: hidden; text-decoration: none; transition: color 0.15s;
}
.tw-job-card:hover .tw-job-title { color: var(--c-brand); }
.tw-job-company {
  font-size: 13px; color: #64748b; font-weight: 500;
  white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.tw-job-heart {
  width: 32px; height: 32px; border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  margin-left: auto; flex-shrink: 0;
  background: #f8fafc; border: 1px solid #e2e8f0; cursor: pointer;
  font-size: 14px; color: #94a3b8; transition: all 0.2s ease;
  text-decoration: none; padding: 0;
}
.tw-job-heart:hover {
  background: #ffe4e6; border-color: #fecdd3; color: #f43f5e;
  transform: scale(1.1);
}
.tw-job-heart .fa-heart { color: #f43f5e; }

.tw-job-meta { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 12px; }
.tw-job-badge {
  display: inline-flex; align-items: center; gap: 4px; font-size: 12px;
  padding: 3px 9px; border-radius: 6px; font-weight: 500;
  border: 1px solid transparent;
}
.tw-badge-loc { background: #f1f5f9; color: #475569; border-color: #e2e8f0; }
.tw-badge-type { background: #ecfdf5; color: #047857; border-color: #a7f3d0; }
.tw-badge-salary { background: #fef3c7; color: #92400e; border-color: #fde68a; font-weight: 600; }

.tw-job-tags { display: flex; flex-wrap: wrap; gap: 5px; margin-bottom: 14px; flex: 1; }
.tw-job-tag {
  font-size: 11px; padding: 2px 8px; border-radius: 999px;
  background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b;
  text-decoration: none; transition: all 0.15s;
}
.tw-job-tag:hover { color: var(--c-brand); border-color: var(--c-brand); background: var(--c-brand-light); }

.tw-job-footer {
  display: flex; align-items: center; justify-content: space-between;
  margin-top: auto; padding-top: 12px; border-top: 1px dashed #e2e8f0;
}
.tw-job-time { font-size: 12px; color: #94a3b8; display: flex; align-items: center; gap: 4px; }
.tw-btn-detail {
  display: inline-flex; align-items: center; gap: 5px; text-align: center;
  padding: 6px 14px; background: #ecfdf5; color: #047857; border-radius: 8px;
  font-size: 13px; font-weight: 600; text-decoration: none;
  transition: all 0.2s ease; border: 1px solid #a7f3d0;
}
.tw-btn-detail:hover {
  background: #4a0d8e !important; color: var(--c-white) !important; border-color: #3b0a73 !important;
  box-shadow: 0 4px 12px rgba(74, 13, 142, 0.3) !important;
  transform: translateY(-1px);
}
.tw-btn-detail:hover * { color: #ffffff !important; }
.tw-btn-detail:active { transform: translateY(0); }

/* ===================================================
   VIEW ALL BUTTON
   ================================================= */
.tw-view-all {
  display: inline-flex; align-items: center; gap: 6px;
  border: 1px solid #4a0d8e; color: #4a0d8e;
  padding: 10px 24px; border-radius: 10px;
  font-size: 14px; font-weight: 600; text-decoration: none;
  transition: all 0.2s ease; background: var(--c-white);
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}
.tw-view-all:hover {
  background: #4a0d8e !important; color: var(--c-white) !important; border-color: #3b0a73 !important;
  box-shadow: 0 4px 14px rgba(74, 13, 142, 0.3) !important;
  transform: translateY(-1px);
}
.tw-view-more-link {
  color: #4a0d8e; font-size: 14px; font-weight: 600;
  transition: color 0.15s, transform 0.15s;
}
.tw-view-more-link:hover {
  color: #3b0a73; transform: translateX(3px);
}

/* ===================================================
   CTA BANNER — IBM Carbon-style: full-color block, no rounded mess
   ================================================= */
.tw-cta {
  background: linear-gradient(135deg, #111827 0%, #1f2937 100%);
  border-radius: var(--r-xl); padding: var(--sp-12) var(--sp-10);
  color: var(--c-white); text-align: center;
  position: relative; overflow: hidden;
  animation: slideUp 0.7s ease both;
}
.tw-cta::before {
  content: '';
  position: absolute; top: -80px; right: -80px;
  width: 320px; height: 320px; border-radius: 50%;
  background: radial-gradient(circle, rgba(0,177,79,0.18) 0%, transparent 70%);
  pointer-events: none;
  animation: float 7s ease-in-out infinite;
}
.tw-cta::after {
  content: '';
  position: absolute; bottom: -120px; left: -80px;
  width: 280px; height: 280px; border-radius: 50%;
  background: radial-gradient(circle, rgba(0,214,106,0.10) 0%, transparent 70%);
  pointer-events: none;
  animation: float 9s ease-in-out infinite reverse;
}
.tw-cta h3, .tw-cta p, .tw-cta-btns { position: relative; z-index: 1; }
.tw-cta h3 { font-size: var(--text-2xl); font-weight: 700; margin-bottom: var(--sp-2); }
.tw-cta p { font-size: var(--text-md); color: var(--c-gray-400); margin-bottom: var(--sp-6); }
.tw-cta-btns { display: flex; gap: var(--sp-3); justify-content: center; flex-wrap: wrap; }
.tw-cta-btn-green {
  background: var(--c-brand); color: var(--c-white);
  font-weight: 600; font-size: var(--text-base);
  padding: 10px var(--sp-6); border-radius: var(--r-md);
  text-decoration: none; transition: background 0.15s; border: 1px solid var(--c-brand-dark);
  font-family: var(--font);
}
.tw-cta-btn-green:hover { background: var(--c-brand-dark); color: var(--c-white); }
.tw-cta-btn-outline {
  border: 1px solid var(--c-gray-600); color: var(--c-gray-300);
  font-weight: 600; font-size: var(--text-base); padding: 10px var(--sp-6);
  border-radius: var(--r-md); text-decoration: none; transition: all 0.15s;
  font-family: var(--font); background: transparent;
}
.tw-cta-btn-outline:hover { background: var(--c-gray-800); border-color: var(--c-gray-400); color: var(--c-white); }

/* ===================================================
   FOOTER — Dark, minimal, no icon clutter
   ================================================= */
.tw-footer {
  background: var(--c-gray-900);
  color: var(--c-gray-400);
  margin-top: var(--sp-16);
  font-family: var(--font);
  font-size: var(--text-base);
}
.tw-footer-top {
  display: grid;
  grid-template-columns: 1.8fr 1fr 1fr 1fr;
  gap: var(--sp-12);
  padding: var(--sp-12) 0 var(--sp-10);
}
.tw-footer-brand img { margin-bottom: var(--sp-3); display: block; }
.tw-footer-tagline { font-size: var(--text-sm); line-height: var(--lh-loose); color: var(--c-gray-500); margin-bottom: var(--sp-4); }
.tw-footer-socials { display: flex; gap: var(--sp-2); }
.tw-footer-socials a {
  display: inline-block; padding: 4px 10px;
  border: 1px solid var(--c-gray-700); border-radius: var(--r-sm);
  color: var(--c-gray-500); text-decoration: none; font-size: var(--text-sm); font-weight: 600;
  transition: all 0.15s;
}
.tw-footer-socials a:hover { border-color: var(--c-brand); color: var(--c-brand); }

.tw-footer-col-title {
  font-size: var(--text-xs); font-weight: 700; color: var(--c-gray-200);
  text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: var(--sp-4);
}
.tw-footer-col ul { list-style: none; padding: 0; margin: 0; }
.tw-footer-col ul li { margin-bottom: 10px; font-size: var(--text-base); color: var(--c-gray-500); }
.tw-footer-col ul li a { color: var(--c-gray-500); text-decoration: none; transition: color 0.15s; }
.tw-footer-col ul li a:hover { color: var(--c-brand); }

.tw-footer-bottom { border-top: 1px solid var(--c-gray-800); padding: var(--sp-4) 0; }
.tw-footer-bottom-inner {
  display: flex; justify-content: space-between; align-items: center;
  font-size: var(--text-sm); color: var(--c-gray-600); flex-wrap: wrap; gap: var(--sp-2);
}
.tw-footer-credit { color: var(--c-brand); text-decoration: none; font-weight: 600; }

@media (max-width: 991px) { .tw-footer-top { grid-template-columns: 1fr 1fr; gap: var(--sp-8); } }
@media (max-width: 576px) { .tw-footer-top { grid-template-columns: 1fr; gap: var(--sp-6); } }

/* ===================================================
   AI CHAT WIDGET — Clean, non-AI-looking, like Intercom
   ================================================= */
#aiChatWrapper {
  position: fixed; bottom: var(--sp-6); right: var(--sp-6); z-index: 99999;
  font-family: var(--font);
}
.ai-launcher {
  background: var(--c-brand); color: var(--c-white);
  border: none; border-radius: var(--r-md);
  padding: 10px var(--sp-4); font-size: var(--text-base); font-weight: 600;
  cursor: pointer; box-shadow: var(--shadow-md);
  transition: background 0.15s;
}
.ai-launcher:hover { background: var(--c-brand-dark); }

.ai-window {
  width: 340px; max-width: calc(100vw - 28px);
  height: 440px; max-height: calc(100vh - 80px);
  background: var(--c-white); border-radius: var(--r-xl);
  border: 1px solid var(--c-border);
  box-shadow: var(--shadow-lg);
  display: flex; flex-direction: column; overflow: hidden;
  position: absolute; bottom: 0; right: 0;
  animation: ai-in 0.2s ease;
}
@keyframes ai-in {
  from { opacity: 0; transform: translateY(8px); }
  to   { opacity: 1; transform: translateY(0); }
}
.ai-header {
  background: var(--c-gray-900); padding: var(--sp-3) var(--sp-4);
  display: flex; justify-content: space-between; align-items: center;
}
.ai-header-title { font-size: var(--text-base); font-weight: 600; color: var(--c-white); }
.ai-header-sub { font-size: var(--text-xs); color: var(--c-gray-500); margin-top: 1px; }
.ai-header-actions { display: flex; gap: var(--sp-2); }
.ai-header-actions button {
  background: var(--c-gray-800); border: none; color: var(--c-gray-400);
  font-size: var(--text-xs); font-weight: 600; padding: 3px 8px;
  border-radius: var(--r-sm); cursor: pointer; font-family: var(--font);
  transition: background 0.15s, color 0.15s;
}
.ai-header-actions button:hover { background: var(--c-gray-700); color: var(--c-white); }

.ai-body {
  flex: 1; padding: var(--sp-4); overflow-y: auto;
  background: var(--c-gray-50); display: flex; flex-direction: column; gap: var(--sp-2);
}
.ai-body::-webkit-scrollbar { width: 4px; }
.ai-body::-webkit-scrollbar-thumb { background: var(--c-gray-200); border-radius: 4px; }

.ai-msg { display: flex; max-width: 88%; }
.ai-msg-bot { align-self: flex-start; }
.ai-msg-user { align-self: flex-end; flex-direction: row-reverse; }
.ai-bubble {
  padding: 8px var(--sp-3); border-radius: 10px;
  font-size: var(--text-base); line-height: var(--lh-base); word-break: break-word;
}
.ai-msg-bot .ai-bubble {
  background: var(--c-white); color: var(--c-text);
  border: 1px solid var(--c-border); border-top-left-radius: var(--r-sm);
}
.ai-msg-user .ai-bubble {
  background: var(--c-brand); color: var(--c-white);
  border-top-right-radius: var(--r-sm);
}
.ai-bubble a { color: #059669; font-weight: 600; }
.ai-msg-user .ai-bubble a { color: #d1fae5; }
.ai-bubble strong { font-weight: 700; }
.ai-bubble ul { margin: var(--sp-1) 0; padding-left: var(--sp-4); }
.ai-bubble li { margin-bottom: 3px; }

.ai-chips { display: flex; flex-direction: column; gap: var(--sp-2); margin: var(--sp-1) 0; }
.ai-chip {
  background: var(--c-white); border: 1px solid var(--c-border); color: var(--c-gray-700);
  font-size: var(--text-sm); padding: 6px var(--sp-3); border-radius: var(--r-md);
  text-align: left; cursor: pointer; font-family: var(--font);
  transition: border-color 0.15s, color 0.15s;
}
.ai-chip:hover { border-color: var(--c-brand); color: var(--c-brand); }

.ai-typing { display: flex; align-items: center; gap: 4px; }
.ai-typing span {
  width: 5px; height: 5px; background: var(--c-gray-400); border-radius: 50%;
  animation: ai-bounce 1.2s infinite ease-in-out;
}
.ai-typing span:nth-child(2) { animation-delay: 0.15s; }
.ai-typing span:nth-child(3) { animation-delay: 0.30s; }
@keyframes ai-bounce {
  0%,80%,100% { transform: scale(0.7); opacity: 0.5; }
  40% { transform: scale(1); opacity: 1; }
}

.ai-footer { padding: var(--sp-3) var(--sp-3); background: var(--c-white); border-top: 1px solid var(--c-border); }
.ai-form { display: flex; gap: var(--sp-2); }
.ai-input {
  flex: 1; border: 1px solid var(--c-border); border-radius: var(--r-md);
  padding: 7px var(--sp-3); font-size: var(--text-base); outline: none;
  font-family: var(--font); transition: border-color 0.15s;
  background: var(--c-white); color: var(--c-text);
}
.ai-input:focus { border-color: var(--c-brand); box-shadow: 0 0 0 3px var(--c-brand-muted); }
.ai-send {
  background: var(--c-brand); color: var(--c-white); border: none; border-radius: var(--r-md);
  padding: 7px var(--sp-4); font-size: var(--text-base); font-weight: 600; cursor: pointer;
  font-family: var(--font); transition: background 0.15s;
}
.ai-send:hover { background: var(--c-brand-dark); }

/* ===================================================
   JOBS LIST & FILTER SIDEBAR (Primer/Atlassian style)
   ================================================= */
.tw-filter-card {
  background: var(--c-white);
  border: 1px solid var(--c-border);
  border-radius: var(--r-lg);
  padding: var(--sp-4);
}
.tw-filter-title {
  font-size: var(--text-md); font-weight: 600; color: var(--c-text);
  margin-bottom: 0;
}
.tw-filter-group {
  margin-bottom: var(--sp-4);
  padding-bottom: var(--sp-3);
  border-bottom: 1px solid var(--c-gray-100);
}
.tw-filter-group:last-of-type {
  border-bottom: none; margin-bottom: var(--sp-3); padding-bottom: 0;
}
.tw-filter-label {
  display: block; font-size: var(--text-xs); font-weight: 600;
  text-transform: uppercase; letter-spacing: 0.05em;
  color: var(--c-gray-500); margin-bottom: var(--sp-2);
}
.tw-radio-chips {
  display: flex; flex-direction: column; gap: var(--sp-1);
}
.tw-radio-chip {
  position: relative;
}
.tw-radio-chip input[type="radio"] {
  position: absolute; opacity: 0; pointer-events: none;
}
.tw-radio-chip label {
  display: block; width: 100%;
  padding: 6px var(--sp-3);
  font-size: var(--text-sm); color: var(--c-gray-600);
  border-radius: var(--r-md); cursor: pointer;
  transition: all 0.15s; background: transparent;
}
.tw-radio-chip label:hover {
  background: var(--c-gray-100); color: var(--c-text);
}
.tw-radio-chip input[type="radio"]:checked + label {
  background: var(--c-brand-light); color: var(--c-brand);
  font-weight: 600;
}

/* ===================================================
   JOB DETAIL PAGE (Atlassian Clean Spec style)
   ================================================= */
.tw-detail-header-card {
  background: var(--c-white);
  border: 1px solid var(--c-border);
  border-radius: var(--r-lg);
  padding: var(--sp-6);
  margin-bottom: var(--sp-6);
}
.tw-detail-logo {
  width: 72px; height: 72px; border-radius: var(--r-md);
  object-fit: cover; border: 1px solid var(--c-border);
}
.tw-detail-title {
  font-size: var(--text-2xl); font-weight: 700; color: var(--c-text);
  line-height: var(--lh-tight); margin-bottom: var(--sp-2);
}
.tw-detail-company {
  font-size: var(--text-base); font-weight: 500; color: var(--c-gray-600);
  margin-bottom: var(--sp-3);
}
.tw-metrics-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
  gap: var(--sp-3);
  margin-bottom: var(--sp-6);
}
.tw-metric-card {
  background: var(--c-white);
  border: 1px solid var(--c-border);
  border-radius: var(--r-md);
  padding: var(--sp-3) var(--sp-4);
}
.tw-metric-label {
  font-size: var(--text-xs); color: var(--c-gray-400);
  text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600;
  margin-bottom: 2px;
}
.tw-metric-value {
  font-size: var(--text-md); font-weight: 600; color: var(--c-text);
}
.tw-content-card {
  background: var(--c-white);
  border: 1px solid var(--c-border);
  border-radius: var(--r-lg);
  padding: var(--sp-6);
  margin-bottom: var(--sp-6);
}
.tw-content-card h3 {
  font-size: var(--text-lg); font-weight: 700; color: var(--c-text);
  margin-bottom: var(--sp-4); padding-bottom: var(--sp-2);
  border-bottom: 1px solid var(--c-gray-100);
}
.tw-content-card p, .tw-content-card li {
  color: var(--c-gray-700); font-size: var(--text-base); line-height: var(--lh-loose);
}

/* ===================================================
   AUTH / ACCOUNT CARDS (GitHub Primer style forms)
   ================================================= */
.tw-auth-wrap {
  min-height: calc(100vh - 200px);
  display: flex; align-items: center; justify-content: center;
  padding: var(--sp-10) var(--sp-4);
  background: var(--c-gray-50);
}
.tw-auth-card {
  background: var(--c-white);
  border: 1px solid var(--c-border);
  border-radius: var(--r-lg);
  padding: var(--sp-8);
  width: 100%; max-width: 440px;
  box-shadow: var(--shadow-sm);
}
.tw-auth-title {
  font-size: var(--text-2xl); font-weight: 700; color: var(--c-text);
  margin-bottom: var(--sp-2); text-align: center;
}
.tw-auth-sub {
  font-size: var(--text-sm); color: var(--c-gray-500);
  margin-bottom: var(--sp-6); text-align: center;
}
.tw-form-group {
  margin-bottom: var(--sp-4);
}
.tw-form-group label {
  display: block; font-size: var(--text-sm); font-weight: 500;
  color: var(--c-gray-700); margin-bottom: var(--sp-1);
}
.tw-input {
  width: 100%; border: 1px solid var(--c-border); border-radius: var(--r-md);
  padding: 8px var(--sp-3); font-size: var(--text-base); color: var(--c-text);
  background: var(--c-white); font-family: var(--font); outline: none;
  transition: border-color 0.15s, box-shadow 0.15s;
}
.tw-input:focus {
  border-color: var(--c-brand); box-shadow: 0 0 0 3px var(--c-brand-muted);
}
.tw-sidebar-card {
  background: var(--c-white);
  border: 1px solid var(--c-border);
  border-radius: var(--r-lg);
  overflow: hidden;
  margin-bottom: var(--sp-4);
}
.tw-sidebar-nav {
  list-style: none; margin: 0; padding: 0;
}
.tw-sidebar-nav li a {
  display: block; padding: var(--sp-3) var(--sp-4);
  color: var(--c-gray-700); font-size: var(--text-base); font-weight: 500;
  text-decoration: none; border-bottom: 1px solid var(--c-gray-100);
  transition: all 0.15s;
}
.tw-sidebar-nav li:last-child a { border-bottom: none; }
.tw-sidebar-nav li a:hover {
  background: var(--c-brand-light); color: var(--c-brand);
}
.tw-sidebar-nav li a.active {
  background: var(--c-brand-light); color: var(--c-brand); font-weight: 600;
}

/* ===================================================
   HORIZONTAL JOB CARD (.tw-job-card-h)
   ================================================= */
.tw-job-card-h {
  display: flex;
  align-items: flex-start;
  gap: 14px;
  padding: 16px;
  border-radius: 14px;
  border: 1px solid #e2e8f0;
  background: #fff;
  position: relative;
  transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
  height: 100%;
}
.tw-job-card-h:hover {
  border-color: var(--c-brand);
  box-shadow: 0 10px 24px -6px rgba(0, 177, 79, 0.14), 0 4px 6px -2px rgba(0,0,0,0.04);
  transform: translateY(-3px);
}
.tw-job-card-h-flat { height: auto; }
.tw-job-logo-h {
  width: 60px; height: 60px; border-radius: 12px;
  object-fit: cover; border: 1px solid #e2e8f0; background: #f8fafc;
  flex-shrink: 0; padding: 2px; transition: transform 0.2s;
}
.tw-job-card-h:hover .tw-job-logo-h { transform: scale(1.05); }
.tw-job-card-h-body { flex: 1; min-width: 0; }
.tw-job-title-h {
  font-size: 15px; font-weight: 700; color: #1e293b;
  line-height: 1.4; margin-bottom: 4px;
  display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
  overflow: hidden; text-decoration: none; transition: color 0.15s;
}
.tw-job-card-h:hover .tw-job-title-h { color: var(--c-brand); }
.tw-job-card-h-action {
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  justify-content: space-between;
  flex-shrink: 0;
  min-width: 95px;
  align-self: stretch;
}
.tw-job-card-h-bottom {
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  gap: 6px;
  margin-top: auto;
}

/* Urgent section styling */
.tw-urgent-section { background: #fff; }

/* Urgent horizontal scroll layout */
.tw-urgent-scroll-wrap { overflow: hidden; }
.tw-urgent-row {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 16px;
}
@media (max-width: 768px) {
  .tw-urgent-row {
    display: flex;
    overflow-x: auto;
    gap: 12px;
    padding-bottom: 8px;
    scroll-snap-type: x mandatory;
    -webkit-overflow-scrolling: touch;
  }
  .tw-urgent-card-wrap {
    min-width: 310px;
    scroll-snap-align: start;
    flex-shrink: 0;
  }
  .tw-urgent-row::-webkit-scrollbar { height: 4px; }
  .tw-urgent-row::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }
}

@media (max-width: 576px) {
  .tw-job-card-h {
    flex-wrap: wrap;
    gap: 12px;
    padding: 14px;
  }
  .tw-job-card-h-body {
    flex: 1 1 calc(100% - 74px);
  }
  .tw-job-card-h-action {
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    margin-top: 6px;
    padding-top: 8px;
    border-top: 1px dashed #e2e8f0;
    min-width: 0;
  }
  .tw-job-card-h-bottom {
    flex-direction: row;
    align-items: center;
    gap: 10px;
    margin-top: 0;
  }
}

/* ===================================================
   COMPANY LOGOS GRID (.tw-company-grid)
   ================================================= */
.tw-company-grid {
  display: grid;
  grid-template-columns: repeat(6, 1fr);
  gap: 14px;
}
@media (max-width: 991px) {
  .tw-company-grid { grid-template-columns: repeat(4, 1fr); }
}
@media (max-width: 576px) {
  .tw-company-grid { grid-template-columns: repeat(3, 1fr); gap: 10px; }
}
.tw-company-logo-item {
  display: flex; align-items: center; justify-content: center;
  padding: 14px 10px;
  border: 1px solid #e2e8f0; border-radius: 14px; background: #fff;
  text-decoration: none; color: inherit;
  transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
  filter: grayscale(50%); opacity: 0.75;
}
.tw-company-logo-item:hover {
  filter: grayscale(0%); opacity: 1;
  border-color: var(--c-brand);
  transform: translateY(-3px);
  box-shadow: 0 8px 20px -4px rgba(0, 177, 79, 0.14);
}
.tw-company-logo-inner { text-align: center; }
.tw-company-logo-inner img {
  max-height: 42px; max-width: 100%; width: 42px; height: 42px;
  object-fit: cover; border-radius: 8px; margin-bottom: 6px;
  display: block; margin-left: auto; margin-right: auto;
}
.tw-company-logo-label {
  font-size: 11px; font-weight: 600; color: #374151;
  line-height: 1.3; margin-bottom: 2px;
  overflow: hidden; white-space: nowrap; text-overflow: ellipsis;
  max-width: 100px;
}
.tw-company-logo-count {
  font-size: 10px; color: #6b7280;
}
</style>
