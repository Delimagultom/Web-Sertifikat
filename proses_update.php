<?php
include "koneksi.php";

$nama = $_POST['nama'];
$nomor = $_POST['nomor'];
$tanggal = date('Y-m-d');
$file = $_FILES['file']['name'];
$tmp = $_FILES['file']['tmp_name'];
$ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
$allowed = ['pdf', 'jpg', 'jpeg', 'png'];

if (in_array($ext, $allowed)) {
    $newname = uniqid() . '.' . $ext;
    move_uploaded_file($tmp, "uploads/" . $newname);
    mysqli_query($conn, "INSERT INTO sertifikat (nama_kth, nomor_sertifikat, tanggal_upload, nama_file) VALUES ('$nama', '$nomor', '$tanggal', '$newname')");
    header("Location: index.php");
} else {
    echo "Format file tidak didukung!";
}
?>