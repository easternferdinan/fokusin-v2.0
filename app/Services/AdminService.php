<?php

namespace App\Services;

use App\Libraries\FastApiClient;

class AdminService
{
    protected $client;

    public function __construct()
    {
        $this->client = new FastApiClient();
    }

    public function getMahasiswaData()
    {
        $response = $this->client->get('admin/mahasiswa', [
            'headers' => [
                'Authorization' => 'Bearer ' . session()->get('access_token')
            ]
        ]);

        return $response;
    }

    public function createMahasiswa(array $data)
    {
        return $this->client->post('admin/mahasiswa', [
            'headers' => [
                'Authorization' => 'Bearer ' . session()->get('access_token'),
                'Content-Type' => 'application/json',
            ],
            'json' => $data
        ]);
    }

    public function getMahasiswaStressAnalysis($userId, $page = 1, $size = 10)
    {
        return $this->client->get("admin/stress-analysis/{$userId}", [
            'headers' => [
                'Authorization' => 'Bearer ' . session()->get('access_token')
            ],
            'query' => [
                'page' => (int) $page,
                'size' => (int) $size,
            ]
        ]);
    }

    public function getDashboardData()
    {
        return $this->client->get('admin/dashboard', [
            'headers' => [
                'Authorization' => 'Bearer ' . session()->get('access_token')
            ]
        ]);
    }

    public function getStressTrendData($period)
    {
        return $this->client->get('admin/dashboard/stress-trend', [
            'headers' => [
                'Authorization' => 'Bearer ' . session()->get('access_token')
            ],
            'query' => [
                'period' => $period
            ]
        ]);
    }

    public function getAlertData()
    {
        return $this->client->get('admin/stress-alert', [
            'headers' => [
                'Authorization' => 'Bearer ' . session()->get('access_token')
            ]
        ]);
    }

    public function sendAlert(string $userId, $threshold, int $frequency)
    {
        return $this->client->post('admin/stress-alert', [
            'headers' => [
                'Authorization' => 'Bearer ' . session()->get('access_token'),
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'user_id' => $userId,
                'stress_threshold' => $threshold,
                'stress_threshold_frequency' => $frequency,
            ]
        ]);
    }
}
