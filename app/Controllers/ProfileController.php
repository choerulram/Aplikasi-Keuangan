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
        // Ambil ID dari session
        $userId = session('user_id');
        
        // Jika tidak ada session, redirect ke login
        if (!$userId) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        // Ambil data user
        $user = $this->userModel->find($userId);
        
        // Jika user tidak ditemukan
        if (!$user) {
            return redirect()->to('/')->with('error', 'Data profil tidak ditemukan');
        }

        // Siapkan data untuk view
        $data = [
            'title' => 'Profil Pengguna',
            'user' => [
                'id' => $user['id'],
                'username' => $user['username'],
                'nama' => $user['nama'] ?? session('nama'),
                'email' => $user['email'],
                'role' => $user['role'] ?? session('role'),
                'created_at' => $user['created_at']
            ],
            'pageTitle' => 'Profil'
        ];

        // Tampilkan view
        return view('Profile/index', $data);
    }

    public function update()
    {
        $userId = session('user_id');
        
        // Cek apakah user sudah login
        if (!$userId) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        // Validasi input
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
            // Update data user
            $this->userModel->update($userId, $data);
            
            // Update session data
            session()->set([
                'nama' => $data['nama'],
                'email' => $data['email']
            ]);
            
            return redirect()->to('/profile')->with('success', 'Profil berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui profil');
        }
    }

    public function changePassword()
    {
        $userId = session('user_id');

        // Cek apakah user sudah login
        if (!$userId) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        // Validasi input
        $rules = [
            'current_password' => 'required',
            'new_password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[new_password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Cek user dan password
        $user = $this->userModel->find($userId);
        if (!$user) {
            return redirect()->back()->with('error', 'Data user tidak ditemukan');
        }

        $currentPassword = $this->request->getPost('current_password');
        if (!password_verify($currentPassword, $user['password_hash'])) {
            return redirect()->back()->with('error', 'Password saat ini tidak sesuai');
        }

        // Hash password baru
        $newPassword = password_hash($this->request->getPost('new_password'), PASSWORD_DEFAULT);
        
        try {
            $this->userModel->update($userId, ['password_hash' => $newPassword]);
            return redirect()->to('/profile')->with('success', 'Password berhasil diubah');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengubah password');
        }
    }
}