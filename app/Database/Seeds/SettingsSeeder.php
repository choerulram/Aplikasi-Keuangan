<?php
namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'user_id' => 1,
                'tema' => 'dark',
                'bahasa' => 'id',
                'preferensi_lain' => '{"notif":true}',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 2,
                'user_id' => 2,
                'tema' => 'default',
                'bahasa' => 'id',
                'preferensi_lain' => '{"notif":false}',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 3,
                'user_id' => 3,
                'tema' => 'light',
                'bahasa' => 'en',
                'preferensi_lain' => '{"notif":true}',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];
        $this->db->table('settings')->insertBatch($data);
    }
}
