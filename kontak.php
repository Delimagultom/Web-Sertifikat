<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontak - Dinas Kehutanan Samarinda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../style/style.css">
    <style>
    .social-media-container {
        display: flex;
        justify-content: center;
        gap: 2.5rem;
        margin: 2rem 0;
    }

    .social-icon {
        font-size: 1.5rem;
        color: var(--forest-primary);
        transition: all 0.3s;
        background: #f8f9fa;
        width: 65px;
        height: 65px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        border: 1px solid #c8e6c9;
        text-decoration: none;
    }

    .social-icon:hover {
        color: white;
        background: var(--forest-primary);
        transform: translateY(-5px);
        text-decoration: none;
    }

    .social-icon i {
        margin: 0;
    }
    </style>
</head>

<body>
    <?php include '../includes/navbar.php'; ?>

    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card contact-card">
                    <div class="card-body p-5">
                        <h2 class="section-title">Hubungi Kami</h2>

                        <div class="row">
                            <!-- Alamat -->
                            <div class="col-md-6">
                                <div class="contact-info">
                                    <h5><i class="fas fa-map-marker-alt"></i> Alamat</h5>
                                    <p class="mb-0">
                                        Jl. Kesuma Bangsa No.1, Samarinda<br>
                                        Kalimantan Timur<br>
                                        Kode Pos: 75123
                                    </p>
                                </div>
                            </div>

                            <!-- Kontak -->
                            <div class="col-md-6">
                                <div class="contact-info">
                                    <h5><i class="fas fa-phone"></i> Kontak</h5>
                                    <p class="mb-0">
                                        Telepon: (0541) 7777777<br>
                                        Email: dishut.kaltim@gmail.com
                                    </p>
                                </div>
                            </div>

                            <!-- Jam Operasional -->
                            <div class="col-12">
                                <div class="contact-info">
                                    <h5><i class="fas fa-clock"></i> Jam Operasional</h5>
                                    <p class="mb-0">
                                        Senin - Jumat: 08:00 - 16:30 WITA<br>
                                        Sabtu - Minggu: Tutup
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Media Sosial -->
                        <div class="text-center">
                            <h5 class="section-title">Ikuti Kami di Media Sosial</h5>
                            <div class="social-media-container">
                                <a href="https://dishut.kaltimprov.go.id/" target="_blank" class="social-icon"
                                    title="Website Resmi Dinas Kehutanan Kaltim">
                                    <i class="fas fa-globe"></i>
                                </a>
                                <a href="https://instagram.com/dishutkaltim" target="_blank" class="social-icon"
                                    title="Instagram">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a href="https://youtube.com/@dishutkaltim" target="_blank" class="social-icon"
                                    title="YouTube">
                                    <i class="fab fa-youtube"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Peta Lokasi -->
                        <div class="mt-5">
                            <h5 class="section-title">Lokasi Kami</h5>
                            <div class="map-container">
                                <div class="ratio ratio-16x9">
                                    <iframe
                                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.6744874711397!2d117.1427!3d-0.5022!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMMKwMzAnMDcuOSJTIDExN8KwMDgnMzcuNyJF!5e0!3m2!1sid!2sid!4v1635000000000!5m2!1sid!2sid"
                                        style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>