<?php
/**
 * Logout handler — destroys the session and redirects to the homepage.
 *
 * Previously called session_destroy() without session_start() and
 * output HTML before header() redirect (which would never work).
 */
require_once 'includes/database.php';
require_once 'includes/auth.php';

performLogout();