<div class="header-action">
    <div>
        <h1 style="color: #be185d;">Manajemen User & Admin</h1>
        <p style="color: var(--text-muted);">Kelola data pengguna, hak akses, dan tambah admin baru (Khusus Super Admin)</p>
    </div>
    <button class="btn btn-primary" onclick="openModal('addUserModal')">+ Tambah User/Admin</button>
</div>

<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
<?php endif; ?>
<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
<?php endif; ?>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role (Hak Akses)</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $u): ?>
                <tr>
                    <td>#<?= $u['id'] ?></td>
                    <td><strong><?= htmlspecialchars($u['nama']) ?></strong></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td>
                        <span class="user-badge" style="background: <?= $u['role'] === 'super_admin' ? '#fef3c7; color: #d97706;' : ($u['role'] === 'admin' ? '#fbcfe8; color: #be185d;' : '#f1f5f9; color: #475569;') ?>">
                            <?= strtoupper($u['role']) ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($u['id'] != $currentUser['id']): ?>
                            <a href="index.php?page=delete_user&id=<?= $u['id'] ?>" class="btn btn-danger" style="padding: 0.3rem 0.8rem; font-size: 0.8rem;" onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')">🗑️ Hapus</a>
                        <?php else: ?>
                            <span style="color: var(--text-muted); font-size: 0.85rem; font-weight: 600;">(Akun Anda)</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div id="addUserModal" class="modal">
    <div class="modal-content">
        <h3 style="margin-bottom: 1.2rem; color: #be185d;">Tambah User / Admin Baru</h3>
        <form action="index.php?page=add_user" method="POST">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Role</label>
                <select name="role" class="form-control">
                    <option value="siswa">Siswa</option>
                    <option value="admin">Admin</option>
                    <option value="super_admin">Super Admin</option>
                </select>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 0.5rem; margin-top: 1.5rem;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('addUserModal')">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan User</button>
            </div>
        </form>
    </div>
</div>
