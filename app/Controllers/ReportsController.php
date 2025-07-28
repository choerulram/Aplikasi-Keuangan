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

        // Ambil filter dari request dan pastikan semua field tersedia
        $filters = [
            'account_id' => $this->request->getGet('account_id') ?? '',
            'category_id' => $this->request->getGet('category_id') ?? '',
            'tipe' => $this->request->getGet('tipe') ?? '',
            'start_date' => $this->request->getGet('start_date') ?? '',
            'end_date' => $this->request->getGet('end_date') ?? '',
            'month' => $this->request->getGet('month') ?? '',
            'year' => $this->request->getGet('year') ?? '',
            'search' => $this->request->getGet('search') ?? '',
            'page' => $this->request->getGet('page_transactions') ?? 1
        ];

        // Jika filter bulan/tahun diisi, override start_date & end_date
        if (!empty($filters['month']) && !empty($filters['year'])) {
            $month = str_pad($filters['month'], 2, '0', STR_PAD_LEFT);
            $filters['start_date'] = $filters['year'] . '-' . $month . '-01';
            $filters['end_date'] = date('Y-m-t', strtotime($filters['start_date']));
        } elseif (!empty($filters['year']) && empty($filters['month'])) {
            $filters['start_date'] = $filters['year'] . '-01-01';
            $filters['end_date'] = $filters['year'] . '-12-31';
        }

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
        
        // Konfigurasi pagination
        $perPage = 10; // Jumlah item per halaman
        
        // Set up pager
        $pager = service('pager');
        
        // Ambil current page dari query string
        $currentPage = (int)($this->request->getGet('page_transactions') ?? 1);
        
        // Ambil total records untuk pagination
        $total_transactions = $reportModel->getTotal($filters);
        
        // Hitung total halaman
        $pageCount = ceil($total_transactions / $perPage);
        
        // Pastikan currentPage tidak melebihi pageCount
        $currentPage = min($currentPage, max(1, $pageCount));
        
        // Konfigurasi pager
        $pager->setPath('reports'); // Set base path untuk URL pagination
        
        // Store pager state dengan konfigurasi yang benar
        $pager->store('transactions', $currentPage, $perPage, $total_transactions);
        
        // Ambil data dengan pagination yang sudah dikonfigurasi
        $transactions = $reportModel->getReport($filters, $perPage, ($currentPage - 1) * $perPage);

        return view('Reports/index', [
            'pageTitle' => 'Laporan',
            'title' => 'Laporan | Aplikasi Keuangan',
            'accounts' => $accounts,
            'categories' => $categories,
            'filters' => $filters,
            'summary' => $summary,
            'transactions' => $transactions,
            'pager' => $pager,
            'perPage' => $perPage,
            'total_transactions' => $total_transactions,
            'currentPage' => $currentPage,
            'pageCount' => $pageCount
        ]);
    }

    public function exportPdf()
    {
        $reportModel = new TransactionReportModel();
        $accountModel = new AccountModel();
        $categoryModel = new CategoryModel();

        // Ambil filter dari request POST
        $filters = [
            'account_id' => $this->request->getPost('account_id') ?? '',
            'category_id' => $this->request->getPost('category_id') ?? '',
            'tipe' => $this->request->getPost('tipe') ?? '',
            'start_date' => $this->request->getPost('start_date') ?? '',
            'end_date' => $this->request->getPost('end_date') ?? '',
            'month' => $this->request->getPost('month') ?? '',
            'year' => $this->request->getPost('year') ?? ''
        ];

        // Jika filter bulan/tahun diisi, override start_date & end_date
        if (!empty($filters['month']) && !empty($filters['year'])) {
            $month = str_pad($filters['month'], 2, '0', STR_PAD_LEFT);
            $filters['start_date'] = $filters['year'] . '-' . $month . '-01';
            $filters['end_date'] = date('Y-m-t', strtotime($filters['start_date']));
        } elseif (!empty($filters['year']) && empty($filters['month'])) {
            $filters['start_date'] = $filters['year'] . '-01-01';
            $filters['end_date'] = $filters['year'] . '-12-31';
        }

        // Ambil data untuk PDF
        $data = [
            'transactions' => $reportModel->getReport($filters),
            'summary' => $reportModel->getSummary($filters),
            'filters' => $filters,
            'periode' => $this->getPeriodeText($filters),
        ];

        // Tambahkan nama akun dan kategori jika ada filter
        if (!empty($filters['account_id'])) {
            $account = $accountModel->find($filters['account_id']);
            $data['account_name'] = $account['nama_akun'] ?? '';
        }
        if (!empty($filters['category_id'])) {
            $category = $categoryModel->find($filters['category_id']);
            $data['category_name'] = $category['nama_kategori'] ?? '';
        }

        // Generate PDF menggunakan Dompdf
        $dompdf = new \Dompdf\Dompdf(['isRemoteEnabled' => true]);
        $html = view('Reports/pdf_template', $data);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Generate nama file
        $filename = 'Laporan_Transaksi_' . date('Y-m-d_H-i-s') . '.pdf';

        // Download PDF
        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($dompdf->output());
    }

    private function getPeriodeText($filters)
    {
        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            return date('d/m/Y', strtotime($filters['start_date'])) . ' - ' . date('d/m/Y', strtotime($filters['end_date']));
        } elseif (!empty($filters['month']) && !empty($filters['year'])) {
            return date('F Y', mktime(0, 0, 0, $filters['month'], 1, $filters['year']));
        } elseif (!empty($filters['year'])) {
            return 'Tahun ' . $filters['year'];
        }
        return 'Semua Periode';
    }

    // Stub ekspor Excel
    public function exportExcel()
    {
        $filters = $this->request->getPost();
        // TODO: Implementasi ekspor Excel
        return $this->response->setBody('Fitur ekspor Excel belum diimplementasikan.')->setContentType('text/plain');
    }
}
