<?php

namespace Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\HTTP\CURLRequest;
use CodeIgniter\HTTP\Response;
use Config\App;

final class MahasiswaControllerTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    private function mockApiResponses(array $responseMap): void
    {
        $curl = $this->createMock(CURLRequest::class);

        $curl->method('get')->willReturnCallback(function ($url) use ($responseMap) {
            foreach ($responseMap as $pattern => $response) {
                if (str_contains($url, $pattern)) {
                    return $response;
                }
            }
            $fallback = new Response(new App());
            $fallback->setStatusCode(200);
            $fallback->setBody(json_encode([]));
            return $fallback;
        });

        $postResponse = new Response(new App());
        $postResponse->setStatusCode(201);
        $postResponse->setBody(json_encode(['id' => '123']));
        $curl->method('post')->willReturn($postResponse);

        \Config\Services::injectMock('curlrequest', $curl);
    }

    protected function tearDown(): void
    {
        $this->resetServices();
        parent::tearDown();
    }

    public function testDashboardWithoutSession(): void
    {
        $r = new Response(new App());
        $r->setStatusCode(401);
        $r->setBody(json_encode(['message' => 'Unauthorized']));

        $this->mockApiResponses(['dashboard' => $r]);

        $result = $this->get('mahasiswa');
        $result->assertRedirect();
    }

    public function testDashboardWithData(): void
    {
        $dashboardBody = [
            'today_pomodoro_minutes'           => 25,
            'latest_burnout_prediction'        => 'Rendah',
            'incomplete_tasks_count'           => 3,
            'high_priority_tasks_count'        => 1,
            'deadline_urgent_tasks_count'      => 0,
            'deadline_urgent_tasks'            => [],
        ];
        $dashboardRes = new Response(new App());
        $dashboardRes->setStatusCode(200);
        $dashboardRes->setBody(json_encode($dashboardBody));

        $notifRes = new Response(new App());
        $notifRes->setStatusCode(200);
        $notifRes->setBody(json_encode([]));

        $this->mockApiResponses([
            'dashboard'  => $dashboardRes,
            'requirement' => $notifRes,
            'notification' => $notifRes,
        ]);

        $result = $this->withSession([
            'fullname'     => 'Test User',
            'email'        => 'test@test.com',
            'role'         => 'mahasiswa',
            'access_token' => 'fake-token',
        ])->get('mahasiswa');

        $result->assertStatus(200);
        $result->assertSee('Test User');
    }

    public function testDashboardApiError(): void
    {
        $r = new Response(new App());
        $r->setStatusCode(500);
        $r->setBody(json_encode(['error' => 'Internal Server Error']));

        $this->mockApiResponses(['dashboard' => $r]);

        $result = $this->withSession([
            'fullname'     => 'Test User',
            'email'        => 'test@test.com',
            'role'         => 'mahasiswa',
            'access_token' => 'fake-token',
        ])->get('mahasiswa');

        $result->assertRedirect();
    }

    public function testSaveCheckinSuccess(): void
    {
        $r = new Response(new App());
        $r->setStatusCode(201);
        $r->setBody(json_encode(['status' => 'created']));

        $curl = $this->createMock(CURLRequest::class);
        $curl->method('post')->willReturn($r);

        \Config\Services::injectMock('curlrequest', $curl);

        $result = $this->withSession([
            'fullname'     => 'Test User',
            'email'        => 'test@test.com',
            'role'         => 'mahasiswa',
            'access_token' => 'fake-token',
        ])->post('mahasiswa/saveCheckin', [
            'sleep_quality'   => 'Baik',
            'self_esteem_pct' => 80,
            'depression_pct'  => 20,
            'headache'        => 'Tidak',
        ]);

        $result->assertRedirectTo(base_url('mahasiswa/report'));
        $this->assertTrue(session()->get('checked_in_today'));
    }

    public function testSaveCheckinFail(): void
    {
        $r = new Response(new App());
        $r->setStatusCode(422);
        $r->setBody(json_encode(['detail' => 'Validation failed']));

        $curl = $this->createMock(CURLRequest::class);
        $curl->method('post')->willReturn($r);

        \Config\Services::injectMock('curlrequest', $curl);

        $result = $this->withSession([
            'fullname'     => 'Test User',
            'email'        => 'test@test.com',
            'role'         => 'mahasiswa',
            'access_token' => 'fake-token',
        ])->post('mahasiswa/saveCheckin', [
            'sleep_quality'   => '',
            'self_esteem_pct' => null,
            'depression_pct'  => null,
            'headache'        => '',
        ]);

        $result->assertRedirectTo(base_url('mahasiswa/report'));
    }

    public function testSimpanTugasSuccess(): void
    {
        $r = new Response(new App());
        $r->setStatusCode(201);
        $r->setBody(json_encode(['id' => '123']));

        $curl = $this->createMock(CURLRequest::class);
        $curl->method('post')->willReturn($r);

        \Config\Services::injectMock('curlrequest', $curl);

        $result = $this->withSession([
            'fullname'     => 'Test User',
            'email'        => 'test@test.com',
            'role'         => 'mahasiswa',
            'access_token' => 'fake-token',
        ])->post('mahasiswa/simpanTugas', [
            'title'           => 'Belajar Matematika',
            'category'        => 'akademik',
            'priority'        => 'Tinggi',
            'deadline'        => '2026-07-01',
            'target_duration' => '120',
            'description'     => 'Bab 3 dan 4',
        ]);

        $result->assertRedirect();
    }
}
