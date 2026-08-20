<?php
/**
 * Admin Dashboard — Home Page
 *
 * Shows the customer history table with options to delete all records.
 */
require_once 'session.php';
requireAdmin();

$activeMenu = 'beranda';
$adminName  = getAdminName($db) ?? 'Admin';

$customerRecords = fetchAll($db, "SELECT * FROM smartphones");
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dasbor Admin | Kumaphone</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <style>
        body { font-family: 'Inter', sans-serif !important; }
    </style>
    <link href="dashboard.css" rel="stylesheet">
</head>
<body>

<?php include 'includes/admin_header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php include 'includes/admin_sidebar.php'; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Beranda</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <form method="post" action="deletehistori.php" onsubmit="return confirm('Yakin ingin menghapus semua data histori?')">
                        <?= csrfField() ?>
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            <b class="bi bi-person-x-fill"> Hapus semua data</b>
                        </button>
                    </form>
                </div>
            </div>

            <table class="table table-bordered table-striped caption-top">
                <caption>Tabel histori customer</caption>
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>Nama customer</th>
                        <th>Brand &amp; Model</th>
                        <th>IMEI</th>
                        <th>Sistem Operasi</th>
                        <th>ID Kerusakan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($customerRecords as $index => $record): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= escape($record['brand'] ?? '') ?></td>
                        <td><?= escape($record['model'] ?? '') ?></td>
                        <td><?= escape($record['imei'] ?? '') ?></td>
                        <td><?= escape($record['jenishp'] ?? '') ?></td>
                        <td><?= escape($record['idkerusakan'] ?? '') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
    </div>
</div>

<script src="bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" crossorigin="anonymous"></script>
<script>feather.replace();</script>
</body>
</html>
