<?php

namespace App\Models;

use CodeIgniter\Model;

class ReportModel extends Model
{
    protected $table = 'transactions';
    protected $allowedFields = [
        'id', 'user_id', 'account_id', 'category_id', 'tipe', 'jumlah', 'tanggal', 'deskripsi', 'lampiran', 'created_at', 'updated_at'
    ];

    public function getReport($filters = [], $limit = null, $offset = 0)
    {
        $builder = $this->db->table($this->table)
            ->select('transactions.*, accounts.nama_akun as account_name, categories.nama_kategori as category_name, users.username')
            ->join('accounts', 'accounts.id = transactions.account_id', 'left')
            ->join('categories', 'categories.id = transactions.category_id', 'left')
            ->join('users', 'users.id = transactions.user_id', 'left');

        if (!empty($filters['account_id'])) {
            $builder->where('transactions.account_id', $filters['account_id']);
        }
        if (!empty($filters['category_id'])) {
            $builder->where('transactions.category_id', $filters['category_id']);
        }
        if (!empty($filters['tipe'])) {
            $builder->where('transactions.tipe', $filters['tipe']);
        }
        if (!empty($filters['month'])) {
            $builder->where('MONTH(transactions.tanggal)', $filters['month']);
        }
        if (!empty($filters['year'])) {
            $builder->where('YEAR(transactions.tanggal)', $filters['year']);
        }
        if (!empty($filters['start_date'])) {
            $builder->where('transactions.tanggal >=', $filters['start_date']);
        }
        if (!empty($filters['end_date'])) {
            $builder->where('transactions.tanggal <=', $filters['end_date']);
        }

        return $builder->orderBy('transactions.tanggal', 'DESC')
                      ->limit($limit, $offset)
                      ->get()
                      ->getResultArray();
    }

    public function getTotal($filters = [])
    {
        $builder = $this->db->table($this->table);

        if (!empty($filters['account_id'])) {
            $builder->where('account_id', $filters['account_id']);
        }
        if (!empty($filters['category_id'])) {
            $builder->where('category_id', $filters['category_id']);
        }
        if (!empty($filters['tipe'])) {
            $builder->where('tipe', $filters['tipe']);
        }
        if (!empty($filters['month'])) {
            $builder->where('MONTH(tanggal)', $filters['month']);
        }
        if (!empty($filters['year'])) {
            $builder->where('YEAR(tanggal)', $filters['year']);
        }
        if (!empty($filters['start_date'])) {
            $builder->where('tanggal >=', $filters['start_date']);
        }
        if (!empty($filters['end_date'])) {
            $builder->where('tanggal <=', $filters['end_date']);
        }

        return $builder->countAllResults();
    }

    public function getSummary($filters = [])
    {
        $builder = $this->db->table($this->table)
            ->select([
                "SUM(CASE WHEN tipe = 'income' THEN jumlah ELSE 0 END) as total_income",
                "SUM(CASE WHEN tipe = 'expense' THEN jumlah ELSE 0 END) as total_expense"
            ]);

        if (!empty($filters['account_id'])) {
            $builder->where('account_id', $filters['account_id']);
        }
        if (!empty($filters['category_id'])) {
            $builder->where('category_id', $filters['category_id']);
        }
        if (!empty($filters['tipe'])) {
            $builder->where('tipe', $filters['tipe']);
        }
        if (!empty($filters['month'])) {
            $builder->where('MONTH(tanggal)', $filters['month']);
        }
        if (!empty($filters['year'])) {
            $builder->where('YEAR(tanggal)', $filters['year']);
        }
        if (!empty($filters['start_date'])) {
            $builder->where('tanggal >=', $filters['start_date']);
        }
        if (!empty($filters['end_date'])) {
            $builder->where('tanggal <=', $filters['end_date']);
        }

        return $builder->get()->getRowArray();
    }

    public function getMonthlyTotals($startDate = null, $endDate = null, $period = 'this_month')
    {
        $builder = $this->db->table($this->table);
        
        if ($period === 'monthly') {
            // Untuk tampilan bulanan (per hari)
            $builder->select([
                "DATE(tanggal) as tanggal",
                "DAY(tanggal) as month",
                "COALESCE(SUM(CASE WHEN tipe = 'income' THEN jumlah ELSE 0 END), 0) as income",
                "COALESCE(SUM(CASE WHEN tipe = 'expense' THEN jumlah ELSE 0 END), 0) as expense"
            ])
            ->groupBy(['tanggal', 'month']);
        } else {
            // Untuk tampilan tahunan (per bulan)
            $builder->select([
                "DATE_FORMAT(tanggal, '%Y-%m-01') as tanggal",
                "DATE_FORMAT(tanggal, '%M %Y') as month",
                "COALESCE(SUM(CASE WHEN tipe = 'income' THEN jumlah ELSE 0 END), 0) as income",
                "COALESCE(SUM(CASE WHEN tipe = 'expense' THEN jumlah ELSE 0 END), 0) as expense"
            ])
            ->groupBy('tanggal, month');
        }

        $builder->where('tanggal >=', $startDate)
                ->where('tanggal <=', $endDate)
                ->orderBy('tanggal', 'ASC');

        // Get the raw SQL for debugging if needed
        // $sql = $builder->getCompiledSelect(false);
        // log_message('debug', 'SQL Query: ' . $sql);

        $result = $builder->get()->getResultArray();

        // Convert numeric strings to floats
        foreach ($result as &$row) {
            $row['income'] = (float)$row['income'];
            $row['expense'] = (float)$row['expense'];
        }

        // Fill missing dates
        $filledData = $this->fillMissingDates($result, $startDate, $endDate, $period);
        
        return $filledData;
    }

