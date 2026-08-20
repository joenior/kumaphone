<?php
/**
 * Admin — Add Knowledge Base Entry
 *
 * Links symptoms to damage types in the knowledge base.
 */
require_once 'session.php';
requireAdmin();

$activeMenu = 'basispengetahuan';
$adminName  = getAdminName($db) ?? 'Admin';

$selectedOS   = $_POST['hp'] ?? null;
$damageList   = [];
$symptomList  = [];
$showSuccess  = false;

if ($selectedOS && $selectedOS !== 'Pilih') {
    $damageList  = fetchAll($db, "SELECT * FROM kerusakan WHERE jenishp = ?", [$selectedOS]);
    $symptomList = fetchAll($db, "SELECT * FROM gejala WHERE jenishp = ?", [$selectedOS]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $damageName     = $_POST['kerusakan'] ?? '';
    $selectedGejala = $_POST['gejala'] ?? [];

    if (!empty($damageName) && !empty($selectedGejala)) {
        $insertStmt = $db->prepare("INSERT INTO basispengetahuan (namakerusakan, gejala) VALUES (?, ?)");
        foreach ($selectedGejala as $symptom) {
            $insertStmt->execute([$damageName, $symptom]);
        }
        $showSuccess = true;
    }
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Basis Pengetahuan | Kumaphone Admin</title>
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
                <h1 class="h2">Basis Pengetahuan</h1>
            </div>

            <!-- Step 1: Select OS -->
            <form method="post" action="abasispengetahuan.php">
                <label for="hp">Jenis Smartphone</label>
                <select class="form-control" name="hp" id="hp" onchange="this.form.submit();">
                    <option>Pilih</option>
                    <option <?= $selectedOS === 'Android' ? 'selected' : '' ?>>Android</option>
                </select>
            </form>

            <?php if (!empty($damageList)): ?>
            <br>
            <!-- Step 2: Select damage and symptoms -->
            <form method="post" action="abasispengetahuan.php">
                <input type="hidden" name="hp" value="<?= escape($selectedOS) ?>">

                <label for="kerusakan">Daftar Kerusakan</label>
                <select class="form-control" name="kerusakan" id="kerusakan">
                    <option>Pilih</option>
                    <?php foreach ($damageList as $damage): ?>
                    <option value="<?= escape($damage['namakerusakan']) ?>">
                        <?= escape($damage['idkerusakan']) ?> — <?= escape($damage['namakerusakan']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>

                <br>
                <div class="card">
                    <div class="card-header">Ciri Kerusakan</div>
                    <div class="card-body">
                        <?php foreach ($symptomList as $symptom): ?>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input"
                                   value="<?= escape($symptom['gejala']) ?>"
                                   name="gejala[]"
                                   id="symptom-<?= escape($symptom['idgejala']) ?>">
                            <label class="form-check-label" for="symptom-<?= escape($symptom['idgejala']) ?>">
                                <?= escape($symptom['gejala']) ?>
                            </label>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <br>
                <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
            </form>
            <?php endif; ?>

            <?php if ($showSuccess): ?>
            <br>
            <div class="alert alert-success">Data berhasil disimpan.</div>
            <?php endif; ?>
        </main>
    </div>
</div>

<script src="bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" crossorigin="anonymous"></script>
<script>feather.replace();</script>
</body>
</html>
