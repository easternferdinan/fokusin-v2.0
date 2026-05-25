<?= $this->extend('mahasiswa/layout_main') ?>

<?= $this->section('title') ?>Dashboard<?= $this->endSection() ?>
<?= $this->section('page_title') ?>Dashboard<?= $this->endSection() ?>
<?= $this->section('page_sub') ?>Ringkasan aktivitas & analisis AI<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- SECTION 1: DASHBOARD -->
<div id="section-dashboard">
    <?php if (empty($statusBurnout)): ?>
        <div class="card border-0 shadow-sm rounded-4 mb-4 border-start border-gray border-4" style="background-color: #ffffffff;">
            <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="d-flex align-items-start gap-3">
                    <div class="p-3 bg-light bg-opacity-10 rounded-circle text-muted mt-1">
                        <i class="fas fa-brain fa-lg"></i>
                    </div>
                    <div>
                        <!-- <h5 class="fw-bold text-danger mb-1">Status AI: TINGGI (Waspada Burnout)</h5> -->
                        <h5 class="fw-bold text-muted mb-1">Prediksi AI: Belum Tersedia</h5>
                        <p class="text-muted small mb-0">Sistem belum memiliki data untuk memprediksi tingkat stres.</p>
                    </div>
                </div>
    <?php elseif (strtolower($statusBurnout) == 'rendah'): ?>
        <div class="card border-0 shadow-sm rounded-4 mb-4 border-start border-success border-4" style="background-color: #e9fce8ff;">
            <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="d-flex align-items-start gap-3">
                    <div class="p-3 bg-success bg-opacity-10 rounded-circle text-success mt-1">
                        <i class="fas fa-brain fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold text-success mb-1">Prediksi AI: <?= strtoupper($statusBurnout) ?></h5>
                        <p class="text-muted small mb-0">Sistem memprediksi tingkat stres akademikmu adalah rendah. Pertahankan ya!</p>
                    </div>
                </div>
                <a href="<?= base_url('mahasiswa/report') ?>" class="btn btn-success rounded-pill px-4 fw-semibold shadow-sm">
                    Lihat Detail Prediksi <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
    <?php elseif (strtolower($statusBurnout) == 'sedang'): ?>
        <div class="card border-0 shadow-sm rounded-4 mb-4 border-start border-warning border-4" style="background-color: #fffce9ff;">
            <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="d-flex align-items-start gap-3">
                    <div class="p-3 bg-warning bg-opacity-10 rounded-circle text-warning mt-1">
                        <i class="fas fa-brain fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold text-warning mb-1">Prediksi AI: <?= strtoupper($statusBurnout) ?></h5>
                        <p class="text-muted small mb-0">Sistem memprediksi tingkat stres akademikmu adalah sedang. Jangan lupa istirahat ya!</p>
                    </div>
                </div>
                <a href="<?= base_url('mahasiswa/report') ?>" class="btn btn-warning rounded-pill px-4 fw-semibold shadow-sm">
                    Lihat Detail Prediksi <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
    <?php elseif (strtolower($statusBurnout) == 'tinggi'): ?>
        <div class="card border-0 shadow-sm rounded-4 mb-4 border-start border-danger border-4" style="background-color: #ffe9e9ff;">
            <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="d-flex align-items-start gap-3">
                    <div class="p-3 bg-danger bg-opacity-10 rounded-circle text-danger mt-1">
                        <i class="fas fa-brain fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold text-danger mb-1">Prediksi AI: <?= strtoupper($statusBurnout) ?></h5>
                        <p class="text-muted small mb-0">Sistem memprediksi tingkat stres akademikmu adalah tinggi. Kelola tugasmu perlahan ya!</p>
                    </div>
                </div>
                <a href="<?= base_url('mahasiswa/report') ?>" class="btn btn-danger rounded-pill px-4 fw-semibold shadow-sm">
                    Lihat Detail Prediksi <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
    <?php endif; ?>
            
        </div>
    </div>
    
    <div class="row g-3 mb-4">
        <!-- MENGGUNAKAN VARIABEL DARI CONTROLLER -->
        <div class="col-6 col-md-3"><div class="card p-3 text-center"><h2 class="text-primary fw-bold"><?= $totalTugas ?></h2><span class="text-muted small" style="font-size:0.8rem; font-weight:600; text-transform:uppercase;">Total Tugas</span></div></div>
        <div class="col-6 col-md-3"><div class="card p-3 text-center"><h2 class="text-warning fw-bold"><?= $highPriority ?></h2><span class="text-muted small" style="font-size:0.8rem; font-weight:600; text-transform:uppercase;">Tugas High Priority</span></div></div>
        <div class="col-6 col-md-3"><div class="card p-3 text-center"><h2 class="text-danger fw-bold"><?= $deadlineBesok ?></h2><span class="text-muted small" style="font-size:0.8rem; font-weight:600; text-transform:uppercase;">Deadline Besok</span></div></div>
        <div class="col-6 col-md-3"><div class="card p-3 text-center"><h2 class="text-success fw-bold"><?= $waktuFokus ?></h2><span class="text-muted small" style="font-size:0.8rem; font-weight:600; text-transform:uppercase;">Waktu Fokus</span></div></div>
    </div>
    <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold m-0">Tugas Mendesak</h5>
            <button class="btn btn-primary btn-sm px-3 rounded-3 shadow-sm" onclick="openTugasModal()"><i class="fas fa-plus me-1"></i> Tambah</button>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light"><tr><th>Judul Tugas</th><th>Prioritas</th><th>Aksi</th></tr></thead>
                <tbody>
                    <?php if ($tugasMendesak) { ?>
                        <?php foreach ($tugasMendesak as $key => $value) { ?>
                            <tr><td class="fw-semibold"><?= $value->title ?></td><td><span class="badge bg-<?php if(strtolower($value->priority) == 'rendah') {echo 'success';} elseif(strtolower($value->priority) == 'sedang') {echo 'warning';} elseif(strtolower($value->priority) == 'tinggi') {echo 'danger';} ?>"><?= strtoupper($value->priority) ?></span></td><td><button class="btn btn-sm btn-outline-primary rounded-pill px-2" onclick="goToPomodoro('<?= $value->title ?>')"><i class="fas fa-play"></i> Fokus</button></td></tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr><td class="text-center py-5" colspan="3">Tidak ada tugas mendesak</td></tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>