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

                <form action="<?= base_url('auth/loginProcess') ?>" method="POST" id="loginForm">
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
                        <a href="#" class="small text-muted" onclick="showForgotPasswordModal()" style="text-decoration:none;">Lupa Password?</a>
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
                        <label for="regEmail"><i class="fas fa-envelope me-2"></i>Email</label>
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

    <!-- MODAL: LUPA PASSWORD -->
    <div class="modal fade" id="modalForgotPassword" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Lupa Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4">
                    <p class="text-muted small mb-3">Masukkan username kamu. Password akan di-reset ke email yang terdaftar.</p>
                    <div class="form-floating">
                        <input type="text" class="form-control" id="forgotUsername" placeholder="Username" autocomplete="username">
                        <label for="forgotUsername"><i class="fas fa-user me-2"></i>Username</label>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 px-4 pb-4">
                    <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary rounded-3 px-4 shadow-sm" id="btnForgotPassword" onclick="submitForgotPassword()">
                        <i class="fas fa-key me-2"></i>Reset Password
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
        function toggleForm(target) {
            const formLogin = document.getElementById('form-login');
            const formRegister = document.getElementById('form-register');

            if (target === 'register') {
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

        function handleRegister(event) {
            event.preventDefault();
            const form = document.getElementById('registerForm');
            const pass = document.getElementById('regPassword').value;
            const confirmPass = document.getElementById('regConfirmPass').value;
            const submitBtn = form.querySelector('button[type="submit"]');

            if (pass !== confirmPass) {
                Swal.fire({
                    title: 'Password Tidak Sama!',
                    icon: 'error',
                    confirmButtonColor: '#ff7675'
                });
                return false;
            }
            if (pass.length < 8) {
                Swal.fire({
                    title: 'Password Terlalu Lemah!',
                    text: 'Minimal 8 karakter.',
                    icon: 'warning',
                    confirmButtonColor: '#ffeaa7'
                });
                return false;
            }

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mendaftarkan...';

            fetch(form.action, {
                method: 'POST',
                body: new FormData(form)
            })
            .then(res => res.json().then(body => ({ status: res.status, body })))
            .then(({ status, body }) => {
                if (body.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Akun Berhasil Dibuat!',
                        text: body.message,
                        confirmButtonColor: '#00b894'
                    }).then(() => {
                        window.location.href = '<?= base_url('auth/login') ?>';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Registrasi Gagal',
                        text: body.message,
                        confirmButtonColor: '#ff7675'
                    });
                }
            })
            .catch(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text: 'Tidak dapat terhubung ke server. Silakan coba lagi.',
                    confirmButtonColor: '#ff7675'
                });
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-user-plus me-2"></i>Daftar Akun';
            });
        }

        let forgotPasswordModal = null;
        document.addEventListener('DOMContentLoaded', function () {
            const el = document.getElementById('modalForgotPassword');
            if (el) forgotPasswordModal = new bootstrap.Modal(el);
        });

        function showForgotPasswordModal() {
            document.getElementById('forgotUsername').value = '';
            if (forgotPasswordModal) forgotPasswordModal.show();
        }

        async function submitForgotPassword() {
            const username = document.getElementById('forgotUsername').value.trim();
            if (!username) {
                Swal.fire({ icon: 'warning', title: 'Username wajib diisi', confirmButtonColor: '#ffeaa7' });
                return;
            }

            const btn = document.getElementById('btnForgotPassword');
            const originalHTML = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Mereset...';

            try {
                const res = await fetch('<?= base_url('auth/forgot-password') ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ username })
                });
                const json = await res.json();
                if (res.ok) {
                    if (forgotPasswordModal) forgotPasswordModal.hide();
                    Swal.fire({
                        icon: 'success',
                        title: 'Password Direset!',
                        text: json.message || 'Password di-reset menjadi email yang terdaftar. Hubungi admin jika lupa.',
                        confirmButtonColor: '#00b894'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: json.detail || 'Terjadi kesalahan',
                        confirmButtonColor: '#ff7675'
                    });
                }
            } catch {
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan Jaringan',
                    text: 'Tidak dapat terhubung ke server.',
                    confirmButtonColor: '#ff7675'
                });
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalHTML;
            }
        }

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
</body>

</html>