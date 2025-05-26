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
$query = mysqli_query($conn, "SELECT s.*, u.nama_kth, u.email, u.alamat 
                            FROM sertifikat s 
                            JOIN users u ON s.user_id = u.id 
                            WHERE s.id = '$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    header("Location: admin_dashboard.php");
    exit;
}

$file_path = 'uploads/' . $data['nama_file'];
$file_ext = strtolower(pathinfo($data['nama_file'], PATHINFO_EXTENSION));
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Detail Sertifikat - <?= $data['nomor_sertifikat'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">

</head>

<body>
    <header>
        <div class="nav-container">
            <img src="images/logo_dinshut.jpg" alt="Logo KTH" class="logo-img">
            <nav>
                <a href="admin_dashboard.php">Dashboard</a>
                <a href="manage_users.php">Kelola Pengguna</a>
                <a href="manage_certificates.php">Kelola Sertifikat</a>
                <a href="reports.php">Laporan</a>
                <a href="logout.php">Logout</a>
            </nav>
        </div>
    </header>

    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Detail Sertifikat</h2>
            <div class="btn-group">
                <a href="admin_dashboard.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <a href="download_certificate.php?id=<?= $id ?>" class="btn btn-primary">
                    <i class="fas fa-download"></i> Download
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Informasi Sertifikat</h5>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <th width="200">Nomor Sertifikat</th>
                                <td><?= $data['nomor_sertifikat'] ?></td>
                            </tr>
                            <tr>
                                <th>Nama KTH</th>
                                <td><?= $data['nama_kth'] ?></td>
                            </tr>
                            <tr>
                                <th>Tanggal Upload</th>
                                <td><?= date('d/m/Y H:i', strtotime($data['tanggal_upload'])) ?></td>
                            </tr>
                            <tr>
                                <th>Tipe File</th>
                                <td><?= strtoupper($file_ext) ?></td>
                            </tr>
                            <tr>
                                <th>Ukuran File</th>
                                <td><?= number_format(filesize($file_path) / 1024, 2) ?> KB</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td><span class="badge bg-success">Terverifikasi</span></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Informasi KTH</h5>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <th width="200">Email</th>
                                <td><?= $data['email'] ?></td>
                            </tr>
                            <tr>
                                <th>Alamat</th>
                                <td><?= $data['alamat'] ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Preview Sertifikat</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($file_ext == 'pdf'): ?>
                        <iframe src="<?= $file_path ?>" width="100%" height="500px" frameborder="0"></iframe>
                        <?php else: ?>
                        <img src="<?= $file_path ?>" class="img-fluid" alt="Preview Sertifikat">
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .table th {
        background-color: #f8f9fa;
    }

    .badge {
        font-size: 0.875rem;
        padding: 0.5em 0.75em;
    }

    .btn-group {
        gap: 0.5rem;
    }
    </style>
</body>

</html>