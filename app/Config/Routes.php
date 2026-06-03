<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->group('auth', function($routes) {
    $routes->get('login', 'Auth::login');               // Buka halaman form
    $routes->post('loginProcess', 'Auth::loginProcess'); // Menangkap submit login
    $routes->post('registerProcess', 'Auth::registerProcess'); // Menangkap submit register
    $routes->get('logout', 'Auth::logout');             // Proses logout
    $routes->get('adminLogin', 'Auth::adminLogin');             // Login Admin
    $routes->post('adminLoginProcess', 'Auth::adminLoginProcess'); // Proses Login Admin
    
    $routes->get('logout', 'Auth::logout');                 // Logout Global
});

$routes->group('mahasiswa', function($routes) {
    $routes->get('/', 'Mahasiswa::index');              // Mengarah ke Dashboard
    $routes->get('tugas', 'Mahasiswa::tugas');          // Mengarah ke Daftar Tugas
    $routes->post('simpanTugas', 'Mahasiswa::simpanTugas'); // Menangkap submit tambah tugas
    $routes->post('updateTugas/(:any)', 'Mahasiswa::updateTugas/$1'); // Menangkap aksi update tugas dengan ID dinamis
    $routes->post('hapusTugas/(:any)', 'Mahasiswa::hapusTugas/$1'); // Menangkap aksi hapus tugas dengan ID dinamis
    $routes->post('toggleCompleteTugas/(:any)', 'Mahasiswa::toggleCompleteTugas/$1'); // Menangkap aksi toggle complete tugas dengan ID dinamis
    $routes->get('pomodoro', 'Mahasiswa::pomodoro');    // Mengarah ke Timer Pomodoro
    $routes->post('createPomodoro', 'Mahasiswa::createPomodoro'); // Menangkap aksi create pomodoro
    $routes->post('pausePomodoro/(:any)', 'Mahasiswa::pausePomodoro/$1');
    $routes->post('resumePomodoro/(:any)', 'Mahasiswa::resumePomodoro/$1');
    $routes->post('completePomodoro/(:any)', 'Mahasiswa::completePomodoro/$1');
    $routes->get('report', 'Mahasiswa::report');        // Mengarah ke Report AI
    $routes->get('pengaturan', 'Mahasiswa::pengaturan');// Mengarah ke Pengaturan
    $routes->post('pengaturan/save', 'Mahasiswa::saveProfileAI'); // Menangkap submit profile AI
    $routes->post('saveCheckin', 'Mahasiswa::saveCheckin'); // Menangkap submit check-in harian
    $routes->get('stress-trend', 'Mahasiswa::getStressTrend'); // Mengambil data tren stres
});

$routes->group('admin', function($routes) {
    // Menu Admin Biasa
    $routes->get('/', 'Admin::index');
    $routes->get('stress-analysis/(:any)', 'Admin::stressAnalysis/$1');
    $routes->get('stress', 'Admin::stress');
    $routes->get('alert', 'Admin::alert');
    $routes->post('store-mahasiswa', 'Admin::storeMahasiswa');

    // Menu Khusus Superadmin
    $routes->get('roles', 'Admin::roles');
    $routes->get('config', 'Admin::config');
    $routes->get('audit', 'Admin::audit');
});