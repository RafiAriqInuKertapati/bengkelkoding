<?php
// Konfigurasi koneksi ke database
$host = 'localhost';      // Nama host, biasanya localhost
$user = 'root';           // Username database
$password = '';           // Password database
$database = 'bengkel_koding1'; // Nama database

// Membuat koneksi
$conn = mysqli_connect($host, $user, $password, $database);

// Cek koneksi
if (!$conn) {
    die('Koneksi database gagal: ' . mysqli_connect_error());
}
?>
