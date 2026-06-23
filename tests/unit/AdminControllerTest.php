<?php

namespace Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\HTTP\CURLRequest;
use CodeIgniter\HTTP\Response;
use Config\App;

final class AdminControllerTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    protected function tearDown(): void
    {
        $this->resetServices();
        parent::tearDown();
    }

    public function testAdminIndexByMahasiswa(): void
    {
        $result = $this->withSession([
            'fullname'     => 'Mahasiswa User',
            'email'        => 'mhs@test.com',
            'role'         => 'mahasiswa',
            'access_token' => 'fake-token',
        ])->get('admin');

        $result->assertRedirectTo(base_url('auth/adminLogin'));
    }

    public function testAdminIndexByAdmin(): void
    {
        $apiRes = new Response(new App());
        $apiRes->setStatusCode(200);
        $apiRes->setBody(json_encode([
            ['user_id' => '1', 'fullname' => 'Budi', 'username' => 'budi01', 'latest_stress_level' => 'Rendah'],
            ['user_id' => '2', 'fullname' => 'Ani',  'username' => 'ani02',  'latest_stress_level' => 'Sedang'],
        ]));

        $curl = $this->createMock(CURLRequest::class);
        $curl->method('get')->willReturn($apiRes);

        \Config\Services::injectMock('curlrequest', $curl);

        $result = $this->withSession([
            'fullname'     => 'Admin User',
            'email'        => 'admin@test.com',
            'role'         => 'admin',
            'access_token' => 'fake-token',
        ])->get('admin');

        $result->assertStatus(200);
    }

    public function testStoreMahasiswaUnauthorized(): void
    {
        $result = $this->withSession([
            'fullname'     => 'Mahasiswa User',
            'email'        => 'mhs@test.com',
            'role'         => 'mahasiswa',
            'access_token' => 'fake-token',
        ])->post('admin/store-mahasiswa', [
            'fullname' => 'Budi',
            'username' => 'budi01',
            'email'    => 'budi@test.com',
            'password' => 'secret',
        ]);

        $result->assertStatus(401);
    }

    public function testStoreMahasiswaByAdmin(): void
    {
        $apiRes = new Response(new App());
        $apiRes->setStatusCode(201);
        $apiRes->setBody(json_encode(['id' => '456']));

        $curl = $this->createMock(CURLRequest::class);
        $curl->method('post')->willReturn($apiRes);

        \Config\Services::injectMock('curlrequest', $curl);

        $result = $this->withSession([
            'fullname'     => 'Admin User',
            'email'        => 'admin@test.com',
            'role'         => 'admin',
            'access_token' => 'fake-token',
        ])->post('admin/store-mahasiswa', [
            'fullname'              => 'Budi',
            'username'              => 'budi01',
            'email'                 => 'budi@test.com',
            'password'              => 'secret',
            'mental_health_history' => false,
            'academic_performance'  => 85,
            'social_support'        => 70,
        ]);

        $result->assertStatus(201);
    }

    public function testAdminManagementByAdmin(): void
    {
        $result = $this->withSession([
            'fullname'     => 'Admin User',
            'email'        => 'admin@test.com',
            'role'         => 'admin',
            'access_token' => 'fake-token',
        ])->get('admin/admin-management');

        $result->assertRedirectTo(base_url('admin'));
    }

    public function testConfigBySuperadmin(): void
    {
        $apiRes = new Response(new App());
        $apiRes->setStatusCode(200);
        $apiRes->setBody(json_encode([
            'api_base_url'                => 'http://localhost:8000/api/v1/',
            'stress_threshold'            => 70,
            'stress_threshold_frequency'  => 3,
        ]));

        $curl = $this->createMock(CURLRequest::class);
        $curl->method('get')->willReturn($apiRes);

        \Config\Services::injectMock('curlrequest', $curl);

        $result = $this->withSession([
            'fullname'     => 'Super Admin',
            'email'        => 'super@test.com',
            'role'         => 'superadmin',
            'access_token' => 'fake-token',
        ])->get('admin/config');

        $result->assertStatus(200);
    }

    public function testUpdateConfigFail(): void
    {
        $apiRes = new Response(new App());
        $apiRes->setStatusCode(500);
        $apiRes->setBody(json_encode(['detail' => 'Gagal menyimpan konfigurasi']));

        $curl = $this->createMock(CURLRequest::class);
        $curl->method('post')->willReturn($apiRes);

        \Config\Services::injectMock('curlrequest', $curl);

        $result = $this->withSession([
            'fullname'     => 'Super Admin',
            'email'        => 'super@test.com',
            'role'         => 'superadmin',
            'access_token' => 'fake-token',
        ])->post('admin/update-config', [
            'api_base_url'                => 'http://localhost:8000/api/v1/',
            'stress_threshold'            => 70,
            'stress_threshold_frequency'  => 3,
        ]);

        $result->assertRedirect();
    }

    public function testExportDatabaseSuccess(): void
    {
        $apiRes = new Response(new App());
        $apiRes->setStatusCode(200);
        $apiRes->setBody('fake-zip-binary-content');

        $curl = $this->createMock(CURLRequest::class);
        $curl->method('get')->willReturn($apiRes);

        \Config\Services::injectMock('curlrequest', $curl);

        $result = $this->withSession([
            'fullname'     => 'Super Admin',
            'email'        => 'super@test.com',
            'role'         => 'superadmin',
            'access_token' => 'fake-token',
        ])->get('admin/export-database');

        $result->assertStatus(200);
        $result->assertHeader('Content-Type', 'application/zip');
    }
}
