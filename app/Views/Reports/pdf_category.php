<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #333;
            font-size: 24px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 12px;
            color: #666;
            padding: 10px 0;
        }
        .status {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 14px;
        }
        .active {
            background-color: #dcf5dc;
            color: #0a5d0a;
        }
        .inactive {
            background-color: #ffe6e6;
            color: #cc0000;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1><?= $title ?></h1>
        <p>Dicetak pada: <?= date('d/m/Y H:i') ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Kategori</th>
                <th>Deskripsi</th>
                <th>Tipe</th>
                <th>Status</th>
                <th>Tanggal Dibuat</th>
                <th>Terakhir Diubah</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            <?php foreach ($categories as $category): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= esc($category['nama_kategori']) ?></td>
                <td><?= esc($category['catatan'] ?? '-') ?></td>
                <td><?= $category['tipe'] == 'income' ? 'Pemasukan' : 'Pengeluaran' ?></td>
                <td>
                    <span class="status <?= $category['status'] ? 'active' : 'inactive' ?>">
                        <?= $category['status'] ? 'Aktif' : 'Tidak Aktif' ?>
                    </span>
                </td>
                <td><?= $category['created_at'] ?></td>
                <td><?= $category['updated_at'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="footer">
        <p>© <?= date('Y') ?> Aplikasi Keuangan - Laporan Kategori</p>
    </div>
</body>
</html>
