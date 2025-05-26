<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/login.php");
    exit();
}

$host = "localhost";
$username = "root";
$password = "";
$database = "dbsertifikat";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Pagination settings
$records_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

// Total KTH
$query_total_members = "SELECT COUNT(*) as total_kth FROM userss WHERE role = 'kth'";
$result_total_members = mysqli_query($conn, $query_total_members);
$total_members = mysqli_fetch_assoc($result_total_members)['total_kth'];

// Total sertifikat
$query_total_certificates = "SELECT COUNT(*) as total_certificates FROM sertifikat";
$result_total_certificates = mysqli_query($conn, $query_total_certificates);
$total_certificates = mysqli_fetch_assoc($result_total_certificates)['total_certificates'];

// Upload terbaru dengan pagination
$query_recent_uploads = "SELECT * FROM sertifikat ORDER BY tanggal_upload DESC LIMIT $offset, $records_per_page";
$result_recent_uploads = mysqli_query($conn, $query_recent_uploads);

// Hitung total halaman untuk upload terbaru
$query_total_recent = "SELECT COUNT(*) as total FROM sertifikat";
$result_total_recent = mysqli_query($conn, $query_total_recent);
$total_recent = mysqli_fetch_assoc($result_total_recent)['total'];
$total_pages_recent = ceil($total_recent / $records_per_page);

// Statistik upload per hari dengan pagination
$query_uploads_per_day = "
    SELECT nama_kth, DATE(tanggal_upload) AS tanggal, COUNT(*) AS jumlah_upload
    FROM sertifikat
    GROUP BY nama_kth, DATE(tanggal_upload)
    ORDER BY tanggal_upload DESC
    LIMIT $offset, $records_per_page
";
$result_uploads_per_day = mysqli_query($conn, $query_uploads_per_day);

// Hitung total halaman untuk statistik harian
$query_total_stats = "
    SELECT COUNT(*) as total FROM (
        SELECT nama_kth, DATE(tanggal_upload) AS tanggal
        FROM sertifikat
        GROUP BY nama_kth, DATE(tanggal_upload)
    ) as stats
";
$result_total_stats = mysqli_query($conn, $query_total_stats);
$total_stats = mysqli_fetch_assoc($result_total_stats)['total'];
$total_pages_stats = ceil($total_stats / $records_per_page);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - Sistem Sertifikat KTH</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
    .nav-container {
        background-color: #198754;
        /* Hijau Bootstrap */
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 2rem;
        color: white;
    }

    .nav-container .logo {
        display: flex;
        align-items: center;
    }

    .nav-container .logo img {
        height: 50px;
        margin-right: 10px;
    }

    .nav-container nav a {
        color: white;
        margin-left: 20px;
        text-decoration: none;
    }

    .nav-container nav a.active {
        font-weight: bold;
        text-decoration: underline;
    }

    .pagination {
        margin-top: 1rem;
        justify-content: center;
    }

    .pagination .page-link {
        color: #198754;
    }

    .pagination .page-item.active .page-link {
        background-color: #198754;
        border-color: #198754;
    }
    </style>
</head>

<body>

    <header>
        <div class="nav-container">
            <div class="logo">
                <img src="../images/logo.jpg" alt="Logo">
                <div>
                    <strong>Sistem Sertifikat KTH</strong>
                </div>
            </div>
            <nav>
                <a href="#" class="link active">Dashboard</a>
                <a href="../pages/logout.php" class="link">Logout</a>
            </nav>
        </div>
    </header>

    <main class="container mt-4">
        <h2 class="mb-4">Statistik Sertifikat KTH</h2>
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5>Total KTH</h5>
                        <h2><?php echo $total_members; ?></h2>
                        <p>Kelompok Tani Hutan Terdaftar</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5>Total Sertifikat</h5>
                        <h2><?php echo $total_certificates; ?></h2>
                        <p>Sertifikat Terupload</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">Upload Sertifikat Terbaru</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nama Kegiatan</th>
                                <th>Nomor Sertifikat</th>
                                <th>Tanggal Upload</th>
                                <th>Nama File</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result_recent_uploads)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['nama_kth']); ?></td>
                                <td><?php echo htmlspecialchars($row['nomor_sertifikat']); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($row['tanggal_upload'])); ?></td>
                                <td><?php echo htmlspecialchars($row['nama_file']); ?></td>
                                <td>
                                    <a href="../pages/download_certificate.php?id=<?php echo $row['id']; ?>"
                                        class="btn btn-sm btn-primary">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <?php if ($total_pages_recent > 1): ?>
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page - 1; ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages_recent; $i++): ?>
                        <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                        <?php endfor; ?>

                        <?php if ($page < $total_pages_recent): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page + 1; ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </nav>
                <?php endif; ?>
            </div>
        </div>

        <div class="card mb-5">
            <div class="card-header">Statistik Upload Harian</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Kegiatan</th>
                                <th>Tanggal Upload</th>
                                <th>Jumlah Upload</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result_uploads_per_day)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['nama_kth']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($row['tanggal'])); ?></td>
                                <td><?php echo $row['jumlah_upload']; ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <?php if ($total_pages_stats > 1): ?>
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page - 1; ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages_stats; $i++): ?>
                        <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                        <?php endfor; ?>

                        <?php if ($page < $total_pages_stats): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page + 1; ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </nav>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <footer class="py-3 text-center bg-success text-white">
        <div class="container">
            <div>
                <a href="https://www.facebook.com/dishutkaltim" target="_blank" class="text-white me-3">
                    <i class="fab fa-facebook fa-lg"></i>
                </a>
                <a href="https://www.instagram.com/dishutkaltim" target="_blank" class="text-white me-3">
                    <i class="fab fa-instagram fa-lg"></i>
                </a>
                <a href="https://www.youtube.com/@dinaskehutananprovinsikal1974" target="_blank" class="text-white">
                    <i class="fab fa-youtube fa-lg"></i>
                </a>
            </div>
            <p class="mt-2 mb-0">&copy; <?php echo date('Y'); ?> Dinas Kehutanan Kalimantan Timur</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>