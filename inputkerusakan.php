<?php
/**
 * Admin — Add New Damage Type
 *
 * Form to insert a new damage record into the database.
 */
require_once 'session.php';
requireAdmin();

$activeMenu = 'kerusakan';
$adminName  = getAdminName($db) ?? 'Admin';
$showSuccess = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $damageId      = trim($_POST['idkerusakan'] ?? '');
    $damageName    = trim($_POST['namakerusakan'] ?? '');
    $phoneType     = trim($_POST['jenishp'] ?? '');
    $repairSteps   = trim($_POST['caramengatasi'] ?? '');

    if (!empty($damageId) && !empty($damageName) && !empty($phoneType)) {
        executeQuery(
            $db,
            "INSERT INTO kerusakan (idkerusakan, namakerusakan, jenishp, caramengatasi) VALUES (?, ?, ?, ?)",
            [$damageId, $damageName, $phoneType, $repairSteps]
        );
        $showSuccess = true;
    }
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Kerusakan | Kumaphone Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
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
                <h1 class="h2">Tambah Data Kerusakan</h1>
            </div>

            <?php if ($showSuccess): ?>
            <div class="alert alert-success">Data berhasil disimpan.</div>
            <?php endif; ?>

            <form class="form-horizontal" method="post" action="inputkerusakan.php">
                <div class="mb-3">
                    <label class="form-label" for="idkerusakan">ID Kerusakan:</label>
                    <input type="text" class="form-control" required name="idkerusakan" id="idkerusakan">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="namakerusakan">Nama Kerusakan:</label>
                    <input type="text" class="form-control" required name="namakerusakan" id="namakerusakan">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="jenishp">Jenis Smartphone:</label>
                    <select class="form-control" name="jenishp" id="jenishp">
                        <option>Pilih</option>
                        <option>Android</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="caramengatasi">Cara Mengatasi:</label>
                    <textarea rows="8" class="form-control" name="caramengatasi" id="caramengatasi"></textarea>
                </div>
                <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
            </form>
        </main>
    </div>
</div>

<script src="bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" crossorigin="anonymous"></script>
<script>feather.replace();</script>
</body>
</html>
