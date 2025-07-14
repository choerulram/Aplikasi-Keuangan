<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\TransactionReportModel;
use App\Models\AccountModel;
use App\Models\CategoryModel;

class ReportsController extends BaseController
{
    public function index()
    {
        $accountModel = new AccountModel();
        $categoryModel = new CategoryModel();
        $reportModel = new TransactionReportModel();

        // Ambil filter dari request
        $filters = [
            'account_id' => $this->request->getGet('account_id'),
            'category_id' => $this->request->getGet('category_id'),
            'tipe' => $this->request->getGet('tipe'),
            'start_date' => $this->request->getGet('start_date'),
            'end_date' => $this->request->getGet('end_date'),
        ];

        // Data untuk filter dropdown
        // Ambil akun dan kategori, mapping ke key yang sesuai tampilan
        $accounts = array_map(function($acc) {
            return [
                'id' => $acc['id'],
                'name' => $acc['nama_akun']
            ];
        }, $accountModel->findAll());

        $categories = array_map(function($cat) {
            return [
                'id' => $cat['id'],
                'name' => $cat['nama_kategori']
            ];
        }, $categoryModel->findAll());

        // Data laporan
        $summary = $reportModel->getSummary($filters);
        $transactions = $reportModel->getReport($filters);

        return view('Reports/index', [
            'pageTitle' => 'Laporan',
            'title' => 'Laporan | Aplikasi Keuangan',
            'accounts' => $accounts,
            'categories' => $categories,
            'filters' => $filters,
            'summary' => $summary,
            'transactions' => $transactions
        ]);
    }
}
