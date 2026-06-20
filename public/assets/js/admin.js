// ==========================================
// LOGIKA JAVASCRIPT KHUSUS DASHBOARD ADMIN
// ==========================================
let modalDetailInstance = null;
let modalEditAdminInstance = null;
let namaMahasiswaAktif = "";
let userIdAktif = null;
let halamanAktif = 1;
let totalHalaman = 1;
const ukuranHalaman = 5;

document.addEventListener('DOMContentLoaded', function () {
    const modalDetail = document.getElementById('modalDetailMahasiswa');
    if (modalDetail) {
        modalDetailInstance = new bootstrap.Modal(modalDetail);
    }

    const modalEdit = document.getElementById('modalEditAdmin');
    if (modalEdit) {
        modalEditAdminInstance = new bootstrap.Modal(modalEdit);
    }
});

// Fungsi untuk mengubah isi modal sesuai mahasiswa yang diklik
function lihatDetail(userId, nama, username, status) {
    namaMahasiswaAktif = nama;

    document.getElementById('detailNama').innerText = nama;
    document.getElementById('detailUsername').innerText = username;

    let avatarUrl = `https://ui-avatars.com/api/?name=${encodeURIComponent(nama)}&color=fff&size=50`;
    let badgeEl = document.getElementById('detailBadge');
    if (status === 'Tinggi') {
        avatarUrl += '&background=ff7675';
        badgeEl.className = 'badge bg-danger text-white';
    } else if (status === 'Sedang') {
        avatarUrl += '&background=fdcb6e';
        badgeEl.className = 'badge bg-warning text-dark';
    } else if (status === 'Rendah') {
        avatarUrl += '&background=55efc4';
        badgeEl.className = 'badge bg-success text-white';
    } else {
        avatarUrl += '&background=grey';
        badgeEl.className = 'badge bg-secondary text-white';
    }

    badgeEl.innerText = status;
    document.getElementById('detailAvatar').src = avatarUrl;

    userIdAktif = userId;
    halamanAktif = 1;
    ambilRiwayatStress(userId, 1, ukuranHalaman);

    if (modalDetailInstance) {
        modalDetailInstance.show();
    }
}

async function ambilRiwayatStress(userId, page, size) {
    const tbody = document.getElementById('riwayatBody');
    tbody.innerHTML = '<tr><td colspan="11" class="py-4 text-center"><div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>Memuat data...</td></tr>';

    try {
        const res = await fetch(`/admin/stress-analysis/${userId}?page=${page}&size=${size}`);
        const json = await res.json();

        if (json.status === 200 && json.data) {
            const data = json.data;
            const rows = data.items || [];
            totalHalaman = Math.ceil(data.total / data.size) || 1;

            if (rows.length === 0) {
                tbody.innerHTML = '<tr><td colspan="11" class="py-4 text-muted">Tidak ada data riwayat.</td></tr>';
            } else {
                let html = '';
                rows.forEach((r, i) => {
                    const no = (data.page - 1) * data.size + i + 1;
                    const tgl = r.created_at
                        ? new Date(r.created_at).toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric' })
                        : '-';
                    const mentalBg = r.mental_health_history ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success';
                    const mentalTxt = r.mental_health_history ? 'Ada' : 'Tidak';
                    const stressBadge = r.stress_level === 'Tinggi' ? 'bg-danger'
                        : r.stress_level === 'Sedang' ? 'bg-warning text-dark' : 'bg-success';
                    html += `<tr class="border-bottom">
                        <td class="fw-bold text-muted py-3">${no}</td>
                        <td>${tgl}</td>
                        <td>${r.self_esteem ?? '-'}</td>
                        <td><span class="badge ${mentalBg}">${mentalTxt}</span></td>
                        <td>${r.depression ?? '-'}</td>
                        <td>${r.headache ?? '-'}</td>
                        <td>${r.sleep_quality ?? '-'}</td>
                        <td>${r.academic_performance ?? '-'}</td>
                        <td>${r.study_load ?? '-'}</td>
                        <td>${r.social_support ?? '-'}</td>
                        <td><span class="badge ${stressBadge} rounded-pill px-3">${r.stress_level || '-'}</span></td>
                    </tr>`;
                });
                tbody.innerHTML = html;
            }

            document.getElementById('infoHalaman').innerText = `Halaman ${data.page} dari ${totalHalaman}`;
            document.getElementById('btnPrev').disabled = data.page <= 1;
            document.getElementById('btnNext').disabled = data.page >= totalHalaman;
        } else {
            tbody.innerHTML = '<tr><td colspan="11" class="py-4 text-muted">Gagal memuat data riwayat.</td></tr>';
        }
    } catch {
        tbody.innerHTML = '<tr><td colspan="11" class="py-4 text-danger">Terjadi kesalahan saat memuat data.</td></tr>';
    }
}

