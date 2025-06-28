<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function index()
    {
        if (!session('isLoggedIn')) {
            return redirect()->to('/login');
        }
        $role = session('role');
        
        return view('Dashboard/index', [
            'pageTitle' => 'Dashboard Sistem',
            'title' => 'Dashboard | Aplikasi Keuangan',
            'role' => $role
        ]);
    }
}
