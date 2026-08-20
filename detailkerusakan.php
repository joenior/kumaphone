<?php
/**
 * Admin — Detail Kerusakan
 *
 * Displays detailed information about a specific damage record.
 */
require_once 'session.php';
requireAdmin();

$activeMenu = 'kerusakan';
$adminName  = getAdminName($db) ?? 'Admin';

$damageId = $_GET['id'] ?? '';
if (empty($damageId)) {
    header("Location: daftarkerusakan.php");
    exit;
}

$damage = fetchOne($db, "SELECT * FROM kerusakan WHERE idkerusakan = ?", [$damageId]);
if ($damage === null) {
    header("Location: daftarkerusakan.php");
    exit;
}

$symptoms = fetchAll(
    $db,
    "SELECT b.gejala 
     FROM kerusakan p
     JOIN basispengetahuan b ON p.namakerusakan = b.namakerusakan
     WHERE p.idkerusakan = ?",
    [$damageId]
);
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Kerusakan | Kumaphone Admin</title>
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

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Detail Kerusakan</h1>
            </div>

            <div class="mb-3">
                <label class="form-label font-weight-bold">ID Kerusakan:</label>
                <input type="text" class="form-control" readonly value="<?= escape($damage['idkerusakan']) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label font-weight-bold">Nama Kerusakan:</label>
                <input type="text" class="form-control" readonly value="<?= escape($damage['namakerusakan']) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label font-weight-bold">Jenis Smartphone:</label>
                <input type="text" class="form-control" readonly value="<?= escape($damage['jenishp']) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label font-weight-bold">Ciri Kerusakan Terkait:</label>
                <?php if (empty($symptoms)): ?>
                    <p class="text-muted">Belum ada ciri kerusakan yang terhubung di basis pengetahuan.</p>
                <?php else: ?>
                    <ul class="list-group">
                        <?php foreach ($symptoms as $symptom): ?>
                            <li class="list-group-item"><?= escape($symptom['gejala']) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label class="form-label font-weight-bold">Cara Mengatasi:</label>
                <textarea rows="6" class="form-control" readonly><?= escape($damage['caramengatasi'] ?? '') ?></textarea>
            </div>

            <a href="daftarkerusakan.php" class="btn btn-secondary mb-4">Kembali</a>
        </main>
    </div>
</div>

<script src="bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" crossorigin="anonymous"></script>
<script>feather.replace();</script>
</body>
</html>