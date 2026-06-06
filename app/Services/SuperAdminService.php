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

    public function getAdmins()
    {
        return $this->client->get('super-admin/admins', [
            'headers' => [
                'Authorization' => 'Bearer ' . session()->get('access_token'),
            ],
        ]);
    }

    public function createAdmin(array $data)
    {
        return $this->client->post('super-admin/admins', [
            'headers' => [
                'Authorization' => 'Bearer ' . session()->get('access_token'),
                'Content-Type' => 'application/json',
            ],
            'json' => $data,
        ]);
    }

    public function updateAdmin(string $id, array $data)
    {
        return $this->client->put("super-admin/admins/{$id}", [
            'headers' => [
                'Authorization' => 'Bearer ' . session()->get('access_token'),
                'Content-Type' => 'application/json',
            ],
            'json' => $data,
        ]);
    }

    public function deleteAdmin(string $id)
    {
        return $this->client->delete("super-admin/admins/{$id}", [
            'headers' => [
                'Authorization' => 'Bearer ' . session()->get('access_token'),
            ],
        ]);
    }

    public function getLogs(?string $level = null, ?string $eventType = null, int $limit = 100, int $skip = 0)
    {
        $query = [
            'limit' => $limit,
            'skip' => $skip,
        ];
        if ($level !== null) {
            $query['level'] = $level;
        }
        if ($eventType !== null) {
            $query['event_type'] = $eventType;
        }

        return $this->client->get('logs/', [
            'headers' => [
                'Authorization' => 'Bearer ' . session()->get('access_token'),
            ],
            'query' => $query,
        ]);
    }

    public function exportDatabase()
    {
        return $this->client->get('super-admin/export-db', [
            'headers' => [
                'Authorization' => 'Bearer ' . session()->get('access_token'),
            ],
        ]);
    }

    public function updateBaseUrl(string $baseUrl)
    {
        $this->client->setBaseUrl($baseUrl);
    }
}