function gantiHalaman(arah) {
    halamanAktif += arah;
    if (halamanAktif < 1) halamanAktif = 1;
    ambilRiwayatStress(userIdAktif, halamanAktif, ukuranHalaman);
}

// Fungsi untuk memunculkan form pencatatan tindakan (Intervensi)
function catatIntervensi() {
    // Sembunyikan modal detail terlebih dahulu agar tidak menumpuk
    if (modalDetailInstance) {
        modalDetailInstance.hide();
    }

    // Tampilkan Form Input menggunakan SweetAlert2 yang Interaktif
    Swal.fire({
        title: `Intervensi: ${namaMahasiswaAktif}`,
        html: `
            <div class="text-start">
                <label class="form-label text-muted small fw-bold">Tindakan / Solusi yang diberikan:</label>
                <textarea id="catatanIntervensi" class="form-control rounded-3" rows="4" placeholder="Misal: Mahasiswa telah dipanggil untuk konseling, diberikan kelonggaran tugas..."></textarea>
            </div>
        `,
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-save me-2"></i>Simpan Catatan',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#6366f1',
        customClass: { popup: 'rounded-4' },
        preConfirm: () => {
            const catatan = document.getElementById('catatanIntervensi').value.trim();
            if (!catatan) {
                Swal.showValidationMessage('Catatan intervensi wajib diisi!');
            }
            return catatan;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Simulasi berhasil menyimpan data ke backend (nanti bisa disambungkan ke AJAX Fetch)
            Swal.fire({
                title: 'Berhasil Tersimpan! ✅',
                text: `Catatan intervensi tindakan untuk ${namaMahasiswaAktif} telah direkam ke dalam sistem.`,
                icon: 'success',
                confirmButtonColor: '#6366f1',
                customClass: { popup: 'rounded-4' }
            });
        } else {
            // Jika user menekan tombol "Batal", buka kembali modal detail utama
            if (modalDetailInstance) {
                modalDetailInstance.show();
            }
        }
    });
}

// Fungsi untuk memproses penambahan user baru via API
async function simpanUserBaru() {
    const fullname = document.getElementById('inputNama').value.trim();
    const username = document.getElementById('inputUsername').value.trim();
    const email = document.getElementById('regEmail').value.trim();
    const password = document.getElementById('inputPassword').value.trim();
    const mentalHealth = document.getElementById('regMental').value;
    const academicPerf = document.getElementById('regAkademik').value;
    const socialSupport = document.getElementById('regSocial').value;

    const errors = [];
    if (!fullname) errors.push('Nama lengkap wajib diisi');
    if (!username) errors.push('Username wajib diisi');
    if (!email) errors.push('Email wajib diisi');
    if (!password) errors.push('Password wajib diisi');
    if (password && password.length < 6) errors.push('Password minimal 6 karakter');
    if (!mentalHealth) errors.push('Riwayat kesehatan mental wajib dipilih');
    if (!academicPerf) errors.push('Akademik performance wajib dipilih');
    if (!socialSupport) errors.push('Dukungan sosial wajib dipilih');

    if (errors.length > 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Validasi Gagal',
            html: errors.map(e => `<div class="text-start">${e}</div>`).join(''),
            confirmButtonColor: '#6366f1',
            customClass: { popup: 'rounded-4' }
        });
        return;
    }

    const btn = document.getElementById('btnSimpanUser');
    const originalHTML = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Menyimpan...';

    try {
        const res = await fetch('/admin/store-mahasiswa', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                fullname,
                username,
                email,
                password,
                mental_health_history: mentalHealth === '1',
                academic_performance: parseInt(academicPerf),
                social_support: parseInt(socialSupport)
            })
        });

        const json = await res.json();

        if (res.ok) {
            const modalEl = document.getElementById('modalTambahUser');
            const modalInstance = bootstrap.Modal.getInstance(modalEl);
            if (modalInstance) {
                modalInstance.hide();
            }

            Swal.fire({
                title: 'Berhasil!',
                text: `Pengguna baru "${fullname}" berhasil ditambahkan.`,
                icon: 'success',
                confirmButtonColor: '#6366f1',
                customClass: { popup: 'rounded-4' }
            }).then(() => {
                document.getElementById('formTambahUser').reset();
                location.reload();
            });
        } else if (res.status === 422) {
            const detail = json.detail || [];
            const messages = detail.map(d => d.msg);
            Swal.fire({
                icon: 'error',
                title: 'Validasi Server Gagal',
                html: messages.map(m => `<div class="text-start">${m}</div>`).join(''),
                confirmButtonColor: '#6366f1',
                customClass: { popup: 'rounded-4' }
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Terjadi kesalahan saat menambahkan pengguna.',
                confirmButtonColor: '#6366f1',
                customClass: { popup: 'rounded-4' }
            });
        }
    } catch {
        Swal.fire({
            icon: 'error',
            title: 'Kesalahan Jaringan',
            text: 'Tidak dapat terhubung ke server.',
            confirmButtonColor: '#6366f1',
            customClass: { popup: 'rounded-4' }
        });
    } finally {
        btn.disabled = false;
        btn.innerHTML = originalHTML;
    }
}

