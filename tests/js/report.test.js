/**
 * @jest-environment jsdom
 */

const fs = require('fs');
const path = require('path');

// Mock Swal
global.Swal = { fire: jest.fn(() => Promise.resolve({ isConfirmed: true })) };

// Mock bootstrap — Modal harus bisa dipanggil sebagai constructor DAN punya getInstance
const mockModalInstance = { show: jest.fn(), hide: jest.fn() };
global.bootstrap = {
    Modal: Object.assign(
        jest.fn().mockImplementation(() => mockModalInstance),
        { getInstance: jest.fn(() => mockModalInstance) }
    )
};

// Mock Chart.js
global.Chart = jest.fn().mockImplementation(() => ({
    destroy: jest.fn(),
    update: jest.fn(),
    data: { labels: [], datasets: [{ data: [] }] }
}));

// Setup DOM — pastikan modalCheckin ada
document.body.innerHTML = `
    <div id="chartPlaceholder" style="display:none"></div>
    <canvas id="studentStressChart" style="display:block"></canvas>
    <select id="filterTrenStres"></select>
    <div id="modalCheckin"></div>
    <table id="tabelRiwayatLengkap">
        <tr><th>Tanggal</th><th>Stres</th></tr>
        <tr><td>2024-01-01</td><td>Tinggi</td></tr>
    </table>
    <button id="btnPrediksi" data-hastasks="false" data-haspomodoro="false"></button>
`;

// Patch innerText → textContent untuk semua elemen di JSDOM
document.querySelectorAll('td, th').forEach(cell => {
    Object.defineProperty(cell, 'innerText', {
        get: () => cell.textContent,
        configurable: true
    });
});

// Load report.js
const reportScript = fs.readFileSync(
    path.resolve(__dirname, '../../public/assets/js/report.js'), 'utf8'
);
eval(reportScript);

// ✅ Test 1: cekPrasyaratPrediksi tampilkan warning jika prasyarat belum terpenuhi
test('cekPrasyaratPrediksi tampilkan warning jika hasTasks false', () => {
    Swal.fire.mockClear();
    const btn = document.getElementById('btnPrediksi');
    btn.setAttribute('data-hastasks', 'false');
    btn.setAttribute('data-haspomodoro', 'false');
    cekPrasyaratPrediksi(btn);
    expect(Swal.fire).toHaveBeenCalled();
});

// ✅ Test 2: cekPrasyaratPrediksi tidak tampilkan warning jika prasyarat terpenuhi
test('cekPrasyaratPrediksi tidak tampilkan warning jika semua prasyarat terpenuhi', () => {
    Swal.fire.mockClear();
    const btn = document.getElementById('btnPrediksi');
    btn.setAttribute('data-hastasks', 'true');
    btn.setAttribute('data-haspomodoro', 'true');
    cekPrasyaratPrediksi(btn);
    expect(Swal.fire).not.toHaveBeenCalled();
});

// ✅ Test 3: showChartPlaceholder(true) sembunyikan canvas
test('showChartPlaceholder(true) sembunyikan canvas', () => {
    showChartPlaceholder(true);
    expect(document.getElementById('chartPlaceholder').style.display).toBe('block');
    expect(document.getElementById('studentStressChart').style.display).toBe('none');
});

// ✅ Test 4: showChartPlaceholder(false) tampilkan canvas
test('showChartPlaceholder(false) tampilkan canvas', () => {
    showChartPlaceholder(false);
    expect(document.getElementById('chartPlaceholder').style.display).toBe('none');
    expect(document.getElementById('studentStressChart').style.display).toBe('block');
});

// ✅ Test 5: exportTableToCSV tidak error saat tabel ada datanya
test('exportTableToCSV tidak error saat tabel ada datanya', () => {
    global.URL.createObjectURL = jest.fn(() => 'blob:mock');
    expect(() => exportTableToCSV('test.csv')).not.toThrow();
});