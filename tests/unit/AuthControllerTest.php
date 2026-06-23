<?php

namespace Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\HTTP\CURLRequest;
use CodeIgniter\HTTP\Response;
use Config\App;

final class AuthControllerTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    protected function tearDown(): void
    {
        $this->resetServices();
        parent::tearDown();
    }

    private function mockApiResponse(int $statusCode, array $body): void
    {
        $response = new Response(new App());
        $response->setStatusCode($statusCode);
        $response->setBody(json_encode($body));

        $curl = $this->createMock(CURLRequest::class);
        $curl->method('post')->willReturn($response);

        \Config\Services::injectMock('curlrequest', $curl);
    }

    public function testLoginProcessSuccess(): void
    {
        $this->mockApiResponse(200, [
            'fullname'              => 'Test User',
            'username'              => 'testuser',
            'email'                 => 'test@test.com',
            'mental_health_history' => false,
            'academic_performance'  => 80,
            'social_support'        => 70,
            'access_token'          => 'fake-token-123',
            'role'                  => 'mahasiswa',
        ]);

        $result = $this->post('auth/loginProcess', [
            'username' => 'testuser',
            'password' => 'correct_password',
        ]);

        $result->assertRedirectTo(base_url('mahasiswa'));

        $this->assertSame('Test User', session()->get('fullname'));
        $this->assertSame('testuser', session()->get('username'));
        $this->assertSame('fake-token-123', session()->get('access_token'));
        $this->assertSame('mahasiswa', session()->get('role'));
    }

    public function testLoginProcessFail(): void
    {
        $this->mockApiResponse(401, [
            'detail' => 'Username atau password salah.',
        ]);

        $result = $this->post('auth/loginProcess', [
            'username' => 'testuser',
            'password' => 'wrong_password',
        ]);

        $result->assertRedirect();
    }
}
