<?php
namespace App\Models;

use CodeIgniter\Model;

class AccountModel extends Model
{
    protected $table = 'accounts';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id', 'nama_akun', 'tipe_akun', 'saldo_awal', 'catatan', 'created_at', 'updated_at'
    ];
}
