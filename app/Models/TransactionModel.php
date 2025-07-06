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
}
