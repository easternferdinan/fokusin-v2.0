<?php

namespace App\Services;

use App\Libraries\FastApiClient;

class FastApiService
{
    protected $client;

    public function __construct()
    {
        $this->client = new FastApiClient();
    }

    // ==================== USER ====================

    public function registerUser(array $userData)
    {
        $response = $this->client->post('auth/register', [
            'json' => $userData
        ]);
        return $response;
    }

    public function loginUser(array $userData)
    {
        $response = $this->client->post('auth/login', [
            'json' => $userData
        ]);

        if ($response->getStatusCode() !== 200) {
            return $response;
        }

        // Simpan info user ke session agar bisa dipanggil di controller mahasiswa
        $responseData = json_decode($response->getBody(), true);

        session()->set([
            'fullname' => $responseData['fullname'],
            'username' => $responseData['username'],
            'email' => $responseData['email'],
            'mental_health_history' => $responseData['mental_health_history'],
            'academic_performance' => $responseData['academic_performance'],
            'social_support' => $responseData['social_support'],
            'access_token' => $responseData['access_token'] ?? null,
            'role' => $responseData['role']
        ]);
        return $response;
    }

    public function updateProfile(array $userData)
    {
        $response = $this->client->put('auth/update', [
            'json' => $userData,
            'headers' => [
                'Authorization' => 'Bearer ' . session()->get('access_token')
            ]
        ]);

        if ($response->getStatusCode() == 200) {
            $responseData = json_decode($response->getBody(), true);
            session()->set([
                'fullname' => $responseData['fullname'],
                'username' => $responseData['username'],
                'email' => $responseData['email'],
                'mental_health_history' => $responseData['mental_health_history'],
                'academic_performance' => $responseData['academic_performance'],
                'social_support' => $responseData['social_support']
            ]);
        }

        return $response;
    }

    // ================================= DASHBOARD =============================

    public function getDashboardData()
    {
        $response = $this->client->get('dashboard/', [
            'headers' => [
                'Authorization' => 'Bearer ' . session()->get('access_token')
            ]
        ]);

        return $response;
    }

    // =============================== TASK ==============================

    public function getTasks()
    {
        $response = $this->client->get('tasks/', [
            'headers' => [
                'Authorization' => 'Bearer ' . session()->get('access_token')
            ]
        ]);

        return $response;
    }

    public function createTask(array $taskData)
    {
        $response = $this->client->post('tasks/', [
            'json' => $taskData,
            'headers' => [
                'Authorization' => 'Bearer ' . session()->get('access_token')
            ]
        ]);

        return $response;
    }

    public function updateTask(array $taskData)
    {
        $response = $this->client->put('tasks/' . $taskData['id'], [
            'json' => $taskData,
            'headers' => [
                'Authorization' => 'Bearer ' . session()->get('access_token')
            ]
        ]);

        return $response;
    }

    public function toggleCompleteTask(array $taskData)
    {
        $response = $this->client->patch('tasks/' . $taskData['id'] . '/complete', [
            'json' => ['completed' => (bool)$taskData['completed']],
            'headers' => [
                'Authorization' => 'Bearer ' . session()->get('access_token')
            ]
        ]);

        return $response;
    }

    public function deleteTask($id)
    {
        $response = $this->client->delete('tasks/' . $id, [
            'headers' => [
                'Authorization' => 'Bearer ' . session()->get('access_token')
            ]
        ]);

        return $response;
    }

    // =============================== POMODORO =============================

    public function createPomodoro(array $pomodoroData)
    {
        $response = $this->client->post('pomodoro/', [
            'json' => $pomodoroData,
            'headers' => [
                'Authorization' => 'Bearer ' . session()->get('access_token')
            ]
        ]);

        return $response;
    }

    public function pausePomodoro($id)
    {
        $response = $this->client->patch('pomodoro/' . $id . '/pause', [
            'headers' => [
                'Authorization' => 'Bearer ' . session()->get('access_token')
            ]
        ]);

        return $response;
    }

    public function resumePomodoro($id)
    {
        $response = $this->client->patch('pomodoro/' . $id . '/resume', [
            'headers' => [
                'Authorization' => 'Bearer ' . session()->get('access_token')
            ]
        ]);

        return $response;
    }

    public function completePomodoro($id)
    {
        $response = $this->client->patch('pomodoro/' . $id . '/complete', [
            'headers' => [
                'Authorization' => 'Bearer ' . session()->get('access_token')
            ]
        ]);

        return $response;
    }

    // =============================== AI ANALYSIS =============================

    public function getAllAnalysisData()
    {
        $response = $this->client->get('analysis/', [
            'headers' => [
                'Authorization' => 'Bearer ' . session()->get('access_token')
            ]
        ]);

        return $response;
    }

    public function checkAnalysisRequirementsStatus()
    {
        $response = $this->client->get('analysis/requirements-status', [
            'headers' => [
                'Authorization' => 'Bearer ' . session()->get('access_token')
            ]
        ]);

        return $response;
    }

    public function createStressAnalysis(array $analysisData)
    {
        $response = $this->client->post('analysis/', [
            'json' => $analysisData,
            'headers' => [
                'Authorization' => 'Bearer ' . session()->get('access_token')
            ]
        ]);

        return $response;
    }

    // =============================== REPORT =============================

    public function getReportData()
    {
        $response = $this->client->get('report/', [
            'headers' => [
                'Authorization' => 'Bearer ' . session()->get('access_token')
            ]
        ]);

        return $response;
    }

    public function getStressTrend(string $period)
    {
        $response = $this->client->get('report/stress-trend', [
            'query' => [
                'period' => $period
            ],
            'headers' => [
                'Authorization' => 'Bearer ' . session()->get('access_token')
            ]
        ]);

        return $response;
    }
}
