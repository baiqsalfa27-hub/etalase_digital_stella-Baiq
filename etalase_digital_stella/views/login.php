<div class="auth-wrapper">
    <div class="auth-card">
        <h2 style="text-align: center; margin-bottom: 0.25rem; font-size: 1.5rem;">Selamat Datang!</h2>
        <p style="text-align: center; color: var(--text-muted); margin-bottom: 1.5rem; font-size: 0.9rem;">Masuk ke Etalase Digital Stella</p>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
        <?php endif; ?>
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
        <?php endif; ?>

        <form action="index.php?page=login_process" method="POST">
            <div class="form-group">
                <label>Email Sekolah</label>
                <input type="email" name="email" id="login_email" class="form-control" placeholder="nama@stella.sch.id" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" id="login_password" class="form-control" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; margin-top: 0.5rem; padding: 0.7rem;">Login Masuk</button>
        </form>

        <p style="text-align: center; margin-top: 1rem; font-size: 0.85rem; color: var(--text-muted);">
            Belum punya akun siswa? <a href="index.php?page=register" style="color: var(--primary); font-weight: 600;">Daftar Siswa</a>
        </p>

        <div class="demo-box">
            <div class="demo-title">Masuk Cepat Demo</div>
            <div class="demo-btn-group">
                <button type="button" class="btn-demo" onclick="autoFillLogin('superadmin@stella.sch.id', 'password123')">
                    <span>Super Admin</span>
                    <small style="color: var(--text-muted);">Auto Fill</small>
                </button>
                <button type="button" class="btn-demo" onclick="autoFillLogin('admin@stella.sch.id', 'password123')">
                    <span>Admin</span>
                    <small style="color: var(--text-muted);">Auto Fill</small>
                </button>
                <button type="button" class="btn-demo" onclick="autoFillLogin('siswa@stella.sch.id', 'password123')">
                    <span>Siswa</span>
                    <small style="color: var(--text-muted);">Auto Fill</small>
                </button>
            </div>
        </div>
    </div>
</div>