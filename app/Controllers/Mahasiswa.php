<?php

namespace App\Controllers;

use App\Services\MahasiswaService;
use App\Services\AuthService;
use App\Services\AssesmentDataConverter;

class Mahasiswa extends BaseController
{
    protected $mahasiswaService;
    protected $authService;
    protected $assesmentDataConverter;

    public function __construct()
    {
        helper('auth');
        $this->mahasiswaService = new MahasiswaService();
        $this->authService = new AuthService();
        $this->assesmentDataConverter = new AssesmentDataConverter();
    }

    // ====================================================================================================
    // DASHBOARD
    // ====================================================================================================
    public function index()
    {
        $this->cacheCheckinStatus();

        $response = $this->mahasiswaService->getDashboardData();
                    
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
                    'namaMahasiswa' => session()->get('fullname'),
                    'hasCheckedIn'  => session()->get('checked_in_today', false),
                ];

                $notifResponse = $this->mahasiswaService->getNotifications();
                if ($notifResponse->getStatusCode() === 200) {
                    $notifications = json_decode($notifResponse->getBody(), true);
                    foreach ($notifications as $notif) {
                        if (!$notif['is_read']) {
                            $this->mahasiswaService->markNotificationRead($notif['notification_id'], $notif['message']);
                            $data['notification'] = $notif;
                            break;
                        }
                    }
                }

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
        $this->cacheCheckinStatus();

        $response = $this->mahasiswaService->getTasks();

        if (session()->get('email') == null || $response->getStatusCode() == 401) {
            return denyAccess();
        }

