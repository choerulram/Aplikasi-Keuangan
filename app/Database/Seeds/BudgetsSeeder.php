<?php
namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BudgetsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'user_id' => 1,
                'category_id' => 1,
                'jumlah_anggaran' => 5000000,
                'periode' => '2025-06',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 2,
                'user_id' => 2,
                'category_id' => 2,
                'jumlah_anggaran' => 1000000,
                'periode' => '2025-06',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 3,
                'user_id' => 3,
                'category_id' => 3,
                'jumlah_anggaran' => 300000,
                'periode' => '2025-06',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];
        $this->db->table('budgets')->insertBatch($data);
    }
}
