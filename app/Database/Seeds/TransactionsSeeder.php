<?php
namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TransactionsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'user_id' => 1,
                'account_id' => 1,
                'category_id' => 1,
                'tipe' => 'income',
                'jumlah' => 5000000,
                'tanggal' => '2025-06-01',
                'deskripsi' => 'Gaji bulan Juni',
                'lampiran' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 2,
                'user_id' => 2,
                'account_id' => 2,
                'category_id' => 2,
                'tipe' => 'expense',
                'jumlah' => 75000,
                'tanggal' => '2025-06-02',
                'deskripsi' => 'Makan siang',
                'lampiran' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 3,
                'user_id' => 3,
                'account_id' => 3,
                'category_id' => 3,
                'tipe' => 'expense',
                'jumlah' => 20000,
                'tanggal' => '2025-06-03',
                'deskripsi' => 'Naik ojek',
                'lampiran' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];
        $this->db->table('transactions')->insertBatch($data);
    }
}
