<?php
/**
 * Admin — Damage (Kerusakan) Management
 *
 * Lists all damage types with view/edit/delete actions.
 */
require_once 'session.php';
requireAdmin();

$activeMenu = 'kerusakan';
$adminName  = getAdminName($db) ?? 'Admin';

$selectedOS  = $_POST['hp'] ?? null;
$damageList  = [];

if ($selectedOS && $selectedOS !== 'Pilih') {
    $damageList = fetchAll($db, "SELECT * FROM kerusakan WHERE jenishp = ?", [$selectedOS]);
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kerusakan | Kumaphone Admin</title>
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
                <h1 class="h2">Kerusakan</h1>
            </div>

            <form method="post" action="daftarkerusakan.php">
                <label for="hp">Jenis Smartphone</label>
                <select class="form-control" name="hp" id="hp" onchange="this.form.submit();">
                    <option>Pilih</option>
                    <option <?= $selectedOS === 'Android' ? 'selected' : '' ?>>Android</option>
                </select>
            </form>

            <br>
            <a href="inputkerusakan.php">
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
                            <th>ID Kerusakan</th>
                            <th>Nama Kerusakan</th>
                            <th>Jenis Smartphone</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($damageList as $index => $damage): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= escape($damage['idkerusakan']) ?></td>
                            <td><?= escape($damage['namakerusakan']) ?></td>
                            <td><?= escape($damage['jenishp']) ?></td>
                            <td>
                                <a href="detailkerusakan.php?id=<?= urlencode($damage['idkerusakan']) ?>"><i class="bi bi-search"></i></a>
                                &nbsp;
                                <form method="post" action="deletekerusakan.php" style="display:inline" onsubmit="return confirm('Yakin menghapus data ini?')">
                                    <?= csrfField() ?>
                                    <input type="hidden" name="id" value="<?= escape($damage['idkerusakan']) ?>">
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

<script src="bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" crossorigin="anonymous"></script>
<script>feather.replace();</script>
</body>
</html>
