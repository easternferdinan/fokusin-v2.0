<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class FastApi extends BaseConfig
{
    private string $baseUrl;

    public function __construct()
    {
        $json = file_get_contents(APPPATH . 'Config/FastApi.json');
        $data = json_decode($json, true);

        if ($data['api_base_url'] == '') {
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
        $json = file_get_contents(APPPATH . 'Config/FastApi.json');
        $data = json_decode($json, true);
        $data['api_base_url'] = $newBaseUrl;
        file_put_contents(APPPATH . 'Config/FastApi.json', json_encode($data));
    }
}
