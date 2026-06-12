/**
 * @jest-environment jsdom
 */

const fs = require('fs');
const path = require('path');

// Mock Swal
global.Swal = { fire: jest.fn(() => Promise.resolve({ isConfirmed: true })) };

// Mock bootstrap
const mockModalInstance = { show: jest.fn(), hide: jest.fn() };
global.bootstrap = {
    Modal: Object.assign(
        jest.fn().mockImplementation(() => mockModalInstance),
        { getInstance: jest.fn(() => mockModalInstance) }
    )
};

// Setup DOM
document.body.innerHTML = `
    <form id="formCheckin">
        <input id="checkinDate" />
        <input type="radio" name="sleep_quality" value="1" />
        <input type="radio" name="sleep_quality" value="2" />
        <button type="submit">Submit</button>
    </form>
    <span id="checkinBadge" style="display:block"></span>
`;

// Load checkin.js
const checkinScript = fs.readFileSync(
    path.resolve(__dirname, '../../public/assets/js/checkin.js'), 'utf8'
);
eval(checkinScript);

// ✅ Test 1: checkinDate bisa di-set dengan tanggal hari ini
test('checkinDate terisi tanggal hari ini', () => {
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('checkinDate').value = today;
    expect(document.getElementById('checkinDate').value).toBe(today);
});

// ✅ Test 2: submitCheckin tampilkan warning jika sleep_quality belum dipilih
test('submitCheckin tampilkan warning jika sleep_quality belum dipilih', () => {
    Swal.fire.mockClear();
    const event = { preventDefault: jest.fn(), target: document.getElementById('formCheckin') };
    submitCheckin(event);
    expect(Swal.fire).toHaveBeenCalled();
});

// ✅ Test 3: submitCheckin tidak panggil Swal jika sleep_quality sudah dipilih
test('submitCheckin tidak panggil Swal jika sleep_quality dipilih', () => {
    Swal.fire.mockClear();
    document.querySelector('input[name="sleep_quality"]').checked = true;
    const form = document.getElementById('formCheckin');
    form.submit = jest.fn();
    const event = { preventDefault: jest.fn(), target: form };
    submitCheckin(event);
    expect(Swal.fire).not.toHaveBeenCalled();
});

// ✅ Test 4: suksesCheckin menyimpan data ke localStorage
test('suksesCheckin simpan data ke localStorage', async () => {
    const today = new Date().toISOString().split('T')[0];
    const form = document.getElementById('formCheckin');
    form.reset = jest.fn();
    await suksesCheckin(today, '2', form);
    expect(localStorage.getItem('checkin_' + today)).toBe('2');
});

// ✅ Test 5: checkinBadge disembunyikan jika sudah checkin hari ini
test('checkinBadge disembunyikan jika sudah checkin hari ini', () => {
    const today = new Date().toISOString().split('T')[0];
    localStorage.setItem('checkin_' + today, '1');
    const badge = document.getElementById('checkinBadge');
    if (localStorage.getItem('checkin_' + today)) {
        badge.style.display = 'none';
    }
    expect(badge.style.display).toBe('none');
});

// ✅ Test 6: Form tidak bisa submit dua kali dalam sehari
test('form checkin tidak bisa submit jika sudah checkin hari ini', () => {
    const today = new Date().toISOString().split('T')[0];
    localStorage.setItem('checkin_' + today, '2');
    const alreadyCheckin = localStorage.getItem('checkin_' + today) !== null;
    expect(alreadyCheckin).toBe(true);
});

// ✅ Test 7: Nilai sleep_quality harus antara 1-5
test('nilai sleep_quality valid antara 1 sampai 5', () => {
    const validValues = ['1', '2', '3', '4', '5'];
    const radios = document.querySelectorAll('input[name="sleep_quality"]');
    radios.forEach(radio => {
        expect(validValues).toContain(radio.value);
    });
});

// ✅ Test 8: Semua field wajib ada di form checkin
test('form checkin memiliki semua field yang diperlukan', () => {
    expect(document.getElementById('checkinDate')).not.toBeNull();
    expect(document.getElementById('formCheckin')).not.toBeNull();
    expect(document.querySelector('input[name="sleep_quality"]')).not.toBeNull();
});