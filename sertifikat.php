<?php
session_start();
include "../koneksi.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Handle delete certificate
if (isset($_GET['delete'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    $cert = mysqli_fetch_assoc(mysqli_query($conn, "SELECT file_path FROM sertifikat WHERE id = '$id'"));

    if ($cert && file_exists("../" . $cert['file_path'])) {
        unlink("../" . $cert['file_path']);
    }

    mysqli_query($conn, "DELETE FROM sertifikat WHERE id = '$id'");
    header("Location: sertifikat.php");
    exit;
}

// Get all certificates with pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 10;
$start = ($page - 1) * $per_page;

$total_certificates = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM sertifikat"))['total'];
$total_pages = ceil($total_certificates / $per_page);

$certificates = mysqli_query($conn, "SELECT s.*, u.nama_kth 
    FROM sertifikat s 
    JOIN users u ON s.user_id = u.id 
    ORDER BY s.tanggal_upload DESC 
    LIMIT $start, $per_page");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Sertifikat - Admin Panel</title>
    <link rel="stylesheet" href="../style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <header>
        <div class="nav-container">
            <div class="d-flex justify-content-between align-items-center">
                <div class="logo">
                    <img src="../images/logo_dishut.png" alt="Logo Dinas Kehutanan Kaltim">
                    <div class="logo-text">
                        <span>DINAS KEHUTANAN</span>
                        <span>PROVINSI KALIMANTAN TIMUR</span>
                    </div>
                </div>
                <nav>
                    <a href="dashboard.php">Dashboard</a>
                    <a href="users.php">Users</a>
                    <a href="sertifikat.php" class="active">Sertifikat</a>
                    <a href="monitoring.php">Monitoring</a>
                    <a href="logout.php">Logout</a>
                </nav>
            </div>
        </div>
    </header>

    <div class="container mt-5">
        <div class="row mb-4">
            <div class="col-md-12">
                <h2>Kelola Sertifikat</h2>
                <p class="text-muted">Total Sertifikat: <?= $total_certificates ?></p>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama KTH</th>
                                <th>Nama Acara</th>
                                <th>Nomor Sertifikat</th>
                                <th>Tipe File</th>
                                <th>Tanggal Upload</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($cert = mysqli_fetch_assoc($certificates)): ?>
                                <tr>
                                    <td><?= $cert['id'] ?></td>
                                    <td><?= htmlspecialchars($cert['nama_kth']) ?></td>
                                    <td><?= htmlspecialchars($cert['nama_acara']) ?></td>
                                    <td><?= htmlspecialchars($cert['nomor_sertifikat']) ?></td>
                                    <td><?= ucfirst($cert['file_type']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($cert['tanggal_upload'])) ?></td>
                                    <td>
                                        <a href="../<?= $cert['file_path'] ?>" class="btn btn-sm btn-info" target="_blank">
                                            <i class="fas fa-eye"></i> Lihat
                                        </a>
                                        <a href="../<?= $cert['file_path'] ?>" class="btn btn-sm btn-success" download>
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                        <a href="sertifikat.php?delete=<?= $cert['id'] ?>" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus sertifikat ini?')">
                                            <i class="fas fa-trash"></i> Hapus
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                    <nav aria-label="Page navigation" class="mt-4">
                        <ul class="pagination justify-content-center">
                            <?php if ($page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?= $page - 1 ?>">Previous</a>
                                </li>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($page < $total_pages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?= $page + 1 ?>">Next</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php include "../footer.php"; ?>
</body>

</html>