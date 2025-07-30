<?php

namespace App\Models;

use CodeIgniter\Model;

class DashboardModel extends Model
{
    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    public function getTotalBalance($userId)
    {
        $result = $this->db->table('accounts')
            ->selectSum('saldo_awal')
            ->where('user_id', $userId)
            ->get()
            ->getRow();
        return $result ? $result->saldo_awal : 0;
    }

    public function getMonthlyIncome($userId)
    {
        $currentMonth = date('Y-m');
        $result = $this->db->table('transactions')
            ->selectSum('jumlah')
            ->where([
                'user_id' => $userId,
                'tipe' => 'income',
                'MONTH(tanggal)' => date('m'),
                'YEAR(tanggal)' => date('Y')
            ])
            ->get()
            ->getRow();
        return $result ? $result->jumlah : 0;
    }

    public function getMonthlyExpense($userId)
    {
        $currentMonth = date('Y-m');
        $result = $this->db->table('transactions')
            ->selectSum('jumlah')
            ->where([
                'user_id' => $userId,
                'tipe' => 'expense',
                'MONTH(tanggal)' => date('m'),
                'YEAR(tanggal)' => date('Y')
            ])
            ->get()
            ->getRow();
        return $result ? $result->jumlah : 0;
    }

    public function getAccounts($userId)
    {
        return $this->db->table('accounts')
            ->select('accounts.*, COALESCE(income.total_income, 0) - COALESCE(expense.total_expense, 0) + accounts.saldo_awal as saldo')
            ->where('accounts.user_id', $userId)
            ->join('(SELECT account_id, SUM(jumlah) as total_income FROM transactions WHERE tipe = "income" GROUP BY account_id) income', 
                   'income.account_id = accounts.id', 'left')
            ->join('(SELECT account_id, SUM(jumlah) as total_expense FROM transactions WHERE tipe = "expense" GROUP BY account_id) expense', 
                   'expense.account_id = accounts.id', 'left')
            ->get()
            ->getResultArray();
    }

    public function getBudgets($userId)
    {
        $currentMonth = date('Y-m');
        return $this->db->table('budgets b')
            ->select('
                c.nama_kategori as kategori,
                b.jumlah_anggaran as `limit`,
                COALESCE(t.total_used, 0) as used,
                ROUND(COALESCE(t.total_used / b.jumlah_anggaran * 100, 0), 1) as percentage
            ')
            ->join('categories c', 'c.id = b.category_id')
            ->join('(
                SELECT category_id, SUM(jumlah) as total_used 
                FROM transactions 
                WHERE DATE_FORMAT(tanggal, "%Y-%m") = "' . $currentMonth . '"
                GROUP BY category_id
            ) t', 't.category_id = b.category_id', 'left')
            ->where('b.user_id', $userId)
            ->where('b.periode', $currentMonth)
            ->get()
            ->getResultArray();
    }

    public function getChartData($userId)
    {
        $currentMonth = date('Y-m');
        $daysInMonth = date('t');
        
        // Initialize data structure
        $data = [
            'labels' => range(1, $daysInMonth),
            'income' => array_fill(0, $daysInMonth, 0),
            'expense' => array_fill(0, $daysInMonth, 0)
        ];

        // Get daily transactions
        $transactions = $this->db->table('transactions')
            ->select('DAY(tanggal) as day, tipe, SUM(jumlah) as total')
            ->where('user_id', $userId)
            ->where('MONTH(tanggal)', date('m'))
            ->where('YEAR(tanggal)', date('Y'))
            ->groupBy('DAY(tanggal), tipe')
            ->get()
            ->getResultArray();

        // Fill the data arrays
        foreach ($transactions as $trx) {
            $index = (int)$trx['day'] - 1;
            if ($trx['tipe'] === 'income') {
                $data['income'][$index] = (float)$trx['total'];
            } else {
                $data['expense'][$index] = (float)$trx['total'];
            }
        }

        return $data;
    }

    public function getRecentTransactions($userId, $limit = 5)
    {
        return $this->db->table('transactions t')
            ->select('t.*, c.nama_kategori as category_name')
            ->join('categories c', 'c.id = t.category_id')
            ->where('t.user_id', $userId)
            ->orderBy('t.tanggal', 'DESC')
            ->orderBy('t.created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }
}
