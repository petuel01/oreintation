<?php
/**
 * Session management helper functions
 */

/**
 * Initialize a secure session
 */
function secureSessionStart() {
    // Set secure session parameters
    $session_name = 'ORIENTATION_SESSION';
    $secure = false; // Set to true if using HTTPS
    $httponly = true;
    
    // Force session to use cookies only
    ini_set('session.use_only_cookies', 1);
    
    // Get current cookie params
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params(
        $cookieParams["lifetime"],
        $cookieParams["path"],
        $cookieParams["domain"],
        $secure,
        $httponly
    );
    
    // Set session name
    session_name($session_name);
    
    // Start session
    session_start();
    
    // Regenerate session ID to prevent session fixation
    if (!isset($_SESSION['created'])) {
        session_regenerate_id(true);
        $_SESSION['created'] = time();
    } else if (time() - $_SESSION['created'] > 1800) {
        // Regenerate session ID every 30 minutes
        session_regenerate_id(true);
        $_SESSION['created'] = time();
    }
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Check if user has specific role
 */
function hasRole($role) {
    return isLoggedIn() && isset($_SESSION['user_role']) && $_SESSION['user_role'] === $role;
}

/**
 * Require login or redirect
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: /oreintation/auth/login.php');
        exit();
    }
}

/**
 * Require specific role or redirect
 */
function requireRole($role) {
    requireLogin();
    if (!hasRole($role)) {
        header('Location: /oreintation/auth/unauthorized.php');
        exit();
    }
}