<?php

namespace App\Controllers;

use App\Models\UserModel;

class ProfileController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $userId = session('id');
        $user = $this->userModel->find($userId);

        $data = [
            'title' => 'Profil Pengguna',
            'user' => $user,
            'pageTitle' => 'Profil'
        ];

        return view('Profile/index', $data);
    }

    public function update()
    {
        $userId = session('id');
        
        $rules = [
            'nama' => 'required|min_length[3]|max_length[100]',
            'email' => "required|valid_email|is_unique[users.email,id,$userId]"
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nama' => $this->request->getPost('nama'),
            'email' => $this->request->getPost('email')
        ];

        try {
            $this->userModel->update($userId, $data);
            session()->setFlashdata('success', 'Profil berhasil diperbarui');
            return redirect()->to('/profile');
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Terjadi kesalahan saat memperbarui profil');
            return redirect()->back()->withInput();
        }
    }

    public function changePassword()
    {
        $userId = session('id');
        $rules = [
            'current_password' => 'required',
            'new_password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[new_password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $user = $this->userModel->find($userId);
        $currentPassword = $this->request->getPost('current_password');
        
        if (!password_verify($currentPassword, $user['password_hash'])) {
            return redirect()->back()->with('error', 'Password saat ini tidak sesuai');
        }

        $newPassword = password_hash($this->request->getPost('new_password'), PASSWORD_DEFAULT);
        
        try {
            $this->userModel->update($userId, ['password_hash' => $newPassword]);
            session()->setFlashdata('success', 'Password berhasil diubah');
            return redirect()->to('/profile');
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Terjadi kesalahan saat mengubah password');
            return redirect()->back();
        }
    }
}