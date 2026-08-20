<?php
/**
 * Database connection and query helpers.
 *
 * Uses PDO with prepared statements to prevent SQL injection.
 * All pages should include this file instead of koneksi.php.
 */

$DB_HOST     = getenv('DB_HOST') ?: 'localhost';
$DB_USERNAME = getenv('DB_USER') ?: 'root';
$DB_PASSWORD = getenv('DB_PASS') !== false ? getenv('DB_PASS') : '';
$DB_NAME     = getenv('DB_NAME') ?: 'sbp';

try {
    $db = new PDO(
        "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4",
        $DB_USERNAME,
        $DB_PASSWORD,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    die("Terjadi kesalahan koneksi database: " . $e->getMessage() . "<br>Pastikan service MySQL di XAMPP Control Panel sudah dalam keadaan <b>Start / Running</b>.");
}

/* ------------------------------------------------------------------ */
/*  CSRF helpers                                                       */
/* ------------------------------------------------------------------ */

/**
 * Generate a CSRF token and store it in the session.
 */
function generateCsrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Return a hidden input field containing the CSRF token.
 */
function csrfField(): string
{
    $token = generateCsrfToken();
    return "<input type='hidden' name='csrf_token' value='{$token}'>";
}

/**
 * Validate the submitted CSRF token against the session token.
 */
function validateCsrfToken(): bool
{
    $submitted = $_POST['csrf_token'] ?? '';
    $stored    = $_SESSION['csrf_token'] ?? '';

    if (empty($submitted) || empty($stored)) {
        return false;
    }
    return hash_equals($stored, $submitted);
}

/* ------------------------------------------------------------------ */
/*  Query helpers — thin wrappers to keep page code minimal            */
/* ------------------------------------------------------------------ */

/**
 * Execute a query with bound parameters and return the PDOStatement.
 *
 * Usage:
 *   $stmt = executeQuery($db, "SELECT * FROM users WHERE id = ?", [$id]);
 *   $row  = $stmt->fetch();
 */
function executeQuery(PDO $db, string $sql, array $params = []): PDOStatement
{
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}

/**
 * Fetch all rows from a prepared query.
 */
function fetchAll(PDO $db, string $sql, array $params = []): array
{
    return executeQuery($db, $sql, $params)->fetchAll();
}

/**
 * Fetch a single row from a prepared query (or null if none).
 */
function fetchOne(PDO $db, string $sql, array $params = []): ?array
{
    $row = executeQuery($db, $sql, $params)->fetch();
    return $row ?: null;
}

/**
 * Sanitize a string for safe HTML output.
 */
function escape(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
