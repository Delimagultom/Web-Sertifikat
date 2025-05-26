<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include "koneksi.php";
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Penyimpanan Sertifikat KTH</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <header>
        <div class="nav-container">
            <img src="logo.jpg" alt="Logo KTH" class="logo-img">
            <nav>
                <a href="index.php">Home</a>
                <a href="User/upload.php">Upload Sertifikat</a>
                <a href="User/daftar.php">Daftar Sertifikat</a>
                <a href="User/kontak.php">Kontak</a>
                <a href="logout.php">Logout</a>
            </nav>
        </div>
    </header>

    <section class="hero">
        <div class="hero-text">
            <h1>Penyimpanan Sertifikat<br><span>Kelompok Tani Hutan</span></h1>
            <p>Unggah dan simpan sertifikat kelompok tani hutan Anda dengan aman dan mudah.</p>
        </div>
        <div class="hero-icon">
            <img src="https://img.icons8.com/ios-filled/100/000000/certificate.png" alt="Ikon Sertifikat">
        </div>
    </section>

    <main class="container mt-4 mb-5">
        <h2>Selamat Datang, <?= $_SESSION['nama_kth'] ?>!</h2>
        <?php include "main.php"; ?>
    </main>

    <!-- Modal Preview -->
    <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Preview Sertifikat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body text-center">
                    <iframe id="previewPDF" src="" width="100%" height="500px" style="display:none;"
                        frameborder="0"></iframe>
                    <img id="previewImage" src="" class="img-fluid" style="display:none;" alt="Preview Sertifikat">
                </div>
            </div>
        </div>
    </div>

    <script>
    function showPreview(fileUrl) {
        const ext = fileUrl.split('.').pop().toLowerCase();
        const img = document.getElementById('previewImage');
        const pdf = document.getElementById('previewPDF');

        if (ext === 'pdf') {
            img.style.display = 'none';
            pdf.src = fileUrl;
            pdf.style.display = 'block';
        } else {
            pdf.style.display = 'none';
            img.src = fileUrl;
            img.style.display = 'block';
        }

        const modal = new bootstrap.Modal(document.getElementById('previewModal'));
        modal.show();
    }
    </script>
</body>

</html>