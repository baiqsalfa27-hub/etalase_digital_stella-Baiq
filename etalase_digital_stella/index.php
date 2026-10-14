<?php
// index.php - Main Entry Point & Router
session_start();

require_once 'config/database.php';
require_once 'middlewares/authMiddleware.php';
require_once 'middlewares/roleMiddleware.php';
require_once 'controllers/authController.php';
require_once 'controllers/projectController.php';
require_once 'controllers/userController.php';

$page = $_GET['page'] ?? 'projects';
$currentUser = $_SESSION['user'] ?? null;

switch ($page) {
    case 'login_process':
        handleLogin($pdo);
        exit;
    case 'register_process':
        handleRegister($pdo);
        exit;
    case 'logout':
        handleLogout();
        exit;
    case 'add_project':
        checkAuth();
        handleAddProject($pdo);
        exit;
    case 'update_project':
        checkAuth();
        handleUpdateProject($pdo);
        exit;
    case 'delete_project':
        checkAuth();
        handleDeleteProject($pdo);
        exit;
    case 'add_user':
        checkRole(['super_admin']);
        handleAddUser($pdo);
        exit;
    case 'delete_user':
        checkRole(['super_admin']);
        handleDeleteUser($pdo);
        exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Etalase Digital Stella</title>
    <link rel="stylesheet" href="public/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>

    <?php if ($currentUser): ?>
    <nav class="navbar">
        <a href="index.php?page=projects" class="brand" style="display: flex; align-items: center; gap: 10px; text-decoration: none; color: inherit;">
            <img src="logo-ts2.png" alt="Logo Etalase Stella" style="height: 35px; width: auto; object-fit: contain;">
            <span>Etalase Stella</span>
        </a>
        <div class="nav-links">
            <a href="index.php?page=projects" class="<?= $page === 'projects' ? 'active' : '' ?>">Daftar Karya</a>
            <?php if ($currentUser['role'] === 'super_admin'): ?>
                <a href="index.php?page=users" class="<?= $page === 'users' ? 'active' : '' ?>">Kelola User</a>
            <?php endif; ?>
            <div style="display: flex; align-items: center; gap: 10px;">
                <span class="user-badge"><?= htmlspecialchars($currentUser['nama']) ?> (<?= strtoupper($currentUser['role']) ?>)</span>
                <a href="index.php?page=logout" class="btn btn-secondary" style="font-size: 0.8rem; padding: 0.4rem 0.8rem;">Logout</a>
            </div>
        </div>
    </nav>
    <?php endif; ?>

    <main class="container">
        <?php
        switch ($page) {
            case 'login':
                include 'views/login.php';
                break;
            case 'register':
                include 'views/register.php';
                break;
            case 'users':
                checkRole(['super_admin']);
                $users = getUsers($pdo);
                include 'views/users.php';
                break;
            case 'projects':
            default:
                checkAuth();
                $projects = getProjects($pdo);
                include 'views/projects.php';
                break;
        }
        ?>
    </main>

    <footer>
        <p>&copy; <?= date('Y'); ?> Etalase Digital Stella &bull;<strong>Baiq Salfa Sheyna Arfani</strong></p>
    </footer>

    <script src="public/js/main.js"></script>
</body>
</html>