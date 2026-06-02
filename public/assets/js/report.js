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

// =========================================================
// LOGIC CHART TREN STRES
// =========================================================

let studentStressChartInstance = null;

async function loadStressTrend(period = 'harian') {
    try {
        const response = await fetch(`/mahasiswa/stress-trend?period=${period}`);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        
        if (!data || !data.labels || !data.values || data.labels.length === 0) {
            showChartPlaceholder(true);
            if (studentStressChartInstance) {
                studentStressChartInstance.destroy();
                studentStressChartInstance = null;
            }
            return;
        }

        showChartPlaceholder(false);
        renderStressChart(data.labels, data.values);

    } catch (error) {
        console.error("Gagal mengambil data tren stres:", error);
        showChartPlaceholder(true);
        if (studentStressChartInstance) {
            studentStressChartInstance.destroy();
            studentStressChartInstance = null;
        }
    }
}

function showChartPlaceholder(show) {
    const placeholder = document.getElementById('chartPlaceholder');
    const canvas = document.getElementById('studentStressChart');
    if (show) {
        if(placeholder) placeholder.style.display = 'block';
        if(canvas) canvas.style.display = 'none';
    } else {
        if(placeholder) placeholder.style.display = 'none';
        if(canvas) canvas.style.display = 'block';
    }
}

function renderStressChart(labels, data) {
    const ctx = document.getElementById('studentStressChart').getContext('2d');
    
    if (studentStressChartInstance) {
        studentStressChartInstance.data.labels = labels;
        studentStressChartInstance.data.datasets[0].data = data;
        studentStressChartInstance.update();
        return;
    }

    // Buat gradien untuk background chart
    let gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(108, 92, 231, 0.4)');
    gradient.addColorStop(1, 'rgba(108, 92, 231, 0.0)');

    studentStressChartInstance = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Tingkat Stres',
                data: data,
                borderColor: '#6c5ce7',
                backgroundColor: gradient,
                borderWidth: 2,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#6c5ce7',
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.7)',
                    padding: 10,
                    cornerRadius: 8,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            let val = context.parsed.y;
                            if (val === 1) return 'Tingkat Stres: Rendah';
                            if (val === 2) return 'Tingkat Stres: Sedang';
                            if (val === 3) return 'Tingkat Stres: Tinggi';
                            return 'Tingkat Stres: ' + val;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 3, // Misal maksimal tingkat stres 3
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)',
                        borderDash: [5, 5]
                    },
                    ticks: {
                        stepSize: 1,
                        callback: function(value, index, ticks) {
                            if (value === 1) return 'Rendah';
                            if (value === 2) return 'Sedang';
                            if (value === 3) return 'Tinggi';
                            return '';
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

// Inisialisasi saat halaman dimuat
document.addEventListener('DOMContentLoaded', () => {
    // Jalankan pertama kali dengan default "harian"
    loadStressTrend('harian');

    // Listener untuk dropdown filter
    const filterSelect = document.getElementById('filterTrenStres');
    if (filterSelect) {
        filterSelect.addEventListener('change', (e) => {
            loadStressTrend(e.target.value);
        });
    }
});