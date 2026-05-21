// =========================================================
// LOGIC KHUSUS HALAMAN REPORT AI
// =========================================================

function cekPrasyaratPrediksi(btnElement) {
    // 1. Ambil data prasyarat dari atribut tombol HTML
    const hasTasks = btnElement.getAttribute('data-hastasks') === 'true';
    const hasPomodoro = btnElement.getAttribute('data-haspomodoro') === 'true';

    // 2. Jika salah satu prasyarat belum terpenuhi, munculkan SweetAlert
    if (!hasTasks || !hasPomodoro) {
        Swal.fire({
            icon: 'warning',
            iconColor: '#ff7675',
            title: '<span style="font-weight: 700;">Prediksi belum bisa ditampilkan</span>',
            html: `
                <div class="text-start text-muted mt-2" style="font-size: 0.95rem;">
                    Lengkapi dulu data aktivitas berikut:
                    <ul class="mt-2 text-start" style="padding-left: 1.5rem;">
                        <li class="mb-1">Selesaikan minimal 1 tugas di menu Daftar Tugas.</li>
                        <li>Selesaikan minimal 1 siklus fokus di menu Timer Pomodoro.</li>
                    </ul>
                </div>
            `,
            confirmButtonText: 'Mengerti',
            confirmButtonColor: '#ff7675',
            customClass: {
                popup: 'rounded-4 py-4',
                confirmButton: 'rounded-pill px-5 py-2 fw-semibold shadow-sm'
            }
        });
    } else {
        // 3. Jika prasyarat terpenuhi, buka modal Form Prediksi AI
        const modalElement = document.getElementById('modalCheckin');
        if (modalElement) {
            const modalCheckin = new bootstrap.Modal(modalElement);
            modalCheckin.show();
        } else {
            console.error("Modal #modalCheckin tidak ditemukan di halaman ini!");
        }
    }
}

// =========================================================
// FUNGSI EXPORT TABEL KE CSV
// =========================================================

function exportTableToCSV(filename) {
    let csv = [];
    // Targetkan baris (tr) di dalam tabel modal
    let rows = document.querySelectorAll("#tabelRiwayatLengkap tr");
    
    for (let i = 0; i < rows.length; i++) {
        let row = [], cols = rows[i].querySelectorAll("td, th");
        
        for (let j = 0; j < cols.length; j++) {
            // Ambil teks murni (buang tag HTML), bersihkan enter/spasi lebih
            let data = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, "").trim();
            
            // Tambahkan kutip ganda ("") agar format CSV tidak berantakan jika ada tanda koma di teks
            data = data.replace(/"/g, '""');
            row.push('"' + data + '"');
        }
        
        // Gabungkan kolom dengan koma
        csv.push(row.join(","));
    }

    // Panggil fungsi download
    downloadCSV(csv.join("\n"), filename);
}

function downloadCSV(csv, filename) {
    let csvFile;
    let downloadLink;

    // Buat file Blob dari data CSV
    csvFile = new Blob([csv], {type: "text/csv"});

    // Buat link semu (invisible) untuk memaksa browser mengunduh file
    downloadLink = document.createElement("a");
    downloadLink.download = filename;
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = "none";
    
    // Tambahkan ke body, klik, lalu hapus lagi
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}