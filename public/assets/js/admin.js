// ==========================================
// LOGIKA JAVASCRIPT KHUSUS DASHBOARD ADMIN
// ==========================================
let modalDetailInstance = null;
let namaMahasiswaAktif = "";
let userIdAktif = null;
let halamanAktif = 1;
let totalHalaman = 1;
const ukuranHalaman = 5;

document.addEventListener('DOMContentLoaded', function () {
    // Inisialisasi Modal secara aman jika elemennya ada di halaman
    const modalEl = document.getElementById('modalDetailMahasiswa');
    if (modalEl) {
        modalDetailInstance = new bootstrap.Modal(modalEl);
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
    } else {
        avatarUrl += '&background=55efc4';
        badgeEl.className = 'badge bg-success text-white';
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

// Fungsi untuk memproses penambahan user baru
function simpanUserBaru() {
    // 1. Ambil data dari form
    const nama = document.getElementById('inputNama').value.trim();
    const username = document.getElementById('inputUsername').value.trim();
    const role = document.getElementById('inputRole').value;

    // 2. Validasi sederhana (jangan biarkan kosong)
    if (!nama || !username) {
        Swal.fire({
            icon: 'warning',
            title: 'Oops...',
            text: 'Nama dan Username wajib diisi!',
            confirmButtonColor: '#6366f1',
            customClass: { popup: 'rounded-4' }
        });
        return;
    }

    // 3. Sembunyikan Modal Tambah User
    const modalEl = document.getElementById('modalTambahUser');
    const modalInstance = bootstrap.Modal.getInstance(modalEl);
    if (modalInstance) {
        modalInstance.hide();
    }

    // 4. Tampilkan Notifikasi Sukses
    Swal.fire({
        title: 'Berhasil!',
        text: `Pengguna baru "${nama}" dengan role ${role.toUpperCase()} berhasil ditambahkan ke sistem.`,
        icon: 'success',
        confirmButtonColor: '#6366f1',
        customClass: { popup: 'rounded-4' }
    }).then(() => {
        // Reset (kosongkan) isian form setelah notifikasi ditutup
        document.getElementById('formTambahUser').reset();
    });
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

function simpanConfig() {
    // Logika untuk menangkap nilai checkbox dan input
    // Kita gunakan SweetAlert agar terlihat profesional
    Swal.fire({
        title: 'Simpan Konfigurasi?',
        text: "Pengaturan notifikasi baru akan segera diterapkan ke sistem.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Simpan',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Di sini nanti kamu tambahkan AJAX untuk kirim data ke controller
            Swal.fire('Tersimpan!', 'Konfigurasi berhasil diperbarui.', 'success');
        }
    });
}