    public function getAccountBalances($dateRange)
    {
        // Get initial balances
        $accounts = $this->db->table('accounts')
            ->select('accounts.*, users.username')
            ->join('users', 'users.id = accounts.user_id')
            ->get()
            ->getResultArray();

        // Calculate movements and final balances
        foreach ($accounts as &$account) {
            // Get income transactions
            $income = $this->db->table('transactions')
                ->selectSum('jumlah')
                ->where('account_id', $account['id'])
                ->where('tipe', 'income')
                ->where('tanggal >=', $dateRange['start_date'])
                ->where('tanggal <=', $dateRange['end_date'])
                ->get()
                ->getRowArray();

            // Get expense transactions
            $expense = $this->db->table('transactions')
                ->selectSum('jumlah')
                ->where('account_id', $account['id'])
                ->where('tipe', 'expense')
                ->where('tanggal >=', $dateRange['start_date'])
                ->where('tanggal <=', $dateRange['end_date'])
                ->get()
                ->getRowArray();

            // Calculate movements and final balance
            $account['total_income'] = $income['jumlah'] ?? 0;
            $account['total_expense'] = $expense['jumlah'] ?? 0;
            $account['mutasi'] = $account['total_income'] - $account['total_expense'];
            $account['saldo_akhir'] = $account['saldo_awal'] + $account['mutasi'];
        }

        return $accounts;
    }

    public function getCategoryReport($filters = [])
    {
        $builder = $this->db->table('categories')
            ->select([
                'categories.id',
                'categories.nama_kategori',
                'categories.tipe',
                'COUNT(transactions.id) as jumlah_transaksi',
                'COALESCE(SUM(transactions.jumlah), 0) as total'
            ])
            ->join('transactions', 'transactions.category_id = categories.id', 'left');

        if (!empty($filters['start_date'])) {
            $builder->where('transactions.tanggal >=', $filters['start_date']);
        }
        if (!empty($filters['end_date'])) {
            $builder->where('transactions.tanggal <=', $filters['end_date']);
        }
        if (!empty($filters['tipe'])) {
            $builder->where('categories.tipe', $filters['tipe']);
        }

        return $builder->groupBy(['categories.id', 'categories.nama_kategori', 'categories.tipe'])
                      ->orderBy('total', 'DESC')
                      ->get()
                      ->getResultArray();
    }

    public function getBudgetVsActual($period)
    {
        // Get all expense categories
        $categoryQuery = $this->db->table('categories')
            ->where('tipe', 'expense')
            ->get();
        $categories = $categoryQuery->getResultArray();

        // For each category, get budget and actual spending
        foreach ($categories as &$category) {
            // Get budget
            $budgetQuery = $this->db->table('budgets')
                ->where('category_id', $category['id'])
                ->where('periode', $period)
                ->get();
            $budget = $budgetQuery->getRowArray();
            
            // Get actual spending
            $actualQuery = $this->db->table('transactions')
                ->selectSum('jumlah')
                ->where('category_id', $category['id'])
                ->where('tipe', 'expense')
                ->where('DATE_FORMAT(tanggal, "%Y-%m")', $period)
                ->get();
            $actual = $actualQuery->getRowArray();

            // Calculate percentage
            $category['budget'] = $budget['jumlah_anggaran'] ?? 0;
            $category['actual'] = $actual['jumlah'] ?? 0;
            $category['percentage'] = $category['budget'] > 0 
                ? ($category['actual'] / $category['budget']) * 100 
                : 0;
        }

        return $categories;
    }

    private function fillMissingDates($data, $startDate, $endDate, $viewType)
    {
        $start = new \DateTime($startDate);
        $end = new \DateTime($endDate);
        
        if ($viewType === 'monthly') {
            $interval = 'P1D';
            $end->modify('+1 day');
        } else {
            $interval = 'P1M';
            $end->modify('first day of next month');
        }

        $dateInterval = new \DateInterval($interval);
        $datePeriod = new \DatePeriod($start, $dateInterval, $end);

        // Index data by date key for faster lookup
        $indexedData = [];
        foreach ($data as $row) {
            $indexedData[$row['month']] = $row;
        }

        $filledData = [];
        foreach ($datePeriod as $date) {
            $key = $viewType === 'monthly'
                ? $date->format('j') // Menggunakan j untuk hari tanpa leading zero
                : $date->format('F Y'); // Bulan dan tahun untuk tampilan tahunan

            if (isset($indexedData[$key])) {
                // Data exists for this date, use it
                $filledData[] = [
                    'month' => $key,
                    'income' => (float)$indexedData[$key]['income'],
                    'expense' => (float)$indexedData[$key]['expense']
                ];
            } else {
                // No data for this date, fill with zeros
                $filledData[] = [
                    'month' => $key,
                    'income' => 0,
                    'expense' => 0
                ];
            }
        }

        return $filledData;
    }
}
