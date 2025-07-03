<?php
// CSRF Protection Utilities

/**
 * Generate a CSRF token and store it in the session
 * @return string The generated CSRF token
 */
function generate_csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify that the provided CSRF token matches the one in the session
 * @param string $token The token to verify
 * @return bool Whether the token is valid
 */
function verify_csrf_token($token) {
    if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
        return false;
    }
    return true;
}

/**
 * Output a CSRF token field for a form
 * @return string HTML for a hidden input field containing the CSRF token
 */
function csrf_token_field() {
    $token = generate_csrf_token();
    return '<input type="hidden" name="csrf_token" value="' . $token . '">';
}