<?php
/**
 * Shared admin sidebar navigation.
 *
 * Expects: $activeMenu (string) — one of 'beranda', 'kerusakan', 'gejala', 'basispengetahuan'
 */
$activeMenu = $activeMenu ?? '';

$menuItems = [
    ['href' => 'homeadmin.php',        'icon' => 'home',       'label' => 'Beranda',            'key' => 'beranda'],
    ['href' => 'daftarkerusakan.php',   'icon' => 'smartphone', 'label' => 'Kerusakan',          'key' => 'kerusakan'],
    ['href' => 'gejala.php',            'icon' => 'list',       'label' => 'Ciri Kerusakan',     'key' => 'gejala'],
    ['href' => 'basispengetahuan.php',  'icon' => 'database',   'label' => 'Basis Pengetahuan',  'key' => 'basispengetahuan'],
];
?>
<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <?php foreach ($menuItems as $item): ?>
            <li class="nav-item">
                <a class="nav-link<?= $activeMenu === $item['key'] ? ' active' : '' ?>"
                   <?= $activeMenu === $item['key'] ? 'aria-current="page"' : '' ?>
                   href="<?= $item['href'] ?>">
                    <span data-feather="<?= $item['icon'] ?>"></span>
                    <?= $item['label'] ?>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</nav>
