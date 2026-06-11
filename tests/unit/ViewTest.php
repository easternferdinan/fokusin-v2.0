<?php

namespace Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\MahasiswaService;

class ViewTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    // Buat fake HTTP response untuk simulasi FastAPI
    private function mockResponse(int $statusCode, array $body)
    {
        $response = $this->getMockBuilder(\CodeIgniter\HTTP\IncomingRequest::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mock = $this->getMockBuilder(\GuzzleHttp\Psr7\Response::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mock->method('getStatusCode')->willReturn($statusCode);
        $mock->method('getBody')->willReturn(json_encode($body));

        return $mock;
    }

    // ✅ Test 1: Landing page (tidak butuh backend)
    public function testLandingPageLoads()
    {
        $result = $this->get('/');
        $result->assertStatus(200);
        $result->assertSee('Fokusin');        // cek teks muncul di halaman
        $result->assertSeeElement('nav');     // cek elemen HTML ada
    }

    public function testLoginPageLoads()
    {
        $result = $this->get('auth/login');
        $result->assertStatus(200);
        $result->assertSee('Login');
        $result->assertSeeElement('form');
    }

    // Test redirect kalau belum login
    // public function testDashboardRedirectIfNotLoggedIn()
    // {
    //     $result = $this->get('mahasiswa');
    //     // Harusnya redirect ke login, bukan 200
    //     $result->assertRedirectTo(base_url('auth/login'));
    // }

    // Test halaman admin login ada
    public function testAdminLoginPageLoads()
    {
        $result = $this->get('auth/adminLogin');
        $result->assertStatus(200);
    }

    // ✅ Test 3: Pomodoro (tidak butuh backend, langsung return view)
    public function testPomodoroPageLoads()
    {
        $result = $this->withSession([
            'fullname'     => 'Test User',
            'email'        => 'test@test.com',
            'role'         => 'mahasiswa',
            'access_token' => 'fake-token',
        ])->get('mahasiswa/pomodoro');

        $result->assertStatus(200);
    }

    // ✅ Test 4: Pengaturan (tidak butuh backend, langsung return view)
    public function testPengaturanPageLoads()
    {
        $result = $this->withSession([
            'fullname'     => 'Test User',
            'email'        => 'test@test.com',
            'role'         => 'mahasiswa',
            'access_token' => 'fake-token',
        ])->get('mahasiswa/pengaturan');

        $result->assertStatus(200);
    }
}