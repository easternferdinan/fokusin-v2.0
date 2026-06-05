<?php

namespace App\Libraries;

use Config\FastApi;

/**
 * This class is used to make HTTP requests to the FastAPI server.
 * It is meant to be used by services and libraries, not to be used directly by controllers.
 */
class FastApiClient
{
    private FastApi $fastApiConfig;
    protected string $baseUrl;
    protected \CodeIgniter\HTTP\CURLRequest $client;

    public function __construct()
    {
        $this->fastApiConfig = config(FastApi::class);
        $this->baseUrl = $this->fastApiConfig->getBaseUrl();
        $this->client = \Config\Services::curlrequest([
            'http_errors' => false,
        ]);
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    public function setBaseUrl(string $baseUrl): void
    {
        $this->fastApiConfig->setBaseUrl($baseUrl);
        $this->baseUrl = $this->fastApiConfig->getBaseUrl();
    }

    public function get(string $endpoint, array $params = [])
    {
        return $this->client->get($this->baseUrl . $endpoint, $params);
    }

    public function post(string $endpoint, array $params = [])
    {
        return $this->client->post($this->baseUrl . $endpoint, $params);
    }

    public function put(string $endpoint, array $params = [])
    {
        return $this->client->put($this->baseUrl . $endpoint, $params);
    }

    public function delete(string $endpoint, array $params = [])
    {
        return $this->client->delete($this->baseUrl . $endpoint, $params);
    }

    public function patch(string $endpoint, array $params = [])
    {
        return $this->client->patch($this->baseUrl . $endpoint, $params);
    }
}
