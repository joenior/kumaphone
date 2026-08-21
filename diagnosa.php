<?php
/**
 * Guided Diagnosis Page - Forward Chaining Expert System
 *
 * Evaluates symptoms against the knowledge base, computes coverage scores,
 * ranks potential damage conclusions, and records history.
 */
require_once 'includes/database.php';
require_once 'includes/auth.php';
require_once 'includes/diagnosis_engine.php';

$operatingSystem = 'Android';
$symptoms = fetchSymptomsByOS($db, $operatingSystem);
$diagnosisResults = [];
$showSuccess = false;
$errorMessage = '';
$customerName = '';
$brandModel = '';
$imei = '';
$selectedGejala = [];
$savedRecordId = 0;
$savedRegCode = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customerName = trim($_POST['brand'] ?? '');
    $brandModel = trim($_POST['model'] ?? '');
    $imei = trim($_POST['imei'] ?? '');
    $selectedOS = $_POST['jenishp'] ?? $operatingSystem;
    $selectedGejala = is_array($_POST['gejala'] ?? null) ? $_POST['gejala'] : [];

    if ($customerName === '' || $brandModel === '' || $imei === '' || empty($selectedGejala)) {
        $errorMessage = 'Mohon lengkapi data perangkat dan pilih minimal satu ciri kerusakan.';
    } else {
        $diagnosisResults = runForwardChaining($db, $selectedGejala);
        if (!empty($diagnosisResults)) {
            // Save top-ranked (highest match) diagnosis to history
            $topDiagnosis = $diagnosisResults[0];
            $saveResult = saveDiagnosisRecord($db, $customerName, $brandModel, $imei, $selectedOS, $topDiagnosis['idkerusakan']);
            if ($saveResult && is_array($saveResult)) {
                $savedRecordId = $saveResult['id'];
                $savedRegCode = $saveResult['no_registrasi'];
                $showSuccess = true;
            } elseif ($saveResult) {
                $savedRecordId = (int)$saveResult;
                $showSuccess = true;
            }
        } else {
            $errorMessage = 'Tidak ditemukan indikasi kerusakan yang cocok dengan kombinasi ciri-ciri yang dipilih.';
        }
    }
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Diagnosis kerusakan smartphone Android menggunakan sistem pakar forward chaining Kumaphone.">
    <meta name="theme-color" content="#0d1b2a">
    <title>Diagnosis Kerusakan - Kumaphone</title>
    <link rel="icon" href="favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&amp;family=Manrope:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/public-home.css">
    <link rel="stylesheet" href="assets/css/public-diagnosis.css">
