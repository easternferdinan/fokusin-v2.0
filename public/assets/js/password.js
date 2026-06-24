let passwordModalInstance = null;
let currentPasswordMode = 'change';

document.addEventListener('DOMContentLoaded', function () {
    const el = document.getElementById('modalPassword');
    if (el) {
        passwordModalInstance = new bootstrap.Modal(el);
        el.addEventListener('hide.bs.modal', function (e) {
            if (currentPasswordMode === 'force') {
                e.preventDefault();
            }
        });
    }

    if (document.body.dataset.forcePassword === 'true') {
        showPasswordModal('force');
    }
});

function showPasswordModal(mode) {
    currentPasswordMode = mode;
    const modalEl = document.getElementById('modalPassword');
    const oldPwGroup = document.getElementById('oldPasswordGroup');
    const title = document.getElementById('modalPasswordLabel');
    const btnText = document.getElementById('btnPasswordText');
    const btnBatal = document.getElementById('btnBatalPassword');
    const btnClose = modalEl.querySelector('.btn-close');

    if (mode === 'force') {
        oldPwGroup.classList.add('d-none');
        title.textContent = 'Buat Password Baru';
        btnText.textContent = 'Simpan Password';
        btnBatal.classList.add('d-none');
        btnClose.classList.add('d-none');
    } else {
        oldPwGroup.classList.remove('d-none');
        title.textContent = 'Ubah Password';
        btnText.textContent = 'Ganti Password';
        btnBatal.classList.remove('d-none');
        btnClose.classList.remove('d-none');
    }

    document.querySelectorAll('#modalPassword .form-control').forEach(function (el) {
        el.classList.remove('is-invalid');
    });
    document.getElementById('modalPassword').querySelectorAll('input').forEach(function (el) {
        el.value = '';
    });

    if (passwordModalInstance) {
        passwordModalInstance.show();
    }
}

function submitPasswordChange() {
    document.querySelectorAll('#modalPassword .form-control').forEach(function (el) {
        el.classList.remove('is-invalid');
    });

    const oldPassword = document.getElementById('inputOldPassword').value.trim();
    const newPassword = document.getElementById('inputNewPassword').value.trim();
    const confirmPassword = document.getElementById('inputConfirmPassword').value.trim();

    let valid = true;

    if (currentPasswordMode === 'change') {
        if (!oldPassword) {
            document.getElementById('inputOldPassword').classList.add('is-invalid');
            document.getElementById('errorOldPassword').textContent = 'Password lama wajib diisi';
            valid = false;
        }
    }

    if (!newPassword) {
        document.getElementById('inputNewPassword').classList.add('is-invalid');
        document.getElementById('errorNewPassword').textContent = 'Password baru wajib diisi';
        valid = false;
    } else if (newPassword.length < 8) {
        document.getElementById('inputNewPassword').classList.add('is-invalid');
        document.getElementById('errorNewPassword').textContent = 'Password baru minimal 8 karakter';
        valid = false;
    }

    if (!confirmPassword) {
        document.getElementById('inputConfirmPassword').classList.add('is-invalid');
        document.getElementById('errorConfirmPassword').textContent = 'Konfirmasi password wajib diisi';
        valid = false;
    } else if (newPassword !== confirmPassword) {
        document.getElementById('inputConfirmPassword').classList.add('is-invalid');
        document.getElementById('errorConfirmPassword').textContent = 'Password tidak cocok';
        valid = false;
    }

    if (!valid) return;

    const btn = document.getElementById('btnSubmitPassword');
    const originalHTML = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Menyimpan...';

    let body, endpoint;

    if (currentPasswordMode === 'change') {
        endpoint = '/mahasiswa/profile/change-password';
        body = JSON.stringify({
            old_password: oldPassword,
            new_password: newPassword
        });
    } else {
        endpoint = '/mahasiswa/profile/change-password-force';
        body = JSON.stringify({
            password: newPassword
        });
    }

    fetch(endpoint, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: body
    })
    .then(function (res) {
        return res.json().then(function (data) {
            return { ok: res.ok, data: data };
        });
    })
    .then(function ({ ok, data }) {
        if (ok) {
            if (currentPasswordMode === 'force') {
                document.body.dataset.forcePassword = 'false';
            }
            currentPasswordMode = 'change';
            if (passwordModalInstance) {
                passwordModalInstance.hide();
            }
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message || 'Password berhasil diubah',
                confirmButtonColor: '#00b894'
            });
        } else {
            const msg = data.detail || 'Terjadi kesalahan. Coba lagi.';
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: msg,
                confirmButtonColor: '#ff7675'
            });
        }
    })
    .catch(function () {
        Swal.fire({
            icon: 'error',
            title: 'Kesalahan Jaringan',
            text: 'Tidak dapat terhubung ke server.',
            confirmButtonColor: '#ff7675'
        });
    })
    .finally(function () {
        btn.disabled = false;
        btn.innerHTML = originalHTML;
    });
}
