<!DOCTYPE html>
<html lang="id">
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fokusin v2.0 - Sistem Deteksi Dini Kondisi Mahasiswa</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?= base_url('assets/css/global.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/landing.css') ?>">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="#">
                <img src="<?= base_url('assets/img/logo-fokusin.png') ?>" alt="Logo" class="logo-fokusin">
                <span class="fw-bolder fs-4" style="color: #2d3436; letter-spacing: 1px;">FOKUSIN</span>
                <span class="badge bg-dark rounded-pill ms-1" style="font-size: 0.7rem;">v2.0</span>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="bi bi-list fs-2"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-3">
                    <li class="nav-item"><a class="nav-link" href="#features">Alur Kerja</a></li>
                    <li class="nav-item"><a class="nav-link" href="#ai-section">Teknologi AI</a></li>
                    <li class="nav-item"><a class="nav-link" href="#users">Pengguna</a></li>
                </ul>
                <div class="d-flex gap-2 mt-3 mt-lg-0">
                    <a href="<?= base_url('auth/login') ?>" class="btn btn-outline-custom rounded-pill px-4 fw-bold">Masuk</a>
                </div>
            </div>
        </div>
    </nav>

    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 col-12">
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        <span class="tech-badge"><i class="bi bi-cpu me-1"></i>Machine Learning</span>
                        <span class="tech-badge"><i class="bi bi-shield-check me-1"></i>Early Detection</span>
                        <span class="tech-badge"><i class="bi bi-mortarboard me-1"></i>Dedicated for Students</span>
                    </div>
                    <h1 class="hero-title">Cegah Burnout Mahasiswa dengan <span>Deteksi Dini AI</span></h1>
                    <p class="hero-subtitle">
                        Fokusin v2.0 bukan sekadar manajemen tugas. Sistem ini memantau pola kerja, kualitas tidur, dan beban akademik untuk memprediksi tingkat stres mahasiswa secara real-time menggunakan algoritma Random Forest.
                    </p>
                    <div class="btn-group-custom d-flex gap-3">
                        <a href="<?= base_url('mahasiswa/pomodoro') ?>" class="btn btn-modern btn-primary-custom">
                            Coba Timer Sekarang <i class="bi bi-play-circle-fill ms-2"></i>
                        </a>
                        <a href="<?= base_url('auth/login') ?>" class="btn btn-modern btn-outline-custom">
                            Daftar / Masuk <i class="bi bi-box-arrow-in-right ms-2"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 col-12 text-center hero-img-wrap">
                    <img src="<?= base_url('assets/img/hero-img.png') ?>" alt="Fokusin AI Illustration" class="img-fluid float-anim" style="max-width: 85%;">
                </div>
            </div>
        </div>
    </section>

    <section id="features" class="py-5 bg-white">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h2 class="fw-bold" style="font-size: 2.5rem;">Bagaimana Sistem ini Bekerja?</h2>
                <p class="text-muted fs-5">Alur deteksi dini stres mahasiswa dari input hingga intervensi.</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="bento-card">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="step-number">1</div>
                            <span class="fw-bold text-muted small text-uppercase">Input Data</span>
                        </div>
                        <div class="icon-box" style="background: #eef2ff; color: #6366f1;">
                            <i class="bi bi-journal-check"></i>
                        </div>
                        <h4 class="fw-bold fs-5">Manajemen Tugas & Profil</h4>
                        <p class="text-muted small m-0 mt-2">Mahasiswa mengisi riwayat kesehatan mental, IPK, dan mendata tugas beserta deadline-nya.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="bento-card">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="step-number">2</div>
                            <span class="fw-bold text-muted small text-uppercase">Tracking</span>
                        </div>
                        <div class="icon-box" style="background: #fee2e2; color: #ef4444;">
                            <i class="bi bi-stopwatch"></i>
                        </div>
                        <h4 class="fw-bold fs-5">Pomodoro & Check-in</h4>
                        <p class="text-muted small m-0 mt-2">Sistem mencatat durasi fokus harian dan melakukan check-in kualitas tidur malam hari.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="bento-card">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="step-number">3</div>
                            <span class="fw-bold text-muted small text-uppercase">Processing</span>
                        </div>
                        <div class="icon-box" style="background: #fff7ed; color: #f97316;">
                            <i class="bi bi-cpu"></i>
                        </div>
                        <h4 class="fw-bold fs-5">Prediksi Random Forest</h4>
                        <p class="text-muted small m-0 mt-2">Data dikirim ke microservice Python. AI menganalisis pola dan menghitung skor stres (0-100).</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="bento-card">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="step-number">4</div>
                            <span class="fw-bold text-muted small text-uppercase">Intervensi</span>
                        </div>
                        <div class="icon-box" style="background: #dcfce7; color: #22c55e;">
                            <i class="bi bi-bell"></i>
                        </div>
                        <h4 class="fw-bold fs-5">Peringatan Dini</h4>
                        <p class="text-muted small m-0 mt-2">Jika skor tinggi, sistem memunculkan alert ke mahasiswa dan notifikasi ke Admin/Dosen untuk tindak lanjut.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="ai-section" class="py-5" style="background-color: var(--bg-main);">
        <div class="container py-5">
            <div class="row align-items-center g-5">
                <div class="col-lg-5">
                    <h3 class="fw-bold mb-3">Berbasis Machine Learning Nyata</h3>
                    <p class="text-muted">Bukan sekadar logika IF-ELSE. Fokusin v2.0 menggunakan pipeline data science untuk memastikan akurasi prediksi burnout.</p>
                    <ul class="list-unstyled mt-4">
                        <li class="d-flex align-items-start gap-3 mb-3">
                            <i class="bi bi-check-circle-fill text-success fs-5 mt-1"></i>
                            <div><strong>Feature Importance:</strong> Mengetahui variabel apa yang paling mempengaruhi stres (Deadline, Tidur, dsb).</div>
                        </li>
                        <li class="d-flex align-items-start gap-3 mb-3">
                            <i class="bi bi-check-circle-fill text-success fs-5 mt-1"></i>
                            <div><strong>FastAPI Microservice:</strong> Pemrosesan data terpisah dari UI untuk performa maksimal.</div>
                        </li>
                        <li class="d-flex align-items-start gap-3">
                            <i class="bi bi-check-circle-fill text-success fs-5 mt-1"></i>
                            <div><strong>Data Privat & Aman:</strong> Riwayat kesehatan mental dan kondisi pribadi mahasiswa terjaga secara ketat.</div>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-7">
                    <div class="bento-card p-4" style="background: #1e293b; color: white; border:none;">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div style="width:12px; height:12px; background:#ff5f57; border-radius:50%;"></div>
                            <div style="width:12px; height:12px; background:#febc2e; border-radius:50%;"></div>
                            <div style="width:12px; height:12px; background:#28c840; border-radius:50%;"></div>
                            <span class="ms-2 text-muted small">api_predict.py</span>
                        </div>
                        <pre style="color: #a5b4fc; font-size: 0.85rem; margin:0;"><code># Fokusin v2.0 AI Pipeline
