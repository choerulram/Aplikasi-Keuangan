<?php
namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AccountsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'user_id' => 1,
                'nama_akun' => 'Kas Utama',
                'tipe_akun' => 'cash',
                'saldo_awal' => 1000000,
                'catatan' => 'Kas kantor utama',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 2,
                'user_id' => 2,
                'nama_akun' => 'Rekening BCA',
                'tipe_akun' => 'bank',
                'saldo_awal' => 2500000,
                'catatan' => 'Tabungan pribadi Budi',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 3,
                'user_id' => 3,
                'nama_akun' => 'E-Wallet OVO',
                'tipe_akun' => 'ewallet',
                'saldo_awal' => 500000,
                'catatan' => 'Saldo OVO Sari',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];
        $this->db->table('accounts')->insertBatch($data);
    }
}
