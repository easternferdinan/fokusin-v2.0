<?php

if (!function_exists('denyAccess')) {
    function denyAccess()
    {
        return redirect()->to(base_url('auth/login'))->with('error', [
            'title'   => 'Akses Ditolak',
            'message' => 'Login untuk menggunakan fitur ini.'
        ]);
    }
}
