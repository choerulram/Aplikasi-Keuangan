<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AccountModel;

class AccountsController extends BaseController
{
    public function index()
    {
        $accountModel = new AccountModel();
        $role = session('role');
        $userId = session('user_id');
        if ($role === 'admin') {
            $accounts = $accountModel->findAll();
        } else {
            $accounts = $accountModel->where('user_id', $userId)->findAll();
        }
        return view('Accounts/index', [
            'pageTitle' => 'Akun',
            'title' => 'Akun | Aplikasi Keuangan',
            'accounts' => $accounts
        ]);
    }
}
