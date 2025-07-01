<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AccountModel;
use CodeIgniter\Pager\Pager;

class AccountsController extends BaseController
{
    public function index()
    {
        $accountModel = new AccountModel();
        $role = session('role');
        $userId = session('user_id');
        $perPage = 10;

        // Ambil parameter search dan filter dari request
        $search = $this->request->getGet('search');
        $filter = trim($this->request->getGet('filter'));

        // Query dasar
        if ($role === 'admin') {
            $builder = $accountModel;
        } else {
            $builder = $accountModel->where('user_id', $userId);
        }

        // Filter berdasarkan tipe akun jika ada (pastikan filter tidak null dan tidak string kosong)
        if (isset($filter) && $filter !== '') {
            $builder = $builder->where('tipe_akun', $filter);
        }

        // Search berdasarkan nama akun atau catatan
        if (!empty($search)) {
            $builder = $builder->groupStart()
                ->like('nama_akun', $search)
                ->orLike('catatan', $search)
                ->groupEnd();
        }

        $accounts = $builder->paginate($perPage, 'accounts');
        $pager = $accountModel->pager;
        $total = $builder->countAllResults(false); // false agar tidak reset builder

        // Ambil semua tipe akun unik untuk filter dropdown (urutkan ASC)
        $tipeAkunList = $accountModel->select('tipe_akun')->distinct()->orderBy('tipe_akun', 'ASC')->findAll();

        return view('Accounts/index', [
            'pageTitle' => 'Akun',
            'title' => 'Akun | Aplikasi Keuangan',
            'accounts' => $accounts,
            'pager' => $pager,
            'total_accounts' => $total,
            'perPage' => $perPage,
            'search' => $search,
            'filter' => $filter,
            'tipeAkunList' => $tipeAkunList
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

    public function delete($id)
    {
        $accountModel = new AccountModel();
        $akun = $accountModel->find($id);
        if (!$akun) {
            return redirect()->to('/accounts')->with('error', 'Data akun tidak ditemukan.');
        }
        $role = session('role');
        $userId = session('user_id');
        // Hanya admin atau pemilik akun yang boleh hapus
        if ($role !== 'admin' && $akun['user_id'] != $userId) {
            return redirect()->to('/accounts')->with('error', 'Anda tidak berhak menghapus akun ini.');
        }
        if ($accountModel->delete($id)) {
            return redirect()->to('/accounts')->with('success', 'Akun berhasil dihapus!');
        } else {
            return redirect()->to('/accounts')->with('error', 'Gagal menghapus akun.');
        }
    }
}
