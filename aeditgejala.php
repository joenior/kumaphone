<?php
/**
 * Admin — Edit Symptom
 *
 * Form to edit an existing symptom record.
 */
require_once 'session.php';
requireAdmin();
ob_start();

$activeMenu = 'gejala';
$adminName  = getAdminName($db) ?? 'Admin';

$symptomId = $_GET['id'] ?? '';
if (empty($symptomId)) {
    header("Location: gejala.php");
    exit;
}

$symptom = fetchOne($db, "SELECT * FROM gejala WHERE idgejala = ?", [$symptomId]);
if ($symptom === null) {
    header("Location: gejala.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $updatedSymptom  = trim($_POST['gejala'] ?? '');
    $updatedPhoneType = trim($_POST['jenishp'] ?? '');

    if (!empty($updatedSymptom) && !empty($updatedPhoneType)) {
        executeQuery(
            $db,
            "UPDATE gejala SET gejala = ?, jenishp = ? WHERE idgejala = ?",
            [$updatedSymptom, $updatedPhoneType, $symptomId]
        );
        header("Location: gejala.php");
        exit;
    }
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Ciri Kerusakan | Kumaphone Admin</title>
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
                <h1 class="h2">Edit Ciri Kerusakan</h1>
            </div>

            <form method="post">
                <div class="mb-3">
                    <label class="form-label">ID:</label>
                    <input type="text" class="form-control" disabled value="<?= escape($symptom['idgejala']) ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="gejala">Ciri Kerusakan:</label>
                    <input type="text" class="form-control" id="gejala" name="gejala" value="<?= escape($symptom['gejala']) ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="jenishp">Jenis:</label>
                    <input type="text" class="form-control" id="jenishp" name="jenishp" value="<?= escape($symptom['jenishp']) ?>">
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
