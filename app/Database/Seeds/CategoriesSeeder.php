<?php
namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'user_id' => 1,
                'nama_kategori' => 'Gaji',
                'tipe' => 'income',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 2,
                'user_id' => 2,
                'nama_kategori' => 'Makan',
                'tipe' => 'expense',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 3,
                'user_id' => 3,
                'nama_kategori' => 'Transportasi',
                'tipe' => 'expense',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];
        $this->db->table('categories')->insertBatch($data);
    }
}
