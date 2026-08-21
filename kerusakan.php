<?php
require_once 'includes/database.php';
require_once 'includes/auth.php';

$operatingSystem = 'Android';
$damageRecords = fetchAll($db, 'SELECT * FROM kerusakan WHERE jenishp = ?', [$operatingSystem]);
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Daftar ciri kerusakan smartphone Android yang tersedia di Kumaphone.">
    <meta name="theme-color" content="#0d1b2a">
    <title>Ciri kerusakan | Kumaphone</title>
    <link rel="icon" href="favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&amp;family=Manrope:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/public-home.css">
    <link rel="stylesheet" href="assets/css/public-directory.css">
</head>
<body class="directory-page">
    <a class="skip-link" href="#content">Langsung ke konten</a>
    <header class="site-header">
        <nav class="nav container" aria-label="Navigasi utama">
            <a class="brand" href="index.html" aria-label="Kumaphone, halaman utama"><img src="img/logo.png" alt="" width="38" height="38"><span>Kumaphone</span><small>beta</small></a>
            <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="primary-menu"><span class="sr-only">Buka menu navigasi</span><span></span><span></span></button>
            <div class="nav-links" id="primary-menu"><a href="index.html">Beranda</a><a class="active" href="kerusakan.php" aria-current="page">Ciri kerusakan</a><a href="diagnosa.php">Diagnosis</a><a href="tracking.php">Lacak status</a><a href="about.php">Tentang</a><a class="admin-link" href="login.html">Masuk admin <span aria-hidden="true">↗</span></a></div>
        </nav>
    </header>
    <main id="content">
        <section class="directory-intro"><div class="container">
            <nav class="breadcrumb" aria-label="Breadcrumb"><a href="index.html">Beranda</a><span aria-hidden="true">/</span><span aria-current="page">Ciri kerusakan</span></nav>
            <div class="directory-heading"><div><p class="eyebrow"><span></span>Basis pengetahuan</p><h1>Ciri kerusakan <em>Android.</em></h1></div><p>Gunakan daftar ini untuk mengenali jenis gangguan yang tercatat di sistem sebelum memulai proses diagnosis.</p></div>
        </div></section>
        <section class="directory-content"><div class="container">
            <div class="directory-toolbar"><p><strong><?= count($damageRecords) ?></strong> jenis kerusakan tersedia</p><label class="search-control"><span class="sr-only">Cari jenis kerusakan</span><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m21 21-4.35-4.35m2.35-5.15a7.5 7.5 0 1 1-15 0 7.5 7.5 0 0 1 15 0Z"/></svg><input id="damage-search" type="search" placeholder="Cari jenis kerusakan" autocomplete="off"></label></div>
            <?php if ($damageRecords): ?>
            <div class="table-wrap"><table class="damage-table"><thead><tr><th scope="col">No.</th><th scope="col">Jenis kerusakan</th><th scope="col">Perangkat</th><th scope="col"><span class="sr-only">Lihat detail</span></th></tr></thead><tbody>
                <?php foreach ($damageRecords as $index => $record): ?>
                <tr data-damage-row><td data-label="No."><?= str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) ?></td><td data-label="Jenis kerusakan"><strong><?= escape($record['namakerusakan'] ?? '') ?></strong></td><td data-label="Perangkat"><span class="platform-tag"><?= escape($record['jenishp'] ?? '') ?></span></td><td class="detail-cell"><a class="detail-link" href="detailkerusakan1.php?id=<?= urlencode($record['idkerusakan']) ?>"><span>Lihat detail</span><b aria-hidden="true">→</b></a></td></tr>
                <?php endforeach; ?>
            </tbody></table></div>
            <p class="no-results" hidden>Tidak ada kerusakan yang cocok dengan pencarian Anda.</p>
            <?php else: ?>
            <div class="empty-state"><p class="eyebrow"><span></span>Belum tersedia</p><h2>Daftar kerusakan masih kosong.</h2><p>Silakan kembali lagi setelah basis pengetahuan diperbarui.</p></div>
            <?php endif; ?>
            <aside class="directory-note"><span>Catatan</span><p>Hasil diagnosis merupakan arahan awal. Periksa kondisi fisik perangkat dan lakukan pengujian lanjutan sebelum melakukan perbaikan.</p><a href="diagnosa.php">Mulai diagnosis <span aria-hidden="true">→</span></a></aside>
        </div></section>
    </main>
    <footer class="site-footer"><div class="container footer-inner"><a class="brand" href="index.html"><img src="img/logo.png" alt="" width="30" height="30"><span>Kumaphone</span></a><p>Diagnosis awal yang membantu teknisi bekerja lebih terarah.</p><div><a href="about.php">Tentang</a><a href="login.html">Admin</a></div><small>© <span id="year"></span> Kumaphone</small></div></footer>
    <script>
      const toggle=document.querySelector('.menu-toggle'),menu=document.querySelector('#primary-menu');
      toggle.addEventListener('click',()=>{const open=toggle.getAttribute('aria-expanded')==='true';toggle.setAttribute('aria-expanded',String(!open));menu.classList.toggle('is-open',!open)});
      document.querySelector('#year').textContent=new Date().getFullYear();
      const search=document.querySelector('#damage-search'),rows=[...document.querySelectorAll('[data-damage-row]')],noResults=document.querySelector('.no-results');
      if(search) search.addEventListener('input',()=>{const term=search.value.trim().toLocaleLowerCase('id');let shown=0;rows.forEach(row=>{const match=row.textContent.toLocaleLowerCase('id').includes(term);row.hidden=!match;if(match)shown++});noResults.hidden=shown!==0});
    </script>
</body>
</html>
