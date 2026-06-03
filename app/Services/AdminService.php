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
}
