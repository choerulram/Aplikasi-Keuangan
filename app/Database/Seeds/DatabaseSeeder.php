<?php
namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call('UsersSeeder');
        $this->call('AccountsSeeder');
        $this->call('CategoriesSeeder');
        $this->call('TransactionsSeeder');
        $this->call('BudgetsSeeder');
        $this->call('SettingsSeeder');
    }
}
