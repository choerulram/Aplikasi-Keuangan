<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAccountsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'nama_akun' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'tipe_akun' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'saldo_awal' => [
                'type'       => 'DECIMAL',
                'constraint' => '18,2',
                'default'    => 0,
            ],
            'catatan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
            'updated_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('accounts');
    }

    public function down()
    {
        $this->forge->dropTable('accounts');
    }
}
