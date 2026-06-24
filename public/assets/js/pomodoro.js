let timerInterval;
let totalSeconds = 30 * 60;
let remainingSeconds = totalSeconds;
let isRunning = false;
let isPaused = false;
let isWorkMode = true;
let workTime = 30;
let restTime = 5;
let cycles = 0;
let pomodoroId = localStorage.getItem('pomodoro_id') || null;
const circumference = 2 * Math.PI * 90;

function playNotificationSound(isWork) {
    try {
        const file = isWork ? 'work-complete.mp3' : 'rest-complete.mp3';
        new Audio('/assets/audio/' + file).play().catch(() => {});
    } catch (e) {
        console.warn('Sound notification gagal:', e);
    }
}

// TODO: Change 'elapsed_time' in the db to store in seconds
// - That is a breaking change!
// - Ensure study load calculations and other things that depend on elapsed time are updated

function isLoggedIn() {
    return !document.getElementById('guestSisaSesi');
}

async function apiPost(url, body = null) {
    const options = {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
    };
    if (body) options.body = JSON.stringify(body);
    const response = await fetch(url, options);
    if (!response.ok) throw new Error('API request failed');
    return response.json();
}

function savePomodoroState() {
    if (pomodoroId) {
        localStorage.setItem('pomodoro_remaining_' + pomodoroId, remainingSeconds);
    }
}

function clearPomodoroState() {
    if (pomodoroId) {
        localStorage.removeItem('pomodoro_remaining_' + pomodoroId);
    }
    localStorage.removeItem('pomodoro_id');
    pomodoroId = null;
}

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

function tick() {
    remainingSeconds--;
    updateDisplay();

    if (remainingSeconds <= 0) {
        clearInterval(timerInterval);
        isRunning = false;
        handleTimerComplete();
    }
}

function handleTimerComplete() {
    playNotificationSound(isWorkMode);
    const guestIndicator = document.getElementById('guestSisaSesi');

    if (!guestIndicator && pomodoroId) {
        apiPost('/mahasiswa/completePomodoro/' + pomodoroId)
            .then(() => { isPaused = false; })
            .catch(err => {
                console.error(err);
                Swal.fire({
                    title: 'Gagal!',
                    text: 'Gagal menyelesaikan sesi pomodoro.',
                    icon: 'error',
                    confirmButtonColor: '#ff7675',
                    customClass: { popup: 'rounded-4' }
                });
            });
    }

    clearPomodoroState();

    if (isWorkMode) {
        cycles++;
        document.getElementById('cycleCount').innerText = cycles;

        if (guestIndicator) {
            let count = parseInt(localStorage.getItem('guestPomodoroCount') || 0);
            count++;
            localStorage.setItem('guestPomodoroCount', count);

            let sisa = 3 - count;
            guestIndicator.innerText = sisa > 0 ? sisa : 0;

            if (sisa <= 0) {
                kunciTimerGuest();
                return;
            }
        }

        switchMode(false);
    } else {
        switchMode(true);
    }
}

