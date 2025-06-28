<?php
namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'username' => 'admin',
                'email' => 'admin@mail.com',
                'password_hash' => '$2y$10$adminhash',
                'nama' => 'Administrator',
                'role' => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 2,
                'username' => 'budi',
                'email' => 'budi@mail.com',
                'password_hash' => '$2y$10$budihash',
                'nama' => 'Budi Santoso',
                'role' => 'user',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 3,
                'username' => 'sari',
                'email' => 'sari@mail.com',
                'password_hash' => '$2y$10$sarihash',
                'nama' => 'Sari Dewi',
                'role' => 'user',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];
        $this->db->table('users')->insertBatch($data);
    }
}
