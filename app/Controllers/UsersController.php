<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class UsersController extends BaseController
{
    public function index()
    {
        return view('Users/index', [
            'pageTitle' => 'User',
            'title' => 'User | Aplikasi Keuangan'
        ]);
    }
}
