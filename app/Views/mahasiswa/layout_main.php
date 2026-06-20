<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fokusin v2.0 - <?= $this->renderSection('title') ?></title>

    <!-- VENDORS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- CUSTOM ASSETS CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/global.css') ?>">
    <?= $this->renderSection('custom_css') ?>
</head>

<body>

    <div class="overlay" id="mobileOverlay" onclick="toggleSidebar()"></div>

    <div class="d-flex">
        <!-- SIDEBAR -->
        <nav class="sidebar" id="sidebar">
            <div class="sidebar-brand d-flex align-items-center justify-content-center gap-2 py-4">
                <img src="<?= base_url('assets/img/logo-fokusin.png') ?>" alt="Logo" class="logo-fokusin">
                <span class="fw-bolder text-white" style="letter-spacing: 2px;">FOKUSIN</span>
            </div>

            <a href="<?= base_url('mahasiswa') ?>" class="menu-link"><i class="fas fa-home"></i> Dashboard</a>
            <a href="<?= base_url('mahasiswa/tugas') ?>" class="menu-link"><i class="fas fa-tasks"></i> Daftar Tugas</a>
            <a href="<?= base_url('mahasiswa/pomodoro') ?>" class="menu-link"><i class="fas fa-stopwatch"></i> Timer Pomodoro</a>
            <a href="<?= base_url('mahasiswa/report') ?>" class="menu-link"><i class="fas fa-chart-line"></i> Report AI</a>

            <div class="mt-auto border-top border-secondary pt-3">
                <a href="<?= base_url('mahasiswa/pengaturan') ?>" class="menu-link"><i class="fas fa-cog"></i> Pengaturan</a>
                <a href="<?= base_url('auth/logout') ?>" class="menu-link text-danger" onclick="confirmLogout(event)"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </nav>
        <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="<?= base_url('mahasiswa/pengaturan') ?>"><i class="fas fa-user-circle me-2 text-primary"></i> Profile</a></li>
            <li>
                <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item text-danger" href="<?= base_url('auth/logout') ?>" onclick="confirmLogout(event)"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
        </ul>

        <!-- MAIN CONTENT -->
        <main class="content-area">
            <!-- Top Navbar -->
            <div class="top-navbar">
                <button class="btn btn-light d-md-none border-0 shadow-sm" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
                <div class="d-none d-md-block">
                    <h5 class="m-0 fw-bold text-dark" id="pageTitle"><?= $this->renderSection('page_title') ?></h5>
                    <small class="text-muted" id="pageSubtitle"><?= $this->renderSection('page_sub') ?></small>
                </div>
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-decoration-none" data-bs-toggle="dropdown">
                        <!-- Menggunakan variabel PHP dari Controller -->
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($namaMahasiswa) ?>&background=74b9ff&color=fff&bold=true" class="rounded-circle shadow-sm" width="42" height="42">
                        <span class="d-none d-md-block ms-2 fw-semibold text-dark"><?= $namaMahasiswa ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?= base_url('mahasiswa/pengaturan') ?>"><i class="fas fa-user-circle me-2 text-primary"></i> Profile</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item text-danger" href="<?= base_url('auth/logout') ?>" onclick="confirmLogout(event)"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                    </ul>
                </div>
            </div>

            <!-- SLOT ISI HALAMAN -->
            <?= $this->renderSection('content') ?>
        </main>
    </div>

    <!-- OFFCANVAS: PILIH TUGAS DARI TIMER -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasTaskList">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title fw-bold">Pilih Tugas</h5><button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-3">
            <!-- Nanti diganti dengan foreach PHP -->
            <div class="mini-task-item d-flex justify-content-between align-items-center" onclick="pickTaskFromOffcanvas('Revisi Makalah PPL')">
                <div>
                    <div class="fw-semibold">Revisi Makalah PPL</div><small class="text-danger">Besok</small>
                </div><i class="fas fa-arrow-right text-muted"></i>
            </div>
            <div class="mini-task-item d-flex justify-content-between align-items-center" onclick="pickTaskFromOffcanvas('Tugas Logika Matematika')">
                <div>
                    <div class="fw-semibold">Tugas Logika Matematika</div><small class="text-muted">1 Minggu lagi</small>
                </div><i class="fas fa-arrow-right text-muted"></i>
            </div>
        </div>
    </div>

    <!-- MODAL: TAMBAH/EDIT TUGAS -->
    <div class="modal fade" id="modalTugas" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="mahasiswa/simpanTugas" method="post" class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="modalTugasLabel">Tambah Tugas Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body px-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul Tugas</label>
                        <input type="text" name="title" class="form-control rounded-3" id="inputJudul" placeholder="Contoh: Revisi Makalah PPL">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Kategori</label>
                            <select name="category" class="form-select rounded-3">
                                <option value="Kuliah">Kuliah</option>
                                <option value="Proyek">Proyek</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Prioritas</label>
                            <select name="priority" class="form-select rounded-3">
                                <option value="Tinggi">Tinggi</option>
                                <option value="Sedang">Sedang</option>
                                <option value="Rendah">Rendah</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Deadline</label>
                            <input type="date" name="deadline" class="form-control rounded-3">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Target (Menit)</label>
                            <input type="number" name="target_duration" class="form-control rounded-3" placeholder="Misal: 60">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi Tugas</label>
                        <textarea name="description" class="form-control rounded-3" rows="3" placeholder="Tulis detail tugas di sini..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 px-4 pb-4">
                    <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4 shadow-sm">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- FAB CHECK-IN & MODAL CHECK-IN -->
    <div id="fabCheckin" class="position-fixed bottom-0 end-0 p-4" style="z-index: 1040;">
        <button class="btn btn-primary shadow-lg rounded-circle position-relative" data-bs-toggle="modal" data-bs-target="#modalCheckin" style="width: 65px; height: 65px; background: linear-gradient(135deg, #6c5ce7, #a29bfe); border: none;">
            <i class="fas fa-moon fs-4"></i>
            <span id="checkinBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light" style="font-size: 0.6rem;">!</span>
        </button>
    </div>

    <div class="modal fade" id="modalCheckin" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-body p-4">
                    <h5 class="fw-bold text-dark mb-1 text-center">Daily Check-in 🌙</h5>
                    <p class="text-muted small mb-4 text-center">Bagaimana kondisi belajarmu hari ini?</p>

                    <form action="<?= base_url('mahasiswa/saveCheckin') ?>" method="post" id="formCheckin" onsubmit="return submitCheckin(event)">
                        <label class="form-label fw-bold small text-muted text-uppercase mb-3">Kualitas Istirahat Semalam</label>
                        <div class="d-flex justify-content-around mb-4">
                            <div class="text-center">
                                <input type="radio" class="btn-check" name="sleep_quality" id="sleep1" value="1" required>
                                <label class="btn btn-outline-danger rounded-circle p-3 shadow-sm" for="sleep1"><i class="fas fa-frown fa-lg"></i></label>
                            </div>
                            <div class="text-center">
                                <input type="radio" class="btn-check" name="sleep_quality" id="sleep2" value="2">
                                <label class="btn btn-outline-warning rounded-circle p-3 shadow-sm" for="sleep2"><i class="fas fa-meh fa-lg"></i></label>
                            </div>
                            <div class="text-center">
                                <input type="radio" class="btn-check" name="sleep_quality" id="sleep3" value="3">
                                <label class="btn btn-outline-success rounded-circle p-3 shadow-sm" for="sleep3"><i class="fas fa-smile fa-lg"></i></label>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="mb-4">
                            <div class="d-flex justify-content-between">
                                <label class="form-label fw-bold small text-muted text-uppercase">Keyakinan Diri (Tugas/Kuliah)</label>
                                <span class="badge bg-primary rounded-pill" id="valEsteem">50%</span>
                            </div>
                            <input type="range" class="form-range" name="self_esteem_pct" min="0" max="100" value="50" oninput="document.getElementById('valEsteem').innerText = this.value + '%'">
                            <div class="d-flex justify-content-between text-muted" style="font-size: 0.65rem;">
                                <span>Sangat Pesimis (0%)</span>
                                <span>Sangat Yakin (100%)</span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="d-flex justify-content-between">
                                <label class="form-label fw-bold small text-muted text-uppercase">Beban Pikiran (Stres Akademik)</label>
                                <span class="badge bg-warning text-dark rounded-pill" id="valDepression">30%</span>
                            </div>
                            <input type="range" class="form-range" name="depression_pct" min="0" max="100" value="30" oninput="document.getElementById('valDepression').innerText = this.value + '%'">
                            <div class="d-flex justify-content-between text-muted" style="font-size: 0.65rem;">
                                <span>Santai (0%)</span>
                                <span>Sangat Tertekan (100%)</span>
                            </div>
                        </div>

                        <div class="mb-5">
                            <div class="d-flex justify-content-between">
                                <label class="form-label fw-bold small text-muted text-uppercase">Keluhan Fisik (Sakit Kepala/Lelah)</label>
                                <span class="badge bg-danger rounded-pill" id="valHeadache">1</span>
                            </div>
                            <input type="range" class="form-range" name="headache" min="1" max="5" value="1" id="rangeHeadache" oninput="document.getElementById('valHeadache').innerText = this.value">
                            <div class="d-flex justify-content-between text-muted" style="font-size: 0.65rem;">
                                <span>Bugar</span>
                                <span>Sangat Sakit</span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 rounded-pill shadow-sm fw-semibold py-2">
                            <i class="fas fa-paper-plane me-2"></i>Kirim Check-in Harian
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- VENDORS JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- CUSTOM ASSETS JS -->
    <script src="<?= base_url('assets/js/utils.js') ?>"></script>
    <script src="<?= base_url('assets/js/checkin.js') ?>"></script>

    <script>
        <?php $successFlash = session()->getFlashdata('success'); ?>
        <?php if ($successFlash !== null): ?>
            Swal.fire({
                icon: 'success',
                title: <?= json_encode($successFlash['title'] ?? 'Sukses') ?>,
                text: <?= json_encode($successFlash['message'] ?? 'Operasi Berhasil!') ?>,
                confirmButtonColor: '#00b894'
            });
        <?php endif; ?>

        <?php $errorFlash = session()->getFlashdata('error'); ?>
        <?php if ($errorFlash !== null): ?>
            Swal.fire({
                icon: 'error',
                title: <?= json_encode($errorFlash['title'] ?? 'Terjadi Kesalahan') ?>,
                text: <?= json_encode($errorFlash['message'] ?? 'Hubungi Admin!') ?>,
                confirmButtonColor: '#ff7675'
            });

            <?php if (isset($errorFlash['detail']) && ENVIRONMENT !== 'production'): ?>
                console.error(<?= json_encode($errorFlash['detail']) ?>);
            <?php endif; ?>
        <?php endif; ?>
    </script>

    <?= $this->renderSection('js') ?>
</body>

</html>