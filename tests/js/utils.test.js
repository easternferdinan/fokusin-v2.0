/**
 * @jest-environment jsdom
 */

const fs = require('fs');
const path = require('path');

// Load utils.js sebagai global script
const utilsScript = fs.readFileSync(
    path.resolve(__dirname, '../../public/assets/js/utils.js'), 'utf8'
);
eval(utilsScript);

// ✅ Test: fungsi toggleSidebar ada
test('toggleSidebar adalah sebuah fungsi', () => {
    expect(typeof toggleSidebar).toBe('function');
});

// ✅ Test: fungsi openTugasModal ada
test('openTugasModal adalah sebuah fungsi', () => {
    expect(typeof openTugasModal).toBe('function');
});

// ✅ Test: fungsi editTugas ada
test('editTugas adalah sebuah fungsi', () => {
    expect(typeof editTugas).toBe('function');
});