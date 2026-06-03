<?php

namespace App\Services;

use App\Libraries\FastApiClient;

class AuthService
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

        if ($response->getStatusCode() !== 200) {
            return $response;
        }

        $responseData = json_decode($response->getBody(), true);

        session()->set([
            'fullname' => $responseData['fullname'],
            'username' => $responseData['username'],
            'email' => $responseData['email'],
            'mental_health_history' => $responseData['mental_health_history'],
            'academic_performance' => $responseData['academic_performance'],
            'social_support' => $responseData['social_support'],
            'access_token' => $responseData['access_token'] ?? null,
            'role' => $responseData['role']
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
}
