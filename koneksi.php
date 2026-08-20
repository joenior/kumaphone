<?php
/**
 * Compatibility database connection.
 * 
 * Provides backward compatibility for scripts referencing $konek_db or $db.
 * New code should directly use require_once 'includes/database.php'.
 */
require_once __DIR__ . '/includes/database.php';

// Legacy variables referencing centralized configuration
$host     = $DB_HOST;
$user     = $DB_USERNAME;
$password = $DB_PASSWORD;
$database = $DB_NAME;

// Backward-compatible mysqli connection if specifically needed by legacy callers
try {
    $konek_db = mysqli_connect($host, $user, $password, $database);
} catch (Throwable $e) {
    error_log("Legacy mysqli connection error: " . $e->getMessage());
    $konek_db = false;
}