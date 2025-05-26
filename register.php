<?php
session_start();
include "../conn/koneksi.php";

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: ../Admin/home.php");
    } else {
        header("Location: ../User/home.php");
    }
    exit;
}

// Cek apakah ada parameter role=admin di URL
if (isset($_GET['role']) && $_GET['role'] === 'admin') {
    header("Location: login.php");
    exit;
}

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);

    // Validasi input
    if (empty($username) || empty($password) || empty($confirm_password) || empty($nama)) {
        $error = "Semua field harus diisi!";
    } elseif ($password !== $confirm_password) {
        $error = "Password dan konfirmasi password tidak cocok!";
    } elseif (strlen($password) < 8) {
        $error = "Password minimal 8 karakter!";
    } elseif (!preg_match("/[A-Z]/", $password)) {
        $error = "Password harus mengandung huruf besar!";
    } elseif (!preg_match("/[a-z]/", $password)) {
        $error = "Password harus mengandung huruf kecil!";
    } elseif (!preg_match("/[0-9]/", $password)) {
        $error = "Password harus mengandung angka!";
    } else {
        // Cek username sudah ada atau belum
        $stmt = $conn->prepare("SELECT id FROM userss WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Username sudah digunakan!";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert user baru
            $stmt = $conn->prepare("INSERT INTO userss (nama, username, password, role, aktif) VALUES (?, ?, ?, 'kth', 'Y')");
            $stmt->bind_param("sss", $nama, $username, $hashed_password);

            if ($stmt->execute()) {
                $_SESSION['success'] = "Registrasi berhasil! Silahkan login.";
                header("Location: login.php");
                exit;
            } else {
                $error = "Error: " . $stmt->error;
            }
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Sistem Sertifikat KTH</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../style/style.css">
</head>

<body class="login-page">
    <div class="register-container fade-in">
        <div class="card">
            <div class="logo-container">
                <img src="../images/logo.jpg" alt="Logo" class="logo-img">
                <h2>Register</h2>
                <p class="text-muted">Buat akun baru untuk bergabung</p>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST" action="" id="registerForm">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Lengkap</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-at"></i></span>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="password-requirements">
                            <small class="text-muted">Password harus memenuhi kriteria berikut:</small>
                            <ul class="mb-0 mt-1">
                                <li id="length">Minimal 8 karakter</li>
                                <li id="uppercase">Mengandung huruf besar</li>
                                <li id="lowercase">Mengandung huruf kecil</li>
                                <li id="number">Mengandung angka</li>
                            </ul>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Konfirmasi Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                                required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success w-100 btn-login">
                        <i class="fas fa-user-plus me-2"></i> Daftar
                    </button>
                </form>

                <div class="register-link">
                    <p class="mb-0">Sudah punya akun? <a href="login.php">Login disini</a></p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirm_password');
        const form = document.getElementById('registerForm');
        const requirements = {
            length: document.getElementById('length'),
            uppercase: document.getElementById('uppercase'),
            lowercase: document.getElementById('lowercase'),
            number: document.getElementById('number')
        };

        function checkPassword() {
            const value = password.value;

            // Check length
            if (value.length >= 8) {
                requirements.length.classList.add('valid');
                requirements.length.classList.remove('invalid');
            } else {
                requirements.length.classList.add('invalid');
                requirements.length.classList.remove('valid');
            }

            // Check uppercase
            if (/[A-Z]/.test(value)) {
                requirements.uppercase.classList.add('valid');
                requirements.uppercase.classList.remove('invalid');
            } else {
                requirements.uppercase.classList.add('invalid');
                requirements.uppercase.classList.remove('valid');
            }

            // Check lowercase
            if (/[a-z]/.test(value)) {
                requirements.lowercase.classList.add('valid');
                requirements.lowercase.classList.remove('invalid');
            } else {
                requirements.lowercase.classList.add('invalid');
                requirements.lowercase.classList.remove('valid');
            }

            // Check number
            if (/[0-9]/.test(value)) {
                requirements.number.classList.add('valid');
                requirements.number.classList.remove('invalid');
            } else {
                requirements.number.classList.add('invalid');
                requirements.number.classList.remove('valid');
            }
        }

        password.addEventListener('input', checkPassword);

        form.addEventListener('submit', function(e) {
            const value = password.value;

            if (value.length < 8) {
                e.preventDefault();
                alert('Password minimal 8 karakter!');
                return;
            }

            if (!/[A-Z]/.test(value)) {
                e.preventDefault();
                alert('Password harus mengandung huruf besar!');
                return;
            }

            if (!/[a-z]/.test(value)) {
                e.preventDefault();
                alert('Password harus mengandung huruf kecil!');
                return;
            }

            if (!/[0-9]/.test(value)) {
                e.preventDefault();
                alert('Password harus mengandung angka!');
                return;
            }

            if (password.value !== confirmPassword.value) {
                e.preventDefault();
                alert('Password dan konfirmasi password tidak cocok!');
            }
        });
    });
    </script>
</body>

</html>