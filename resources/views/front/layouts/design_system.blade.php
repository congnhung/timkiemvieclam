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

  /* Typography */
  --font:         -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto,
                  Oxygen-Sans, Ubuntu, Cantarell, Helvetica, Arial, sans-serif;
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
   BASE RESET & TYPOGRAPHY
   ================================================= */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

body {
  font-family: var(--font);
  font-size: var(--text-base);
  line-height: var(--lh-base);
  color: var(--c-text);
  background: var(--c-white);
  -webkit-font-smoothing: antialiased;
  scroll-behavior: smooth;
}

html { scroll-behavior: smooth; }

/* ===================================================
   HEADER — GitHub Primer style: clean white, subtle border
   ================================================= */
.tw-header {
  position: sticky; top: 0; z-index: 1000;
  background: var(--c-white);
  border-bottom: 1px solid var(--c-border);
}
.tw-navbar { width: 100%; }
.tw-nav-inner {
  display: flex; align-items: center;
  height: 56px;
  max-width: 1200px; margin: 0 auto; padding: 0 var(--sp-6);
}
.tw-logo { display: flex; align-items: center; text-decoration: none; flex-shrink: 0; }
.tw-logo-img { height: 34px; width: auto; object-fit: contain; }

.tw-hamburger {
  display: none; flex-direction: column; justify-content: center; gap: 5px;
  background: none; border: none; cursor: pointer; padding: var(--sp-2); margin-left: auto;
}
.tw-hamburger span { display: block; width: 20px; height: 2px; background: var(--c-gray-700); border-radius: 2px; }

.tw-nav-collapse { display: flex !important; align-items: center; flex: 1; }
.tw-nav-links {
  display: flex; align-items: center; gap: 0;
  list-style: none; margin: 0 0 0 var(--sp-5); padding: 0; flex: 1;
}
.tw-nav-link {
  display: inline-block;
  padding: var(--sp-2) var(--sp-3); border-radius: var(--r-md);
  font-size: var(--text-base); font-weight: 500; color: var(--c-gray-600);
  text-decoration: none; transition: color 0.15s, background 0.15s; white-space: nowrap;
}
.tw-nav-link:hover { color: var(--c-brand); background: var(--c-brand-light); }
.tw-nav-link.active { color: var(--c-brand); font-weight: 600; }
.tw-nav-cv { color: var(--c-brand); font-weight: 600; }

.tw-nav-actions { display: flex; align-items: center; gap: var(--sp-2); flex-shrink: 0; }

