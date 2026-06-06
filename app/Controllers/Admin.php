<?php

namespace App\Controllers;

use App\Services\AdminService;
use App\Services\SuperAdminService;

class Admin extends BaseController
{
    protected $adminService;
    protected $superAdminService;

    public function __construct()
    {
        helper('auth');
        $this->adminService = new AdminService();
        $this->superAdminService = new SuperAdminService();
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
            'title' => 'Data Pengguna',
            'role'  => $role,
            'mahasiswaData' => $mahasiswaData
        ];

        return view('admin/dashboard', $data);
    }

    public function stress()
    {
        $role = session()->get('role');
        if (($role !== 'admin' && $role !== 'superadmin')) return redirect()->to(base_url('auth/adminLogin'));

        $response = $this->adminService->getDashboardData();
        $dashboardData = [];
        if ($response->getStatusCode() === 200) {
            $dashboardData = json_decode($response->getBody(), true);
        }

        $data = [
            'title' => 'Pantau Stres Global',
            'role' => $role,
            'dashboardData' => $dashboardData
        ];
        return view('admin/stress', $data);
    }

    public function stressTrend()
    {
        $role = session()->get('role');
        if ($role !== 'admin' && $role !== 'superadmin') {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        $period = $this->request->getGet('period') ?? 'this_month';
        $response = $this->adminService->getStressTrendData($period);

        return $this->response->setJSON([
            'status'  => $response->getStatusCode(),
            'data'    => json_decode($response->getBody(), true),
        ])->setStatusCode($response->getStatusCode());
    }

    public function stressAnalysis($userId)
    {
        $role = session()->get('role');
        if ($role !== 'admin' && $role !== 'superadmin') {
            return $this->response->setJSON([
                'error' => 'Unauthorized'
            ])->setStatusCode(401);
        }

        $page = $this->request->getGet('page') ?? 1;
        $size = $this->request->getGet('size') ?? 10;

        $response = $this->adminService->getMahasiswaStressAnalysis($userId, $page, $size);

        return $this->response->setJSON([
            'status'  => $response->getStatusCode(),
            'data'    => json_decode($response->getBody(), true),
        ])->setStatusCode($response->getStatusCode());
    }

    public function storeMahasiswa()
    {
        $role = session()->get('role');
        if ($role !== 'admin' && $role !== 'superadmin') {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        $json = $this->request->getJSON();

        $data = [
            'fullname'              => $json->fullname ?? '',
            'username'              => $json->username ?? '',
            'email'                 => $json->email ?? '',
            'password'              => $json->password ?? '',
            'mental_health_history' => (bool) ($json->mental_health_history ?? false),
            'academic_performance'  => (int) ($json->academic_performance ?? 0),
            'social_support'        => (int) ($json->social_support ?? 0),
        ];

        $response = $this->adminService->createMahasiswa($data);
        $statusCode = $response->getStatusCode();
        $body = json_decode($response->getBody(), true);

        return $this->response->setJSON($body)->setStatusCode($statusCode);
    }

    public function alert()
    {
        $role = session()->get('role');
        if (($role !== 'admin' && $role !== 'superadmin')) return redirect()->to(base_url('auth/adminLogin'));

        $response = $this->adminService->getAlertData();

        if ($response->getStatusCode() !== 200) {
            return redirect()->back()->with('error', [
                'title' => 'Terjadi Kesalahan!',
                'message' => 'Coba lagi nanti atau hubungi admin.',
                'detail' => $response->getBody()
            ]);
        }

        $responseData = json_decode($response->getBody(), true);

        $data = [
            'title' => 'Tindak Lanjut Kritis',
            'role' => $role,
            'alertData' => $responseData['alerted_mahasiswa'],
            'stressThreshold' => $responseData['stress_threshold'],
            'stressThresholdFrequency' => $responseData['stress_threshold_frequency'],
        ];

        return view('admin/alert', $data);
    }

    public function sendAlert()
    {
        $role = session()->get('role');
        if ($role !== 'admin' && $role !== 'superadmin') {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        $json = $this->request->getJSON(true);
        $userId = $json['user_id'] ?? '';
        $threshold = $json['stress_threshold'] ?? '';
        $frequency = (int) ($json['stress_threshold_frequency'] ?? 0);

        if (empty($userId)) {
            return $this->response->setJSON(['error' => 'user_id is required'])->setStatusCode(400);
        }

        $response = $this->adminService->sendAlert($userId, $threshold, $frequency);
        $statusCode = $response->getStatusCode();
        $body = json_decode($response->getBody(), true);

        return $this->response->setJSON($body)->setStatusCode($statusCode);
    }

    // ==================================================================
    // SUPER ADMIN
    // ==================================================================

    public function adminManagement()
    {
        if (session()->get('role') !== 'superadmin') return redirect()->to(base_url('admin'));

        $response = $this->superAdminService->getAdmins();
        $admins = [];

        if ($response->getStatusCode() === 200) {
            $admins = json_decode($response->getBody(), true) ?? [];
        } else {
            log_message('error', 'Gagal mengambil data admin: ' . $response->getBody());
        }

        $data = [
            'title' => 'Admin Management',
            'role'  => 'superadmin',
            'admins' => $admins,
        ];
        return view('admin/admin_management', $data);
    }

    public function storeAdmin()
    {
        if (session()->get('role') !== 'superadmin') {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        $json = $this->request->getJSON();

        $data = [
            'fullname' => $json->fullname ?? '',
            'username' => $json->username ?? '',
            'password' => $json->password ?? '',
        ];

        $response = $this->superAdminService->createAdmin($data);
        $statusCode = $response->getStatusCode();
        $body = json_decode($response->getBody(), true);

        return $this->response->setJSON($body)->setStatusCode($statusCode);
    }

    public function updateAdmin()
    {
        if (session()->get('role') !== 'superadmin') {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        $json = $this->request->getJSON();

        $adminId = $json->admin_id ?? '';
        if (empty($adminId)) {
            return $this->response->setJSON(['error' => 'admin_id is required'])->setStatusCode(400);
        }

        $data = [
            'fullname' => $json->fullname ?? '',
            'username' => $json->username ?? '',
        ];

        if (!empty($json->password)) {
            $data['password'] = $json->password;
        }

        $response = $this->superAdminService->updateAdmin($adminId, $data);
        $statusCode = $response->getStatusCode();
        $body = json_decode($response->getBody(), true);

        return $this->response->setJSON($body)->setStatusCode($statusCode);
    }

    public function deleteAdmin()
    {
        if (session()->get('role') !== 'superadmin') {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        $json = $this->request->getJSON();
        $adminId = $json->admin_id ?? '';
        if (empty($adminId)) {
            return $this->response->setJSON(['error' => 'admin_id is required'])->setStatusCode(400);
        }

        $response = $this->superAdminService->deleteAdmin($adminId);
        $statusCode = $response->getStatusCode();

        if ($statusCode === 204) {
            return $this->response->setJSON(['status' => 'success'])->setStatusCode(200);
        }

        $body = json_decode($response->getBody(), true);
        return $this->response->setJSON($body)->setStatusCode($statusCode);
    }

    public function config()
    {
        if (session()->get('role') !== 'superadmin') return redirect()->to(base_url('admin'));

        $response = $this->superAdminService->getConfig();

        if ($response->getStatusCode() !== 200) {
            return redirect()->back()->with('error', [
                'title' => 'Terjadi Kesalahan!',
                'message' => 'Coba lagi nanti atau hubungi admin.',
                'detail' => $response->getBody()
            ]);
        }

        $configData = json_decode($response->getBody(), true);

        $data = [
            'title' => 'Konfigurasi Sistem',
            'role'  => 'superadmin',
            'config' => $configData
        ];

        return view('admin/config', $data);
    }

    public function updateConfig()
    {
        $role = session()->get('role');
        if ($role !== 'superadmin') {
            return redirect()->to(base_url('auth/adminLogin'))->with('error', [
                'title' => 'Terjadi Kesalahan!',
                'message' => 'Anda tidak memiliki akses ke halaman ini.',
            ]);
        }

        $api_base_url = $this->request->getPost('api_base_url');
        $stress_threshold = $this->request->getPost('stress_threshold');
        $stress_threshold_frequency = $this->request->getPost('stress_threshold_frequency');

        $data = [
            'api_base_url' => $api_base_url,
            'stress_threshold' => $stress_threshold,
            'stress_threshold_frequency' => $stress_threshold_frequency,
        ];

        $response = $this->superAdminService->updateConfig($data);
        $statusCode = $response->getStatusCode();

        $body = json_decode($response->getBody(), true);

        if ($statusCode === 200) {
            $this->superAdminService->updateBaseUrl($data['api_base_url']);
            return redirect()->back()->with('success', [
                'title' => 'Berhasil!',
                'message' => 'Konfigurasi berhasil diperbarui.',
            ]);
        }

        return redirect()->back()->with('error', [
            'title' => 'Gagal!',
            'message' => 'Gagal memperbarui konfigurasi.',
            'detail' => $body
        ]);
    }

    public function exportDatabase()
    {
        if (session()->get('role') !== 'superadmin') {
            return redirect()->to(base_url('admin'));
        }

        $response = $this->superAdminService->exportDatabase();

        if ($response->getStatusCode() !== 200) {
            return redirect()->back()->with('error', [
                'title' => 'Gagal!',
                'message' => 'Gagal mengekspor database.',
                'detail' => $response->getBody()
            ]);
        }

        $filename = 'database-export-' . date('Y-m-d') . '.zip';

        return $this->response
            ->setHeader('Content-Type', 'application/zip')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setHeader('Content-Length', strlen($response->getBody()))
            ->setBody($response->getBody());
    }

    public function audit()
    {
        if (session()->get('role') !== 'superadmin') return redirect()->to(base_url('admin'));

        $data = [
            'title' => 'Audit Log Sistem',
            'role'  => 'superadmin',
        ];
        return view('admin/audit', $data);
    }

    public function auditLogs()
    {
        if (session()->get('role') !== 'superadmin') {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        $level = $this->request->getGet('level');
        $eventType = $this->request->getGet('event_type');
        $limit = (int) ($this->request->getGet('limit') ?? 100);
        $skip = (int) ($this->request->getGet('skip') ?? 0);

        $response = $this->superAdminService->getLogs($level, $eventType, $limit, $skip);

        return $this->response->setJSON([
            'status' => $response->getStatusCode(),
            'data'   => json_decode($response->getBody(), true),
        ])->setStatusCode($response->getStatusCode());
    }

    // Fungsi halaman lainnya (contoh)
    // public function stress() { ... }
}
