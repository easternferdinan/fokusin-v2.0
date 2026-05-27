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
                <!-- TODO: Implement search functionality -->
                <i class="fas fa-search position-absolute" style="left: 20px; top: 50%; transform: translateY(-50%); color: #b2bec3;"></i>
                <input type="text" class="form-control form-control-lg rounded-pill search-bar ps-5" placeholder="Cari nama tugas...">
            </div>
        </div>
        <div class="col-12 col-lg-6 d-flex justify-content-lg-end align-items-center gap-2">
            <button class="btn btn-primary btn-lg rounded-pill shadow-sm px-4" onclick="openTugasModal()"><i class="fas fa-plus me-2"></i> Baru</button>
        </div>
    </div>
    
    <div class="task-list">

    <?php if ($tasks): 
        $count = 1;
        foreach ($tasks as $task): ?>
        <div class="task-item priority-<?= strtolower($task->priority) ?> <?= $task->completed ? 'completed' : '' ?>" id="<?= $task->task_id ?>">
            <div class="custom-check me-3 <?= $task->completed ? 'checked' : '' ?>" onclick="toggleComplete('<?= $task->task_id ?>', '<?= $task->title ?>')"><i class="fas fa-check <?= $task->completed ? '' : 'd-none' ?>"></i></div>
            <div class="flex-grow-1">
                <div class="d-flex align-items-center gap-2">
                    <h6 class="mb-1 fw-bold task-title"><?= $task->title ?></h6>
                    <span class="badge bg-secondary-subtle text-secondary small px-2" style="font-size: 0.65rem;"><?= $task->category ?></span>
                </div>
                <div class="d-flex flex-wrap gap-3 task-meta">
                    <!-- PROTOTYPE -->
                    <!-- TODO: Move to helper function -->
                    <!-- TODO: Handle sorting (by deadline then priority) -->
                    <?php
                        $deadlineDt = new \DateTime($task->deadline);
                        $deadlineDtYmd = $deadlineDt->format('Y-m-d');
                        $nowDt = new \DateTime();
                        $deadlineDtDay = clone $deadlineDt;
                        $deadlineDtDay->setTime(0, 0, 0);
                        $nowDtDay = clone $nowDt;
                        $nowDtDay->setTime(0, 0, 0);
                        $diffDays = (int)$nowDtDay->diff($deadlineDtDay)->format('%R%a');

                        $taskColor = 'text-dark';
                        
                        if ($diffDays === 0) {
                            $dlText = 'Hari ini';
                            $taskColor = 'text-danger';
                        } elseif ($diffDays === 1) {
                            $dlText = 'Besok';
                            $taskColor = 'text-success';
                        } elseif ($diffDays === -1) {
                            $dlText = 'Kemarin';
                            $taskColor = 'text-danger';
                        } elseif (abs($diffDays) <= 7) {
                            $dlText = ($diffDays > 0 ? '+' . abs($diffDays) . ' hari' : abs($diffDays) . ' hari yang lalu');
                            $taskColor = $diffDays > 0 ? 'text-dark' : 'text-danger';
                        } else {
                            $dlText = $deadlineDt->format('d-m-y');
                            $taskColor = $diffDays > 0 ? 'text-dark' : 'text-danger';
                        }
                    ?>
                    <small class="text-muted"><i class="fas fa-clock me-1"></i><span class="<?= $taskColor ?> fw-bold"><?= $dlText ?></span></small>
                    <small class="text-muted"><i class="fas fa-hourglass-half me-1"></i><?= $task->target_duration ?> menit</small>
                </div>
                <p class="text-muted small m-0 mt-1" style="font-size: 0.75rem;"><?= $task->description ?></p>
            </div>
            <div class="action-btns d-flex gap-2 ms-auto align-items-center">
                <a href="<?= base_url('mahasiswa/pomodoro?task=' . urlencode($task->title)) ?>" class="btn btn-sm btn-play" title="Kerjakan dengan Pomodoro"><i class="fas fa-play"></i></a>
                <button class="btn btn-sm btn-edit" title="Edit Tugas" onclick="editTugas('<?= $task->task_id ?>', '<?= $task->title ?>', '<?= $task->category ?>', '<?= $task->priority ?>', '<?= $deadlineDtYmd ?>', '<?= $task->target_duration ?>', '<?= $task->description ?>')"><i class="fas fa-edit"></i></button>
                <form id="form-hapus-tugas-<?= $task->task_id ?>" action="<?= base_url('mahasiswa/hapusTugas/' . urlencode($task->task_id)) ?>" method="post" style="display: inline;">
                    <button type="submit" class="btn btn-sm btn-delete" title="Hapus Tugas" onclick="hapusTugas(event, '<?= $task->task_id ?>')"><i class="fas fa-trash-alt"></i></button>
                </form>
            </div>
        </div>
        <?php $count++; endforeach; ?>
    <?php else: ?>
        <div class="alert alert-info">Belum ada tugas.</div>
    <?php endif; ?>
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
                <form id="formTugas" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-muted">Judul Tugas</label>
                        <input type="text" id="inputJudul" name="title" class="form-control" placeholder="Contoh: Revisi Makalah PPL" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label fw-semibold small text-muted">Kategori</label>
                            <select id="inputKategori" name="category" class="form-select">
                                <option value="Kuliah">Kuliah</option>
                                <option value="Proyek">Proyek</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label fw-semibold small text-muted">Prioritas</label>
                            <select id="inputPrioritas" name="priority" class="form-select">
                                <option value="Tinggi">Tinggi</option>
                                <option value="Sedang" selected>Sedang</option>
                                <option value="Rendah">Rendah</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label fw-semibold small text-muted">Deadline</label>
                            <input type="date" id="inputDeadline" name="deadline" class="form-control" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label fw-semibold small text-muted">Target (Menit)</label>
                            <input type="number" id="inputTarget" name="target_duration" class="form-control" placeholder="Misal: 60">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-muted">Deskripsi Tugas</label>
                        <textarea id="inputDeskripsi" name="description" class="form-control" rows="3" placeholder="Tulis detail tugas di sini..."></textarea>
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