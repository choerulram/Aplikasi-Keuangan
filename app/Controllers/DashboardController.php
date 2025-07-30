<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DashboardModel;

class DashboardController extends BaseController
{
    protected $dashboardModel;

    public function __construct()
    {
        $this->dashboardModel = new DashboardModel();
    }

    public function index()
    {
        // Get user ID from session
        $userId = session()->get('user_id');

        if (!$userId) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        // Get all required data from model
        $data = [
            'pageTitle' => 'Dashboard',
            'title' => 'Dashboard | Aplikasi Keuangan',
            
            // Get financial summary
            'totalBalance' => $this->dashboardModel->getTotalBalance($userId),
            'monthlyIncome' => $this->dashboardModel->getMonthlyIncome($userId),
            'monthlyExpense' => $this->dashboardModel->getMonthlyExpense($userId),
            
            // Get accounts data
            'accounts' => $this->dashboardModel->getAccounts($userId),
            
            // Get budget status
            'budgets' => $this->dashboardModel->getBudgets($userId),
            
            // Get chart data
            'chartData' => $this->dashboardModel->getChartData($userId),
            
            // Get recent transactions
            'recentTransactions' => $this->dashboardModel->getRecentTransactions($userId),
            
            // Current date for display
            'currentMonth' => date('F Y'),
            'today' => date('l, d F Y')
        ];

        // Debug: Cek data budget
        var_dump($data['budgets']);
        

        return view('Dashboard/index', $data);
    }
}
