<?php
// middlewares/roleMiddleware.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function checkRole($allowed_roles = []) {
    checkAuth();
    $user_role = $_SESSION['user']['role'];
    if (!in_array($user_role, $allowed_roles)) {
        header('Location: index.php?page=projects&error=Akses ditolak! Anda tidak memiliki izin.');
        exit;
    }
}
