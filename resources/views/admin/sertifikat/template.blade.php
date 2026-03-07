{{-- resources/views/admin/sertifikat/template.blade.php (PDF landscape) --}}
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sertifikat Magang</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Cormorant+SC:wght@500;600;700&family=EB+Garamond:ital,wght@0,400;1,400&display=swap" rel="stylesheet">
<style>
  :root {
    --blue-deep:    #1A3A6E;
    --blue-mid:     #2563EB;
    --blue-bright:  #3B82F6;
    --blue-sky:     #60A5FA;
    --blue-pale:    #BAD8FF;
    --cyan:         #7DD3FC;
    --cyan-light:   #BAE6FD;
    --white:        #FFFFFF;
    --paper:        #F4F9FF;
    --paper-warm:   #EEF5FF;
    --text-dark:    #0F2A5A;
    --text-mid:     #2A4A80;
    --text-light:   #6B8FC0;
  }

  * { margin:0; padding:0; box-sizing:border-box; }

  @page {
    size: A4 landscape;
    margin: 10mm;
  }

  body {
    background: linear-gradient(160deg, #C8DEFF 0%, #DAEEFF 40%, #B8D4F8 100%);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    padding: 40px 20px;
    font-family: 'EB Garamond', Georgia, serif;
  }

  .certificate {
    width: 900px;
    min-height: 640px;
    position: relative;
    overflow: hidden;
    background: white;
    box-shadow:
      0 0 0 1px #93C5FD,
      0 0 0 4px rgba(37,99,235,0.12),
      0 12px 60px rgba(37,99,235,0.18),
      0 40px 100px rgba(59,130,246,0.10);
    border-radius: 3px;
  }

  .bg-main {
    position: absolute;
    inset: 0;
    background:
      radial-gradient(ellipse 90% 70% at 50% 0%,   #EBF4FF 0%, #DBEAFE 50%, #F0F8FF 100%),
      radial-gradient(ellipse 60% 40% at 15% 90%,  #DBEAFE 0%, transparent 70%),
      radial-gradient(ellipse 50% 35% at 85% 85%,  #E0F2FE 0%, transparent 70%);
    z-index: 0;
  }

  .bg-wave {
    position: absolute;
    inset: 0;
    opacity: 0.06;
    background-image: repeating-linear-gradient(
      -30deg,
      transparent,
      transparent 28px,
      #2563EB 28px,
      #2563EB 30px
    );
    z-index: 1;
  }

  .top-bar {
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 8px;
    background: linear-gradient(90deg,
      #BFDBFE 0%,
      #3B82F6 20%,
      #2563EB 40%,
      #60A5FA 55%,
      #BAE6FD 65%,
      #60A5FA 75%,
      #2563EB 85%,
      #3B82F6 92%,
      #BFDBFE 100%
    );
    z-index: 10;
  }
  .bottom-bar {
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 6px;
    background: linear-gradient(90deg,
      #BFDBFE 0%, #3B82F6 20%, #2563EB 40%, #60A5FA 55%,
      #BAE6FD 65%, #60A5FA 75%, #2563EB 85%, #BFDBFE 100%
    );
    z-index: 10;
  }

  .frame-outer {
    position: absolute;
    inset: 16px;
    border: 1.8px solid #93C5FD;
    z-index: 2;
    pointer-events: none;
  }
  .frame-inner {
    position: absolute;
    inset: 20px;
    border: 0.6px solid #BFDBFE;
    z-index: 2;
    pointer-events: none;
  }

  .corner { position:absolute; width:110px; height:110px; z-index:3; }
  .corner-tl { top:0;    left:0; }
  .corner-tr { top:0;    right:0;  transform:scaleX(-1); }
  .corner-bl { bottom:0; left:0;   transform:scaleY(-1); }
  .corner-br { bottom:0; right:0;  transform:scale(-1); }

  .content {
    position: relative;
    z-index: 5;
    padding: 38px 68px 36px;
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .institution-header {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
    margin-bottom: 14px;
  }
  .institution-name {
    font-family: 'Cinzel', serif;
    font-size: 16px;
    font-weight: 700;
    letter-spacing: 4px;
    color: var(--blue-deep);
    text-transform: uppercase;
    font-style: bold;
  }
  .institution-sub {
    font-family: 'EB Garamond', serif;
    font-size: 11px;
    letter-spacing: 2px;
    color: var(--text-light);
    font-style: italic;
  }

  .orn-div {
    width: 100%;
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 2px 0 14px;
  }
  .orn-div .line {
    flex: 1;
    height: 1px;
    background: linear-gradient(to right, transparent, #60A5FA, #BAE6FD, #60A5FA, transparent);
  }
  .orn-div .dia { color: #3B82F6; font-size: 10px; }

  .cert-label {
    font-family: 'Cinzel', serif;
    font-size: 9px;
    letter-spacing: 7px;
    color: var(--blue-sky);
    margin-bottom: 4px;
  }
  .cert-title {
    font-family: 'Cinzel', serif;
    font-size: 36px;
    font-weight: 700;
    letter-spacing: 8px;
    text-align: center;
    line-height: 1;
    color: var(--blue-deep);
  }
  .cert-title span {
    background: linear-gradient(135deg, #1A3A6E 0%, #2563EB 50%, #60A5FA 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }
  .cert-title-en {
    font-family: 'Cormorant Garamond', serif;
    font-size: 11.5px;
    font-style: italic;
    color: var(--text-light);
    letter-spacing: 3px;
    margin-top: 4px;
  }
  .title-rule {
    width: 320px;
    height: 2px;
    background: linear-gradient(90deg,
      transparent, #BFDBFE, #60A5FA, #BAE6FD, #60A5FA, #BFDBFE, transparent
    );
    margin: 12px 0 18px;
    position: relative;
  }
  .title-rule::before, .title-rule::after {
    content: '◆';
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    color: #60A5FA;
    font-size: 7px;
  }
  .title-rule::before { left: 55px; }
  .title-rule::after  { right: 55px; }

  .intro {
    font-family: 'Cormorant Garamond', serif;
    font-size: 15px;
    font-style: italic;
    color: var(--text-mid);
    margin-bottom: 8px;
  }

  .recipient-name {
    font-family: 'Cormorant Garamond', serif;
    font-size: 50px;
    font-weight: 600;
    font-style: italic;
    line-height: 1.1;
    letter-spacing: 1px;
    color: var(--blue-deep);
    text-align: center;
  }
  .recipient-nim {
    font-family: 'Cormorant SC', serif;
    font-size: 11px;
    letter-spacing: 4px;
    color: var(--text-light);
    margin-top: 2px;
  }

  .name-flourish { width: 360px; height: 18px; margin: 3px auto 16px; }

  .details-section {
    width: 100%;
    max-width: 620px;
    display: flex;
    flex-direction: column;
    gap: 7px;
    margin-bottom: 18px;
  }
  .detail-row {
    display: grid;
    grid-template-columns: 195px 20px 1fr;
    align-items: baseline;
  }
  .detail-label {
    font-family: 'Cormorant Garamond', serif;
    font-size: 13px;
    font-style: italic;
    color: var(--text-light);
    text-align: right;
    padding-right: 8px;
  }
  .detail-sep  { color: var(--blue-bright); font-size: 10px; text-align: center; }
  .detail-value {
    font-family: 'Cormorant Garamond', serif;
    font-size: 14.5px;
    font-weight: 600;
    color: var(--text-dark);
    padding-left: 8px;
  }
  .grade-badge { display:inline-flex; align-items:center; gap:8px; }
  .grade-pill {
    background: linear-gradient(135deg, #1A3A6E, #2563EB);
    color: #BAE6FD;
    font-family: 'Cinzel', serif;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 2px;
    padding: 2px 10px;
    border: 1px solid #93C5FD;
  }

  .sec-div {
    width: 100%;
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 2px 0 16px;
  }
  .sec-div .sl { flex:1; height:0.5px; background:linear-gradient(to right, transparent, rgba(96,165,250,0.3), transparent); }
  .sec-div .ss { color:#93C5FD; font-size:7px; }

  .sig-row {
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    gap: 20px;
  }
  .sig-block {
    display: flex;
    flex-direction: column;
    align-items: center;
    flex: 1;
    max-width: 200px;
  }
  .sig-space {
    height: 46px;
    width: 100%;
    display: flex;
    align-items: flex-end;
    justify-content: center;
    margin-bottom: 6px;
  }
  .sig-line {
    width: 100%;
    height: 1px;
    background: linear-gradient(to right, transparent, #93C5FD, transparent);
  }
  .sig-name  { font-family:'Cormorant SC',serif; font-size:11.5px; font-weight:600; color:var(--blue-deep); letter-spacing:1px; text-align:center; }
  .sig-title { font-family:'EB Garamond',serif; font-size:9.5px; font-style:italic; color:var(--text-light); text-align:center; margin-top:1px; }

  .wax-seal { flex-shrink:0; display:flex; flex-direction:column; align-items:center; gap:6px; }
  .seal-date { font-family:'Cormorant SC',serif; font-size:9px; color:var(--text-light); letter-spacing:1.5px; text-align:center; }

  .footnote {
    margin-top: 14px;
    font-family: 'EB Garamond', serif;
    font-size: 9px;
    font-style: italic;
    color: var(--text-light);
    text-align: center;
    letter-spacing: 1px;
    opacity: 0.7;
  }
  .serial-no {
    position:absolute; bottom:20px; right:32px;
    font-family:'Arial',sans-serif; font-size:10px; font-weight:bold;
    letter-spacing:2px; color:var(--blue-deep); opacity:0.8; z-index:6;
  }
</style>
</head>
<body>

<div class="certificate">
  <div class="bg-main"></div>
  <div class="bg-wave"></div>
  <div class="top-bar"></div>
  <div class="bottom-bar"></div>
  <div class="frame-outer"></div>
  <div class="frame-inner"></div>

  <!-- Corners -->
  <div class="corner corner-tl">
    <svg viewBox="0 0 110 110" fill="none" xmlns="http://www.w3.org/2000/svg">
      <defs>
        <linearGradient id="cg" x1="0" y1="0" x2="110" y2="110" gradientUnits="userSpaceOnUse">
          <stop offset="0%"  stop-color="#60A5FA"/>
          <stop offset="60%" stop-color="#93C5FD"/>
          <stop offset="100%" stop-color="#BFDBFE" stop-opacity="0"/>
        </linearGradient>
        <linearGradient id="cf" x1="0" y1="0" x2="60" y2="60" gradientUnits="userSpaceOnUse">
          <stop offset="0%" stop-color="#DBEAFE" stop-opacity="0.5"/>
          <stop offset="100%" stop-color="transparent"/>
        </linearGradient>
      </defs>
      <path d="M0 85 Q0 0 85 0"  stroke="url(#cg)" stroke-width="0.8" fill="none"/>
      <path d="M0 65 Q0 0 65 0"  stroke="url(#cg)" stroke-width="0.5" fill="none" opacity="0.6"/>
      <path d="M0 45 Q0 0 45 0"  stroke="url(#cg)" stroke-width="0.4" fill="none" opacity="0.35"/>
      <line x1="0" y1="0" x2="52" y2="0"  stroke="#3B82F6" stroke-width="2.2"/>
      <line x1="0" y1="0" x2="0"  y2="52" stroke="#3B82F6" stroke-width="2.2"/>
      <line x1="0" y1="0" x2="26" y2="0"  stroke="#93C5FD" stroke-width="1"/>
      <line x1="0" y1="0" x2="0"  y2="26" stroke="#93C5FD" stroke-width="1"/>
      <line x1="0" y1="0" x2="36" y2="36" stroke="#60A5FA" stroke-width="0.5" opacity="0.5"/>
      <line x1="0" y1="0" x2="18" y2="48" stroke="#60A5FA" stroke-width="0.4" opacity="0.3"/>
      <line x1="0" y1="0" x2="48" y2="18" stroke="#60A5FA" stroke-width="0.4" opacity="0.3"/>
      <polygon points="12,0 17,5 12,10 7,5"  fill="#60A5FA" opacity="0.9"/>
      <polygon points="0,12 5,17 0,22 -5,17" fill="#60A5FA" opacity="0.9"/>
      <polygon points="0,0 38,0 0,38" fill="url(#cf)"/>
    </svg>
  </div>
  <div class="corner corner-tr">
    <svg viewBox="0 0 110 110" fill="none" xmlns="http://www.w3.org/2000/svg">
      <defs>
        <linearGradient id="cg2" x1="0" y1="0" x2="110" y2="110" gradientUnits="userSpaceOnUse">
          <stop offset="0%"  stop-color="#60A5FA"/>
          <stop offset="100%" stop-color="#BFDBFE" stop-opacity="0"/>
        </linearGradient>
      </defs>
      <path d="M0 85 Q0 0 85 0" stroke="url(#cg2)" stroke-width="0.8" fill="none"/>
      <path d="M0 65 Q0 0 65 0" stroke="url(#cg2)" stroke-width="0.5" fill="none" opacity="0.6"/>
      <line x1="0" y1="0" x2="52" y2="0" stroke="#3B82F6" stroke-width="2.2"/>
      <line x1="0" y1="0" x2="0"  y2="52" stroke="#3B82F6" stroke-width="2.2"/>
      <line x1="0" y1="0" x2="26" y2="0" stroke="#93C5FD" stroke-width="1"/>
      <line x1="0" y1="0" x2="0"  y2="26" stroke="#93C5FD" stroke-width="1"/>
      <line x1="0" y1="0" x2="36" y2="36" stroke="#60A5FA" stroke-width="0.5" opacity="0.5"/>
      <polygon points="12,0 17,5 12,10 7,5" fill="#60A5FA" opacity="0.9"/>
      <polygon points="0,12 5,17 0,22 -5,17" fill="#60A5FA" opacity="0.9"/>
    </svg>
  </div>
  <div class="corner corner-bl">
    <svg viewBox="0 0 110 110" fill="none" xmlns="http://www.w3.org/2000/svg">
      <defs>
        <linearGradient id="cg3" x1="0" y1="0" x2="110" y2="110" gradientUnits="userSpaceOnUse">
          <stop offset="0%"  stop-color="#60A5FA"/>
          <stop offset="100%" stop-color="#BFDBFE" stop-opacity="0"/>
        </linearGradient>
      </defs>
      <path d="M0 85 Q0 0 85 0" stroke="url(#cg3)" stroke-width="0.8" fill="none"/>
      <path d="M0 65 Q0 0 65 0" stroke="url(#cg3)" stroke-width="0.5" fill="none" opacity="0.6"/>
      <line x1="0" y1="0" x2="52" y2="0" stroke="#3B82F6" stroke-width="2.2"/>
      <line x1="0" y1="0" x2="0"  y2="52" stroke="#3B82F6" stroke-width="2.2"/>
      <line x1="0" y1="0" x2="26" y2="0" stroke="#93C5FD" stroke-width="1"/>
      <line x1="0" y1="0" x2="0"  y2="26" stroke="#93C5FD" stroke-width="1"/>
      <line x1="0" y1="0" x2="36" y2="36" stroke="#60A5FA" stroke-width="0.5" opacity="0.5"/>
      <polygon points="12,0 17,5 12,10 7,5" fill="#60A5FA" opacity="0.9"/>
      <polygon points="0,12 5,17 0,22 -5,17" fill="#60A5FA" opacity="0.9"/>
    </svg>
  </div>
  <div class="corner corner-br">
    <svg viewBox="0 0 110 110" fill="none" xmlns="http://www.w3.org/2000/svg">
      <defs>
        <linearGradient id="cg4" x1="0" y1="0" x2="110" y2="110" gradientUnits="userSpaceOnUse">
          <stop offset="0%"  stop-color="#60A5FA"/>
          <stop offset="100%" stop-color="#BFDBFE" stop-opacity="0"/>
        </linearGradient>
      </defs>
      <path d="M0 85 Q0 0 85 0" stroke="url(#cg4)" stroke-width="0.8" fill="none"/>
      <path d="M0 65 Q0 0 65 0" stroke="url(#cg4)" stroke-width="0.5" fill="none" opacity="0.6"/>
      <line x1="0" y1="0" x2="52" y2="0" stroke="#3B82F6" stroke-width="2.2"/>
      <line x1="0" y1="0" x2="0"  y2="52" stroke="#3B82F6" stroke-width="2.2"/>
      <line x1="0" y1="0" x2="26" y2="0" stroke="#93C5FD" stroke-width="1"/>
      <line x1="0" y1="0" x2="0"  y2="26" stroke="#93C5FD" stroke-width="1"/>
      <line x1="0" y1="0" x2="36" y2="36" stroke="#60A5FA" stroke-width="0.5" opacity="0.5"/>
      <polygon points="12,0 17,5 12,10 7,5" fill="#60A5FA" opacity="0.9"/>
      <polygon points="0,12 5,17 0,22 -5,17" fill="#60A5FA" opacity="0.9"/>
    </svg>
  </div>

  @php
    // Indonesian month names
    $bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    
    function formatTanggalIndonesia($date) {
        global $bulan;
        if (!$date) return '-';
        $day = $date->format('d');
        $month = (int) $date->format('n');
        $year = $date->format('Y');
        return $day . ' ' . ($bulan[$month] ?? 'Januari') . ' ' . $year;
    }
@endphp

<div class="serial-no">No: {{ $sertifikat->nomor_sertifikat }}</div>

  <!-- CONTENT -->
  <div class="content">

    <div class="institution-header">
      <div style="display:flex; align-items:center; gap:40px; margin-bottom:16px;">
        @if(!empty($settings['logo']))
        <img src="{{ asset('storage/' . $settings['logo']) }}" alt="Logo Universitas" style="height:100px;object-fit:contain;">
        @else
        <!-- Default Emblem -->
        <svg width="58" height="58" viewBox="0 0 58 58" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-bottom:8px;">
          <defs>
            <radialGradient id="embG" cx="50%" cy="35%" r="65%">
              <stop offset="0%"   stop-color="#60A5FA"/>
              <stop offset="100%" stop-color="#1D4ED8"/>
            </radialGradient>
            <linearGradient id="ringG" x1="0" y1="0" x2="1" y2="1">
              <stop offset="0%"   stop-color="#93C5FD"/>
              <stop offset="50%"  stop-color="#BFDBFE"/>
              <stop offset="100%" stop-color="#60A5FA"/>
            </linearGradient>
          </defs>
          <circle cx="29" cy="29" r="27" stroke="url(#ringG)" stroke-width="1.8" fill="none"/>
          <circle cx="29" cy="29" r="23.5" stroke="rgba(96,165,250,0.4)" stroke-width="0.6" fill="none"/>
          <circle cx="29" cy="29" r="22" fill="url(#embG)"/>
          <polygon points="29,7  31,21 29,19 27,21" fill="white" opacity="0.9"/>
          <polygon points="29,51 31,37 29,39 27,37" fill="white" opacity="0.9"/>
          <polygon points="7,29  21,31 19,29 21,27" fill="white" opacity="0.9"/>
          <polygon points="51,29 37,31 39,29 37,27" fill="white" opacity="0.9"/>
          <text x="29" y="35" text-anchor="middle" font-family="Arial,serif" font-size="15" font-weight="700" fill="white">{{ substr($settings['university_name'] ?? 'U', 0, 1) }}</text>
          <circle cx="29" cy="3"  r="1.5" fill="#93C5FD"/>
          <circle cx="29" cy="55" r="1.5" fill="#93C5FD"/>
          <circle cx="3"  cy="29" r="1.5" fill="#93C5FD"/>
          <circle cx="55" cy="29" r="1.5" fill="#93C5FD"/>
        </svg>
        @endif
        
        @if(!empty($pengajuan->mitra->logo))
        <div style="display:flex; flex-direction:column; align-items:center;">
          <img src="{{ asset('storage/' . $pengajuan->mitra->logo) }}" alt="Logo Mitra" style="height:90px;object-fit:contain;">
        </div>
        @endif
      </div>
      
    </div>

    <!-- <div class="orn-div">
      <div class="line"></div>
      <span class="dia">◆</span>
      <span class="dia" style="font-size:7px;opacity:0.5">◆</span>
      <span class="dia">◆</span>
      <div class="line"></div>
    </div> -->

    <div class="cert-label">Dengan Bangga Mempersembahkan</div>
    <div class="cert-title"><span>SERTIFIKAT MAGANG</span></div>
    <div class="cert-title-en">Certificate of Internship Completion</div>
    <div class="title-rule"></div>

    <p class="intro">Yang bertanda tangan di bawah ini menerangkan dengan sesungguhnya bahwa:</p>

    <div class="recipient-name">{{ $pengajuan->mahasiswa->nama_lengkap }}</div>
    <div class="recipient-nim">NIM &nbsp;·&nbsp; {{ $pengajuan->mahasiswa->nim }} &nbsp;·&nbsp; {{ $pengajuan->mahasiswa->program_studi }} — Angkatan {{ substr($pengajuan->mahasiswa->nim, 0, 4) }}</div>

    <svg class="name-flourish" viewBox="0 0 360 18" fill="none" xmlns="http://www.w3.org/2000/svg">
      <defs>
        <linearGradient id="fl1" x1="0" y1="0" x2="130" y2="0" gradientUnits="userSpaceOnUse">
          <stop offset="0%"   stop-color="transparent"/>
          <stop offset="100%" stop-color="#3B82F6"/>
        </linearGradient>
        <linearGradient id="fl2" x1="230" y1="0" x2="360" y2="0" gradientUnits="userSpaceOnUse">
          <stop offset="0%"   stop-color="#3B82F6"/>
          <stop offset="100%" stop-color="transparent"/>
        </linearGradient>
      </defs>
      <line x1="0"   y1="9" x2="130" y2="9" stroke="url(#fl1)" stroke-width="1"/>
      <polygon points="162,9 167,4 175,9 167,14" fill="#2563EB"/>
      <polygon points="173,9 177,5 183,9 177,13" fill="#60A5FA" opacity="0.8"/>
      <polygon points="184,9 188,5.5 192,9 188,12.5" fill="#BAE6FD" opacity="0.6"/>
      <line x1="230" y1="9" x2="360" y2="9" stroke="url(#fl2)" stroke-width="1"/>
    </svg>

    <div class="details-section">
      <div class="detail-row">
        <span class="detail-label">Telah menyelesaikan magang di</span>
        <span class="detail-sep">◆</span>
        <span class="detail-value" style="color:var(--blue-deep);font-size:15px;">{{ $pengajuan->mitra->nama_perusahaan }}</span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Bidang kerja</span>
        <span class="detail-sep">◆</span>
        <span class="detail-value">{{ $pengajuan->bidang_kerja }}</span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Periode magang</span>
        <span class="detail-sep">◆</span>
        <span class="detail-value">{{ formatTanggalIndonesia($pengajuan->tanggal_mulai) }} — {{ formatTanggalIndonesia($pengajuan->tanggal_selesai) }}</span>
      </div>
      @if($pengajuan->penilaian?->grade)
      <div class="detail-row">
        <span class="detail-label">Nilai akhir</span>
        <span class="detail-sep">◆</span>
        <span class="detail-value">
          <span class="grade-badge">{{ number_format($pengajuan->penilaian->nilai_akhir, 2) }} / 100 <span class="grade-pill">GRADE {{ $pengajuan->penilaian->grade }}</span></span>
        </span>
      </div>
      @endif
    </div>

    <div class="sec-div">
      <div class="sl"></div>
      <span class="ss">✦</span>
      <span class="ss" style="font-size:5px;opacity:0.5">✦</span>
      <span class="ss">✦</span>
      <div class="sl"></div>
    </div>

    <div class="sig-row">

      <div class="sig-block">
        <div class="sig-space">
          @if(!empty($pengajuan->mitra->tanda_tangan))
          <img src="{{ asset('storage/' . $pengajuan->mitra->tanda_tangan) }}" alt="TTD Mitra" style="height:50px;object-fit:contain;">
          @else
          <svg width="120" height="40" viewBox="0 0 120 40" fill="none" style="opacity:0.25">
            <path d="M8 30 Q28 5 48 20 Q62 30 78 14 Q95 0 114 18" stroke="#1D4ED8" stroke-width="1.5" fill="none" stroke-linecap="round"/>
            <path d="M6 28 Q26 8 46 22 Q58 30 72 16" stroke="#1D4ED8" stroke-width="0.8" fill="none" stroke-linecap="round" opacity="0.5"/>
          </svg>
          @endif
        </div>
        <div class="sig-line"></div>
        <div class="sig-name">{{ $pengajuan->mitra->nama_kontak }}</div>
        <div class="sig-title">Supervisor</div>
        <div class="sig-title">{{ $pengajuan->mitra->nama_perusahaan }}</div>
      </div>

      <!-- Light blue seal -->
      <div class="wax-seal">
        <svg width="82" height="82" viewBox="0 0 82 82" fill="none" xmlns="http://www.w3.org/2000/svg">
          <defs>
            <radialGradient id="sG" cx="50%" cy="35%" r="65%">
              <stop offset="0%"   stop-color="#60A5FA"/>
              <stop offset="100%" stop-color="#1D4ED8"/>
            </radialGradient>
            <linearGradient id="sR" x1="0" y1="0" x2="1" y2="1">
              <stop offset="0%"   stop-color="#BAE6FD"/>
              <stop offset="50%"  stop-color="#60A5FA"/>
              <stop offset="100%" stop-color="#2563EB"/>
            </linearGradient>
          </defs>
          <!-- Starburst rays -->
          <g opacity="0.7">
            <polygon points="41,2  43.5,15 41,13 38.5,15" fill="#60A5FA"/>
            <polygon points="41,80 43.5,67 41,69 38.5,67" fill="#60A5FA"/>
            <polygon points="2,41  15,43.5 13,41 15,38.5" fill="#60A5FA"/>
            <polygon points="80,41 67,43.5 69,41 67,38.5" fill="#60A5FA"/>
            <polygon points="11.4,11.4 21,23 18,23 20,20" fill="#93C5FD" opacity="0.8"/>
            <polygon points="70.6,11.4 61,23 64,23 62,20" fill="#93C5FD" opacity="0.8"/>
            <polygon points="11.4,70.6 21,59 18,59 20,62" fill="#93C5FD" opacity="0.8"/>
            <polygon points="70.6,70.6 61,59 64,59 62,62" fill="#93C5FD" opacity="0.8"/>
            <polygon points="5,24  17,30 15,29 16,26" fill="#BAE6FD" opacity="0.6"/>
            <polygon points="77,24 65,30 67,29 66,26" fill="#BAE6FD" opacity="0.6"/>
            <polygon points="24,5  30,17 29,15 26,16" fill="#BAE6FD" opacity="0.6"/>
            <polygon points="58,5  52,17 53,15 56,16" fill="#BAE6FD" opacity="0.6"/>
            <polygon points="5,58  17,52 15,53 16,56" fill="#BAE6FD" opacity="0.6"/>
            <polygon points="77,58 65,52 67,53 66,56" fill="#BAE6FD" opacity="0.6"/>
            <polygon points="24,77 30,65 29,67 26,66" fill="#BAE6FD" opacity="0.6"/>
            <polygon points="58,77 52,65 53,67 56,66" fill="#BAE6FD" opacity="0.6"/>
          </g>
          <!-- Circle -->
          <circle cx="41" cy="41" r="30" fill="url(#sG)"/>
          <circle cx="41" cy="41" r="30" stroke="url(#sR)" stroke-width="1.5" fill="none"/>
          <circle cx="41" cy="41" r="27" stroke="rgba(186,230,253,0.6)" stroke-width="0.6" fill="none"/>
          <circle cx="41" cy="41" r="22" stroke="rgba(147,197,253,0.3)" stroke-width="0.4" fill="none"/>
          <!-- Text path -->
          <text font-family="Cinzel,serif" font-size="4.5" fill="rgba(219,234,254,0.9)" letter-spacing="2">
            <textPath href="#sp" startOffset="3%"> MAGANG ✦ POLITEKNIK KRAKATAU ✦</textPath>
          </text>
          <defs><path id="sp" d="M41,17 A24,24 0 1,1 40.99,17"/></defs>
          <line x1="27" y1="43" x2="55" y2="43" stroke="rgba(186,230,253,0.5)" stroke-width="0.5"/>
          <text x="41" y="40" text-anchor="middle" font-family="Cinzel,serif" font-size="8.5" font-weight="700" fill="white" letter-spacing="1">LULUS</text>
          <text x="41" y="50" text-anchor="middle" font-family="Cinzel,serif" font-size="6" fill="#BAE6FD">{{ $sertifikat->diterbitkan_at->format('Y') }}</text>
        </svg>
        <div class="seal-date">{{ formatTanggalIndonesia($sertifikat->diterbitkan_at) }}</div>
      </div>

      <div class="sig-block">
        <div class="sig-space">
          @if(!empty($settings['ttd_koordinator']))
          <img src="{{ asset('storage/' . $settings['ttd_koordinator']) }}" alt="TTD" style="height:120px;object-fit:contain;">
          @else
          <svg width="120" height="40" viewBox="0 0 120 40" fill="none" style="opacity:0.25">
            <path d="M5 26 Q25 4 46 18 Q60 28 76 12 Q92 0 112 20" stroke="#1D4ED8" stroke-width="1.5" fill="none" stroke-linecap="round"/>
            <path d="M14 28 Q34 10 55 22 Q70 32 86 16" stroke="#1D4ED8" stroke-width="0.8" fill="none" stroke-linecap="round" opacity="0.5"/>
          </svg>
          @endif
        </div>
        <div class="sig-line"></div>
        <div class="sig-name">{{ $settings['nama_koordinator'] ?? 'Koordinator Program Magang' }}</div>
        <div class="sig-title">Wakil Direktur I</div>
        <div class="sig-title">{{ $settings['university_name'] ?? 'Politeknik Krakatau' }}</div>
      </div>

    </div>

    <p class="footnote">
      Sertifikat ini sah dan diterbitkan secara resmi — berlaku tanpa tanda tangan basah apabila disertai nomor seri yang valid
    </p>

  </div>
</div>

</body>
</html>
