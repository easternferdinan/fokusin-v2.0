<?php

namespace App\Controllers;

use App\Services\AdminService;

class Admin extends BaseController
{
    protected $adminService;

    public function __construct()
    {
        helper('auth');
        $this->adminService = new AdminService();
    }

    // ==================================================================
    // ADMIN
    // ==================================================================

    // Fungsi utama halaman data pengguna
    public function index()
    {
        // Cek Keamanan: Jika tidak ada session login atau bukan admin, tendang ke login admin
        $role = session()->get('role');
        if (($role !== 'admin' && $role !== 'superadmin')) {
            return redirect()->to(base_url('auth/adminLogin'));
        }

        $response = $this->adminService->getMahasiswaData();

        if ($response->getStatusCode() !== 200) {
            log_message('error', 'Error API Admin: ' . $response->getBody());
            return redirect()->back()->with('error', [
                'title' => 'Terjadi Kesalahan!',
                'message' => 'Coba lagi nanti atau hubungi admin.',
                'detail' => $response->getBody()
            ]);
        }

        $mahasiswaData = json_decode($response->getBody(), true);

        // Kirim data role ke view agar badge dan menu bisa dinamis
        $data = [
            'title' => 'Dashboard',
            'role'  => $role,
            'mahasiswaData' => $mahasiswaData
        ];

        return view('admin/dashboard', $data);
    }

    public function stress()
    {
        $role = session()->get('role');
        if (($role !== 'admin' && $role !== 'superadmin')) return redirect()->to(base_url('auth/adminLogin'));

        $data = ['title' => 'Pantau Stres Global', 'role' => $role];
        return view('admin/stress', $data);
    }

    public function alert()
    {
        $role = session()->get('role');
        if (($role !== 'admin' && $role !== 'superadmin')) return redirect()->to(base_url('auth/adminLogin'));

        $data = ['title' => 'Tindak Lanjut Kritis', 'role' => $role];
        return view('admin/alert', $data);
    }

    // ==================================================================
    // SUPER ADMIN
    // ==================================================================

    public function roles()
    {
        if (session()->get('role') !== 'superadmin') return redirect()->to(base_url('admin'));

        $data = [
            'title' => 'Role Management',
            'role'  => 'superadmin'
        ];
        return view('admin/roles', $data);
    }

    public function config()
    {
        if (session()->get('role') !== 'superadmin') return redirect()->to(base_url('admin'));

        $data = [
            'title' => 'Konfigurasi Sistem',
            'role'  => 'superadmin'
        ];
        return view('admin/config', $data);
    }

    public function audit()
    {
        if (session()->get('role') !== 'superadmin') return redirect()->to(base_url('admin'));

        $data = [
            'title' => 'Audit Log Sistem',
            'role'  => 'superadmin'
        ];
        return view('admin/audit', $data);
    }

    // Fungsi halaman lainnya (contoh)
    // public function stress() { ... }
}
