<?php
/**
 * Pelacakan Hasil Diagnosa Smartphone
 * Kumaphone - Sistem Pakar Forward Chaining
 */
require_once 'includes/database.php';
require_once 'includes/auth.php';
require_once 'includes/diagnosis_engine.php';

$keyword = trim($_GET['q'] ?? $_GET['reg'] ?? $_GET['imei'] ?? '');
$results = [];
$hasSearched = ($keyword !== '');

if ($hasSearched) {
    $results = searchDiagnosisForTracking($db, $keyword);
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Lacak status dan hasil diagnosa kerusakan smartphone Kumaphone dengan nomor registrasi atau nomor IMEI.">
    <meta name="theme-color" content="#0d1b2a">
    <title>Lacak Status Diagnosa - Kumaphone</title>
    <link rel="icon" href="favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500;600&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/public-home.css">
    <link rel="stylesheet" href="assets/css/public-tracking.css">
</head>
<body class="tracking-page">
    <a class="skip-link" href="#content">Langsung ke konten</a>

    <header class="site-header">
        <nav class="nav container" aria-label="Navigasi utama">
            <a class="brand" href="index.html" aria-label="Kumaphone, halaman utama">
                <img src="img/logo.png" alt="" width="38" height="38">
                <span>Kumaphone</span>
                <small>beta</small>
            </a>
            <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="primary-menu">
                <span class="sr-only">Buka menu navigasi</span>
                <span></span>
                <span></span>
            </button>
            <div class="nav-links" id="primary-menu">
                <a href="index.html">Beranda</a>
                <a href="kerusakan.php">Ciri kerusakan</a>
                <a href="diagnosa.php">Diagnosis</a>
                <a class="active" href="tracking.php" aria-current="page">Lacak status</a>
                <a href="about.php">Tentang</a>
                <a class="admin-link" href="login.html">Masuk admin <span aria-hidden="true">↗</span></a>
            </div>
        </nav>
    </header>

    <main id="content">
        <section class="tracking-hero">
            <div class="container">
                <nav class="breadcrumb" aria-label="Breadcrumb">
                    <a href="index.html">Beranda</a>
                    <span aria-hidden="true">/</span>
                    <span aria-current="page">Lacak status diagnosa</span>
                </nav>
                <div class="tracking-header">
                    <p class="eyebrow"><span></span>Pusat pelacakan dokumen teknis</p>
                    <h1>Cari rekam jejak <em>diagnosa.</em></h1>
                    <p>Masukkan Nomor Registrasi Pemeriksaan (contoh: <code>KMP-20240910-0001</code>) atau 15-digit Nomor IMEI smartphone Anda untuk melihat riwayat dan mengunduh ulang laporan resmi PDF.</p>
                </div>
            </div>
        </section>

        <section class="tracking-workspace">
            <div class="container">
                <!-- Search Box -->
                <div class="search-box-card">
                    <p class="eyebrow"><span></span>Pencarian Terpadu</p>
                    <h2>Masukkan Kode Registrasi atau Nomor IMEI</h2>
                    <form method="get" action="tracking.php" class="search-form">
                        <div class="search-input-wrap">
                            <input 
                                type="text" 
                                name="q" 
                                class="search-input" 
                                value="<?= escape($keyword) ?>" 
                                placeholder="Contoh: KMP-20240910-0001 atau 354892104829104"
                                required
                                autofocus
                            >
                        </div>
                        <button type="submit" class="btn-search">
                            <span>Lacak Dokumen</span>
                            <span aria-hidden="true">→</span>
                        </button>
                    </form>
                    <div class="search-hints">
                        <span>Pencarian cepat contoh:</span>
                        <code onclick="document.querySelector('.search-input').value=this.textContent; document.querySelector('.search-form').submit();">KMP-20240910-0001</code>
                        <code onclick="document.querySelector('.search-input').value=this.textContent; document.querySelector('.search-form').submit();">354892104829104</code>
                    </div>
                </div>

                <!-- Search Results -->
                <?php if ($hasSearched): ?>
                    <?php if (!empty($results)): ?>
                        <div class="result-heading" style="margin-bottom: 2rem;">
                            <div>
                                <p class="eyebrow"><span></span>Hasil Pelacakan</p>
                                <h2>Ditemukan <?= count($results) ?> catatan pemeriksaan.</h2>
                            </div>
                        </div>

                        <div class="tracking-results">
                            <?php foreach ($results as $item): 
                                $dateFormatted = !empty($item['created_at']) ? date('d F Y, H:i', strtotime($item['created_at'])) . ' WIB' : 'Tercatat';
                            ?>
                            <article class="tracking-card">
                                <header class="tracking-card-header">
                                    <div>
                                        <span class="reg-badge">
                                            <span><?= escape($item['no_registrasi'] ?? 'ID #' . $item['id']) ?></span>
                                        </span>
                                    </div>
                                    <span class="date-text">Waktu Pemeriksaan: <?= escape($dateFormatted) ?></span>
                                </header>

                                <div class="tracking-info-grid">
                                    <div class="info-item">
                                        <label>Nama Pemilik</label>
                                        <span><?= escape($item['brand']) ?></span>
                                    </div>
                                    <div class="info-item">
                                        <label>Merek &amp; Model</label>
                                        <span><?= escape($item['model']) ?></span>
                                    </div>
                                    <div class="info-item">
                                        <label>Nomor IMEI</label>
                                        <span style="font-family: var(--mono); font-size: 0.82rem;"><?= escape($item['imei']) ?></span>
                                    </div>
                                    <div class="info-item">
                                        <label>Sistem Operasi</label>
                                        <span><span class="platform-tag"><?= escape($item['jenishp']) ?></span></span>
                                    </div>
                                </div>

                                <div class="tracking-diagnosis-box">
                                    <span class="label">Kesimpulan Kerusakan Sistem Pakar (Forward Chaining):</span>
                                    <h3><?= escape($item['namakerusakan'] ?? 'Tidak Teridentifikasi') ?> (Kode: <?= escape($item['idkerusakan']) ?>)</h3>
                                </div>

                                <footer class="tracking-card-footer">
                                    <span style="font-family: var(--mono); font-size: 0.75rem; color: var(--muted);">
                                        Status: Terverifikasi di Lab Kumaphone
                                    </span>
                                    <div class="tracking-actions">
                                        <a href="cetak_diagnosa.php?reg=<?= urlencode($item['no_registrasi']) ?>" target="_blank" class="btn-pdf-track">
                                            <span>Buka &amp; Unduh Laporan PDF</span>
                                            <span aria-hidden="true">↗</span>
                                        </a>
                                        <a href="detailkerusakan1.php?id=<?= urlencode($item['idkerusakan']) ?>" class="btn-detail-track">
                                            <span>Lihat Arahan Perbaikan</span>
                                            <span aria-hidden="true">→</span>
                                        </a>
                                    </div>
                                </footer>
                            </article>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="empty-state" style="background: #ffffff; border: 1px solid var(--line); border-radius: 8px; padding: 3rem 2rem; text-align: center;">
                            <p class="eyebrow"><span></span>Tidak Ditemukan</p>
                            <h2>Pemeriksaan Tidak Ditemukan</h2>
                            <p style="max-width: 500px; margin: 0.5rem auto 1.5rem; color: var(--muted);">
                                Tidak ditemukan data riwayat untuk kata kunci <strong>"<?= escape($keyword) ?>"</strong>. Pastikan format nomor registrasi atau nomor IMEI yang Anda masukkan sudah tepat.
                            </p>
                            <div style="display: flex; justify-content: center; gap: 1rem;">
                                <a href="tracking.php" class="button button-secondary">Ulangi Pencarian</a>
                                <a href="diagnosa.php" class="button button-primary">Mulai Diagnosis Baru <span aria-hidden="true">→</span></a>
                            </div>
                        </div>
                    <?php endif; ?>

                <?php else: ?>
                    <!-- Guide Steps on initial load -->
                    <div style="margin-top: 1rem;">
                        <p class="eyebrow"><span></span>Panduan Pelacakan</p>
                        <h2>Cara Melacak Hasil Pemeriksaan Smartphone</h2>
                        
                        <div class="tracking-steps">
                            <div class="step-card">
                                <span class="step-number">01</span>
                                <h3>Cari Kode Dokumen</h3>
                                <p>Gunakan Nomor Registrasi unik (format <code>KMP-YYYYMMDD-XXXX</code>) yang tertera pada lembar konfirmasi diagnosa Anda.</p>
                            </div>
                            <div class="step-card">
                                <span class="step-number">02</span>
                                <h3>Atau Gunakan IMEI</h3>
                                <p>Jika Anda lupa nomor registrasi, masukkan 15-digit nomor IMEI smartphone Anda yang didaftarkan saat pemeriksaan.</p>
                            </div>
                            <div class="step-card">
                                <span class="step-number">03</span>
                                <h3>Unduh Laporan PDF</h3>
                                <p>Sistem akan menampilkan riwayat kerusakan dan menyediakan berkas laporan PDF resmi siap unduh atau cetak.</p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <div class="container footer-inner">
            <a class="brand" href="index.html">
                <img src="img/logo.png" alt="" width="30" height="30">
                <span>Kumaphone</span>
            </a>
            <p>Diagnosis awal yang membantu teknisi bekerja lebih terarah.</p>
            <div>
                <a href="about.php">Tentang</a>
                <a href="tracking.php">Lacak Status</a>
                <a href="login.html">Admin</a>
            </div>
            <small>© <span id="year"></span> Kumaphone</small>
        </div>
    </footer>

    <script>
      const toggle = document.querySelector('.menu-toggle');
      const menu = document.querySelector('#primary-menu');
      if (toggle && menu) {
        toggle.addEventListener('click', () => {
          const open = toggle.getAttribute('aria-expanded') === 'true';
          toggle.setAttribute('aria-expanded', String(!open));
          menu.classList.toggle('is-open', !open);
        });
      }
      document.querySelector('#year').textContent = new Date().getFullYear();
    </script>
</body>
</html>
