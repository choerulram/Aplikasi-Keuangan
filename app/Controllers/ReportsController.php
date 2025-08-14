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
