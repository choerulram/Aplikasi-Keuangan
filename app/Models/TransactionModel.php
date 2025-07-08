<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id', 'account_id', 'category_id', 'tipe', 'jumlah', 'tanggal', 'deskripsi', 'lampiran', 'created_at', 'updated_at'
    ];
    protected $useTimestamps = true;
    protected $returnType = 'array';

    public function getTransactions($userId = null, $isAdmin = false)
    {
        $builder = $this->select('transactions.*, users.username, accounts.nama_akun, categories.nama_kategori')
            ->join('users', 'users.id = transactions.user_id', 'left')
            ->join('accounts', 'accounts.id = transactions.account_id', 'left')
            ->join('categories', 'categories.id = transactions.category_id', 'left');
        if (!$isAdmin && $userId !== null) {
            $builder->where('transactions.user_id', $userId);
        }
        $builder->orderBy('transactions.tanggal', 'DESC');
        return $builder->findAll();
    }

    public function getTransactionsByType($type, $userId = null, $isAdmin = false)
    {
        $builder = $this->select('transactions.*, users.username, accounts.nama_akun, categories.nama_kategori')
            ->join('users', 'users.id = transactions.user_id', 'left')
            ->join('accounts', 'accounts.id = transactions.account_id', 'left')
            ->join('categories', 'categories.id = transactions.category_id', 'left')
            ->where('transactions.tipe', $type);
        if (!$isAdmin && $userId !== null) {
            $builder->where('transactions.user_id', $userId);
        }
        $builder->orderBy('transactions.tanggal', 'DESC');
        return $builder->findAll();
    }

    /**
     * Ambil transaksi dengan filter, search, dan pagination
     */
    public function getFilteredTransactions($type, $userId, $isAdmin, $filter = [], $perPage = 10, $group = 'transactions')
    {
        $builder = $this->select('transactions.*, users.username, accounts.nama_akun, categories.nama_kategori')
            ->join('users', 'users.id = transactions.user_id', 'left')
            ->join('accounts', 'accounts.id = transactions.account_id', 'left')
            ->join('categories', 'categories.id = transactions.category_id', 'left')
            ->where('transactions.tipe', $type);

        if (!$isAdmin && $userId !== null) {
            $builder->where('transactions.user_id', $userId);
        }
        if (!empty($filter['search'])) {
            $builder->like('transactions.deskripsi', $filter['search']);
        }
        if (!empty($filter['account'])) {
            $builder->where('transactions.account_id', $filter['account']);
        }
        if (!empty($filter['category'])) {
            $builder->where('transactions.category_id', $filter['category']);
        }
        if (!empty($filter['date'])) {
            $builder->where('transactions.tanggal', $filter['date']);
        }

        $builder->orderBy('transactions.tanggal', 'DESC');
        return $builder->paginate($perPage, $group);
    }

    /**
     * Hitung total transaksi dengan filter/search
     */
    public function getFilteredTransactionsCount($type, $userId, $isAdmin, $filter = [])
    {
        $builder = $this->where('transactions.tipe', $type);
        if (!$isAdmin && $userId !== null) {
            $builder->where('transactions.user_id', $userId);
        }
        if (!empty($filter['search'])) {
            $builder->like('transactions.deskripsi', $filter['search']);
        }
        if (!empty($filter['account'])) {
            $builder->where('transactions.account_id', $filter['account']);
        }
        if (!empty($filter['category'])) {
            $builder->where('transactions.category_id', $filter['category']);
        }
        if (!empty($filter['date'])) {
            $builder->where('transactions.tanggal', $filter['date']);
        }
        return $builder->countAllResults();
    }
}