        if ($response->getStatusCode() == 200) {
            $tasks = json_decode($response->getBody());

            $data = [
                'tasks' => $tasks,
                'namaMahasiswa' => session()->get('fullname'),
                'hasCheckedIn'  => session()->get('checked_in_today', false),
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

        $response = $this->mahasiswaService->createTask($data);

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

        $response = $this->mahasiswaService->updateTask($data);

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

        $response = $this->mahasiswaService->toggleCompleteTask([
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

        $response = $this->mahasiswaService->deleteTask($id);

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
        $this->cacheCheckinStatus();

        $data = [
            // Jika login, ambil nama dari session. Jika tidak, beri nama 'Guest'
            'namaMahasiswa' => session()->get('fullname') ?? 'Guest',
            'hasCheckedIn'  => session()->get('checked_in_today', false),
        ];
        
        return view('mahasiswa/pomodoro', $data);
    }

    public function createPomodoro()
    {
        $response = $this->mahasiswaService->createPomodoro([
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
        $response = $this->mahasiswaService->pausePomodoro($id);

        if ($response->getStatusCode() !== 200) {
            log_message('error', 'Error API Pomodoro: ' . $response->getBody());
        }

        return $response;
    }

    public function resumePomodoro($id = null)
    {
        $response = $this->mahasiswaService->resumePomodoro($id);

        if ($response->getStatusCode() !== 200) {
            log_message('error', 'Error API Pomodoro: ' . $response->getBody());
        }

        return $response;
    }

    public function completePomodoro($id = null)
    {
        $response = $this->mahasiswaService->completePomodoro($id);

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

        $requirementsResponse = $this->mahasiswaService->checkAnalysisRequirementsStatus();

        if ($requirementsResponse->getStatusCode() !== 200) {
            log_message('error', 'Error API Requirements Status: ' . $requirementsResponse->getBody());
            return redirect()->back()->with('error', [
                'title' => 'Terjadi Kesalahan!',
                'message' => 'Coba lagi nanti atau hubungi admin.',
                'detail' => $requirementsResponse->getBody()
            ]);
        }

        $reportResponse = $this->mahasiswaService->getReportData();

        if ($reportResponse->getStatusCode() !== 200) {
            log_message('error', 'Error API Report Data: ' . $reportResponse->getBody());
            return redirect()->back()->with('error', [
                'title' => 'Terjadi Kesalahan!',
                'message' => 'Coba lagi nanti atau hubungi admin.',
                'detail' => $reportResponse->getBody()
            ]);
        }

        $analysisRequirements = json_decode($requirementsResponse->getBody(), true);
        $reportData = json_decode($reportResponse->getBody(), true);

        // Cache check-in status so the layout can conditionally render the FAB
        session()->set('checked_in_date', date('Y-m-d'));
        session()->set('checked_in_today', (bool)($analysisRequirements['stress_assesment_done_today'] ?? false));

        $data = [
            'title'           => 'Report AI',
            'namaMahasiswa'   => session()->get('fullname') ?? 'Guest',
            'hasTasks'        => $analysisRequirements['task_done_today'],
            'hasPomodoro'     => $analysisRequirements['pomodoro_done_today'],
            'hasFilledInputs' => $analysisRequirements['stress_assesment_done_today'],
            'hasCheckedIn'    => session()->get('checked_in_today', false),
            'latestAnalysis'  => $reportData['all_stress_analysis'][0] ?? null,
            'stressCategory'  => $reportData['all_stress_analysis'][0]['stress_level'] ?? null,
            'potentialStressFactors' => $reportData['potential_stress_factors'] ?? null,
            'recommendations' => $reportData['recommendations'] ?? null,
            'allStressData'   => $reportData['all_stress_analysis'] ?? null,
        ];
        
        return view('mahasiswa/report_ai', $data);
    }
    
    public function getStressTrend()
    {
        if (session()->get('email') == null) {
            return $this->response->setStatusCode(401)->setJSON(['message' => 'Unauthorized']);
        }

        $period = $this->request->getGet('period') ?? 'harian';
        $response = $this->mahasiswaService->getStressTrend($period);

        if ($response->getStatusCode() !== 200) {
            log_message('error', 'Error API Stress Trend: ' . $response->getBody());
            return $this->response->setStatusCode($response->getStatusCode())->setJSON(json_decode($response->getBody()));
        }

        return $this->response->setJSON(json_decode($response->getBody()));
    }

    private function cacheCheckinStatus(): void
    {
        $session = session();
        $today = date('Y-m-d');

        if ($session->get('checked_in_date') !== $today) {
            $response = $this->mahasiswaService->checkAnalysisRequirementsStatus();
            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody(), true);
                $session->set('checked_in_date', $today);
                $session->set('checked_in_today', (bool)($data['stress_assesment_done_today'] ?? false));
            }
        }
    }

    public function checkTodayCheckin()
    {
        $response = $this->mahasiswaService->checkAnalysisRequirementsStatus();
        if ($response->getStatusCode() !== 200) {
            return $this->response->setJSON(['checked_in' => false]);
        }
        $data = json_decode($response->getBody(), true);
        return $this->response->setJSON([
            'checked_in' => (bool)($data['stress_assesment_done_today'] ?? false)
        ]);
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

        $response = $this->mahasiswaService->createStressAnalysis($data);

        if ($response->getStatusCode() !== 201) {
            log_message('error', 'Error API Stress Analysis: ' . $response->getBody());
            return redirect()->to(base_url('mahasiswa/report'))->with('error', [
                'title' => 'Gagal menyimpan check-in',
                'message' => 'Analisis stres Anda gagal disimpan.',
                'detail' => $response->getBody()
            ]);
        }

        // Cache check-in status immediately so FAB is hidden on redirect
        session()->set('checked_in_today', true);
        session()->set('checked_in_date', date('Y-m-d'));

        return redirect()->to(base_url('mahasiswa/report'));
    }

    // ====================================================================================================
    // PENGATURAN
    // ====================================================================================================
    public function pengaturan()
    {
        $this->cacheCheckinStatus();

        $data = [
            'namaMahasiswa' => session()->get('fullname'),
            'hasCheckedIn'  => session()->get('checked_in_today', false),
        ];
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

        $response = $this->authService->updateProfile($data);

        if ($response->getStatusCode() == 200) {
            return redirect()->to(base_url('mahasiswa/pengaturan'))->with('success', [
                'title' => 'Profile Berhasil Diupdate!',
                'message' => 'Profile Anda telah berhasil diperbarui.'
            ]);
        }

        return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui profile. Silahkan coba lagi.<br>' . $response->getBody());
    }
}