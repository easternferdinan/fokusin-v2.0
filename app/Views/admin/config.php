<?= $this->extend('admin/layout_main') ?>

<?= $this->section('content') ?>
<div class="card shadow-sm border-0 p-4 rounded-4">
    <h5 class="fw-bold mb-4"><i class="fas fa-cogs me-2 text-primary"></i>Konfigurasi Sistem</h5>
    
    <form action="<?= base_url('admin/config/save') ?>" method="post" id="formConfig">
        
        <div class="mb-4">
            <h6 class="fw-bold text-muted mb-3"><i class="fas fa-server me-2"></i>Parameter Koneksi AI</h6>
            <div class="p-3 bg-light rounded-3">
                <label class="form-label fw-bold small">API Endpoint Python</label>
                <input type="text" name="api_url" class="form-control rounded-3" value="http://localhost:8000/predict">
                <small class="text-muted">URL dari microservice Python. Pastikan server AI sedang berjalan.</small>
            </div>
        </div>

        <div class="mb-4">
            <h6 class="fw-bold text-muted mb-3"><i class="fas fa-bell me-2"></i>Konfigurasi Trigger Alert Admin</h6>
            
            <div class="p-3 bg-light rounded-3">
                <div class="mb-3">
                    <label class="form-label fw-bold small">Kirim Notifikasi Jika Mahasiswa Masuk Kategori:</label>
                    <div class="d-flex gap-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="alert_kategori[]" value="Tinggi" id="alertTinggi" checked>
                            <label class="form-check-label fw-semibold text-danger" for="alertTinggi">Tinggi (High Risk)</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="alert_kategori[]" value="Sedang" id="alertSedang">
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
                                <input type="number" name="freq_days" class="form-control" value="3" min="1" max="7">
                                <span class="input-group-text bg-white border-start-0">hari berturut-turut</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm" onclick="simpanConfig()">
                <i class="fas fa-save me-2"></i>Simpan Konfigurasi
            </button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="<?= base_url('assets/js/admin.js') ?>"></script>
<?= $this->endSection() ?>