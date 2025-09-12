<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\ReportModel;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

/**
 * ReportsController - Controller untuk mengelola semua jenis laporan
 * 
 * Controller ini dibagi menjadi 3 bagian utama:
 * 1. Laporan Arus Kas (Cashflow)
 * 2. Laporan Budget
 * 3. Laporan Kategori
 */
class ReportsController extends BaseController
{
    /**************************************
     * BAGIAN 1: LAPORAN ARUS KAS (CASHFLOW)
     **************************************/
    
    public function cashflow()
    {
        $reportModel = new ReportModel();
        
        // Get filter parameters
        $viewType = $this->request->getGet('view_type') ?? 'monthly';
        $month = $this->request->getGet('month') ?? date('m');
        $year = $this->request->getGet('year') ?? date('Y');
        $yearFilter = $this->request->getGet('year_filter') ?? date('Y');

        // Set date range based on view type
        if ($viewType === 'monthly') {
            $startDate = "$year-$month-01";
            $endDate = date('Y-m-t', strtotime($startDate));
        } else {
            $startDate = "$yearFilter-01-01";
            $endDate = "$yearFilter-12-31";
        }

        // Prepare filters
        $filters = [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'page' => $this->request->getGet('page') ?? 1
        ];

        // Get summary data
        $summary = $reportModel->getSummary($filters);

        // Get chart data
        $chartData = $reportModel->getMonthlyTotals($startDate, $endDate, $viewType);

        // Get transactions with pagination
        $perPage = 10;
        $currentPage = (int)($this->request->getGet('page_transactions') ?? 1);
        $total = $reportModel->getTotal($filters);
        $transactions = $reportModel->getReport($filters, $perPage, ($currentPage - 1) * $perPage);

        // Configure pagination
        $pager = service('pager');
        $pager->setPath('reports/cashflow');
        $pager->setSegment(2, 'page_transactions');
        $pager->store('transactions', $currentPage, $perPage, $total);

        // Total halaman untuk view
        $totalPages = ceil($total / $perPage);

        return view('Reports/cashflow', [
            'pageTitle' => 'Laporan Arus Kas',
            'title' => 'Laporan Arus Kas | Aplikasi Keuangan',
            'view_type' => $viewType,
            'month' => $month,
            'year' => $year,
            'year_filter' => $yearFilter,
            'summary' => $summary,
            'chartData' => $chartData,
            'transactions' => $transactions,
            'pager' => $pager,
            'filters' => $filters
        ]);
    }

    public function exportPDF()
    {
        $reportModel = new ReportModel();
        
        // Ambil data yang akan diekspor
        $filters = [
            'start_date' => date('Y-m-01'),
            'end_date' => date('Y-m-t')
        ];
        
        $summary = $reportModel->getSummary($filters);
        $transactions = $reportModel->getReport($filters);
        $chartData = $reportModel->getMonthlyTotals($filters['start_date'], $filters['end_date'], 'monthly');

        // Persiapkan data untuk view
        $data = [
            'title' => 'Laporan Arus Kas',
            'pageTitle' => 'Laporan Arus Kas',
            'summary' => $summary,
            'transactions' => $transactions,
            'chartData' => $chartData,
            'periode' => 'Periode ' . date('F Y')
        ];

        // Load Dompdf
        $dompdf = new \Dompdf\Dompdf();
        $options = new \Dompdf\Options();
        $options->setIsRemoteEnabled(true);
        $dompdf->setOptions($options);

        // Load view ke HTML
        $html = view('Reports/pdf_cashflow', $data);

        // Convert ke PDF
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Download file
        $filename = 'Laporan_Arus_Kas_' . date('Y-m') . '.pdf';
        return $dompdf->stream($filename, ['Attachment' => false]);
    }

    public function exportExcel()
    {
        $reportModel = new ReportModel();
        
        // Ambil data yang akan diekspor
        $filters = [
            'start_date' => date('Y-m-01'),
            'end_date' => date('Y-m-t')
        ];
        
        $summary = $reportModel->getSummary($filters);
        $transactions = $reportModel->getReport($filters);

        // Buat spreadsheet baru
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set judul
        $sheet->setCellValue('A1', 'LAPORAN ARUS KAS');
        $sheet->setCellValue('A2', 'Periode: ' . date('F Y'));
        $sheet->mergeCells('A1:G1');
        $sheet->mergeCells('A2:G2');

        // Style untuk judul
        $sheet->getStyle('A1:A2')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1:A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Ringkasan
        $sheet->setCellValue('A4', 'RINGKASAN');
        $sheet->mergeCells('A4:G4');
        $sheet->getStyle('A4')->getFont()->setBold(true);

        $sheet->setCellValue('A5', 'Total Pemasukan');
        $sheet->setCellValue('B5', 'Rp ' . number_format($summary['total_income'] ?? 0, 0, ',', '.'));
        $sheet->setCellValue('A6', 'Total Pengeluaran');
        $sheet->setCellValue('B6', 'Rp ' . number_format($summary['total_expense'] ?? 0, 0, ',', '.'));
        $sheet->setCellValue('A7', 'Arus Kas Bersih');
        $sheet->setCellValue('B7', 'Rp ' . number_format(($summary['total_income'] ?? 0) - ($summary['total_expense'] ?? 0), 0, ',', '.'));
        
        // Style untuk ringkasan
        $sheet->getStyle('A5:A7')->getFont()->setBold(true);
        $sheet->getStyle('B5:B7')->getNumberFormat()->setFormatCode('#,##0');
        
        // Header tabel transaksi
        $sheet->setCellValue('A9', 'No');
        $sheet->setCellValue('B9', 'Tanggal');
        $sheet->setCellValue('C9', 'Tipe');
        $sheet->setCellValue('D9', 'Akun');
        $sheet->setCellValue('E9', 'Kategori');
        $sheet->setCellValue('F9', 'Deskripsi');
        $sheet->setCellValue('G9', 'Jumlah');

        // Style untuk header tabel
        $headerStyle = [
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E2E8F0'],
            ],
        ];
        $sheet->getStyle('A9:G9')->applyFromArray($headerStyle);

