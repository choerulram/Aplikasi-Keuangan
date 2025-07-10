<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class TransactionsController extends BaseController
{
    public function income()
    {
        $session = session();
        $userId = $session->get('user_id');
        $role = $session->get('role');
        $isAdmin = ($role === 'admin');

        $transactionModel = new \App\Models\TransactionModel();
        $accountModel = new \App\Models\AccountModel();
        $categoryModel = new \App\Models\CategoryModel();
        $pager = \Config\Services::pager();
        $perPage = 10;

        // Ambil filter dari GET
        $search = $this->request->getGet('search');
        $account = $this->request->getGet('account');
        $category = $this->request->getGet('category');
        $date = $this->request->getGet('date');

        $filter = [
            'search' => $search,
            'account' => $account,
            'category' => $category,
            'date' => $date
        ];

        $transactions = $transactionModel->getFilteredTransactions('income', $userId, $isAdmin, $filter, $perPage, 'transactions');
        $total_transactions = $transactionModel->getFilteredTransactionsCount('income', $userId, $isAdmin, $filter);

        // Data akun & kategori untuk filter
        if ($isAdmin) {
            $accounts = $accountModel->findAll();
            $categories = $categoryModel->where(['tipe' => 'income'])->findAll();
        } else {
            $accounts = $accountModel->where(['user_id' => $userId])->findAll();
            $categories = $categoryModel->where(['tipe' => 'income', 'user_id' => $userId])->findAll();
        }

        return view('Transactions/income', [
            'pageTitle' => 'Transaksi Pemasukan',
            'title' => 'Transaksi Pemasukan | Aplikasi Keuangan',
            'transactions' => $transactions,
            'isAdmin' => $isAdmin,
            'pager' => $transactionModel->pager,
            'total_transactions' => $total_transactions,
            'perPage' => $perPage,
            'accounts' => $accounts,
            'categories' => $categories,
            'search' => $search,
            'account' => $account,
            'category' => $category,
            'date' => $date
        ]);
    }

    public function addIncome()
    {
        $session = session();
        $userId = $session->get('user_id');
        if (!$userId) {
            return redirect()->back()->with('error', 'Anda harus login.');
        }
        $transactionModel = new \App\Models\TransactionModel();
        $data = [
            'user_id' => $userId,
            'account_id' => $this->request->getPost('account_id'),
            'category_id' => $this->request->getPost('category_id'),
            'tipe' => 'income',
            'jumlah' => $this->request->getPost('jumlah'),
            'tanggal' => $this->request->getPost('tanggal'),
            'deskripsi' => $this->request->getPost('deskripsi'),
        ];
        // Validasi sederhana
        if (!$data['account_id'] || !$data['category_id'] || !$data['jumlah'] || !$data['tanggal'] || !$data['deskripsi']) {
            return redirect()->back()->with('error', 'Semua field wajib diisi.');
        }
        $transactionModel->insert($data);
        return redirect()->back()->with('success', 'Transaksi pemasukan berhasil ditambahkan.');
    }

    public function editIncome()
    {
        $session = session();
        $userId = $session->get('user_id');
        $role = $session->get('role');
        $isAdmin = ($role === 'admin');

        $transactionModel = new \App\Models\TransactionModel();

        $id = $this->request->getPost('id');
        $data = [
            'account_id' => $this->request->getPost('account_id'),
            'category_id' => $this->request->getPost('category_id'),
            'jumlah' => $this->request->getPost('jumlah'),
            'tanggal' => $this->request->getPost('tanggal'),
            'deskripsi' => $this->request->getPost('deskripsi'),
        ];

        // Validasi sederhana
        if (!$id || !$data['account_id'] || !$data['category_id'] || !$data['jumlah'] || !$data['tanggal'] || !$data['deskripsi']) {
            return redirect()->back()->with('error', 'Semua field wajib diisi.');
        }

        // Cek hak akses: hanya admin atau pemilik data yang boleh edit
        $trx = $transactionModel->find($id);
        if (!$trx) {
            return redirect()->back()->with('error', 'Transaksi tidak ditemukan.');
        }
        if (!$isAdmin && $trx['user_id'] != $userId) {
            return redirect()->back()->with('error', 'Anda tidak berhak mengubah transaksi ini.');
        }

        $transactionModel->update($id, $data);
        return redirect()->back()->with('success', 'Transaksi pemasukan berhasil diubah.');
    }

    public function deleteIncome($id)
    {
        $session = session();
        $userId = $session->get('user_id');
        $role = $session->get('role');
        $isAdmin = ($role === 'admin');

        $transactionModel = new \App\Models\TransactionModel();
        $trx = $transactionModel->find($id);
        if (!$trx) {
            return redirect()->back()->with('error', 'Transaksi tidak ditemukan.');
        }
        // Cek hak akses: hanya admin atau pemilik data yang boleh hapus
        if (!$isAdmin && $trx['user_id'] != $userId) {
            return redirect()->back()->with('error', 'Anda tidak berhak menghapus transaksi ini.');
        }
        $transactionModel->delete($id);
        return redirect()->back()->with('success', 'Transaksi pemasukan berhasil dihapus.');
    }

    public function expense()
    {
        $session = session();
        $userId = $session->get('user_id');
        $role = $session->get('role');
        $isAdmin = ($role === 'admin');

        $transactionModel = new \App\Models\TransactionModel();
        $accountModel = new \App\Models\AccountModel();
        $categoryModel = new \App\Models\CategoryModel();
        $pager = \Config\Services::pager();
        $perPage = 10;

        // Ambil filter dari GET
        $search = $this->request->getGet('search');
        $account = $this->request->getGet('account');
        $category = $this->request->getGet('category');
        $date = $this->request->getGet('date');

        $filter = [
            'search' => $search,
            'account' => $account,
            'category' => $category,
            'date' => $date
        ];

        $transactions = $transactionModel->getFilteredTransactions('expense', $userId, $isAdmin, $filter, $perPage, 'transactions');
        $total_transactions = $transactionModel->getFilteredTransactionsCount('expense', $userId, $isAdmin, $filter);

        // Data akun & kategori untuk filter
        $accounts = $accountModel
            ->where($isAdmin ? [] : ['user_id' => $userId])
            ->findAll();
        $categories = $categoryModel
            ->where(['tipe' => 'expense'] + ($isAdmin ? [] : ['user_id' => $userId]))
            ->findAll();

        return view('Transactions/expense', [
            'pageTitle' => 'Transaksi Pengeluaran',
            'title' => 'Transaksi Pengeluaran | Aplikasi Keuangan',
            'transactions' => $transactions,
            'isAdmin' => $isAdmin,
            'pager' => $transactionModel->pager,
            'total_transactions' => $total_transactions,
            'perPage' => $perPage,
            'accounts' => $accounts,
            'categories' => $categories,
            'search' => $search,
            'account' => $account,
            'category' => $category,
            'date' => $date
        ]);
    }
}
