<?php
// File: config/database.php

// Konfigurasi Database
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');           // Kosong kalau pakai XAMPP default
define('DB_NAME', 'dbhotel');    // Sesuaikan dengan nama database kamu

// Koneksi ke Database
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Cek koneksi
if (!$conn) {
    die("❌ Koneksi database gagal: " . mysqli_connect_error());
}

// Set charset UTF-8 supaya bisa pakai emoji dan bahasa Indonesia
mysqli_set_charset($conn, "utf8mb4");

// Fungsi untuk mencegah SQL Injection
function clean_input($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = mysqli_real_escape_string($conn, $data);
    return $data;
}

// echo "✅ Koneksi database berhasil!"; // Uncomment untuk testing
?>  