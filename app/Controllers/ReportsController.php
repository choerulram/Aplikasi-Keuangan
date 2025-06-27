<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class ReportsController extends BaseController
{
    public function index()
    {
        return view('Reports/index', [
            'pageTitle' => 'Laporan',
            'title' => 'Laporan | Aplikasi Keuangan'
        ]);
    }
}
