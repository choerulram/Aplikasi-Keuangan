<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\ReportModel;
use App\Models\AccountModel;
use App\Models\CategoryModel;

class ReportsController extends BaseController
{
    public function index()
    {
        $accountModel = new AccountModel();
        $categoryModel = new CategoryModel();
        $reportModel = new ReportModel();

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

    public function cashflow()
    {
        $reportModel = new ReportModel();
        $accountModel = new AccountModel();
        $categoryModel = new CategoryModel();

        // Ambil filter dari request
        $filters = [
            'start_date' => $this->request->getGet('start_date'),
            'end_date' => $this->request->getGet('end_date'),
            'period' => $this->request->getGet('period') ?? 'this_month'
        ];

        // Set default filter berdasarkan period yang dipilih
        if ($filters['period'] === 'this_month') {
            $filters['start_date'] = date('Y-m-01'); // Awal bulan ini
            $filters['end_date'] = date('Y-m-t'); // Akhir bulan ini
        } elseif ($filters['period'] === 'last_month') {
            $filters['start_date'] = date('Y-m-01', strtotime('last month'));
            $filters['end_date'] = date('Y-m-t', strtotime('last month'));
        } elseif ($filters['period'] === 'this_year') {
            $filters['start_date'] = date('Y-01-01');
            $filters['end_date'] = date('Y-12-31');
        } elseif ($filters['period'] === 'last_year') {
            $filters['start_date'] = date('Y-01-01', strtotime('-1 year'));
            $filters['end_date'] = date('Y-12-31', strtotime('-1 year'));
        }

        // Ambil data
        $summary = $reportModel->getSummary($filters);
        
        // Set up pagination
        $perPage = 10;
        $currentPage = (int)($this->request->getGet('page') ?? 1);
        $total = $reportModel->getTotal($filters);
        
        $pager = service('pager');
        $pager->setPath('reports/cashflow');
        $pager->store('default', $currentPage, $perPage, $total);
        
        // Ambil transaksi dengan pagination
        $transactions = $reportModel->getReport($filters, $perPage, ($currentPage - 1) * $perPage);

        // Data untuk view
        $data = [
            'summary' => $summary,
            'transactions' => $transactions,
            'pager' => $pager,
            'filters' => $filters,
            'accounts' => $accountModel->findAll(),
            'categories' => $categoryModel->findAll()
        ];

        return view('Reports/cashflow', $data);
    }

    public function exportPdf()
    {
        $reportModel = new ReportModel();
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

    public function exportExcel()
    {
        $reportModel = new ReportModel();
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

        // Ambil data transaksi
        $transactions = $reportModel->getReport($filters);
        $summary = $reportModel->getSummary($filters);

        // Buat objek spreadsheet baru
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set judul laporan
        $sheet->setCellValue('A1', 'LAPORAN TRANSAKSI KEUANGAN');
        $sheet->mergeCells('A1:H1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Set periode
        $periode = $this->getPeriodeText($filters);
        $sheet->setCellValue('A2', 'Periode: ' . $periode);
        $sheet->mergeCells('A2:H2');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Set ringkasan
        $row = 4;
        $sheet->setCellValue('A' . $row, 'Total Pemasukan:');
        $sheet->setCellValue('B' . $row, 'Rp ' . number_format($summary['total_income'] ?? 0, 0, ',', '.'));
        $row++;
        $sheet->setCellValue('A' . $row, 'Total Pengeluaran:');
        $sheet->setCellValue('B' . $row, 'Rp ' . number_format($summary['total_expense'] ?? 0, 0, ',', '.'));
        $row++;
        $sheet->setCellValue('A' . $row, 'Saldo Akhir:');
        $sheet->setCellValue('B' . $row, 'Rp ' . number_format(($summary['total_income'] ?? 0) - ($summary['total_expense'] ?? 0), 0, ',', '.'));

        // Header tabel
        $row = 8;
        $headers = ['No', 'Tanggal', 'Deskripsi', 'Akun', 'Kategori', 'Tipe', 'Jumlah'];
        if (session()->get('role') === 'admin') {
            $headers[] = 'User';
        }
        
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . $row, $header);
            $sheet->getStyle($col . $row)->getFont()->setBold(true);
            $col++;
        }

        // Style header tabel
        $headerRange = 'A' . $row . ':' . $col . $row;
        $sheet->getStyle($headerRange)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CCCCCC');
        $sheet->getStyle($headerRange)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        // Isi tabel
        $row++;
        $no = 1;
        foreach ($transactions as $trx) {
            $col = 'A';
            $sheet->setCellValue($col++ . $row, $no++);
            $sheet->setCellValue($col++ . $row, date('d/m/Y', strtotime($trx['tanggal'])));
            $sheet->setCellValue($col++ . $row, $trx['deskripsi']);
            $sheet->setCellValue($col++ . $row, $trx['account_name']);
            $sheet->setCellValue($col++ . $row, $trx['category_name']);
            $sheet->setCellValue($col++ . $row, ucfirst($trx['tipe']));
            $sheet->setCellValue($col++ . $row, 'Rp ' . number_format($trx['jumlah'], 0, ',', '.'));
            if (session()->get('role') === 'admin') {
                $sheet->setCellValue($col++ . $row, $trx['username']);
            }
            $row++;
        }

        // Auto size kolom
        foreach (range('A', $col) as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Create Excel file
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'Laporan_Transaksi_' . date('Y-m-d_H-i-s') . '.xlsx';

        // Set header untuk download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        // Output file
        $writer->save('php://output');
        exit();
    }
}
