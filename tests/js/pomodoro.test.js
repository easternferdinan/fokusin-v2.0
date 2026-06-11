/**
 * @jest-environment jsdom
 */

const fs = require('fs');
const path = require('path');

// Setup DOM minimal
document.body.innerHTML = `
    <div id="timeDisplay">30:00</div>
    <circle id="progressBar"></circle>
    <span id="timerLabel"></span>
    <span id="modeBadge"></span>
    <input id="taskInput" />
    <button id="btnStart"></button>
    <button id="btnPause" class="d-none"></button>
    <button id="btnStop" class="d-none"></button>
    <button id="btnSkip" class="d-none"></button>
    <span id="cycleCount">0</span>
    <span id="workTimeDisplay">30</span>
    <span id="restTimeDisplay">5</span>
`;

// Load pomodoro.js sebagai global script
const pomodoroScript = fs.readFileSync(
    path.resolve(__dirname, '../../public/assets/js/pomodoro.js'), 'utf8'
);
eval(pomodoroScript);

// ✅ Test 1: Format waktu tampil benar saat awal
test('timeDisplay menampilkan 30:00 di awal', () => {
    expect(document.getElementById('timeDisplay').innerText).toBe('30:00');
});

// ✅ Test 2: adjustTime menambah workTime
test('adjustTime menambah work time +5', () => {
    adjustTime('work', 5);
    expect(String(document.getElementById('workTimeDisplay').innerText)).toBe('35');
});

// ✅ Test 3: adjustTime mengurangi workTime
test('adjustTime mengurangi work time -5', () => {
    adjustTime('work', -5);
    expect(String(document.getElementById('workTimeDisplay').innerText)).toBe('30');
});

// ✅ Test 4: workTime tidak bisa kurang dari 5
test('workTime tidak bisa kurang dari 5 menit', () => {
    adjustTime('work', -100);
    expect(String(document.getElementById('workTimeDisplay').innerText)).toBe('5');
});

// ✅ Test 5: workTime tidak bisa lebih dari 120
test('workTime tidak bisa lebih dari 120 menit', () => {
    adjustTime('work', 200);
    expect(String(document.getElementById('workTimeDisplay').innerText)).toBe('120');
});