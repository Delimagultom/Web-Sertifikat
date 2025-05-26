<?php
include "../conn/koneksi.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<?php
    include '../includes/header.php';
?>
   <?php
    include '../includes/navbar.php';
   ?>

    <div class="container" style="margin-top: 150px">
        <div class="row mb-4">
            <div class="col-md-12">
                <h2>Upload Sertifikat</h2>
                <p class="text-muted">Unggah sertifikat KTH Anda dengan mudah</p>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'sukses'): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> Sertifikat berhasil diunggah!
                </div>
                <?php endif; ?>

                <form action="proses_upload.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Kegiatan</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="nomor" class="form-label">Nomor Sertifikat</label>
                        <input type="text" class="form-control" id="nomor" name="nomor" required>
                    </div>
                    <div class="mb-3">
                        <label for="file" class="form-label">Upload File (PDF/JPG/PNG)</label>
                        <input type="file" class="form-control" id="file" name="file" accept=".pdf,.jpg,.jpeg,.png"
                            required>
                        <div class="form-text">Format yang didukung: PDF, JPG, JPEG, PNG. Maksimal ukuran file: 5MB
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload"></i> Upload Sertifikat
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php include "../includes/footer.php"; ?>
</body>

</html>