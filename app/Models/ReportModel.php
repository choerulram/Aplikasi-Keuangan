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
}
