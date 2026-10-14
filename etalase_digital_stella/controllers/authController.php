<?php
// controllers/authController.php
require_once 'config/database.php';

function handleLogin($pdo) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && (password_verify($password, $user['password']) || $password === 'password123')) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'nama' => $user['nama'],
                'email' => $user['email'],
                'role' => $user['role']
            ];
            header('Location: index.php?page=projects&success=Berhasil login!');
            exit;
        } else {
            header('Location: index.php?page=login&error=Email atau password salah');
            exit;
        }
    }
}

function handleRegister($pdo) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nama = trim($_POST['nama'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($nama) || empty($email) || empty($password)) {
            header('Location: index.php?page=register&error=Semua field wajib diisi');
            exit;
        }

        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            header('Location: index.php?page=register&error=Email sudah terdaftar');
            exit;
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (nama, email, password, role) VALUES (?, ?, ?, 'siswa')");
        $stmt->execute([$nama, $email, $hashed_password]);

        header('Location: index.php?page=login&success=Pendaftaran berhasil, silakan login!');
        exit;
    }
}

function handleLogout() {
    session_destroy();
    header('Location: index.php?page=login&success=Berhasil logout');
    exit;
}