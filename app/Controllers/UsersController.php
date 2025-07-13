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
        $perPage = 10;
        if ($search) {
            $userModel->groupStart()
                ->like('username', $search)
                ->orLike('nama', $search)
                ->groupEnd();
        }
        if ($role) {
            $userModel->where('role', $role);
        }
        $userModel->orderBy('created_at', 'DESC');
        $users = $userModel->paginate($perPage, 'users');
        $pager = $userModel->pager;
        return view('Users/index', [
            'pageTitle' => 'User',
            'title' => 'User | Aplikasi Keuangan',
            'users' => $users,
            'search' => $search,
            'role' => $role,
            'pager' => $pager,
            'perPage' => $perPage
        ]);
    }

    public function add()
    {
        if (session('role') !== 'admin') {
            return redirect()->to('/');
        }

        $validation =  \Config\Services::validation();
        $rules = [
            'username' => 'required|is_unique[users.username]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'nama'     => 'required',
            'password' => 'required|min_length[6]',
            'role'     => 'required|in_list[admin,user]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $userModel = new UserModel();
        $data = [
            'username'      => $this->request->getPost('username'),
            'email'         => $this->request->getPost('email'),
            'nama'          => $this->request->getPost('nama'),
            'password_hash' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'          => $this->request->getPost('role'),
        ];

        $userModel->insert($data);

        return redirect()->to('/users')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit()
    {
        if (session('role') !== 'admin') {
            return redirect()->to('/');
        }

        $id = $this->request->getPost('id');
        if (!$id) {
            return redirect()->back()->with('errors', ['ID user tidak ditemukan.']);
        }

        $userModel = new UserModel();
        $user = $userModel->find($id);
        if (!$user) {
            return redirect()->back()->with('errors', ['User tidak ditemukan.']);
        }

        $validation = \Config\Services::validation();
        $rules = [
            'username' => 'required|is_unique[users.username,id,{id}]',
            'email'    => 'required|valid_email|is_unique[users.email,id,{id}]',
            'nama'     => 'required',
            'role'     => 'required|in_list[admin,user]'
        ];
        $data = [
            'id'       => $id,
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'nama'     => $this->request->getPost('nama'),
            'role'     => $this->request->getPost('role'),
        ];
        $password = $this->request->getPost('password');
        if ($password) {
            $rules['password'] = 'min_length[6]';
            $data['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
        }

        // Replace {id} in rules
        foreach ($rules as $key => $rule) {
            $rules[$key] = str_replace('{id}', $id, $rule);
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $userModel->update($id, $data);

        return redirect()->to('/users')->with('success', 'User berhasil diubah.');
    }
}
