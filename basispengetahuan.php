<?php
/**
 * Admin — Knowledge Base (Basis Pengetahuan) Management
 *
 * Lists the knowledge base rules and allows viewing/deleting entries.
 */
require_once 'session.php';
requireAdmin();

$activeMenu = 'basispengetahuan';
$adminName  = getAdminName($db) ?? 'Admin';

$selectedOS    = $_POST['hp'] ?? null;
$knowledgeList = [];

if ($selectedOS && $selectedOS !== 'Pilih') {
    $knowledgeList = fetchAll(
        $db,
        "SELECT p.idkerusakan, p.jenishp, b.namakerusakan, b.gejala
         FROM basispengetahuan b
         JOIN kerusakan p ON p.namakerusakan = b.namakerusakan
         WHERE p.jenishp = ?",
        [$selectedOS]
    );
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Basis Pengetahuan | Kumaphone Admin</title>
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
                <h2 class="h2">Basis Pengetahuan</h2>
            </div>

            <form method="post" action="basispengetahuan.php">
                <label for="hp">Sistem Operasi</label>
                <select class="form-control" name="hp" id="hp" onchange="this.form.submit();">
                    <option>Pilih</option>
                    <option <?= $selectedOS === 'Android' ? 'selected' : '' ?>>Android</option>
                </select>
            </form>

            <br>
            <a href="abasispengetahuan.php">
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
                            <th>Ciri Kerusakan</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($knowledgeList as $index => $entry): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= escape($entry['idkerusakan']) ?></td>
                            <td><?= escape($entry['namakerusakan']) ?></td>
                            <td><?= escape($entry['gejala']) ?></td>
                            <td>
                                <form method="post" action="adeletebasispengetahuan.php" style="display:inline" onsubmit="return confirm('Yakin menghapus data ini?')">
                                    <?= csrfField() ?>
                                    <input type="hidden" name="id" value="<?= escape($entry['namakerusakan']) ?>">
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
