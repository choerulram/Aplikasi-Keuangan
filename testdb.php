<?php
// File testdb.php untuk cek koneksi MySQL manual dari PHP CLI
$mysqli = new mysqli('localhost', 'root', '', 'aplikasi_keuangan');
if ($mysqli->connect_errno) {
    echo 'Gagal konek: ' . $mysqli->connect_error;
} else {
    echo 'Koneksi BERHASIL!';
}
