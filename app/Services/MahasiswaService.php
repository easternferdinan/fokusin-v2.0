<?php

namespace App\Services;

use App\Libraries\FastApiClient;

class MahasiswaService
{
    protected $client;

    public function __construct()
    {
        $this->client = new FastApiClient();
    }

    public function getDashboardData()
    {
        $response = $this->client->get('dashboard/', [
            'headers' => [
                'Authorization' => 'Bearer ' . session()->get('access_token')
            ]
        ]);

        return $response;
    }

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

    public function getReportData()
    {
        $response = $this->client->get('report/', [
            'headers' => [
                'Authorization' => 'Bearer ' . session()->get('access_token')
            ]
        ]);

        return $response;
    }

    public function getNotifications()
    {
        $response = $this->client->get('notifications/', [
            'headers' => [
                'Authorization' => 'Bearer ' . session()->get('access_token')
            ]
        ]);

        return $response;
    }

    public function markNotificationRead(string $notificationId, string $message)
    {
        $response = $this->client->put('notifications/' . $notificationId, [
            'json' => [
                'message' => $message,
                'is_read' => true
            ],
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
