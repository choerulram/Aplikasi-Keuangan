<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table = 'settings';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['user_id', 'tema', 'bahasa', 'preferensi_lain'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'user_id' => 'required|integer',
        'tema' => 'required|in_list[default,light,dark]',
        'bahasa' => 'required|in_list[id,en]'
    ];

    protected $validationMessages = [
        'user_id' => [
            'required' => 'ID pengguna harus diisi',
            'integer' => 'ID pengguna harus berupa angka'
        ],
        'tema' => [
            'required' => 'Tema harus dipilih',
            'in_list' => 'Tema yang dipilih tidak valid'
        ],
        'bahasa' => [
            'required' => 'Bahasa harus dipilih',
            'in_list' => 'Bahasa yang dipilih tidak valid'
        ]
    ];
}