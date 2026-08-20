<?php
/**
 * Admin — Add New Symptom
 *
 * Form to insert a new symptom/characteristic record.
 */
require_once 'session.php';
requireAdmin();

$activeMenu = 'gejala';
$adminName  = getAdminName($db) ?? 'Admin';
$showSuccess = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $symptomId   = trim($_POST['idgejala'] ?? '');
    $symptomName = trim($_POST['gejala'] ?? '');
    $phoneType   = trim($_POST['jenishp'] ?? '');

    if (!empty($symptomId) && !empty($symptomName) && !empty($phoneType)) {
        executeQuery(
            $db,
            "INSERT INTO gejala (idgejala, gejala, jenishp) VALUES (?, ?, ?)",
            [$symptomId, $symptomName, $phoneType]
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
    <title>Tambah Ciri Kerusakan | Kumaphone Admin</title>
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
                <h1 class="h2">Tambah Ciri Kerusakan</h1>
            </div>

            <?php if ($showSuccess): ?>
            <div class="alert alert-success">Data berhasil disimpan.</div>
            <?php endif; ?>

            <form class="form-horizontal" method="post" action="ainputgejala.php">
                <div class="mb-3">
                    <label class="form-label" for="idgejala">ID Ciri Kerusakan:</label>
                    <input type="text" class="form-control" required name="idgejala" id="idgejala">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="gejala">Ciri Kerusakan:</label>
                    <input type="text" class="form-control" required name="gejala" id="gejala">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="jenishp">Jenis Smartphone:</label>
                    <select class="form-control" name="jenishp" id="jenishp">
                        <option>Pilih</option>
                        <option>Android</option>
                    </select>
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
