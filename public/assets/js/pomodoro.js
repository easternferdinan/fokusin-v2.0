// ==========================================
// LOGIKA UTAMA TIMER POMODORO
// ==========================================
let timerInterval;
let totalSeconds = 30 * 60;
let remainingSeconds = totalSeconds;
let isRunning = false;
let isPaused = false;
let isWorkMode = true;
let workTime = 30;
let restTime = 5;
let cycles = 0;
const circumference = 2 * Math.PI * 90;

function updateDisplay() {
    const m = Math.floor(remainingSeconds / 60);
    const s = remainingSeconds % 60;
    document.getElementById('timeDisplay').innerText = `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
    const progress = (totalSeconds - remainingSeconds) / totalSeconds;
    document.getElementById('progressBar').style.strokeDashoffset = circumference * (1 - progress);
    const pb = document.getElementById('progressBar');
    const lbl = document.getElementById('timerLabel');
    const badge = document.getElementById('modeBadge');

    if (isWorkMode) {
        lbl.innerText = "Fokus";
        pb.classList.remove('rest-mode');
        badge.innerHTML = '<i class="fas fa-brain me-1"></i> FOCUS MODE';
        badge.style.backgroundColor = '#74b9ff';
        badge.style.color = '#fff';
    } else {
        lbl.innerText = "Istirahat";
        pb.classList.add('rest-mode');
        badge.innerHTML = '<i class="fas fa-mug-hot me-1"></i> BREAK MODE';
        badge.style.backgroundColor = '#55efc4';
        badge.style.color = '#006644';
    }
}

function startTimer() {
    // MODIFIKASI: Input tidak lagi wajib diisi (Bebas)
    const taskInput = document.getElementById('taskInput');
    if (!taskInput.value.trim()) {
        taskInput.value = "Sesi Fokus Mandiri"; // Beri default value jika kosong
    }

    if (remainingSeconds === 0) {
        totalSeconds = isWorkMode ? workTime * 60 : restTime * 60;
        remainingSeconds = totalSeconds;
    }

    isRunning = true;
    document.getElementById('btnStart').classList.add('d-none');
    document.getElementById('btnPause').classList.remove('d-none');
    document.getElementById('btnStop').classList.remove('d-none');
    document.getElementById('btnSkip').classList.remove('d-none');
    document.getElementById('taskInput').disabled = true;

    // TODO: Refactor this spaghetti.
    const guestIndicator = document.getElementById('guestSisaSesi');
    if (!guestIndicator && !isPaused) {
        fetch('/mahasiswa/createPomodoro', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                title: taskInput.value,
                status: 'active',
                duration: workTime,
                break_duration: restTime
            })
        }).then(response => {
            if (!response.ok) {
                Swal.fire({
                    title: 'Gagal!',
                    text: 'Gagal menyimpan sesi pomodoro.',
                    icon: 'error',
                    confirmButtonColor: '#ff7675',
                    customClass: { popup: 'rounded-4' }
                });
            } else {
                response.json().then(data => {
                    console.log(data);
                    localStorage.setItem('pomodoro_id', data.pomodoro_id);
                }).catch(error => {
                    console.error(error);
                    Swal.fire({
                        title: 'Error!',
                        text: 'Terjadi error saat mengakses id sesi pomodoro.',
                        icon: 'error',
                        confirmButtonColor: '#ff7675',
                        customClass: { popup: 'rounded-4' }
                    });
                })
            }
        }).catch(error => {
            console.error(error);
            Swal.fire({
                title: 'Error!',
                text: 'Terjadi error saat menyimpan sesi pomodoro.',
                icon: 'error',
                confirmButtonColor: '#ff7675',
                customClass: { popup: 'rounded-4' }
            });
        });
    } else if (!guestIndicator && isPaused) {
        const pomodoroId = localStorage.getItem('pomodoro_id');

        fetch(`/mahasiswa/resumePomodoro/${pomodoroId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
        }).then(response => {
            if (!response.ok) {
                Swal.fire({
                    title: 'Gagal!',
                    text: 'Gagal melanjutkan sesi pomodoro.',
                    icon: 'error',
                    confirmButtonColor: '#ff7675',
                    customClass: { popup: 'rounded-4' }
                });
            } else {
                isPaused = false;
            }
        }).catch(error => {
            console.error(error);
            Swal.fire({
                title: 'Error!',
                text: 'Terjadi error saat menyimpan sesi pomodoro.',
                icon: 'error',
                confirmButtonColor: '#ff7675',
                customClass: { popup: 'rounded-4' }
            });
        });
    } else {
        Swal.fire({
            title: 'Peringatan',
            text: 'Pomodoro dimulai sebagai guest. Daftar untuk sesi tak terbatas!',
            icon: 'warning',
            confirmButtonColor: '#ff7675',
            customClass: { popup: 'rounded-4' }
        });
    }

    timerInterval = setInterval(() => {
        remainingSeconds--;
        updateDisplay();

        if (remainingSeconds === 0) {
            clearInterval(timerInterval);
            isRunning = false;

            const pomodoroId = localStorage.getItem('pomodoro_id');
            const guestIndicator = document.getElementById('guestSisaSesi');
            if (!guestIndicator && pomodoroId) {
                fetch(`/mahasiswa/completePomodoro/${pomodoroId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                }).then(response => {
                    if (!response.ok) {
                        Swal.fire({
                            title: 'Gagal!',
                            text: 'Gagal menyelesaikan sesi pomodoro.',
                            icon: 'error',
                            confirmButtonColor: '#ff7675',
                            customClass: { popup: 'rounded-4' }
                        });
                    } else {
                        isPaused = false;
                    }
                }).catch(error => {
                    console.error(error);
                    Swal.fire({
                        title: 'Error!',
                        text: 'Terjadi error saat menyimpan sesi pomodoro.',
                        icon: 'error',
                        confirmButtonColor: '#ff7675',
                        customClass: { popup: 'rounded-4' }
                    });
                });
            }

            if (isWorkMode) {
                cycles++;
                document.getElementById('cycleCount').innerText = cycles;

                // --- LOGIKA GUEST TRIAL (Dijalankan tiap 1 siklus kerja selesai) ---
                const guestIndicator = document.getElementById('guestSisaSesi');
                if (guestIndicator) {
                    let count = parseInt(localStorage.getItem('guestPomodoroCount') || 0);
                    count++;
                    localStorage.setItem('guestPomodoroCount', count);

                    let sisa = 3 - count;
                    guestIndicator.innerText = sisa > 0 ? sisa : 0;

                    if (sisa <= 0) {
                        kunciTimerGuest();
                        return; // Hentikan eksekusi, biarkan popup muncul
                    }
                }
                // -------------------------------------------------------------------

                switchMode(false);
            } else {
                switchMode(true);
            }
        }
    }, 1000);
}

function pauseTimer() {
    clearInterval(timerInterval);
    isRunning = false;
    document.getElementById('btnStart').classList.remove('d-none');
    document.getElementById('btnStart').innerHTML = '<i class="fas fa-play me-2"></i>Lanjut';
    document.getElementById('btnPause').classList.add('d-none');

    const guestIndicator = document.getElementById('guestSisaSesi');
    const pomodoroId = localStorage.getItem('pomodoro_id');
    if (!guestIndicator && pomodoroId) {
        isPaused = true;
        fetch(`/mahasiswa/pausePomodoro/${pomodoroId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
        }).then(response => {
            if (!response.ok) {
                Swal.fire({
                    title: 'Gagal!',
                    text: 'Gagal menyimpan sesi pomodoro.',
                    icon: 'error',
                    confirmButtonColor: '#ff7675',
                    customClass: { popup: 'rounded-4' }
                });
            }
        }).catch(error => {
            console.error(error);
            Swal.fire({
                title: 'Error!',
                text: 'Terjadi error saat menyimpan sesi pomodoro.',
                icon: 'error',
                confirmButtonColor: '#ff7675',
                customClass: { popup: 'rounded-4' }
            });
        });
    }
}

function stopTimer() {
    Swal.fire({
        title: 'Hentikan Sesi?',
        text: 'Progress sesi ini akan direset.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#ff7675',
        cancelButtonColor: '#dfe6e9',
        confirmButtonText: 'Ya, Hentikan',
        cancelButtonText: 'Batal',
        customClass: { popup: 'rounded-4', confirmButton: 'rounded-3 px-4', cancelButton: 'rounded-3 px-4' }
    }).then((result) => {
        if (result.isConfirmed) {

            const guestIndicator = document.getElementById('guestSisaSesi');
            const pomodoroId = localStorage.getItem('pomodoro_id');
            if (!guestIndicator && pomodoroId) {
                fetch(`/mahasiswa/completePomodoro/${pomodoroId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                }).then(response => {
                    if (!response.ok) {
                        Swal.fire({
                            title: 'Gagal!',
                            text: 'Gagal menyimpan sesi pomodoro.',
                            icon: 'error',
                            confirmButtonColor: '#ff7675',
                            customClass: { popup: 'rounded-4' }
                        });
                    }
                }).catch(error => {
                    console.error(error);
                    Swal.fire({
                        title: 'Error!',
                        text: 'Terjadi error saat menyimpan sesi pomodoro.',
                        icon: 'error',
                        confirmButtonColor: '#ff7675',
                        customClass: { popup: 'rounded-4' }
                    });
                });
            }

            clearInterval(timerInterval);
            isRunning = false;
            switchMode(true);
            document.getElementById('taskInput').disabled = false;
            // document.getElementById('taskInput').value = ''; // Opsional: Hapus komentar ini jika ingin mereset text input
            resetControlButtons();
            updateDisplay();
        }
    });
}

function skipTimer() {
    clearInterval(timerInterval);
    isRunning = false;
    switchMode(!isWorkMode);
}

function switchMode(toWork) {
    isWorkMode = toWork;
    totalSeconds = toWork ? workTime * 60 : restTime * 60;
    remainingSeconds = totalSeconds;
    updateDisplay();

    if (!toWork) {
        Swal.fire({
            title: 'Waktu Istirahat! ☕',
            text: 'Berdiri, minum air, atau stretching sebentar ya.',
            icon: 'success',
            allowOutsideClick: false,
            confirmButtonColor: '#00b894',
            confirmButtonText: 'Oke, Istirahat Dulu',
            customClass: { popup: 'rounded-4', confirmButton: 'rounded-3' }
        });
    } else {
        Swal.fire({
            title: 'Istirahat Selesai! 💪',
            text: 'Siap lanjut fokus?',
            icon: 'info',
            confirmButtonColor: '#74b9ff',
            confirmButtonText: 'Siap!',
            customClass: { popup: 'rounded-4', confirmButton: 'rounded-3' }
        });
    }

    resetControlButtons();
    if (isRunning) startTimer();
}

function resetControlButtons() {
    document.getElementById('btnStart').classList.remove('d-none');
    document.getElementById('btnStart').innerHTML = '<i class="fas fa-play me-2"></i>Mulai';
    document.getElementById('btnPause').classList.add('d-none');
    document.getElementById('btnStop').classList.add('d-none');
    document.getElementById('btnSkip').classList.add('d-none');
}

function adjustTime(type, amount) {
    if (isRunning) {
        Swal.fire({ title: 'Tunggu Dulu ⏳', text: 'Timer sedang berjalan.', icon: 'warning', confirmButtonColor: '#ff7675', customClass: { popup: 'rounded-4' } });
        return;
    }
    if (!isWorkMode && type === 'rest') {
        Swal.fire({ title: 'Tunggu Dulu ⏳', text: 'Sedang istirahat.', icon: 'info', confirmButtonColor: '#74b9ff', customClass: { popup: 'rounded-4' } });
        return;
    }

    if (type === 'work') {
        workTime = Math.max(5, Math.min(120, workTime + amount));
        document.getElementById('workTimeDisplay').innerText = workTime;
        if (isWorkMode) { totalSeconds = workTime * 60; remainingSeconds = totalSeconds; }
    } else {
        restTime = Math.max(1, Math.min(30, restTime + amount));
        document.getElementById('restTimeDisplay').innerText = restTime;
        if (!isWorkMode) { totalSeconds = restTime * 60; remainingSeconds = totalSeconds; }
    }
    updateDisplay();
}

// ==========================================
// FUNGSI KHUSUS GUEST (TRIAL LOCK)
// ==========================================
function kunciTimerGuest() {
    // 1. Reset tampilan timer dan matikan semua fungsi
    switchMode(true);
    document.getElementById('taskInput').disabled = true;

    // 2. Kunci Tombol Start secara visual
    const btnStart = document.getElementById('btnStart');
    if (btnStart) {
        btnStart.disabled = true;
        btnStart.classList.replace('btn-primary', 'btn-secondary');
        btnStart.innerHTML = '<i class="fas fa-lock me-2"></i>Terkunci';
    }

    // 3. Tampilkan Pop-Up Rayuan Mendaftar
    Swal.fire({
        icon: 'success',
        title: 'Fokus yang Luar Biasa! 🎉',
        text: 'Kamu telah menyelesaikan 3 sesi uji coba gratis. Untuk memutar timer tanpa batas, menyimpan riwayat belajarmu, dan melihat Prediksi AI, yuk buat akun gratismu sekarang!',
        confirmButtonText: 'Daftar & Masuk',
        showCancelButton: true,
        cancelButtonText: 'Nanti Saja',
        allowOutsideClick: false,
        confirmButtonColor: '#6366f1'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '/auth/login';
        }
    });
}

// ==========================================
// INISIALISASI SAAT HALAMAN DIBUKA
// ==========================================
document.addEventListener("DOMContentLoaded", function () {
    // 1. Cek Mode Guest
    const guestIndicator = document.getElementById('guestSisaSesi');
    if (guestIndicator) {
        let count = parseInt(localStorage.getItem('guestPomodoroCount') || 0);
        let sisa = 3 - count;

        if (sisa <= 0) {
            guestIndicator.innerText = "0";
            kunciTimerGuest();
        } else {
            guestIndicator.innerText = sisa;
        }
    }

    // 2. Cek Parameter URL (Untuk pengisian tugas dari halaman lain)
    const urlParams = new URLSearchParams(window.location.search);
    const taskFromUrl = urlParams.get('task');
    if (taskFromUrl) {
        const taskInput = document.getElementById('taskInput');
        if (taskInput) {
            taskInput.value = taskFromUrl;
        }
    }
});

// Jalankan setelan layar pertama kali
updateDisplay();