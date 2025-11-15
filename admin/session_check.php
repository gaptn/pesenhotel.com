<?php
// File: admin/session_check.php
// Include file ini di setiap halaman admin untuk proteksi

session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Include database connection
include '../config/db.php';
?>