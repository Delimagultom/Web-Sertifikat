<?php
include "../conn/koneksi.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $nama_kth = $_POST['nama'];
    $nomor_sertifikat = $_POST['nomor'];

    // Validasi file
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
    $max_size = 5 * 1024 * 1024; // 5MB

    if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        die("Error uploading file: " . $_FILES['file']['error']);
    }

    $file = $_FILES['file'];
    $file_type = $file['type'];
    $file_size = $file['size'];

    if (!in_array($file_type, $allowed_types)) {
        die("File type not allowed. Please upload JPG, PNG, GIF, or PDF files only.");
    }

    if ($file_size > $max_size) {
        die("File size too large. Maximum size is 5MB.");
    }

    // Generate unique filename
    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $new_filename = uniqid() . '_' . time() . '.' . $file_ext;
    $upload_path = "../uploads/" . $new_filename;

    // Create uploads directory if it doesn't exist
    if (!file_exists("../uploads")) {
        mkdir("../uploads", 0777, true);
    }

    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $upload_path)) {
        // Save to database
        $query = "INSERT INTO sertifikat (user_id, nama_kth, nomor_sertifikat, nama_file) 
                 VALUES ('$user_id', '$nama_kth', '$nomor_sertifikat', '$new_filename')";

        if (mysqli_query($conn, $query)) {
            header("Location: ../pages/main.php");
            exit;
        } else {
            unlink($upload_path); // Delete uploaded file if database insert fails
            die("Error saving to database: " . mysqli_error($conn));
        }
    } else {
        die("Error moving uploaded file.");
    }
} else {
    header("Location: ../main.php");
    exit;
}