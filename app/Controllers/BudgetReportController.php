<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ReportModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BudgetReportController extends BaseController
{
    public function exportExcel()
    {
        $reportModel = new ReportModel();
        $period = $this->request->getPost('period') ?? date('Y-m');
        
        // Ambil data
        $categories = $reportModel->getBudgetVsActual($period);
        
        // Hitung summary
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
        $sheet->mergeCells('A1:F1');
        $sheet->mergeCells('A2:F2');

        // Style untuk judul
        $sheet->getStyle('A1:A2')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1:A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:F2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F3F4F6');

        // Ringkasan
        $sheet->setCellValue('A4', 'RINGKASAN');
        $sheet->mergeCells('A4:F4');
        $sheet->getStyle('A4')->getFont()->setBold(true);
        $sheet->getStyle('A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E5E7EB');

        // Data ringkasan
        $sheet->setCellValue('A5', 'Total Budget');
        $sheet->setCellValue('B5', $summary['total_budget']);
        $sheet->setCellValue('A6', 'Total Aktual');
        $sheet->setCellValue('B6', $summary['total_actual']);
        $sheet->setCellValue('A7', 'Selisih');
        $sheet->setCellValue('B7', $summary['total_budget'] - $summary['total_actual']);
        
        // Style untuk ringkasan
        $sheet->getStyle('A5:A7')->getFont()->setBold(true);
        $sheet->getStyle('B5:B7')->getNumberFormat()->setFormatCode('#,##0');

        // Header tabel detail
        $sheet->setCellValue('A9', 'DETAIL PER KATEGORI');
        $sheet->mergeCells('A9:F9');
        $sheet->getStyle('A9')->getFont()->setBold(true);
        $sheet->getStyle('A9')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A9')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E5E7EB');

        // Header kolom
        $headers = ['No', 'Kategori', 'Budget', 'Aktual', 'Persentase', 'Status'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '10', $header);
            $col++;
        }

        // Style untuk header kolom
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '3B82F6'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
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

            // Style untuk baris data
            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('C' . $row . ':D' . $row)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('E' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            
            // Warna untuk status
            if ($category['percentage'] > 100) {
                $sheet->getStyle('F' . $row)->getFont()->getColor()->setRGB('DC2626');
            } else {
                $sheet->getStyle('F' . $row)->getFont()->getColor()->setRGB('059669');
            }

            $row++;
        }

        // Style untuk seluruh tabel
        $lastRow = $row - 1;
        $tableBorderStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'D1D5DB'],
                ],
            ],
        ];
        $sheet->getStyle('A10:F' . $lastRow)->applyFromArray($tableBorderStyle);

        // Set lebar kolom otomatis
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Footer
        $row = $lastRow + 2;
        $sheet->setCellValue('A' . $row, 'Digenerate pada: ' . date('d/m/Y H:i:s'));
        $sheet->mergeCells('A' . $row . ':F' . $row);
        $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('A' . $row)->getFont()->setItalic(true);

        // Set nama file dan header untuk download
        $filename = 'Laporan_Budget_vs_Aktual_' . $period . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        // Tulis ke output
        $writer = new Xlsx($spreadsheet);
        ob_end_clean();
        $writer->save('php://output');
        exit();
    }
}
