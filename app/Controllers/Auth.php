<?php

namespace App\Controllers;

use App\Services\AuthService;

class Auth extends BaseController
{
    protected AuthService $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    // Menampilkan halaman gabungan Login & Register
    public function login()
    {
        return view('auth/login_register');
    }

    // Nanti logika cek database ditaruh di sini
    public function loginProcess()
    {
        // Tangkap data dari form (name="username" dan name="password")
        $data = [
            'username' => $this->request->getPost('username'),
            'password' => $this->request->getPost('password'),
        ];

        $response = $this->authService->loginUser($data);
        if ($response->getStatusCode() == 200) {
            return redirect()->to(base_url('mahasiswa'))->with('success', [
                'title' => 'Login Berhasil!',
                'message' => 'Selamat Datang ' . session()->get('fullname') . '!'
            ]);
        }

        $errorResponse = json_decode($response->getBody());
        if ($response->getStatusCode() == 401) {
            return redirect()->back()->with('error', [
                'title' => 'Login Gagal!',
                'message' => $errorResponse->detail,
                'detail' => $response->getBody()
            ]);
        }

        return redirect()->back()->with('error', [
            'title' => 'Terjadi Kesalahan!',
            'message' => 'Coba lagi nanti atau hubungi admin.',
            'detail' => $response->getBody()
        ]);
    }

    public function registerProcess()
    {
        $data = [
            'fullname'    => $this->request->getPost('nama_lengkap'),
            'username'    => $this->request->getPost('username'),
            'email'       => $this->request->getPost('email'),
            'password'    => $this->request->getPost('password'),
            'mental_health_history' => (bool) $this->request->getPost('riwayat_mental'),
            'academic_performance'  => (int) $this->request->getPost('akademik_performa'),
            'social_support'        => (int) $this->request->getPost('dukungan_sosial'),
        ];

        $response = $this->authService->registerUser($data);

        if ($response->getStatusCode() == 201) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Akun Berhasil Dibuat! Silakan login menggunakan username dan password yang baru dibuat.'
            ]);
        }

        $body = json_decode($response->getBody(), true);

        return $this->response->setStatusCode($response->getStatusCode())->setJSON([
            'success' => false,
            'message' => $body['detail'] ?? 'Gagal mendaftar. Silakan coba lagi nanti.'
        ]);
    }

    public function adminLogin()
    {
        return view('auth/login_admin');
    }

    // Memproses form login admin
    public function adminLoginProcess()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $response = $this->authService->loginUser([
            'username' => $username,
            'password' => $password
        ]);

        if ($response->getStatusCode() == 401) {
            return redirect()->to(base_url('auth/adminLogin'))->with('error', [
                'title' => 'Login Gagal!',
                'message' => 'Username atau password salah.',
            ]);
        }

        if ($response->getStatusCode() !== 200) {
            return redirect()->to(base_url('auth/adminLogin'))->with('error', [
                'title' => 'Login Gagal!',
                'message' => 'Terjadi kesalahan saat login. Silakan coba lagi.',
                'detail' => $response->getBody()
            ]);
        }

        if (session()->get('role') === 'admin') {
            return redirect()->to(base_url('admin'))->with('success', [
                'title' => 'Login Berhasil!',
                'message' => 'Selamat Datang Admin!'
            ]);
        }

        if (session()->get('role') === 'superadmin') {
            return redirect()->to(base_url('admin/admin-management'))->with('success', [
                'title' => 'Login Berhasil!',
                'message' => 'Selamat Datang Super Admin!'
            ]);
        }

        return redirect()->back()->with('error', [
            'title' => 'Login Gagal!',
            'message' => 'Terjadi kesalahan saat login. Silakan coba lagi.',
            'detail' => $response->getBody()
        ]);
    }

    public function forgotPassword()
    {
        $json = $this->request->getJSON(true);
        $username = $json['username'] ?? '';

        if (empty($username)) {
            return $this->response->setJSON(['detail' => 'Username wajib diisi'])->setStatusCode(400);
        }

        $response = $this->authService->forgotPassword($username);
        $body = json_decode($response->getBody(), true);

        if ($response->getStatusCode() == 200) {
            return $this->response->setJSON($body);
        }

        return $this->response->setJSON($body)->setStatusCode($response->getStatusCode());
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
