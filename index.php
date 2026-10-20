<?php
// Nama tamu dari URL, contoh: index.php?to=Budi+Santoso
$namaTamu = isset($_GET['to']) && trim($_GET['to']) !== ''
    ? htmlspecialchars(trim($_GET['to']), ENT_QUOTES, 'UTF-8')
    : 'Tamu Undangan';
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>The Wedding of Nama anda &amp; Pasangann</title>

    <meta name="theme-color" content="#0c0a09">

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- GSAP + ScrollTrigger (cukup dimuat sekali) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>

    <style>
        body { font-family: 'Poppins', sans-serif; }
        .font-serif-custom { font-family: 'Cormorant Garamond', serif; }
        body.locked { overflow: hidden; }

        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        /* ===== ORNAMEN BATIK KAWUNG (tipis, di pinggir kiri & kanan, bergerak) ===== */
        .batik-edge {
            position: fixed;
            top: 0; bottom: 0;
            width: 40px;
            z-index: 55;               /* di atas cover & konten */
            pointer-events: none;
            opacity: .30;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60' viewBox='0 0 60 60'%3E%3Cg fill='none' stroke='%23d9a441' stroke-width='1.1'%3E%3Cellipse cx='30' cy='15' rx='9' ry='15'/%3E%3Cellipse cx='30' cy='45' rx='9' ry='15'/%3E%3Cellipse cx='15' cy='30' rx='15' ry='9'/%3E%3Cellipse cx='45' cy='30' rx='15' ry='9'/%3E%3Ccircle cx='30' cy='30' r='2.6' fill='%23d9a441' stroke='none'/%3E%3Ccircle cx='0' cy='0' r='1.6' fill='%23d9a441' stroke='none'/%3E%3C/g%3E%3C/svg%3E");
            background-size: 40px 40px;
            background-repeat: repeat;
        }
        .batik-left {
            left: 0;
            border-right: 1px solid rgba(217, 164, 65, .25);
            -webkit-mask-image: linear-gradient(to right, #000 55%, transparent);
                    mask-image: linear-gradient(to right, #000 55%, transparent);
            animation: batik-down 28s linear infinite, batik-glow 6s ease-in-out infinite;
        }
        .batik-right {
            right: 0;
            border-left: 1px solid rgba(217, 164, 65, .25);
            -webkit-mask-image: linear-gradient(to left, #000 55%, transparent);
                    mask-image: linear-gradient(to left, #000 55%, transparent);
            animation: batik-up 28s linear infinite, batik-glow 6s ease-in-out 3s infinite;
        }
        @keyframes batik-down { to { background-position: 0 40px; } }
        @keyframes batik-up   { to { background-position: 0 -40px; } }
        @keyframes batik-glow { 0%, 100% { opacity: .22; } 50% { opacity: .42; } }

        @media (max-width: 640px) { .batik-edge { width: 26px; background-size: 26px 26px; } @keyframes batik-down { to { background-position: 0 26px; } } @keyframes batik-up { to { background-position: 0 -26px; } } }

        /* ===== HIASAN BUNGA & DAUN EMAS (4 sudut tiap section) ===== */
        .orn-above { position: relative; z-index: 10; }
        .orn { position: absolute; width: 150px; height: 190px; z-index: 0; pointer-events: none; opacity: .6; }
        .orn-tl { top: -6px;    left: 10px; }
        .orn-tr { top: -6px;    right: 10px; transform: scaleX(-1); }
        .orn-bl { bottom: -6px; left: 10px;  transform: scaleY(-1); }
        .orn-br { bottom: -6px; right: 10px; transform: scale(-1, -1); }
        .orn svg { position: absolute; overflow: visible; }
        .orn-sprig  { left: 7%;  top: 0;   width: 53%; height: 71%; transform-origin: 50% 0; animation: sway 7s ease-in-out infinite; animation-delay: var(--d, 0s); }
        .orn-leaf   { left: 66%; top: 8%;  width: 19%; height: 24%; transform-origin: 50% 100%; animation: sway 5s ease-in-out infinite; animation-delay: var(--d, 0s); }
        .orn-flower { transform-origin: 50% 50%; animation: spin 50s linear infinite, float 6s ease-in-out infinite, glint 5s ease-in-out infinite; animation-delay: var(--d, 0s); }
        .orn-f1 { left: 42%; top: 37%; width: 41%; }
        .orn-f2 { left: 18%; top: 68%; width: 25%; animation-duration: 34s, 5s, 4s; animation-direction: reverse, normal, normal; }
        @keyframes sway  { 0%, 100% { transform: rotate(-5deg); } 50% { transform: rotate(6deg); } }
        @keyframes spin  { to { rotate: 360deg; } }
        @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-7px); } }
        @keyframes glint { 0%, 100% { filter: drop-shadow(0 0 0 rgba(246,220,139,0)); } 50% { filter: drop-shadow(0 0 6px rgba(246,220,139,.75)); } }
        @media (max-width: 640px) { .orn { width: 100px; height: 127px; opacity: .5; } }

        /* Pemisah kecil: bunga emas diapit dua daun */
        .orn-divider { position: absolute; top: 28px; left: 50%; transform: translateX(-50%); display: flex; align-items: center; gap: 10px; pointer-events: none; }
        .orn-divider i { display: block; width: 56px; height: 1px; background: linear-gradient(to right, transparent, #d9a441); }
        .orn-divider i:last-child { transform: scaleX(-1); }
        .orn-divider svg { overflow: visible; }
        .dv-leaf { width: 14px; height: 24px; transform-origin: 50% 100%; animation: sway 5s ease-in-out infinite; animation-delay: var(--d, 0s); }
        .dv-leaf-l { rotate: -70deg; }
        .dv-leaf-r { rotate: 70deg; }
        .dv-flower { width: 30px; height: 30px; }
        @media (prefers-reduced-motion: reduce) { .dv-leaf { animation: none !important; } }

        /* Tombol musik */
        @keyframes spin-slow { to { transform: rotate(360deg); } }
        .spin-slow { animation: spin-slow 6s linear infinite; }

        @media (prefers-reduced-motion: reduce) {
            .batik-edge, .spin-slow, .orn-sprig, .orn-leaf, .orn-flower { animation: none !important; }
        }
    </style>
</head>
<body class="locked bg-stone-900 text-stone-800 antialiased overflow-x-hidden">

    <!-- SPRITE SVG: bunga, daun, ranting emas (dipakai ulang lewat <use>) -->
    <svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false">
        <defs>
            <linearGradient id="gold" x1="0" y1="0" x2="1" y2="1">
                <stop offset="0" stop-color="#f6dc8b"/>
                <stop offset=".5" stop-color="#d9a441"/>
                <stop offset="1" stop-color="#a8741a"/>
            </linearGradient>

            <symbol id="flower" viewBox="0 0 100 100">
                <g fill="url(#gold)" stroke="#8a5e12" stroke-width=".8">
                    <ellipse cx="50" cy="27" rx="10" ry="23"/>
                    <ellipse cx="50" cy="27" rx="10" ry="23" transform="rotate(45 50 50)"/>
                    <ellipse cx="50" cy="27" rx="10" ry="23" transform="rotate(90 50 50)"/>
                    <ellipse cx="50" cy="27" rx="10" ry="23" transform="rotate(135 50 50)"/>
                    <ellipse cx="50" cy="27" rx="10" ry="23" transform="rotate(180 50 50)"/>
                    <ellipse cx="50" cy="27" rx="10" ry="23" transform="rotate(225 50 50)"/>
                    <ellipse cx="50" cy="27" rx="10" ry="23" transform="rotate(270 50 50)"/>
                    <ellipse cx="50" cy="27" rx="10" ry="23" transform="rotate(315 50 50)"/>
                </g>
                <g fill="#f6dc8b" opacity=".85">
                    <ellipse cx="50" cy="36" rx="5" ry="13" transform="rotate(22.5 50 50)"/>
                    <ellipse cx="50" cy="36" rx="5" ry="13" transform="rotate(67.5 50 50)"/>
                    <ellipse cx="50" cy="36" rx="5" ry="13" transform="rotate(112.5 50 50)"/>
                    <ellipse cx="50" cy="36" rx="5" ry="13" transform="rotate(157.5 50 50)"/>
                    <ellipse cx="50" cy="36" rx="5" ry="13" transform="rotate(202.5 50 50)"/>
                    <ellipse cx="50" cy="36" rx="5" ry="13" transform="rotate(247.5 50 50)"/>
                    <ellipse cx="50" cy="36" rx="5" ry="13" transform="rotate(292.5 50 50)"/>
                    <ellipse cx="50" cy="36" rx="5" ry="13" transform="rotate(337.5 50 50)"/>
                </g>
                <circle cx="50" cy="50" r="8" fill="#a8741a"/>
                <circle cx="50" cy="50" r="4" fill="#f6dc8b"/>
            </symbol>

            <symbol id="leaf" viewBox="0 0 60 100">
                <path d="M30 98 C5 70 8 25 30 2 C52 25 55 70 30 98Z" fill="url(#gold)" stroke="#8a5e12" stroke-width=".8"/>
                <path d="M30 96 L30 10" stroke="#8a5e12" stroke-width=".9" fill="none"/>
            </symbol>

            <symbol id="sprig" viewBox="0 0 120 200">
                <path d="M60 2 C55 60 65 120 60 190" stroke="#d9a441" stroke-width="2" fill="none"/>
                <g fill="url(#gold)" stroke="#8a5e12" stroke-width=".8">
                    <g transform="translate(60 40) scale(1) translate(-60 -40)">
                        <path d="M60 40 C25 35 8 55 14 80 C42 78 58 62 60 40Z"/>
                        <path d="M60 40 C95 35 112 55 106 80 C78 78 62 62 60 40Z"/>
                    </g>
                    <g transform="translate(60 85) scale(.85) translate(-60 -40)">
                        <path d="M60 40 C25 35 8 55 14 80 C42 78 58 62 60 40Z"/>
                        <path d="M60 40 C95 35 112 55 106 80 C78 78 62 62 60 40Z"/>
                    </g>
                    <g transform="translate(60 130) scale(.7) translate(-60 -40)">
                        <path d="M60 40 C25 35 8 55 14 80 C42 78 58 62 60 40Z"/>
                        <path d="M60 40 C95 35 112 55 106 80 C78 78 62 62 60 40Z"/>
                    </g>
                    <path d="M60 192 C45 172 48 152 60 142 C72 152 75 172 60 192Z"/>
                </g>
            </symbol>
        </defs>
    </svg>

    <!-- ORNAMEN BATIK (tampil di semua halaman, termasuk cover) -->
    <div class="batik-edge batik-left" aria-hidden="true"></div>
    <div class="batik-edge batik-right" aria-hidden="true"></div>

    <!-- 1. COVER -->
    <section id="cover-section" data-corners="tl,tr" class="orn-host overflow-hidden fixed inset-0 z-50 flex flex-col items-center justify-center bg-stone-950 text-stone-100 text-center px-10">
        <div class="absolute inset-0 opacity-30 bg-cover bg-center" style="background-image: url('assets/images/hero-bg.jpg');"></div>
        <div class="relative z-10 max-w-md mx-auto">
            <p class="text-sm tracking-widest uppercase text-amber-200 mb-3">The Wedding Of</p>
            <h1 class="font-serif-custom text-5xl md:text-6xl italic mb-6">Mempelai &amp; Pasangan</h1>
            <p class="text-stone-300 text-sm mb-2">Kepada Yth. Bapak/Ibu/Saudara/i:</p>
            <div class="bg-stone-900/80 border border-amber-500/30 rounded-lg py-3 px-6 mb-8 text-amber-100 font-medium text-lg tracking-wide">
                <?php echo $namaTamu; ?>
            </div>
            <button id="open-invitation" type="button" class="bg-amber-600 hover:bg-amber-500 text-stone-950 font-semibold px-8 py-3 rounded-full shadow-lg transition hover:scale-105 flex items-center justify-center mx-auto gap-2 focus:outline-none focus:ring-2 focus:ring-amber-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                </svg>
                Buka Undangan
            </button>
        </div>
    </section>

    <audio id="bg-music" loop preload="none">
        <source src="assets/audio/backsound.mp3" type="audio/mpeg">
    </audio>

    <button id="music-control" type="button" aria-label="Putar atau jeda musik" class="fixed bottom-6 right-12 z-[60] bg-amber-600 text-white w-11 h-11 rounded-full shadow-xl hidden items-center justify-center">
        <span id="music-icon" class="spin-slow inline-block">🎵</span>
    </button>

    <!-- 2. KONTEN UTAMA -->
    <div id="main-content" class="opacity-0">

        <!-- Hero -->
        <header data-corners="tl,tr" class="orn-host overflow-hidden min-h-screen flex flex-col items-center justify-center text-center px-10 relative bg-stone-900 text-stone-100">
            <div class="absolute inset-0 opacity-40 bg-cover bg-center" style="background-image: url('assets/images/hero-bg.jpg');"></div>
            <div class="relative z-10 max-w-2xl mx-auto py-20">
                <p class="text-amber-200 tracking-widest uppercase mb-4 text-sm">Save The Date</p>
                <h1 class="font-serif-custom text-6xl md:text-7xl italic mb-6">Nama &amp; Pasangan</h1>
                <p class="text-stone-300 text-base mb-8">Jumat, 25 Desember 2026</p>

                <div id="countdown" class="grid grid-cols-4 gap-3 max-w-md mx-auto text-stone-900">
                    <div class="glass-card p-3 rounded-lg"><span id="days" class="text-2xl font-bold block">00</span><span class="text-xs uppercase">Hari</span></div>
                    <div class="glass-card p-3 rounded-lg"><span id="hours" class="text-2xl font-bold block">00</span><span class="text-xs uppercase">Jam</span></div>
                    <div class="glass-card p-3 rounded-lg"><span id="minutes" class="text-2xl font-bold block">00</span><span class="text-xs uppercase">Menit</span></div>
                    <div class="glass-card p-3 rounded-lg"><span id="seconds" class="text-2xl font-bold block">00</span><span class="text-xs uppercase">Detik</span></div>
                </div>
            </div>
        </header>

        <!-- Mempelai -->
        <section class="div-host relative py-24 px-10 md:px-16 bg-stone-950 text-stone-200 text-center overflow-hidden">
            <div class="max-w-4xl mx-auto">
                <h2 class="reveal font-serif-custom text-4xl italic text-amber-200 mb-6">Assalamu'alaikum Wr. Wb.</h2>
                <p class="reveal text-stone-400 max-w-xl mx-auto mb-16 text-sm md:text-base leading-relaxed">
                    Tanpa mengurangi rasa hormat, kami mengundang Bapak/Ibu/Saudara/i sekalian untuk menghadiri acara pernikahan kami.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                    <div class="profile-card space-y-4">
                        <div class="w-48 h-48 mx-auto rounded-full overflow-hidden border-2 border-amber-500/50 shadow-xl bg-stone-800">
                            <img src="assets/images/groom.jpg" alt="Mempelai pria" class="w-full h-full object-cover" onerror="this.style.display='none'">
                        </div>
                        <h3 class="font-serif-custom text-3xl italic text-amber-100">Nama Pria</h3>
                        <p class="text-xs text-stone-400">Putra dari Bpk. ... &amp; Ibu ...</p>
                    </div>

                    <div class="profile-card space-y-4">
                        <div class="w-48 h-48 mx-auto rounded-full overflow-hidden border-2 border-amber-500/50 shadow-xl bg-stone-800">
                            <img src="assets/images/bride.jpg" alt="Mempelai wanita" class="w-full h-full object-cover" onerror="this.style.display='none'">
                        </div>
                        <h3 class="font-serif-custom text-3xl italic text-amber-100">Nama Wanita</h3>
                        <p class="text-xs text-stone-400">Putri dari Bpk. ... &amp; Ibu ...</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Acara -->
        <section id="acara" class="div-host relative py-24 px-10 md:px-16 bg-stone-900 text-stone-200 text-center overflow-hidden">
            <div class="max-w-3xl mx-auto">
                <h2 class="reveal font-serif-custom text-4xl italic text-amber-200 mb-12">Waktu &amp; Tempat</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="reveal glass-card p-6 rounded-2xl text-stone-800">
                        <h3 class="font-serif-custom text-2xl italic text-amber-900 mb-2">Akad Nikah</h3>
                        <p class="text-sm">Jumat, 25 Desember 2026</p>
                        <p class="text-sm mb-3">Pukul 09.00 WIB – selesai</p>
                        <p class="text-sm text-stone-600">Nama gedung / alamat lengkap</p>
                    </div>
                    <div class="reveal glass-card p-6 rounded-2xl text-stone-800">
                        <h3 class="font-serif-custom text-2xl italic text-amber-900 mb-2">Resepsi</h3>
                        <p class="text-sm">Jumat, 25 Desember 2026</p>
                        <p class="text-sm mb-3">Pukul 11.00 WIB – 14.00 WIB</p>
                        <p class="text-sm text-stone-600">Nama gedung / alamat lengkap</p>
                    </div>
                </div>
                <a href="https://maps.google.com/?q=Lokasi+Acara" target="_blank" rel="noopener"
                   class="reveal inline-block mt-10 bg-amber-600 hover:bg-amber-500 text-stone-950 font-semibold px-8 py-3 rounded-full shadow-lg transition">
                    Lihat Lokasi di Google Maps
                </a>
            </div>
        </section>

        <!-- RSVP -->
        <section id="rsvp" class="div-host relative overflow-hidden py-24 px-10 md:px-16 bg-stone-950 text-stone-200">
            <div class="max-w-2xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="reveal font-serif-custom text-4xl italic text-amber-200 mb-3">RSVP &amp; Ucapan</h2>
                    <p class="reveal text-stone-400 text-sm">Berikan konfirmasi kehadiran dan doa restu Anda untuk kami.</p>
                </div>

                <form id="rsvp-form" class="reveal glass-card p-6 md:p-8 rounded-2xl shadow-xl space-y-6 text-stone-800">
                    <div>
                        <label for="guest-name" class="block text-sm font-medium mb-2">Nama Lengkap</label>
                        <input type="text" id="guest-name" name="name" required maxlength="80"
                               value="<?php echo $namaTamu !== 'Tamu Undangan' ? $namaTamu : ''; ?>"
                               class="w-full px-4 py-3 rounded-lg border border-stone-300 focus:ring-2 focus:ring-amber-500 focus:outline-none" placeholder="Masukkan nama Anda">
                    </div>
                    <div>
                        <label for="guest-status" class="block text-sm font-medium mb-2">Konfirmasi Kehadiran</label>
                        <select id="guest-status" name="status" class="w-full px-4 py-3 rounded-lg border border-stone-300 focus:ring-2 focus:ring-amber-500 focus:outline-none">
                            <option value="Hadir">Hadir (Datang)</option>
                            <option value="Tidak Hadir">Berhalangan Hadir</option>
                        </select>
                    </div>
                    <div>
                        <label for="guest-message" class="block text-sm font-medium mb-2">Ucapan &amp; Doa Restu</label>
                        <textarea id="guest-message" name="message" rows="4" required maxlength="500"
                                  class="w-full px-4 py-3 rounded-lg border border-stone-300 focus:ring-2 focus:ring-amber-500 focus:outline-none" placeholder="Tuliskan ucapan atau doa..."></textarea>
                    </div>
                    <button type="submit" id="submit-btn" class="w-full bg-amber-600 hover:bg-amber-500 disabled:opacity-60 text-white font-semibold py-3 rounded-lg shadow-md transition duration-300">
                        Kirim Ucapan &amp; Konfirmasi
                    </button>
                </form>

                <div class="mt-16">
                    <h3 class="font-serif-custom text-2xl italic text-amber-100 mb-6 text-center">Ucapan &amp; Doa Tamu</h3>
                    <div id="comments-container" class="space-y-4 max-h-96 overflow-y-auto pr-2">
                        <div class="glass-card p-4 rounded-xl text-stone-800">
                            <div class="flex justify-between items-center mb-1">
                                <h4 class="font-semibold text-amber-900">Budi Santoso</h4>
                                <span class="text-xs bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded-full">Hadir</span>
                            </div>
                            <p class="text-sm text-stone-600">Selamat berbahagia! Semoga menjadi keluarga yang sakinah, mawaddah, warahmah.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

<!-- Kado Digital / Digital Envelope -->
        <section id="kado" class="div-host relative py-24 px-10 md:px-16 bg-stone-900 text-stone-200 text-center overflow-hidden">
            <div class="max-w-3xl mx-auto">
                <h2 class="reveal font-serif-custom text-4xl italic text-amber-200 mb-4">Kado Digital</h2>
                <p class="reveal text-stone-400 text-sm md:text-base max-w-lg mx-auto mb-12">
                    Doa restu Anda merupakan karunia yang sangat berarti bagi kami. Namun jika memberi adalah ungkapan tanda kasih, Anda dapat mengirimkannya melalui tautan di bawah ini:
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Rekening Bank 1 -->
                    <div class="reveal glass-card p-6 rounded-2xl text-stone-800 text-left space-y-3 shadow-lg">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold text-amber-900 text-lg">BCA</span>
                            <span class="text-xs bg-amber-100 text-amber-800 px-2 py-0.5 rounded-full font-medium">Bank Transfer</span>
                        </div>
                        <div>
                            <p class="text-xs text-stone-500">Nomor Rekening</p>
                            <p id="rek-1" class="font-mono text-lg font-bold text-stone-800 tracking-wide">1234567890</p>
                        </div>
                        <div>
                            <p class="text-xs text-stone-500">Atas Nama</p>
                            <p class="text-sm font-medium text-stone-700">Nama Mempelai Pria</p>
                        </div>
                        <button type="button" onclick="copyToClipboard('1234567890', this)" class="w-full mt-2 bg-amber-600 hover:bg-amber-500 text-white font-medium text-sm py-2.5 rounded-lg shadow transition flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                            </svg>
                            Salin Nomor Rekening
                        </button>
                    </div>

                    <!-- E-Wallet / Rekening Bank 2 -->
                    <div class="reveal glass-card p-6 rounded-2xl text-stone-800 text-left space-y-3 shadow-lg">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold text-amber-900 text-lg">GoPay / Dana</span>
                            <span class="text-xs bg-sky-100 text-sky-800 px-2 py-0.5 rounded-full font-medium">E-Wallet</span>
                        </div>
                        <div>
                            <p class="text-xs text-stone-500">Nomor Handphone / Akun</p>
                            <p id="rek-2" class="font-mono text-lg font-bold text-stone-800 tracking-wide">081234567890</p>
                        </div>
                        <div>
                            <p class="text-xs text-stone-500">Atas Nama</p>
                            <p class="text-sm font-medium text-stone-700">Nama Mempelai Wanita</p>
                        </div>
                        <button type="button" onclick="copyToClipboard('081234567890', this)" class="w-full mt-2 bg-amber-600 hover:bg-amber-500 text-white font-medium text-sm py-2.5 rounded-lg shadow transition flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                            </svg>
                            Salin Nomor Akun
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <footer class="bg-stone-950 text-stone-400 text-center py-8 px-10 border-t border-stone-800">
            <p class="font-serif-custom italic text-lg text-amber-200 mb-1">Wassalamu'alaikum Wr. Wb.</p>
            <p class="text-sm">&copy; 2026 Nama &amp; Pasangan.<br>by ChiwaWebDesign.</p>
        </footer>
    </div>

    <script>
        const openBtn      = document.getElementById('open-invitation');
        const coverSection = document.getElementById('cover-section');
        const mainContent  = document.getElementById('main-content');
        const bgMusic      = document.getElementById('bg-music');
        const musicControl = document.getElementById('music-control');
        const musicIcon    = document.getElementById('music-icon');
        let isPlaying = false;

        gsap.registerPlugin(ScrollTrigger);

        // ---------- Buka undangan ----------
        openBtn.addEventListener('click', () => {
            // Musik harus dimulai langsung dari klik pengguna agar tidak diblokir browser
            bgMusic.play().then(() => { isPlaying = true; }).catch(() => { isPlaying = false; setMusicUI(); });

            gsap.to(coverSection, {
                yPercent: -100,
                duration: 1.2,
                ease: 'power4.inOut',
                onComplete: () => {
                    coverSection.style.display = 'none';
                    document.body.classList.remove('locked');
                    gsap.to(mainContent, { opacity: 1, duration: 0.8 });
                    musicControl.classList.remove('hidden');
                    musicControl.classList.add('flex');
                    setMusicUI();
                    gsap.from('header h1', { y: 50, opacity: 0, duration: 1, delay: 0.2 });
                    ScrollTrigger.refresh();
                }
            });
        });

        // ---------- Musik ----------
        function setMusicUI() {
            musicIcon.textContent = isPlaying ? '🎵' : '🔇';
            musicIcon.classList.toggle('spin-slow', isPlaying);
        }
        musicControl.addEventListener('click', () => {
            if (isPlaying) { bgMusic.pause(); isPlaying = false; }
            else { bgMusic.play().catch(() => {}); isPlaying = true; }
            setMusicUI();
        });

        // ---------- Countdown (WIB, UTC+7) ----------
        const weddingDate = new Date('2026-12-25T09:00:00+07:00').getTime();
        const pad = n => String(n).padStart(2, '0');
        function tick() {
            const distance = weddingDate - Date.now();
            if (distance <= 0) {
                clearInterval(timer);
                document.getElementById('countdown').innerHTML =
                    "<p class='col-span-4 text-amber-200 font-semibold'>Acara Telah Berlangsung</p>";
                return;
            }
            document.getElementById('days').textContent    = pad(Math.floor(distance / 86400000));
            document.getElementById('hours').textContent   = pad(Math.floor(distance % 86400000 / 3600000));
            document.getElementById('minutes').textContent = pad(Math.floor(distance % 3600000 / 60000));
            document.getElementById('seconds').textContent = pad(Math.floor(distance % 60000 / 1000));
        }
        const timer = setInterval(tick, 1000);
        tick();

        // ---------- RSVP ----------
        const rsvpForm = document.getElementById('rsvp-form');
        const commentsContainer = document.getElementById('comments-container');
        const submitBtn = document.getElementById('submit-btn');

        // Buat kartu ucapan dengan textContent (aman dari XSS)
        function buildCard(name, status, message) {
            const card = document.createElement('div');
            card.className = 'glass-card p-4 rounded-xl text-stone-800';

            const head = document.createElement('div');
            head.className = 'flex justify-between items-center mb-1';

            const h4 = document.createElement('h4');
            h4.className = 'font-semibold text-amber-900';
            h4.textContent = name;

            const badge = document.createElement('span');
            badge.className = 'text-xs px-2 py-0.5 rounded-full ' +
                (status === 'Hadir' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800');
            badge.textContent = status;

            const p = document.createElement('p');
            p.className = 'text-sm text-stone-600 break-words';
            p.textContent = message;

            head.append(h4, badge);
            card.append(head, p);
            return card;
        }

        rsvpForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(rsvpForm);
            const name = formData.get('name'), status = formData.get('status'), message = formData.get('message');

            submitBtn.disabled = true;
            submitBtn.textContent = 'Mengirim...';

            fetch('process-rsvp.php', { method: 'POST', body: formData })
                .then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
                .then(data => {
                    if (data.status !== 'success') throw new Error('Gagal menyimpan');
                    const card = buildCard(name, status, message);
                    commentsContainer.prepend(card);
                    gsap.from(card, { opacity: 0, y: 16, duration: 0.6, ease: 'power2.out' });
                    rsvpForm.reset();
                })
                .catch(err => {
                    console.error(err);
                    alert('Terjadi kesalahan, silakan coba lagi.');
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Kirim Ucapan & Konfirmasi';
                });
        });

   // ---------- Fungsi Salin Nomor Rekening / E-Wallet ----------
        function copyToClipboard(text, btnElement) {
            navigator.clipboard.writeText(text).then(() => {
                const originalText = btnElement.innerHTML;
                btnElement.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Berhasil Disalin!
                `;
                btnElement.classList.remove('bg-amber-600', 'hover:bg-amber-500');
                btnElement.classList.add('bg-emerald-600', 'hover:bg-emerald-500');

                setTimeout(() => {
                    btnElement.innerHTML = originalText;
                    btnElement.classList.remove('bg-emerald-600', 'hover:bg-emerald-500');
                    btnElement.classList.add('bg-amber-600', 'hover:bg-amber-500');
                }, 2000);
            }).catch(err => {
                console.error('Gagal menyalin text: ', err);
                alert('Gagal menyalin teks secara otomatis.');
            });
        }
        // ---------- Hiasan bunga & daun emas di 4 sudut tiap section ----------
        const ORN_HTML = `
            <svg class="orn-sprig" viewBox="0 0 120 200"><use href="#sprig"/></svg>
            <svg class="orn-leaf" viewBox="0 0 60 100"><use href="#leaf"/></svg>
            <svg class="orn-flower orn-f1" viewBox="0 0 100 100"><use href="#flower"/></svg>
            <svg class="orn-flower orn-f2" viewBox="0 0 100 100"><use href="#flower"/></svg>`;

        document.querySelectorAll('.orn-host').forEach((host) => {
            // Konten tetap di atas hiasan
            Array.from(host.children).forEach((c) => {
                if (!c.classList.contains('absolute')) c.classList.add('orn-above');
            });
            (host.dataset.corners || 'tl,tr').split(',').forEach((pos) => {
                const box = document.createElement('div');
                box.className = 'orn orn-' + pos;
                box.setAttribute('aria-hidden', 'true');
                box.innerHTML = ORN_HTML;
                // Delay acak supaya tiap bunga & daun bergerak tidak serempak
                box.querySelectorAll('svg').forEach((el) => {
                    el.style.setProperty('--d', (-Math.random() * 8).toFixed(2) + 's');
                });
                host.appendChild(box);
            });
        });

        // Pemisah di bagian atas section lain
        document.querySelectorAll('.div-host').forEach((host) => {
            const d = document.createElement('div');
            d.className = 'orn-divider';
            d.setAttribute('aria-hidden', 'true');
            d.innerHTML = `<i></i>
                <svg class="dv-leaf dv-leaf-l" viewBox="0 0 60 100"><use href="#leaf"/></svg>
                <svg class="orn-flower dv-flower" viewBox="0 0 100 100"><use href="#flower"/></svg>
                <svg class="dv-leaf dv-leaf-r" viewBox="0 0 60 100"><use href="#leaf"/></svg>
                <i></i>`;
            d.querySelectorAll('svg').forEach((el) => el.style.setProperty('--d', (-Math.random() * 6).toFixed(2) + 's'));
            host.appendChild(d);
        });

        // ---------- Animasi scroll ----------
        gsap.utils.toArray('.reveal').forEach((el) => {
            gsap.from(el, {
                y: 40, opacity: 0, duration: 1, ease: 'power3.out',
                scrollTrigger: { trigger: el, start: 'top 88%', toggleActions: 'play none none none' }
            });
        });

        gsap.from('.profile-card', {
            y: 80, opacity: 0, duration: 1.2, stagger: 0.3, ease: 'power4.out',
            scrollTrigger: { trigger: '.profile-card', start: 'top 85%', toggleActions: 'play none none none' }
        });
    </script>
</body>
</html>
