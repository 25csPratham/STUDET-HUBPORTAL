<?php
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'domain'   => '',
        'secure'   => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function getCsrfToken() {
    return $_SESSION['csrf_token'] ?? '';
}

function verifyCsrfToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function isLoggedIn() {
    return !empty($_SESSION['studenthub_user']);
}

function getCurrentUser() {
    return $_SESSION['studenthub_user'] ?? null;
}

function getUserRole() {
    return $_SESSION['studenthub_user']['role'] ?? 'guest';
}

function requireLogin($redirectPage = 'login.php') {
    if (!isLoggedIn()) {
        header("Location: $redirectPage");
        exit;
    }
}

function requireRole($role, $fallbackPage = 'dashboard.php') {
    requireLogin('login.php');
    if (getUserRole() !== $role) {
        header("Location: $fallbackPage?error=unauthorized");
        exit;
    }
}

function setFlashMessage($type, $message) {
    $_SESSION['flash'] = [
        'type'    => $type,
        'message' => $message
    ];
}

function getFlashMessage() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

function sanitizeInput($data) {
    if (is_array($data)) {
        return array_map('sanitizeInput', $data);
    }
    return htmlspecialchars(trim((string)$data), ENT_QUOTES, 'UTF-8');
}
