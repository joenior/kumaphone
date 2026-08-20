<?php
/**
 * Delete a damage record.
 *
 * Requires: POST method, admin session, valid CSRF token.
 */
require_once 'includes/database.php';
require_once 'includes/auth.php';

requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: daftarkerusakan.php");
    exit;
}

if (!validateCsrfToken()) {
    die("Permintaan tidak valid (CSRF).");
}

$damageId = $_POST['id'] ?? '';

if (!empty($damageId)) {
    executeQuery($db, "DELETE FROM kerusakan WHERE idkerusakan = ?", [$damageId]);
}

header("Location: daftarkerusakan.php");
exit;