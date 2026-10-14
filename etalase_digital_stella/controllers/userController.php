<?php
// controllers/userController.php
require_once 'config/database.php';

function getUsers($pdo) {
    $stmt = $pdo->query("SELECT id, nama, email, role FROM users ORDER BY id DESC");
    return $stmt->fetchAll();
}

function handleAddUser($pdo) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nama = trim($_POST['nama'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $role = $_POST['role'] ?? 'siswa';

        if (empty($nama) || empty($email) || empty($password)) {
            header('Location: index.php?page=users&error=Semua data wajib diisi!');
            exit;
        }

        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            header('Location: index.php?page=users&error=Email sudah terdaftar!');
            exit;
        }

        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (nama, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nama, $email, $hashed, $role]);

        header('Location: index.php?page=users&success=User berhasil ditambahkan!');
        exit;
    }
}

function handleDeleteUser($pdo) {
    $id = $_GET['id'] ?? null;
    $current_user = $_SESSION['user'];

    if ($id == $current_user['id']) {
        header('Location: index.php?page=users&error=Anda tidak dapat menghapus akun Anda sendiri!');
        exit;
    }

    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);

    header('Location: index.php?page=users&success=User berhasil dihapus!');
    exit;
}
