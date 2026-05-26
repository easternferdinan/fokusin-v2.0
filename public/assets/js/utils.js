const titles = {
    'section-dashboard': ['Dashboard', 'Ringkasan aktivitas & analisis AI'],
    'section-tugas': ['Daftar Tugas', 'Kelola semua tugas kuliah dan proyekmu'],
    'section-pomodoro': ['Timer Pomodoro', 'Tetap fokus, Raih mimpimu!'],
    'section-report': ['Report AI', 'Hasil pemrosesan data oleh microservice'],
    'section-pengaturan': ['Pengaturan', 'Update informasi profile kamu']
};

function toggleSidebar() { 
    document.getElementById('sidebar').classList.toggle('show'); 
    document.getElementById('mobileOverlay').classList.toggle('show'); 
}

// ==========================================
// CRUD MODAL TUGAS (UPDATED)
// ==========================================
let modalTugasInstance = null;

// Inisialisasi aman: Hanya buat instance modal jika elemennya ada di halaman tersebut
document.addEventListener('DOMContentLoaded', function() {
    const modalEl = document.getElementById('modalTugas');
    if (modalEl) {
        modalTugasInstance = new bootstrap.Modal(modalEl);
    }
});

function openTugasModal() { 
    document.getElementById('modalTugasTitle').innerText = 'Tambah Tugas Baru';
    document.getElementById('btnSimpanTugas').innerHTML = '<i class="fas fa-save me-2"></i>Simpan';
    document.getElementById('formTugas').reset(); // Kosongkan form
    if(modalTugasInstance) modalTugasInstance.show(); 
}

function editTugas(id, judul, kategori, prioritas, deadline, target, deskripsi) {
    document.getElementById('modalTugasTitle').innerText = 'Edit Detail Tugas';
    document.getElementById('btnSimpanTugas').innerHTML = '<i class="fas fa-check me-2"></i>Update Tugas';
    
    // Isi form otomatis
    document.getElementById('inputJudul').value = judul;
    document.getElementById('inputKategori').value = kategori;
    document.getElementById('inputPrioritas').value = prioritas;
    document.getElementById('inputDeadline').value = deadline;
    document.getElementById('inputTarget').value = target;
    document.getElementById('inputDeskripsi').value = deskripsi;

    if(modalTugasInstance) modalTugasInstance.show();
}

function simpanTugas() {
    const judul = document.getElementById('inputJudul').value.trim();
    const isEdit = document.getElementById('modalTugasTitle').innerText.includes('Edit');
    
    if(!judul) {
        Swal.fire({icon: 'warning', title: 'Oops!', text: 'Judul tugas tidak boleh kosong!', customClass: { popup: 'rounded-4' }});
        return;
    }

    Swal.fire({
        title: isEdit ? 'Memperbarui Tugas...' : 'Menyimpan Tugas...',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); },
        customClass: { popup: 'rounded-4' }
    });

    setTimeout(() => {
        if(modalTugasInstance) modalTugasInstance.hide();
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: isEdit ? 'Tugas berhasil diperbarui.' : 'Tugas baru berhasil ditambahkan.',
            confirmButtonColor: '#6366f1',
            customClass: { popup: 'rounded-4' }
        });
    }, 1000);
}

function hapusTugas(id) { 
    Swal.fire({ 
        title: 'Hapus Tugas?', 
        text: "Tugas ini akan dihapus permanen dari daftarmu.", 
        icon: 'warning', 
        showCancelButton: true, 
        confirmButtonColor: '#d33', 
        cancelButtonColor: '#b2bec3', 
        confirmButtonText: '<i class="fas fa-trash-alt me-2"></i>Ya, Hapus!', 
        cancelButtonText: 'Batal', 
        customClass: { popup: 'rounded-4' } 
    }).then((result) => {
        if (result.isConfirmed) {
            // Hapus elemen dari UI
            const taskEl = document.getElementById('task-' + id);
            if (taskEl) taskEl.remove();
            
            Swal.fire({icon: 'success', title: 'Terhapus!', text: 'Tugas berhasil dihapus.', confirmButtonColor: '#6366f1', customClass: { popup: 'rounded-4' }});
        }
    }); 
}

function toggleComplete(id, name) { 
    const el = document.getElementById(id); 
    const isDone = el.classList.toggle('completed'); 
    const icon = el.querySelector('.custom-check'); 
    icon.classList.toggle('checked', isDone); 
    icon.querySelector('i').classList.toggle('d-none', !isDone); 
    if(isDone) Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2000 }).fire({ icon: 'success', title: `"${name}" selesai ✅` }); 
}

// ==========================================
// INTEGRASI TIMER & TUGAS
// ==========================================
function goToPomodoro(taskName) {
    Swal.fire({ 
        title: 'Mulai Fokus?', 
        html: `Ingin memulai sesi Pomodoro untuk:<br><strong>${taskName}</strong>`, 
        icon: 'info', 
        showCancelButton: true, 
        confirmButtonColor: '#74b9ff', 
        cancelButtonColor: '#dfe6e9', 
        confirmButtonText: 'Gas!', 
        cancelButtonText: 'Batal', 
        customClass: { popup: 'rounded-4', confirmButton: 'rounded-3 px-4', cancelButton: 'rounded-3 px-4' } 
    }).then((result) => {
        if (result.isConfirmed) { 
            window.location.href = '/mahasiswa/pomodoro?task=' + encodeURIComponent(taskName);
        }
    });
}

function pickTaskFromOffcanvas(taskName) { 
    document.getElementById('taskInput').value = taskName; 
    const offcanvasEl = document.getElementById('offcanvasTaskList'); 
    const offcanvasInstance = bootstrap.Offcanvas.getInstance(offcanvasEl); 
    if(offcanvasInstance) offcanvasInstance.hide(); 
    Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2000 }).fire({ icon: 'info', title: `Tugas: ${taskName}` }); 
}

// ==========================================
// PROFILE & LOGOUT
// ==========================================
function simpanProfileAI() { 
    Swal.fire({ title: 'Tersimpan & Diproses! ✅', html: 'Data profile dan parameter AI diperbarui.<br><small class="text-muted">Model Random Forest akan menyesuaikan prediksi.</small>', icon: 'success', confirmButtonColor: '#00b894', confirmButtonText: 'Mengerti', customClass: { popup: 'rounded-4', confirmButton: 'rounded-3 px-4' } }); 
}

function confirmLogout(event) { 
    if(event) event.preventDefault(); 
    Swal.fire({ 
        title: 'Yakin mau Logout?', 
        text: 'Sesi fokus kamu akan terputus.', 
        icon: 'question', 
        showCancelButton: true, 
        confirmButtonColor: '#ff7675', 
        cancelButtonColor: '#dfe6e9', 
        confirmButtonText: 'Ya, Logout', 
        cancelButtonText: 'Batal', 
        customClass: { popup: 'rounded-4', confirmButton: 'rounded-3', cancelButton: 'rounded-3' } 
    }).then((r) => { 
        if(r.isConfirmed) {
            window.location.href = '/auth/logout'; 
        }
    }); 
}