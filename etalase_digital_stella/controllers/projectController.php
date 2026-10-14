<?php
// controllers/projectController.php
require_once 'config/database.php';

function getProjects($pdo) {
    $stmt = $pdo->query("SELECT projects.*, users.nama as pembuat, users.role as role_pembuat 
                         FROM projects 
                         JOIN users ON projects.user_id = users.id 
                         ORDER BY projects.created_at DESC");
    return $stmt->fetchAll();
}

function handleAddProject($pdo) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nama_project = trim($_POST['nama_project'] ?? '');
        $deskripsi = trim($_POST['deskripsi'] ?? '');
        $link_project = trim($_POST['link_project'] ?? '');
        $link_vercel = trim($_POST['link_vercel'] ?? '');
        $user_id = $_SESSION['user']['id'];

        // Validasi input utama (minimal salah satu link diisi atau sesuai kebutuhan, misal link github wajib atau opsional)
        if (empty($nama_project) || empty($deskripsi) || empty($_FILES['gambar']['name'])) {
            header('Location: index.php?page=projects&error=Nama, deskripsi, dan gambar wajib diisi!');
            exit;
        }

        $target_dir = "uploads/";
        $file_ext = strtolower(pathinfo($_FILES["gambar"]["name"], PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (!in_array($file_ext, $allowed_ext)) {
            header('Location: index.php?page=projects&error=Format gambar tidak valid!');
            exit;
        }

        $new_filename = time() . '_' . uniqid() . '.' . $file_ext;
        if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_dir . $new_filename)) {
            $stmt = $pdo->prepare("INSERT INTO projects (user_id, nama_project, deskripsi, gambar, link_project, link_vercel) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$user_id, $nama_project, $deskripsi, $new_filename, $link_project, $link_vercel]);
            header('Location: index.php?page=projects&success=Karya digital berhasil ditambahkan!');
            exit;
        }
    }
}

function handleUpdateProject($pdo) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'] ?? null;
        $nama_project = trim($_POST['nama_project'] ?? '');
        $deskripsi = trim($_POST['deskripsi'] ?? '');
        $link_project = trim($_POST['link_project'] ?? '');
        $link_vercel = trim($_POST['link_vercel'] ?? '');
        $current_user = $_SESSION['user'];

        $stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
        $stmt->execute([$id]);
        $project = $stmt->fetch();

        if (!$project) { header('Location: index.php?page=projects&error=Proyek tidak ditemukan'); exit; }

        if ($current_user['role'] === 'siswa' && $project['user_id'] != $current_user['id']) {
            header('Location: index.php?page=projects&error=Akses Ditolak! Anda tidak dapat mengubah karya siswa lain.');
            exit;
        }

        $filename = $project['gambar'];

        if (!empty($_FILES['gambar']['name'])) {
            $target_dir = "uploads/";
            $file_ext = strtolower(pathinfo($_FILES["gambar"]["name"], PATHINFO_EXTENSION));
            if (in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $new_filename = time() . '_' . uniqid() . '.' . $file_ext;
                if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_dir . $new_filename)) {
                    if (file_exists("uploads/" . $project['gambar'])) { unlink("uploads/" . $project['gambar']); }
                    $filename = $new_filename;
                }
            }
        }

        $stmt = $pdo->prepare("UPDATE projects SET nama_project = ?, deskripsi = ?, gambar = ?, link_project = ?, link_vercel = ? WHERE id = ?");
        $stmt->execute([$nama_project, $deskripsi, $filename, $link_project, $link_vercel, $id]);
        header('Location: index.php?page=projects&success=Karya berhasil diperbarui!');
        exit;
    }
}

function handleDeleteProject($pdo) {
    $id = $_GET['id'] ?? null;
    $current_user = $_SESSION['user'];

    $stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
    $stmt->execute([$id]);
    $project = $stmt->fetch();

    if ($project) {
        if ($current_user['role'] === 'siswa' && $project['user_id'] != $current_user['id']) {
            header('Location: index.php?page=projects&error=Akses Ditolak!');
            exit;
        }
        if (file_exists("uploads/" . $project['gambar'])) { unlink("uploads/" . $project['gambar']); }
        $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
        $stmt->execute([$id]);
    }
    header('Location: index.php?page=projects&success=Karya berhasil dihapus!');
    exit;
}