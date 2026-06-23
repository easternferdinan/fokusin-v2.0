<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class FastApi extends BaseConfig
{
    private string $baseUrl;

    private const WRITABLE_FILE = 'fastapi.json';

    public function __construct()
    {
        $data = $this->readConfig();

        if ($data['api_base_url'] === '') {
            $this->baseUrl = env('API_URL');
        } else {
            $this->baseUrl = $data['api_base_url'];
        }
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    public function setBaseUrl(string $newBaseUrl): void
    {
        $this->baseUrl = $newBaseUrl;
        $data = $this->readConfig();
        $data['api_base_url'] = $newBaseUrl;
        file_put_contents(WRITEPATH . self::WRITABLE_FILE, json_encode($data));
    }

    private function readConfig(): array
    {
        $writableFile = WRITEPATH . self::WRITABLE_FILE;

        if (is_file($writableFile)) {
            return json_decode(file_get_contents($writableFile), true);
        }

        return json_decode(file_get_contents(APPPATH . 'Config/FastApi.json'), true);
    }
}
