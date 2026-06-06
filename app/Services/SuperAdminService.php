<?php

namespace App\Services;

use App\Libraries\FastApiClient;

class SuperAdminService
{
    protected $client;

    public function __construct()
    {
        $this->client = new FastApiClient();
    }

    public function getConfig()
    {
        return $this->client->get('super-admin/config', [
            'headers' => [
                'Authorization' => 'Bearer ' . session()->get('access_token'),
            ],
        ]);
    }

    public function updateConfig(array $data)
    {
        return $this->client->put('super-admin/config', [
            'headers' => [
                'Authorization' => 'Bearer ' . session()->get('access_token'),
                'Content-Type' => 'application/json',
            ],
            'json' => $data,
        ]);
    }

    public function updateBaseUrl(string $baseUrl)
    {
        $this->client->setBaseUrl($baseUrl);
    }
}