function startTimer() {
    const taskInput = document.getElementById('taskInput');
    if (!taskInput.value.trim()) {
        taskInput.value = "Sesi Fokus Mandiri";
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

    const guestIndicator = document.getElementById('guestSisaSesi');
    if (!guestIndicator) {
        if (isPaused && pomodoroId) {
            apiPost('/mahasiswa/resumePomodoro/' + pomodoroId)
                .then(() => {
                    isPaused = false;
                    timerInterval = setInterval(tick, 1000);
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire({
                        title: 'Gagal!',
                        text: 'Gagal melanjutkan sesi pomodoro.',
                        icon: 'error',
                        confirmButtonColor: '#ff7675',
                        customClass: { popup: 'rounded-4' }
                    });
                });
        } else if (!pomodoroId && isWorkMode) {
            apiPost('/mahasiswa/createPomodoro', {
                title: taskInput.value,
                status: 'active',
                duration: workTime,
                break_duration: restTime
            }).then(data => {
                pomodoroId = data.pomodoro_id;
                localStorage.setItem('pomodoro_id', pomodoroId);
            }).catch(err => {
                console.error(err);
                Swal.fire({
                    title: 'Gagal!',
                    text: 'Gagal menyimpan sesi pomodoro.',
                    icon: 'error',
                    confirmButtonColor: '#ff7675',
                    customClass: { popup: 'rounded-4' }
                });
            });
            timerInterval = setInterval(tick, 1000);
        } else if (!pomodoroId && !isWorkMode) {
            timerInterval = setInterval(tick, 1000);
        }
    } else {
        Swal.fire({
            title: 'Peringatan',
            text: 'Pomodoro dimulai sebagai guest. Daftar untuk sesi tak terbatas!',
            icon: 'warning',
            confirmButtonColor: '#ff7675',
            customClass: { popup: 'rounded-4' }
        });
    }
}

function pauseTimer() {
    clearInterval(timerInterval);
    isRunning = false;
    document.getElementById('btnStart').classList.remove('d-none');
    document.getElementById('btnStart').innerHTML = '<i class="fas fa-play me-2"></i>Lanjut';
    document.getElementById('btnPause').classList.add('d-none');

    const guestIndicator = document.getElementById('guestSisaSesi');
    if (!guestIndicator && pomodoroId) {
        savePomodoroState();
        isPaused = true;
        apiPost('/mahasiswa/pausePomodoro/' + pomodoroId)
            .catch(err => {
                console.error(err);
                Swal.fire({
                    title: 'Gagal!',
                    text: 'Gagal menjeda sesi pomodoro.',
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

            if (!document.getElementById('guestSisaSesi') && pomodoroId) {
                apiPost('/mahasiswa/updatePomodoro/' + pomodoroId, {status: 'stopped', completed: false})
                    .catch(err => console.error(err));
            }

            clearInterval(timerInterval);
            isRunning = false;
            clearPomodoroState();
            switchMode(true);
            document.getElementById('taskInput').disabled = false;
            resetControlButtons();
            updateDisplay();
        }
    });
}

function skipTimer() {
    clearInterval(timerInterval);
    isRunning = false;

    if (isWorkMode && pomodoroId && !document.getElementById('guestSisaSesi')) {
        apiPost('/mahasiswa/updatePomodoro/' + pomodoroId, {status: 'stopped', completed: false})
            .catch(err => console.error(err));
        clearPomodoroState();
    }

    switchMode(!isWorkMode);
}

function switchMode(toWork) {
    isWorkMode = toWork;
    totalSeconds = toWork ? workTime * 60 : restTime * 60;
    remainingSeconds = totalSeconds;
    updateDisplay();

    if (!toWork) {
        Swal.fire({
            title: 'Waktu Istirahat!',
            text: 'Berdiri, minum air, atau stretching sebentar ya.',
            icon: 'success',
            allowOutsideClick: false,
            confirmButtonColor: '#00b894',
            confirmButtonText: 'Oke, Istirahat Dulu',
            customClass: { popup: 'rounded-4', confirmButton: 'rounded-3' }
        });
    } else {
        Swal.fire({
            title: 'Istirahat Selesai!',
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
        Swal.fire({ title: 'Tunggu Dulu', text: 'Timer sedang berjalan.', icon: 'warning', confirmButtonColor: '#ff7675', customClass: { popup: 'rounded-4' } });
        return;
    }
    if (!isWorkMode && type === 'rest') {
        Swal.fire({ title: 'Tunggu Dulu', text: 'Sedang istirahat.', icon: 'info', confirmButtonColor: '#74b9ff', customClass: { popup: 'rounded-4' } });
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

function kunciTimerGuest() {
    switchMode(true);
    document.getElementById('taskInput').disabled = true;

    const btnStart = document.getElementById('btnStart');
    if (btnStart) {
        btnStart.disabled = true;
        btnStart.classList.replace('btn-primary', 'btn-secondary');
        btnStart.innerHTML = '<i class="fas fa-lock me-2"></i>Terkunci';
    }

    Swal.fire({
        icon: 'success',
        title: 'Fokus yang Luar Biasa!',
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

document.addEventListener("DOMContentLoaded", function () {
    const guestIndicator = document.getElementById('guestSisaSesi');

    if (guestIndicator) {
        let count = parseInt(localStorage.getItem('guestPomodoroCount') || 0);
        let sisa = 3 - count;

        cycles = count;
        document.getElementById('cycleCount').innerText = cycles;

        if (sisa <= 0) {
            guestIndicator.innerText = "0";
            kunciTimerGuest();
        } else {
            guestIndicator.innerText = sisa;
        }
    } else {
        restoreActiveSession();
        fetchTodayCycles();
    }

    const urlParams = new URLSearchParams(window.location.search);
    const taskFromUrl = urlParams.get('task');
    if (taskFromUrl) {
        const taskInput = document.getElementById('taskInput');
        if (taskInput) {
            taskInput.value = taskFromUrl;
        }
    }
});

function restoreActiveSession() {
    fetch('/mahasiswa/getActivePomodoro', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' }
    })
        .then(r => r.json())
        .then(data => {
            if (!data.active_session) return;

            const session = data.active_session;
            const createdDate = new Date(session.created_at);
            const today = new Date();

            if (createdDate.toDateString() !== today.toDateString()) return;

            pomodoroId = session.pomodoro_id;
            localStorage.setItem('pomodoro_id', pomodoroId);

            isWorkMode = true;
            const durationSec = session.duration * 60;

            if (session.status === 'paused') {
                const saved = localStorage.getItem('pomodoro_remaining_' + pomodoroId);
                if (saved) {
                    remainingSeconds = parseInt(saved);
                } else {
                    const startTime = new Date(session.session_start).getTime();
                    const pauseTime = new Date(session.updated_at).getTime();
                    const elapsed = Math.floor((pauseTime - startTime) / 1000);
                    remainingSeconds = Math.max(0, durationSec - elapsed);
                    remainingSeconds = Math.min(remainingSeconds, durationSec);
                }
                isPaused = true;
            } else if (session.status === 'active') {
                const startTime = new Date(session.session_start).getTime();
                const elapsed = Math.floor((Date.now() - startTime) / 1000);
                remainingSeconds = Math.max(0, durationSec - elapsed);
                remainingSeconds = Math.min(remainingSeconds, durationSec);

                if (remainingSeconds > 0) {
                    isRunning = true;
                } else {
                    apiPost('/mahasiswa/completePomodoro/' + pomodoroId).catch(err => console.error(err));
                    clearPomodoroState();
                    return;
                }
            } else {
                return;
            }

            totalSeconds = durationSec;

            document.getElementById('taskInput').value = session.title || '';
            document.getElementById('taskInput').disabled = true;
            document.getElementById('btnStart').classList.add('d-none');

            if (isPaused) {
                document.getElementById('btnStart').classList.remove('d-none');
                document.getElementById('btnStart').innerHTML = '<i class="fas fa-play me-2"></i>Lanjut';
            }

            if (!isPaused) {
                document.getElementById('btnPause').classList.remove('d-none');
            }
            document.getElementById('btnStop').classList.remove('d-none');
            document.getElementById('btnSkip').classList.remove('d-none');
            updateDisplay();

            if (isRunning) {
                timerInterval = setInterval(tick, 1000);
            }
        })
        .catch(err => console.error('Gagal merestorasi sesi pomodoro:', err));
}

function fetchTodayCycles() {
    fetch('/mahasiswa/getPomodoros', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' }
    })
        .then(r => r.json())
        .then(pomodoros => {
            const today = new Date();
            const todayStr = today.toDateString();
            const completedToday = pomodoros.filter(p => {
                const created = new Date(p.created_at);
                return p.completed && created.toDateString() === todayStr;
            });
            cycles = completedToday.length;
            document.getElementById('cycleCount').innerText = cycles;
        })
        .catch(err => console.error('Gagal memuat siklus pomodoro:', err));
}

updateDisplay();
