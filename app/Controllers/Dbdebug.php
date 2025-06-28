<?php

// Debug koneksi database detail

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\Database\BaseConnection;

class Dbdebug extends Controller
{
    public function index()
    {
        try {
            $db = \Config\Database::connect();
            $output = '';
            // Paksa koneksi
            $db->connect();
            if ($db instanceof BaseConnection && $db->connID) {
                $output .= 'Koneksi database BERHASIL!';
            } else {
                $output .= 'Koneksi database GAGAL!<br>';
            }
            $output .= '<br><b>Config:</b><br>';
            $output .= 'Host: ' . $db->hostname . '<br>';
            $output .= 'Database: ' . $db->database . '<br>';
            $output .= 'Username: ' . $db->username . '<br>';
            $output .= 'Driver: ' . $db->DBDriver . '<br>';
            $output .= '<br><b>Error Info:</b><br>';
            // Jalankan query dummy untuk memastikan error bisa diambil
            $db->query('SELECT 1');
            $dbError = $db->error();
            if (!empty($dbError['message'])) {
                $output .= 'Error Code: ' . $dbError['code'] . '<br>';
                $output .= 'Error Message: ' . $dbError['message'] . '<br>';
            } else {
                $output .= 'Tidak ada error.';
            }
            echo $output;
        } catch (\Throwable $e) {
            echo 'Exception: ' . $e->getMessage();
        }
    }
}
