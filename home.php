<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include "../conn/koneksi.php";

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$role = $_SESSION['role'];

// Ambil data user dari database
$query_user = mysqli_query($conn, "SELECT * FROM userss WHERE id='$user_id'");
$user_data = mysqli_fetch_assoc($query_user);
$nama = $user_data['nama'];

// Jika admin, redirect ke halaman admin
if ($role === 'admin') {
    header("Location: ../Admin/dashboard.php");
    exit;
}

// Hitung jumlah sertifikat user
$query_count = mysqli_query($conn, "SELECT COUNT(*) as total FROM sertifikat WHERE user_id='$user_id'");
$total = mysqli_fetch_assoc($query_count)['total'];
?>

<?php
include '../includes/header.php';
?>
<?php
include '../includes/navbar.php';
?>

<div class="container " style="margin-top: 150px;">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>Selamat Datang, <?= htmlspecialchars($nama) ?>!</h2>
            <p class="text-muted">Kelola sertifikat KTH Anda dengan mudah</p>
        </div>
    </div>

    <!-- Statistik -->
    <div class="row mb-4">
        <div class="col-lg-4 col-12 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Sertifikat</h5>
                    <h2 class="card-text"><?= $total ?></h2>
                    <p class="card-text">Sertifikat yang telah diunggah</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-12 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Sertifikat Terbaru</h5>
                    <?php
                    $query_latest = mysqli_query($conn, "SELECT tanggal_upload FROM sertifikat WHERE user_id='$user_id' ORDER BY tanggal_upload DESC LIMIT 1");
                    $latest = mysqli_fetch_assoc($query_latest);
                    ?>
                    <h2 class="card-text"><?= $latest ? date('d/m/Y', strtotime($latest['tanggal_upload'])) : '-' ?>
                    </h2>
                    <p class="card-text">Tanggal upload terakhir</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-12 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Aksi Cepat</h5>
                    <a href="../user/upload.php" class="btn btn-light">Upload Baru</a>
                    <a href="../user/daftar.php" class="btn btn-light">Lihat Semua</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Sertifikat Terbaru -->
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0">Sertifikat Terbaru</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nama Kegiatan</th>
                            <th>Nomor Sertifikat</th>
                            <th>Tanggal Upload</th>
                            <th>File</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = mysqli_query($conn, "SELECT * FROM sertifikat WHERE user_id='$user_id' ORDER BY tanggal_upload DESC LIMIT 5");
                        while ($data = mysqli_fetch_array($query)) {
                            $file = $data['nama_file'];
                            $file_ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                            $is_image = in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                            $is_pdf = $file_ext === 'pdf';
                            $is_doc = in_array($file_ext, ['doc', 'docx']);
                            $is_excel = in_array($file_ext, ['xls', 'xlsx']);
                            $file_path = "../uploads/" . $file;

                            echo "<tr>
                                    <td>{$data['nama_kth']}</td>
                                    <td>{$data['nomor_sertifikat']}</td>
                                    <td>" . date('d/m/Y H:i', strtotime($data['tanggal_upload'])) . "</td>
                                    <td>
                                        <div class='btn-group' role='group'>";

                            if (file_exists($file_path)) {
                                if ($is_image || $is_pdf) {
                                    echo "<button 
                                        type='button' 
                                        class='btn btn-primary btn-sm viewBtn' 
                                        data-bs-toggle='modal' 
                                        data-bs-target='#viewModal' 
                                        data-nama='{$data['nama_kth']}'
                                        data-type='" . ($is_image ? "image" : "pdf") . "'
                                        data-src='{$file_path}'
                                    >
                                        <i class='fas fa-eye'></i> Lihat
                                    </button>
                                    <a href='{$file_path}' download class='btn btn-success btn-sm'>
                                        <i class='fas fa-download'></i> Download
                                    </a>";
                                } else {
                                    echo "<a href='{$file_path}' download class='btn btn-success btn-sm'>
                                            <i class='fas fa-download'></i> Download
                                        </a>";
                                }
                            } else {
                                echo "<span class='text-danger'>File tidak ditemukan</span>";
                            }

                            echo "</div>
                                    </td>
                                    <td>
                                        <a href='../user/edit.php?id={$data['id']}' class='btn btn-warning btn-sm'>
                                            <i class='fas fa-edit'></i> Edit
                                        </a>
                                        <a href='../user/hapus.php?id={$data['id']}' class='btn btn-danger btn-sm' onclick=\"return confirm('Yakin ingin menghapus sertifikat ini?')\">
                                            <i class='fas fa-trash'></i> Hapus
                                        </a>
                                    </td>
                                </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <?php if ($total > 5): ?>
            <div class="text-center mt-3">
                <a href="../user/daftar.php" class="btn btn-primary">Lihat Semua Sertifikat</a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php include "../includes/footer.php"; ?>

<!-- modalnya ku pindah kesini -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewModalLabel">Preview Sertifikat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center" id="modalContent">

            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.viewBtn').forEach(btn => {
    btn.addEventListener('click', function() {
        const nama = this.getAttribute('data-nama');
        const type = this.getAttribute('data-type');
        const src = this.getAttribute('data-src');


        document.getElementById('viewModalLabel').textContent = nama;

        let content = '';
        if (type === 'image') {
            content =
                `<img src="${src}" class="img-fluid" alt="${nama}" style="max-width: 100%; height: auto;">`;
        } else {
            content =
                `<iframe src="${src}" width="100%" height="500px" frameborder="0" style="border: none;"></iframe>`;
        }

        document.getElementById('modalContent').innerHTML = content;
    });
});
</script>


</body>

</html>

<style>
.modal-body img {
    max-width: 100%;
    height: auto;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
}

.modal-dialog {
    max-width: 800px;
}

.modal-body iframe {
    width: 100%;
    height: 500px;
    border: none;
}

.btn-group {
    display: inline-flex;
    gap: 5px;
}
</style>