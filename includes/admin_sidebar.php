<?php
/**
 * Shared admin sidebar navigation.
 *
 * Expects: $activeMenu (string) - one of 'beranda', 'histori', 'kerusakan', 'gejala', 'basispengetahuan'
 */
$activeMenu = $activeMenu ?? '';

$menuItems = [
    ['href' => 'homeadmin.php',        'icon' => 'bi-speedometer2',        'label' => 'Beranda',            'key' => 'beranda'],
    ['href' => 'histori.php',          'icon' => 'bi-clock-history',       'label' => 'Histori Diagnosa',   'key' => 'histori'],
    ['href' => 'daftarkerusakan.php',   'icon' => 'bi-phone',               'label' => 'Kerusakan',          'key' => 'kerusakan'],
    ['href' => 'gejala.php',            'icon' => 'bi-clipboard2-pulse',    'label' => 'Ciri Kerusakan',     'key' => 'gejala'],
    ['href' => 'basispengetahuan.php',  'icon' => 'bi-diagram-3',           'label' => 'Basis Pengetahuan',  'key' => 'basispengetahuan'],
];
?>
<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block admin-sidebar collapse">
    <span class="sidebar-heading-label">Menu Utama</span>
    <ul class="admin-nav-list">
        <?php foreach ($menuItems as $item): ?>
        <li class="admin-nav-item">
            <a class="admin-nav-link<?= $activeMenu === $item['key'] ? ' active' : '' ?>"
               <?= $activeMenu === $item['key'] ? 'aria-current="page"' : '' ?>
               href="<?= $item['href'] ?>">
                <span class="admin-nav-link-content">
                    <i class="bi <?= $item['icon'] ?>"></i>
                    <span><?= $item['label'] ?></span>
                </span>
                <?php if ($activeMenu === $item['key']): ?>
                    <span class="admin-nav-badge">Aktif</span>
                <?php endif; ?>
            </a>
        </li>
        <?php endforeach; ?>
    </ul>

    <span class="sidebar-heading-label">Laporan &amp; Cetak</span>
    <ul class="admin-nav-list">
        <li class="admin-nav-item">
            <a class="admin-nav-link" href="cetak_histori.php" target="_blank" rel="noopener noreferrer">
                <span class="admin-nav-link-content">
                    <i class="bi bi-printer"></i>
                    <span>Rekapitulasi PDF</span>
                </span>
                <span class="admin-nav-badge">^</span>
            </a>
        </li>
    </ul>

    <span class="sidebar-heading-label">Sistem &amp; Navigasi</span>
    <ul class="admin-nav-list">
        <li class="admin-nav-item">
            <a class="admin-nav-link" href="tracking.php" target="_blank" rel="noopener noreferrer">
                <span class="admin-nav-link-content">
                    <i class="bi bi-search"></i>
                    <span>Lacak Diagnosa</span>
                </span>
                <span class="admin-nav-badge">^</span>
            </a>
        </li>
        <li class="admin-nav-item">
            <a class="admin-nav-link" href="diagnosa.php" target="_blank" rel="noopener noreferrer">
                <span class="admin-nav-link-content">
                    <i class="bi bi-cpu"></i>
                    <span>Tes Diagnosis</span>
                </span>
                <span class="admin-nav-badge">^</span>
            </a>
        </li>
        <li class="admin-nav-item">
            <a class="admin-nav-link" href="index.html" target="_blank" rel="noopener noreferrer">
                <span class="admin-nav-link-content">
                    <i class="bi bi-box-arrow-up-right"></i>
                    <span>Halaman Publik</span>
                </span>
                <span class="admin-nav-badge">^</span>
            </a>
        </li>
    </ul>
</nav>
