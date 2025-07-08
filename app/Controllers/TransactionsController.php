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
        $accounts = $accountModel
            ->where($isAdmin ? [] : ['user_id' => $userId])
            ->findAll();
        $categories = $categoryModel
            ->where(['tipe' => 'income'] + ($isAdmin ? [] : ['user_id' => $userId]))
            ->findAll();

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
