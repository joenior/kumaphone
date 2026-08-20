<?php
/**
 * Delete all customer history records.
 *
 * Requires: POST method, admin session, valid CSRF token.
 */
require_once 'includes/database.php';
require_once 'includes/auth.php';

requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: homeadmin.php");
    exit;
}

if (!validateCsrfToken()) {
    die("Permintaan tidak valid (CSRF).");
}

executeQuery($db, "TRUNCATE TABLE smartphones");

header("Location: homeadmin.php");
exit;