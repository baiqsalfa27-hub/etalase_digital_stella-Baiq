<div class="auth-wrapper">
    <div class="auth-card">
        <h2 style="text-align: center; margin-bottom: 0.25rem; font-size: 1.5rem; color: var(--text-dark);">Registrasi Siswa</h2>
        <p style="text-align: center; color: var(--text-muted); margin-bottom: 1.5rem; font-size: 0.9rem;">Buat akun untuk memamerkan karya digitalmu</p>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
        <?php endif; ?>

        <form action="index.php?page=register_process" method="POST">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" placeholder="Masukkan nama lengkap" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" placeholder="siswa@stella.sch.id" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; margin-top: 0.5rem; padding: 0.7rem;">Daftar Akun</button>
        </form>

        <p style="text-align: center; margin-top: 1rem; font-size: 0.85rem; color: var(--text-muted);">
            Sudah punya akun? <a href="index.php?page=login" style="color: var(--primary); font-weight: 600;">Login</a>
        </p>
    </div>
</div>