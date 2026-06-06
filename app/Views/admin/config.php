<?php

/**
 * @var array $config
 */
?>
<?= $this->extend('admin/layout_main') ?>

<?= $this->section('content') ?>
<div class="card shadow-sm border-0 p-4 rounded-4">
    <h5 class="fw-bold mb-4"><i class="fas fa-cogs me-2 text-primary"></i>Konfigurasi Sistem</h5>

    <form action="<?= base_url('admin/update-config') ?>" method="post" id="formConfig">

        <div class="mb-4">
            <h6 class="fw-bold text-muted mb-3"><i class="fas fa-server me-2"></i>Parameter Koneksi AI</h6>
            <div class="p-3 bg-light rounded-3">
                <label class="form-label fw-bold small">API Endpoint Python</label>
                <input type="text" name="api_base_url" class="form-control rounded-3" value="<?= $config['api_base_url'] ?>">
                <small class="text-muted">URL dari microservice Python. Pastikan server sedang berjalan.</small>
            </div>
        </div>

        <div class="mb-4">
            <h6 class="fw-bold text-muted mb-3"><i class="fas fa-bell me-2"></i>Konfigurasi Trigger Alert Admin</h6>

            <div class="p-3 bg-light rounded-3">
                <div class="mb-3">
                    <label class="form-label fw-bold small">Kirim Notifikasi Jika Mahasiswa Masuk Kategori:</label>
                    <div class="d-flex gap-4">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="stress_threshold" value="Tinggi" id="alertTinggi" <?= $config['stress_threshold'] === 'Tinggi' ? 'checked' : '' ?>>
                            <label class="form-check-label fw-semibold text-danger" for="alertTinggi">Tinggi (High Risk)</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="stress_threshold" value="Sedang" id="alertSedang" <?= $config['stress_threshold'] === 'Sedang' ? 'checked' : '' ?>>
                            <label class="form-check-label fw-semibold text-warning" for="alertSedang">Sedang (Moderate Risk)</label>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="form-label fw-bold small">Aturan Frekuensi:</label>
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">Kirim alert jika konsisten selama</span>
                                <input type="number" name="stress_threshold_frequency" class="form-control" value="<?= $config['stress_threshold_frequency'] ?>" min="1" max="7">
                                <span class="input-group-text bg-white border-start-0">hari berturut-turut</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm" onsubmit="simpanConfig()">
                <i class="fas fa-save me-2"></i>Simpan Konfigurasi
            </button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="<?= base_url('assets/js/admin.js') ?>"></script>
<?= $this->endSection() ?>