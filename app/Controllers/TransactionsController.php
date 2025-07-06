<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class TransactionsController extends BaseController
{
    public function index()
    {
        $session = session();
        $userId = $session->get('user_id');
        $role = $session->get('role');
        $isAdmin = ($role === 'admin');

        $transactionModel = new \App\Models\TransactionModel();
        $transactions = $transactionModel->getTransactions($userId, $isAdmin);

        return view('Transactions/index', [
            'pageTitle' => 'Transaksi',
            'title' => 'Transaksi | Aplikasi Keuangan',
            'transactions' => $transactions,
            'isAdmin' => $isAdmin
        ]);
    }
}
