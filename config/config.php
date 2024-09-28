<?php
// Mengatur zona waktu ke Asia/Makassar
date_default_timezone_set('Asia/Makassar');

// Mode environment (development/production)
$env = 'development'; // Ubah menjadi 'production' jika dalam tahap produksi

// Konfigurasi base url
$base_url = 'http://localhost/restaurant'; // Ubah sesuai domain anda

function base_url($path = '')
{
    global $base_url;
    return rtrim($base_url, '/') . '/' . ltrim($path, '/');
}

// Konfigurasi database
$hostname   = 'localhost';
$username   = 'root';
$password   = '';
$dbname     = 'restaurant';

// Mengaktifkan laporan error jika mode 'development'
if ($env === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    // Mode produksi, sembunyikan error
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Membuat koneksi
$koneksi = mysqli_connect($hostname, $username, $password, $dbname);

// Cek koneksi
if (!$koneksi) {
    if ($env === 'development') {
        die("Koneksi ke database gagal: " . mysqli_connect_error());
    } else {
        // Mengarahkan ke halaman 500 jika dalam mode produksi
        header('Location: ' . base_url('error/500.php'));
        exit();
    }
}

// Handle gambar jika tidak ada di direktori
function get_image_url($url_gambar)
{
    // Path absolut untuk pengecekan file di server
    $path_gambar = $_SERVER['DOCUMENT_ROOT'] . parse_url($url_gambar, PHP_URL_PATH);

    // Jika file tidak ada atau URL gambar kosong, gunakan placeholder
    if (!file_exists($path_gambar) || empty($url_gambar)) {
        return base_url('assets/uploads/static/no-image-placeholder.png');
    }

    // Jika file ada, kembalikan URL gambar
    return $url_gambar;
}
