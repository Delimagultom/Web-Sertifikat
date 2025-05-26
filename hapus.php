<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include "../conn/koneksi.php";

$id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Cek apakah user memiliki akses ke sertifikat ini
$data = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM sertifikat WHERE id='$id' AND (user_id='$user_id' OR '{$_SESSION['role']}' = 'admin')"));
if (!$data) {
    header("Location: home.php");
    exit;
}

// Hapus file fisik juga
if ($data['nama_file'] && file_exists("uploads/" . $data['nama_file'])) {
    unlink("uploads/" . $data['nama_file']);
}
mysqli_query($conn, "DELETE FROM sertifikat WHERE id='$id'");
header("Location: " . ($_SESSION['role'] === 'admin' ? 'admin_dashboard.php' : 'home.php'));
exit;
