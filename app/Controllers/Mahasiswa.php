<?php

namespace App\Controllers;

use App\Services\FastApiService;

class Mahasiswa extends BaseController
{
    protected $fastApiService;

    public function __construct()
    {
        $this->fastApiService = new FastApiService();
    }

    // Halaman Dashboard
    public function index()
    {
        $data = [
            'totalTugas'   => 12,
            'highPriority' => 5,
            'deadlineBesok'=> 2,
            'waktuFokus'   => '8h',
            'namaMahasiswa'=> session()->get('fullname')
        ];
        return view('mahasiswa/dashboard', $data);
    }

    // Halaman Daftar Tugas
    public function tugas()
    {
        // Kita tetap perlu mengirim namaMahasiswa agar Navbar tidak error
        $data = ['namaMahasiswa' => session()->get('fullname') ?? 'Guest'];
        return view('mahasiswa/daftar_tugas', $data);
    }

    // Halaman Timer Pomodoro
    public function pomodoro()
    {
        $data = [
            // Jika login, ambil nama dari session. Jika tidak, beri nama 'Guest'
            'namaMahasiswa' => session()->get('fullname') ?? 'Guest',
        ];
        
        return view('mahasiswa/pomodoro', $data);
    }

    // Halaman Report AI
    public function report()
    {
        // --- VARIABEL KONTROL UNTUK TESTING (Ubah true/false di sini untuk tes tampilan) ---
        $hasTasks        = false;  // Apakah mahasiswa punya tugas?
        $hasPomodoro     = false;  // Apakah mahasiswa sudah pernah pakai sesi pomodoro?
        $hasFilledInputs = false; // Apakah hari ini sudah input data prediksi stres?
        $stressCategory  = 'Tinggi'; // Kategori hasil AI kelak: 'Rendah', 'Sedang', 'Tinggi'

        // Ambil data nama seperti biasa
        $data = [
            'title'           => 'Report AI',
            'namaMahasiswa'   => session()->get('fullname') ?? 'Guest',
            'hasTasks'        => $hasTasks,
            'hasPomodoro'     => $hasPomodoro,
            'hasFilledInputs' => $hasFilledInputs,
            'stressScore'     => 3, // Contoh skor stres yang diprediksi AI
            'stressCategory'  => $stressCategory
        ];

        return view('mahasiswa/report_ai', $data);
    }

    // Halaman Pengaturan
    public function pengaturan()
    {
        $data = ['namaMahasiswa' => session()->get('fullname')];
        return view('mahasiswa/pengaturan', $data);
    }

    public function saveProfileAI()
    {
        $data = [
            'fullname' => $this->request->getPost('fullname'),
            'email' => $this->request->getPost('email'),
            'mental_health_history' => $this->request->getPost('mental_health_history'),
            'academic_performance' => $this->request->getPost('academic_performance'),
            'social_support' => $this->request->getPost('social_support')
        ];

        $response = $this->fastApiService->updateProfile($data);

        if ($response->getStatusCode() == 200) {
            return redirect()->to(base_url('mahasiswa/pengaturan'))->with('success', [
                'title' => 'Profile Berhasil Diupdate!',
                'message' => 'Profile Anda telah berhasil diperbarui.'
            ]);
        }

        return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui profile. Silahkan coba lagi.<br>' . $response->getBody());
    }

    public function saveCheckin()
    {
        // Tangkap data dari JS
        $sleep      = $this->request->getPost('sleep_quality');
        $esteem     = $this->request->getPost('self_esteem');
        $depression = $this->request->getPost('depression');
        $headache   = $this->request->getPost('headache');

        // (Proses simpan ke database di sini...)

        // Beri balasan ke JS bahwa data sudah aman
        return $this->response->setJSON(['status' => 'success']);
    }
}