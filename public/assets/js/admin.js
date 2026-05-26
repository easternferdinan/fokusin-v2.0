// ==========================================
// LOGIKA JAVASCRIPT KHUSUS DASHBOARD ADMIN
// ==========================================
let modalDetailInstance = null;
let namaMahasiswaAktif = ""; // Menyimpan nama mahasiswa yang sedang dilihat

document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi Modal secara aman jika elemennya ada di halaman
    const modalEl = document.getElementById('modalDetailMahasiswa');
    if (modalEl) {
        modalDetailInstance = new bootstrap.Modal(modalEl);
    }
});

// Fungsi untuk mengubah isi modal sesuai mahasiswa yang diklik
function lihatDetail(nama, username, status) {
    namaMahasiswaAktif = nama; // Simpan namanya untuk fitur intervensi nanti
    
    // 1. Ubah Teks di Dalam Modal
    document.getElementById('detailNama').innerText = nama;
    // document.getElementById('detailNamaGrafik').innerText = nama;
    document.getElementById('detailUsername').innerText = username;
    
    // 2. Siapkan Base URL untuk Foto Profil Dinamis
    let avatarUrl = `https://ui-avatars.com/api/?name=${encodeURIComponent(nama)}&color=fff&size=50`;
    
    // 3. Ubah Warna Badge & Warna Avatar berdasarkan Status Prediksi AI
    let badgeEl = document.getElementById('detailBadge');
    if (status === 'Tinggi') {
        avatarUrl += '&background=ff7675'; // Latar belakang merah untuk risiko tinggi
        badgeEl.className = 'badge bg-danger text-white';
    } else if (status === 'Sedang') {
        avatarUrl += '&background=fdcb6e'; // Latar belakang kuning untuk risiko sedang
        badgeEl.className = 'badge bg-warning text-dark';
    } else {
        avatarUrl += '&background=55efc4'; // Latar belakang hijau untuk risiko rendah
        badgeEl.className = 'badge bg-success text-white';
    }
    
    badgeEl.innerText = status;
    document.getElementById('detailAvatar').src = avatarUrl;

    // 4. Tampilkan Modal ke Layar
    if (modalDetailInstance) {
        modalDetailInstance.show();
    }
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
    barisTabel.forEach(function(baris) {
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
