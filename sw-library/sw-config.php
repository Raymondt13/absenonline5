<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('Asia/Jakarta');

// Koneksi Database
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASSWD", "");
define("DB_NAME", "absenonline5");

$connection = new mysqli(DB_HOST, DB_USER, DB_PASSWD, DB_NAME);
if ($connection->connect_error) {
    die("Gagal koneksi database: " . $connection->connect_error);
}

// Ambil data site
$query_site  = "SELECT * FROM sw_site LIMIT 1";
$result_site = $connection->query($query_site);

if ($result_site && $result_site->num_rows > 0) {
    $row_site = $result_site->fetch_assoc();
} else {
    $row_site = [
        'site_url' => 'http://localhost/absenonline5/',
        'site_name' => 'Absen Online',
        'site_phone' => '',
        'site_address' => '',
        'site_description' => '',
        'site_logo' => '',
        'site_email' => ''
    ];
}

// Base URL FIX
$base_url = "http://localhost/absenonline5/";
