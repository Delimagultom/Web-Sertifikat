<?php
$current_year = date('Y');
?>
<footer class="footer">
    <div class="footer-container">
        <div class="d-flex justify-content-center align-items-center">
            <div class="footer-text text-center">
                <span>© <?= $current_year ?> Dinas Kehutanan Provinsi Kalimantan Timur</span>
            </div>
        </div>
    </div>
</footer>

<style>
.footer {
    background: linear-gradient(to right, var(--forest-primary), var(--forest-secondary));
    color: white;
    padding: 1rem 0;
    position: fixed;
    bottom: 0;
    width: 100%;
    box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.1);
}

.footer-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

.footer-text {
    font-size: 0.9rem;
    font-weight: 500;
    width: 100%;
}

.footer-links {
    display: flex;
    gap: 1.5rem;
}

.footer-links a {
    color: white;
    text-decoration: none;
    font-size: 0.9rem;
    opacity: 0.9;
    transition: opacity 0.3s ease;
}

.footer-links a:hover {
    opacity: 1;
}

/* Tambahkan padding bottom pada container utama agar konten tidak tertutup footer */
.container {
    padding-bottom: 4rem;
}

@media (max-width: 768px) {
    .footer-container {
        text-align: center;
    }

    .footer-text {
        font-size: 0.8rem;
    }
}
</style>