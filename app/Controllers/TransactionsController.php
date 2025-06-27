<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class TransactionsController extends BaseController
{
    public function index()
    {
        return view('Transactions/index', [
            'pageTitle' => 'Transaksi',
            'title' => 'Transaksi | Aplikasi Keuangan'
        ]);
    }
}
