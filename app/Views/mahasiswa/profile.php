<?= $this->extend('mahasiswa/layout_main') ?>

<?= $this->section('title') ?>Profile<?= $this->endSection() ?>
<?= $this->section('page_title') ?>Profile<?= $this->endSection() ?>
<?= $this->section('page_sub') ?>Update informasi profile kamu<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card p-4">
            <div class="text-center mb-4">
                <img src="https://ui-avatars.com/api/?name=<?= urlencode(session()->get('fullname')) ?>&background=74b9ff&color=fff&size=100&bold=true" class="rounded-circle mb-3 shadow">
                <h5 class="fw-bold"><?= session()->get('fullname') ?></h5>
                <span class="badge bg-primary bg-opacity-10 text-primary">Mahasiswa</span>
            </div>
            <form id="formProfile" action="profile/save" method="POST">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label fw-semibold">Nama Lengkap</label><input type="text" name="fullname" class="form-control rounded-3" value="<?= session()->get('fullname') ?>"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Email</label><input type="email" name="email" class="form-control rounded-3" value="<?= session()->get('email') ?>"></div>
                    <div class="col-12 mt-4 mb-2"><hr><p class="text-muted small mb-0"><i class="fas fa-info-circle me-1"></i> Data di bawah ini digunakan oleh sistem <strong>AI Random Forest</strong>.</p></div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Kesehatan Mental</label>
                        <select name="mental_health_history" class="form-select rounded-3"><option value="0" <?= session()->get('mental_health_history') == false ? 'selected' : '' ?>>0 - Tidak Ada Riwayat</option><option value="1" <?= session()->get('mental_health_history') == true ? 'selected' : '' ?>>1 - Ada Riwayat</option></select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Akademik Performance</label>
                        <select name="academic_performance" class="form-select rounded-3"><option value="1" <?= session()->get('academic_performance') == 1 ? 'selected' : '' ?>>1 - Sangat Rendah</option><option value="2" <?= session()->get('academic_performance') == 2 ? 'selected' : '' ?>>2 - Rendah</option><option value="3" <?= session()->get('academic_performance') == 3 ? 'selected' : '' ?>>3 - Cukup</option><option value="4" <?= session()->get('academic_performance') == 4 ? 'selected' : '' ?>>4 - Tinggi</option><option value="5" <?= session()->get('academic_performance') == 5 ? 'selected' : '' ?>>5 - Sangat Tinggi</option></select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Dukungan Sosial</label>
                        <select name="social_support" class="form-select rounded-3"><option value="1" <?= session()->get('social_support') == 1 ? 'selected' : '' ?>>1 - Rendah</option><option value="2" <?= session()->get('social_support') == 2 ? 'selected' : '' ?>>2 - Cukup</option><option value="3" <?= session()->get('social_support') == 3 ? 'selected' : '' ?>>3 - Tinggi</option></select>
                    </div>
                    <div class="col-12 mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary rounded-3 px-4"><i class="fas fa-save me-2"></i> Simpan Semua Perubahan</button>
                        <button type="button" class="btn btn-outline-primary rounded-3 px-4" onclick="showPasswordModal('change')"><i class="fas fa-key me-2"></i> Ubah Password</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
    if ('<?= session()->getFlashdata('success') !== null ?>') {
        const success = JSON.parse('<?= json_encode(session()->getFlashdata('success')) ?>');
        Swal.fire({
            title: success.title,
            text: success.message,
            icon: 'success',
            confirmButtonColor: '#00b894'
        })
    }

    if ('<?= session()->getFlashdata('error') !== null ?>') {
        Swal.fire({
            title: 'Error!',
            text: '<?= session()->getFlashdata('error') ?>',
            icon: 'error',
            confirmButtonColor: '#ff7675'
        })
    }
</script>
<?= $this->endSection() ?>