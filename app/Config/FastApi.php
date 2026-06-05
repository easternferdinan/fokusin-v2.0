<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class FastApi extends BaseConfig
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = env('API_URL');
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    public function setBaseUrl(string $baseUrl): void
    {
        $this->baseUrl = $baseUrl;
    }
}
