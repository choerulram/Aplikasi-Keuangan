<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\ReportModel;
use App\Models\AccountModel;
use App\Models\CategoryModel;

class ReportsController extends BaseController
{
    public function cashflow()
    {
        $reportModel = new ReportModel();
        
        // Get period from request
        $period = $this->request->getGet('period') ?? 'this_month';
        $startDate = null;
        $endDate = null;

        // Set date range based on period
        switch ($period) {
            case 'this_month':
                $startDate = date('Y-m-01');
                $endDate = date('Y-m-t');
                break;
            case 'last_month':
                $startDate = date('Y-m-01', strtotime('-1 month'));
                $endDate = date('Y-m-t', strtotime('-1 month'));
                break;
            case 'this_year':
                $startDate = date('Y-01-01');
                $endDate = date('Y-12-31');
                break;
            case 'last_year':
                $startDate = date('Y-01-01', strtotime('-1 year'));
                $endDate = date('Y-12-31', strtotime('-1 year'));
                break;
            case 'custom':
                $startDate = $this->request->getGet('start_date');
                $endDate = $this->request->getGet('end_date');
                break;
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
        $chartData = $reportModel->getMonthlyTotals($startDate, $endDate);

        // Get transactions with pagination
        $perPage = 10;
        $currentPage = (int)($this->request->getGet('page') ?? 1);
        $total = $reportModel->getTotal($filters);
        $transactions = $reportModel->getReport($filters, $perPage, ($currentPage - 1) * $perPage);

        // Configure pagination
        $pager = service('pager');
        $pager->setPath('reports/cashflow');
        $pager->store('default', $currentPage, $perPage, $total);

        return view('Reports/cashflow', [
            'pageTitle' => 'Laporan Arus Kas',
            'title' => 'Laporan Arus Kas | Aplikasi Keuangan',
            'period' => $period,
            'summary' => $summary,
            'chartData' => $chartData,
            'transactions' => $transactions,
            'pager' => $pager,
            'filters' => $filters
        ]);
    }
}
