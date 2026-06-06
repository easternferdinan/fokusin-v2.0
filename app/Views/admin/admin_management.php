<?= $this->extend('admin/layout_main') ?>

<?= $this->section('content') ?>
<button class="btn btn-primary rounded-pill px-4 shadow-sm mb-4" data-bs-toggle="modal" data-bs-target="#modalTambahAdmin">
    <i class="fas fa-plus me-2"></i>Tambah Admin
</button>

<div class="card p-0 overflow-hidden">
    <table class="table table-hover">
        <thead>
            <tr class="text-center">
                <th>Nama Admin</th>
                <th>Username</th>
                <th>Terakhir Diperbarui</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($admins)): ?>
                <tr class="text-center">
                    <td colspan="4" class="py-4 text-muted">Belum ada data admin.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($admins as $admin): ?>
                    <tr class="text-center">
                        <td class="fw-semibold"><?= esc($admin['fullname']) ?></td>
                        <td><?= esc($admin['username']) ?></td>
                        <td class="text-muted small">
                            <?= !empty($admin['updated_at']) ? date('d M Y, H:i', strtotime($admin['updated_at'])) : '—' ?>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary rounded-pill" data-id="<?= esc($admin['user_id']) ?>" data-nama="<?= esc($admin['fullname']) ?>" data-username="<?= esc($admin['username']) ?>" onclick="editAdmin(this)"><i class="fas fa-pen-to-square"></i></button>
                            <button class="btn btn-sm btn-outline-danger rounded-pill"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal Tambah Admin -->
<div class="modal fade" id="modalTambahAdmin" tabindex="-1" aria-labelledby="modalTambahAdminLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">

            <div class="modal-header border-bottom-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-bold" id="modalTambahAdminLabel">Tambah Admin Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <form id="formTambahAdmin">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Nama Admin</label>
                        <input type="text" class="form-control rounded-3" id="inputNama" placeholder="Masukkan nama admin..." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Username</label>
                        <input type="text" class="form-control rounded-3" id="inputUsername" placeholder="Masukkan username..." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Password</label>
                        <input type="password" class="form-control rounded-3" id="inputPassword" placeholder="Masukkan password..." required>
                    </div>
                </form>
            </div>

            <div class="modal-footer border-top-0 pt-0 px-4 pb-4 justify-content-between">
                <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Batal</button>
                <button type="button" id="btnSimpanUser" class="btn btn-primary rounded-3 px-4" onclick="simpanAdminBaru()"><i class="fas fa-save me-2"></i>Simpan Data</button>
            </div>

        </div>
    </div>
</div>

<!-- Modal Edit Admin -->
<div class="modal fade" id="modalEditAdmin" tabindex="-1" aria-labelledby="modalEditAdminLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">

            <div class="modal-header border-bottom-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-bold" id="modalEditAdminLabel">Edit Admin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <form id="formEditAdmin">
                    <input type="hidden" id="editAdminId">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Nama Admin</label>
                        <input type="text" class="form-control rounded-3" id="editNama" placeholder="Masukkan nama admin..." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Username</label>
                        <input type="text" class="form-control rounded-3" id="editUsername" placeholder="Masukkan username..." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Password <span class="text-muted fw-normal">(opsional)</span></label>
                        <input type="password" class="form-control rounded-3" id="editPassword" placeholder="Kosongkan jika tidak ingin mengubah password">
                    </div>
                </form>
            </div>

            <div class="modal-footer border-top-0 pt-0 px-4 pb-4 justify-content-between">
                <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Batal</button>
                <button type="button" id="btnSimpanEditAdmin" class="btn btn-primary rounded-3 px-4" onclick="simpanEditAdmin()"><i class="fas fa-save me-2"></i>Simpan Perubahan</button>
            </div>

        </div>
    </div>
</div>
<?= $this->section('js') ?>
<script src="<?= base_url('assets/js/admin.js') ?>"></script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>