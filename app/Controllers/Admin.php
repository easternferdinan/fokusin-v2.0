<?php

namespace App\Controllers;

class Admin extends BaseController
{
    // Fungsi utama dashboard admin (menggantikan fungsi dashboard() sebelumnya agar URL lebih bersih)
    public function index()
    {
        // Cek Keamanan: Jika tidak ada session login atau bukan admin, tendang ke login admin
        $role = session()->get('role');
        if (!session()->get('isLoggedIn') || ($role !== 'admin' && $role !== 'superadmin')) {
            return redirect()->to(base_url('auth/adminLogin')); 
        }

        // Kirim data role ke view agar badge dan menu bisa dinamis
        $data = [
            'title' => 'Dashboard',
            'role'  => $role
        ];
        
        return view('admin/dashboard', $data);
    }

    public function stress()
    {
        $role = session()->get('role');
        if (!session()->get('isLoggedIn') || ($role !== 'admin' && $role !== 'superadmin')) return redirect()->to(base_url('auth/adminLogin')); 

        $data = ['title' => 'Pantau Stres Global', 'role' => $role];
        return view('admin/stress', $data);
    }

    public function alert()
    {
        $role = session()->get('role');
        if (!session()->get('isLoggedIn') || ($role !== 'admin' && $role !== 'superadmin')) return redirect()->to(base_url('auth/adminLogin')); 

        $data = ['title' => 'Tindak Lanjut Kritis', 'role' => $role];
        return view('admin/alert', $data);
    }

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