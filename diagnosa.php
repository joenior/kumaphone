<?php
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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $customerName = trim($_POST['brand'] ?? '');
    $brandModel = trim($_POST['model'] ?? '');
    $imei = trim($_POST['imei'] ?? '');
    $selectedOS = $_POST['jenishp'] ?? $operatingSystem;
    $selectedGejala = is_array($_POST['gejala'] ?? null) ? $_POST['gejala'] : [];

    if ($customerName === '' || $brandModel === '' || $imei === '' || empty($selectedGejala)) {
        $errorMessage = 'Mohon isi semua kolom dan pilih minimal satu ciri kerusakan.';
    } else {
        $diagnosisResults = runForwardChaining($db, $selectedGejala);
        if ($diagnosisResults) {
            saveDiagnosisRecord($db, $customerName, $brandModel, $imei, $selectedOS, $diagnosisResults[0]['idkerusakan']);
            $showSuccess = true;
        } else {
            $errorMessage = 'Tidak ditemukan kerusakan yang cocok dengan ciri-ciri yang dipilih.';
        }
    }
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Diagnosis awal kerusakan smartphone Android menggunakan basis pengetahuan Kumaphone.">
    <meta name="theme-color" content="#0d1b2a">
    <title>Diagnosis kerusakan | Kumaphone</title>
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
            <a class="brand" href="index.html" aria-label="Kumaphone, halaman utama"><img src="img/logo.png" alt="" width="38" height="38"><span>Kumaphone</span><small>beta</small></a>
            <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="primary-menu"><span class="sr-only">Buka menu navigasi</span><span></span><span></span></button>
            <div class="nav-links" id="primary-menu"><a href="index.html">Beranda</a><a href="kerusakan.php">Ciri kerusakan</a><a class="active" href="diagnosa.php" aria-current="page">Diagnosis</a><a href="about.php">Tentang</a><a class="admin-link" href="login.html">Masuk admin <span aria-hidden="true">↗</span></a></div>
        </nav>
    </header>
    <main id="content">
        <section class="diagnosis-intro"><div class="container"><nav class="breadcrumb" aria-label="Breadcrumb"><a href="index.html">Beranda</a><span aria-hidden="true">/</span><span aria-current="page">Diagnosis</span></nav><div class="diagnosis-heading"><div><p class="eyebrow"><span></span>Diagnosis terpandu</p><h1>Mulai dari apa yang <em>terlihat.</em></h1></div><p>Isi data perangkat, lalu pilih gejala yang benar-benar Anda temukan. Sistem akan menelusuri kemungkinan kerusakan dari basis pengetahuan.</p></div></div></section>
        <section class="diagnosis-workspace"><div class="container">
            <form id="diagnosis-form" method="post" action="diagnosa.php" class="diagnosis-form">
                <input type="hidden" name="jenishp" value="Android">
                <aside class="form-intro"><p class="eyebrow"><span></span>Langkah 01</p><h2>Informasi perangkat</h2><p>Data ini dicatat bersama hasil diagnosis untuk memudahkan pelacakan pemeriksaan.</p><div class="form-tip"><span>i</span><p>Pilih gejala seperlunya. Jawaban yang tepat menghasilkan arahan yang lebih relevan.</p></div></aside>
                <div class="form-fields"><div class="field"><label for="brand">Nama pelanggan</label><input id="brand" name="brand" type="text" value="<?= escape($customerName) ?>" placeholder="Nama pelanggan" required></div><div class="field"><label for="model">Merek dan model smartphone</label><input id="model" name="model" type="text" value="<?= escape($brandModel) ?>" placeholder="Contoh: Samsung Galaxy A54" required></div><div class="field"><label for="imei">IMEI smartphone</label><input id="imei" name="imei" type="text" value="<?= escape($imei) ?>" inputmode="numeric" maxlength="16" placeholder="Nomor IMEI perangkat" required></div><div class="field readonly-field"><label>Sistem operasi</label><p><span class="platform-tag">Android</span></p></div></div>
                <div class="symptom-section"><div class="symptom-heading"><div><p class="eyebrow"><span></span>Langkah 02</p><h2>Pilih ciri kerusakan</h2></div><p id="selection-count">Belum ada gejala dipilih</p></div>
                    <?php if ($errorMessage): ?><div class="form-alert error" role="alert"><?= escape($errorMessage) ?></div><?php endif; ?>
                    <div class="symptom-grid">
                    <?php foreach ($symptoms as $index => $symptom): $value = (string) ($symptom['gejala'] ?? ''); $inputId = 'symptom-' . ($index + 1); ?>
                        <label class="symptom-option" for="<?= $inputId ?>"><input id="<?= $inputId ?>" type="checkbox" name="gejala[]" value="<?= escape($value) ?>" <?= in_array($value, $selectedGejala, true) ? 'checked' : '' ?>><span class="custom-check" aria-hidden="true">✓</span><span><?= escape($value) ?></span></label>
                    <?php endforeach; ?>
                    </div>
                    <?php if (!$symptoms): ?><div class="empty-state"><p class="eyebrow"><span></span>Belum tersedia</p><h2>Belum ada gejala untuk Android.</h2><p>Silakan kembali setelah basis pengetahuan diperbarui.</p></div><?php endif; ?>
                </div>
                <div class="submit-row"><p>Dengan mengirim formulir ini, hasil diagnosis akan dicatat sebagai riwayat pemeriksaan.</p><button class="button button-primary" type="submit" name="submit">Analisis kerusakan <span aria-hidden="true">→</span></button></div>
            </form>
            <?php if ($showSuccess || $diagnosisResults): ?><section class="diagnosis-result" aria-live="polite"><div class="result-heading"><div><?php if ($showSuccess): ?><p class="result-status"><span>✓</span> Data pemeriksaan tercatat</p><?php endif; ?><p class="eyebrow"><span></span>Hasil analisis</p><h2>Kemungkinan kerusakan.</h2></div><p>Gunakan hasil ini sebagai dasar pemeriksaan berikutnya sebelum melakukan tindakan perbaikan.</p></div><div class="result-list"><?php foreach ($diagnosisResults as $index => $result): ?><article><span><?= str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) ?></span><div><h3><?= escape($result['namakerusakan']) ?></h3><p><span class="platform-tag"><?= escape($result['jenishp']) ?></span> ID: <?= escape($result['idkerusakan']) ?></p></div><a href="hasildiagnosa.php?id=<?= urlencode($result['idkerusakan']) ?>">Buka detail <b aria-hidden="true">→</b></a></article><?php endforeach; ?></div></section><?php endif; ?>
        </div></section>
    </main>
    <footer class="site-footer"><div class="container footer-inner"><a class="brand" href="index.html"><img src="img/logo.png" alt="" width="30" height="30"><span>Kumaphone</span></a><p>Diagnosis awal yang membantu teknisi bekerja lebih terarah.</p><div><a href="about.php">Tentang</a><a href="login.html">Admin</a></div><small>© <span id="year"></span> Kumaphone</small></div></footer>
    <script>
      const toggle=document.querySelector('.menu-toggle'),menu=document.querySelector('#primary-menu');toggle.addEventListener('click',()=>{const open=toggle.getAttribute('aria-expanded')==='true';toggle.setAttribute('aria-expanded',String(!open));menu.classList.toggle('is-open',!open)});document.querySelector('#year').textContent=new Date().getFullYear();
      const count=document.querySelector('#selection-count'),boxes=[...document.querySelectorAll('.symptom-option input')];function updateCount(){const selected=boxes.filter(box=>box.checked).length;count.textContent=selected?`${selected} gejala dipilih`:'Belum ada gejala dipilih'}boxes.forEach(box=>box.addEventListener('change',updateCount));updateCount();
    </script>
</body>
</html>