        // Isi data transaksi
        $row = 10;
        foreach ($transactions as $key => $transaction) {
            $sheet->setCellValue('A' . $row, $key + 1);
            $sheet->setCellValue('B' . $row, date('d/m/Y', strtotime($transaction['tanggal'])));
            $sheet->setCellValue('C' . $row, $transaction['tipe'] === 'income' ? 'Pemasukan' : 'Pengeluaran');
            $sheet->setCellValue('D' . $row, $transaction['account_name']);
            $sheet->setCellValue('E' . $row, $transaction['category_name']);
            $sheet->setCellValue('F' . $row, $transaction['deskripsi']);
            $sheet->setCellValue('G' . $row, $transaction['jumlah']);
            
            // Warna untuk tipe transaksi
            if ($transaction['tipe'] === 'income') {
                $sheet->getStyle('G' . $row)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('228B22'));
            } else {
                $sheet->getStyle('G' . $row)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('DC143C'));
            }
            
            $row++;
        }

        // Style untuk seluruh data
        $lastRow = $row - 1;
        $sheet->getStyle('A10:G' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN],
            ],
        ]);
        
        // Set lebar kolom otomatis
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Format angka untuk kolom jumlah
        $sheet->getStyle('G10:G' . $lastRow)->getNumberFormat()->setFormatCode('#,##0');
        
        // Alignment
        $sheet->getStyle('A10:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('G10:G' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        // Set nama file
        $filename = 'Laporan_Arus_Kas_' . date('Y-m') . '.xlsx';

        // Set header untuk download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        // Export ke Excel
        $writer = new Xlsx($spreadsheet);
        ob_end_clean();
        $writer->save('php://output');
        exit();
    }

    /**************************************
     * BAGIAN 2: LAPORAN BUDGET
     **************************************/
    
    public function budget()
    {
        $reportModel = new ReportModel();
        
        // Get filter parameters
        $period = $this->request->getGet('period') ?? date('Y-m');
        
        // Get data from model
        $categories = $reportModel->getBudgetVsActual($period);
        
        // Calculate summary
        $summary = [
            'total_budget' => array_sum(array_column($categories, 'budget')),
            'total_actual' => array_sum(array_column($categories, 'actual'))
        ];

        $data = [
            'pageTitle' => 'Laporan Budget vs Aktual',
            'title' => 'Laporan Budget vs Aktual | Aplikasi Keuangan',
            'categories' => $categories,
            'summary' => $summary,
            'period' => $period
        ];

        return view('Reports/budget_actual', $data);
    }

    public function exportBudgetPDF()
    {
        $reportModel = new ReportModel();
        $period = $this->request->getPost('period') ?? date('Y-m');
        
        $categories = $reportModel->getBudgetVsActual($period);
        $summary = [
            'total_budget' => array_sum(array_column($categories, 'budget')),
            'total_actual' => array_sum(array_column($categories, 'actual'))
        ];

        $data = [
            'title' => 'Laporan Budget vs Aktual',
            'period' => $period,
            'categories' => $categories,
            'summary' => $summary
        ];

        // Load Dompdf
        $dompdf = new \Dompdf\Dompdf();
        $options = new \Dompdf\Options();
        $options->setIsRemoteEnabled(true);
        $dompdf->setOptions($options);

        // Load view ke HTML
        $html = view('Reports/pdf_budget', $data);

        // Convert ke PDF
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Download file
        $filename = 'Laporan_Budget_vs_Aktual_' . $period . '.pdf';
        return $dompdf->stream($filename, ['Attachment' => false]);
    }

    public function exportBudgetExcel()
    {
        $reportModel = new ReportModel();
        $period = $this->request->getPost('period') ?? date('Y-m');
        
        $categories = $reportModel->getBudgetVsActual($period);
        $summary = [
            'total_budget' => array_sum(array_column($categories, 'budget')),
            'total_actual' => array_sum(array_column($categories, 'actual'))
        ];

        // Buat spreadsheet baru
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Budget vs Aktual');

        // Set judul
        $sheet->setCellValue('A1', 'LAPORAN BUDGET VS AKTUAL');
        $sheet->setCellValue('A2', 'Periode: ' . date('F Y', strtotime($period)));
        $sheet->mergeCells('A1:G1');
        $sheet->mergeCells('A2:G2');

        // Style untuk judul
        $titleStyle = [
            'font' => [
                'bold' => true,
                'size' => 14
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E2E8F0']
            ]
        ];
        $sheet->getStyle('A1:G2')->applyFromArray($titleStyle);

        // Ringkasan
        $sheet->setCellValue('A4', 'RINGKASAN');
        $sheet->mergeCells('A4:G4');
        $sheet->getStyle('A4')->getFont()->setBold(true);
        $sheet->getStyle('A4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E2E8F0');

        // Data ringkasan
        $sheet->setCellValue('A5', 'Total Budget:');
        $sheet->setCellValue('B5', $summary['total_budget']);
        $sheet->setCellValue('A6', 'Total Aktual:');
        $sheet->setCellValue('B6', $summary['total_actual']);
        $sheet->setCellValue('A7', 'Selisih Budget:');
        $sheet->setCellValue('B7', $summary['total_budget'] - $summary['total_actual']);

        // Style untuk ringkasan
        $sheet->getStyle('A5:A7')->getFont()->setBold(true);
        $sheet->getStyle('B5:B7')->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('A5:G7')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // Header detail
        $sheet->setCellValue('A9', 'DETAIL BUDGET PER KATEGORI');
        $sheet->mergeCells('A9:G9');
        $sheet->getStyle('A9')->getFont()->setBold(true);
        $sheet->getStyle('A9')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E2E8F0');

        // Header tabel
        $sheet->setCellValue('A10', 'No');
        $sheet->setCellValue('B10', 'Kategori');
        $sheet->setCellValue('C10', 'Budget');
        $sheet->setCellValue('D10', 'Aktual');
        $sheet->setCellValue('E10', 'Progress');
        $sheet->setCellValue('F10', 'Status');

        // Style untuk header tabel
        $headerStyle = [
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN]
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E2E8F0']
            ]
        ];
        $sheet->getStyle('A10:F10')->applyFromArray($headerStyle);

        // Isi data
        $row = 11;
        foreach ($categories as $key => $category) {
            $sheet->setCellValue('A' . $row, $key + 1);
            $sheet->setCellValue('B' . $row, $category['nama_kategori']);
            $sheet->setCellValue('C' . $row, $category['budget']);
            $sheet->setCellValue('D' . $row, $category['actual']);
            $sheet->setCellValue('E' . $row, number_format($category['percentage'], 1) . '%');
            $sheet->setCellValue('F' . $row, $category['percentage'] > 100 ? 'Melebihi Budget' : 'Sesuai Budget');
            
            // Style untuk status
            if ($category['percentage'] > 100) {
                $sheet->getStyle('F' . $row)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('DC143C'));
            } else {
                $sheet->getStyle('F' . $row)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('228B22'));
            }
            
            $row++;
        }

        // Style untuk seluruh data
        $lastRow = $row - 1;
        $sheet->getStyle('A11:F' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN]
            ]
        ]);

        // Format angka dan alignment
        $sheet->getStyle('C11:D' . $lastRow)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('A11:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E11:F' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Set lebar kolom otomatis
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Tambah footer
        $row = $lastRow + 2;
        $sheet->setCellValue('A' . $row, 'Digenerate pada: ' . date('d/m/Y H:i:s'));
        $sheet->mergeCells('A' . $row . ':F' . $row);
        $sheet->getStyle('A' . $row)->getFont()->setItalic(true);

        // Set nama file
        $filename = 'Laporan_Budget_vs_Aktual_' . $period . '.xlsx';

        // Set header untuk download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        // Export ke Excel
        $writer = new Xlsx($spreadsheet);
        ob_end_clean();
        $writer->save('php://output');
        exit();
    }

    /**************************************
     * BAGIAN 3: LAPORAN KATEGORI
     **************************************/

    private function formatPeriode($period) 
    {
        $dates = $this->calculateDateRange($period);
        $startDate = date('d F Y', strtotime($dates['start_date']));
        $endDate = date('d F Y', strtotime($dates['end_date']));
        
        switch ($period) {
            case 'this_month':
                return 'Bulan Ini (' . $startDate . ' - ' . $endDate . ')';
            case 'last_month':
                return 'Bulan Lalu (' . $startDate . ' - ' . $endDate . ')';
            case 'this_year':
                return 'Tahun Ini (' . $startDate . ' - ' . $endDate . ')';
            case 'last_30_days':
                return '30 Hari Terakhir (' . $startDate . ' - ' . $endDate . ')';
            default:
                return $startDate . ' - ' . $endDate;
            
        }
    }

    public function category()
    {
        $reportModel = new ReportModel();
        
        // Get filter parameters
        $period = $this->request->getGet('period') ?? 'this_month';
        $type = $this->request->getGet('type') ?? 'all';
        
        // Calculate date range based on period
        $dateRange = $this->calculateDateRange($period);
        $startDate = $dateRange['start_date'];
        $endDate = $dateRange['end_date'];
        
        // Prepare filters
        $filters = [
            'start_date' => $startDate,
            'end_date' => $endDate
        ];
        
        if ($type !== 'all') {
            $filters['tipe'] = $type;
        }

        // Get transactions grouped by category
        $transactions = $reportModel->getReport($filters);
        
        // Process data for categories
        $categoryTotals = [];
        foreach ($transactions as $transaction) {
            $categoryId = $transaction['category_id'];
            if (!isset($categoryTotals[$categoryId])) {
                $categoryTotals[$categoryId] = [
                    'nama_kategori' => $transaction['category_name'],
                    'tipe' => $transaction['tipe'],
                    'total' => 0,
                    'jumlah_transaksi' => 0,
                    'transactions' => []
                ];
            }
            $categoryTotals[$categoryId]['total'] += $transaction['jumlah'];
            $categoryTotals[$categoryId]['jumlah_transaksi']++;
            $categoryTotals[$categoryId]['transactions'][] = $transaction;
        }

        // Calculate grand total
        $grandTotal = array_sum(array_column($categoryTotals, 'total'));

        // Convert to array and calculate percentages
        $categories = [];
        foreach ($categoryTotals as $categoryData) {
            $categoryData['percentage'] = $grandTotal > 0 ? ($categoryData['total'] / $grandTotal * 100) : 0;
            $categories[] = $categoryData;
        }

        // Sort categories by total amount
        usort($categories, function($a, $b) {
            return $b['total'] - $a['total'];
        });

        $data = [
            'pageTitle' => 'Laporan Kategori',
            'title' => 'Laporan Kategori | Aplikasi Keuangan',
            'categories' => $categories,
            'grandTotal' => $grandTotal,
            'period' => $period,
            'type' => $type,
            'startDate' => $startDate,
            'endDate' => $endDate
        ];

        return view('Reports/category', $data);
    }

    private function calculateDateRange($period)
    {
        $now = new \DateTime();
        
        switch ($period) {
            case 'this_month':
                $startDate = $now->format('Y-m-01');
                $endDate = $now->format('Y-m-t');
                break;
                
            case 'last_month':
                $now->modify('-1 month');
                $startDate = $now->format('Y-m-01');
                $endDate = $now->format('Y-m-t');
                break;
                
            case 'this_year':
                $startDate = $now->format('Y-01-01');
                $endDate = $now->format('Y-12-31');
                break;
                
            case 'last_year':
                $now->modify('-1 year');
                $startDate = $now->format('Y-01-01');
                $endDate = $now->format('Y-12-31');
                break;
                
            case 'last_30_days':
                $endDate = $now->format('Y-m-d');
                $now->modify('-30 days');
                $startDate = $now->format('Y-m-d');
                break;
                
            default:
                $startDate = $now->format('Y-m-01');
                $endDate = $now->format('Y-m-t');
        }
        
        return [
            'start_date' => $startDate,
            'end_date' => $endDate
        ];
    }

    public function exportCategoryPDF()
    {
        $reportModel = new ReportModel();
        
        // Get filter parameters
        $period = $this->request->getPost('period') ?? 'this_month';
        $type = $this->request->getPost('type') ?? 'all';
        
        // Calculate date range
        $dateRange = $this->calculateDateRange($period);
        
        // Prepare filters
        $filters = [
            'start_date' => $dateRange['start_date'],
            'end_date' => $dateRange['end_date']
        ];
        
        if ($type !== 'all') {
            $filters['tipe'] = $type;
        }
        
        // Get category report data
        $categories = $reportModel->getCategoryReport($filters);
        
        // Prepare data for view
        $data = [
            'categories' => $categories,
            'title' => 'Laporan Kategori',
            'periode' => $this->formatPeriode($period)
        ];

        // Initialize DOMPDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        
        $dompdf = new Dompdf($options);
        
        // Load the view
        $html = view('Reports/pdf_category', $data);
        
        // Load HTML content
        $dompdf->loadHtml($html);
        
        // Set paper size and orientation
        $dompdf->setPaper('A4', 'portrait');
        
        // Render PDF
        $dompdf->render();
        
        // Stream PDF to browser
        $dompdf->stream('kategori.pdf', ['Attachment' => 0]);
    }

    public function exportCategoryExcel()
    {
        $reportModel = new ReportModel();
        
        // Get filter parameters
        $period = $this->request->getPost('period') ?? 'this_month';
        $type = $this->request->getPost('type') ?? 'all';
        
        // Calculate date range
        $dateRange = $this->calculateDateRange($period);
        
        // Prepare filters
        $filters = [
            'start_date' => $dateRange['start_date'],
            'end_date' => $dateRange['end_date']
        ];
        
        if ($type !== 'all') {
            $filters['tipe'] = $type;
        }
        
        // Get category report data
        $categories = $reportModel->getCategoryReport($filters);
        
        // Hitung total
        $totalAmount = array_sum(array_column($categories, 'total'));
        
        // Buat spreadsheet baru
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Kategori');

        // Set judul
        $sheet->setCellValue('A1', 'LAPORAN KATEGORI');
        $sheet->setCellValue('A2', 'Periode: ' . $this->formatPeriode($period));
        $sheet->mergeCells('A1:F1');
        $sheet->mergeCells('A2:F2');

        // Style untuk judul
        $titleStyle = [
            'font' => [
                'bold' => true,
                'size' => 14
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E2E8F0']
            ]
        ];
        $sheet->getStyle('A1:F2')->applyFromArray($titleStyle);

        // Header tabel
        $sheet->setCellValue('A4', 'No');
        $sheet->setCellValue('B4', 'Kategori');
        $sheet->setCellValue('C4', 'Tipe');
        $sheet->setCellValue('D4', 'Jumlah Transaksi');
        $sheet->setCellValue('E4', 'Total');
        $sheet->setCellValue('F4', 'Persentase');

        // Style untuk header tabel
        $headerStyle = [
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E2E8F0']
            ]
        ];
        $sheet->getStyle('A4:F4')->applyFromArray($headerStyle);

        // Isi data kategori
        $row = 5;
        $no = 1;
        foreach ($categories as $category) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $category['nama_kategori']);
            $sheet->setCellValue('C' . $row, $category['tipe'] === 'income' ? 'Pemasukan' : 'Pengeluaran');
            $sheet->setCellValue('D' . $row, $category['jumlah_transaksi']);
            $sheet->setCellValue('E' . $row, $category['total']);
            $sheet->setCellValue('F' . $row, ($category['total'] / $totalAmount) * 100);

            // Warna untuk tipe transaksi
            $textColor = $category['tipe'] === 'income' ? '28a745' : 'dc3545';
            $sheet->getStyle('C' . $row)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color($textColor));
            
            $row++;
        }

        // Total row
        $lastRow = $row - 1;
        $totalRow = $row;
        $sheet->setCellValue('A' . $totalRow, 'Total');
        $sheet->mergeCells('A' . $totalRow . ':C' . $totalRow);
        $sheet->setCellValue('D' . $totalRow, '=SUM(D5:D' . $lastRow . ')');
        $sheet->setCellValue('E' . $totalRow, '=SUM(E5:E' . $lastRow . ')');
        $sheet->setCellValue('F' . $totalRow, '100');

        // Style untuk total
        $sheet->getStyle('A' . $totalRow . ':F' . $totalRow)->getFont()->setBold(true);
        $sheet->getStyle('A' . $totalRow . ':F' . $totalRow)->getFill()
            ->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F8F9FA');

        // Format angka
        $sheet->getStyle('D5:D' . $totalRow)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('E5:E' . $totalRow)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('F5:F' . $totalRow)->getNumberFormat()->setFormatCode('#,##0.0"%"');

        // Alignment
        $sheet->getStyle('A5:A' . $totalRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('C5:C' . $totalRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D5:F' . $totalRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        // Border untuk seluruh tabel
        $sheet->getStyle('A4:F' . $totalRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // Set lebar kolom otomatis
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Set nama file
        $filename = 'Laporan_Kategori_' . str_replace(' ', '_', $this->formatPeriode($period)) . '.xlsx';

        // Set header untuk download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        // Export ke Excel
        $writer = new Xlsx($spreadsheet);
        ob_end_clean();
        $writer->save('php://output');
        exit();
    }
    
    /**************************************
     * BAGIAN 4: LAPORAN SALDO PER AKUN
     **************************************/
    
    public function account()
    {
        $reportModel = new ReportModel();
        
        // Get filter parameters
        $period = $this->request->getGet('period') ?? 'this_month';
        
        // Calculate date range
        $dateRange = $this->calculateDateRange($period);
        
        // Get account balances and movements
        $accounts = $reportModel->getAccountBalances($dateRange);
        $totalBalance = array_sum(array_column($accounts, 'saldo_akhir'));
        
        return view('Reports/account', [
            'period' => $period,
            'accounts' => $accounts,
            'totalBalance' => $totalBalance
        ]);
    }

    public function exportAccountPDF()
    {
        $reportModel = new ReportModel();
        
        // Ambil periode dari form
        $period = $this->request->getPost('period') ?? 'this_month';
        
        // Hitung range tanggal berdasarkan periode
        $dateRange = $this->calculateDateRange($period);
        
        // Ambil data akun beserta saldo dan mutasinya
        $accounts = $reportModel->getAccountBalances($dateRange);
        
        // Format periode untuk ditampilkan
        $periode = $this->formatPeriode($period);
        
        // Persiapkan data untuk view
        $data = [
            'accounts' => $accounts,
            'periode' => $periode
        ];

        // Load Dompdf
        $options = new Options();
        $options->setIsRemoteEnabled(true);
        $dompdf = new Dompdf($options);

        // Load view ke HTML
        $html = view('Reports/pdf_account', $data);

        // Convert ke PDF
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Download file
        $filename = 'Laporan_Saldo_per_Akun_' . date('Y-m') . '.pdf';
        return $dompdf->stream($filename, ['Attachment' => false]);
    }

    public function exportAccountExcel()
    {
        $reportModel = new ReportModel();
        
        // Ambil periode dari form
        $period = $this->request->getPost('period') ?? 'this_month';
        
        // Hitung range tanggal berdasarkan periode
        $dateRange = $this->calculateDateRange($period);
        
        // Ambil data akun beserta saldo dan mutasinya
        $accounts = $reportModel->getAccountBalances($dateRange);
        
        // Hitung total
        $totalSaldoAwal = array_sum(array_column($accounts, 'saldo_awal'));
        $totalIncome = array_sum(array_column($accounts, 'total_income'));
        $totalExpense = array_sum(array_column($accounts, 'total_expense'));
        $totalMutasi = array_sum(array_column($accounts, 'mutasi'));
        $totalSaldoAkhir = array_sum(array_column($accounts, 'saldo_akhir'));
        
        // Buat spreadsheet baru
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Saldo per Akun');

        // Set judul
        $sheet->setCellValue('A1', 'LAPORAN SALDO PER AKUN');
        $sheet->setCellValue('A2', 'Periode: ' . $this->formatPeriode($period));
        $sheet->mergeCells('A1:G1');
        $sheet->mergeCells('A2:G2');

        // Style untuk judul
        $titleStyle = [
            'font' => [
                'bold' => true,
                'size' => 14
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E2E8F0']
            ]
        ];
        $sheet->getStyle('A1:G2')->applyFromArray($titleStyle);

        // Header tabel
        $sheet->setCellValue('A4', 'No');
        $sheet->setCellValue('B4', 'Nama Akun');
        $sheet->setCellValue('C4', 'Saldo Awal');
        $sheet->setCellValue('D4', 'Total Masuk');
        $sheet->setCellValue('E4', 'Total Keluar');
        $sheet->setCellValue('F4', 'Mutasi');
        $sheet->setCellValue('G4', 'Saldo Akhir');

        // Style untuk header tabel
        $headerStyle = [
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E2E8F0']
            ]
        ];
        $sheet->getStyle('A4:G4')->applyFromArray($headerStyle);

        // Isi data akun
        $row = 5;
        $no = 1;
        foreach ($accounts as $account) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $account['nama_akun']);
            $sheet->setCellValue('C' . $row, $account['saldo_awal']);
            $sheet->setCellValue('D' . $row, $account['total_income']);
            $sheet->setCellValue('E' . $row, $account['total_expense']);
            $sheet->setCellValue('F' . $row, $account['mutasi']);
            $sheet->setCellValue('G' . $row, $account['saldo_akhir']);
            
            // Warna untuk mutasi
            if ($account['mutasi'] >= 0) {
                $sheet->getStyle('F' . $row)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('28a745'));
            } else {
                $sheet->getStyle('F' . $row)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('dc3545'));
            }
            
            $row++;
        }

        // Total row
        $lastRow = $row - 1;
        $totalRow = $row;
        $sheet->setCellValue('A' . $totalRow, 'Total');
        $sheet->mergeCells('A' . $totalRow . ':B' . $totalRow);
        $sheet->setCellValue('C' . $totalRow, $totalSaldoAwal);
        $sheet->setCellValue('D' . $totalRow, $totalIncome);
        $sheet->setCellValue('E' . $totalRow, $totalExpense);
        $sheet->setCellValue('F' . $totalRow, $totalMutasi);
        $sheet->setCellValue('G' . $totalRow, $totalSaldoAkhir);

        // Style untuk total
        $sheet->getStyle('A' . $totalRow . ':G' . $totalRow)->getFont()->setBold(true);
        $sheet->getStyle('A' . $totalRow . ':G' . $totalRow)->getFill()
            ->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F8F9FA');

        // Format angka
        $sheet->getStyle('C5:G' . $totalRow)->getNumberFormat()->setFormatCode('#,##0');

        // Alignment
        $sheet->getStyle('A5:A' . $totalRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('C5:G' . $totalRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        // Border untuk seluruh tabel
        $sheet->getStyle('A4:G' . $totalRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // Set lebar kolom otomatis
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Set nama file
        $filename = 'Laporan_Saldo_per_Akun_' . str_replace(' ', '_', $this->formatPeriode($period)) . '.xlsx';

        // Set header untuk download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        // Export ke Excel
        $writer = new Xlsx($spreadsheet);
        ob_end_clean();
        $writer->save('php://output');
        exit();
    }

    /**************************************
     * BAGIAN 5: LAPORAN TREN BULANAN
     **************************************/
    public function trend()
    {
        $reportModel = new ReportModel();
        
        // Ambil parameter filter tahun
        $year = $this->request->getGet('year') ?? date('Y');
        
        // Set rentang tanggal untuk Year-to-Date
        $startDate = $year . '-01-01';
        $endDate = $year . '-12-31';
        
        // Siapkan filter
        $filters = [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'year' => $year
        ];
        
        // Ambil data untuk tabel dan grafik sekaligus
        $monthlyData = $reportModel->db->table('transactions')
            ->select([
                "DATE_FORMAT(tanggal, '%Y-%m') as month",
                "COALESCE(SUM(CASE WHEN tipe = 'income' THEN jumlah ELSE 0 END), 0) as income",
                "COALESCE(SUM(CASE WHEN tipe = 'expense' THEN jumlah ELSE 0 END), 0) as expense",
                "DATE_FORMAT(tanggal, '%m') as bulan_angka"
            ])
            ->where('YEAR(tanggal)', $year)
            ->groupBy(['month', 'bulan_angka'])
            ->orderBy('month', 'ASC')
            ->get()
            ->getResultArray();
        
        // Data akan digunakan untuk grafik dan tabel
        $currentYearData = [];
        
        for ($i = 1; $i <= 12; $i++) {
            $month = str_pad($i, 2, '0', STR_PAD_LEFT);
            
            // Data tahun ini
            $currentMonthFilters = [
                'start_date' => "$year-$month-01",
                'end_date' => date('Y-m-t', strtotime("$year-$month-01"))
            ];
            
            // Ubah query untuk mendapatkan total income dan expense
            $builder = $reportModel->db->table('transactions')
                ->select([
                    "COALESCE(SUM(CASE WHEN tipe = 'income' THEN jumlah ELSE 0 END), 0) as total_income",
                    "COALESCE(SUM(CASE WHEN tipe = 'expense' THEN jumlah ELSE 0 END), 0) as total_expense"
                ])
                ->where('tanggal >=', $currentMonthFilters['start_date'])
                ->where('tanggal <=', $currentMonthFilters['end_date']);
            
            $currentMonthData = $builder->get()->getRowArray();
            
            // Data tahun lalu untuk perbandingan
            $prevYear = $year - 1;
            $previousMonthFilters = [
                'start_date' => "$prevYear-$month-01",
                'end_date' => date('Y-m-t', strtotime("$prevYear-$month-01"))
            ];
            
            $builder = $reportModel->db->table('transactions')
                ->select([
                    "COALESCE(SUM(CASE WHEN tipe = 'income' THEN jumlah ELSE 0 END), 0) as total_income",
                    "COALESCE(SUM(CASE WHEN tipe = 'expense' THEN jumlah ELSE 0 END), 0) as total_expense"
                ])
                ->where('tanggal >=', $previousMonthFilters['start_date'])
                ->where('tanggal <=', $previousMonthFilters['end_date']);
            
            $previousMonthData = $builder->get()->getRowArray();
            
            // Hitung pertumbuhan YoY
            $currentTotal = ($currentMonthData['total_income'] ?? 0) - ($currentMonthData['total_expense'] ?? 0);
            $previousTotal = ($previousMonthData['total_income'] ?? 0) - ($previousMonthData['total_expense'] ?? 0);
            
            $yoyGrowth = $previousTotal != 0 ? (($currentTotal - $previousTotal) / abs($previousTotal)) * 100 : 0;
            
            $currentYearData[] = [
                'bulan' => date('F', strtotime("$year-$month-01")),
                'pemasukan' => (float)($currentMonthData['total_income'] ?? 0),
                'pengeluaran' => (float)($currentMonthData['total_expense'] ?? 0),
                'selisih' => $currentTotal,
                'yoy_growth' => $yoyGrowth
            ];
        }

        // Format data untuk grafik dan tabel
        $chartData = [];
        $months = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
            '04' => 'April', '05' => 'Mei', '06' => 'Juni',
            '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
            '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        ];

        // Inisialisasi data untuk setiap bulan
        foreach ($months as $monthNum => $monthName) {
            $monthKey = $year . '-' . $monthNum;
            $chartData[$monthKey] = [
                'bulan' => $monthName,
                'pemasukan' => 0,
                'pengeluaran' => 0,
                'selisih' => 0
            ];
        }

        // Isi data aktual
        foreach ($monthlyData as $data) {
            if (isset($chartData[$data['month']])) {
                $income = (float)$data['income'];
                $expense = (float)$data['expense'];
                $chartData[$data['month']]['pemasukan'] = $income;
                $chartData[$data['month']]['pengeluaran'] = $expense;
                $chartData[$data['month']]['selisih'] = $income - $expense;
            }
        }

        // Sort by month and convert to indexed array
        ksort($chartData);
        $chartData = array_values($chartData);

        // Tampilkan view dengan data
        return view('Reports/trend', [
            'currentYearData' => $currentYearData,
            'chartData' => $chartData,
            'selectedYear' => $year
        ]);
    }

    public function exportTrendPDF()
    {
        // Ambil parameter filter
        $year = $this->request->getPost('year') ?? date('Y');
        
        // Load model
        $reportModel = new ReportModel();
        
        // Set rentang tanggal untuk data bulanan
        $startDate = $year . '-01-01';
        $endDate = $year . '-12-31';
        
        // Ambil data bulanan
        $monthlyData = [];
        for ($i = 1; $i <= 12; $i++) {
            $month = str_pad($i, 2, '0', STR_PAD_LEFT);
            
            // Filter untuk bulan ini
            $monthFilters = [
                'year' => $year,
                'month' => $month
            ];
            
            $monthData = $reportModel->getSummary($monthFilters);
            
            $monthlyData[] = [
                'month' => "$year-$month",
                'income' => $monthData['total_income'] ?? 0,
                'expense' => $monthData['total_expense'] ?? 0
            ];
        }
        
        // Siapkan data untuk view PDF
        $data = [
            'selectedYear' => $year,
            'monthlyData' => $monthlyData
        ];
        
        // Load view ke HTML
        $html = view('Reports/pdf_trend', $data);
        
        // Buat PDF
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        // Kirim file PDF ke browser
        $dompdf->stream("laporan-tren-$year.pdf", ['Attachment' => false]);
        exit();
    }
    
    public function exportTrendExcel()
    {
        // Ambil parameter filter
        $year = $this->request->getPost('year') ?? date('Y');
        
        // Load model
        $reportModel = new ReportModel();
        
        // Set rentang tanggal untuk data bulanan
        $startDate = $year . '-01-01';
        $endDate = $year . '-12-31';
        
        // Ambil data perbandingan bulanan
        $currentYearData = [];
        $totalIncome = 0;
        $totalExpense = 0;
        
        for ($i = 1; $i <= 12; $i++) {
            $month = str_pad($i, 2, '0', STR_PAD_LEFT);
            
            // Data tahun ini
            $currentMonthFilters = [
                'start_date' => "$year-$month-01",
                'end_date' => date('Y-m-t', strtotime("$year-$month-01"))
            ];
            $currentMonthData = $reportModel->getSummary($currentMonthFilters);
            
            // Data tahun lalu untuk perbandingan
            $prevYear = $year - 1;
            $previousMonthFilters = [
                'start_date' => "$prevYear-$month-01",
                'end_date' => date('Y-m-t', strtotime("$prevYear-$month-01"))
            ];
            $previousMonthData = $reportModel->getSummary($previousMonthFilters);
            
            // Hitung pertumbuhan YoY
            $currentTotal = ($currentMonthData['total_income'] ?? 0) - ($currentMonthData['total_expense'] ?? 0);
            $previousTotal = ($previousMonthData['total_income'] ?? 0) - ($previousMonthData['total_expense'] ?? 0);
            $yoyGrowth = $previousTotal != 0 ? (($currentTotal - $previousTotal) / abs($previousTotal)) * 100 : 0;
            
            $income = $currentMonthData['total_income'] ?? 0;
            $expense = $currentMonthData['total_expense'] ?? 0;
            
            $totalIncome += $income;
            $totalExpense += $expense;
            
            $currentYearData[] = [
                'bulan' => date('F', strtotime("$year-$month-01")),
                'pemasukan' => $income,
                'pengeluaran' => $expense,
                'selisih' => $currentTotal,
                'yoy_growth' => $yoyGrowth
            ];
        }

        // Buat spreadsheet baru
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Tren ' . $year);

        // Set judul
        $sheet->setCellValue('A1', 'LAPORAN TREN BULANAN');
        $sheet->setCellValue('A2', 'Tahun: ' . $year);
        $sheet->mergeCells('A1:F1');
        $sheet->mergeCells('A2:F2');

        // Style untuk judul
        $sheet->getStyle('A1:A2')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1:A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Header tabel
        $sheet->setCellValue('A4', 'No');
        $sheet->setCellValue('B4', 'Bulan');
        $sheet->setCellValue('C4', 'Pemasukan');
        $sheet->setCellValue('D4', 'Pengeluaran');
        $sheet->setCellValue('E4', 'Selisih');
        $sheet->setCellValue('F4', 'YoY Growth');

        // Style untuk header
        $sheet->getStyle('A4:F4')->getFont()->setBold(true);
        $sheet->getStyle('A4:F4')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('E2E8F0');

        // Isi data
        $row = 5;
        foreach ($currentYearData as $index => $data) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $data['bulan']);
            $sheet->setCellValue('C' . $row, $data['pemasukan']);
            $sheet->setCellValue('D' . $row, $data['pengeluaran']);
            $sheet->setCellValue('E' . $row, $data['selisih']);
            $sheet->setCellValue('F' . $row, $data['yoy_growth']);
            $row++;
        }

        // Tambah row total
        $lastRow = $row - 1;
        $totalRow = $row;
        
        $sheet->setCellValue('A' . $totalRow, 'Total');
        $sheet->mergeCells('A' . $totalRow . ':B' . $totalRow);
        $sheet->setCellValue('C' . $totalRow, '=SUM(C5:C' . $lastRow . ')');
        $sheet->setCellValue('D' . $totalRow, '=SUM(D5:D' . $lastRow . ')');
        $sheet->setCellValue('E' . $totalRow, '=SUM(E5:E' . $lastRow . ')');
        $sheet->setCellValue('F' . $totalRow, '-');

        // Style untuk total
        $sheet->getStyle('A' . $totalRow . ':F' . $totalRow)->getFont()->setBold(true);
        $sheet->getStyle('A' . $totalRow . ':F' . $totalRow)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('F3F4F6');

        // Format angka
        $sheet->getStyle('C5:E' . $totalRow)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('F5:F' . $lastRow)->getNumberFormat()->setFormatCode('#,##0.0"%"');

        // Alignment
        $sheet->getStyle('A5:A' . $totalRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('C5:F' . $totalRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        // Border untuk seluruh tabel
        $sheet->getStyle('A4:F' . $totalRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // Set lebar kolom otomatis
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Set nama file
        $filename = 'Laporan_Tren_Bulanan_' . $year . '.xlsx';

        // Set header untuk download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        // Export ke Excel
        $writer = new Xlsx($spreadsheet);
        ob_end_clean();
        $writer->save('php://output');
        exit();
    }

}
