<?php

// File ini untuk cek koneksi database dari CodeIgniter

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\Database\BaseConnection;

class Dbtest extends Controller
{
    public function index()
    {
        try {
            $db = \Config\Database::connect();
            if ($db instanceof BaseConnection && $db->connID) {
                echo 'Koneksi database BERHASIL!';
            } else {
                echo 'Koneksi database GAGAL!';
            }
        } catch (\Throwable $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
}
