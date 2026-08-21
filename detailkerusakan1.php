<?php
require_once 'includes/database.php';
require_once 'includes/auth.php';

$damageId = $_GET['id'] ?? '';
if ($damageId === '') { header('Location: kerusakan.php'); exit; }

$damage = fetchOne($db, 'SELECT * FROM kerusakan WHERE idkerusakan = ?', [$damageId]);
if ($damage === null) { header('Location: kerusakan.php'); exit; }

$symptoms = fetchAll($db, 'SELECT b.gejala FROM kerusakan p JOIN basispengetahuan b ON p.namakerusakan = b.namakerusakan WHERE p.idkerusakan = ?', [$damageId]);
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Detail kerusakan dan arahan penanganan smartphone dari Kumaphone.">
    <meta name="theme-color" content="#0d1b2a">
    <title><?= escape($damage['namakerusakan']) ?> | Kumaphone</title>
    <link rel="icon" href="favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&amp;family=Manrope:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/public-home.css">
    <link rel="stylesheet" href="assets/css/public-detail.css">
</head>
<body class="detail-page">
    <a class="skip-link" href="#content">Langsung ke konten</a>
    <header class="site-header"><nav class="nav container" aria-label="Navigasi utama"><a class="brand" href="index.html" aria-label="Kumaphone, halaman utama"><img src="img/logo.png" alt="" width="38" height="38"><span>Kumaphone</span><small>beta</small></a><button class="menu-toggle" type="button" aria-expanded="false" aria-controls="primary-menu"><span class="sr-only">Buka menu navigasi</span><span></span><span></span></button><div class="nav-links" id="primary-menu"><a href="index.html">Beranda</a><a class="active" href="kerusakan.php" aria-current="page">Ciri kerusakan</a><a href="diagnosa.php">Diagnosis</a><a href="tracking.php">Lacak status</a><a href="about.php">Tentang</a><a class="admin-link" href="login.html">Masuk admin <span aria-hidden="true">↗</span></a></div></nav></header>
    <main id="content">
        <section class="detail-hero"><div class="container"><nav class="breadcrumb" aria-label="Breadcrumb"><a href="index.html">Beranda</a><span aria-hidden="true">/</span><a href="kerusakan.php">Ciri kerusakan</a><span aria-hidden="true">/</span><span aria-current="page">Detail</span></nav><div class="detail-heading"><div><p class="eyebrow"><span></span>Detail kerusakan</p><h1><?= escape($damage['namakerusakan']) ?></h1></div><dl><div><dt>ID kerusakan</dt><dd><?= escape($damage['idkerusakan']) ?></dd></div><div><dt>Sistem operasi</dt><dd><span class="platform-tag"><?= escape($damage['jenishp']) ?></span></dd></div></dl></div></div></section>
        <section class="detail-content"><div class="container detail-grid"><aside class="detail-sidebar"><a class="back-link" href="kerusakan.php"><span aria-hidden="true">←</span> Kembali ke daftar</a><div class="sidebar-note"><p class="eyebrow"><span></span>Catatan pemeriksaan</p><p>Cocokkan gejala berikut dengan kondisi perangkat sebelum menentukan tindakan perbaikan.</p></div></aside><div class="detail-main"><section class="detail-block"><div class="block-heading"><p class="eyebrow"><span></span>Gejala terkait</p><h2>Yang perlu diperiksa.</h2></div><?php if ($symptoms): ?><ol class="symptom-detail-list"><?php foreach ($symptoms as $index => $symptom): ?><li><span><?= str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) ?></span><p><?= escape($symptom['gejala']) ?></p><b aria-hidden="true">↗</b></li><?php endforeach; ?></ol><?php else: ?><p class="empty-detail">Belum ada ciri kerusakan yang terdaftar untuk kerusakan ini.</p><?php endif; ?></section><section class="solution-block"><p class="eyebrow"><span></span>Arahan penanganan</p><h2>Langkah perbaikan yang disarankan.</h2><div class="solution-copy"><?= nl2br(escape($damage['caramengatasi'] ?? 'Belum ada arahan penanganan yang tersedia.')) ?></div><div class="solution-actions"><a class="button button-primary" href="diagnosa.php">Mulai diagnosis <span aria-hidden="true">→</span></a><a class="text-link" href="kerusakan.php">Lihat kerusakan lain <span aria-hidden="true">↗</span></a></div></section></div></div></section>
    </main>
    <footer class="site-footer"><div class="container footer-inner"><a class="brand" href="index.html"><img src="img/logo.png" alt="" width="30" height="30"><span>Kumaphone</span></a><p>Diagnosis awal yang membantu teknisi bekerja lebih terarah.</p><div><a href="about.php">Tentang</a><a href="tracking.php">Lacak Status</a><a href="login.html">Admin</a></div><small>© <span id="year"></span> Kumaphone</small></div></footer>
    <script>const toggle=document.querySelector('.menu-toggle'),menu=document.querySelector('#primary-menu');toggle.addEventListener('click',()=>{const open=toggle.getAttribute('aria-expanded')==='true';toggle.setAttribute('aria-expanded',String(!open));menu.classList.toggle('is-open',!open)});document.querySelector('#year').textContent=new Date().getFullYear();</script>
</body>
</html>
