const titles = {
    'section-dashboard': ['Dashboard 🚀', 'Ringkasan aktivitas & analisis AI'],
    'section-tugas': ['Daftar Tugas 📝', 'Kelola semua tugas kuliah dan proyekmu'],
    'section-pomodoro': ['Timer Pomodoro 🍅', 'Tetap fokus, Raih mimpimu!'],
    'section-report': ['Report AI 🧠', 'Hasil pemrosesan data oleh microservice'],
    'section-pengaturan': ['Pengaturan ⚙️', 'Update informasi profile kamu']
};

// function showSection(event, sectionId) {
//     event.preventDefault();
//     document.querySelectorAll('.content-section').forEach(el => el.classList.remove('active'));
//     document.getElementById(sectionId).classList.add('active');
//     document.querySelectorAll('.sidebar .menu-link').forEach(el => el.classList.remove('active'));
//     event.currentTarget.classList.add('active');
//     if(titles[sectionId]) { 
//         document.getElementById('pageTitle').innerText = titles[sectionId][0]; 
//         document.getElementById('pageSubtitle').innerText = titles[sectionId][1]; 
//     }
//     if(window.innerWidth < 768) toggleSidebar();
// }

function toggleSidebar() { 
    document.getElementById('sidebar').classList.toggle('show'); 
    document.getElementById('mobileOverlay').classList.toggle('show'); 
}

// CRUD Modal Tugas
var modalTugasInstance = new bootstrap.Modal(document.getElementById('modalTugas'));
function openTugasModal(judul = null) { 
    document.getElementById('modalTugasLabel').innerText = judul ? 'Edit Tugas' : 'Tambah Tugas Baru'; 
    document.getElementById('inputJudul').value = judul || ''; 
    modalTugasInstance.show(); 
}
function simpanTugas() { 
    modalTugasInstance.hide(); 
    setTimeout(() => Swal.fire({ title: 'Berhasil Disimpan! ✅', icon: 'success', confirmButtonColor: '#00b894', customClass: { popup: 'rounded-4', confirmButton: 'rounded-3' } }), 300); 
}
function hapusTugas(nama) { 
    Swal.fire({ title: 'Hapus Tugas?', html: `Hapus <strong>${nama}</strong>?`, icon: 'warning', showCancelButton: true, confirmButtonColor: '#ff7675', cancelButtonColor: '#dfe6e9', confirmButtonText: 'Hapus', cancelButtonText: 'Batal', customClass: { popup: 'rounded-4', confirmButton: 'rounded-3', cancelButton: 'rounded-3' } 
    }).then((r) => { if(r.isConfirmed) Swal.fire({title:'Dihapus!', icon:'success', customClass:{popup:'rounded-4'}}) }); 
}
function toggleComplete(id, name) { 
    const el = document.getElementById(id); 
    const isDone = el.classList.toggle('completed'); 
    const icon = el.querySelector('.custom-check'); 
    icon.classList.toggle('checked', isDone); 
    icon.querySelector('i').classList.toggle('d-none', !isDone); 
    if(isDone) Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2000 }).fire({ icon: 'success', title: `"${name}" selesai ✅` }); 
}

// Integrasi Timer & Tugas
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

// Profile & Logout
function simpanProfileAI() { 
    Swal.fire({ title: 'Tersimpan & Diproses! ✅', html: 'Data profile dan parameter AI diperbarui.<br><small class="text-muted">Model Random Forest akan menyesuaikan prediksi.</small>', icon: 'success', confirmButtonColor: '#00b894', confirmButtonText: 'Mengerti', customClass: { popup: 'rounded-4', confirmButton: 'rounded-3 px-4' } }); 
}
function confirmLogout(event) { 
    if(event) event.preventDefault(); 
    Swal.fire({ 
        title: 'Yakin mau Logout? 👋', 
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