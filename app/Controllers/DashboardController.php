<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function index()
    {
        return view('Dashboard/index', [
            'pageTitle' => 'Dashboard Sistem',
            'title' => 'Dashboard | Aplikasi Keuangan',
        ]);
    }
}
