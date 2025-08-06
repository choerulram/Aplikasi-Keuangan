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

    public function getBudgetsByType($userId, $type, $isAdmin = false, $perPage = null, $group = 'budgets')
    {
        try {
            $select = 'budgets.*, categories.nama_kategori, categories.tipe, users.username';
            $this->select($select);
            $this->join('categories', 'categories.id = budgets.category_id', 'left');
            $this->join('users', 'users.id = budgets.user_id', 'left');
            
            // Filter berdasarkan tipe (income/expense)
            $this->where('categories.tipe', $type);

            // Jika bukan admin, hanya tampilkan data user tersebut
            if (!$isAdmin) {
                $this->where('budgets.user_id', $userId);
            }

            $this->orderBy('budgets.periode', 'DESC');
            
            // Jika perPage diset, gunakan pagination
            if ($perPage !== null) {
                return $this->paginate($perPage, $group);
            }
            
            return $this->findAll();

        } catch (\Exception $e) {
            log_message('error', 'Error in getBudgetsByType: ' . $e->getMessage());
            return [];
        }
    }

    public function getTotalBudgetsByType($userId, $type, $isAdmin = false)
    {
        try {
            $builder = $this->db->table('budgets')
                ->join('categories', 'categories.id = budgets.category_id')
                ->where('categories.tipe', $type);
            
            if (!$isAdmin) {
                $builder->where('budgets.user_id', $userId);
            }
            
            return $builder->countAllResults();
        } catch (\Exception $e) {
            log_message('error', 'Error in getTotalBudgetsByType: ' . $e->getMessage());
            return 0;
        }
    }

    public function getBudgetsByUser($userId, $isAdmin = false, $perPage = null, $group = 'budgets')
    {
        try {
            $select = 'budgets.*, categories.nama_kategori, categories.tipe, users.username';
            $this->select($select);
            $this->join('categories', 'categories.id = budgets.category_id', 'left');
            $this->join('users', 'users.id = budgets.user_id', 'left');

            // Jika bukan admin, hanya tampilkan data user tersebut
            if (!$isAdmin) {
                $this->where('budgets.user_id', $userId);
            }

            $this->orderBy('budgets.periode', 'DESC');
            
            // Jika perPage diset, gunakan pagination
            if ($perPage !== null) {
                return $this->paginate($perPage, $group);
            }
            
            return $this->findAll();

        } catch (\Exception $e) {
            log_message('error', 'Error in getBudgetsByUser: ' . $e->getMessage());
            return [];
        }
    }

    public function getTotalBudgets($userId, $isAdmin = false)
    {
        try {
            $builder = $this->db->table('budgets');
            
            if (!$isAdmin) {
                $builder->where('user_id', $userId);
            }
            
            return $builder->countAllResults();
        } catch (\Exception $e) {
            log_message('error', 'Error in getTotalBudgets: ' . $e->getMessage());
            return 0;
        }
    }

    public function getCurrentUsage($categoryId, $periode)
    {
        try {
            // Dapatkan tipe kategori (income/expense)
            $category = $this->db->table('categories')
                ->select('tipe')
                ->where('id', $categoryId)
                ->get()
                ->getRow();

            if (!$category) {
                return 0.0;
            }

            $builder = $this->db->table('transactions');
            $result = $builder->select('COALESCE(SUM(jumlah), 0) as total')
                ->where([
                    'category_id' => $categoryId,
                    'tipe' => $category->tipe
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
