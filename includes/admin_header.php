<?php
/**
 * Shared admin page header (top navbar).
 *
 * Expects: $adminName (string|null) - the logged-in admin's display name
 */
$adminName = $adminName ?? 'Admin';
$adminInitials = strtoupper(substr($adminName, 0, 2));
?>
<header class="admin-navbar">
    <div class="d-flex align-items-center gap-3">
        <button class="btn d-md-none p-0 text-white border-0" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Buka menu navigasi">
            <i class="bi bi-list fs-4"></i>
        </button>
        <a class="admin-brand" href="homeadmin.php" aria-label="Kumaphone Admin Dashboard">
            <img src="img/logo.png" alt="" width="32" height="32">
            <span>Kumaphone</span>
            <span class="badge-admin">Admin</span>
        </a>
    </div>

    <div class="admin-status-pill d-none d-lg-inline-flex" aria-hidden="true">
        <span class="admin-status-dot"></span>
        <span>Sistem Pakar Forward Chaining - Status: Normal</span>
    </div>

    <div class="admin-user-menu">
        <div class="admin-user-info d-none d-sm-flex">
            <div class="admin-user-avatar" title="<?= escape($adminName) ?>"><?= escape($adminInitials) ?></div>
            <span><?= escape($adminName) ?></span>
        </div>
        <a class="btn-admin-logout" href="logout.php">
            <i class="bi bi-box-arrow-right"></i>
            <span>Keluar</span>
        </a>
    </div>
</header>
