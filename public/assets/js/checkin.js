// LOGIC DAILY CHECK-IN + AJAX CI4

// 1. Saat halaman dimuat, cek apakah hari ini sudah check-in
window.addEventListener("DOMContentLoaded", function() { 
    const today = new Date().toISOString().split('T')[0]; 
    const dateInput = document.getElementById('checkinDate');
    if(dateInput) dateInput.value = today; 
    
    // Jika sudah checkin hari ini, sembunyikan tanda seru (!)
    if(localStorage.getItem('checkin_' + today)) { 
        const badge = document.getElementById('checkinBadge');
        if(badge) badge.style.display = 'none'; 
    } 
});

// 2. Fungsi memproses form checkin
function submitCheckin(event) { 
    event.preventDefault(); 
    
    // Validasi khusus untuk radio button kualitas tidur
    // (Note: Name di HTML diubah jadi sleep_quality)
    const selectedQuality = document.querySelector('input[name="sleep_quality"]:checked'); 
    if(!selectedQuality) { 
        Swal.fire({ 
            title: 'Pilih Dulu', 
            text: 'Klik ikon wajah kualitas tidur yang sesuai.', 
            icon: 'warning', 
            confirmButtonColor: '#74b9ff', 
            customClass:{popup:'rounded-4'} 
        }); 
        return false; 
    } 
    
    // Ambil tanggal hari ini
    const today = document.getElementById('checkinDate') ? document.getElementById('checkinDate').value : new Date().toISOString().split('T')[0]; 
    
    // Tangkap SEMUA data form (Tidur, Self Esteem, Depression, Headache)
    const form = event.target;
    const formData = new FormData(form);

    // Kirim data ke Controller CodeIgniter 4 via AJAX (Fetch)
    fetch('/mahasiswa/saveCheckin', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json()) // Tunggu balasan dari CI4
    .then(data => {
        if(data.status === 'success') {
            // Jika CI4 membalas sukses
            suksesCheckin(today, selectedQuality.value, form);
        } else {
            // Jika ada error dari CI4
            Swal.fire('Oops!', data.message || 'Gagal menyimpan check-in', 'error');
        }
    })
    .catch(error => {
        console.error("Error saat mengirim data:", error);
        
        // --- FALLBACK UNTUK PROTOTYPE ---
        // Karena saat ini kamu belum membuat Controllernya (saveCheckin), 
        // fetch akan error (404). Agar UI tetap berjalan saat prototype, kita panggil fungsi suksesnya di sini sementara:
        suksesCheckin(today, selectedQuality.value, form);
    });
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
            if(badge) badge.style.display = 'none'; 
            
            // Tutup Modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalCheckin')); 
            if(modal) modal.hide(); 
            
            // Kosongkan form untuk besok
            form.reset();
        } 
    });
}