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
    // Modal for creating task
    document.getElementById('modalTugasTitle').innerText = 'Tambah Tugas Baru';
    document.getElementById('btnSimpanTugas').innerHTML = '<i class="fas fa-save me-2"></i>Simpan';
    document.getElementById('formTugas').reset(); // Kosongkan form
    document.getElementById('formTugas').action = '/mahasiswa/simpanTugas';
    document.getElementById('formTugas').method = 'POST';
    if(modalTugasInstance) modalTugasInstance.show(); 
}

function editTugas(id, judul, kategori, prioritas, deadline, target, deskripsi) {
    document.getElementById('modalTugasTitle').innerText = 'Edit Detail Tugas';
    document.getElementById('btnSimpanTugas').innerHTML = '<i class="fas fa-check me-2"></i>Update Tugas';
    document.getElementById('formTugas').action = '/mahasiswa/updateTugas/'+id;
    document.getElementById('formTugas').method = 'POST';
    
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
    const form = document.getElementById('formTugas');
    const judul = document.getElementById('inputJudul').value.trim();
    const deadline = document.getElementById('inputDeadline').value;
    const targetDuration = document.getElementById('inputTarget').value;
    
    
    if(!judul) {
        Swal.fire({icon: 'warning', title: 'Oops!', text: 'Judul tugas tidak boleh kosong!', customClass: { popup: 'rounded-4' }});
        return;
    }

    if (!deadline) {
        Swal.fire({icon: 'warning', title: 'Oops!', text: 'Deadline tugas tidak boleh kosong!', customClass: { popup: 'rounded-4' }});
        return;
    }

    if (!targetDuration) {
        Swal.fire({icon: 'warning', title: 'Oops!', text: 'Target durasi tugas tidak boleh kosong!', customClass: { popup: 'rounded-4' }});
        return;
    }

    form.submit();
}

function hapusTugas(event, id) { 
    event.preventDefault();
    
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
            document.getElementById('form-hapus-tugas-' + id).submit();
        }
    }); 
}

function toggleComplete(id, name) { 
    const el = document.getElementById(id); 
    const isDone = el.classList.toggle('completed'); 
    const icon = el.querySelector('.custom-check'); 
    icon.classList.toggle('checked', isDone); 
    icon.querySelector('i').classList.toggle('d-none', !isDone);

    console.log()

    fetch('/mahasiswa/toggleCompleteTugas/' + id, {
        method: 'POST',
        body: JSON.stringify({ completed: isDone }),
        headers: {
            'Content-Type': 'application/json'
        }
    }).then(response => {
        if (response.ok) {
            if(isDone) Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2000 }).fire({ icon: 'success', title: `"${name}" selesai ✅` }); 
        } else {
            Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2000 }).fire({ icon: 'error', title: `"${name}" gagal diselesaikan` });
        }
    }).catch(error => {
        Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2000 }).fire({ icon: 'error', title: `Terjadi Kesalahan` });
    });
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
            // Mengarahkan ke halaman pomodoro dan membawa nama tugas via parameter URL
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
            // Mengarahkan ke halaman logout CI4
            window.location.href = '/auth/logout'; 
        }
    }); 
}

// ==========================================
// SEARCH TUGAS
// ==========================================
function cariTugas() {
    const keyword = document.getElementById('searchTugas').value.toLowerCase();
    const tasks = document.querySelectorAll('.task-item');

    tasks.forEach(function (task) {
        const title = task.querySelector('.task-title')?.textContent.toLowerCase() || '';
        const category = task.querySelector('.badge')?.textContent.toLowerCase() || '';
        const description = task.querySelector('p.text-muted')?.textContent.toLowerCase() || '';

        if (title.includes(keyword) || category.includes(keyword) || description.includes(keyword)) {
            task.style.display = '';
        } else {
            task.style.display = 'none';
        }
    });
}

// function submitCheckin(event) { 
//     event.preventDefault(); 
    
//     // Contoh cara mengambil data form di CI4 menggunakan FormData
//     const formData = new FormData(event.target);
//     const data = {
//         sleep: formData.get('sleep_quality'),
//         esteem: formData.get('self_esteem'),
//         depression: formData.get('depression'),
//         headache: formData.get('headache')
//     };

//     console.log("Mengirim data check-in ke AI:", data);

//     Swal.fire({ 
//         title: 'Check-in Berhasil! 🌙', 
//         text: 'Data kesehatanmu sudah tercatat dalam sistem AI.', 
//         icon: 'success', 
//         confirmButtonColor: '#6c5ce7' 
//     }).then(() => {
//         const modal = bootstrap.Modal.getInstance(document.getElementById('modalCheckin'));
//         modal.hide();
//     });
// }