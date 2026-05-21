<?php

namespace App\Controllers;

class Auth extends BaseController
{
    // Menampilkan halaman gabungan Login & Register
    public function login()
    {
        return view('auth/login_register');
    }

    // Nanti logika cek database ditaruh di sini
    public function loginProcess()
    {
        // Tangkap data dari form (name="username" dan name="password")
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        
        // (Proses validasi database akan dibuat nanti)
    }

    // Nanti logika insert ke database ditaruh di sini
    public function registerProcess()
    {
        // Tangkap semua data dari form registrasi
        $data = [
            'nama_lengkap'    => $this->request->getPost('nama_lengkap'),
            'username'        => $this->request->getPost('username'),
            'email'           => $this->request->getPost('email'),
            'password'        => $this->request->getPost('password'), // Nanti harus di hash
            'riwayat_mental'  => $this->request->getPost('riwayat_mental'),
            'ipk'             => $this->request->getPost('ipk'),
            'dukungan_sosial' => $this->request->getPost('dukungan_sosial'),
        ];
        
        // (Proses simpan ke database akan dibuat nanti)
    }

    public function adminLogin()
    {
        // Jika sudah login sebagai admin/superadmin, langsung lempar ke dashboard
        if (session()->get('role') === 'admin' || session()->get('role') === 'superadmin') {
            return redirect()->to(base_url('admin'));
        }

        return view('auth/login_admin');
    }

    // Memproses form login admin
    public function adminLoginProcess()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Simulasi Cek Database (Prototype)
        if ($username === 'admin' || $username === 'superadmin') {
            
            // Buat session
            session()->set([
                'isLoggedIn' => true,
                'role'       => $username // 'admin' atau 'superadmin'
            ]);

            return redirect()->to(base_url('admin'));
        }

        // Jika gagal, kembalikan ke form login admin
        return redirect()->to(base_url('auth/adminLogin'))->with('error', 'Username atau password salah');
    }

    public function logout()
    {
        // Cek dulu siapa yang logout untuk menentukan arah redirect
        $role = session()->get('role');
        
        session()->destroy();

        // Jika yang logout admin, kembalikan ke form login admin
        if ($role === 'admin' || $role === 'superadmin') {
            return redirect()->to(base_url('auth/adminLogin'));
        }
        
        // Jika mahasiswa, kembalikan ke landing page / login utama
        return redirect()->to(base_url('/'));
    }
}