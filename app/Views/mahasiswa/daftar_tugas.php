<?= $this->extend('mahasiswa/layout_main') ?>

<?= $this->section('title') ?>Daftar Tugas<?= $this->endSection() ?>
<?= $this->section('page_title') ?>Daftar Tugas<?= $this->endSection() ?>
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
            <div class="action-btns d-flex gap-2 ms-auto align-items-center">
                <a href="<?= base_url('mahasiswa/pomodoro?task=Revisi%20Makalah%20PPL') ?>" class="btn btn-sm btn-play" title="Kerjakan dengan Pomodoro"><i class="fas fa-play"></i></a>
                <button class="btn btn-sm btn-edit" title="Edit Tugas" onclick="editTugas(1, 'Revisi Makalah PPL', 'Kuliah', 'Tinggi', '2026-05-22', 60, 'Menyelesaikan bab 3 dan merapikan daftar pustaka.')"><i class="fas fa-edit"></i></button>
                <button class="btn btn-sm btn-delete" title="Hapus Tugas" onclick="hapusTugas(1)"><i class="fas fa-trash-alt"></i></button>
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
            <div class="action-btns d-flex gap-2 ms-auto align-items-center">
                <a href="<?= base_url('mahasiswa/pomodoro?task=Tugas%20Pemrograman%20Web') ?>" class="btn btn-sm btn-play" title="Kerjakan dengan Pomodoro"><i class="fas fa-play"></i></a>
                <button class="btn btn-sm btn-edit" title="Edit Tugas" onclick="editTugas(2, 'Tugas Pemrograman Web', 'Kuliah', 'Sedang', '2026-05-24', 120, 'Membuat halaman login dan register sesuai desain.')"><i class="fas fa-edit"></i></button>
                <button class="btn btn-sm btn-delete" title="Hapus Tugas" onclick="hapusTugas(2)"><i class="fas fa-trash-alt"></i></button>
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
            <div class="action-btns d-flex gap-2 ms-auto align-items-center">
                <a href="<?= base_url('mahasiswa/pomodoro?task=Proyek%20Akhir%20AI') ?>" class="btn btn-sm btn-play" title="Kerjakan dengan Pomodoro"><i class="fas fa-play"></i></a>
                <button class="btn btn-sm btn-edit" title="Edit Tugas" onclick="editTugas(3, 'Proyek Akhir AI', 'Proyek', 'Rendah', '2026-06-21', 300, 'Membangun model klasifikasi gambar menggunakan CNN.')"><i class="fas fa-edit"></i></button>
                <button class="btn btn-sm btn-delete" title="Hapus Tugas" onclick="hapusTugas(3)"><i class="fas fa-trash-alt"></i></button>
            </div>
        </div>

    </div>
</div>

<div class="modal fade" id="modalTugas" tabindex="-1" aria-labelledby="modalTugasTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            
            <div class="modal-header border-bottom-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-bold" id="modalTugasTitle">Tambah Tugas Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-4">
                <form id="formTugas">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-muted">Judul Tugas</label>
                        <input type="text" id="inputJudul" class="form-control" placeholder="Contoh: Revisi Makalah PPL" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label fw-semibold small text-muted">Kategori</label>
                            <select id="inputKategori" class="form-select">
                                <option value="Kuliah">Kuliah</option>
                                <option value="Proyek">Proyek</option>
                                <option value="Pribadi">Pribadi</option>
                            </select>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label fw-semibold small text-muted">Prioritas</label>
                            <select id="inputPrioritas" class="form-select">
                                <option value="Tinggi">Tinggi</option>
                                <option value="Sedang" selected>Sedang</option>
                                <option value="Rendah">Rendah</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label fw-semibold small text-muted">Deadline</label>
                            <input type="date" id="inputDeadline" class="form-control" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label fw-semibold small text-muted">Target (Menit)</label>
                            <input type="number" id="inputTarget" class="form-control" placeholder="Misal: 60">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-muted">Deskripsi Tugas</label>
                        <textarea id="inputDeskripsi" class="form-control" rows="3" placeholder="Tulis detail tugas di sini..."></textarea>
                    </div>
                </form>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <button type="button" class="btn btn-light px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary px-4" id="btnSimpanTugas" onclick="simpanTugas()"><i class="fas fa-save me-2"></i>Simpan</button>
                </div>
            </div>
            
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>

<?= $this->endSection() ?>