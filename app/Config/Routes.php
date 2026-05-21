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
    $routes->get('pomodoro', 'Mahasiswa::pomodoro');    // Mengarah ke Timer Pomodoro
    $routes->get('report', 'Mahasiswa::report');        // Mengarah ke Report AI
    $routes->get('pengaturan', 'Mahasiswa::pengaturan');// Mengarah ke Pengaturan
    $routes->post('saveCheckin', 'Mahasiswa::saveCheckin'); // Menangkap submit check-in harian
    $routes->post('simpanTugas', 'Mahasiswa::simpanTugas'); // Menangkap submit tambah tugas
    $routes->post('hapusTugas/(:any)', 'Mahasiswa::hapusTugas/$1'); // Menangkap aksi hapus tugas dengan ID dinamis
});

$routes->group('admin', function($routes) {
    // Menu Admin Biasa
    $routes->get('/', 'Admin::index');
    $routes->get('stress', 'Admin::stress');
    $routes->get('alert', 'Admin::alert');

    // Menu Khusus Superadmin
    $routes->get('roles', 'Admin::roles');
    $routes->get('config', 'Admin::config');
    $routes->get('audit', 'Admin::audit');
});