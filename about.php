<?php
require_once 'includes/database.php';
require_once 'includes/auth.php';
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Tentang Kumaphone, sistem pakar diagnosis awal kerusakan smartphone Android.">
    <meta name="theme-color" content="#0d1b2a">
    <title>Tentang Kumaphone</title>
    <link rel="icon" href="favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&amp;family=Manrope:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/public-home.css">
    <link rel="stylesheet" href="assets/css/public-about.css">
</head>
<body class="about-page">
    <a class="skip-link" href="#content">Langsung ke konten</a>
    <header class="site-header"><nav class="nav container" aria-label="Navigasi utama"><a class="brand" href="index.html" aria-label="Kumaphone, halaman utama"><img src="img/logo.png" alt="" width="38" height="38"><span>Kumaphone</span><small>beta</small></a><button class="menu-toggle" type="button" aria-expanded="false" aria-controls="primary-menu"><span class="sr-only">Buka menu navigasi</span><span></span><span></span></button><div class="nav-links" id="primary-menu"><a href="index.html">Beranda</a><a href="kerusakan.php">Ciri kerusakan</a><a href="diagnosa.php">Diagnosis</a><a href="tracking.php">Lacak status</a><a class="active" href="about.php" aria-current="page">Tentang</a><a class="admin-link" href="login.html">Masuk admin <span aria-hidden="true">↗</span></a></div></nav></header>
    <main id="content">
        <section class="about-hero"><div class="container"><nav class="breadcrumb" aria-label="Breadcrumb"><a href="index.html">Beranda</a><span aria-hidden="true">/</span><span aria-current="page">Tentang</span></nav><div class="about-heading"><p class="eyebrow"><span></span>Tentang Kumaphone</p><h1>Diagnosis awal yang dibuat untuk <em>kerja lapangan.</em></h1><p>Kumaphone adalah sistem pakar yang membantu teknisi menelusuri kemungkinan kerusakan smartphone Android melalui gejala yang teramati.</p></div><div class="about-mark" aria-hidden="true"><span>K</span><i>01</i></div></div></section>
        <section class="about-story"><div class="container story-grid"><div><p class="eyebrow"><span></span>Prinsip kerja</p><h2>Memberi titik awal yang lebih terarah.</h2></div><div class="story-copy"><p>Kami membangun Kumaphone untuk merapikan proses identifikasi awal: dari keluhan dan gejala perangkat menjadi kemungkinan kerusakan yang dapat diperiksa lebih lanjut.</p><p>Sistem menggunakan metode <strong>Forward Chaining</strong> untuk mencocokkan ciri yang dipilih dengan basis pengetahuan. Hasilnya membantu menyusun langkah pemeriksaan, bukan menggantikan penilaian teknisi.</p><a class="button button-dark" href="diagnosa.php">Mulai diagnosis <span aria-hidden="true">→</span></a></div></div></section>
        <section class="team-section"><div class="container"><div class="team-heading"><p class="eyebrow"><span></span>Orang di balik proyek</p><h2>Dibuat dengan rasa ingin tahu, ketelitian, dan banyak pengujian.</h2></div><div class="team-grid"><article class="team-member member-primary"><div class="member-image"><img src="img/steven.jpg" alt="Steven J. Naibaho"><span>01</span></div><div class="member-copy"><p class="member-role">Developer</p><h3>Steven J. Naibaho</h3><p>“Kesabaran adalah kekuatan yang tak ternilai harganya.”</p><div class="member-links"><a href="https://instagram.com/stevenjoe24" target="_blank" rel="noopener noreferrer">Instagram <span aria-hidden="true">↗</span></a><a href="https://github.com/joenior" target="_blank" rel="noopener noreferrer">GitHub <span aria-hidden="true">↗</span></a></div></div></article><article class="team-member member-secondary"><div class="member-image"><img src="img/rizki.png" alt="Rizki Febryano"><span>02</span></div><div class="member-copy"><p class="member-role">Co-developer</p><h3>Rizki Febryano</h3><p>“Technology is a useful servant but a dangerous master.”</p><div class="member-links"><a href="https://instagram.com/rizkiifebryano" target="_blank" rel="noopener noreferrer">Instagram <span aria-hidden="true">↗</span></a></div></div></article></div></div></section>
        <section class="about-cta"><div class="container"><p class="eyebrow"><span></span>Siap memeriksa?</p><h2>Mulai dengan gejala yang Anda lihat pada perangkat.</h2><a class="button button-primary" href="diagnosa.php">Buka diagnosis <span aria-hidden="true">→</span></a></div></section>
    </main>
    <footer class="site-footer"><div class="container footer-inner"><a class="brand" href="index.html"><img src="img/logo.png" alt="" width="30" height="30"><span>Kumaphone</span></a><p>Diagnosis awal yang membantu teknisi bekerja lebih terarah.</p><div><a href="about.php">Tentang</a><a href="tracking.php">Lacak Status</a><a href="login.html">Admin</a></div><small>© <span id="year"></span> Kumaphone</small></div></footer>
    <script>const toggle=document.querySelector('.menu-toggle'),menu=document.querySelector('#primary-menu');toggle.addEventListener('click',()=>{const open=toggle.getAttribute('aria-expanded')==='true';toggle.setAttribute('aria-expanded',String(!open));menu.classList.toggle('is-open',!open)});document.querySelector('#year').textContent=new Date().getFullYear();</script>
</body>
</html>
