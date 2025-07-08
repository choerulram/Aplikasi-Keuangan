<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class TransactionsController extends BaseController
{
    public function index()
    {
        return redirect()->to('/transactions/income');
    }

    public function income()
    {
        $session = session();
        $userId = $session->get('user_id');
        $role = $session->get('role');
        $isAdmin = ($role === 'admin');

        $transactionModel = new \App\Models\TransactionModel();
        $pager = \Config\Services::pager();
        $perPage = 10;
        $transactions = $transactionModel
            ->where('transactions.tipe', 'income')
            ->select('transactions.*, users.username, accounts.nama_akun, categories.nama_kategori')
            ->join('users', 'users.id = transactions.user_id', 'left')
            ->join('accounts', 'accounts.id = transactions.account_id', 'left')
            ->join('categories', 'categories.id = transactions.category_id', 'left');
        if (!$isAdmin && $userId !== null) {
            $transactions->where('transactions.user_id', $userId);
        }
        $transactions = $transactions->orderBy('transactions.tanggal', 'DESC')
            ->paginate($perPage, 'transactions');

        $total_transactions = $transactionModel
            ->where('transactions.tipe', 'income')
            ->when(!$isAdmin && $userId !== null, function($query) use ($userId) {
                return $query->where('transactions.user_id', $userId);
            })
            ->countAllResults();

        return view('Transactions/income', [
            'pageTitle' => 'Transaksi Pemasukan',
            'title' => 'Transaksi Pemasukan | Aplikasi Keuangan',
            'transactions' => $transactions,
            'isAdmin' => $isAdmin,
            'pager' => $transactionModel->pager,
            'total_transactions' => $total_transactions,
            'perPage' => $perPage
        ]);
    }

    public function expense()
    {
        $session = session();
        $userId = $session->get('user_id');
        $role = $session->get('role');
        $isAdmin = ($role === 'admin');

        $transactionModel = new \App\Models\TransactionModel();
        $pager = \Config\Services::pager();
        $perPage = 10;
        $transactions = $transactionModel
            ->where('transactions.tipe', 'expense')
            ->select('transactions.*, users.username, accounts.nama_akun, categories.nama_kategori')
            ->join('users', 'users.id = transactions.user_id', 'left')
            ->join('accounts', 'accounts.id = transactions.account_id', 'left')
            ->join('categories', 'categories.id = transactions.category_id', 'left');
        if (!$isAdmin && $userId !== null) {
            $transactions->where('transactions.user_id', $userId);
        }
        $transactions = $transactions->orderBy('transactions.tanggal', 'DESC')
            ->paginate($perPage, 'transactions');

        $total_transactions = $transactionModel
            ->where('transactions.tipe', 'expense')
            ->when(!$isAdmin && $userId !== null, function($query) use ($userId) {
                return $query->where('transactions.user_id', $userId);
            })
            ->countAllResults();

        return view('Transactions/expense', [
            'pageTitle' => 'Transaksi Pengeluaran',
            'title' => 'Transaksi Pengeluaran | Aplikasi Keuangan',
            'transactions' => $transactions,
            'isAdmin' => $isAdmin,
            'pager' => $transactionModel->pager,
            'total_transactions' => $total_transactions,
            'perPage' => $perPage
        ]);
    }
}
