<?php
/**
 * Delete a symptom record.
 *
 * Requires: POST method, admin session, valid CSRF token.
 */
require_once 'includes/database.php';
require_once 'includes/auth.php';

requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: gejala.php");
    exit;
}

if (!validateCsrfToken()) {
    die("Permintaan tidak valid (CSRF).");
}

$symptomId = $_POST['id'] ?? '';

if (!empty($symptomId)) {
    executeQuery($db, "DELETE FROM gejala WHERE idgejala = ?", [$symptomId]);
}

header("Location: gejala.php");
exit;