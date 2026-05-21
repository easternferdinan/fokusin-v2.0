<?= $this->extend('admin/layout_main') ?>

<?= $this->section('content') ?>
<div class="card border-0 p-4 mb-3" style="border-left: 5px solid #ff7675 !important; background: #fff5f5;">
    <div class="d-flex justify-content-between align-items-start">
        <div>
            <h6 class="fw-bold text-danger"><i class="fas fa-user-injured me-2"></i>Salma Azzahra</h6>
            <p class="text-muted small mb-2">Skor stres 85 selama 3 hari berturut-turut.</p>
            <button class="btn btn-sm btn-danger rounded-pill px-3" onclick="kirimAlert('Salma')"><i class="fas fa-bell me-1"></i>Kirim Peringatan</button>
        </div>
        <span class="badge bg-danger">KRITIS</span>
    </div>
</div>

<div class="card border-0 p-4" style="border-left: 5px solid #ffeaa7 !important; background: #fffdf5;">
    <div class="d-flex justify-content-between align-items-start">
        <div>
            <h6 class="fw-bold text-warning"><i class="fas fa-exclamation-circle me-2"></i>Rizky Pratama</h6>
            <p class="text-muted small mb-2">Skor stres naik drastis 40 ke 65.</p>
            <button class="btn btn-sm btn-warning text-dark rounded-pill px-3" onclick="kirimAlert('Rizky')"><i class="fas fa-envelope me-1"></i>Kirim Edukasi</button>
        </div>
        <span class="badge bg-warning text-dark">WASPADA</span>
    </div>
</div>
<?= $this->endSection() ?>