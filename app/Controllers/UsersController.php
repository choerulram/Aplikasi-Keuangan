<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class UsersController extends BaseController
{
    public function index()
    {
        if (session('role') !== 'admin') {
            return redirect()->to('/');
        }
        $userModel = new UserModel();
        $search = $this->request->getGet('search');
        $role = $this->request->getGet('role');
        if ($search) {
            $userModel->groupStart()
                ->like('username', $search)
                ->orLike('nama', $search)
                ->groupEnd();
        }
        if ($role) {
            $userModel->where('role', $role);
        }
        $users = $userModel->findAll();
        return view('Users/index', [
            'pageTitle' => 'User',
            'title' => 'User | Aplikasi Keuangan',
            'users' => $users,
            'search' => $search,
            'role' => $role
        ]);
    }
}
