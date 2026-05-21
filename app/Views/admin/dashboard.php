<?= $this->extend('admin/layout_main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="position-relative" style="max-width: 300px;">
        <i class="fas fa-search position-absolute" style="left: 15px; top: 50%; transform: translateY(-50%); color: #b2bec3;"></i>
        <input type="text" class="form-control rounded-pill ps-5 border-0 bg-light" placeholder="Cari NIM / Nama...">
    </div>
    <button class="btn btn-primary rounded-pill px-4 shadow-sm"><i class="fas fa-plus me-2"></i>Tambah User</button>
</div>

<div class="card p-0 overflow-hidden">
    <table class="table table-hover">
        <thead>
            <tr><th>Nama</th><th>Username</th><th>Status AI</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            <tr>
                <td class="d-flex align-items-center gap-2"><img src="https://ui-avatars.com/api/?name=Salma&background=74b9ff&color=fff&size=30" class="rounded-circle"><span class="fw-semibold">Salma Pudjiati</span></td>
                <td>salma_p</td>
                <td><span class="stress-badge bg-danger bg-opacity-10 text-danger">Burnout (85)</span></td>
                <td>
                    <button class="btn btn-sm btn-outline-primary rounded-pill"><i class="fas fa-edit"></i></button> 
                    <button class="btn btn-sm btn-outline-danger rounded-pill"><i class="fas fa-trash"></i></button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>