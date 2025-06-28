<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class UsersController extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();
        $users = $userModel->findAll();
        return view('Users/index', [
            'pageTitle' => 'User',
            'title' => 'User | Aplikasi Keuangan',
            'users' => $users
        ]);
    }
}