// ==========================================
// FUNGSI TAMBAH / EDIT ADMIN (SUPER ADMIN)
// ==========================================

async function simpanAdminBaru() {
    const fullname = document.getElementById('inputNama').value.trim();
    const username = document.getElementById('inputUsername').value.trim();
    const password = document.getElementById('inputPassword').value.trim();

    const errors = [];
    if (!fullname) errors.push('Nama admin wajib diisi');
    if (!username) errors.push('Username wajib diisi');
    if (!password) errors.push('Password wajib diisi');
    if (password && password.length < 8) errors.push('Password minimal 8 karakter');

    if (errors.length > 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Validasi Gagal',
            html: errors.map(e => `<div class="text-start">${e}</div>`).join(''),
            confirmButtonColor: '#6366f1',
            customClass: { popup: 'rounded-4' }
        });
        return;
    }

    const btn = document.getElementById('btnSimpanUser');
    const originalHTML = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Menyimpan...';

    try {
        const res = await fetch('/admin/store-admin', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ fullname, username, password })
        });

        const json = await res.json();

        if (res.ok) {
            const modalEl = document.getElementById('modalTambahAdmin');
            const modalInstance = bootstrap.Modal.getInstance(modalEl);
            if (modalInstance) modalInstance.hide();

            Swal.fire({
                title: 'Berhasil!',
                text: `Admin "${fullname}" berhasil ditambahkan.`,
                icon: 'success',
                confirmButtonColor: '#6366f1',
                customClass: { popup: 'rounded-4' }
            }).then(() => {
                document.getElementById('formTambahAdmin').reset();
                location.reload();
            });
        } else {
            const detail = json.detail || json.error || 'Terjadi kesalahan.';
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: typeof detail === 'string' ? detail : JSON.stringify(detail),
                confirmButtonColor: '#6366f1',
                customClass: { popup: 'rounded-4' }
            });
        }
    } catch {
        Swal.fire({
            icon: 'error',
            title: 'Kesalahan Jaringan',
            text: 'Tidak dapat terhubung ke server.',
            confirmButtonColor: '#6366f1',
            customClass: { popup: 'rounded-4' }
        });
    } finally {
        btn.disabled = false;
        btn.innerHTML = originalHTML;
    }
}

function editAdmin(btn) {
    document.getElementById('editAdminId').value = btn.dataset.id;
    document.getElementById('editNama').value = btn.dataset.nama;
    document.getElementById('editUsername').value = btn.dataset.username;
    document.getElementById('editPassword').value = '';

    if (modalEditAdminInstance) {
        modalEditAdminInstance.show();
    }
}

