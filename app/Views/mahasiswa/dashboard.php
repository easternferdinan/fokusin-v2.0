<?= $this->extend('mahasiswa/layout_main') ?>

<?= $this->section('title') ?>Dashboard<?= $this->endSection() ?>
<?= $this->section('page_title') ?>Dashboard<?= $this->endSection() ?>
<?= $this->section('page_sub') ?>Ringkasan aktivitas & analisis AI<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- SECTION 1: DASHBOARD -->
<div id="section-dashboard">
    <div class="card border-0 shadow-sm rounded-4 mb-4 border-start border-danger border-4" style="background-color: #fff5f5;">
        <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-start gap-3">
                <div class="p-3 bg-danger bg-opacity-10 rounded-circle text-danger mt-1">
                    <i class="fas fa-brain fa-lg"></i>
                </div>
                <div>
                    <h5 class="fw-bold text-danger mb-1">Status AI: TINGGI (Waspada Burnout)</h5>
                    <p class="text-muted small mb-0">Sistem mendeteksi tingkat stres akademikmu sedang tinggi hari ini. Kelola tugasmu perlahan ya.</p>
                </div>
            </div>
            
            <a href="<?= base_url('mahasiswa/report') ?>" class="btn btn-danger rounded-pill px-4 fw-semibold shadow-sm">
                Lihat Detail Prediksi <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
    
    <div class="row g-3 mb-4">
        <!-- MENGGUNAKAN VARIABEL DARI CONTROLLER -->
        <div class="col-6 col-md-3"><div class="card p-3 text-center"><h2 class="text-primary fw-bold"><?= $totalTugas ?></h2><span class="text-muted small" style="font-size:0.8rem; font-weight:600; text-transform:uppercase;">Total Tugas</span></div></div>
        <div class="col-6 col-md-3"><div class="card p-3 text-center"><h2 class="text-warning fw-bold"><?= $highPriority ?></h2><span class="text-muted small" style="font-size:0.8rem; font-weight:600; text-transform:uppercase;">High Priority</span></div></div>
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
                    <tr><td class="fw-semibold">Revisi Makalah PPL</td><td><span class="badge bg-danger">Tinggi</span></td><td><button class="btn btn-sm btn-outline-primary rounded-pill px-2" onclick="goToPomodoro('Revisi Makalah PPL')"><i class="fas fa-play"></i> Fokus</button></td></tr>
                    <tr><td class="fw-semibold">Desain UI Dashboard</td><td><span class="badge bg-warning text-dark">Sedang</span></td><td><button class="btn btn-sm btn-outline-primary rounded-pill px-2" onclick="goToPomodoro('Desain UI Dashboard')"><i class="fas fa-play"></i> Fokus</button></td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>