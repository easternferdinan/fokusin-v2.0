// LOGIC DAILY CHECK-IN + AJAX CI4

// 1. Saat halaman dimuat, cek apakah hari ini sudah check-in
window.addEventListener("DOMContentLoaded", function () {
    const today = new Date().toISOString().split('T')[0];
    const dateInput = document.getElementById('checkinDate');
    if (dateInput) dateInput.value = today;

    // Safety net: jika FAB ada di DOM (server belum render-nya),
    // lakukan pengecekan ulang via AJAX untuk antisipasi data basi
    const fab = document.getElementById('fabCheckin');
    if (!fab) return;

    fetch('/mahasiswa/check-checkin')
        .then(res => res.json())
        .then(data => {
            if (data.checked_in) fab.style.display = 'none';
        })
        .catch(() => {
            if (localStorage.getItem('checkin_' + today)) {
                fab.style.display = 'none';
            }
        });
});

// 2. Fungsi memproses form checkin
function submitCheckin(event) {
    event.preventDefault();

    // Validasi khusus untuk radio button kualitas tidur
    // (Note: Name di HTML diubah jadi sleep_quality)
    const selectedQuality = document.querySelector('input[name="sleep_quality"]:checked');
    if (!selectedQuality) {
        Swal.fire({
            title: 'Pilih Dulu',
            text: 'Klik ikon wajah kualitas tidur yang sesuai.',
            icon: 'warning',
            confirmButtonColor: '#74b9ff',
            customClass: { popup: 'rounded-4' }
        });
        return false;
    }

    // Ambil tanggal hari ini
    const today = document.getElementById('checkinDate') ? document.getElementById('checkinDate').value : new Date().toISOString().split('T')[0];

    // Disable button & show spinner
    const btn = document.querySelector('#formCheckin button[type="submit"]');
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Mengirim...';
    }

    const form = event.target;

    form.submit();
}

// 3. Fungsi terpisah untuk UI setelah sukses (agar rapi)
function suksesCheckin(today, sleepValue, form) {
    Swal.fire({
        title: 'Check-in Berhasil! 🌙',
        html: `Data kesehatanmu (Tidur, Self Esteem, Depresi, Sakit Kepala) sudah dikirim ke AI.`,
        icon: 'success',
        confirmButtonColor: '#6c5ce7',
        confirmButtonText: 'Siap',
        customClass: { popup: 'rounded-4', confirmButton: 'rounded-3' }
    }).then((result) => {
        if (result.isConfirmed) {
            // Simpan tanda di browser
            localStorage.setItem('checkin_' + today, sleepValue);

            // Sembunyikan badge (!)
            const badge = document.getElementById('checkinBadge');
            if (badge) badge.style.display = 'none';

            // Tutup Modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalCheckin'));
            if (modal) modal.hide();

            // Kosongkan form untuk besok
            form.reset();
        }
    });
}