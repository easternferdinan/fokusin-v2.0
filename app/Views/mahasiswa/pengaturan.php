<?= $this->extend('mahasiswa/layout_main') ?>

<?= $this->section('title') ?>Pengaturan<?= $this->endSection() ?>
<?= $this->section('page_title') ?>Pengaturan ⚙️<?= $this->endSection() ?>
<?= $this->section('page_sub') ?>Update informasi profile kamu<?= $this->endSection() ?>

<?= $this->section('content') ?>

<h4 class="fw-bold mb-4">Pengaturan Akun & Data AI ⚙️</h4>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card p-4">
            <div class="text-center mb-4">
                <img src="https://ui-avatars.com/api/?name=Salma+AZ&background=74b9ff&color=fff&size=100&bold=true" class="rounded-circle mb-3 shadow">
                <h5 class="fw-bold">Salma Pudjiati</h5>
                <span class="badge bg-primary bg-opacity-10 text-primary">Mahasiswa Aktif</span>
            </div>
            <form id="formProfile">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label fw-semibold">Nama Lengkap</label><input type="text" class="form-control rounded-3" value="Salma Pudjiati"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Email</label><input type="email" class="form-control rounded-3" value="user@gmail.com"></div>
                    <div class="col-12 mt-4 mb-2"><hr><p class="text-muted small mb-0"><i class="fas fa-info-circle me-1"></i> Data di bawah ini digunakan oleh sistem <strong>AI Random Forest</strong>.</p></div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Kesehatan Mental</label>
                        <select class="form-select rounded-3"><option value="0" selected>0 - Tidak Ada Riwayat</option><option value="1">1 - Ada Riwayat</option></select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Akademik Performance</label>
                        <select class="form-select rounded-3"><option value="1">1 - Sangat Rendah</option><option value="2">2 - Rendah</option><option value="3">3 - Cukup</option><option value="4" selected>4 - Tinggi</option><option value="5" selected>5 - Sangat Tinggi</option></select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Dukungan Sosial</label>
                        <select class="form-select rounded-3"><option value="1">1 - Rendah</option><option value="2" selected>2 - Cukup</option><option value="3">3 - Tinggi</option></select>
                    </div>
                    <div class="col-12 mt-4"><button type="button" class="btn btn-primary rounded-3 px-4" onclick="simpanProfileAI()"><i class="fas fa-save me-2"></i> Simpan Semua Perubahan</button></div>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<!-- Tidak perlu load JS khusus untuk halaman ini karena hanya fungsi alert statis -->
<?= $this->endSection() ?>