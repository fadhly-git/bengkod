<!DOCTYPE html>
<html lang="id">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Poliklinik - Smart Clinic Experience</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700;800&family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">

	<style>
		:root {
			--ink: #13213c;
			--ink-soft: #2e3f63;
			--paper: #fbfaf8;
			--mint: #4ad4bc;
			--amber: #f6b24f;
			--sky: #8fc3ff;
			--line: rgba(19, 33, 60, 0.12);
			--glow: rgba(74, 212, 188, 0.45);
		}

		* {
			box-sizing: border-box;
			margin: 0;
			padding: 0;
		}

		body {
			min-height: 100vh;
			overflow-x: hidden;
			color: var(--ink);
			background: radial-gradient(circle at 18% 20%, #ffffff 0%, #f6f8ff 42%, #eef6ff 100%);
			font-family: 'Space Grotesk', sans-serif;
		}

		.scene {
			position: fixed;
			inset: 0;
			pointer-events: none;
			z-index: 0;
			overflow: hidden;
		}

		.orb {
			position: absolute;
			border-radius: 999px;
			filter: blur(1px);
			opacity: 0.8;
			animation: drift 16s ease-in-out infinite alternate;
		}

		.orb.one {
			width: 340px;
			height: 340px;
			left: -120px;
			top: -100px;
			background: radial-gradient(circle, rgba(143, 195, 255, 0.7), rgba(143, 195, 255, 0.08));
			animation-duration: 18s;
		}

		.orb.two {
			width: 420px;
			height: 420px;
			right: -160px;
			top: 12%;
			background: radial-gradient(circle, rgba(74, 212, 188, 0.62), rgba(74, 212, 188, 0.06));
			animation-duration: 14s;
		}

		.orb.three {
			width: 280px;
			height: 280px;
			right: 28%;
			bottom: -120px;
			background: radial-gradient(circle, rgba(246, 178, 79, 0.55), rgba(246, 178, 79, 0.06));
			animation-duration: 20s;
		}

		.grid {
			position: absolute;
			inset: 0;
			background-image:
				linear-gradient(to right, var(--line) 1px, transparent 1px),
				linear-gradient(to bottom, var(--line) 1px, transparent 1px);
			background-size: 48px 48px;
			mask-image: radial-gradient(circle at center, black 30%, transparent 85%);
			opacity: 0.4;
			animation: pulseGrid 6s ease-in-out infinite;
		}

		.wrapper {
			position: relative;
			z-index: 1;
			width: min(1120px, 92vw);
			margin: 0 auto;
			padding: 28px 0 42px;
		}

		.topbar {
			display: flex;
			align-items: center;
			justify-content: space-between;
			gap: 18px;
			margin-bottom: 34px;
			animation: slideDown 900ms cubic-bezier(.18, .72, .22, 1) both;
		}

		.brand {
			display: inline-flex;
			align-items: center;
			gap: 12px;
			font-weight: 700;
			letter-spacing: 0.02em;
		}

		.brand-badge {
			width: 42px;
			height: 42px;
			border-radius: 14px;
			display: grid;
			place-items: center;
			background: linear-gradient(145deg, #172a53, #2f5fa3);
			color: #fff;
			box-shadow: 0 12px 24px rgba(23, 42, 83, 0.26);
			transform: rotate(-8deg);
			animation: floatBadge 5s ease-in-out infinite;
		}

		.top-actions {
			display: inline-flex;
			gap: 10px;
		}

		.btn {
			border: 0;
			text-decoration: none;
			border-radius: 999px;
			padding: 11px 18px;
			font-weight: 600;
			font-size: 0.92rem;
			transition: transform 200ms ease, box-shadow 200ms ease, background 200ms ease;
		}

		.btn-soft {
			color: var(--ink);
			background: rgba(255, 255, 255, 0.75);
			border: 1px solid rgba(255, 255, 255, 0.9);
			backdrop-filter: blur(6px);
		}

		.btn-main {
			color: #fff;
			background: linear-gradient(135deg, #183162, #2a5a9f);
			box-shadow: 0 12px 20px rgba(24, 49, 98, 0.3);
		}

		.btn:hover {
			transform: translateY(-2px);
		}

		.hero {
			display: grid;
			grid-template-columns: 1.06fr 0.94fr;
			align-items: center;
			gap: 34px;
		}

		.hero-copy {
			animation: rise 900ms 180ms cubic-bezier(.15, .75, .2, 1) both;
		}

		.eyebrow {
			display: inline-flex;
			align-items: center;
			gap: 8px;
			border-radius: 999px;
			padding: 7px 12px;
			font-size: 0.74rem;
			letter-spacing: 0.12em;
			text-transform: uppercase;
			color: var(--ink-soft);
			background: rgba(255, 255, 255, 0.75);
			border: 1px solid rgba(19, 33, 60, 0.08);
			margin-bottom: 18px;
		}

		.eyebrow i {
			width: 8px;
			height: 8px;
			border-radius: 999px;
			background: var(--mint);
			box-shadow: 0 0 0 0 var(--glow);
			animation: ping 2s ease-out infinite;
		}

		h1 {
			font-family: 'Playfair Display', serif;
			font-size: clamp(2.1rem, 5.4vw, 4.3rem);
			line-height: 1.04;
			letter-spacing: -0.02em;
			margin-bottom: 16px;
		}

		.lead {
			max-width: 56ch;
			color: var(--ink-soft);
			line-height: 1.75;
			margin-bottom: 24px;
			font-size: clamp(0.98rem, 1.35vw, 1.1rem);
		}

		.cta-row {
			display: flex;
			flex-wrap: wrap;
			gap: 12px;
			margin-bottom: 28px;
		}

		.stat-row {
			display: grid;
			grid-template-columns: repeat(3, minmax(90px, 1fr));
			gap: 12px;
			max-width: 560px;
		}

		.stat {
			padding: 14px;
			border-radius: 16px;
			background: rgba(255, 255, 255, 0.78);
			border: 1px solid rgba(19, 33, 60, 0.08);
			backdrop-filter: blur(5px);
			animation: rise 900ms cubic-bezier(.15, .75, .2, 1) both;
		}

		.stat:nth-child(1) {
			animation-delay: 350ms;
		}

		.stat:nth-child(2) {
			animation-delay: 480ms;
		}

		.stat:nth-child(3) {
			animation-delay: 610ms;
		}

		.stat b {
			display: block;
			font-size: 1.2rem;
		}

		.stat span {
			color: var(--ink-soft);
			font-size: 0.84rem;
		}

		.hero-panel {
			position: relative;
			border-radius: 26px;
			background: linear-gradient(168deg, rgba(255, 255, 255, 0.95), rgba(241, 248, 255, 0.82));
			border: 1px solid rgba(19, 33, 60, 0.08);
			box-shadow: 0 24px 64px rgba(32, 52, 91, 0.16);
			overflow: hidden;
			min-height: 520px;
			animation: rise 920ms 280ms cubic-bezier(.15, .75, .2, 1) both;
		}

		.panel-glow {
			position: absolute;
			inset: -40% auto auto -25%;
			width: 360px;
			height: 360px;
			border-radius: 999px;
			background: radial-gradient(circle, rgba(143, 195, 255, 0.45), transparent 66%);
			animation: orbit 14s linear infinite;
		}

		.panel-content {
			position: relative;
			padding: 26px;
			display: grid;
			gap: 12px;
		}

		.panel-card {
			border-radius: 18px;
			padding: 14px;
			background: rgba(255, 255, 255, 0.88);
			border: 1px solid rgba(19, 33, 60, 0.08);
			transform: translateX(22px);
			opacity: 0;
			animation: cardIn 700ms cubic-bezier(.22, .73, .25, 1) forwards;
		}

		.panel-card:nth-child(2) {
			animation-delay: 460ms;
		}

		.panel-card:nth-child(3) {
			animation-delay: 620ms;
		}

		.panel-card:nth-child(4) {
			animation-delay: 780ms;
		}

		.tag {
			display: inline-flex;
			align-items: center;
			padding: 5px 10px;
			border-radius: 999px;
			background: #eff7ff;
			color: #2f5fa3;
			font-size: 0.72rem;
			margin-bottom: 8px;
		}

		.panel-card h3 {
			font-family: 'Playfair Display', serif;
			margin-bottom: 6px;
			font-size: 1.08rem;
		}

		.panel-card p {
			color: var(--ink-soft);
			line-height: 1.65;
			font-size: 0.92rem;
		}

		.ticker {
			margin-top: 24px;
			border-top: 1px dashed rgba(19, 33, 60, 0.2);
			padding-top: 16px;
			overflow: hidden;
			white-space: nowrap;
		}

		.ticker-track {
			display: flex;
			width: max-content;
			animation: ticker 26s linear infinite;
			color: var(--ink-soft);
			font-size: 0.88rem;
		}

		.ticker-item {
			flex-shrink: 0;
			word-spacing: 0.9rem;
			letter-spacing: 0.08em;
			padding-right: 4.5rem;
		}

		footer {
			margin-top: 30px;
			text-align: center;
			color: rgba(19, 33, 60, 0.6);
			font-size: 0.84rem;
			animation: rise 950ms 540ms cubic-bezier(.15, .75, .2, 1) both;
		}

		@keyframes drift {
			from {
				transform: translateY(-10px) translateX(0) scale(1);
			}

			to {
				transform: translateY(26px) translateX(-18px) scale(1.08);
			}
		}

		@keyframes pulseGrid {
			0%,
			100% {
				opacity: 0.28;
			}

			50% {
				opacity: 0.48;
			}
		}

		@keyframes slideDown {
			from {
				opacity: 0;
				transform: translateY(-16px);
			}

			to {
				opacity: 1;
				transform: translateY(0);
			}
		}

		@keyframes rise {
			from {
				opacity: 0;
				transform: translateY(22px);
			}

			to {
				opacity: 1;
				transform: translateY(0);
			}
		}

		@keyframes floatBadge {
			0%,
			100% {
				transform: rotate(-8deg) translateY(0);
			}

			50% {
				transform: rotate(-4deg) translateY(-6px);
			}
		}

		@keyframes orbit {
			from {
				transform: rotate(0deg) translateX(25px);
			}

			to {
				transform: rotate(360deg) translateX(25px);
			}
		}

		@keyframes cardIn {
			to {
				opacity: 1;
				transform: translateX(0);
			}
		}

		@keyframes ticker {
			from {
				transform: translateX(0);
			}

			to {
				transform: translateX(-50%);
			}
		}

		@keyframes ping {
			0% {
				box-shadow: 0 0 0 0 var(--glow);
			}

			80%,
			100% {
				box-shadow: 0 0 0 12px rgba(74, 212, 188, 0);
			}
		}

		@media (max-width: 980px) {
			.hero {
				grid-template-columns: 1fr;
				gap: 20px;
			}

			.hero-panel {
				min-height: auto;
			}

			.panel-content {
				padding: 18px;
			}
		}

		@media (max-width: 640px) {
			.wrapper {
				padding-top: 16px;
			}

			.topbar {
				flex-wrap: wrap;
			}

			.top-actions {
				width: 100%;
			}

			.top-actions .btn {
				flex: 1;
				text-align: center;
			}

			.stat-row {
				grid-template-columns: 1fr;
			}
		}

		@media (prefers-reduced-motion: reduce) {
			*,
			*::before,
			*::after {
				animation-duration: 1ms !important;
				animation-iteration-count: 1 !important;
				transition-duration: 1ms !important;
			}
		}
	</style>
</head>

<body>
	<div class="scene" aria-hidden="true">
		<div class="orb one"></div>
		<div class="orb two"></div>
		<div class="orb three"></div>
		<div class="grid"></div>
	</div>

	<main class="wrapper">
		<header class="topbar">
			<div class="brand">
				<span class="brand-badge">PK</span>
				<span>Poliklinik Management</span>
			</div>
			<div class="top-actions">
				<a class="btn btn-soft" href="/register">Register</a>
				<a class="btn btn-main" href="/login">Masuk Dashboard</a>
			</div>
		</header>

		<section class="hero" id="fitur">
			<div class="hero-copy">
				<span class="eyebrow"><i></i> Real-Time Clinic Flow</span>
				<h1>Pengalaman Digital untuk Alur Klinik yang Cepat dan Elegan</h1>
				<p class="lead">
					Kelola poli, jadwal dokter, antrian pasien, pemeriksaan, hingga resep obat dalam satu platform.
					Dibuat untuk menjaga ritme kerja klinik tetap rapi, cepat, dan minim hambatan administrasi.
				</p>

				<div class="cta-row">
					<a class="btn btn-main" href="/login">Mulai Sekarang</a>
					<a class="btn btn-soft" href="#ringkasan">Lihat Ringkasan</a>
				</div>

				<div class="stat-row" id="ringkasan">
					<div class="stat">
						<b>7 Modul</b>
						<span>Domain utama klinik</span>
					</div>
					<div class="stat">
						<b>Realtime</b>
						<span>Sinkron data jadwal dan antrian</span>
					</div>
					<div class="stat">
						<b>Laravel 11</b>
						<span>Fondasi modern dan scalable</span>
					</div>
				</div>
			</div>

			<aside class="hero-panel" aria-label="Preview fitur">
				<div class="panel-glow"></div>
				<div class="panel-content">
					<article class="panel-card">
						<span class="tag">Jadwal Dokter</span>
						<h3>Slot Konsultasi Terstruktur</h3>
						<p>Atur jam praktik per poli dan tampilkan ketersediaan secara jelas untuk mempercepat keputusan pasien.</p>
					</article>

					<article class="panel-card">
						<span class="tag">Antrian Pasien</span>
						<h3>Nomor Antrian Otomatis</h3>
						<p>Pendaftaran pasien langsung terhubung ke jadwal aktif, dengan nomor urut yang konsisten.</p>
					</article>

					<article class="panel-card">
						<span class="tag">Pemeriksaan</span>
						<h3>Riwayat Klinis Lebih Rapi</h3>
						<p>Catat hasil pemeriksaan dan detail tindakan per kunjungan agar monitoring pasien lebih akurat.</p>
					</article>
				</div>
			</aside>
		</section>

		<div class="ticker" aria-hidden="true">
			<div class="ticker-track">
				<span class="ticker-item">POLI - JADWAL - DAFTAR PASIEN - PEMERIKSAAN - RESEP OBAT - LAPORAN -</span>
				<span class="ticker-item">POLI - JADWAL - DAFTAR PASIEN - PEMERIKSAAN - RESEP OBAT - LAPORAN -</span>
			</div>
		</div>

		<footer>
			Poliklinik App - Efficient care, human-centered flow.
		</footer>
	</main>
</body>

</html>
