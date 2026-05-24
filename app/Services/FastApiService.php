<?php

namespace App\Services;

use App\Libraries\FastApiClient;

class FastApiService
{
    protected $client;

    public function __construct()
    {
        $this->client = new FastApiClient();
    }

    public function registerUser(array $userData)
    {
        $response = $this->client->post('auth/register', [
            'json' => $userData
        ]);
        return $response;
    }

    public function loginUser(array $userData)
    {
        $response = $this->client->post('auth/login', [
            'json' => $userData
        ]);

        // Simpan info user ke session agar bisa dipanggil di controller mahasiswa
        $responseData = json_decode($response->getBody(), true);

        session()->set([
            'fullname' => $responseData['fullname'],
            'username' => $responseData['username'],
            'email' => $responseData['email'],
            'mental_health_history' => $responseData['mental_health_history'],
            'academic_performance' => $responseData['academic_performance'],
            'social_support' => $responseData['social_support'],
            'access_token' => $responseData['access_token'] ?? null,
            'role' => 'mahasiswa' // TODO: Adjust role's value according to backend enums
        ]);
        return $response;
    }

    public function updateProfile(array $userData)
    {
        $response = $this->client->put('auth/update', [
            'json' => $userData,
            'headers' => [
                'Authorization' => 'Bearer ' . session()->get('access_token')
            ]
        ]);

        if ($response->getStatusCode() == 200) {
            $responseData = json_decode($response->getBody(), true);
            session()->set([
                'fullname' => $responseData['fullname'],
                'username' => $responseData['username'],
                'email' => $responseData['email'],
                'mental_health_history' => $responseData['mental_health_history'],
                'academic_performance' => $responseData['academic_performance'],
                'social_support' => $responseData['social_support']
            ]);
        }

        return $response;
    }

    public function getTasks()
    {
        $response = $this->client->get('tasks', [
            'headers' => [
                'Authorization' => 'Bearer ' . session()->get('access_token')
            ]
        ]);
        return $response;
    }
}
