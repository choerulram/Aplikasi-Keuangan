<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class SettingsController extends BaseController
{
    public function index()
    {
        return view('Settings/index', [
            'pageTitle' => 'Pengaturan',
            'title' => 'Pengaturan | Aplikasi Keuangan'
        ]);
    }
}
