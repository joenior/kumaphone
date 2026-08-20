<?php
/**
 * Submit handler for diagnosis activity log.
 *
 * Uses the centralized PDO connection with prepared statements
 * to prevent SQL injection and ensure consistent error handling.
 */
require_once __DIR__ . '/../includes/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo "Method Not Allowed";
    exit;
}

$userName = trim($_POST['nama_user'] ?? '');
$brand    = trim($_POST['brand'] ?? '');
$imei     = trim($_POST['imei'] ?? '');
$dateLog  = date('Y-m-d H:i:s');

if (empty($userName) || empty($brand) || empty($imei)) {
    http_response_code(400);
    echo "Mohon lengkapi semua data diagnosa.";
    exit;
}

try {
    $stmt = $db->prepare("INSERT INTO diagnosa (nama_user, date_log, brand, imei) VALUES (?, ?, ?, ?)");
    $stmt->execute([$userName, $dateLog, $brand, $imei]);

    echo "New record created successfully";
} catch (PDOException $e) {
    error_log("Failed to insert diagnosis log: " . $e->getMessage());
    http_response_code(500);
    echo "Terjadi kesalahan saat menyimpan log diagnosa.";
}