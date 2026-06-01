<?php

namespace App\Controllers;

use App\Services\FastApiService;
use App\Services\AssesmentDataConverter;

class Mahasiswa extends BaseController
{
    protected $fastApiService;
    protected $assesmentDataConverter;

    public function __construct()
    {
        helper('auth');
        $this->fastApiService = new FastApiService();
        $this->assesmentDataConverter = new AssesmentDataConverter();
    }

    // ====================================================================================================
    // DASHBOARD
    // ====================================================================================================
    public function index()
    {
        $response = $this->fastApiService->getDashboardData();
                    
        if (session()->get('email') == null || $response->getStatusCode() == 401) {
            return denyAccess();
        }

        if ($response->getStatusCode() == 200) {
            $dashboardData = json_decode($response->getBody());
    
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

    // ====================================================================================================
    // TUGAS
    // ====================================================================================================
    public function tugas()
    {
        $response = $this->fastApiService->getTasks();

        if (session()->get('email') == null || $response->getStatusCode() == 401) {
            return denyAccess();
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

    public function simpanTugas()
    {
        if (session()->get('email') == null) {
        return denyAccess();
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
            return denyAccess();
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
            return denyAccess();
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
            return denyAccess();
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

    // ====================================================================================================
    // POMODORO
    // ====================================================================================================
    public function pomodoro()
    {
        $data = [
            // Jika login, ambil nama dari session. Jika tidak, beri nama 'Guest'
            'namaMahasiswa' => session()->get('fullname') ?? 'Guest',
        ];
        
        return view('mahasiswa/pomodoro', $data);
    }

    public function createPomodoro()
    {
        $response = $this->fastApiService->createPomodoro([
            'title' => $this->request->getJSON()->title,
            'status' => 'active',
            'duration' => (int) $this->request->getJSON()->duration,
            'break_duration' => (int) $this->request->getJSON()->break_duration
        ]);

        if ($response->getStatusCode() !== 201) {
            log_message('error', 'Error API Pomodoro: ' . $response->getBody());
        }

        return $response;
    }

    public function pausePomodoro($id = null)
    {
        $response = $this->fastApiService->pausePomodoro($id);

        if ($response->getStatusCode() !== 200) {
            log_message('error', 'Error API Pomodoro: ' . $response->getBody());
        }

        return $response;
    }

    public function resumePomodoro($id = null)
    {
        $response = $this->fastApiService->resumePomodoro($id);

        if ($response->getStatusCode() !== 200) {
            log_message('error', 'Error API Pomodoro: ' . $response->getBody());
        }

        return $response;
    }

    public function completePomodoro($id = null)
    {
        $response = $this->fastApiService->completePomodoro($id);

        if ($response->getStatusCode() !== 200) {
            log_message('error', 'Error API Pomodoro: ' . $response->getBody());
        }

        return $response;
    }

    // ====================================================================================================
    // REPORT
    // ====================================================================================================
    public function report()
    {
        if (session()->get('email') == null) {
            return denyAccess();
        }

        $requirementsResponse = $this->fastApiService->checkAnalysisRequirementsStatus();

        if ($requirementsResponse->getStatusCode() !== 200) {
            log_message('error', 'Error API Requirements Status: ' . $requirementsResponse->getBody());
            return redirect()->back()->with('error', [
                'title' => 'Terjadi Kesalahan!',
                'message' => 'Coba lagi nanti atau hubungi admin.',
                'detail' => $requirementsResponse->getBody()
            ]);
        }

        $allAnalysisResponse = $this->fastApiService->getAllAnalysisData();

        if ($allAnalysisResponse->getStatusCode() !== 200) {
            log_message('error', 'Error API All Analysis Data: ' . $allAnalysisResponse->getBody());
            return redirect()->back()->with('error', [
                'title' => 'Terjadi Kesalahan!',
                'message' => 'Coba lagi nanti atau hubungi admin.',
                'detail' => $allAnalysisResponse->getBody()
            ]);
        }

        $analysisRequirements = json_decode($requirementsResponse->getBody(), true);
        $allAnalysisData = json_decode($allAnalysisResponse->getBody(), true);

        // TODO: Add option for user to retake the stress assesment, if already taken.
        // TODO: Add update stress analysis endpoint (in case user wants to retake the stress analysis)

        // TODO: Add endpoints to retrieve 'feature importance'

        // TODO: Add recommendation algorithm based on user's data (tasks, pomodoro, sleep quality, etc.)

        $data = [
            'title'           => 'Report AI',
            'namaMahasiswa'   => session()->get('fullname') ?? 'Guest',
            'hasTasks'        => $analysisRequirements['task_done_today'],
            'hasPomodoro'     => $analysisRequirements['pomodoro_done_today'],
            'hasFilledInputs' => $analysisRequirements['stress_assesment_done_today'],
            'allStressData'   => $allAnalysisData,
            'latestAnalysis'  => $allAnalysisData[0] ?? null,
            'stressCategory'  => $allAnalysisData[0]['stress_level'] ?? null, // Give latest stress level analysis to be presented
        ];

        return view('mahasiswa/report_ai', $data);
    }

    public function saveCheckin()
    {
        // Tangkap data dari JS
        $sleep      = $this->request->getPost('sleep_quality');
        $esteem     = $this->request->getPost('self_esteem_pct');
        $depression = $this->request->getPost('depression_pct');
        $headache   = $this->request->getPost('headache');

        // (Proses simpan ke database di sini...)

        $selfEsteemScore = $this->assesmentDataConverter->convertSelfEsteem($esteem);
        $depressionScore = $this->assesmentDataConverter->convertDepression($depression);

        $data = [
            'sleep_quality' => $sleep,
            'self_esteem' => $selfEsteemScore,
            'depression' => $depressionScore,
            'headache' => $headache
        ];

        $response = $this->fastApiService->createStressAnalysis($data);

        if ($response->getStatusCode() !== 201) {
            log_message('error', 'Error API Stress Analysis: ' . $response->getBody());
            return redirect()->to(base_url('mahasiswa/report'))->with('error', [
                'title' => 'Gagal menyimpan check-in',
                'message' => 'Analisis stres Anda gagal disimpan.',
                'detail' => $response->getBody()
            ]);
        }

        // Beri balasan ke JS bahwa data sudah aman
        return redirect()->to(base_url('mahasiswa/report'));
    }

    // ====================================================================================================
    // PENGATURAN
    // ====================================================================================================
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
}