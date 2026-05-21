<?= $this->extend('admin/layout_main') ?>

<?= $this->section('content') ?>
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card p-3 border-start border-danger border-4">
            <h3 class="text-danger fw-bold">18%</h3>
            <small class="text-muted">Mahasiswa Burnout</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card p-3 border-start border-warning border-4">
            <h3 class="text-warning fw-bold">42%</h3>
            <small class="text-muted">Status Warning</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card p-3 border-start border-success border-4">
            <h3 class="text-success fw-bold">40%</h3>
            <small class="text-muted">Kondisi Sehat</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card p-3 border-start border-primary border-4">
            <h3 class="text-primary fw-bold">120</h3>
            <small class="text-muted">Total Mahasiswa</small>
        </div>
    </div>
</div>

<div class="card p-4 border-0 shadow-sm mb-4" style="border-radius: 1rem;">
    <h6 class="fw-bold text-dark mb-2"><i class="fas fa-microchip me-2 text-primary"></i>Analisis Distribusi Stres (Berdasarkan Faktor Risiko)</h6>
    <p class="text-muted small mb-4">Visualisasi di bawah menunjukkan kelompok mana yang memiliki risiko burnout tertinggi berdasarkan variabel input AI.</p>

    <div class="row g-4">
        <div class="col-md-6">
            <h6 class="fw-bold text-muted small text-uppercase mb-3">Berdasarkan Performa Akademik</h6>

            <div class="mb-3">
                <div class="d-flex justify-content-between mb-1">
                    <small class="fw-semibold">Performa Kurang (Skala 1-2)</small>
                    <small class="text-danger fw-bold text-uppercase" style="font-size: 0.75rem;">Risiko Tinggi</small>
                </div>
                <div class="progress rounded-pill" style="height: 10px; background: #ffeaea;">
                    <div class="progress-bar rounded-pill" style="width: 82%; background-color: #e63946;"></div>
                </div>
            </div>

            <div class="mb-3">
                <div class="d-flex justify-content-between mb-1">
                    <small class="fw-semibold">Performa Cukup/Baik (Skala 3-5)</small>
                    <small class="text-success fw-bold text-uppercase" style="font-size: 0.75rem;">Risiko Rendah</small>
                </div>
                <div class="progress rounded-pill" style="height: 10px; background: #e3f2fd;">
                    <div class="progress-bar rounded-pill" style="width: 25%; background-color: #2a9d8f;"></div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <h6 class="fw-bold text-muted small text-uppercase mb-3">Berdasarkan Dukungan Sosial</h6>

            <div class="mb-3">
                <div class="d-flex justify-content-between mb-1">
                    <small class="fw-semibold">Dukungan Rendah (Skala 1)</small>
                    <small class="text-danger fw-bold text-uppercase" style="font-size: 0.75rem;">Risiko Tinggi</small>
                </div>
                <div class="progress rounded-pill" style="height: 10px; background: #ffeaea;">
                    <div class="progress-bar rounded-pill" style="width: 70%; background-color: #e63946;"></div>
                </div>
            </div>

            <div class="mb-3">
                <div class="d-flex justify-content-between mb-1">
                    <small class="fw-semibold">Dukungan Tinggi (Skala 3)</small>
                    <small class="text-info fw-bold text-uppercase" style="font-size: 0.75rem;">Risiko Rendah</small>
                </div>
                <div class="progress rounded-pill" style="height: 10px; background: #e3f2fd;">
                    <div class="progress-bar rounded-pill" style="width: 15%; background-color: #48cae4;"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
    
    <div class="col-md-8">
        <div class="card p-4 border-0 shadow-sm h-100" style="border-radius: 1rem;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h6 class="fw-bold text-dark m-0"><i class="fas fa-chart-line me-2 text-primary"></i>Tren Tingkat Stres (30 Hari Terakhir)</h6>
                <select class="form-select form-select-sm w-auto rounded-pill border-0 bg-light fw-semibold text-muted">
                    <option>Bulan Ini</option>
                    <option>Bulan Lalu</option>
                </select>
            </div>
            
            <div class="bg-light rounded-4 d-flex flex-column align-items-center justify-content-center" style="height: 250px; border: 2px dashed #e2e8f0;">
                <i class="fas fa-chart-area fa-3x text-muted opacity-25 mb-2"></i>
                <span class="text-muted small fw-semibold">Area Visualisasi Grafik (Misal: Chart.js)</span>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-4 border-0 shadow-sm h-100" style="border-radius: 1rem;">
            <h6 class="fw-bold text-dark mb-4"><i class="fas fa-exclamation-triangle me-2 text-warning"></i>Top 3 Pemicu Utama</h6>
            
            <p class="text-muted small mb-4">Faktor yang paling dominan memicu stres mahasiswa berdasarkan akumulasi data AI.</p>

            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-end mb-1">
                    <small class="fw-bold text-dark">1. Beban Belajar <br><span class="text-muted fw-normal" style="font-size: 0.75rem;">Terdeteksi pada 150 kasus</span></small>
                    <small class="text-danger fw-bolder text-uppercase" style="font-size: 0.75rem;">Sangat Signifikan</small>
                </div>
                <div class="progress rounded-pill shadow-sm" style="height: 8px; background: #ffeaea;">
                    <div class="progress-bar bg-danger rounded-pill" style="width: 80%;"></div>
                </div>
            </div>

            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-end mb-1">
                    <small class="fw-bold text-dark">2. Kualitas Tidur <br><span class="text-muted fw-normal" style="font-size: 0.75rem;">Terdeteksi pada 98 kasus</span></small>
                    <small class="text-warning fw-bolder text-uppercase" style="font-size: 0.75rem;">Signifikan</small>
                </div>
                <div class="progress rounded-pill shadow-sm" style="height: 8px; background: #fff9e6;">
                    <div class="progress-bar bg-warning rounded-pill" style="width: 55%;"></div>
                </div>
            </div>

            <div class="mb-0">
                <div class="d-flex justify-content-between align-items-end mb-1">
                    <small class="fw-bold text-dark">3. Deadline Mepet <br><span class="text-muted fw-normal" style="font-size: 0.75rem;">Terdeteksi pada 45 kasus</span></small>
                    <small class="text-info fw-bolder text-uppercase" style="font-size: 0.75rem;">Cukup Signifikan</small>
                </div>
                <div class="progress rounded-pill shadow-sm" style="height: 8px; background: #e3f2fd;">
                    <div class="progress-bar bg-info rounded-pill" style="width: 30%;"></div>
                </div>
            </div>
        </div>
    </div>

</div>

    <div class="mt-4 p-3 rounded-3" style="background-color: #f8f9fa; border-left: 4px solid #e63946;">
        <h6 class="fw-bold text-muted small text-uppercase mb-2">Pengaruh Riwayat Kesehatan Mental</h6>
        <div class="d-flex align-items-center">
            <i class="fas fa-heartbeat text-danger fa-2x me-3"></i>
            <p class="m-0 small text-muted">Sistem mendeteksi bahwa mahasiswa dengan <strong>Riwayat Kesehatan Mental</strong> memiliki kecenderungan tingkat stres <strong>signifikan lebih tinggi</strong> dibandingkan yang tidak memiliki riwayat.</p>
        </div>
    </div>
</div>
<?= $this->endSection() ?>