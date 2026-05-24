<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class FastApi extends BaseConfig
{
    public string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = env('API_URL');
    }
}
