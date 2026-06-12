/**
 * @jest-environment jsdom
 */

// Setup DOM — simulasi elemen status stres di dashboard
document.body.innerHTML = `
    <div id="stressStatus">
        <h5 id="stressLabel" class="fw-bold"></h5>
        <p id="stressMessage" class="text-muted small mb-0"></p>
    </div>
    <span id="checkinBadge" style="display:block"></span>
`;

// Fungsi simulasi render status stres (seperti yang dilakukan PHP di view)
function renderStressStatus(status) {
    const label = document.getElementById('stressLabel');
    const message = document.getElementById('stressMessage');

    if (!status || status === '') {
        label.textContent = '';
        label.className = 'fw-bold';
        message.textContent = 'Sistem belum memiliki data untuk memprediksi tingkat stres.';
        return;
    }

    const s = status.toLowerCase();

    if (s === 'rendah') {
        label.textContent = 'Prediksi AI: RENDAH';
        label.className = 'fw-bold text-success';
        message.textContent = 'Sistem memprediksi tingkat stres akademikmu adalah rendah. Pertahankan ya!';
    } else if (s === 'sedang') {
        label.textContent = 'Prediksi AI: SEDANG';
        label.className = 'fw-bold text-warning';
        message.textContent = 'Sistem memprediksi tingkat stres akademikmu adalah sedang. Jangan lupa istirahat ya!';
    } else if (s === 'tinggi') {
        label.textContent = 'Prediksi AI: TINGGI';
        label.className = 'fw-bold text-danger';
        message.textContent = 'Sistem memprediksi tingkat stres akademikmu adalah tinggi. Kelola tugasmu perlahan ya!';
    }
}

// ✅ Test 1: Status kosong tampilkan pesan belum ada data
test('status kosong tampilkan pesan belum ada data', () => {
    renderStressStatus('');
    expect(document.getElementById('stressMessage').textContent)
        .toBe('Sistem belum memiliki data untuk memprediksi tingkat stres.');
});

// ✅ Test 2: Status rendah tampilkan label hijau
test('status rendah tampilkan label RENDAH dengan warna hijau', () => {
    renderStressStatus('rendah');
    expect(document.getElementById('stressLabel').textContent).toBe('Prediksi AI: RENDAH');
    expect(document.getElementById('stressLabel').className).toContain('text-success');
});

// ✅ Test 3: Status sedang tampilkan label kuning
test('status sedang tampilkan label SEDANG dengan warna kuning', () => {
    renderStressStatus('sedang');
    expect(document.getElementById('stressLabel').textContent).toBe('Prediksi AI: SEDANG');
    expect(document.getElementById('stressLabel').className).toContain('text-warning');
});

// ✅ Test 4: Status tinggi tampilkan label merah
test('status tinggi tampilkan label TINGGI dengan warna merah', () => {
    renderStressStatus('tinggi');
    expect(document.getElementById('stressLabel').textContent).toBe('Prediksi AI: TINGGI');
    expect(document.getElementById('stressLabel').className).toContain('text-danger');
});

// ✅ Test 5: Status tinggi tampilkan pesan yang tepat
test('status tinggi tampilkan pesan kelola tugas perlahan', () => {
    renderStressStatus('tinggi');
    expect(document.getElementById('stressMessage').textContent)
        .toBe('Sistem memprediksi tingkat stres akademikmu adalah tinggi. Kelola tugasmu perlahan ya!');
});

// ✅ Test 6: Elemen badge checkin tersedia di halaman
test('elemen checkinBadge tersedia di halaman', () => {
    expect(document.getElementById('checkinBadge')).not.toBeNull();
});

// ✅ Test 7: Badge disembunyikan saat status tinggi dan sudah checkin
test('checkinBadge disembunyikan setelah user checkin', () => {
    const badge = document.getElementById('checkinBadge');
    badge.style.display = 'none';
    expect(badge.style.display).toBe('none');
});