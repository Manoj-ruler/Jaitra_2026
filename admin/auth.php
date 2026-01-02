<?php
/**
 * Auth Check - Include this at the top of protected admin pages
 */
session_start();

function isLoggedIn() {
    return isset($_SESSION['admin_id']) && isset($_SESSION['admin_username']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function getAllowedSportId() {
    return $_SESSION['allowed_sport_id'] ?? null;
}

function canAccessSport($sport_id) {
    $allowed = getAllowedSportId();
    // NULL means super admin (all sports)
    return $allowed === null || $allowed == $sport_id;
}

function getAdminUsername() {
    return $_SESSION['admin_username'] ?? '';
}
?>
