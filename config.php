<?php
// Cek apakah session belum dimulai, baru lakukan session_start()
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Konfigurasi Database
$host = 'localhost';
$db   = 'db_siaptrek';
$user = 'root'; // Default XAMPP
$pass = '';     // Default XAMPP (Kosongkan jika tidak ada password)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Koneksi数据库 gagal: " . $e->getMessage());
}
?>