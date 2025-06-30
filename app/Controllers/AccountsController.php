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

    public function add()
    {
        // Pastikan user login
        $userId = session('user_id');
        if (!$userId) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }
        if ($this->request->getMethod() === 'POST') {
            $accountModel = new AccountModel();
            $nama_akun = $this->request->getPost('nama_akun');
            $tipe_akun = $this->request->getPost('tipe_akun');
            $saldo_awal = $this->request->getPost('saldo_awal');
            // Validasi minimal
            if (!$nama_akun || !$tipe_akun) {
                return redirect()->to('/accounts')->with('error', 'Nama akun dan tipe akun wajib diisi!');
            }
            $data = [
                'user_id' => $userId,
                'nama_akun' => $nama_akun,
                'tipe_akun' => $tipe_akun,
                'saldo_awal' => $saldo_awal,
                'catatan' => $this->request->getPost('catatan'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            if ($accountModel->insert($data)) {
                return redirect()->to('/accounts')->with('success', 'Akun berhasil ditambahkan!');
            } else {
                return redirect()->to('/accounts')->with('error', 'Gagal menambah akun.');
            }
        }
        return redirect()->to('/accounts');
    }

    public function edit()
    {
        $accountModel = new AccountModel();
        $id = $this->request->getPost('id');
        if (!$id) {
            return redirect()->to('/accounts')->with('error', 'ID akun tidak ditemukan.');
        }
        $akun = $accountModel->find($id);
        if (!$akun) {
            return redirect()->to('/accounts')->with('error', 'Data akun tidak ditemukan.');
        }
        // Jika user biasa, hanya boleh edit akun miliknya sendiri
        $role = session('role');
        $userId = session('user_id');
        if ($role !== 'admin' && $akun['user_id'] != $userId) {
            return redirect()->to('/accounts')->with('error', 'Anda tidak berhak mengubah akun ini.');
        }
        $data = [
            'nama_akun' => $this->request->getPost('nama_akun'),
            'tipe_akun' => $this->request->getPost('tipe_akun'),
            'saldo_awal' => $this->request->getPost('saldo_awal'),
            'catatan' => $this->request->getPost('catatan'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        // user_id tidak diubah, tetap milik user aslinya
        $accountModel->update($id, $data);
        return redirect()->to('/accounts')->with('success', 'Akun berhasil diubah!');
    }
}
