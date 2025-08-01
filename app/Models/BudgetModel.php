<?php

namespace App\Models;

use CodeIgniter\Model;

class BudgetModel extends Model
{
    protected $table = 'budgets';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['user_id', 'category_id', 'jumlah_anggaran', 'periode'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getBudgetsByUser($userId, $isAdmin = false)
    {
        try {
            $builder = $this->db->table('budgets')
                ->select('budgets.id, budgets.user_id, budgets.category_id, budgets.jumlah_anggaran, 
                         budgets.periode, budgets.created_at, budgets.updated_at,
                         categories.nama_kategori, categories.tipe, 
                         users.username')
                ->join('categories', 'categories.id = budgets.category_id', 'left')
                ->join('users', 'users.id = budgets.user_id', 'left');

            if (!$isAdmin && $userId !== null) {
                $builder->where('budgets.user_id', $userId);
            }

            $builder->orderBy('budgets.periode', 'DESC');
            return $builder->get()->getResultArray();

        } catch (\Exception $e) {
            log_message('error', 'Error in getBudgetsByUser: ' . $e->getMessage());
            return [];
        }
    }

    public function getCurrentUsage($categoryId, $periode)
    {
        try {
            $builder = $this->db->table('transactions');
            $result = $builder->select('COALESCE(SUM(jumlah), 0) as total')
                ->where([
                    'category_id' => $categoryId,
                    'tipe' => 'expense'
                ])
                ->where("DATE_FORMAT(tanggal, '%Y-%m')", $periode)
                ->get()
                ->getRow();

            return (float) ($result->total ?? 0);
        } catch (\Exception $e) {
            log_message('error', 'Error in getCurrentUsage: ' . $e->getMessage());
            return 0.0;
        }
    }
}