/* Buttons — Primer style */
.tw-btn {
  display: inline-block;
  padding: 6px var(--sp-4); border-radius: var(--r-md);
  font-size: var(--text-base); font-weight: 500; font-family: var(--font);
  text-decoration: none; transition: all 0.15s; white-space: nowrap;
  border: 1px solid var(--c-border); cursor: pointer; line-height: 20px;
}
.tw-btn-outline { color: var(--c-gray-700); background: var(--c-white); }
.tw-btn-outline:hover { background: var(--c-gray-50); border-color: var(--c-gray-300); color: var(--c-gray-900); }
.tw-btn-primary { background: var(--c-brand); color: var(--c-white); border-color: var(--c-brand-dark); }
.tw-btn-primary:hover { background: var(--c-brand-dark); color: var(--c-white); border-color: var(--c-brand-dark); }

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
  display: flex; gap: var(--sp-8); flex-wrap: wrap;
  animation: slideUp 0.8s ease-out 0.6s both;
}
.tw-hero-stats > div { position: relative; padding-left: 14px; }
.tw-hero-stats > div::before {
  content: ''; position: absolute; left: 0; top: 4px; bottom: 4px;
  width: 3px; border-radius: 3px; background: var(--c-brand);
}
.tw-stat-num { font-size: var(--text-xl); font-weight: 700; color: var(--c-white); }
.tw-stat-label { font-size: var(--text-sm); color: #8aa396; margin-top: 2px; }

/* Search box — card style + entrance animation */
.tw-search-box {
  background: var(--c-white);
  border-radius: var(--r-xl);
  padding: var(--sp-6);
  box-shadow: var(--shadow-hero);
  position: relative; z-index: 10;
  animation: slideUp 0.9s cubic-bezier(0.22, 1, 0.36, 1) 0.3s both;
}
.tw-search-box:hover {
  box-shadow: 0 20px 56px rgba(0,0,0,0.28);
}
.tw-search-box-title {
  font-size: var(--text-base); font-weight: 600; color: var(--c-text);
  margin-bottom: var(--sp-4); padding-bottom: var(--sp-3);
  border-bottom: 1px solid var(--c-border);
}
.tw-search-field { margin-bottom: var(--sp-3); }
.tw-search-field label {
  display: block; font-size: var(--text-sm); font-weight: 500;
  color: var(--c-gray-600); margin-bottom: var(--sp-1);
}
.tw-search-field input,
.tw-search-field select {
  width: 100%; border: 1px solid var(--c-border); border-radius: var(--r-md);
  padding: 8px var(--sp-3); font-size: var(--text-base); color: var(--c-text);
  font-family: var(--font); background: var(--c-white); outline: none;
  transition: border-color 0.15s, box-shadow 0.15s; appearance: auto;
}
.tw-search-field input:focus,
.tw-search-field select:focus {
  border-color: var(--c-brand);
  box-shadow: 0 0 0 3px var(--c-brand-muted);
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
   CAREER CHIPS — Atlassian-style tag chips
   ================================================= */
.tw-career-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
  gap: var(--sp-3);
}
.tw-career-chip {
  display: flex; align-items: center; justify-content: space-between;
  background: var(--c-white); border: 1px solid var(--c-border);
  border-radius: var(--r-lg); padding: var(--sp-3) var(--sp-4);
  text-decoration: none; color: var(--c-text);
  transition: border-color 0.2s, background 0.2s, box-shadow 0.2s, transform 0.2s;
}
.tw-career-chip:hover {
  border-color: var(--c-brand); background: var(--c-brand-light);
  color: var(--c-brand); box-shadow: var(--shadow-sm);
  transform: translateY(-3px);
}
.tw-career-chip:active { transform: translateY(-1px); }
.tw-career-chip-name { font-size: var(--text-base); font-weight: 500; }
.tw-career-chip-count { font-size: var(--text-xs); color: var(--c-gray-400); }

/* ===================================================
   JOB CARD — GitHub Primer card style
   ================================================= */
.tw-job-card {
  background: var(--c-white);
  border: 1px solid var(--c-border);
  border-radius: var(--r-lg);
  padding: var(--sp-4);
  transition: border-color 0.2s, box-shadow 0.25s, transform 0.25s;
  display: flex; flex-direction: column; height: 100%;
}
.tw-job-card:hover {
  border-color: var(--c-brand);
  box-shadow: var(--shadow-lg);
  transform: translateY(-4px);
}
/* Card entrance animation (staggered via JS adding .tw-reveal) */
.tw-job-card.tw-reveal {
  animation: slideUp 0.6s cubic-bezier(0.22, 1, 0.36, 1) both;
}
.tw-job-card-top { display: flex; align-items: flex-start; gap: var(--sp-3); margin-bottom: var(--sp-3); }
.tw-job-logo {
  width: 44px; height: 44px; border-radius: var(--r-md);
  object-fit: cover; border: 1px solid var(--c-border); flex-shrink: 0;
}
.tw-job-title {
  font-size: var(--text-base); font-weight: 600; color: var(--c-text);
  line-height: var(--lh-tight); margin-bottom: 2px;
}
.tw-job-company { font-size: var(--text-sm); color: var(--c-text-sub); }
.tw-job-heart {
  margin-left: auto; flex-shrink: 0;
  background: none; border: none; cursor: pointer;
  font-size: 16px; color: var(--c-gray-300); transition: color 0.15s; padding: 0;
}
.tw-job-heart:hover, .tw-job-heart .fa-heart { color: #f43f5e; }

.tw-job-meta { display: flex; flex-wrap: wrap; gap: var(--sp-2); margin-bottom: var(--sp-3); }
.tw-job-badge {
  display: inline-block; font-size: var(--text-xs);
  padding: 2px 8px; border-radius: var(--r-sm); font-weight: 500;
}
.tw-badge-loc { background: var(--c-gray-100); color: var(--c-gray-600); }
.tw-badge-type { background: var(--c-brand-light); color: var(--c-brand-dark); }
.tw-badge-salary { background: #fffbeb; color: #92400e; font-weight: 600; }

.tw-job-tags { display: flex; flex-wrap: wrap; gap: var(--sp-1); margin-bottom: var(--sp-3); flex: 1; }
.tw-job-tag {
  font-size: var(--text-xs); padding: 2px 8px; border-radius: var(--r-sm);
  background: var(--c-gray-50); border: 1px solid var(--c-gray-200); color: var(--c-gray-500);
  text-decoration: none; transition: color 0.12s, border-color 0.12s;
}
.tw-job-tag:hover { color: var(--c-brand); border-color: var(--c-brand); background: var(--c-brand-light); }

.tw-job-footer { margin-top: auto; }
.tw-btn-detail {
  display: block; text-align: center; padding: 8px;
  background: var(--c-brand); color: var(--c-white); border-radius: var(--r-md);
  font-size: var(--text-base); font-weight: 600; text-decoration: none;
  transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
  border: 1px solid var(--c-brand-dark);
  position: relative; overflow: hidden;
}
.tw-btn-detail:hover {
  background: var(--c-brand-dark); color: var(--c-white);
  box-shadow: 0 4px 14px rgba(0, 177, 79, 0.35);
  transform: translateY(-1px);
}
.tw-btn-detail:active { transform: translateY(0); }

/* ===================================================
   VIEW ALL BUTTON
   ================================================= */
.tw-view-all {
  display: inline-block;
  border: 1px solid var(--c-brand); color: var(--c-brand);
  padding: 8px var(--sp-6); border-radius: var(--r-md);
  font-size: var(--text-base); font-weight: 600; text-decoration: none;
  transition: all 0.15s; background: var(--c-white);
}
.tw-view-all:hover { background: var(--c-brand); color: var(--c-white); }

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
</style>
