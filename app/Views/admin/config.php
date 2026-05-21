<?= $this->extend('admin/layout_main') ?>

<?= $this->section('content') ?>
<div class="card p-4">
    <h5 class="fw-bold mb-4">Parameter AI & Sistem</h5>
    <div class="row g-4">
        <div class="col-md-6">
            <label class="form-label fw-semibold">API Endpoint Python</label>
            <input type="text" class="form-control rounded-3 bg-light" value="http://localhost:8000/predict" disabled>
            <small class="text-muted">Diatur di file .env</small>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">Threshold Burnout</label>
            <input type="number" class="form-control rounded-3" value="80">
        </div>
        <div class="col-12 mt-3">
            <button class="btn btn-dark rounded-pill px-4" onclick="simpanConfig()">
                <i class="fas fa-save me-2"></i>Simpan Konfigurasi
            </button>
        </div>
    </div>
</div>
<?= $this->endSection() ?>