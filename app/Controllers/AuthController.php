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
        if ($this->request->getMethod() === 'POST') {
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
                return redirect()->to(base_url('/'));
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
        $session = session();
        $errorMsg = [];
        if ($this->request->getMethod() === 'POST') {
            $userModel = new UserModel();
            $username = $this->request->getPost('username');
            $email = $this->request->getPost('email');
            // Cek username
            if ($userModel->where('username', $username)->first()) {
                $errorMsg['username'] = 'Username sudah digunakan.';
            }
            // Cek email
            if ($userModel->where('email', $email)->first()) {
                $errorMsg['email'] = 'Email sudah digunakan.';
            }
            if (!empty($errorMsg)) {
                if ($this->request->isAJAX()) {
                    if (isset($errorMsg['username'])) {
                        $errorMsg['username'] = '<i>' . $errorMsg['username'] . '</i>';
                    }
                    if (isset($errorMsg['email'])) {
                        $errorMsg['email'] = '<i>' . $errorMsg['email'] . '</i>';
                    }
                    return $this->response->setJSON([
                        'success' => false,
                        'errors' => $errorMsg
                    ]);
                } else {
                    return view('Auth/register', [
                        'errorMsg' => implode(' ', $errorMsg)
                    ]);
                }
            }
            $data = [
                'username' => $username,
                'email' => $email,
                'nama' => $this->request->getPost('nama'),
                'password_hash' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                'role' => 'user',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            if ($userModel->insert($data)) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => true,
                        'redirect' => site_url('login')
                    ]);
                } else {
                    $session->setFlashdata('success', 'Registrasi berhasil! Silakan login.');
                    return redirect()->to('/login');
                }
            } else {
                $db = \Config\Database::connect();
                $dbError = $db->error();
                $modelErrors = $userModel->errors();
                $errorMsg = 'Registrasi gagal!';
                if (!empty($modelErrors)) {
                    $errorMsg .= ' Model: ' . implode(', ', $modelErrors);
                }
                if ($dbError['code'] != 0) {
                    $errorMsg .= ' | DB Error: ' . $dbError['message'];
                }
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => false,
                        'errors' => ['general' => $errorMsg]
                    ]);
                } else {
                    return view('Auth/register', [
                        'errorMsg' => $errorMsg
                    ]);
                }
            }
        }
        return view('Auth/register');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
