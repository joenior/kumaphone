<?php
/**
 * Login handler — validates credentials and starts an admin session.
 *
 * Replaces the old ceklogin.php which had SQL injection and plaintext
 * password comparison.
 */
require_once 'includes/database.php';
require_once 'includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.html");
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    header("Location: salahlogin.php");
    exit;
}

if (attemptLogin($db, $username, $password)) {
    header("Location: homeadmin.php");
    exit;
}

header("Location: salahlogin.php");
exit;