<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
include "koneksi.php";

if (!isset($_GET['id'])) {
    header("Location: admin_dashboard.php");
    exit;
}

$id = mysqli_real_escape_string($conn, $_GET['id']);
$query = mysqli_query($conn, "SELECT * FROM sertifikat WHERE id = '$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    header("Location: admin_dashboard.php");
    exit;
}

$file_path = 'uploads/' . $data['nama_file'];

if (!file_exists($file_path)) {
    die("File tidak ditemukan");
}

// Get file extension
$file_ext = strtolower(pathinfo($data['nama_file'], PATHINFO_EXTENSION));

// Set appropriate headers
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $data['nama_file'] . '"');
header('Content-Length: ' . filesize($file_path));
header('Cache-Control: no-cache, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// Output file
readfile($file_path);
exit;