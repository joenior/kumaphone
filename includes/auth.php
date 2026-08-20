<?php
/**
 * Authentication and session management.
 *
 * Provides login verification using password_hash/password_verify,
 * session guards for admin pages, and a clean logout flow.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check whether the current visitor is logged in as admin.
 */
function isAdminLoggedIn(): bool
{
    return !empty($_SESSION['login_user']);
}

/**
 * Get the display name of the logged-in admin (or null).
 */
function getAdminName(PDO $db): ?string
{
    if (!isAdminLoggedIn()) {
        return null;
    }

    $username = $_SESSION['login_user'];
    $admin    = fetchOne($db, "SELECT nama FROM admin WHERE username = ?", [$username]);

    return $admin['nama'] ?? null;
}

/**
 * Redirect to the login page if the visitor is not an admin.
 * Call this at the top of every admin-only page.
 */
function requireAdmin(): void
{
    if (!isAdminLoggedIn()) {
        header("Location: login.html");
        exit;
    }
}

/**
 * Attempt to log in with the given credentials.
 *
 * Returns true on success (and sets the session), false on failure.
 *
 * IMPORTANT: The `admin` table must store passwords hashed with
 * password_hash(). A migration script should be run once to convert
 * existing plaintext passwords.
 */
function attemptLogin(PDO $db, string $username, string $password): bool
{
    $admin = fetchOne($db, "SELECT username, password FROM admin WHERE username = ?", [$username]);

    if ($admin === null) {
        return false;
    }

    $storedPassword = $admin['password'];
    $isValid = false;

    // Check if hashed with password_hash / bcrypt
    if (password_verify($password, $storedPassword)) {
        $isValid = true;
    } elseif ($password === $storedPassword) {
        // Legacy plaintext password detected: auto-upgrade hash in database
        $newHash = password_hash($password, PASSWORD_DEFAULT);
        executeQuery($db, "UPDATE admin SET password = ? WHERE username = ?", [$newHash, $username]);
        $isValid = true;
    }

    if (!$isValid) {
        return false;
    }

    $_SESSION['login_user'] = $admin['username'];
    return true;
}

/**
 * Destroy the session and redirect to the homepage.
 */
function performLogout(): void
{
    session_unset();
    session_destroy();
    header("Location: index.html");
    exit;
}