async function simpanEditAdmin() {
    const adminId = document.getElementById('editAdminId').value.trim();
    const fullname = document.getElementById('editNama').value.trim();
    const username = document.getElementById('editUsername').value.trim();
    const password = document.getElementById('editPassword').value.trim();

    const errors = [];
    if (!fullname) errors.push('Nama admin wajib diisi');
    if (!username) errors.push('Username wajib diisi');
    if (password && password.length < 8) errors.push('Password minimal 8 karakter');

    if (errors.length > 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Validasi Gagal',
            html: errors.map(e => `<div class="text-start">${e}</div>`).join(''),
            confirmButtonColor: '#6366f1',
            customClass: { popup: 'rounded-4' }
        });
        return;
    }

    const btn = document.getElementById('btnSimpanEditAdmin');
    const originalHTML = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Menyimpan...';

    try {
        const body = { admin_id: adminId, fullname, username };
        if (password) body.password = password;

        const res = await fetch('/admin/update-admin', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(body)
        });

        const json = await res.json();

        if (res.ok) {
            const modalEl = document.getElementById('modalEditAdmin');
            const modalInstance = bootstrap.Modal.getInstance(modalEl);
            if (modalInstance) modalInstance.hide();

            Swal.fire({
                title: 'Berhasil!',
                text: `Data admin "${fullname}" berhasil diperbarui.`,
                icon: 'success',
                confirmButtonColor: '#6366f1',
                customClass: { popup: 'rounded-4' }
            }).then(() => {
                location.reload();
            });
        } else {
            const detail = json.detail || json.error || 'Terjadi kesalahan.';
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: typeof detail === 'string' ? detail : JSON.stringify(detail),
                confirmButtonColor: '#6366f1',
                customClass: { popup: 'rounded-4' }
            });
        }
    } catch {
        Swal.fire({
            icon: 'error',
            title: 'Kesalahan Jaringan',
            text: 'Tidak dapat terhubung ke server.',
            confirmButtonColor: '#6366f1',
            customClass: { popup: 'rounded-4' }
        });
    } finally {
        btn.disabled = false;
        btn.innerHTML = originalHTML;
    }
}

// ==========================================
// HAPUS ADMIN (SUPER ADMIN)
// ==========================================

