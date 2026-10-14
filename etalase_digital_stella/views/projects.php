<div class="header-action">
    <div>
        <h1 style="font-size: 1.5rem;">Etalase Karya Digital</h1>
        <p style="color: var(--text-muted); font-size: 0.9rem;">Daftar seluruh karya digital siswa Stella</p>
    </div>
    <button class="btn btn-primary" onclick="openModal('addModal')">+ Tambah Karya Baru</button>
</div>

<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
<?php endif; ?>
<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
<?php endif; ?>

<div class="grid">
    <?php if (empty($projects)): ?>
        <p style="grid-column: 1/-1; text-align: center; color: var(--text-muted); padding: 3rem 0; font-weight: 500;">Belum ada karya digital yang diunggah.</p>
    <?php else: ?>
        <?php foreach ($projects as $item): ?>
            <div class="card">
                <img src="uploads/<?= htmlspecialchars($item['gambar']) ?>" class="card-img" alt="Project Thumbnail" onerror="this.src='https://via.placeholder.com/400x200/f3f4f6/9ca3af?text=Gambar+Karya';">
                <div class="card-body">
                    <h3 class="card-title"><?= htmlspecialchars($item['nama_project']) ?></h3>
                    <p class="card-desc"><?= nl2br(htmlspecialchars($item['deskripsi'])) ?></p>

                    <!-- Tombol Dual Link: GitHub & Vercel -->
                    <div style="margin-bottom: 1rem; display: flex; gap: 0.5rem; flex-wrap: wrap;">
                        <?php if (!empty($item['link_project'])): ?>
                            <a href="<?= htmlspecialchars($item['link_project']) ?>" target="_blank" class="btn btn-secondary" style="flex: 1; justify-content: center; font-size: 0.85rem; padding: 0.4rem 0.6rem;">
                                GitHub
                            </a>
                        <?php endif; ?>

                        <?php if (!empty($item['link_vercel'])): ?>
                            <a href="<?= htmlspecialchars($item['link_vercel']) ?>" target="_blank" class="btn btn-primary" style="flex: 1; justify-content: center; font-size: 0.85rem; padding: 0.4rem 0.6rem;">
                                Live Demo (Vercel)
                            </a>
                        <?php endif; ?>
                    </div>

                    <div class="card-footer">
                        <div class="author-info">
                            <strong><?= htmlspecialchars($item['pembuat']) ?></strong><br>
                            <small><?= date('d M Y', strtotime($item['created_at'])) ?></small>
                        </div>
                        <?php if ($currentUser['role'] === 'super_admin' || $currentUser['role'] === 'admin' || $currentUser['id'] == $item['user_id']): ?>
                            <div style="display: flex; gap: 0.4rem;">
                                <button class="btn btn-secondary" style="padding: 0.35rem 0.65rem; font-size: 0.8rem;" 
                                        onclick="prepareEditModal(<?= $item['id'] ?>, '<?= htmlspecialchars(addslashes($item['nama_project'])) ?>', '<?= htmlspecialchars(addslashes($item['deskripsi'])) ?>', '<?= htmlspecialchars(addslashes($item['link_project'] ?? '')) ?>', '<?= htmlspecialchars(addslashes($item['link_vercel'] ?? '')) ?>')">Edit</button>
                                <a href="index.php?page=delete_project&id=<?= $item['id'] ?>" class="btn btn-danger" style="padding: 0.3rem 0.65rem; font-size: 0.8rem;" onclick="return confirm('Apakah Anda yakin ingin menghapus karya ini?')">Hapus</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Modal Tambah Karya -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <h3 style="margin-bottom: 1.2rem; font-size: 1.2rem;">Tambah Karya Digital Baru</h3>
        <form action="index.php?page=add_project" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Nama Project</label>
                <input type="text" name="nama_project" class="form-control" placeholder="Contoh: Stickman Arena" required>
            </div>
            <div class="form-group">
                <label>Deskripsi Project</label>
                <textarea name="deskripsi" class="form-control" rows="3" placeholder="Jelaskan secara singkat fitur dan teknologi yang digunakan..." required></textarea>
            </div>
            <div class="form-group">
                <label>Link GitHub (Repositori Kode)</label>
                <input type="url" name="link_project" class="form-control" placeholder="https://github.com/username/repository">
            </div>
            <div class="form-group">
                <label>Link Vercel (Live Demo / Application Preview)</label>
                <input type="url" name="link_vercel" class="form-control" placeholder="https://nama-project.vercel.app">
            </div>
            <div class="form-group">
                <label>File Upload Gambar Preview</label>
                <input type="file" name="gambar" class="form-control" accept="image/*" required>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 0.5rem; margin-top: 1.5rem;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('addModal')">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Karya</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Karya -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <h3 style="margin-bottom: 1.2rem; font-size: 1.2rem;">Edit Karya Digital</h3>
        <form action="index.php?page=update_project" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" id="edit_id">
            <div class="form-group">
                <label>Nama Project</label>
                <input type="text" name="nama_project" id="edit_nama" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Deskripsi Project</label>
                <textarea name="deskripsi" id="edit_deskripsi" class="form-control" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label>Link GitHub (Repositori Kode)</label>
                <input type="url" name="link_project" id="edit_link" class="form-control" placeholder="https://github.com/...">
            </div>
            <div class="form-group">
                <label>Link Vercel (Live Demo)</label>
                <input type="url" name="link_vercel" id="edit_link_vercel" class="form-control" placeholder="https://...vercel.app">
            </div>
            <div class="form-group">
                <label>Ganti Gambar (Opsional)</label>
                <input type="file" name="gambar" class="form-control" accept="image/*">
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 0.5rem; margin-top: 1.5rem;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('editModal')">Batal</button>
                <button type="submit" class="btn btn-primary">Update Karya</button>
            </div>
        </form>
    </div>
</div>

<script>
function prepareEditModal(id, nama, deskripsi, linkGitHub, linkVercel) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_nama').value = nama;
    document.getElementById('edit_deskripsi').value = deskripsi;
    document.getElementById('edit_link').value = linkGitHub;
    document.getElementById('edit_link_vercel').value = linkVercel;
    openModal('editModal');
}
</script>