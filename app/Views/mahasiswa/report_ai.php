<?= $this->extend('mahasiswa/layout_main') ?>

<?= $this->section('title') ?>Report AI<?= $this->endSection() ?>
<?= $this->section('page_title') ?>Report AI<?= $this->endSection() ?>
<?= $this->section('page_sub') ?>Hasil pemrosesan data oleh microservice<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="row align-items-center mb-4">
    <div class="col-12">
        <h4 class="fw-bold m-0">Visualisasi Kondisi Mental</h4>
        <small class="text-muted">Data diproses oleh <strong>FastAPI & Random Forest</strong>.</small>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <?php 
        $bgGradient = 'linear-gradient(135deg, #ff7675 0%, #fab1a0 100%)'; 
        if ($hasFilledInputs && $stressCategory == 'Sedang') $bgGradient = 'linear-gradient(135deg, #fdcb6e 0%, #ffeaa7 100%)';
        if ($hasFilledInputs && $stressCategory == 'Rendah') $bgGradient = 'linear-gradient(135deg, #00b894 0%, #55efc4 100%)';
        ?>
        
        <div class="card p-4 text-center h-100 d-flex flex-column align-items-center border-0 shadow-sm" style="background: <?= $bgGradient ?>; color: white; border-radius: 1rem;">
            <span class="fw-semibold mb-3 text-uppercase" style="font-size: 0.8rem; letter-spacing: 2px; opacity: 0.9;">AI STRESS SCORE</span>
            
            <?php if (!$hasFilledInputs): ?>
                <div class="d-flex flex-column justify-content-center align-items-center flex-grow-1 w-100 gap-3">
                    
                    <div class="p-3 w-100 rounded-4" style="background: rgba(255, 255, 255, 0.1); border: 1.5px dashed rgba(255, 255, 255, 0.5);">
                        <p class="mb-1 fw-bold text-white" style="font-size: 0.95rem;">Belum ada prediksi untuk hari ini</p>
                        <p class="small m-0 text-white opacity-75">Klik tombol untuk menampilkan hasil prediksi.</p>
                    </div>
                    
                    <button class="btn btn-light rounded-pill px-4 py-2 fw-bold shadow-sm w-100" 
                            style="color: #ff7675;" 
                            data-hastasks="<?= $hasTasks ? 'true' : 'false' ?>"
                            data-haspomodoro="<?= $hasPomodoro ? 'true' : 'false' ?>"
                            onclick="cekPrasyaratPrediksi(this)">
                        Melihat hasil prediksi
                    </button>
                </div>
            <?php endif; ?>

            <?php if ($hasFilledInputs): ?>
                <div class="d-flex flex-column justify-content-center align-items-center flex-grow-1 w-100">
                    <div class="p-3 rounded-4 w-100 animate__animated animate__fadeIn" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.2);">
                        <p class="small m-0 opacity-90 mb-1">Prediksi Stress Level</p>
                        <h1 class="fw-bolder m-0" style="font-size: 3rem;"><?= $stressCategory ?></h1>
                        <p class="small m-0 opacity-75 mt-1">
                            <?= $stressCategory == 'Tinggi' ? 'High Risk Zone' : ($stressCategory == 'Sedang' ? 'Warning Zone' : 'Safe Zone') ?>
                        </p>
                        <p class="small mb-0 mt-2 opacity-90">Dianalisis pada pukul <?= (new DateTime($latestAnalysis['created_at']))->setTimezone(new DateTimeZone('Asia/Jakarta'))->format('H:i:s') ?> WIB</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card p-4 h-100 border-0 shadow-sm" style="border-radius: 1rem;">
            
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
                <div>
                    <h6 class="fw-bold text-dark m-0"><i class="fas fa-chart-area me-2 text-primary"></i>Tren Tingkat Stres Kamu</h6>
                    <small class="text-muted">Pantau fluktuasi kondisi mentalmu dari waktu ke waktu.</small>
                </div>
                
                <select class="form-select form-select-sm w-auto rounded-pill border-0 bg-light fw-semibold text-muted shadow-none ps-3" id="filterTrenStres" style="min-width: 165px; cursor: pointer;">
                    <option value="harian" selected>7 Hari Terakhir</option>
                    <option value="mingguan">4 Minggu Terakhir</option>
                    <option value="bulanan">6 Bulan Terakhir</option>
                </select>
            </div>
            
            <div class="bg-light rounded-4 d-flex flex-column align-items-center justify-content-center w-100" style="height: 180px; border: 2px dashed #e2e8f0; position: relative;">
                <canvas id="studentStressChart" style="max-height: 100%; width: 100%; z-index: 2;"></canvas>
                
                <span id="chartPlaceholder" class="text-muted small fw-semibold position-absolute text-center ps-3" style="z-index: 1;">
                    <i class="fas fa-chart-line fa-2x mb-2 opacity-50"></i><br>
                    Memuat grafik...
                </span>
            </div>
            
        </div>
    </div>

    <div class="col-md-5">
        <div class="card p-4 h-100 border-0 shadow-sm" style="border-radius: 1rem;">
            <h6 class="fw-bold text-dark mb-4"><i class="fas fa-chart-pie me-2 text-danger"></i>Level Pemicu Stres</h6>
            
            <?php
            if (isset($potentialStressFactors)) {
                $propertyMap = [
                    'tinggi' => [ 'color' => 'danger', 'color_bg' => '#ffeaea', 'progress' => '100%' ],
                    'buruk' => [ 'color' => 'danger', 'color_bg' => '#ffeaea', 'progress' => '100%' ], // Specifically for sleep_quality
                    'sedang' => [ 'color' => 'warning', 'color_bg' => '#fff9e6', 'progress' => '50%' ],
                    'rendah' => [ 'color' => 'info', 'color_bg' => '#e3f2fd', 'progress' => '5%' ],
                    'baik' => [ 'color' => 'info', 'color_bg' => '#e3f2fd', 'progress' => '5%' ], // Specifically for sleep_quality
                ];

                $deadlineColor = $propertyMap[$potentialStressFactors['deadline_is_tomorrow_tasks']]['color'];
                $deadlineBgColor = $propertyMap[$potentialStressFactors['deadline_is_tomorrow_tasks']]['color_bg'];
                $deadlineProgress = $propertyMap[$potentialStressFactors['deadline_is_tomorrow_tasks']]['progress'];

                $pilingUpTasksColor = $propertyMap[$potentialStressFactors['piling_up_tasks']]['color'];
                $pilingUpTasksBgColor = $propertyMap[$potentialStressFactors['piling_up_tasks']]['color_bg'];
                $pilingUpTasksProgress = $propertyMap[$potentialStressFactors['piling_up_tasks']]['progress'];

                $sleepColor = $propertyMap[$potentialStressFactors['sleep_quality']]['color'];
                $sleepBgColor = $propertyMap[$potentialStressFactors['sleep_quality']]['color_bg'];
                $sleepProgress = $propertyMap[$potentialStressFactors['sleep_quality']]['progress'];
            }
            ?>

            <div class="mb-3">
                <div class="d-flex justify-content-between mb-1">
                    <small class="fw-semibold">Deadline Mepet</small>
                    <small class="text-<?= $deadlineColor ?> fw-bold text-uppercase" style="font-size: 0.75rem;"><?= $potentialStressFactors['deadline_is_tomorrow_tasks'] ?></small>
                </div>
                <div class="progress rounded-pill" style="height: 10px; background: <?= $deadlineBgColor ?>;">
                    <div class="progress-bar rounded-pill bg-<?= $deadlineColor ?>" style="width: <?= $deadlineProgress ?>;"></div>
                </div>
            </div>
            
            <div class="mb-3">
                <div class="d-flex justify-content-between mb-1">
                    <small class="fw-semibold">Tugas Menumpuk</small>
                    <small class="text-<?= $pilingUpTasksColor ?> fw-bold text-uppercase" style="font-size: 0.75rem;"><?= $potentialStressFactors['piling_up_tasks'] ?></small>
                </div>
                <div class="progress rounded-pill" style="height: 10px; background: <?= $pilingUpTasksBgColor ?>;">
                    <div class="progress-bar rounded-pill bg-<?= $pilingUpTasksColor ?>" style="width: <?= $pilingUpTasksProgress ?>;"></div>
                </div>
            </div>
            
            <div>
                <div class="d-flex justify-content-between mb-1">
                    <small class="fw-semibold">Kualitas Tidur</small>
                    <small class="text-<?= $sleepColor ?> fw-bold text-uppercase" style="font-size: 0.75rem;"><?= $potentialStressFactors['sleep_quality'] ?></small>
                </div>
                <div class="progress rounded-pill" style="height: 10px; background: <?= $sleepBgColor ?>;">
                    <div class="progress-bar rounded-pill bg-<?= $sleepColor ?>" style="width: <?= $sleepProgress ?>;"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card p-4 h-100 border-0 shadow-sm" style="border-radius: 1rem; border-left: 5px solid #6c5ce7 !important; background: linear-gradient(to right, #f8f7ff, #ffffff);">
            <h6 class="fw-bold text-dark mb-4"><i class="fas fa-robot me-2" style="color: #6c5ce7;"></i>Rekomendasi</h6>
            <ul class="list-unstyled mb-0">
                <?php foreach ($recommendations as $recommendation) : ?>
                    <?php
                        $iconMap = [
                            'deadline_is_tomorrow_tasks' => '<i class="fas fa-solid fa-clock text-'.$recommendation['color_label'].' fa-lg"></i>',
                            'piling_up_tasks' => '<i class="fas fa-solid fa-tasks text-'.$recommendation['color_label'].' fa-lg"></i>',
                            'sleep_quality' => '<i class="fas fa-solid fa-bed text-'.$recommendation['color_label'].' fa-lg"></i>',
                            'other' => '<i class="fas fa-solid fa-lightbulb text-'.$recommendation['color_label'].' fa-lg"></i>'
                        ]
                    ?>
                    <li class="d-flex mb-3"><span class="me-3 mt-1"><?= $iconMap[$recommendation['subject']] ?></span><div><strong class="text-<?= $recommendation['color_label'] ?>"><?= $recommendation['messages'][0] ?></strong><p class="text-muted small mb-0"><?= $recommendation['messages'][1] ?></p></div></li>
                <?php endforeach ?>
            </ul>
        </div>
    </div>

    <div class="col-12 mt-2">
        <div class="card p-4 border-0 shadow-sm" style="border-radius: 1rem;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h6 class="fw-bold text-dark m-0"><i class="fas fa-history me-2 text-primary"></i>Riwayat Prediksi AI</h6>
                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalRiwayat">
                    Lihat Semua
                </button>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="white-space: nowrap;">
                    <thead class="table-light text-muted" style="font-size: 0.85rem;">
                        <tr>
                            <th class="fw-semibold rounded-start-3 border-0 py-3 ps-3 text-center" style="width: 50px;">No</th>
                            <th class="fw-semibold border-0 py-3">Tanggal</th>
                            <th class="fw-semibold border-0 py-3 text-center">Self Esteem</th>
                            <th class="fw-semibold border-0 py-3 text-center">Riwayat Mental</th>
                            <th class="fw-semibold border-0 py-3 text-center">Depresi</th>
                            <th class="fw-semibold border-0 py-3 text-center">Sakit Kepala</th>
                            <th class="fw-semibold border-0 py-3 text-center">Kualitas Tidur</th>
                            <th class="fw-semibold border-0 py-3 text-center">Performa Akademik</th>
                            <th class="fw-semibold border-0 py-3 text-center">Beban Belajar</th>
                            <th class="fw-semibold border-0 py-3 text-center">Dukungan Sosial</th>
                            <th class="fw-semibold rounded-end-3 border-0 py-3 text-center pe-3">Stres Level</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 0.9rem;">
                        <?php if (count($allStressData) > 0) { ?>
                            <?php 
                                // Definisikan warna badge berdasarkan stress level
                                $stressLevelColorMap = [
                                    'Tinggi' => 'danger',
                                    'Sedang' => 'warning',
                                    'Rendah' => 'success'
                                ];

                                // Definisikan warna badge berdasarkan riwayat mental health
                                $mentalHealthColorMap = [
                                    true => 'danger',
                                    false => 'success'
                                ];
                            ?>
                            <?php for ($i = 0; $i < (count($allStressData) >= 7 ? 7 : count($allStressData)); $i++) { ?>
                            <tr>
                                <td class="border-bottom-0 py-3 ps-3 text-center fw-bold text-muted"><?= $i + 1 ?></td>
                                <td class="border-bottom-0 py-3"></i><?= date('d/m/Y', strtotime($allStressData[$i]['created_at'])) ?></td>
                                <td class="border-bottom-0 py-3 text-center"><?= $allStressData[$i]['self_esteem'] ?></td>
                                <td class="border-bottom-0 py-3 text-center">
                                    <?php 
                                        // Ambil warna badge, default 'secondary' jika tidak ditemukan
                                        $mentalHealthBadgeColor = $mentalHealthColorMap[$allStressData[$i]['mental_health_history']] ?? 'secondary';
                                    ?>

                                    <span class="badge bg-<?= $mentalHealthBadgeColor ?> text-white rounded-pill px-3 py-2"><?= $allStressData[$i]['mental_health_history'] ? 'Ada' : 'Tidak Ada' ?></span>
                                </td>
                                <td class="border-bottom-0 py-3 text-center"><?= $allStressData[$i]['depression'] ?></td>
                                <td class="border-bottom-0 py-3 text-center"><?= $allStressData[$i]['headache'] ?></td>
                                <td class="border-bottom-0 py-3 text-center"><?= $allStressData[$i]['sleep_quality'] ?></td>
                                <td class="border-bottom-0 py-3 text-center"><?= $allStressData[$i]['academic_performance'] ?></td>
                                <td class="border-bottom-0 py-3 text-center"><?= $allStressData[$i]['study_load'] ?></td>
                                <td class="border-bottom-0 py-3 text-center"><?= $allStressData[$i]['social_support'] ?></td>
                                <td class="border-bottom-0 py-3 text-center pe-3">
                                    <?php 
                                        // Ambil warna badge, default 'secondary' jika tidak ditemukan
                                        $stressLevelBadgeColor = $stressLevelColorMap[$allStressData[$i]['stress_level']] ?? 'secondary';
                                    ?>

                                    <span class="badge bg-<?= $stressLevelBadgeColor ?> text-white rounded-pill px-3 py-2"><?= $allStressData[$i]['stress_level'] ?></span>
                                </td>
                            </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="10" class="text-center py-3">
                                    <div class="text-center py-5">
                                        <i class="fas fa-chart-pie fa-2x text-muted mb-3"></i>
                                        <h6 class="text-muted fw-semibold">Belum Ada Riwayat Prediksi</h6>
                                        <p class="text-muted small">Lakukan aktivitas untuk menghasilkan prediksi AI.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>

                        <!-- <tr>
                            <td class="border-bottom-0 py-3 ps-3 text-center fw-bold text-muted">1</td>
                            <td class="border-bottom-0 py-3"></i>15/05/2026</td>
                            <td class="border-bottom-0 py-3 text-center">12</td>
                            <td class="border-bottom-0 py-3 text-center"><span class="badge bg-danger-subtle text-danger">Ada</span></td>
                            <td class="border-bottom-0 py-3 text-center">18</td>
                            <td class="border-bottom-0 py-3 text-center">3</td>
                            <td class="border-bottom-0 py-3 text-center">2</td>
                            <td class="border-bottom-0 py-3 text-center">3</td>
                            <td class="border-bottom-0 py-3 text-center">4</td>
                            <td class="border-bottom-0 py-3 text-center">2</td>
                            <td class="border-bottom-0 py-3 text-center pe-3">
                                <span class="badge bg-warning text-dark rounded-pill px-3 py-2">Sedang</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="border-bottom-0 py-3 ps-3 text-center fw-bold text-muted">2</td>
                            <td class="border-bottom-0 py-3"></i>14/05/2026</td>
                            <td class="border-bottom-0 py-3 text-center">26</td>
                            <td class="border-bottom-0 py-3 text-center"><span class="badge bg-success-subtle text-success">Tidak</span></td>
                            <td class="border-bottom-0 py-3 text-center">4</td>
                            <td class="border-bottom-0 py-3 text-center">1</td>
                            <td class="border-bottom-0 py-3 text-center">4</td>
                            <td class="border-bottom-0 py-3 text-center">4</td>
                            <td class="border-bottom-0 py-3 text-center">2</td>
                            <td class="border-bottom-0 py-3 text-center">4</td>
                            <td class="border-bottom-0 py-3 text-center pe-3">
                                <span class="badge bg-success rounded-pill px-3 py-2">Rendah</span>
                            </td>
                        </tr> -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalRiwayat" tabindex="-1" aria-labelledby="modalRiwayatLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-bold" id="modalRiwayatLabel"><i class="fas fa-list me-2 text-primary"></i>Riwayat Lengkap Prediksi AI</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <p class="text-muted small m-0">Seluruh data riwayat prediksi kondisimu terekam di sini.</p>
                    <button class="btn btn-success btn-sm rounded-pill px-3 fw-semibold shadow-sm" onclick="exportTableToCSV('Riwayat_Prediksi_Fokusin.csv')">
                        <i class="fas fa-file-csv me-2"></i>Export ke CSV
                    </button>
                </div>
                
                <div class="table-responsive rounded-3 border">
                    <table class="table table-hover align-middle mb-0" id="tabelRiwayatLengkap" style="white-space: nowrap;">
                        <thead class="table-light text-muted" style="font-size: 0.85rem;">
                            <tr>
                                <th class="fw-semibold py-3 ps-3 text-center">No</th>
                                <th class="fw-semibold py-3">Tanggal</th>
                                <th class="fw-semibold py-3 text-center">Self Esteem</th>
                                <th class="fw-semibold py-3 text-center">Riwayat Mental</th>
                                <th class="fw-semibold py-3 text-center">Depresi</th>
                                <th class="fw-semibold py-3 text-center">Sakit Kepala</th>
                                <th class="fw-semibold py-3 text-center">Kualitas Tidur</th>
                                <th class="fw-semibold py-3 text-center">Performa Akademik</th>
                                <th class="fw-semibold py-3 text-center">Beban Belajar</th>
                                <th class="fw-semibold py-3 text-center">Dukungan Sosial</th>
                                <th class="fw-semibold py-3 text-center pe-3">Stres Level</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 0.9rem;">
                            <?php if (count($allStressData) > 0) { ?>
                                <?php foreach ($allStressData as $key => $value) { ?>
                                <tr>
                                    <td class="py-3 ps-3 text-center fw-bold text-muted"><?= $key + 1 ?></td>
                                    <td class="py-3"><?= date('d/m/Y', strtotime($value['created_at'])) ?></td>
                                    <td class="py-3 text-center"><?= $value['self_esteem'] ?></td>
                                    <td class="py-3 text-center"><?= $value['mental_health_history'] ? 'Ada' : 'Tidak Ada' ?></td>
                                    <td class="py-3 text-center"><?= $value['depression'] ?></td>
                                    <td class="py-3 text-center"><?= $value['headache'] ?></td>
                                    <td class="py-3 text-center"><?= $value['sleep_quality'] ?></td>
                                    <td class="py-3 text-center"><?= $value['academic_performance'] ?></td>
                                    <td class="py-3 text-center"><?= $value['study_load'] ?></td>
                                    <td class="py-3 text-center"><?= $value['social_support'] ?></td>
                                    <td class="py-3 text-center pe-3"><?= $value['stress_level'] ?></td>
                                </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="11" class="text-center py-3">
                                        <div class="text-center py-5">
                                            <i class="fas fa-chart-pie fa-2x text-muted mb-3"></i>
                                            <h6 class="text-muted fw-semibold">Belum Ada Riwayat Prediksi</h6>
                                            <p class="text-muted small">Lakukan aktivitas untuk menghasilkan prediksi AI.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                            <!-- <tr>
                                <td class="py-3 ps-3 text-center fw-bold text-muted">1</td>
                                <td class="py-3">15/05/2026</td>
                                <td class="py-3 text-center">12</td>
                                <td class="py-3 text-center">Ada</td>
                                <td class="py-3 text-center">18</td>
                                <td class="py-3 text-center">3</td>
                                <td class="py-3 text-center">2</td>
                                <td class="py-3 text-center">3</td>
                                <td class="py-3 text-center">4</td>
                                <td class="py-3 text-center">2</td>
                                <td class="py-3 text-center pe-3">2 - Sedang</td>
                            </tr>
                            <tr>
                                <td class="py-3 ps-3 text-center fw-bold text-muted">2</td>
                                <td class="py-3">14/05/2026</td>
                                <td class="py-3 text-center">26</td>
                                <td class="py-3 text-center">Tidak</td>
                                <td class="py-3 text-center">4</td>
                                <td class="py-3 text-center">1</td>
                                <td class="py-3 text-center">4</td>
                                <td class="py-3 text-center">4</td>
                                <td class="py-3 text-center">2</td>
                                <td class="py-3 text-center">4</td>
                                <td class="py-3 text-center pe-3">1 - Rendah</td>
                            </tr> -->
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="<?= base_url('assets/js/report.js') ?>"></script>
<?= $this->endSection() ?>