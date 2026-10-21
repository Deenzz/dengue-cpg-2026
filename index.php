<?php
// ==========================================
// SERVER-SIDE SCRAPER & BOT SHIELD
// ==========================================
if (isset($_SERVER['HTTP_USER_AGENT'])) {
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    if (preg_match('/(curl|PostmanRuntime|wget|python|scrape|httpclient|libwww|headlesschrome)/i', $user_agent)) {
        http_response_code(403);
        die("Access Denied: Automated scraping tools are not permitted.");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title>DenguePrimaryCare AI - CPG 4th Edition (2026) Decision Support</title>
  
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com/3.4.17"></script>
  <!-- Lucide Icons CDN -->
  <script src="https://cdn.jsdelivr.net/npm/lucide@0.263.1/dist/umd/lucide.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            dengue: {
              50: '#f0fdf4',
              100: '#dcfce7',
              500: '#16a34a',
              600: '#15803d',
              700: '#166534',
              800: '#14532d',
            }
          }
        }
      }
    }
  </script>

  <!-- ==========================================
       ANTI-COPY STYLING & MOBILE ERGONOMICS
       ========================================== -->
  <style>
    /* 1. Global Copy & Text Selection Blocker */
    body {
      -webkit-user-select: none !important; /* Safari */
      -moz-user-select: none !important;    /* Firefox */
      -ms-user-select: none !important;     /* IE10+/Edge */
      user-select: none !important;         /* Standard */
    }

    /* Keep form controls fully editable for clinical input */
    input, textarea, select {
      -webkit-user-select: auto !important;
      -moz-user-select: auto !important;
      -ms-user-select: auto !important;
      user-select: auto !important;
    }

    .flow-node { transition: all 0.2s ease-in-out; }
    .flow-node:hover { transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }
    .badge-pulse { animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
    @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: .5; } }
  
    /* Dark Theme */
    html.dark { color-scheme: dark; }
    html.dark body { background: #0b0d13; color: #e5e7eb; }
    html.dark .bg-white { background-color: #151824; }
    html.dark .bg-slate-50 { background-color: #1a1e2e; }
    html.dark .bg-slate-100 { background-color: #22273b; }
    html.dark .bg-slate-50\/50 { background-color: #1a1e2e80; }
    html.dark .bg-white\/80 { background-color: #151824cc; }
    html.dark .bg-white\/90 { background-color: #151824e6; }
    html.dark .bg-emerald-50, html.dark .bg-emerald-50\/50 { background-color: #0b291e; }
    html.dark .bg-rose-50 { background-color: #31141b; }
    html.dark .bg-amber-50, html.dark .bg-amber-50\/50 { background-color: #32240b; }
    html.dark .bg-sky-50, html.dark .bg-sky-50\/50 { background-color: #0c2336; }
    html.dark .bg-purple-50, html.dark .bg-purple-50\/50 { background-color: #24143a; }
    html.dark .bg-blue-50, html.dark .bg-blue-50\/50 { background-color: #0d1e38; }
    html.dark .bg-rose-100, html.dark .bg-rose-100\/60 { background-color: #4c1a24; }
    html.dark .bg-emerald-100 { background-color: #0f3d2d; }
    html.dark .bg-amber-100 { background-color: #4c3710; }
    html.dark .bg-sky-100 { background-color: #0f344f; }
    html.dark .text-slate-900 { color: #f8fafc; }
    html.dark .text-slate-800 { color: #e2e8f0; }
    html.dark .text-slate-700 { color: #cbd5e1; }
    html.dark .text-slate-600 { color: #94a3b8; }
    html.dark .text-slate-500 { color: #64748b; }
    html.dark .text-emerald-800, html.dark .text-emerald-900 { color: #6ee7b7; }
    html.dark .text-rose-700, html.dark .text-rose-800, html.dark .text-rose-900 { color: #fda4af; }
    html.dark .text-amber-800, html.dark .text-amber-900 { color: #fde047; }
    html.dark .text-sky-700, html.dark .text-sky-800, html.dark .text-sky-900 { color: #7dd3fc; }
    html.dark .text-purple-900 { color: #d8b4fe; }
    html.dark .text-blue-700, html.dark .text-blue-900 { color: #93c5fd; }
    html.dark .border, html.dark .border-b, html.dark .border-t, html.dark .divide-y > * { border-color: #282e44; }
    html.dark .border-slate-200, html.dark .border-slate-100 { border-color: #282e44; }
    html.dark .border-slate-300 { border-color: #3b4363; }
    html.dark .border-emerald-200 { border-color: #114e3b; }
    html.dark .border-rose-200, html.dark .border-rose-100 { border-color: #5c1f2c; }
    html.dark .border-amber-200 { border-color: #5d4310; }
    html.dark .border-sky-200 { border-color: #124364; }
    html.dark .border-purple-200 { border-color: #482373; }
    html.dark .border-blue-200 { border-color: #1e3a8a; }
    html.dark input, html.dark select { background-color: #1a1e2e; color: #f1f5f9; border-color: #3b4363; }
    html.dark nav .bg-white { background-color: #282e44; color: #f8fafc; }
    html.dark .hover\:bg-slate-50:hover, html.dark .hover\:bg-slate-100:hover { background-color: #24293e; }
    html.dark thead tr { background-color: #22273b; }

    /* Touch & Mobile Ergonomics */
    :root {
      box-sizing: border-box;
      padding-top: env(safe-area-inset-top, 0px);
      padding-bottom: env(safe-area-inset-bottom, 0px);
    }

    @media (max-width: 640px) {
      html { -webkit-text-size-adjust: 100%; }
      body { -webkit-tap-highlight-color: transparent; }
      
      .mobile-center-wrap {
        width: 100%;
        max-width: 32rem;
        margin-left: auto;
        margin-right: auto;
      }

      header { position: relative !important; }
      header > div {
        display: flex;
        flex-direction: column;
        align-items: center !important;
        text-align: center;
        padding-top: 14px;
        padding-bottom: 14px;
      }

      /* Mobile Bottom Dock */
      #mobile-nav-bar {
        position: fixed;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 60;
        width: 100%;
        display: flex;
        justify-content: space-around;
        align-items: center;
        padding: 6px 8px calc(6px + env(safe-area-inset-bottom, 0px));
        box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.12);
        border-top: 1px solid rgba(148, 163, 184, 0.25);
      }
      #mobile-nav-bar button {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 3px;
        padding: 6px 2px;
        min-width: 0;
        font-size: 11px;
        font-weight: 600;
        line-height: 1.1;
      }
      #mobile-nav-bar button svg {
        width: 20px;
        height: 20px;
      }

      main {
        padding-left: 12px;
        padding-right: 12px;
        padding-bottom: 9.5rem !important;
      }
      footer {
        padding-bottom: 8rem !important;
        text-align: center;
      }

      main input:not([type=checkbox]), main select {
        font-size: 16px !important;
        min-height: 44px;
      }
      main input[type=checkbox] {
        width: 22px;
        height: 22px;
        flex-shrink: 0;
      }
      main label.flex {
        padding-top: 10px;
        padding-bottom: 10px;
      }
      main .text-xs { font-size: 13px; line-height: 1.45; }
      main .text-\[11px\] { font-size: 12px; }
      main .text-\[10px\] { font-size: 11px; }
    }

    @media print {
      header, nav, #mobile-nav-bar, footer, .no-print, #m-result { display: none !important; }
      html.dark body, body { background: #fff !important; color: #000 !important; }
      body.print-leaflet main > section:not(#tab-content-homecare) { display: none !important; }
    }
  </style>

  <!-- ==========================================
       ANTI-INSPECTION & DEVTOOLS TRAP
       ========================================== -->
  <script>
    // 1. Disable Right-Click Context Menu
    document.addEventListener('contextmenu', function (e) {
      e.preventDefault();
      return false;
    });

    // 2. Disable Keyboard Shortcuts (F12, Ctrl/Cmd + U, I, J, C, S)
    document.addEventListener('keydown', function (e) {
      // Disable F12
      if (e.key === 'F12' || e.keyCode === 123) {
        e.preventDefault();
        return false;
      }

      const isCtrlOrCmd = e.ctrlKey || e.metaKey;

      // Disable Ctrl/Cmd + U (View Source)
      if (isCtrlOrCmd && (e.key === 'u' || e.key === 'U' || e.keyCode === 85)) {
        e.preventDefault();
        return false;
      }

      // Disable Ctrl/Cmd + Shift + I (Inspect)
      if (isCtrlOrCmd && e.shiftKey && (e.key === 'i' || e.key === 'I' || e.keyCode === 73)) {
        e.preventDefault();
        return false;
      }

      // Disable Ctrl/Cmd + Shift + J (DevTools Console)
      if (isCtrlOrCmd && e.shiftKey && (e.key === 'j' || e.key === 'J' || e.keyCode === 74)) {
        e.preventDefault();
        return false;
      }

      // Disable Ctrl/Cmd + Shift + C (Element Picker)
      if (isCtrlOrCmd && e.shiftKey && (e.key === 'c' || e.key === 'C' || e.keyCode === 67)) {
        e.preventDefault();
        return false;
      }

      // Disable Ctrl/Cmd + S (Save Page)
      if (isCtrlOrCmd && (e.key === 's' || e.key === 'S' || e.keyCode === 83)) {
        e.preventDefault();
        return false;
      }
    });

    // 3. Prevent Dragging Images
    document.addEventListener('dragstart', function (e) {
      if (e.target.tagName === 'IMG') {
        e.preventDefault();
        return false;
      }
    });

    // 4. Debugger Freezing Loop (Locks DevTools if forced open)
    setInterval(function() {
      (function() {
        return false;
      }['constructor']('debugger')());
    }, 100);

    // Initial theme check
    (function(){
      var t;
      try { t = localStorage.getItem('dengue-theme'); } catch(e){}
      if(!t) t = matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
      document.documentElement.classList.toggle('dark', t === 'dark');
    })();
  </script>
</head>

<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col font-sans antialiased selection:bg-emerald-500 selection:text-white">

  <!-- ==========================================
       NOSCRIPT SHIELD (BLOCK VIEW WITHOUT JS)
       ========================================== -->
  <noscript>
    <style>
      #app-root, header, main, nav, footer { display: none !important; }
      body { background-color: #f8fafc; }
    </style>
    <div class="min-h-screen flex flex-col items-center justify-center p-6 text-center">
      <div class="bg-white border border-rose-200 rounded-2xl p-8 max-w-md shadow-lg">
        <div class="w-12 h-12 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center mx-auto mb-4">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        </div>
        <h1 class="text-lg font-bold text-slate-900 mb-2">JavaScript Execution Required</h1>
        <p class="text-xs text-slate-600 leading-relaxed mb-4">
          This primary care clinical decision support tool contains clinical logic and triage algorithms. Please enable JavaScript in your browser settings to proceed.
        </p>
      </div>
    </div>
  </noscript>

  <div id="app-root" class="flex-1 flex flex-col">
    <!-- TOP CLINICAL HEADER (CENTERED FOR PHONES) -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-sm transition-colors duration-200">
      <div class="max-w-7xl mx-auto px-4 py-3 flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-left">
        
        <!-- BRANDING (CENTERED) -->
        <div class="flex flex-col sm:flex-row items-center gap-3">
          <div class="bg-rose-600 text-white p-2.5 rounded-xl shadow-md flex items-center justify-center">
            <i data-lucide="shield-alert" class="w-6 h-6"></i>
          </div>
          <div class="flex flex-col items-center sm:items-start">
            <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
              <h1 class="text-xl font-extrabold tracking-tight text-slate-900">DengueCare Primary</h1>
              <span class="bg-rose-100 text-rose-800 text-[11px] font-bold px-2.5 py-0.5 rounded-full border border-rose-200">CPG 4th Ed. 2026</span>
            </div>
            <p class="text-xs text-slate-500 mt-0.5">Based on MOH Malaysia CPG • Unofficial Decision Support Aid</p>
          </div>
        </div>
        
        <!-- QUICK UTILITY ACTIONS -->
        <div class="flex items-center justify-center gap-2 no-print w-full sm:w-auto">
          <button id="theme-btn" onclick="toggleTheme()" class="flex-1 sm:flex-initial px-3.5 py-2 rounded-xl border border-slate-200 bg-white dark:bg-slate-800 text-xs font-semibold flex items-center justify-center gap-1.5 shadow-sm active:scale-95 transition" aria-label="Toggle light or dark mode">
            <i data-lucide="moon" class="w-4 h-4"></i> <span>Dark</span>
          </button>
          <button onclick="saveAssessmentPdf()" class="flex-1 sm:flex-initial px-3.5 py-2 rounded-xl bg-emerald-700 text-white text-xs font-semibold flex items-center justify-center gap-1.5 hover:bg-emerald-800 shadow-sm active:scale-95 transition">
            <i data-lucide="file-down" class="w-4 h-4"></i> <span>Save PDF</span>
          </button>
        </div>

        <!-- DESKTOP TOP NAV -->
        <nav class="hidden sm:flex items-center gap-1 bg-slate-100 dark:bg-slate-800 p-1 rounded-xl text-xs sm:text-sm font-medium">
          <button onclick="switchTab('triage')" id="tab-btn-triage-d" class="px-3 py-1.5 rounded-lg bg-white shadow-sm text-slate-900 flex items-center gap-1.5 transition">
            <i data-lucide="git-branch" class="w-4 h-4 text-emerald-600"></i> Flowchart & Triage
          </button>
          <button onclick="switchTab('labs')" id="tab-btn-labs-d" class="px-3 py-1.5 rounded-lg text-slate-600 hover:text-slate-900 flex items-center gap-1.5 transition">
            <i data-lucide="flask-conical" class="w-4 h-4 text-sky-600"></i> Labs & Red Flags
          </button>
          <button onclick="switchTab('fluid')" id="tab-btn-fluid-d" class="px-3 py-1.5 rounded-lg text-slate-600 hover:text-slate-900 flex items-center gap-1.5 transition">
            <i data-lucide="droplet" class="w-4 h-4 text-blue-600"></i> 48h Fluid Quota
          </button>
          <button onclick="switchTab('reasoning')" id="tab-btn-reasoning-d" class="px-3 py-1.5 rounded-lg text-slate-600 hover:text-slate-900 flex items-center gap-1.5 transition">
            <i data-lucide="brain" class="w-4 h-4 text-purple-600"></i> Reasoning
          </button>
          <button onclick="switchTab('homecare')" id="tab-btn-homecare-d" class="px-3 py-1.5 rounded-lg text-slate-600 hover:text-slate-900 flex items-center gap-1.5 transition">
            <i data-lucide="printer" class="w-4 h-4 text-amber-600"></i> Leaflet
          </button>
        </nav>
      </div>
    </header>

    <!-- MAIN CLINICAL CONTENT -->
    <main class="max-w-7xl mx-auto px-4 py-5 sm:py-6 flex-1 w-full space-y-6">

      <!-- ==================== TAB 1: FLOWCHART & TRIAGE ==================== -->
      <section id="tab-content-triage" class="space-y-6 mobile-center-wrap">
        
        <!-- STEPWISE FLOWCHART SUMMARY -->
        <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-5 shadow-sm text-center sm:text-left">
          <div class="flex flex-col sm:flex-row items-center justify-between gap-2 mb-4">
            <div class="flex flex-col items-center sm:items-start">
              <h2 class="text-sm sm:text-base font-bold text-slate-900 flex items-center justify-center sm:justify-start gap-2">
                <i data-lucide="workflow" class="w-5 h-5 text-emerald-600"></i>
                Primary Care Triage Flowchart (CPG 2026)
              </h2>
              <p class="text-xs text-slate-500 mt-0.5">Connecting Malaysian Triage Scale (MTS) to PAHO Groups A, B1, B2, C</p>
            </div>
            <span class="text-[11px] font-mono bg-slate-100 dark:bg-slate-800 text-slate-600 px-2.5 py-1 rounded-md">Interactive Flow</span>
          </div>

          <!-- 4-STEP GRID -->
          <div class="grid grid-cols-1 md:grid-cols-4 gap-3 text-left">
            <div class="border-2 border-emerald-500 bg-emerald-50/50 rounded-xl p-3.5 relative flow-node">
              <span class="absolute -top-2.5 left-3 bg-emerald-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">Step 1: First Look</span>
              <h4 class="font-bold text-sm text-slate-900 mt-1">Fever & Primary Perfusion</h4>
              <p class="text-xs text-slate-600 mt-1">Fever duration, outbreak locality, ABCD, and <b>CCTVR</b> (Colour, CRT, Temp, Volume, Rate).</p>
              <div class="mt-2 text-[11px] bg-white dark:bg-slate-800 p-2 rounded border border-emerald-200">
                <span class="text-rose-600 font-bold">Shock Check:</span> CRT &gt;3s, SBP &lt;90, cold clammy skin.
              </div>
            </div>

            <div class="border-2 border-amber-500 bg-amber-50/50 rounded-xl p-3.5 relative flow-node">
              <span class="absolute -top-2.5 left-3 bg-amber-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">Step 2: Second Touch</span>
              <h4 class="font-bold text-sm text-slate-900 mt-1">Warning Signs & Phase</h4>
              <p class="text-xs text-slate-600 mt-1">Detect critical phase (defervescence D3-7) and presence of 10 classic warning signs.</p>
              <div class="mt-2 text-[11px] bg-white dark:bg-slate-800 p-2 rounded border border-amber-200">
                Abdominal pain, &gt;3x vomit, fluid leak, postural drop, HCT &ge;20% rise with PLT drop.
              </div>
            </div>

            <div class="border-2 border-sky-500 bg-sky-50/50 rounded-xl p-3.5 relative flow-node">
              <span class="absolute -top-2.5 left-3 bg-sky-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">Step 3: Modifiers</span>
              <h4 class="font-bold text-sm text-slate-900 mt-1">Comorbidity & Social</h4>
              <p class="text-xs text-slate-600 mt-1">Pregnancy, age &ge;65, obesity (BMI &gt;27.5), DM, HTN, CKD, heart disease, living alone.</p>
              <div class="mt-2 text-[11px] bg-white dark:bg-slate-800 p-2 rounded border border-sky-200">
                Low threshold for hospital admission even if warning signs absent.
              </div>
            </div>

            <div class="border-2 border-purple-500 bg-purple-50/50 rounded-xl p-3.5 relative flow-node">
              <span class="absolute -top-2.5 left-3 bg-purple-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">Step 4: Disposition</span>
              <h4 class="font-bold text-sm text-slate-900 mt-1">Classification & Plan</h4>
              <p class="text-xs text-slate-600 mt-1">Assign PAHO Group (A, B1, B2, C), trigger Act 342 notification, issue monitoring chart.</p>
              <div class="mt-2 text-[11px] bg-white dark:bg-slate-800 p-2 rounded border border-purple-200">
                <b>Notification:</b> Within 24 h of diagnosis (Act 342: compound up to RM10,000 / 2y jail).
              </div>
            </div>
          </div>
        </div>

        <!-- FORM & DISPOSITION GRID -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
          
          <!-- CLINICAL ASSESSMENT FORM (LEFT) -->
          <div class="lg:col-span-7 bg-white border border-slate-200 rounded-2xl p-4 sm:p-5 shadow-sm space-y-5">
            <div class="flex items-center justify-between border-b pb-3">
              <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                <i data-lucide="clipboard-list" class="w-4 h-4 text-emerald-600"></i> Primary Care Clinical Assessment Form
              </h3>
              <button onclick="resetTriageForm()" class="text-xs text-slate-500 hover:text-rose-600 flex items-center gap-1 active:scale-95 transition">
                <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i> Clear
              </button>
            </div>

            <!-- Demographics -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
              <div>
                <label class="block font-medium text-slate-700 mb-1">Age (Years)</label>
                <input type="number" id="in-age" value="32" class="w-full px-3 py-2 border rounded-xl focus:ring-2 focus:ring-emerald-500" oninput="runEvaluation()">
              </div>
              <div>
                <label class="block font-medium text-slate-700 mb-1">Gender</label>
                <select id="in-gender" class="w-full px-3 py-2 border rounded-xl focus:ring-2 focus:ring-emerald-500" onchange="runEvaluation()">
                  <option value="male">Male (Base 46%)</option>
                  <option value="female">Female (Base 40%)</option>
                </select>
              </div>
              <div>
                <label class="block font-medium text-slate-700 mb-1">Weight (kg)</label>
                <input type="number" id="in-weight" value="65" class="w-full px-3 py-2 border rounded-xl focus:ring-2 focus:ring-emerald-500" oninput="runEvaluation()">
              </div>
              <div>
                <label class="block font-medium text-slate-700 mb-1">Day of Illness</label>
                <input type="number" id="in-day" value="4" min="1" max="14" class="w-full px-3 py-2 border rounded-xl focus:ring-2 focus:ring-emerald-500" oninput="runEvaluation()">
              </div>
            </div>

            <!-- Phase & Patient Identifier -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
              <div>
                <label class="block font-medium text-slate-700 mb-1">Phase of Illness (CPG 4.2)</label>
                <select id="in-fever" class="w-full px-3 py-2 border rounded-xl" onchange="runEvaluation();interpretSerology()">
                  <option value="febrile">Febrile phase (fever ongoing)</option>
                  <option value="critical">Critical phase (defervescence / afebrile D3-7)</option>
                  <option value="recovery">Recovery / reabsorption phase</option>
                </select>
              </div>
              <div>
                <label class="block font-medium text-slate-700 mb-1">Patient Name / MRN (PDF only)</label>
                <input type="text" id="in-pid" maxlength="60" placeholder="Optional identifier" class="w-full px-3 py-2 border rounded-xl">
              </div>
            </div>

            <!-- Hemodynamics & CCTVR -->
            <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 space-y-3">
              <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
                <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wide">
                  Haemodynamics & CCTVR (Perfusion Assessment)
                </h4>
                <span class="text-[10px] text-slate-500">WHO Standard: Index finger 10s press</span>
              </div>
              
              <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                <div>
                  <label class="block font-medium text-slate-700 mb-1">Systolic BP</label>
                  <input type="number" id="in-sbp" value="114" class="w-full px-3 py-2 border rounded-xl bg-white" oninput="runEvaluation()">
                </div>
                <div>
                  <label class="block font-medium text-slate-700 mb-1">Diastolic BP</label>
                  <input type="number" id="in-dbp" value="78" class="w-full px-3 py-2 border rounded-xl bg-white" oninput="runEvaluation()">
                </div>
                <div>
                  <label class="block font-medium text-slate-700 mb-1">Pulse Rate</label>
                  <input type="number" id="in-hr" value="84" class="w-full px-3 py-2 border rounded-xl bg-white" oninput="runEvaluation()">
                </div>
                <div>
                  <label class="block font-medium text-slate-700 mb-1">Capillary Refill (CRT)</label>
                  <select id="in-crt" class="w-full px-3 py-2 border rounded-xl bg-white" onchange="runEvaluation()">
                    <option value="normal">&le; 3 seconds (Normal)</option>
                    <option value="delayed">&gt; 3 seconds (Hypoperfusion)</option>
                  </select>
                </div>
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                <div>
                  <label class="block font-medium text-slate-700 mb-1">Peripheries Temp</label>
                  <select id="in-temp" class="w-full px-3 py-2 border rounded-xl bg-white" onchange="runEvaluation()">
                    <option value="warm">Warm peripheries</option>
                    <option value="cold">Cold / clammy peripheries</option>
                  </select>
                </div>
                <div>
                  <label class="block font-medium text-slate-700 mb-1">Pulse Volume</label>
                  <select id="in-pulsevol" class="w-full px-3 py-2 border rounded-xl bg-white" onchange="runEvaluation()">
                    <option value="good">Good / full volume</option>
                    <option value="low">Low / thready / absent</option>
                  </select>
                </div>
                <div>
                  <label class="block font-medium text-slate-700 mb-1">Urine Output (past 6h)</label>
                  <select id="in-urine" class="w-full px-3 py-2 border rounded-xl bg-white" onchange="runEvaluation()">
                    <option value="adequate">&ge; 4-6 times/day (&gt;0.5ml/kg/h)</option>
                    <option value="reduced">Reduced / absent 4-6h</option>
                  </select>
                </div>
              </div>
            </div>

            <!-- Primary Care Laboratory Values -->
            <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 space-y-3">
              <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wide">Primary Care POCT / Laboratory Values</h4>
              <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                <div>
                  <label class="block font-medium text-slate-700 mb-1">HCT (%)</label>
                  <input type="number" id="in-hct" value="42" step="0.5" class="w-full px-3 py-2 border rounded-xl bg-white" oninput="runEvaluation()">
                </div>
                <div>
                  <label class="block font-medium text-slate-700 mb-1">Platelets (x10⁹/L)</label>
                  <input type="number" id="in-plt" value="128" class="w-full px-3 py-2 border rounded-xl bg-white" oninput="runEvaluation()">
                </div>
                <div>
                  <label class="block font-medium text-slate-700 mb-1">WCC (x10⁹/L)</label>
                  <input type="number" id="in-wcc" value="3.4" step="0.1" class="w-full px-3 py-2 border rounded-xl bg-white" oninput="runEvaluation()">
                </div>
                <div>
                  <label class="block font-medium text-slate-700 mb-1">AST / ALT (U/L)</label>
                  <input type="number" id="in-ast" value="48" class="w-full px-3 py-2 border rounded-xl bg-white" oninput="runEvaluation()">
                </div>
              </div>
            </div>

            <!-- Warning Signs Checklist -->
            <div>
              <h4 class="text-xs font-bold text-rose-700 uppercase tracking-wide mb-2 flex items-center gap-1.5">
                <i data-lucide="alert-triangle" class="w-4 h-4 text-rose-600"></i> CPG Warning Signs (Critical Phase Predictors)
              </h4>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                <label class="flex items-center gap-2.5 p-2.5 bg-slate-50 border rounded-xl hover:bg-slate-100 cursor-pointer transition">
                  <input type="checkbox" id="ws-abd" class="rounded text-rose-600 focus:ring-rose-500" onchange="runEvaluation()">
                  <span>Intense abdominal pain / tenderness</span>
                </label>
                <label class="flex items-center gap-2.5 p-2.5 bg-slate-50 border rounded-xl hover:bg-slate-100 cursor-pointer transition">
                  <input type="checkbox" id="ws-vomit" class="rounded text-rose-600 focus:ring-rose-500" onchange="runEvaluation()">
                  <span>Persistent vomiting (&gt;3 times/day) <i class="text-slate-500 text-[11px] block sm:inline">(critical phase)</i></span>
                </label>
                <label class="flex items-center gap-2.5 p-2.5 bg-slate-50 border rounded-xl hover:bg-slate-100 cursor-pointer transition">
                  <input type="checkbox" id="ws-lethargy" class="rounded text-rose-600 focus:ring-rose-500" onchange="runEvaluation()">
                  <span>Lethargy, restlessness, or confusion</span>
                </label>
                <label class="flex items-center gap-2.5 p-2.5 bg-slate-50 border rounded-xl hover:bg-slate-100 cursor-pointer transition">
                  <input type="checkbox" id="ws-bleed" class="rounded text-rose-600 focus:ring-rose-500" onchange="runEvaluation()">
                  <span>Mucosal bleed (gums, nose, menorrhagia)</span>
                </label>
                <label class="flex items-center gap-2.5 p-2.5 bg-slate-50 border rounded-xl hover:bg-slate-100 cursor-pointer transition">
                  <input type="checkbox" id="ws-fluid" class="rounded text-rose-600 focus:ring-rose-500" onchange="runEvaluation()">
                  <span>Fluid accumulation (ascites, effusion, GB edema)</span>
                </label>
                <label class="flex items-center gap-2.5 p-2.5 bg-slate-50 border rounded-xl hover:bg-slate-100 cursor-pointer transition">
                  <input type="checkbox" id="ws-liver" class="rounded text-rose-600 focus:ring-rose-500" onchange="runEvaluation()">
                  <span>Hepatomegaly (Liver enlargement &gt; 2 cm)</span>
                </label>
                <label class="flex items-center gap-2.5 p-2.5 bg-slate-50 border rounded-xl hover:bg-slate-100 cursor-pointer transition">
                  <input type="checkbox" id="ws-postural" class="rounded text-rose-600 focus:ring-rose-500" onchange="runEvaluation()">
                  <span>Postural hypotension</span>
                </label>
                <label class="flex items-center gap-2.5 p-2.5 bg-slate-50 border rounded-xl hover:bg-slate-100 cursor-pointer transition">
                  <input type="checkbox" id="ws-worse" class="rounded text-rose-600 focus:ring-rose-500" onchange="runEvaluation()">
                  <span>Worsening symptoms as fever subsides</span>
                </label>
              </div>
            </div>

            <!-- Severe Dengue Features (Group C) -->
            <div>
              <h4 class="text-xs font-bold text-rose-800 uppercase tracking-wide mb-2 flex items-center gap-1.5">
                <i data-lucide="alert-octagon" class="w-4 h-4 text-rose-600"></i> Severe Dengue Features (Group C)
              </h4>
              <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 text-xs">
                <label class="flex items-center gap-2.5 p-2.5 bg-slate-50 border rounded-xl hover:bg-slate-100 cursor-pointer transition">
                  <input type="checkbox" id="sv-bleed" class="rounded text-rose-600" onchange="runEvaluation()">
                  <span>Severe overt bleed (GI, vaginal, occult)</span>
                </label>
                <label class="flex items-center gap-2.5 p-2.5 bg-slate-50 border rounded-xl hover:bg-slate-100 cursor-pointer transition">
                  <input type="checkbox" id="sv-resp" class="rounded text-rose-600" onchange="runEvaluation()">
                  <span>Respiratory distress / fluid overload</span>
                </label>
                <label class="flex items-center gap-2.5 p-2.5 bg-slate-50 border rounded-xl hover:bg-slate-100 cursor-pointer transition">
                  <input type="checkbox" id="sv-organ" class="rounded text-rose-600" onchange="runEvaluation()">
                  <span>Severe organ impairment (liver, CNS, heart)</span>
                </label>
              </div>
            </div>

            <!-- Comorbidities & Modifiers -->
            <div>
              <h4 class="text-xs font-bold text-sky-800 uppercase tracking-wide mb-2 flex items-center gap-1.5">
                <i data-lucide="heart-pulse" class="w-4 h-4 text-sky-600"></i> Special Populations & Risk Modifiers (Group B1)
              </h4>
              <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 text-xs">
                <label class="flex items-center gap-2 p-2 bg-slate-50 border rounded-lg hover:bg-slate-100 cursor-pointer transition">
                  <input type="checkbox" id="co-preg" class="rounded text-sky-600" onchange="runEvaluation()">
                  <span>Pregnancy</span>
                </label>
                <label class="flex items-center gap-2 p-2 bg-slate-50 border rounded-lg hover:bg-slate-100 cursor-pointer transition">
                  <input type="checkbox" id="co-dm" class="rounded text-sky-600" onchange="runEvaluation()">
                  <span>Diabetes Mellitus</span>
                </label>
                <label class="flex items-center gap-2 p-2 bg-slate-50 border rounded-lg hover:bg-slate-100 cursor-pointer transition">
                  <input type="checkbox" id="co-htn" class="rounded text-sky-600" onchange="runEvaluation()">
                  <span>Hypertension</span>
                </label>
                <label class="flex items-center gap-2 p-2 bg-slate-50 border rounded-lg hover:bg-slate-100 cursor-pointer transition">
                  <input type="checkbox" id="co-ckd" class="rounded text-sky-600" onchange="runEvaluation()">
                  <span>CKD / Dialysis</span>
                </label>
                <label class="flex items-center gap-2 p-2 bg-slate-50 border rounded-lg hover:bg-slate-100 cursor-pointer transition">
                  <input type="checkbox" id="co-heart" class="rounded text-sky-600" onchange="runEvaluation()">
                  <span>Heart Failure / IHD</span>
                </label>
                <label class="flex items-center gap-2 p-2 bg-slate-50 border rounded-lg hover:bg-slate-100 cursor-pointer transition">
                  <input type="checkbox" id="co-social" class="rounded text-sky-600" onchange="runEvaluation()">
                  <span>Social / Alone</span>
                </label>
                <label class="flex items-center gap-2 p-2 bg-slate-50 border rounded-lg hover:bg-slate-100 cursor-pointer transition">
                  <input type="checkbox" id="co-obese" class="rounded text-sky-600" onchange="runEvaluation()">
                  <span>Obesity</span>
                </label>
                <label class="flex items-center gap-2 p-2 bg-slate-50 border rounded-lg hover:bg-slate-100 cursor-pointer transition">
                  <input type="checkbox" id="co-resp" class="rounded text-sky-600" onchange="runEvaluation()">
                  <span>Asthma / COPD</span>
                </label>
                <label class="flex items-center gap-2 p-2 bg-slate-50 border rounded-lg hover:bg-slate-100 cursor-pointer transition">
                  <input type="checkbox" id="co-liver" class="rounded text-sky-600" onchange="runEvaluation()">
                  <span>Liver Cirrhosis</span>
                </label>
                <label class="flex items-center gap-2 p-2 bg-slate-50 border rounded-lg hover:bg-slate-100 cursor-pointer transition">
                  <input type="checkbox" id="co-haem" class="rounded text-sky-600" onchange="runEvaluation()">
                  <span>Haemolytic Dis.</span>
                </label>
                <label class="flex items-center gap-2 p-2 bg-slate-50 border rounded-lg hover:bg-slate-100 cursor-pointer transition">
                  <input type="checkbox" id="co-pud" class="rounded text-sky-600" onchange="runEvaluation()">
                  <span>Peptic Ulcer</span>
                </label>
                <label class="flex items-center gap-2 p-2 bg-slate-50 border rounded-lg hover:bg-slate-100 cursor-pointer transition">
                  <input type="checkbox" id="co-antico" class="rounded text-sky-600" onchange="runEvaluation()">
                  <span>Anticoagulant</span>
                </label>
              </div>
            </div>
          </div>

          <!-- DISPOSITION CARD (RIGHT / CENTERED FOR PHONES) -->
          <div class="lg:col-span-5 space-y-4">
            
            <div id="disposition-card" class="bg-emerald-50 border-2 border-emerald-500 rounded-2xl p-4 sm:p-5 shadow-sm transition-all duration-300 text-center sm:text-left">
              <div class="flex flex-col sm:flex-row items-center justify-between gap-2">
                <span id="disposition-badge" class="bg-emerald-600 text-white font-bold text-xs uppercase px-3 py-1.5 rounded-lg shadow-sm">
                  Group A • Outpatient Care
                </span>
                <span id="mts-level" class="text-xs font-semibold text-emerald-800">MTS Level 5 (Green)</span>
              </div>

              <h3 id="disposition-title" class="text-base sm:text-lg font-extrabold text-slate-900 mt-3">
                Uncomplicated Dengue (Without Warning Signs)
              </h3>

              <p id="disposition-desc" class="text-xs text-slate-700 mt-2 leading-relaxed">
                Patient is hemodynamically stable with adequate oral fluid tolerance and no clinical warning signs or acute plasma leakage. Suitable for daily primary care follow-up.
              </p>

              <!-- HEMODYNAMIC SUMMARY TILES (CENTERED) -->
              <div class="mt-4 p-3 bg-white/90 dark:bg-slate-800/90 rounded-xl border border-slate-200 grid grid-cols-3 gap-2 text-center text-xs">
                <div>
                  <span class="text-[10px] text-slate-500 block">Pulse Pressure</span>
                  <span id="calc-pp" class="font-bold text-slate-800 text-xs sm:text-sm">36 mmHg</span>
                </div>
                <div>
                  <span class="text-[10px] text-slate-500 block">Mean Arterial (MAP)</span>
                  <span id="calc-map" class="font-bold text-slate-800 text-xs sm:text-sm">90 mmHg</span>
                </div>
                <div>
                  <span class="text-[10px] text-slate-500 block">HCT Deviation</span>
                  <span id="calc-hct-diff" class="font-bold text-slate-800 text-xs sm:text-sm">-8.7%</span>
                </div>
              </div>

              <!-- ACTION LIST -->
              <div class="mt-4 pt-4 border-t border-emerald-200/60 text-left">
                <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-2.5 text-center sm:text-left">
                  Primary Care Action Protocol
                </h4>
                <ul id="action-list" class="text-xs text-slate-700 space-y-2.5">
                  <li class="flex items-start gap-2">
                    <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-600 flex-shrink-0 mt-0.5"></i>
                    <span><b>Oral Hydration:</b> 200 ml/hr while awake (ORS, fruit juice, soup, coconut water). Avoid plain water alone.</span>
                  </li>
                  <li class="flex items-start gap-2">
                    <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-600 flex-shrink-0 mt-0.5"></i>
                    <span><b>Daily Clinic Review:</b> Clinical assessment, CCTVR, and serial FBC daily until afebrile for 48h.</span>
                  </li>
                </ul>
              </div>

              <!-- DISEASE NOTIFICATION BANNER -->
              <div class="mt-4 p-2.5 bg-rose-50 border border-rose-200 rounded-xl flex items-center justify-between text-[11px] text-rose-800">
                <span class="flex items-center gap-1.5 font-medium">
                  <i data-lucide="bell-ring" class="w-3.5 h-3.5 text-rose-600"></i>
                  Act 342 Notification Required
                </span>
                <span class="font-bold">&lt; 24 Hours</span>
              </div>
            </div>

            <!-- RAPID COMBO TEST (RDT) INTERPRETER -->
            <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm space-y-3 text-center sm:text-left">
              <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wide flex items-center justify-center sm:justify-start gap-1.5">
                <i data-lucide="microscope" class="w-4 h-4 text-sky-600"></i> Rapid Combo Test (RDT) Interpreter
              </h4>
              <div class="grid grid-cols-3 gap-2 text-xs text-left">
                <div>
                  <label class="block text-slate-600 text-[10px] mb-1">NS1 Antigen</label>
                  <select id="rdt-ns1" class="w-full px-2 py-1.5 border rounded-lg bg-white" onchange="interpretSerology()">
                    <option value="pos">Positive (+)</option>
                    <option value="neg">Negative (-)</option>
                  </select>
                </div>
                <div>
                  <label class="block text-slate-600 text-[10px] mb-1">IgM Antibody</label>
                  <select id="rdt-igm" class="w-full px-2 py-1.5 border rounded-lg bg-white" onchange="interpretSerology()">
                    <option value="neg">Negative (-)</option>
                    <option value="pos">Positive (+)</option>
                  </select>
                </div>
                <div>
                  <label class="block text-slate-600 text-[10px] mb-1">IgG Antibody</label>
                  <select id="rdt-igg" class="w-full px-2 py-1.5 border rounded-lg bg-white" onchange="interpretSerology()">
                    <option value="neg">Negative (-)</option>
                    <option value="pos">Positive (+)</option>
                  </select>
                </div>
              </div>
              <div id="serology-result-box" class="p-2.5 bg-sky-50 border border-sky-200 rounded-xl text-xs text-sky-900 leading-snug text-left">
                <b>Acute Dengue Infection Confirmed:</b> Early primary infection indicated by positive NS1 within Day 1-5 of illness.
              </div>
            </div>

          </div>
        </div>
      </section>

      <!-- ==================== TAB 2: LABS & RED FLAGS ==================== -->
      <section id="tab-content-labs" class="hidden space-y-6 mobile-center-wrap">
        <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-5 shadow-sm space-y-4">
          <div class="flex flex-col sm:flex-row items-center justify-between border-b pb-3 text-center sm:text-left gap-2">
            <div>
              <h2 class="text-base font-bold text-slate-900 flex items-center justify-center sm:justify-start gap-2">
                <i data-lucide="file-spreadsheet" class="w-5 h-5 text-sky-600"></i>
                Laboratory Ranges & Red Flag Action Matrix
              </h2>
              <p class="text-xs text-slate-500">Chapters 7 & 8 • CPG Dengue in Adults (Fourth Edition, 2026)</p>
            </div>
            <span class="text-xs bg-sky-50 text-sky-700 px-3 py-1 rounded-full font-medium border border-sky-200">Point-of-Care Ready</span>
          </div>

          <div class="overflow-x-auto -mx-4 sm:mx-0 px-4 sm:px-0">
            <table class="w-full text-left text-xs border-collapse min-w-[580px]">
              <thead>
                <tr class="bg-slate-100 text-slate-700 uppercase tracking-wider border-b border-slate-200">
                  <th class="p-3 font-bold">Investigation</th>
                  <th class="p-3 font-bold">Adult Baseline Range</th>
                  <th class="p-3 font-bold text-rose-600">Red Flag Trigger</th>
                  <th class="p-3 font-bold">Primary Care Action Plan</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100 text-slate-700">
                <tr class="hover:bg-slate-50">
                  <td class="p-3 font-bold text-slate-900">Haematocrit (HCT)</td>
                  <td class="p-3">
                    • Male &le;60y: <b>46%</b><br>
                    • Male &gt;60y: <b>42%</b><br>
                    • Female: <b>40%</b>
                  </td>
                  <td class="p-3 text-rose-700 font-semibold">
                    • Rising &ge;20% from baseline<br>
                    • Sudden drop in HCT with persistent shock
                  </td>
                  <td class="p-3">
                    <b>Rise &ge;20%:</b> Severe plasma leakage. Admit and titrate IV fluid within 48h quota (Group B2).<br>
                    <b>Drop with shock:</b> Unmasks occult bleed. Urgent cross-match & PRBC transfusion (Group C).
                  </td>
                </tr>
                <tr class="hover:bg-slate-50">
                  <td class="p-3 font-bold text-slate-900">Platelet Count</td>
                  <td class="p-3">150 – 400 &times; 10⁹/L</td>
                  <td class="p-3 text-rose-700 font-semibold">
                    • Rapid drop &lt;100 &times; 10⁹/L<br>
                    • Severe thrombocytopenia &lt;50 &times; 10⁹/L
                  </td>
                  <td class="p-3">
                    <b>&lt;100:</b> Signals entry into critical phase.<br>
                    <b>&lt;50:</b> High mortality risk factor (OR 15.7).<br>
                    <span class="text-slate-500 italic">Do not give prophylactic platelet transfusion if no active bleed.</span>
                  </td>
                </tr>
                <tr class="hover:bg-slate-50">
                  <td class="p-3 font-bold text-slate-900">White Cell Count (WCC)</td>
                  <td class="p-3">4.0 – 10.0 &times; 10⁹/L</td>
                  <td class="p-3 text-rose-700 font-semibold">
                    • Leucopenia &lt;4.0 &times; 10⁹/L<br>
                    • Leucocytosis in critical phase
                  </td>
                  <td class="p-3">
                    <b>Leucopenia:</b> Diagnostic hallmark in febrile phase (AUC 0.91 in elderly).<br>
                    <b>Leucocytosis:</b> Screen for secondary bacterial sepsis or HLH.
                  </td>
                </tr>
                <tr class="hover:bg-slate-50">
                  <td class="p-3 font-bold text-slate-900">AST / ALT Transaminases</td>
                  <td class="p-3">AST: 10–40 U/L<br>ALT: 7–56 U/L (AST &gt; ALT typical)</td>
                  <td class="p-3 text-rose-700 font-semibold">
                    • AST/ALT &ge;100 U/L on D3-4<br>
                    • Severe hepatitis &ge;1000 U/L (DISH)
                  </td>
                  <td class="p-3">
                    <b>&ge;100:</b> High plasma leak score.<br>
                    <b>&ge;1000:</b> Severe Dengue with liver failure. Stop paracetamol immediately, admit to hospital/ICU.
                  </td>
                </tr>
                <tr class="hover:bg-slate-50">
                  <td class="p-3 font-bold text-slate-900">Urine Output</td>
                  <td class="p-3">&ge;0.5 – 1.0 ml/kg/hr<br>(Voids 4–6 times/day)</td>
                  <td class="p-3 text-rose-700 font-semibold">
                    • Oliguria &lt;0.5 ml/kg/hr<br>
                    • Absent urine for 4–6 hours
                  </td>
                  <td class="p-3">
                    Reflects reduced renal perfusion or AKI. Transfer for IV rehydration. In diabetics, exclude osmotic diuresis false security.
                  </td>
                </tr>
                <tr class="hover:bg-slate-50">
                  <td class="p-3 font-bold text-slate-900">BP & Pulse Pressure</td>
                  <td class="p-3">Normal: SBP 100–120, PP &gt;30 mmHg</td>
                  <td class="p-3 text-rose-700 font-semibold">
                    • PP 20–30 mmHg (Compensated Shock)<br>
                    • PP &lt;20 mmHg or SBP &lt;90 (Decompensated)
                  </td>
                  <td class="p-3">
                    <b>Group C Emergency:</b> Resuscitate with crystalloids immediately (10–20 ml/kg). Do not delay while awaiting transfer.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </section>

      <!-- ==================== TAB 3: 48-HOUR FLUID QUOTA ==================== -->
      <section id="tab-content-fluid" class="hidden space-y-6 mobile-center-wrap">
        <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-5 shadow-sm space-y-5 text-center sm:text-left">
          <div class="flex flex-col sm:flex-row items-center justify-between border-b pb-3 gap-2">
            <div>
              <h2 class="text-base font-bold text-slate-900 flex items-center justify-center sm:justify-start gap-2">
                <i data-lucide="calculator" class="w-5 h-5 text-blue-600"></i>
                CPG 2026 Fluid Quota Calculator (Group B2)
              </h2>
              <p class="text-xs text-slate-500">Fluid Quota = 1-day Maintenance + 5% Deficit over 48h, weight capped at 70 kg</p>
            </div>
            <span class="text-xs bg-blue-50 text-blue-700 px-3 py-1 rounded-full font-medium border border-blue-200">Weight Cap: 70 kg</span>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-left">
            <div class="space-y-4 text-xs">
              <div>
                <label class="block font-medium text-slate-700 mb-1">Patient Actual Weight (kg)</label>
                <input type="number" id="fq-weight" value="65" class="w-full px-3 py-2 border rounded-xl" oninput="calcFluidQuota()">
              </div>
              <div>
                <label class="block font-medium text-slate-700 mb-1">Patient Height (cm) (for BMI/ABW)</label>
                <input type="number" id="fq-height" value="168" class="w-full px-3 py-2 border rounded-xl" oninput="calcFluidQuota()">
              </div>
              <div>
                <label class="block font-medium text-slate-700 mb-1">Patient Gender</label>
                <select id="fq-gender" class="w-full px-3 py-2 border rounded-xl" onchange="calcFluidQuota()">
                  <option value="male">Male</option>
                  <option value="female">Female</option>
                </select>
              </div>
              <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl text-amber-800 text-[11px] leading-relaxed">
                <b>CPG Safety Principle:</b> Weight is strictly capped at <b>70 kg</b> to prevent fluid overload from the Revised Starling endothelial leak. In obesity (BMI &gt;27.5), Adjusted Body Weight (ABW) is applied.
              </div>
            </div>

            <!-- CALCULATED VOLUMES -->
            <div class="space-y-3 bg-slate-50 p-4 rounded-xl border border-slate-200 text-xs">
              <h4 class="font-bold text-slate-800 uppercase tracking-wide text-center sm:text-left">Calculated Volumes</h4>
              <div class="flex justify-between py-1.5 border-b">
                <span class="text-slate-600">Calculated Weight:</span>
                <span id="fq-calc-wt" class="font-bold text-slate-900">65.0 kg</span>
              </div>
              <div class="flex justify-between py-1.5 border-b">
                <span class="text-slate-600">1-Day Maintenance (A):</span>
                <span id="fq-maint-day" class="font-bold text-slate-900">2,500 ml</span>
              </div>
              <div class="flex justify-between py-1.5 border-b">
                <span class="text-slate-600">Hourly Maintenance (4-2-1):</span>
                <span id="fq-maint-48" class="font-bold text-slate-900">105 ml/hr</span>
              </div>
              <div class="flex justify-between py-1.5 border-b">
                <span class="text-slate-600">5% Deficit (50ml/kg) (B):</span>
                <span id="fq-deficit" class="font-bold text-slate-900">3,250 ml</span>
              </div>
              <div class="flex justify-between py-2 border-t-2 border-slate-300 text-sm font-extrabold text-blue-700">
                <span>Total 48h Quota (Oral + IV):</span>
                <span id="fq-total-quota">5,700 ml</span>
              </div>
              <p id="fq-note" class="text-[11px] text-slate-500 leading-relaxed pt-2"></p>
            </div>

            <!-- ALGORITHM 1 STEPDOWN RATES -->
            <div class="space-y-3 bg-blue-50/50 p-4 rounded-xl border border-blue-200 text-xs">
              <h4 class="font-bold text-blue-900 uppercase tracking-wide text-center sm:text-left">Algorithm 1 Infusion Rates</h4>
              <p class="text-[11px] text-slate-600">IV crystalloid 3-5 ml/kg/hr for 1-2h if oral fluids not tolerated. Reduce stepwise upon improvement:</p>
              <div class="space-y-2 mt-2">
                <div class="flex justify-between p-2 bg-white rounded-lg border">
                  <span>R5 (5 ml/kg/hr - Initial):</span>
                  <span id="rate-r5" class="font-mono font-bold text-slate-800">325 ml/hr</span>
                </div>
                <div class="flex justify-between p-2 bg-white rounded-lg border">
                  <span>R3 (3 ml/kg/hr - Stepdown):</span>
                  <span id="rate-r3" class="font-mono font-bold text-slate-800">195 ml/hr</span>
                </div>
                <div class="flex justify-between p-2 bg-white rounded-lg border">
                  <span>R2 (2 ml/kg/hr - Stepdown):</span>
                  <span id="rate-r2" class="font-mono font-bold text-slate-800">130 ml/hr</span>
                </div>
                <div class="flex justify-between p-2 bg-white rounded-lg border">
                  <span>R1 (1 ml/kg/hr - Maintenance):</span>
                  <span id="rate-r1" class="font-mono font-bold text-slate-800">65 ml/hr</span>
                </div>
                <div class="flex justify-between p-2 bg-white rounded-lg border">
                  <span>R7 (7 ml/kg/hr - Refractory non-shock):</span>
                  <span id="rate-r7" class="font-mono font-bold text-slate-800">455 ml/hr</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ==================== TAB 4: CLINICAL REASONING ==================== -->
      <section id="tab-content-reasoning" class="hidden space-y-6 mobile-center-wrap">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-left">
          
          <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-5 shadow-sm space-y-2.5">
            <div class="flex items-center gap-2 text-rose-600">
              <i data-lucide="git-commit" class="w-5 h-5"></i>
              <h3 class="font-bold text-sm text-slate-900">Revised Starling Law & EGL</h3>
            </div>
            <p class="text-xs text-slate-600 leading-relaxed">
              Traditional models assumed 90% venular reabsorption. Current science confirms 100% of microvascular filtration drains via lymphatics due to the Endothelial Glycocalyx Layer (EGL). Dengue NS1 damages the EGL; excess IV crystalloids leak directly into third spaces and trigger pulmonary edema during recovery phase reabsorption.
            </p>
          </div>

          <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-5 shadow-sm space-y-2.5">
            <div class="flex items-center gap-2 text-amber-600">
              <i data-lucide="droplet-off" class="w-5 h-5"></i>
              <h3 class="font-bold text-sm text-slate-900">Occult Bleeding vs Plasma Leakage</h3>
            </div>
            <p class="text-xs text-slate-600 leading-relaxed">
              Plasma leakage manifests as haemoconcentration (rising HCT &ge;20%). If a patient presents in shock but HCT drops or remains normal, severe occult haemorrhage is occurring. Urgent PRBC transfusion (5–10 ml/kg) and cross-matching take priority over further crystalloids.
            </p>
          </div>

          <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-5 shadow-sm space-y-2.5">
            <div class="flex items-center gap-2 text-purple-600">
              <i data-lucide="ban" class="w-5 h-5"></i>
              <h3 class="font-bold text-sm text-slate-900">Prohibited Primary Care Medications</h3>
            </div>
            <p class="text-xs text-slate-600 leading-relaxed">
              NSAIDs (mefenamic acid, ibuprofen) and aspirin cause gastric mucosal ulceration and platelet inhibition in patients with preexisting thrombocytopenia, precipitating catastrophic GI bleeding. Corticosteroids confer no clinical benefit and elevate bacterial sepsis risks.
            </p>
          </div>

          <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-5 shadow-sm space-y-2.5">
            <div class="flex items-center gap-2 text-sky-600">
              <i data-lucide="users" class="w-5 h-5"></i>
              <h3 class="font-bold text-sm text-slate-900">Special Populations: Low Admission Threshold</h3>
            </div>
            <p class="text-xs text-slate-600 leading-relaxed">
              In pregnancy, physiological haemodilution masks rising HCT, while resting tachycardia masks early shock. In older adults, dengue presents atypically with lethargy and leucopenia rather than classic high fever. Both groups require hospital admission even without warning signs.
            </p>
          </div>

        </div>
      </section>

      <!-- ==================== TAB 5: PATIENT LEAFLET ==================== -->
      <section id="tab-content-homecare" class="hidden space-y-6 mobile-center-wrap">
        <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-6 shadow-sm max-w-4xl mx-auto space-y-6 text-center sm:text-left">
          <div class="flex flex-col sm:flex-row items-center justify-between border-b pb-4 gap-3">
            <div>
              <h2 class="text-lg font-bold text-slate-900">Outpatient Dengue Home Care & Warning Leaflet</h2>
              <p class="text-xs text-slate-500">Appendix 5B MOH CPG 2026 • Bilingual Primary Care Patient Take-Home Sheet</p>
            </div>
            <div class="flex items-center justify-center gap-2 no-print w-full sm:w-auto">
              <button onclick="saveLeafletPdf()" class="flex-1 sm:flex-initial px-3.5 py-2 bg-emerald-700 text-white rounded-xl text-xs font-semibold flex items-center justify-center gap-1.5 hover:bg-emerald-800 shadow-sm active:scale-95 transition">
                <i data-lucide="file-down" class="w-3.5 h-3.5"></i> Save PDF
              </button>
              <button onclick="printLeaflet()" class="flex-1 sm:flex-initial px-3.5 py-2 bg-slate-900 text-white rounded-xl text-xs font-semibold flex items-center justify-center gap-1.5 hover:bg-slate-800 shadow-sm active:scale-95 transition">
                <i data-lucide="printer" class="w-3.5 h-3.5"></i> Print
              </button>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-xs leading-relaxed text-left">
            <!-- English -->
            <div class="border rounded-xl p-4 bg-slate-50 space-y-3">
              <div class="border-b pb-2 text-center sm:text-left">
                <h3 class="font-bold text-sm text-slate-900">Home Care Advice (English)</h3>
                <p class="text-[11px] text-slate-500">For patients suitable for outpatient management</p>
              </div>
              <div class="space-y-1">
                <h4 class="font-bold text-emerald-800">WHAT TO DO:</h4>
                <ul class="list-disc pl-4 space-y-1 text-slate-700">
                  <li>Drink 200 ml fluid every hour while awake (ORS, fruit juice, coconut water, soup).</li>
                  <li>Ensure you pass urine at least 4 to 6 times a day.</li>
                  <li>Take Paracetamol for fever or pain, but <b>never exceed 4,000 mg (8 tablets of 500mg) per day</b>.</li>
                  <li>Attend daily blood tests and doctor appointments without fail.</li>
                </ul>
              </div>
              <div class="space-y-1 pt-2">
                <h4 class="font-bold text-rose-800">WHAT TO AVOID:</h4>
                <ul class="list-disc pl-4 space-y-1 text-slate-700">
                  <li><b>DO NOT take NSAIDs or pain relievers</b> (Ibuprofen, Voltaren, Ponstan, Aspirin).</li>
                  <li>DO NOT take steroids or unverified herbal extracts.</li>
                  <li>DO NOT take intramuscular injections, cupping (bekam), or vigorous massage.</li>
                </ul>
              </div>
              <div class="p-2.5 bg-rose-100/60 border border-rose-200 rounded-xl text-rose-900">
                <h4 class="font-bold text-xs uppercase text-rose-800">DANGER SIGNS - GO TO HOSPITAL IMMEDIATELY:</h4>
                <p class="text-[11px] mt-1">Severe stomach pain, persistent vomiting, bleeding gums/nose, black stools, fainting spells, extreme lethargy or fast breathing as fever starts to subside.</p>
              </div>
            </div>

            <!-- Bahasa Melayu -->
            <div class="border rounded-xl p-4 bg-slate-50 space-y-3">
              <div class="border-b pb-2 text-center sm:text-left">
                <h3 class="font-bold text-sm text-slate-900">Nasihat Penjagaan Di Rumah (BM)</h3>
                <p class="text-[11px] text-slate-500">Untuk pesakit yang dirawat sebagai pesakit luar</p>
              </div>
              <div class="space-y-1">
                <h4 class="font-bold text-emerald-800">APA YANG PERLU DILAKUKAN:</h4>
                <ul class="list-disc pl-4 space-y-1 text-slate-700">
                  <li>Minum air secukupnya 200 ml setiap jam sewaktu jaga (garam hidrasi ORS, jus buah, air kelapa, sup).</li>
                  <li>Pastikan anda membuang air kencing sekurang-kurangnya 4 hingga 6 kali sehari.</li>
                  <li>Ambil Paracetamol jika demam, <b>tidak melebihi 4,000 mg (8 biji 500mg) sehari</b>.</li>
                  <li>Hadir temujanji pemeriksaan darah dan doktor setiap hari.</li>
                </ul>
              </div>
              <div class="space-y-1 pt-2">
                <h4 class="font-bold text-rose-800">APA YANG MESTI DIELAKKAN:</h4>
                <ul class="list-disc pl-4 space-y-1 text-slate-700">
                  <li><b>JANGAN ambil ubat tahan sakit NSAID</b> seperti Ibuprofen, Voltaren, Ponstan, atau Aspirin.</li>
                  <li>JANGAN ambil steroid, ubat tradisional, atau jus herba yang tidak disahkan.</li>
                  <li>JANGAN ambil suntikan ke dalam otot, berbekam, atau mengurut.</li>
                </ul>
              </div>
              <div class="p-2.5 bg-rose-100/60 border border-rose-200 rounded-xl text-rose-900">
                <h4 class="font-bold text-xs uppercase text-rose-800">TANDA BAHAYA - SEGERA KE KECEMASAN HOSPITAL:</h4>
                <p class="text-[11px] mt-1">Sakit perut yang teruk, muntah berterusan, pendarahan gusi/hidung, najis hitam, pitam, sangat lemah atau sesak nafas apabila demam mula kebah.</p>
              </div>
            </div>
          </div>
        </div>
      </section>

    </main>

    <!-- FLOATING RESULT ACTION PILL FOR SMARTPHONES (CENTERED) -->
    <div class="sm:hidden fixed inset-x-0 z-[55] flex justify-center px-3" style="bottom: calc(66px + env(safe-area-inset-bottom, 0px));">
      <button id="m-result" type="button" onclick="document.getElementById('disposition-card').scrollIntoView({behavior:'smooth',block:'start'})" class="w-full max-w-sm rounded-2xl px-4 py-3 text-sm font-bold text-white shadow-xl flex items-center justify-between bg-emerald-600 active:scale-95 transition-transform backdrop-blur-md">
        <span id="m-result-text" class="truncate pr-2">Group A • Outpatient Care</span>
        <span class="text-xs font-semibold bg-white/20 px-2 py-0.5 rounded-full flex-shrink-0">View Plan &rsaquo;</span>
      </button>
    </div>

    <!-- DOCKED PHONE BOTTOM NAVIGATION BAR (CENTERED WITH SAFE-AREA) -->
    <nav id="mobile-nav-bar" class="sm:hidden bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border-t border-slate-200 dark:border-slate-800">
      <button onclick="switchTab('triage')" id="m-tab-btn-triage" class="text-emerald-600 transition">
        <i data-lucide="git-branch"></i>
        <span>Triage</span>
      </button>
      <button onclick="switchTab('labs')" id="m-tab-btn-labs" class="text-slate-500 transition">
        <i data-lucide="flask-conical"></i>
        <span>Labs</span>
      </button>
      <button onclick="switchTab('fluid')" id="m-tab-btn-fluid" class="text-slate-500 transition">
        <i data-lucide="droplet"></i>
        <span>Fluids</span>
      </button>
      <button onclick="switchTab('reasoning')" id="m-tab-btn-reasoning" class="text-slate-500 transition">
        <i data-lucide="brain"></i>
        <span>Reasoning</span>
      </button>
      <button onclick="switchTab('homecare')" id="m-tab-btn-homecare" class="text-slate-500 transition">
        <i data-lucide="printer"></i>
        <span>Leaflet</span>
      </button>
    </nav>

    <footer class="max-w-7xl mx-auto px-4 pb-6 text-[11px] text-slate-500 leading-relaxed no-print">
      Unofficial decision-support aid based on MOH Malaysia, <i>Management of Dengue in Adults (4th Edition, 2026)</i>. It does not replace clinical judgement, local protocols, or specialist consultation. Check results against the official CPG.
    </footer>
  </div>

  <!-- ==========================================
       APPLICATION LOGIC ENGINE
       ========================================== -->
  <script>
    function switchTab(tabId) {
      const tabs = ['triage', 'labs', 'fluid', 'reasoning', 'homecare'];
      
      tabs.forEach(t => {
        document.getElementById(`tab-content-${t}`).classList.add('hidden');
        
        // Desktop nav styling
        const btnD = document.getElementById(`tab-btn-${t}-d`);
        if (btnD) {
          btnD.classList.remove('bg-white', 'shadow-sm', 'text-slate-900');
          btnD.classList.add('text-slate-600');
        }

        // Mobile bottom nav styling
        const btnM = document.getElementById(`m-tab-btn-${t}`);
        if (btnM) {
          btnM.classList.remove('text-emerald-600', 'font-bold');
          btnM.classList.add('text-slate-500');
        }
      });

      document.getElementById(`tab-content-${tabId}`).classList.remove('hidden');
      
      const activeBtnD = document.getElementById(`tab-btn-${tabId}-d`);
      if (activeBtnD) {
        activeBtnD.classList.add('bg-white', 'shadow-sm', 'text-slate-900');
        activeBtnD.classList.remove('text-slate-600');
      }

      const activeBtnM = document.getElementById(`m-tab-btn-${tabId}`);
      if (activeBtnM) {
        activeBtnM.classList.add('text-emerald-600', 'font-bold');
        activeBtnM.classList.remove('text-slate-500');
      }

      const mr = document.getElementById('m-result');
      if (mr) mr.parentElement.style.display = (tabId === 'triage') ? 'flex' : 'none';
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    const $ = (id) => document.getElementById(id);
    const chk = (id) => $(id) && $(id).checked;
    const li = (ic, icon, txt, bold) => `<li class="flex items-start gap-2 ${bold || ''}"><i data-lucide="${icon}" class="w-4 h-4 ${ic} flex-shrink-0 mt-0.5"></i><span>${txt}</span></li>`;

    function runEvaluation() {
      const age = parseFloat($('in-age').value) || 30;
      const gender = $('in-gender').value;
      const weight = Math.min(parseFloat($('in-weight').value) || 60, 250);
      const sbp = parseFloat($('in-sbp').value) || 120;
      const dbp = parseFloat($('in-dbp').value) || 80;
      const hr = parseFloat($('in-hr').value) || 80;
      const crt = $('in-crt').value, temp = $('in-temp').value, pulsevol = $('in-pulsevol').value, urine = $('in-urine').value;
      const hct = parseFloat($('in-hct').value) || 40;
      const plt = parseFloat($('in-plt').value) || 150;
      const ast = parseFloat($('in-ast').value) || 30;
      const critical = $('in-fever').value === 'critical';

      const pp = sbp - dbp;
      const map = Math.round(dbp + (pp / 3));
      const baseHct = (gender === 'male') ? (age > 60 ? 42 : 46) : 40;
      const hctDiff = ((hct - baseHct) / baseHct) * 100;
      $('calc-pp').innerText = `${pp} mmHg`;
      $('calc-map').innerText = `${map} mmHg`;
      $('calc-hct-diff').innerText = `${hctDiff > 0 ? '+' : ''}${hctDiff.toFixed(1)}%`;

      // Warning signs (CPG 4.2.2 / Table 6)
      const vomitCounts = chk('ws-vomit') && critical;
      const hasWS = chk('ws-abd') || vomitCounts || chk('ws-lethargy') || chk('ws-bleed') || chk('ws-fluid') || chk('ws-liver') ||
                    chk('ws-postural') || chk('ws-worse') || urine === 'reduced' || hctDiff >= 20;

      // Reduced-perfusion signs (CPG Algorithms 2 & 3)
      const signs = [hr > 100, temp === 'cold', crt === 'delayed', pp >= 20 && pp <= 30, pulsevol === 'low'].filter(Boolean).length;
      const decomp = sbp < 90 || (pp < 20 && signs >= 1);
      const comp = !decomp && signs >= 2;
      const severeFeat = chk('sv-bleed') || chk('sv-resp') || chk('sv-organ') || ast >= 1000;
      const groupC = decomp || comp || severeFeat;

      const preg = chk('co-preg');
      const hasComorb = preg || ['co-dm','co-htn','co-ckd','co-heart','co-social','co-obese','co-resp','co-liver','co-haem','co-pud','co-antico'].some(chk) || age >= 65;

      const notes = [];
      if (chk('ws-vomit') && !critical) notes.push(li('text-amber-600','info','Vomiting in the febrile phase is not by itself a warning sign (CPG). Check hydration; if oral fluids are poorly tolerated, initiate IV crystalloids and refer.'));
      if (!groupC && signs === 1) notes.push(li('text-amber-600','info','One reduced-perfusion sign detected (tachycardia, cold peripheries, CRT &gt;3s, PP 20-30, low pulse volume). Re-check CCTVR, BP, and urine output within 1h; &ge;2 signs = compensated shock.'));
      if (chk('co-htn')) notes.push(li('text-sky-600','info','Hypertension: interpret BP relative to the patient&#39;s baseline. A "normal" BP may represent relative hypotension.'));
      notes.push(li('text-slate-500','pill','Paracetamol at lowest effective dose, max 4 g/day; avoid or discontinue if liver transaminases are deranged' + (ast > 40 ? ' (AST/ALT entered is elevated)' : '') + '. Do not initiate NSAIDs or steroids.'));
      if (chk('co-dm')) notes.push(li('text-slate-500','pill','SGLT2 inhibitors: withhold immediately (sick-day rules). Metformin and statins may continue unless renal impairment or shock is present.'));
      if (chk('co-antico')) notes.push(li('text-amber-600','info','On anticoagulant: monitor closely and arrange hospital referral. Antiplatelet (e.g. recent stenting): individualise with cardiology.'));
      if (ast >= 1000) notes.push(li('text-rose-600','alert-octagon','AST/ALT &ge;1000 U/L: severe hepatitis (DISH). Cease paracetamol immediately and manage in ICU/HDU.'));

      const card = $('disposition-card'), badge = $('disposition-badge'), mts = $('mts-level'), title = $('disposition-title'), desc = $('disposition-desc');
      let items = '';
      const bd = 'font-bold text-xs uppercase px-3 py-1.5 rounded-lg text-white shadow-sm';

      if (groupC) {
        card.className = "bg-rose-50 border-2 border-rose-600 rounded-2xl p-4 sm:p-5 shadow-sm transition-all duration-300 text-center sm:text-left";
        badge.className = bd + " bg-rose-600 badge-pulse";
        badge.innerText = "Group C • Severe Dengue";
        mts.className = "text-xs font-semibold text-rose-800";
        mts.innerText = decomp ? "MTS Level 1 (Red Zone Resuscitation)" : "MTS Level 2 (Red Zone Emergency)";
        title.innerText = decomp ? "Decompensated Dengue Shock" : comp ? "Compensated Dengue Shock" : "Severe Dengue (Bleeding / Organ / Respiratory)";
        desc.innerText = "Medical emergency. Resuscitate with crystalloids immediately and transfer to hospital ED/ICU now. Do not delay fluid resuscitation while awaiting transfer.";
        const k = decomp ? 20 : 10;
        const vol = chk('co-obese') ? 'use adjusted body weight (see Fluid tab)' : `about ${Math.round(k * Math.min(weight, 250))} ml for ${weight} kg`;
        if (decomp) items += li('text-rose-600','alert-octagon',`<b>Resuscitation:</b> Crystalloid 20 ml/kg within 15-30 min (${vol}). If no improvement, repeat HCT and run second bolus 10-20 ml/kg (Algorithm 3).`,'text-rose-900 font-semibold');
        else if (comp) items += li('text-rose-600','alert-octagon',`<b>Resuscitation:</b> Crystalloid 10 ml/kg over 1 hour (${vol}); reassess CCTVR, BP, urine output, and HCT (Algorithm 2).`,'text-rose-900 font-semibold');
        else items += li('text-rose-600','alert-octagon','<b>Severe Dengue Features:</b> Establish IV access, begin supportive care, and arrange urgent ED/ICU transfer.','text-rose-900 font-semibold');
        if (chk('sv-resp')) items += li('text-rose-600','alert-octagon','<b>Respiratory Distress:</b> Suspect severe plasma leakage, effusion, or fluid overload. Reassess fluid status before boluses.');
        items += li('text-rose-600','alert-octagon','<b>Pre-Resuscitation Labs (do not delay):</b> HCT/FBC, RP, LFT, RBS, PT/APTT, CK, VBG/lactate, and emergency cross-match (GXM).');
        items += li('text-rose-600','alert-octagon','<b>Occult Bleed Vigilance:</b> If HCT drops or fails to rise despite ongoing shock, consider occult bleed and transfuse Packed Red Blood Cells (Algorithm 4).');
        items += li('text-rose-600','alert-octagon','<b>Transfer Call:</b> Notify receiving hospital prior to transfer. Send clerking sheet, fluid record, and POCT investigations.');
      } else if (hasWS) {
        card.className = "bg-amber-50 border-2 border-amber-500 rounded-2xl p-4 sm:p-5 shadow-sm transition-all duration-300 text-center sm:text-left";
        badge.className = bd + " bg-amber-600";
        badge.innerText = "Group B2 • Dengue with Warning Signs";
        mts.className = "text-xs font-semibold text-amber-800";
        mts.innerText = "MTS Level 3 (Yellow Zone Urgent)";
        title.innerText = "Dengue with Warning Signs (Critical Phase Risk)";
        desc.innerText = "Warning signs present. Mandatory hospital admission for monitored fluid therapy (CPG Table 6: Group B2 = admit).";
        items += li('text-amber-600','arrow-right-circle','<b>Hospital Admission:</b> Stabilise, establish IV access, and notify receiving medical team. If oral intake poor, IV crystalloid 3-5 ml/kg/hr for 1-2 hours.','text-amber-900 font-semibold');
        items += li('text-amber-600','arrow-right-circle','<b>48h Fluid Quota:</b> Keep total fluid within Maintenance + 5% deficit over 48 hours. Weight capped strictly at 70 kg. See Fluid tab.');
        items += li('text-amber-600','arrow-right-circle','<b>Serial Monitoring:</b> FBC every 4 to 12 hours depending on haemodynamic progression. If shock occurs, escalate to Group C.');
      } else if (hasComorb) {
        card.className = "bg-sky-50 border-2 border-sky-500 rounded-2xl p-4 sm:p-5 shadow-sm transition-all duration-300 text-center sm:text-left";
        badge.className = bd + " bg-sky-600";
        badge.innerText = "Group B1 • Comorbidity / Special Risk";
        mts.className = "text-xs font-semibold text-sky-800";
        mts.innerText = "MTS Level 4 (Green Zone Observation)";
        title.innerText = "Dengue without Warning Signs with Comorbidity or Social Risk";
        desc.innerText = "No acute plasma leakage at present, but high vulnerability due to physiological risk. Consider referral for close observation and management of comorbidities (CPG Table 6).";
        items += li('text-sky-600','info', preg ? '<b>Pregnancy:</b> All pregnant patients with confirmed dengue must be hospitalised under multidisciplinary care.' : '<b>Referral / Admission:</b> Low threshold for admission. Refer if comorbidities are unstable (poor glycemic control, heart failure, CKD) or social factors preclude daily review.','text-sky-900 font-semibold');
        items += li('text-sky-600','info','<b>Individualised Fluid:</b> In heart failure, ESKD, or cirrhosis, fluid regimens must be strictly individualised to avoid fluid overload.');
        items += li('text-sky-600','info','<b>If Managed Outpatient:</b> Daily review with primary care clerking sheet, CCTVR, and FBC until afebrile for 24-48 hours without antipyretics.');
      } else {
        card.className = "bg-emerald-50 border-2 border-emerald-500 rounded-2xl p-4 sm:p-5 shadow-sm transition-all duration-300 text-center sm:text-left";
        badge.className = bd + " bg-emerald-600";
        badge.innerText = "Group A • Outpatient Monitoring";
        mts.className = "text-xs font-semibold text-emerald-800";
        mts.innerText = "MTS Level 5 (Outpatient)";
        title.innerText = "Uncomplicated Dengue (Without Warning Signs)";
        desc.innerText = "Patient is stable with good oral hydration tolerance and no signs of microvascular plasma leakage. Suitable for outpatient primary care management with daily review.";
        items += li('text-emerald-600','check-circle-2','<b>Oral Hydration:</b> 200 ml/hr while awake (ORS, fruit juice, soup, coconut water). Avoid plain water alone. Maintain urine voiding 4-6 times daily.');
        items += li('text-emerald-600','check-circle-2','<b>Daily Primary Care Follow-up:</b> Daily clinical exam, CCTVR, and FBC until afebrile for 24-48 hours without antipyretics. Update monitoring record each visit.');
        items += li('text-emerald-600','check-circle-2','<b>Home Care Advice:</b> Issue Appendix 5B take-home sheet and educate patient/family on red flag warning triggers.');
      }
      
      $('action-list').innerHTML = items + notes.join('');
      
      const mr = $('m-result');
      if (mr) {
        const bgc = (badge.className.match(/bg-\w+-600/) || ['bg-slate-600'])[0];
        mr.className = 'w-full max-w-sm rounded-2xl px-4 py-3 text-sm font-bold text-white shadow-xl flex items-center justify-between active:scale-95 transition-transform backdrop-blur-md ' + bgc;
        $('m-result-text').innerText = badge.innerText.trim();
      }
      lucide.createIcons();
    }

    function interpretSerology() {
      const day = parseInt($('in-day').value) || 3;
      const ns1 = $('rdt-ns1').value, igm = $('rdt-igm').value, igg = $('rdt-igg').value;
      const box = $('serology-result-box');
      const set = (html, c) => { box.innerHTML = html; box.className = 'p-2.5 border rounded-xl text-xs leading-snug ' + c; };
      const sec = igg === 'pos' ? ' IgG positive: indicates possible secondary infection (IgG rises rapidly in secondary dengue).' : '';
      
      if (ns1 === 'pos') set('<b>NS1 positive: supports acute dengue.</b> RDT specificity is ~92% (sensitivity ~77-79%). Notify District Health Office (PKD) within 24h.' + sec, 'bg-emerald-50 border-emerald-200 text-emerald-900');
      else if (igm === 'pos') set('<b>IgM positive: probable recent or acute dengue.</b> IgM typically appears around day 3-5 and persists for weeks. Correlate clinically.' + sec, 'bg-sky-50 border-sky-200 text-sky-900');
      else if (igg === 'pos') set('<b>IgG only positive:</b> Past exposure or secondary dengue where NS1 has cleared. Request high-titre IgG ELISA or RT-PCR if in early febrile illness.', 'bg-purple-50 border-purple-200 text-purple-900');
      else if (day <= 5) set('<b>NS1 and IgM negative: does not exclude dengue.</b> Rapid test sensitivity is limited early on. Repeat RDT in 24-48h or send RT-PCR/NS1 ELISA if clinical suspicion remains high.', 'bg-slate-100 border-slate-300 text-slate-800');
      else set('<b>All markers negative beyond day 5:</b> Dengue is less likely. Actively investigate differentials (leptospirosis, malaria, typhoid, viral hepatitis).', 'bg-slate-100 border-slate-300 text-slate-800');
    }

    function calcFluidQuota() {
      const actWt = parseFloat($('fq-weight').value) || 60;
      const ht = parseFloat($('fq-height').value) || 165;
      const gender = $('fq-gender').value;
      const bmi = actWt / ((ht / 100) * (ht / 100));
      let w = actWt, abw = false;
      
      if (bmi > 27.5) {
        const ibw = (gender === 'male') ? 50.0 + 0.91 * (ht - 152) : 45.5 + 0.91 * (ht - 152);
        w = ibw + 0.4 * (actWt - ibw); 
        abw = true;
      }
      
      const capped = w > 70;
      if (capped) w = 70;
      
      // Holliday-Segar Maintenance Formula (CPG Table 11)
      const hourly = w <= 10 ? w * 4 : w <= 20 ? 40 + (w - 10) * 2 : 60 + (w - 20);
      const maintDay = hourly * 24;
      const deficit = w * 50; // 5% Deficit is 50 ml/kg
      const total = maintDay + deficit;
      const f = (n) => Math.round(n).toLocaleString();
      
      $('fq-calc-wt').innerText = `${w.toFixed(1)} kg ${abw ? '(ABW)' : ''}${capped ? ' (70 kg cap)' : ''}`;
      $('fq-maint-day').innerText = `${f(maintDay)} ml`;
      $('fq-maint-48').innerText = `${f(hourly)} ml/hr`;
      $('fq-deficit').innerText = `${f(deficit)} ml`;
      $('fq-total-quota').innerText = `${f(total)} ml`;
      $('fq-note').innerText = 'Formula: Maintenance (Holliday-Segar 1-day) + 5% Deficit (50ml/kg) administered over 48 hours. Rounded values in Algorithm 1 list 5,700 ml at 65 kg and 6,000 ml at ≥70 kg.';
      
      [['r5',5],['r3',3],['r2',2],['r1',1],['r7',7]].forEach(([id, k]) => $('rate-' + id).innerText = `${Math.round(w * k)} ml/hr`);
    }

    function resetTriageForm() {
      const v = { 'in-age': 32, 'in-weight': 65, 'in-day': 4, 'in-sbp': 114, 'in-dbp': 78, 'in-hr': 84, 'in-crt': 'normal', 'in-temp': 'warm', 'in-pulsevol': 'good', 'in-urine': 'adequate', 'in-hct': 42, 'in-plt': 128, 'in-wcc': 3.4, 'in-ast': 48, 'in-fever': 'febrile', 'in-pid': '' };
      Object.keys(v).forEach(k => $(k).value = v[k]);
      document.querySelectorAll('#tab-content-triage input[type=checkbox]').forEach(c => c.checked = false);
      runEvaluation(); 
      interpretSerology();
    }

    // Theme Engine
    function toggleTheme() {
      const dark = !document.documentElement.classList.contains('dark');
      document.documentElement.classList.toggle('dark', dark);
      try { localStorage.setItem('dengue-theme', dark ? 'dark' : 'light'); } catch (e) {}
      syncThemeBtn();
    }
    
    function syncThemeBtn() {
      const dark = document.documentElement.classList.contains('dark');
      $('theme-btn').innerHTML = `<i data-lucide="${dark ? 'sun' : 'moon'}" class="w-4 h-4"></i> <span>${dark ? 'Light' : 'Dark'}</span>`;
      lucide.createIcons();
    }

    // PDF Generator
    const clean = (t) => String(t).replace(/≥/g, '>=').replace(/≤/g, '<=').replace(/[–—]/g, '-').replace(/×/g, 'x').replace(/⁹/g, '^9').replace(/[•●]/g, '-').replace(/[‘’]/g, "'").replace(/[“”]/g, '"').replace(/\s+/g, ' ').trim();
    
    function pdfWriter() {
      const d = new window.jspdf.jsPDF({ unit: 'pt', format: 'a4' });
      let y = 56; const L = 48, W = 499;
      const need = (n) => { if (y + n > 790) { d.addPage(); y = 56; } };
      return {
        d,
        h(t, s = 12) { need(s + 14); y += 6; d.setFont('helvetica', 'bold'); d.setFontSize(s); d.setTextColor(20, 83, 45); d.text(clean(t), L, y); y += s + 4; },
        p(t, o = {}) {
          d.setFont('helvetica', o.b ? 'bold' : 'normal'); d.setFontSize(o.s || 10); d.setTextColor(...(o.c || [40, 40, 40]));
          d.splitTextToSize(clean(t), W - (o.i || 0)).forEach(l => { need(14); d.text(l, L + (o.i || 0), y); y += (o.s || 10) + 3; });
          y += o.g == null ? 3 : o.g;
        }
      };
    }

    async function savePdf(doc, name) {
      const blob = doc.output('blob');
      const a = document.createElement('a'); 
      a.href = URL.createObjectURL(blob); 
      a.download = name; 
      a.click();
    }
    
    const today = () => new Date().toISOString().slice(0, 10);
    const optText = (id) => $(id).selectedOptions[0].text;
    const ticked = (prefix) => [...document.querySelectorAll(`#tab-content-triage input[id^="${prefix}"]:checked`)].map(c => c.parentElement.innerText);

    async function saveAssessmentPdf() {
      if (!window.jspdf) { window.print(); return; }
      runEvaluation(); 
      calcFluidQuota();
      const w = pdfWriter();
      w.p('Dengue Primary Care Assessment', { b: 1, s: 17, g: 4 });
      w.p(`Generated: ${new Date().toLocaleString('en-GB')}    Patient: ${$('in-pid').value.trim() || '-'}`, { c: [90, 90, 90] });
      w.p('Based on MOH Malaysia CPG: Management of Dengue in Adults (4th Edition, 2026). Decision-support aid only.', { s: 8, c: [110, 110, 110], g: 6 });
      w.h('Clinical Classification');
      w.p($('disposition-badge').innerText, { b: 1, s: 12 });
      w.p(`${$('mts-level').innerText} - ${$('disposition-title').innerText}`, { b: 1 });
      w.p($('disposition-desc').innerText, { g: 4 });
      w.h('Primary Care Action Protocol');
      document.querySelectorAll('#action-list li').forEach(l => w.p('- ' + l.innerText, { i: 8 }));
      w.h('Clinical Data');
      w.p(`Age ${$('in-age').value} y, ${$('in-gender').value}, weight ${$('in-weight').value} kg, day ${$('in-day').value} of illness. Phase: ${optText('in-fever')}.`);
      w.p(`BP ${$('in-sbp').value}/${$('in-dbp').value} mmHg, pulse ${$('in-hr').value}/min, CRT: ${optText('in-crt')}, peripheries: ${optText('in-temp')}, pulse volume: ${optText('in-pulsevol')}, urine: ${optText('in-urine')}.`);
      w.p(`HCT ${$('in-hct').value}%, platelets ${$('in-plt').value} x10^9/L, WCC ${$('in-wcc').value} x10^9/L, AST/ALT ${$('in-ast').value} U/L.`);
      w.p(`Pulse pressure ${$('calc-pp').innerText}, MAP ${$('calc-map').innerText}, HCT deviation ${$('calc-hct-diff').innerText}.`);
      const ws = ticked('ws-'), sv = ticked('sv-'), co = ticked('co-');
      w.p('Warning signs: ' + (ws.length ? ws.join('; ') : 'None documented'));
      w.p('Severe features: ' + (sv.length ? sv.join('; ') : 'None documented'));
      w.p('Comorbidities / Risk: ' + (co.length ? co.join('; ') : 'None documented'));
      w.h('Rapid Diagnostic Test (RDT)');
      w.p(`NS1: ${optText('rdt-ns1')}, IgM: ${optText('rdt-igm')}, IgG: ${optText('rdt-igg')}.`);
      w.p($('serology-result-box').innerText);
      w.h('48h Fluid Quota (Critical Phase Without Shock)');
      w.p(`Weight used: ${$('fq-calc-wt').innerText}. 1-day maintenance ${$('fq-maint-day').innerText} + 5% deficit ${$('fq-deficit').innerText} = ${$('fq-total-quota').innerText} over 48 hours.`);
      w.p('Disease Notification: Notify District Health Office (PKD) within 24h of diagnosis (Act 342).', { b: 1 });
      await savePdf(w.d, `dengue-assessment-${today()}.pdf`);
    }

    async function saveLeafletPdf() {
      if (!window.jspdf) { printLeaflet(); return; }
      const w = pdfWriter();
      w.p('Dengue Home Care & Warning Signs Leaflet', { b: 1, s: 17, g: 6 });
      document.querySelectorAll('#tab-content-homecare .grid > div').forEach(col => {
        col.querySelectorAll('h3,h4,li,p').forEach(el => {
          const t = el.tagName;
          if (t === 'H3') w.h(el.innerText, 13);
          else if (t === 'H4') w.p(el.innerText, { b: 1, g: 1 });
          else if (t === 'LI') w.p('- ' + el.innerText, { i: 8, g: 1 });
          else w.p(el.innerText, { s: 9, c: [90, 90, 90], g: 3 });
        });
      });
      await savePdf(w.d, `dengue-home-care-leaflet-${today()}.pdf`);
    }

    function printLeaflet() {
      document.body.classList.add('print-leaflet');
      window.print();
      setTimeout(() => document.body.classList.remove('print-leaflet'), 500);
    }

    window.addEventListener('DOMContentLoaded', () => {
      syncThemeBtn();
      lucide.createIcons();
      runEvaluation();
      interpretSerology();
      calcFluidQuota();
    });
  </script>
</body>
</html>
