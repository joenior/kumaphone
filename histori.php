<?php
/**
 * Admin — History Pengecekan
 *
 * Shows historical diagnostic check records from customers.
 */
require_once 'session.php';
requireAdmin();

$activeMenu = 'histori';
$adminName  = getAdminName($db) ?? 'Admin';

$historyList = fetchAll(
    $db,
    "SELECT s.*, k.namakerusakan
     FROM smartphones s
     LEFT JOIN kerusakan k ON s.idkerusakan = k.idkerusakan
     ORDER BY s.id DESC"
);
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Histori Pengecekan | Kumaphone Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <style>body { font-family: 'Inter', sans-serif !important; }</style>
    <link href="dashboard.css" rel="stylesheet">
</head>
<body>

<?php include 'includes/admin_header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php include 'includes/admin_sidebar.php'; ?>

        <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Histori Pengecekan</h1>
                <div class="btn-toolbar mb-2 mb-md-0 d-flex gap-2">
                    <a href="cetak_histori.php" target="_blank" class="btn btn-sm btn-primary">
                        <i class="bi bi-printer-fill"></i> Cetak Rekapitulasi (PDF)
                    </a>
                    <form method="post" action="deletehistori.php" onsubmit="return confirm('Yakin ingin menghapus semua data histori?')">
                        <?= csrfField() ?>
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            <b class="bi bi-trash-fill"> Hapus semua data</b>
                        </button>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 4%;">NO</th>
                            <th style="width: 15%;">No. Registrasi</th>
                            <th>Nama Customer</th>
                            <th>Brand &amp; Model</th>
                            <th>IMEI</th>
                            <th>Sistem Operasi</th>
                            <th>Hasil Diagnosa</th>
                            <th style="width: 13%; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($historyList)): ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Belum ada data histori pengecekan.</td>
                        </tr>
                        <?php endif; ?>
                        <?php foreach ($historyList as $index => $row): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><span class="badge bg-dark font-monospace"><?= escape($row['no_registrasi'] ?? '-') ?></span></td>
                            <td><strong><?= escape($row['brand'] ?? '') ?></strong></td>
                            <td><?= escape($row['model'] ?? '') ?></td>
                            <td><code><?= escape($row['imei'] ?? '') ?></code></td>
                            <td><span class="badge bg-secondary"><?= escape($row['jenishp'] ?? '') ?></span></td>
                            <td>
                                <span class="badge bg-success"><?= escape($row['idkerusakan'] ?? '') ?></span>
                                <?= escape($row['namakerusakan'] ?? '') ?>
                            </td>
                            <td class="text-center">
                                <a href="cetak_diagnosa.php?id=<?= (int)$row['id'] ?>" target="_blank" class="btn btn-sm btn-outline-primary" title="Cetak Lembar Diagnosa">
                                    <i class="bi bi-file-earmark-pdf-fill"></i> Cetak PDF
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" crossorigin="anonymous"></script>
<script>feather.replace();</script>
</body>
</html>
