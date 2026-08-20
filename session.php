<?php
/**
 * Session helper — included by pages that need the admin session.
 *
 * This is a thin compatibility shim. New pages should use
 * includes/auth.php directly.
 */
require_once __DIR__ . '/includes/database.php';
require_once __DIR__ . '/includes/auth.php';

$login_session = getAdminName($db);