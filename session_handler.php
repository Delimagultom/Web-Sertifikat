<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Function to check if user is logged in
function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

// Function to check if user is admin
function isAdmin()
{
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Function to check session timeout
function checkSessionTimeout()
{
    if (isset($_SESSION['last_activity']) && isset($_SESSION['expire_time'])) {
        $inactive = time() - $_SESSION['last_activity'];
        if ($inactive >= $_SESSION['expire_time']) {
            // Session has expired
            session_unset();
            session_destroy();
            header("Location: /pages/login.php?error=session_expired");
            exit;
        }
    }
    $_SESSION['last_activity'] = time();
}

// Function to require login
function requireLogin()
{
    if (!isLoggedIn()) {
        header("Location: /pages/login.php");
        exit;
    }
    checkSessionTimeout();
}

// Function to require admin
function requireAdmin()
{
    requireLogin();
    if (!isAdmin()) {
        header("Location: /pages/login.php?error=unauthorized");
        exit;
    }
}

// Function to regenerate session ID periodically
function regenerateSession()
{
    if (!isset($_SESSION['last_regeneration'])) {
        $_SESSION['last_regeneration'] = time();
    } else {
        $interval = 300; // 5 minutes
        if (time() - $_SESSION['last_regeneration'] >= $interval) {
            session_regenerate_id(true);
            $_SESSION['last_regeneration'] = time();
        }
    }
}

// Set secure session parameters
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));

// Initialize session security
regenerateSession();
checkSessionTimeout();