async function hapusAdmin(btn) {
    const adminId = btn.dataset.id;
    const adminName = btn.dataset.nama;

    const confirm = await Swal.fire({
        title: 'Hapus Admin?',
        text: `Admin "${adminName}" akan dihapus secara permanen.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        confirmButtonText: '<i class="fas fa-trash me-2"></i>Ya, Hapus',
        cancelButtonText: 'Batal',
        customClass: { popup: 'rounded-4' }
    });

    if (!confirm.isConfirmed) return;

    const originalHTML = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span>Menghapus...';

    try {
        const res = await fetch('/admin/delete-admin', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ admin_id: adminId })
        });

        if (res.ok) {
            Swal.fire({
                title: 'Berhasil!',
                text: `Admin "${adminName}" berhasil dihapus.`,
                icon: 'success',
                confirmButtonColor: '#6366f1',
                customClass: { popup: 'rounded-4' }
            }).then(() => location.reload());
            return;
        }

        const json = await res.json();
        const detail = json.detail || json.error || 'Terjadi kesalahan.';
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: typeof detail === 'string' ? detail : JSON.stringify(detail),
            confirmButtonColor: '#6366f1',
            customClass: { popup: 'rounded-4' }
        });
        btn.disabled = false;
        btn.innerHTML = originalHTML;
    } catch {
        Swal.fire({
            icon: 'error',
            title: 'Kesalahan Jaringan',
            text: 'Tidak dapat terhubung ke server.',
            confirmButtonColor: '#6366f1',
            customClass: { popup: 'rounded-4' }
        });
        btn.disabled = false;
        btn.innerHTML = originalHTML;
    }
}

// ==========================================
// FUNGSI LIVE SEARCH TABEL PENGGUNA
// ==========================================
function cariPengguna() {
    // 1. Ambil teks yang diketik user, ubah ke huruf kecil semua (case-insensitive)
    const inputKata = document.getElementById("searchInput").value.toLowerCase();

    // 2. Ambil semua baris (tr) yang ada di dalam <tbody> tabel
    const barisTabel = document.querySelectorAll("table tbody tr");

    // 3. Lakukan perulangan untuk mengecek setiap baris
    barisTabel.forEach(function (baris) {
        // Ambil teks dari kolom pertama (Nama) dan kolom kedua (Username)
        const teksNama = baris.cells[0].textContent.toLowerCase();
        const teksUsername = baris.cells[1].textContent.toLowerCase();

        // Jika teks yang diketik cocok dengan nama ATAU username...
        if (teksNama.includes(inputKata) || teksUsername.includes(inputKata)) {
            baris.style.display = ""; // Munculkan barisnya
        } else {
            baris.style.display = "none"; // Sembunyikan barisnya
        }
    });
}

function simpanConfig(event) {
    event.preventDefault();
    const form = document.getElementById('formConfig');

    Swal.fire({
        title: 'Simpan Konfigurasi?',
        text: "Pengaturan notifikasi baru akan segera diterapkan ke sistem.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Simpan',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
}

// ==========================================
// KIRIM PERINGATAN KE MAHASISWA
// ==========================================
async function kirimAlert(userId, threshold, frequency, btn) {
    const confirm = await Swal.fire({
        title: 'Kirim Peringatan?',
        text: 'Notifikasi akan dikirimkan ke mahasiswa terkait.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e74c3c',
        confirmButtonText: '<i class="fas fa-paper-plane me-2"></i>Ya, Kirim',
        cancelButtonText: 'Batal',
        customClass: { popup: 'rounded-4' }
    });

    if (!confirm.isConfirmed) return;

    const originalHTML = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span>Mengirim...';

    try {
        const res = await fetch('/admin/send-alert', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ user_id: userId, stress_threshold: threshold, stress_threshold_frequency: frequency })
        });

        const json = await res.json();

        if (res.ok) {
            Swal.fire({
                icon: 'success',
                title: 'Peringatan Terkirim!',
                text: 'Notifikasi peringatan telah dikirim ke mahasiswa.',
                confirmButtonColor: '#00b894',
                timer: 3000,
                timerProgressBar: true
            });
            btn.innerHTML = '<i class="fas fa-check me-1"></i>Terkirim';
            btn.classList.replace('btn-danger', 'btn-success');
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: typeof json.detail === 'string' ? json.detail : 'Terjadi kesalahan.',
                confirmButtonColor: '#ff7675'
            });
            btn.disabled = false;
            btn.innerHTML = originalHTML;
        }
    } catch {
        Swal.fire({
            icon: 'error',
            title: 'Kesalahan Jaringan',
            text: 'Tidak dapat terhubung ke server.',
            confirmButtonColor: '#ff7675'
        });
        btn.disabled = false;
        btn.innerHTML = originalHTML;
    }
}

// ==========================================
// GRAFIK TREN STRES ADMIN
// ==========================================
let stressChartInstance = null;

async function initStressTrendChart(period = 'this_month') {
    const canvas = document.getElementById('stressTrendChart');
    const loadingIndicator = document.getElementById('chartLoadingIndicator');

    if (!canvas) return; // not on stress page

    loadingIndicator.style.display = 'block';
    canvas.style.display = 'none';

    try {
        const res = await fetch(`/admin/stress-trend?period=${period}`);
        const json = await res.json();

        if (json.status === 200 && json.data) {
            const data = json.data;
            const items = data.items || [];

            const labels = items.map(i => i.label);
            const modes = items.map(i => i.mode_stress);

            // Map stress mode to numeric value for charting
            // Rendah: 1, Sedang: 2, Tinggi: 3
            const numericData = modes.map(m => {
                if (m === 'Tinggi') return 3;
                if (m === 'Sedang') return 2;
                if (m === 'Rendah') return 1;
                return 0; // null/empty
            });

            const pointColors = modes.map(m => {
                if (m === 'Tinggi') return '#e63946'; // danger
                if (m === 'Sedang') return '#ffca28'; // warning
                if (m === 'Rendah') return '#2a9d8f'; // success
                return '#cbd5e1';
            });

            if (stressChartInstance) {
                stressChartInstance.destroy();
            }

            const ctx = canvas.getContext('2d');
            stressChartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Mode Stress Level',
                        data: numericData,
                        borderColor: '#6366f1',
                        backgroundColor: 'rgba(99, 102, 241, 0.1)',
                        borderWidth: 2,
                        pointBackgroundColor: pointColors,
                        pointRadius: 4,
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            min: 0,
                            max: 3.5,
                            ticks: {
                                stepSize: 1,
                                callback: function (value) {
                                    if (value === 3) return 'Tinggi';
                                    if (value === 2) return 'Sedang';
                                    if (value === 1) return 'Rendah';
                                    if (value === 0) return 'Data Kosong';
                                    return '';
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    const idx = context.dataIndex;
                                    return 'Stress Level: ' + modes[idx];
                                }
                            }
                        }
                    }
                }
            });

            loadingIndicator.style.display = 'none';
            canvas.style.display = 'block';
        }
    } catch (e) {
        console.error('Error fetching chart data:', e);
        loadingIndicator.innerHTML = '<span class="text-danger small fw-semibold">Gagal memuat data grafik.</span>';
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const trendSelect = document.getElementById('trendPeriodSelect');
    if (trendSelect) {
        trendSelect.addEventListener('change', function (e) {
            initStressTrendChart(e.target.value);
        });

        // init default
        initStressTrendChart(trendSelect.value);
    }
});
