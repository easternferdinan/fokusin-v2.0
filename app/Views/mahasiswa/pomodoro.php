<?= $this->extend('mahasiswa/layout_main') ?>

<?= $this->section('title') ?>Pomodoro<?= $this->endSection() ?>
<?= $this->section('page_title') ?>Timer Pomodoro<?= $this->endSection() ?>
<?= $this->section('page_sub') ?>Tetap fokus, Raih mimpimu!<?= $this->endSection() ?>

<?= $this->section('custom_css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/pomodoro.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php if ($namaMahasiswa === 'Guest'): ?>
    <!-- TODO: Tambahkan logic untuk menghitung sisa sesi -->
    <!-- NOTE: Logic untuk guest trial berada di file pomodoro.js, method startTimer() dan kunciTimerGuest() -->
<div class="row mb-3 justify-content-center">
    <div class="col-lg-12 text-center">
        <div class="alert alert-info rounded-4 border-0 py-2 px-4 d-inline-block shadow-sm animate__animated animate__fadeInDown" style="background-color: #e3f2fd; color: #0056b3;">
            <i class="fas fa-info-circle me-2"></i> Mode Guest: Tersisa <strong id="guestSisaSesi">3</strong> sesi uji coba
        </div>
    </div>
</div>
<?php endif; ?>

<div class="row g-4 justify-content-center">
    <div class="col-lg-7">
        <div class="card timer-card text-center">
            <div class="mb-3">
                <span id="modeBadge" class="badge rounded-pill px-4 py-2 shadow-sm" style="background-color: #dfe6e9; color: #636e72; font-size: 0.9rem; font-weight: 600;">
                    <i class="fas fa-brain me-1"></i> FOCUS MODE
                </span>
            </div>
            
            <div class="d-flex justify-content-center mb-4">
                <div class="position-relative">
                    <svg class="timer-svg" viewBox="0 0 200 200">
                        <circle class="timer-bg" cx="100" cy="100" r="90"></circle>
                        <circle id="progressBar" class="timer-progress" cx="100" cy="100" r="90" stroke-dasharray="565.48" stroke-dashoffset="0"></circle>
                    </svg>
                    <div class="position-absolute top-50 start-50 translate-middle text-center">
                        <span id="timerLabel" class="d-block timer-label mb-1">Fokus</span>
                        <span id="timeDisplay" class="timer-text">30:00</span>
                    </div>
                </div>
            </div>
            
            <!-- NOTE: Completed cycles might be broken. Scenario: -->
            <!-- * User open the page -->
            <!-- * User click mulai (focus mode) -->
            <!-- * User click skip -->
            <!-- * User click mulai (break mode) -->
            <!-- * User click skip -->
            <!-- |-> Completed cycles stays 0, but it should be 1. Unless, by design it doesn't count skip as a cycle. -->
            <!-- NOTE: After further testing, turns out the cycle count doesn't count skip as a cycle. -->
            <!-- TODO: UNIMPORTANT but neat, sync with db state on reload and handle cycles -->
            <h5 class="text-muted mb-4">Completed Cycles: <span id="cycleCount" class="fw-bold text-dark">0</span></h5>
            
            <div class="d-flex justify-content-center gap-3 mb-4 flex-wrap">
                <button id="btnStart" class="pill-btn btn btn-primary shadow-sm" onclick="startTimer()"><i class="fas fa-play me-2"></i>Mulai</button>
                <button id="btnPause" class="pill-btn btn btn-warning text-white shadow-sm d-none" onclick="pauseTimer()"><i class="fas fa-pause me-2"></i>Jeda</button>
                <button id="btnStop" class="pill-btn btn btn-light shadow-sm d-none" onclick="stopTimer()"><i class="fas fa-stop me-2"></i>Stop</button>
                <button id="btnSkip" class="pill-btn btn btn-outline-success shadow-sm d-none" onclick="skipTimer()"><i class="fas fa-forward me-2"></i>Skip</button>
            </div>
            
            <div class="px-md-5 d-flex gap-2 align-items-center">
                <input type="text" id="taskInput" class="task-input-pomodoro" placeholder="Sedang mengerjakan apa? (Bebas diisi)">
                
                <?php if (session()->get('email')): ?>
                <button class="btn btn-outline-secondary rounded-3 flex-shrink-0" data-bs-toggle="offcanvas" data-bs-target="#offcanvasTaskList" title="Pilih dari daftar tugas">
                    <i class="fas fa-list"></i>
                </button>
                <?php endif; ?>
            </div>

        </div>
    </div>
    
    <div class="col-lg-5">
        <div class="card p-4 mb-4">
            <h6 class="fw-bold text-dark mb-3"><i class="fas fa-sliders-h me-2 text-primary"></i>Pengaturan Waktu</h6>
            <div class="setting-box mb-3"><span class="fw-semibold text-muted small">Waktu Kerja</span><div class="d-flex align-items-center gap-2"><button class="setting-btn" onclick="adjustTime('work', -5)">-</button><span id="workTimeDisplay" class="fw-bold mx-2" style="min-width: 30px;">30</span><button class="setting-btn" onclick="adjustTime('work', 5)">+</button><span class="text-muted small ms-1">mnt</span></div></div>
            <div class="setting-box"><span class="fw-semibold text-muted small">Waktu Istirahat</span><div class="d-flex align-items-center gap-2"><button class="setting-btn" onclick="adjustTime('rest', -1)">-</button><span id="restTimeDisplay" class="fw-bold mx-2" style="min-width: 30px;">5</span><button class="setting-btn" onclick="adjustTime('rest', 1)">+</button><span class="text-muted small ms-1">mnt</span></div></div>
        </div>
        <div class="card p-4">
            <h6 class="fw-bold text-dark mb-3"><i class="fas fa-lightbulb me-2 text-warning"></i>Tips Fokus</h6>
            <div class="d-flex align-items-start gap-2 mb-2 text-muted small"><i class="fas fa-check-circle mt-1 text-primary"></i><span>Tentukan 1 tugas spesifik sebelum memulai.</span></div>
            <div class="d-flex align-items-start gap-2 mb-2 text-muted small"><i class="fas fa-check-circle mt-1 text-primary"></i><span>Jangan skip sesi istirahat, otak butuh jeda!</span></div>
            <div class="d-flex align-items-start gap-2 text-muted small"><i class="fas fa-check-circle mt-1 text-primary"></i><span>Minum air saat istirahat.</span></div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="<?= base_url('assets/js/pomodoro.js') ?>"></script>
<?= $this->endSection() ?>