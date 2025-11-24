<?php
/**
 * Konfigurasi Database
 * File ini berisi konfigurasi koneksi database dan fungsi-fungsi helper
 */

// Konfigurasi database
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'edukasi_lingkungan');

/**
 * Fungsi untuk mendapatkan koneksi database
 * @return mysqli
 */
function getDBConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conn->connect_error) {
        die("Koneksi database gagal: " . $conn->connect_error);
    }
    
    $conn->set_charset("utf8mb4");
    return $conn;
}

/**
 * Fungsi untuk membersihkan input dari user
 * @param string $data
 * @return string
 */
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Fungsi untuk membuat slug dari string
 * @param string $string
 * @return string
 */
function create_slug($string) {
    $string = strtolower($string);
    $string = preg_replace('/[^a-z0-9\s-]/', '', $string);
    $string = preg_replace('/[\s-]+/', '-', $string);
    $string = trim($string, '-');
    return $string;
}

/**
 * Fungsi untuk cek apakah user sudah login
 */
function check_login() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../user/login.php");
        exit();
    }
}

/**
 * Fungsi untuk cek apakah user adalah admin
 */
function check_admin() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
        header("Location: ../user/login.php");
        exit();
    }
}

/**
 * Fungsi untuk format tanggal Indonesia
 * @param string $date
 * @return string
 */
function format_tanggal($date) {
    $bulan = array(
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    );
    
    $pecah = explode('-', date('Y-m-d', strtotime($date)));
    return $pecah[2] . ' ' . $bulan[(int)$pecah[1]] . ' ' . $pecah[0];
}

/**
 * Fungsi untuk memotong text
 * @param string $text
 * @param int $length
 * @return string
 */
function excerpt($text, $length = 150) {
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr(strip_tags($text), 0, $length) . '...';
}
?>