<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fokusin v2.0 - Login & Register</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="<?= base_url('assets/css/global.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/auth.css') ?>">
</head>
<body>

    <div class="auth-container">
        <div class="auth-branding">
            <div class="d-flex align-items-center gap-3 mb-4">
                <img src="<?= base_url('assets/img/logo-fokusin.png') ?>" alt="Logo" class="logo-fokusin">
                <h2 class="fw-bolder text-white m-0" style="letter-spacing: 2px;">FOKUSIN</h2>
            </div>
            <div class="brand-tagline">Sistem Deteksi Dini Kondisi Mahasiswa. Kelola tugas, atur fokus, dan biarkan AI yang memantau stresmu.</div>
            <ul class="brand-features">
                <li><i class="fas fa-check"></i> Manajemen Tugas Akademik</li>
                <li><i class="fas fa-check"></i> Timer Pomodoro Terintegrasi</li>
                <li><i class="fas fa-check"></i> Analisis Stres AI (Random Forest)</li>
                <li><i class="fas fa-check"></i> Early Warning Burnout</li>
            </ul>
        </div>

        <div class="auth-form-area">
            
            <div id="form-login">
                <h3 class="fw-bold text-dark mb-2">Selamat Datang di Fokusin</h3>
                <p class="text-muted mb-4">Silakan masuk untuk melanjutkan fokus.</p>
                
                <form action="<?= base_url('auth/loginProcess') ?>" method="POST" id="loginForm" onsubmit="return handleLogin(event)">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="loginUsername" name="username" placeholder="Username" required>
                        <label for="loginUsername"><i class="fas fa-user me-2"></i>Username</label>
                    </div>
                    <div class="form-floating">
                        <input type="password" class="form-control" id="loginPassword" name="password" placeholder="Password" required>
                        <label for="loginPassword"><i class="fas fa-lock me-2"></i>Password</label>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="rememberMe" name="remember">
                            <label class="form-check-label small text-muted" for="rememberMe">Ingat saya</label>
                        </div>
                        <a href="#" class="small text-muted" onclick="showAlertInfo('Fitur reset password akan diarahkan ke CI4.')" style="text-decoration:none;">Lupa Password?</a>
                    </div>

                    <button type="submit" class="btn btn-primary btn-auth w-100 mb-3 shadow-sm">
                        <i class="fas fa-sign-in-alt me-2"></i>Masuk
                    </button>
                </form>

                <p class="text-center toggle-text mt-4">
                    Belum punya akun? <a onclick="toggleForm('register')">Daftar Sekarang</a>
                </p>
            </div>

            <div id="form-register" style="display: none;">
                <h3 class="fw-bold text-dark mb-2">Buat Akun Baru</h3>
                <p class="text-muted mb-4">Bergabunglah dan cegah burnout sekarang.</p>
                
                <form action="<?= base_url('auth/registerProcess') ?>" method="POST" id="registerForm" onsubmit="return handleRegister(event)">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="regNama" name="nama_lengkap" placeholder="Nama Lengkap" required>
                        <label for="regNama"><i class="fas fa-id-card me-2"></i>Nama Lengkap</label>
                    </div>
                    <div class="form-floating">
                        <input type="text" class="form-control" id="regUsername" name="username" placeholder="Username" required>
                        <label for="regUsername"><i class="fas fa-user me-2"></i>Username</label>
                    </div>
                    <div class="form-floating">
                        <input type="email" class="form-control" id="regEmail" name="email" placeholder="Email" required>
                        <label for="regEmail"><i class="fas fa-envelope me-2"></i>Email Kampus</label>
                    </div>
                    <div class="form-floating">
                        <input type="password" class="form-control" id="regPassword" name="password" placeholder="Password" required>
                        <label for="regPassword"><i class="fas fa-lock me-2"></i>Password</label>
                    </div>
                    <div class="form-floating">
                        <input type="password" class="form-control" id="regConfirmPass" name="konfirmasi_password" placeholder="Konfirmasi" required>
                        <label for="regConfirmPass"><i class="fas fa-lock me-2"></i>Konfirmasi Password</label>
                    </div>

                    <div class="my-4">
                        <hr class="text-muted">
                        <p class="text-muted small mb-3"><i class="fas fa-brain text-danger me-1"></i> <strong>Data Awal untuk Prediksi AI</strong></p>
                    </div>

                    <div class="mb-3">
                        <label for="regMental" style="color:#b2bec3;"><i class="fas fa-heartbeat me-2"></i>Riwayat Kesehatan Mental</label>
                        <select class="form-select" id="regMental" name="riwayat_mental" required style="border-radius:12px; border:2px solid #e9ecef; font-size: 0.9rem;">
                            <option value="" disabled selected></option>
                            <option value="0">0 - Tidak Ada Riwayat</option>
                            <option value="1">1 - Ada Riwayat</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="regAkademik" style="color:#b2bec3;"><i class="fas fa-graduation-cap me-2"></i>Akademik Performance</label>
                        <select class="form-select" id="regAkademik" name="akademik_performa" required style="border-radius:12px; border:2px solid #e9ecef; font-size: 0.9rem;;">
                            <option value="" disabled selected></option>
                            <option value="1">1 - Sangat Rendah</option>
                            <option value="2">2 - Rendah</option>
                            <option value="3">3 - Cukup</option>
                            <option value="4">4 - Tinggi</option>
                            <option value="5">5 - Sangat Tinggi</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="regSocial" style="color:#b2bec3;"><i class="fas fa-users me-2"></i>Dukungan Sosial Lingkungan</label>
                        <select class="form-select" id="regSocial" name="dukungan_sosial" required style="border-radius:12px; border:2px solid #e9ecef; font-size: 0.9rem;">
                            <option value="" disabled selected></option>
                            <option value="1">1 - Rendah</option>
                            <option value="2">2 - Cukup</option>
                            <option value="3">3 - Tinggi</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary btn-auth w-100 mb-3 mt-4 shadow-sm">
                        <i class="fas fa-user-plus me-2"></i>Daftar Akun
                    </button>
                </form>

                <p class="text-center toggle-text mt-3">
                    Sudah punya akun? <a onclick="toggleForm('login')">Masuk di sini</a>
                </p>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleForm(target) {
            const formLogin = document.getElementById('form-login');
            const formRegister = document.getElementById('form-register');
            
            if(target === 'register') {
                formLogin.style.display = 'none';
                formRegister.style.display = 'block';
                formRegister.style.animation = 'fadeIn 0.3s ease';
            } else {
                formRegister.style.display = 'none';
                formLogin.style.display = 'block';
                formLogin.style.animation = 'fadeIn 0.3s ease';
            }
        }

        const styleSheet = document.createElement("style");
        styleSheet.innerText = `@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }`;
        document.head.appendChild(styleSheet);

        // NANTI SAAT MODEL/DATABASE SIAP, FUNGSI INI AKAN KITA UBAH AGAR MENGIRIM DATA KE CI4 
        // Untuk saat ini, kita biarkan logic prototipe aslimu
        function handleLogin(event) {
            event.preventDefault(); 
            const user = document.getElementById('loginUsername').value;
            Swal.fire({
                title: 'Login Berhasil! ✅',
                text: `Halo ${user}, sedang mengalihkan ke dashboard...`,
                icon: 'success',
                confirmButtonColor: '#00b894'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?= base_url("mahasiswa") ?>';
                }
            });
        }

        function handleRegister(event) {
            event.preventDefault();
            const pass = document.getElementById('regPassword').value;
            const confirmPass = document.getElementById('regConfirmPass').value;
            const user = document.getElementById('regUsername').value;

            if(pass !== confirmPass) {
                Swal.fire({ title: 'Password Tidak Sama!', icon: 'error', confirmButtonColor: '#ff7675' });
                return false;
            }
            if(pass.length < 6) {
                Swal.fire({ title: 'Password Terlalu Lemah!', text: 'Minimal 6 karakter.', icon: 'warning', confirmButtonColor: '#ffeaa7' });
                return false;
            }

            Swal.fire({
                title: 'Akun Berhasil Dibuat! 🎉',
                text: 'Silakan login menggunakan username dan password yang baru dibuat.',
                icon: 'success',
                confirmButtonColor: '#00b894'
            }).then((result) => {
                if (result.isConfirmed) {
                    toggleForm('login'); 
                    document.getElementById('loginUsername').value = user;
                }
            });
        }

        function showAlertInfo(msg) { Swal.fire({ title: 'Info', text: msg, icon: 'info', confirmButtonColor: '#74b9ff' }); }
    </script>
</body>
</html>