<?php
/**
 * Submit handler for smartphone data.
 *
 * Previously had its own hardcoded database connection (different from
 * koneksi.php) and SQL injection vulnerabilities.
 * Now uses the shared database helper with prepared statements.
 */
require_once 'includes/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: diagnosa.php");
    exit;
}

$customerName = trim($_POST['brand'] ?? '');
$brandModel   = trim($_POST['model'] ?? '');
$imei         = trim($_POST['imei'] ?? '');

if (empty($customerName) || empty($brandModel) || empty($imei)) {
    echo "Mohon isi semua kolom.";
    exit;
}

$stmt = $db->prepare("INSERT INTO smartphones (brand, model, imei) VALUES (?, ?, ?)");

if ($stmt->execute([$customerName, $brandModel, $imei])) {
    echo "Data berhasil disimpan.";
} else {
    error_log("Failed to insert smartphone record");
    echo "Terjadi kesalahan. Silahkan coba lagi.";
}