from sklearn.ensemble import RandomForestClassifier

def predict_stress(data: InputData):
    features = [
        data.mental_history, # (0: Tidak, 1: Ada)
        data.academic_perf,   # (Skala 1-4)
        data.social_support,  # (Skala 1-3)
        data.sleep_quality,   # (Skala 1-3 Check-in)
        data.deadline_count   # (Integer)
    ]
    
    # Model Random Forest menghasilkan probabilitas
    prediction = rf_model.predict([features])
    stress_score = int(prediction[0] * 100)
    
    return {"status": "Burnout" if stress_score > 80 else "Safe"}</code></pre>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="users" class="py-5 bg-white">
        <div class="container py-5">
            <div class="row align-items-center g-5">
                <div class="col-lg-5">
                    <h3 class="fw-bold mb-3">Fenomena Mahasiswa Saat Ini</h3>
                    <p class="text-muted mb-4">Banyak mahasiswa tidak menyadari bahwa pola kerja mereka sudah masuk zona berbahaya hingga terlambat.</p>
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex align-items-start gap-3 p-3 bg-light rounded-4">
                            <i class="bi bi-emoji-dizzy fs-4 text-danger"></i>
                            <div>
                                <h6 class="fw-bold mb-1 small">75% Mengalami Burnout</h6>
                                <p class="text-muted small mb-0">Berdasarkan survei, mayoritas mahasiswa pernah mengalami kelelahan akademik yang berkepanjangan.</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-start gap-3 p-3 bg-light rounded-4">
                            <i class="bi bi-moon-stars fs-4 text-warning"></i>
                            <div>
                                <h6 class="fw-bold mb-1 small">Pola Tidur Tidak Teratur</h6>
                                <p class="text-muted small mb-0">Mengerjakan tugas larut malam menjadi budaya yang merusak kondisi mental secara perlahan.</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-start gap-3 p-3 bg-light rounded-4">
                            <i class="bi bi-hourglass-split fs-4 text-primary"></i>
                            <div>
                                <h6 class="fw-bold mb-1 small">Deadline Menumpuk</h6>
                                <p class="text-muted small mb-0">Kesulitan memprioritaskan tugas membuat mahasiswa merasa kewalahan dan stuck.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="bento-card p-4 p-lg-5 text-center" style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); color: white; border:none;">
                        <i class="bi bi-shield-check fs-1 mb-3 d-block" style="opacity: 0.8;"></i>
                        <h3 class="fw-bold mb-3">Fokusin Hadir sebagai Solusi</h3>
                        <p class="mb-4" style="opacity: 0.9;">Kamu tidak perlu menghadapi tekanan itu sendirian. Fokusin secara pasif memantau pola tugasmu, mengingatkan untuk istirahat, dan memberikan peringatan dini sebelum kondisimu drop.</p>
                        
                        <div class="row g-3 text-start mt-4">
                            <div class="col-6">
                                <div class="d-flex align-items-center gap-2 p-2" style="background: rgba(255,255,255,0.15); border-radius: 12px;">
                                    <i class="bi bi-check-circle-fill"></i>
                                    <span class="small fw-semibold">Deteksi Otomatis AI</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex align-items-center gap-2 p-2" style="background: rgba(255,255,255,0.15); border-radius: 12px;">
                                    <i class="bi bi-check-circle-fill"></i>
                                    <span class="small fw-semibold">Tanpa Input Rumit</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex align-items-center gap-2 p-2" style="background: rgba(255,255,255,0.15); border-radius: 12px;">
                                    <i class="bi bi-check-circle-fill"></i>
                                    <span class="small fw-semibold">Gratis & Privat</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex align-items-center gap-2 p-2" style="background: rgba(255,255,255,0.15); border-radius: 12px;">
                                    <i class="bi bi-check-circle-fill"></i>
                                    <span class="small fw-semibold">Fokus pada Tugas</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- section ini masih gua non aktifin karena kita belum tau insight
     sebenernya ketika pengguna make aplikasi-->
    <!-- <section id="insights" class="py-5 bg-light">
        <div class="container py-4">
            <div class="text-center mb-5">
                <h5 class="fw-bold" style="color: #6366f1;">
                    <i class="bi bi-lightbulb-fill me-2 text-warning"></i> Fokusin Insights
                </h5>
                <h3 class="fw-bold mb-3">Data Berbicara.</h3>
                <p class="text-muted fs-6">Fakta menarik yang kami pelajari dari pola belajar komunitas mahasiswa bulan ini.</p>
            </div>

            <div class="row g-4 justify-content-center">
                <div class="col-md-5">
                    <div class="bento-card text-center p-4 p-lg-5" style="border-top: 4px solid #6366f1;">
                        <div class="mb-4">
                            <i class="bi bi-moon-stars-fill" style="font-size: 3rem; color: #4f46e5;"></i>
                        </div>
                        <h5 class="fw-bold">Night Owls Merajai</h5>
                        <p class="text-muted small mb-0"><strong>65% pengguna</strong> mencapai tingkat fokus tertinggi (flow state) saat mengerjakan tugas antara pukul 21:00 hingga 01:00 dini hari.</p>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="bento-card text-center p-4 p-lg-5" style="border-top: 4px solid #10b981;">
                        <div class="mb-4">
                            <i class="bi bi-heart-pulse-fill" style="font-size: 3rem; color: #10b981;"></i>
                        </div>
                        <h5 class="fw-bold">Istirahat = Penawar Stres</h5>
                        <p class="text-muted small mb-0">Menyelesaikan minimal <strong>2 siklus Pomodoro</strong> sehari (dengan istirahat rutin) terbukti menurunkan laporan tingkat stres "Tinggi" hingga 30%.</p>
                    </div>
                </div>
            </div>
        </div>
    </section> -->

    <footer id="contact" class="border-top py-5" style="background-color: var(--bg-main);">
        <div class="container text-center">
            <div class="d-flex align-items-center justify-content-center gap-2 mb-3">
                <div style="background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white; width: 30px; height: 30px; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-bullseye"></i>
                </div>
                <span class="fw-bold fs-5">Fokusin v2.0</span>
            </div>
            <p class="text-muted mb-4">Sistem Deteksi Dini Kondisi Mahasiswa Berbasis Machine Learning.<br>
            <!-- <small class="text-muted">Proyek Perangkat Lunak (PPL)</small></p> -->
            
            <div class="d-flex justify-content-center gap-4 mb-4">
                <!-- <a href="#" class="text-decoration-none text-muted fw-semibold"><i class="bi bi-github me-2"></i>GitHub Repo</a> -->
                <a href="#" class="text-decoration-none text-muted fw-semibold"><i class="bi bi-envelope me-2"></i>Tim Development Fokusin</a>
            </div>
            <hr class="text-muted" style="opacity: 0.2;">
            <p class="mb-0 mt-4 text-muted small">
                <!-- <br> -->
                &copy; <?= date('Y') ?> Fokusin Team. All Right Reserved.
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>