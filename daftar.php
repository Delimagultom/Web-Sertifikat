<?php
include "../conn/koneksi.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Konfigurasi pagination
$records_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

// Hitung total records
$total_records_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM sertifikat WHERE user_id='$user_id'");
$total_records = mysqli_fetch_assoc($total_records_query)['total'];
$total_pages = ceil($total_records / $records_per_page);

// Query untuk data dengan pagination
$query = mysqli_query($conn, "SELECT * FROM sertifikat WHERE user_id='$user_id' ORDER BY id DESC LIMIT $offset, $records_per_page");
?>


<?php
include '../includes/header.php';
?>
<?php
include '../includes/navbar.php';
?>


<div class="container" style="margin-top: 150px">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>Daftar Sertifikat</h2>
            <p class="text-muted">Kelola semua sertifikat KTH Anda</p>
        </div>
    </div>

    <div class="table-info">
        <div class="row">
            <div class="col-md-6">
                <p class="mb-0">Total Sertifikat: <strong><?= $total_records ?></strong></p>
            </div>
            <div class="col-md-6 text-end">
                <p class="mb-0">Halaman <?= $page ?> dari <?= $total_pages ?></p>
            </div>
        </div>
    </div>

    <div class="card">
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
                        while ($data = mysqli_fetch_array($query)) {
                            $file = $data['nama_file'];
                            $file_path = "../uploads/" . $file;
                            $file_ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                            $is_image = in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif']);
                            $is_pdf = $file_ext === 'pdf';

                            echo "<tr>
                                        <td>{$data['nama_kth']}</td>
                                        <td>{$data['nomor_sertifikat']}</td>
                                        <td>" . date('d/m/Y H:i', strtotime($data['tanggal_upload'])) . "</td>
                                        <td>";

                            if (file_exists($file_path)) {
                                if ($is_image) {
                                    echo "<div class='btn-group' role='group'>
                                                <button type='button' class='btn btn-primary btn-sm view-image-btn' data-bs-toggle='modal' data-bs-target='#viewModal{$data['id']}' data-image='{$file_path}'>
                                                    <i class='fas fa-eye'></i> Lihat
                                                </button>
                                                <a href='{$file_path}' download class='btn btn-success btn-sm'>
                                                    <i class='fas fa-download'></i> Download
                                                </a>
                                              </div>";

                                    // Modal untuk preview file
                                    echo "<div class='modal fade' id='viewModal{$data['id']}' tabindex='-1' aria-labelledby='viewModalLabel{$data['id']}' aria-hidden='true'>
                                            <div class='modal-dialog modal-lg modal-dialog-centered'>
                                                <div class='modal-content'>
                                                    <div class='modal-header'>
                                                        <h5 class='modal-title' id='viewModalLabel{$data['id']}'>{$data['nama_kth']}</h5>
                                                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                                    </div>
                                                    <div class='modal-body text-center'>
                                                        <img src='{$file_path}' class='img-fluid preview-image' alt='Sertifikat {$data['nama_kth']}' style='max-width: 100%; height: auto;'>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>";
                                } elseif ($is_pdf) {
                                    echo "<div class='btn-group' role='group'>
                                                <button type='button' class='btn btn-primary btn-sm' data-bs-toggle='modal' data-bs-target='#viewModal{$data['id']}'>
                                                    <i class='fas fa-eye'></i> Lihat
                                                </button>
                                                <a href='{$file_path}' download class='btn btn-success btn-sm'>
                                                    <i class='fas fa-download'></i> Download
                                                </a>
                                              </div>";

                                    // Modal untuk preview PDF
                                    echo "<div class='modal fade' id='viewModal{$data['id']}' tabindex='-1' aria-labelledby='viewModalLabel{$data['id']}' aria-hidden='true'>
                                            <div class='modal-dialog modal-lg modal-dialog-centered'>
                                                <div class='modal-content'>
                                                    <div class='modal-header'>
                                                        <h5 class='modal-title' id='viewModalLabel{$data['id']}'>{$data['nama_kth']}</h5>
                                                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                                    </div>
                                                    <div class='modal-body'>
                                                        <iframe src='{$file_path}' width='100%' height='500px' frameborder='0' style='border: none;'></iframe>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>";
                                } else {
                                    echo "<a href='{$file_path}' download class='btn btn-success btn-sm'>
                                                <i class='fas fa-download'></i> Download
                                              </a>";
                                }
                            } else {
                                echo "<span class='text-danger'>File tidak ditemukan</span>";
                            }

                            echo "</td>
                                        <td>
                                            <a href='edit.php?id={$data['id']}' class='btn btn-warning btn-sm'>
                                                <i class='fas fa-edit'></i> Edit
                                            </a>
                                            <a href='hapus.php?id={$data['id']}' class='btn btn-danger btn-sm' onclick=\"return confirm('Yakin hapus?')\">
                                                <i class='fas fa-trash'></i> Hapus
                                            </a>
                                        </td>
                                      </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <?php if ($total_pages > 1): ?>
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=1" aria-label="First">
                            <span aria-hidden="true">&laquo;&laquo;</span>
                        </a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $page - 1 ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php
                        $start_page = max(1, $page - 2);
                        $end_page = min($total_pages, $page + 2);

                        for ($i = $start_page; $i <= $end_page; $i++):
                        ?>
                    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $page + 1 ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $total_pages ?>" aria-label="Last">
                            <span aria-hidden="true">&raquo;&raquo;</span>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.image-container {
    position: relative;
    min-height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.preview-image {
    max-width: 100%;
    height: auto;
    transition: opacity 0.3s ease;
}

.modal-dialog {
    max-width: 90%;
    margin: 1.75rem auto;
}

.modal-content {
    background-color: #fff;
    border-radius: 0.5rem;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.modal-body {
    padding: 1rem;
    overflow: hidden;
}

/* Loading indicator */
.image-container::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 40px;
    height: 40px;
    border: 4px solid #f3f3f3;
    border-top: 4px solid #3498db;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    display: none;
}

.image-container.loading::before {
    display: block;
}

@keyframes spin {
    0% {
        transform: translate(-50%, -50%) rotate(0deg);
    }

    100% {
        transform: translate(-50%, -50%) rotate(360deg);
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const viewButtons = document.querySelectorAll('.view-image-btn');

    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const imagePath = this.getAttribute('data-image');
            const modalId = this.getAttribute('data-bs-target');
            const modal = document.querySelector(modalId);
            const imageContainer = modal.querySelector('.image-container');
            const img = modal.querySelector('.preview-image');

            // Tampilkan loading indicator
            imageContainer.classList.add('loading');

            // Preload image
            const tempImage = new Image();
            tempImage.onload = function() {
                img.src = imagePath;
                imageContainer.classList.remove('loading');
            };
            tempImage.onerror = function() {
                imageContainer.classList.remove('loading');
                img.src = '../images/error.png'; // Fallback image
            };
            tempImage.src = imagePath;
        });
    });

    // Optimize modal handling
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        modal.addEventListener('hidden.bs.modal', function() {
            const img = this.querySelector('.preview-image');
            if (img) {
                img.src = '';
            }
        });
    });
});
</script>

<?php include "../includes/footer.php"; ?>
</body>

</html>