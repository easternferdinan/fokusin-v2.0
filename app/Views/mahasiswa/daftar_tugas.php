<?= $this->extend('mahasiswa/layout_main') ?>

<?= $this->section('title') ?>Daftar Tugas<?= $this->endSection() ?>
<?= $this->section('page_title') ?>Daftar Tugas 📝<?= $this->endSection() ?>
<?= $this->section('page_sub') ?>Kelola semua tugas kuliah dan proyekmu<?= $this->endSection() ?>

<?= $this->section('custom_css') ?>
    <link rel="stylesheet" href="<?= base_url('assets/css/tugas.css') ?>">
<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="task-list-container">
    <div class="row mb-4">
        <div class="col-12 col-lg-6 mb-3 mb-lg-0">
            <div class="position-relative">
                <i class="fas fa-search position-absolute" style="left: 20px; top: 50%; transform: translateY(-50%); color: #b2bec3;"></i>
                <input type="text" class="form-control form-control-lg rounded-pill search-bar ps-5" placeholder="Cari nama tugas...">
            </div>
        </div>
        <div class="col-12 col-lg-6 d-flex justify-content-lg-end align-items-center gap-2">
            <button class="btn btn-primary btn-lg rounded-pill shadow-sm px-4" onclick="openTugasModal()"><i class="fas fa-plus me-2"></i> Baru</button>
        </div>
    </div>
    
    <div class="task-list">
        <div class="task-item priority-tinggi" id="task-1">
            <div class="custom-check me-3" onclick="toggleComplete('task-1', 'Revisi Makalah PPL')"><i class="fas fa-check d-none"></i></div>
            <div class="flex-grow-1">
                <div class="d-flex align-items-center gap-2">
                    <h6 class="mb-1 fw-bold task-title">Revisi Makalah PPL</h6>
                    <span class="badge bg-secondary-subtle text-secondary small px-2" style="font-size: 0.65rem;">Kuliah</span>
                </div>
                <div class="d-flex flex-wrap gap-3 task-meta">
                    <small class="text-muted"><i class="fas fa-clock me-1"></i><span class="text-danger fw-bold">Besok</span></small>
                    <small class="text-muted"><i class="fas fa-hourglass-half me-1"></i>60 Menit</small>
                </div>
                <p class="text-muted small m-0 mt-1" style="font-size: 0.75rem;">Menyelesaikan bab 3 dan merapikan daftar pustaka.</p>
            </div>
        </div>
        <div class="task-item priority-sedang" id="task-2">
            <div class="custom-check me-3" onclick="toggleComplete('task-2', 'Tugas Pemrograman Web')"><i class="fas fa-check d-none"></i></div>
            <div class="flex-grow-1">
                <div class="d-flex align-items-center gap-2">
                    <h6 class="mb-1 fw-bold task-title">Tugas Pemrograman Web</h6>
                    <span class="badge bg-secondary-subtle text-secondary small px-2" style="font-size: 0.65rem;">Kuliah</span>
                </div>
                <div class="d-flex flex-wrap gap-3 task-meta">
                    <small class="text-muted"><i class="fas fa-clock me-1"></i><span class="text-warning fw-bold">3 Hari Lagi</span></small>
                    <small class="text-muted"><i class="fas fa-hourglass-half me-1"></i>120 Menit</small>
                </div>
                <p class="text-muted small m-0 mt-1" style="font-size: 0.75rem;">Membuat halaman login dan register sesuai desain.</p>
            </div>
        </div>
        <div class="task-item priority-rendah" id="task-3">
            <div class="custom-check me-3" onclick="toggleComplete('task-3', 'Proyek Akhir AI')"><i class="fas fa-check d-none"></i></div>
            <div class="flex-grow-1">
                <div class="d-flex align-items-center gap-2">
                    <h6 class="mb-1 fw-bold task-title">Proyek Akhir AI</h6>
                    <span class="badge bg-secondary-subtle text-secondary small px-2" style="font-size: 0.65rem;">Proyek</span>
                </div>
                <div class="d-flex flex-wrap gap-3 task-meta">
                    <small class="text-muted"><i class="fas fa-clock me-1"></i><span class="text-info fw-bold">1 Bulan Lagi</span></small>
                    <small class="text-muted"><i class="fas fa-hourglass-half me-1"></i>300 Menit</small>
                </div>
                <p class="text-muted small m-0 mt-1" style="font-size: 0.75rem;">Membangun model klasifikasi gambar menggunakan CNN.</p>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<!-- Tidak perlu load JS khusus untuk halaman ini karena logic ada di utils.js (Modal & Alert saja) -->
<?= $this->endSection() ?>