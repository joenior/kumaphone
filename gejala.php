<?php
/**
 * Admin — Symptom (Ciri Kerusakan) Management
 *
 * Lists all symptoms with edit/delete actions.
 */
require_once 'session.php';
requireAdmin();

$activeMenu = 'gejala';
$adminName  = getAdminName($db) ?? 'Admin';

$selectedOS  = $_POST['hp'] ?? null;
$symptomList = [];

if ($selectedOS && $selectedOS !== 'Pilih') {
    $symptomList = fetchAll($db, "SELECT * FROM gejala WHERE jenishp = ?", [$selectedOS]);
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ciri Kerusakan | Kumaphone Admin</title>
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
                <h1 class="h2">Ciri Kerusakan</h1>
            </div>

            <form method="post" action="gejala.php">
                <label for="hp">Sistem Operasi</label>
                <select class="form-control" name="hp" id="hp" onchange="this.form.submit();">
                    <option>Pilih</option>
                    <option <?= $selectedOS === 'Android' ? 'selected' : '' ?>>Android</option>
                </select>
            </form>

            <br>
            <a href="ainputgejala.php">
                <button type="button" class="btn btn-default">
                    <span class="bi bi-plus" aria-hidden="true">Tambah data</span>
                </button>
            </a>
            <br><br>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>ID Ciri Kerusakan</th>
                            <th>Ciri Kerusakan</th>
                            <th>Jenis Smartphone</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($symptomList as $index => $symptom): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= escape($symptom['idgejala']) ?></td>
                            <td><?= escape($symptom['gejala']) ?></td>
                            <td><?= escape($symptom['jenishp']) ?></td>
                            <td>
                                <a href="aeditgejala.php?id=<?= urlencode($symptom['idgejala']) ?>"><i class="bi bi-pencil"></i></a>
                                &nbsp;
                                <form method="post" action="adeletegejala.php" style="display:inline" onsubmit="return confirm('Yakin menghapus data ini?')">
                                    <?= csrfField() ?>
                                    <input type="hidden" name="id" value="<?= escape($symptom['idgejala']) ?>">
                                    <button type="submit" class="btn btn-link p-0"><i class="bi bi-trash3 text-danger"></i></button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script>feather.replace();</script>
</body>
</html>
