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

    protected $lastQuery;

    public function getBudgetsByUser($userId, $isAdmin = false)
    {
        try {
            // Debug user input
            echo "<div style='background: #e3f2fd; padding: 10px; margin: 10px; border: 1px solid #90caf9;'>";
            echo "<pre>";
            echo "Input Debug:\n";
            echo "User ID from parameter: " . ($userId ?? 'NULL') . "\n";
            echo "Is Admin: " . ($isAdmin ? 'true' : 'false') . "\n";
            echo "</pre>";
            echo "</div>";

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
            
            // Store the query for debugging
            $this->lastQuery = $builder->getCompiledSelect();
            
            $result = $builder->get()->getResultArray();

            // Debug query result
            echo "<div style='background: #e8f5e9; padding: 10px; margin: 10px; border: 1px solid #a5d6a7;'>";
            echo "<pre>";
            echo "Query Result Debug:\n";
            echo "Generated SQL: " . $this->lastQuery . "\n\n";
            echo "Result Count: " . count($result) . "\n\n";
            if (!empty($result)) {
                echo "First Row: \n";
                print_r($result[0]);
                echo "\n\nAvailable Keys:\n";
                print_r(array_keys($result[0]));
            } else {
                echo "\nNo Results Found\n";
                echo "Last Error: " . $this->db->error()['message'] . "\n";
                echo "Last Error Code: " . $this->db->error()['code'] . "\n";
            }
            echo "</pre>";
            echo "</div>";

            return $result;

        } catch (\Exception $e) {
            echo "<div style='background: #ffebee; padding: 10px; margin: 10px; border: 1px solid #ffcdd2;'>";
            echo "<h3>Error in getBudgetsByUser:</h3>";
            echo "<pre>";
            echo "Error: " . $e->getMessage() . "\n";
            echo "Stack trace: " . $e->getTraceAsString();
            echo "</pre>";
            echo "</div>";
            return [];
        }
    }

    public function getLastQuery()
    {
        return $this->lastQuery ?? 'No query executed yet';
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
