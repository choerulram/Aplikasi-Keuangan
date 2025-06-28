<?php
namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class AuthController extends Controller
{
    public function login()
    {
        helper(['form']);
        $session = session();
        if ($this->request->getMethod() === 'post') {
            $userModel = new UserModel();
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            $user = $userModel->where('email', $email)->first();
            if ($user && password_verify($password, $user['password_hash'])) {
                $session->set([
                    'user_id' => $user['id'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'role' => $user['role'],
                    'isLoggedIn' => true
                ]);
                $session->setFlashdata('success', 'Login berhasil!');
                return redirect()->to('/dashboard');
            } else {
                $session->setFlashdata('error', 'Email atau password salah');
                return redirect()->to('/login');
            }
        }
        return view('Auth/login');
    }

    public function register()
    {
        helper(['form']);
        if ($this->request->getMethod() === 'post') {
            $userModel = new UserModel();
            $data = [
                'username' => $this->request->getPost('username'),
                'email' => $this->request->getPost('email'),
                'nama' => $this->request->getPost('nama'),
                'password_hash' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                'role' => 'user',
            ];
            $userModel->insert($data);
            return redirect()->to('/login');
        }
        return view('Auth/register');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
