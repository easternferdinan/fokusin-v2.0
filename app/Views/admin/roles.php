<?= $this->extend('admin/layout_main') ?>

<?= $this->section('content') ?>
<button class="btn btn-primary rounded-pill px-4 shadow-sm mb-4" onclick="openModal('Tambah Admin Baru', true)">
    <i class="fas fa-plus me-2"></i>Tambah Admin
</button>

<div class="card p-0 overflow-hidden">
    <table class="table table-hover">
        <thead>
            <tr><th>Nama Admin</th><th>Username</th><th>Role</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            <tr>
                <td class="fw-semibold">Admin Fokusin</td>
                <td>admin_fks</td>
                <td><span class="badge bg-info bg-opacity-10 text-info">Admin</span></td>
                <td><button class="btn btn-sm btn-outline-primary rounded-pill"><i class="fas fa-key"></i> Ubah</button></td>
            </tr>
            <tr>
                <td class="fw-semibold">Tim PPL Developer</td>
                <td>dev_master</td>
                <td><span class="badge bg-dark bg-opacity-10 text-dark">Super Admin</span></td>
                <td><button class="btn btn-sm btn-outline-secondary rounded-pill" disabled><i class="fas fa-lock"></i> Protected</button></td>
            </tr>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>