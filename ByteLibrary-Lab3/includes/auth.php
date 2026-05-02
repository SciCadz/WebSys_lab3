<?php
// ============================================================
//  ByteLibrary — Session Guard
//  File: includes/auth.php
//  Include at the TOP of every protected page (before any HTML)
// ============================================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// Convenience shortcut: $currentUser available on every page
$currentUser = $_SESSION['user'];
?>