</head>
<body class="diagnosis-page">
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
                <a class="active" href="diagnosa.php" aria-current="page">Diagnosis</a>
                <a href="tracking.php">Lacak status</a>
                <a href="about.php">Tentang</a>
                <a class="admin-link" href="login.html">Masuk admin <span aria-hidden="true">↗</span></a>
            </div>
        </nav>
    </header>

    <main id="content">
        <section class="diagnosis-intro">
            <div class="container">
                <nav class="breadcrumb" aria-label="Breadcrumb">
                    <a href="index.html">Beranda</a>
                    <span aria-hidden="true">/</span>
                    <span aria-current="page">Diagnosis</span>
                </nav>
                <div class="diagnosis-heading">
                    <div>
                        <p class="eyebrow"><span></span>Diagnosis terpandu forward chaining</p>
                        <h1>Mulai dari apa yang <em>terlihat.</em></h1>
                    </div>
                    <p>Isi data perangkat, lalu pilih gejala yang ditemukan pada smartphone. Mesin inferensi akan menelusuri aturan basis pengetahuan dan menyusun kemungkinan kerusakan berdasarkan derajat kecocokan.</p>
                </div>
            </div>
        </section>

        <section class="diagnosis-workspace">
            <div class="container">
                <form id="diagnosis-form" method="post" action="diagnosa.php" class="diagnosis-form">
                    <input type="hidden" name="jenishp" value="Android">

                    <aside class="form-intro">
                        <p class="eyebrow"><span></span>Langkah 01</p>
                        <h2>Informasi perangkat</h2>
                        <p>Data ini dicatat bersama hasil diagnosis untuk memudahkan pelacakan pemeriksaan teknis.</p>
                        <div class="form-tip">
                            <span>i</span>
                            <p>Pilih gejala sesuai kondisi riil peranti. Ketepatan input menghasilkan akurasi kesimpulan yang optimal.</p>
                        </div>
                    </aside>

                    <div class="form-fields">
                        <div class="field">
                            <label for="brand">Nama pelanggan</label>
                            <input id="brand" name="brand" type="text" value="<?= escape($customerName) ?>" placeholder="Nama pelanggan" required>
                        </div>
                        <div class="field">
                            <label for="model">Merek dan model smartphone</label>
                            <input id="model" name="model" type="text" value="<?= escape($brandModel) ?>" placeholder="Contoh: Samsung Galaxy A54" required>
                        </div>
                        <div class="field">
                            <label for="imei">IMEI smartphone</label>
                            <input id="imei" name="imei" type="text" value="<?= escape($imei) ?>" inputmode="numeric" maxlength="16" placeholder="Nomor IMEI perangkat (15 digit)" required>
                        </div>
                        <div class="field readonly-field">
                            <label>Sistem operasi</label>
                            <p><span class="platform-tag">Android</span></p>
                        </div>
                    </div>

                    <div class="symptom-section">
                        <div class="symptom-heading">
                            <div>
                                <p class="eyebrow"><span></span>Langkah 02</p>
                                <h2>Pilih ciri kerusakan</h2>
                            </div>
                            <p id="selection-count">Belum ada gejala dipilih</p>
                        </div>

                        <?php if ($errorMessage): ?>
                            <div class="form-alert error" role="alert"><?= escape($errorMessage) ?></div>
                        <?php endif; ?>

                        <div class="symptom-grid">
                            <?php foreach ($symptoms as $index => $symptom): 
                                $value = (string) ($symptom['gejala'] ?? ''); 
                                $inputId = 'symptom-' . ($index + 1); 
                            ?>
                            <label class="symptom-option" for="<?= $inputId ?>">
                                <input id="<?= $inputId ?>" type="checkbox" name="gejala[]" value="<?= escape($value) ?>" <?= in_array($value, $selectedGejala, true) ? 'checked' : '' ?>>
                                <span class="custom-check" aria-hidden="true">✓</span>
                                <span><?= escape($value) ?></span>
                            </label>
                            <?php endforeach; ?>
                        </div>

                        <?php if (!$symptoms): ?>
                            <div class="empty-state">
                                <p class="eyebrow"><span></span>Belum tersedia</p>
                                <h2>Belum ada data gejala untuk Android.</h2>
                                <p>Silakan hubungi administrator untuk memperbarui basis pengetahuan.</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="submit-row">
                        <p>Dengan mengirim formulir ini, mesin inferensi akan mencocokkan gejala dan menyimpan rekomendasi utama ke riwayat pemeriksaan.</p>
                        <button class="button button-primary" type="submit" name="submit">Jalankan inferensi <span aria-hidden="true">→</span></button>
                    </div>
                </form>

                <?php if ($showSuccess || !empty($diagnosisResults)): ?>
                <section class="diagnosis-result" aria-live="polite">
                    <div class="result-heading">
                        <div>
                            <?php if ($showSuccess): ?>
                                <p class="result-status"><span>✓</span> Hasil inferensi forward chaining berhasil diproses &amp; dicatat</p>
                            <?php endif; ?>
                            <p class="eyebrow"><span></span>Hasil inferensi forward chaining</p>
                            <h2>Kesimpulan diagnosis.</h2>
                        </div>
                        <?php if ($savedRecordId > 0): ?>
                        <div class="result-heading-action">
                            <a href="cetak_diagnosa.php?id=<?= $savedRecordId ?>" target="_blank" class="button-pdf-export">
                                <span>Cetak / Unduh Laporan PDF</span>
                                <span aria-hidden="true">↗</span>
                            </a>
                        </div>
                        <?php else: ?>
                        <p>Hasil diurutkan dari persentase kecocokan aturan tertinggi. Gunakan rekomendasi utama sebagai panduan pemeriksaan awal.</p>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($savedRegCode)): ?>
                    <div class="reg-tracking-callout" style="background: rgba(182, 204, 120, 0.12); border: 1px solid rgba(182, 204, 120, 0.35); border-radius: 6px; padding: 1.25rem 1.5rem; margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                        <div>
                            <span style="font-family: var(--mono); font-size: 0.72rem; color: var(--lime); text-transform: uppercase; font-weight: 700; display: block; margin-bottom: 0.25rem;">Nomor Registrasi Diagnosa Anda:</span>
                            <code style="font-family: var(--mono); font-size: 1.25rem; font-weight: 800; color: #ffffff; letter-spacing: 0.05em;"><?= escape($savedRegCode) ?></code>
                            <p style="margin: 0.35rem 0 0; font-size: 0.78rem; color: #b8c3be;">Simpan kode ini untuk melacak status atau mencetak ulang laporan kapan saja di halaman <a href="tracking.php?q=<?= urlencode($savedRegCode) ?>" target="_blank" style="color: var(--lime); font-weight: 700; text-decoration: underline;">Lacak Status</a>.</p>
                        </div>
                        <div>
                            <a href="tracking.php?q=<?= urlencode($savedRegCode) ?>" target="_blank" class="link-pdf-export" style="background: rgba(255, 255, 255, 0.1); border-color: rgba(255, 255, 255, 0.3); color: #ffffff;">
                                <span>Lacak di Sistem</span>
                                <span aria-hidden="true">↗</span>
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="result-list">
                        <?php foreach ($diagnosisResults as $index => $result): 
                            $isTop = ($index === 0);
                            $percentage = $result['match_percentage'];
                            $isExact = $result['is_exact_match'];
                        ?>
                        <article class="result-card <?= $isTop ? 'primary-match' : '' ?>">
                            <div class="result-card-header">
                                <div>
                                    <span class="result-rank-badge">
                                        <?= $isTop ? 'Rekomendasi Utama (Peringkat 01)' : 'Peringkat ' . sprintf('%02d', $index + 1) ?>
                                    </span>
                                    <h3 class="result-title"><?= escape($result['namakerusakan']) ?></h3>
                                    <p class="score-subtext">ID: <?= escape($result['idkerusakan']) ?> | Kategori: <?= escape($result['jenishp']) ?></p>
                                </div>
                                <div class="result-score-box">
                                    <span class="score-badge <?= $isExact ? 'exact' : '' ?>">
                                        <?= $percentage ?>% Kecocokan
                                    </span>
                                    <p class="score-subtext"><?= $result['matched_count'] ?> dari <?= $result['total_rules'] ?> syarat aturan terpenuhi</p>
                                </div>
                            </div>

                            <!-- Explanation Facility -->
                            <div class="explanation-block">
                                <p class="explanation-title">Rincian inferensi aturan (Basis Pengetahuan):</p>
                                <div class="symptom-tags">
                                    <?php foreach ($result['matched_symptoms'] as $symptom): ?>
                                        <span class="tag-matched" title="Gejala cocok dengan fakta yang Anda pilih">
                                            ✓ <?= escape($symptom) ?>
                                        </span>
                                    <?php endforeach; ?>
                                    <?php foreach ($result['unmatched_symptoms'] as $symptom): ?>
                                        <span class="tag-unmatched" title="Syarat aturan yang tidak dipilih">
                                            ○ <?= escape($symptom) ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="result-card-footer">
                                <span class="score-subtext">
                                    <?= $isExact ? 'Semua gejala aturan terpenuhi sempurna (Exact Match).' : 'Sebagian gejala aturan terpenuhi.' ?>
                                </span>
                                <div class="result-footer-actions">
                                    <?php if ($isTop && $savedRecordId > 0): ?>
                                    <a href="cetak_diagnosa.php?id=<?= $savedRecordId ?>" target="_blank" class="link-pdf-export">
                                        <span>Cetak Lembar Diagnosa</span>
                                    </a>
                                    <?php endif; ?>
                                    <a href="detailkerusakan1.php?id=<?= urlencode($result['idkerusakan']) ?>">
                                        <span>Buka panduan perbaikan</span>
                                        <b aria-hidden="true">→</b>
                                    </a>
                                </div>
                            </div>
                        </article>
                        <?php endforeach; ?>
                    </div>
                </section>
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

      // Live symptom count badge
      const count = document.querySelector('#selection-count');
      const boxes = [...document.querySelectorAll('.symptom-option input')];
      function updateCount() {
        const selected = boxes.filter(box => box.checked).length;
        count.textContent = selected ? `${selected} gejala dipilih` : 'Belum ada gejala dipilih';
      }
      boxes.forEach(box => box.addEventListener('change', updateCount));
      updateCount();
    </script>
</body>
</html>
