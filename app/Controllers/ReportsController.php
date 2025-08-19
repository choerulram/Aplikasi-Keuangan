<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\ReportModel;
use App\Models\AccountModel;
use App\Models\CategoryModel;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ReportsController extends BaseController
{
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
        return $dompdf->stream($filename);
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
}
