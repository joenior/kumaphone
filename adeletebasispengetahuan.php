<?php
/**
 * Delete a knowledge base entry.
 *
 * Requires: POST method, admin session, valid CSRF token.
 * Previously used GET with raw $_GET['id'] — SQL injection + no auth.
 */
require_once 'includes/database.php';
require_once 'includes/auth.php';

requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: basispengetahuan.php");
    exit;
}

if (!validateCsrfToken()) {
    die("Permintaan tidak valid (CSRF).");
}

$damageName = $_POST['id'] ?? '';

if (!empty($damageName)) {
    executeQuery($db, "DELETE FROM basispengetahuan WHERE namakerusakan = ?", [$damageName]);
}

header("Location: basispengetahuan.php");
exit;