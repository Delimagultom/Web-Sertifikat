<?php
include "../conn/koneksi.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set zona waktu ke WITA
date_default_timezone_set('Asia/Makassar');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$id = $_GET['id'];

// Ambil data sertifikat
$query = mysqli_query($conn, "SELECT * FROM sertifikat WHERE id='$id' AND user_id='$user_id'");
$data = mysqli_fetch_array($query);

// Jika data tidak ditemukan atau bukan milik user ini
if (!$data) {
    header("Location: daftar.php");
    exit;
}

// Proses update data
if (isset($_POST['submit'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $nomor = mysqli_real_escape_string($conn, $_POST['nomor']);
    $tanggal_update = date('Y-m-d H:i:s'); // Waktu update dalam WITA

    // Jika ada file baru diupload
    if (!empty($_FILES['file']['name'])) {
        $file = $_FILES['file'];
        $file_name = $file['name'];
        $file_tmp = $file['tmp_name'];
        $file_size = $file['size'];
        $file_error = $file['error'];

        // Cek ekstensi file
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed = array('jpg', 'jpeg', 'png', 'pdf');

        if (in_array($file_ext, $allowed)) {
            if ($file_error === 0) {
                if ($file_size <= 5242880) { // 5MB
                    $file_name_new = uniqid('', true) . '.' . $file_ext;
                    $file_destination = '../uploads/' . $file_name_new;

                    if (move_uploaded_file($file_tmp, $file_destination)) {
                        // Hapus file lama
                        if (file_exists('../uploads/' . $data['nama_file'])) {
                            unlink('../uploads/' . $data['nama_file']);
                        }

                        // Update database dengan file baru
                        $query = mysqli_query($conn, "UPDATE sertifikat SET 
                            nama_kth='$nama', 
                            nomor_sertifikat='$nomor', 
                            nama_file='$file_name_new',
                            tanggal_update=NOW()
                            WHERE id='$id' AND user_id='$user_id'");
                    }
                }
            }
        }
    } else {
        // Update tanpa file baru
        $query = mysqli_query($conn, "UPDATE sertifikat SET 
            nama_kth='$nama', 
            nomor_sertifikat='$nomor',
            tanggal_update=NOW()
            WHERE id='$id' AND user_id='$user_id'");
    }

    if ($query) {
        header("Location: daftar.php?pesan=sukses");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Sertifikat - Penyimpanan Sertifikat KTH</title>
    <link rel="stylesheet" href="../style/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <?php include '../includes/header.php'; ?>
    <?php include '../includes/navbar.php'; ?>

    <div class="container" style="margin-top: 150px;">
        <div class="row mb-4">
            <div class="col-md-12">
                <h2>Edit Sertifikat</h2>
                <p class="text-muted">Ubah informasi sertifikat KTH Anda</p>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Kegiatan</label>
                        <input type="text" class="form-control" id="nama" name="nama"
                            value="<?= htmlspecialchars($data['nama_kth']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="nomor" class="form-label">Nomor Sertifikat</label>
                        <input type="text" class="form-control" id="nomor" name="nomor"
                            value="<?= htmlspecialchars($data['nomor_sertifikat']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="file" class="form-label">File Sertifikat (Opsional)</label>
                        <input type="file" class="form-control" id="file" name="file" accept=".pdf,.jpg,.jpeg,.png">
                        <div class="form-text">Biarkan kosong jika tidak ingin mengubah file. Format yang didukung: PDF,
                            JPG, JPEG, PNG. Maksimal ukuran file: 5MB</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">File Saat Ini</label>
                        <div>
                            <?php
                            $file_path = "../uploads/" . $data['nama_file'];
                            $file_ext = strtolower(pathinfo($data['nama_file'], PATHINFO_EXTENSION));
                            $is_image = in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif']);
                            $is_pdf = $file_ext === 'pdf';

                            if (file_exists($file_path)) {
                                if ($is_image) {
                                    echo "<img src='$file_path' class='img-thumbnail' style='max-height: 200px;'>";
                                } elseif ($is_pdf) {
                                    echo "<a href='$file_path' target='_blank' class='btn btn-primary btn-sm'>
                                            <i class='fas fa-file-pdf'></i> Lihat PDF
                                          </a>";
                                } else {
                                    echo "<a href='$file_path' download class='btn btn-secondary btn-sm'>
                                            <i class='fas fa-download'></i> Download File
                                          </a>";
                                }
                            } else {
                                echo "<span class='text-danger'>File tidak ditemukan</span>";
                            }
                            ?>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" name="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                        <a href="daftar.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>