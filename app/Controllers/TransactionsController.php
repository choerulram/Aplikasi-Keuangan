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
        $transactions = $transactionModel->getTransactionsByType('income', $userId, $isAdmin);

        return view('Transactions/income', [
            'pageTitle' => 'Transaksi Pemasukan',
            'title' => 'Transaksi Pemasukan | Aplikasi Keuangan',
            'transactions' => $transactions,
            'isAdmin' => $isAdmin
        ]);
    }

    public function expense()
    {
        $session = session();
        $userId = $session->get('user_id');
        $role = $session->get('role');
        $isAdmin = ($role === 'admin');

        $transactionModel = new \App\Models\TransactionModel();
        $transactions = $transactionModel->getTransactionsByType('expense', $userId, $isAdmin);

        return view('Transactions/expense', [
            'pageTitle' => 'Transaksi Pengeluaran',
            'title' => 'Transaksi Pengeluaran | Aplikasi Keuangan',
            'transactions' => $transactions,
            'isAdmin' => $isAdmin
        ]);
    }
}
