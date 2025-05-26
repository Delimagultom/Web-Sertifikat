<?php
$host = "localhost";
$username = "root";
$password = "";

// Create connection without database
$conn = mysqli_connect($host, $username, $password);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS sertifikat";
if (mysqli_query($conn, $sql)) {
    echo "Database created successfully<br>";
} else {
    echo "Error creating database: " . mysqli_error($conn) . "<br>";
}

// Select the database
mysqli_select_db($conn, "sertifikat");

// Create userss table
$sql = "CREATE TABLE IF NOT EXISTS userss (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_kth VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (mysqli_query($conn, $sql)) {
    echo "Userss table created successfully<br>";
} else {
    echo "Error creating userss table: " . mysqli_error($conn) . "<br>";
}

// Create sertifikat table
$sql = "CREATE TABLE IF NOT EXISTS sertifikat (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    nama_kth VARCHAR(100) NOT NULL,
    nomor_sertifikat VARCHAR(50) NOT NULL,
    nama_file VARCHAR(255) NOT NULL,
    tanggal_upload TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES userss(id) ON DELETE CASCADE
)";

if (mysqli_query($conn, $sql)) {
    echo "Sertifikat table created successfully<br>";
} else {
    echo "Error creating sertifikat table: " . mysqli_error($conn) . "<br>";
}

mysqli_close($conn);
echo "Database setup completed!";