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
        $response = $this->fastApiService->getDashboardData();
                    
        if (session()->get('email') == null || $response->getStatusCode() == 401) {
            return redirect()->back()->with('error', [
                'title' => 'Akses Ditolak',
                'message' => 'Login untuk menggunakan fitur ini.'
            ]);
        }

        if ($response->getStatusCode() == 200) {
            $dashboardData = json_decode($response->getBody());

            // dummy prediction data for testing
            // $dashboardData->latest_burnout_prediction: ['rendah', 'sedang', 'tinggi'] = null;
    
            $waktuFokus = $dashboardData->today_pomodoro_minutes;
            if ($waktuFokus == 0) {
                $waktuFokus = '0 Menit';
            } elseif ($waktuFokus < 60) {
                $waktuFokus = $waktuFokus . ' Menit';
            } else {
                $waktuFokus = ($waktuFokus / 60) . ' Jam';
            }
    
            $data = [
                    'statusBurnout' => $dashboardData->latest_burnout_prediction,
                    'totalTugas'    => $dashboardData->incomplete_tasks_count,
                    'highPriority'  => $dashboardData->high_priority_tasks_count,
                    'deadlineBesok' => $dashboardData->deadline_is_tomorrow_tasks_count,
                    'waktuFokus'    => $waktuFokus,
                    'tugasMendesak' => $dashboardData->deadline_is_tomorrow_tasks,
                    'namaMahasiswa' => session()->get('fullname')
                ];
                return view('mahasiswa/dashboard', $data);
        } else {
            return redirect()->to(base_url('auth/login'))->with('error', [
                'title' => 'Terjadi Kesalahan!',
                'message' => 'Terjadi kesalahan tak terduga. Hubungi admin.',
                'detail' => $response->getBody()
            ]);
        }
    }

    // Halaman Daftar Tugas
    public function tugas()
    {
        $response = $this->fastApiService->getTasks();

        if (session()->get('email') == null || $response->getStatusCode() == 401) {
            return redirect()->back()->with('error', [
                'title' => 'Akses Ditolak',
                'message' => 'Login untuk menggunakan fitur ini.'
            ]);
        }

        if ($response->getStatusCode() == 200) {
            $tasks = json_decode($response->getBody());

            $data = [
                'tasks' => $tasks,
                'namaMahasiswa' => session()->get('fullname')
            ];

            return view('mahasiswa/daftar_tugas', $data);
        } else {
            log_message('error', 'Error API Tugas: ' . $response->getBody());
            return redirect()->to(base_url('mahasiswa'))->with('error', [
                'title' => 'Terjadi Kesalahan!',
                'message' => 'Terjadi kesalahan saat mengambil data tugas. Hubungi admin.',
                'detail' => $response->getBody()
            ]);
        }
    }

    // Simpan Tugas
    public function simpanTugas()
    {
        if (session()->get('email') == null) {
        return redirect()->back()->with('error', [
            'title' => 'Akses Ditolak',
            'message' => 'Login untuk menggunakan fitur ini.',
        ]);
    }

        $data = [
            'title'           => $this->request->getPost('title'),
            'category'        => $this->request->getPost('category'),
            'priority'        => $this->request->getPost('priority'),
            'deadline'        => $this->request->getPost('deadline'),
            'target_duration' => $this->request->getPost('target_duration'),
            'description'     => $this->request->getPost('description'),
        ];

        $response = $this->fastApiService->createTask($data);

        if ($response->getStatusCode() == 201) {
            return redirect()->back()->with('success', [
                'title' => 'Tugas Berhasil Ditambahkan!',
                'message' => ''
            ]);
        } else {
            log_message('error', 'Error API Tugas: ' . $response->getBody());
            return redirect()->back()->with('error', [
                'title' => 'Terjadi Kesalahan!',
                'message' => 'Coba lagi nanti atau hubungi admin.',
                'detail' => $response->getBody()
            ]);
        }
    }

    public function updateTugas($id = null)
    {
        if (session()->get('email') == null) {
            return redirect()->back()->with('error', [
                'title' => 'Akses Ditolak',
                'message' => 'Login untuk menggunakan fitur ini.'
            ]);
        }

        $data = [
            'id'              => $id,
            'title'           => $this->request->getPost('title'),
            'category'        => $this->request->getPost('category'),
            'priority'        => $this->request->getPost('priority'),
            'deadline'        => $this->request->getPost('deadline'),
            'target_duration' => $this->request->getPost('target_duration'),
            'description'     => $this->request->getPost('description'),
        ];

        $response = $this->fastApiService->updateTask($data);

        if ($response->getStatusCode() == 200) {
            return redirect()->back()->with('success', [
                'title' => 'Tugas Berhasil Diupdate!',
                'message' => ''
            ]);
        } else {
            log_message('error', 'Error API Tugas: ' . $response->getBody());
            return redirect()->back()->with('error', [
                'title' => 'Terjadi Kesalahan!',
                'message' => 'Coba lagi nanti atau hubungi admin.',
                'detail' => $response->getBody()
            ]);
        }
    }

    public function toggleCompleteTugas($id = null)
    {
        if (session()->get('email') == null) {
            return redirect()->back()->with('error', [
                'title' => 'Akses Ditolak',
                'message' => 'Login untuk menggunakan fitur ini.'
            ]);
        }

        $response = $this->fastApiService->toggleCompleteTask([
            'id' => $id,
            'completed' => (bool) $this->request->getJSON()->completed
        ]);

        if ($response->getStatusCode() !== 200) {
            log_message('error', 'Error API Tugas: ' . $response->getBody());
        }
        
        return $response;
    }

    public function hapusTugas($id = null)
    {
        if (session()->get('email') == null) {
            return redirect()->back()->with('error', [
                'title' => 'Akses Ditolak',
                'message' => 'Login untuk menggunakan fitur ini.'
            ]);
        }

        $response = $this->fastApiService->deleteTask($id);

        if ($response->getStatusCode() == 200 || $response->getStatusCode() == 204) {
            return redirect()->to('/mahasiswa/tugas')->with('success', [
                'title' => 'Tugas Berhasil Dihapus!',
                'message' => ''
            ]);
        } else {
            log_message('error', 'Error API Tugas: ' . $response->getBody());
            return redirect()->to('/mahasiswa/tugas')->with('error', [
                'title' => 'Terjadi Kesalahan!',
                'message' => 'Coba lagi nanti atau hubungi admin.',
                'detail' => $response->getBody()
            ]);
        }
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