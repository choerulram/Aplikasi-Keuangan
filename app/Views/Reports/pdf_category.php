<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?= $title ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .summary-box {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ddd;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .income {
            color: #28a745;
        }
        .expense {
            color: #dc3545;
        }
        .summary-total {
            font-weight: bold;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1><?= $title ?></h1>
        <p>Periode: <?= $periode ?></p>
        <p>Tanggal Cetak: <?= date('d/m/Y H:i:s') ?></p>
    </div>

    <h3>Detail Transaksi per Kategori</h3>
    <table>
        <thead>
            <tr>
                <th style="width: 5%;" class="text-center">No</th>
                <th style="width: 25%;">Kategori</th>
                <th style="width: 15%;" class="text-center">Tipe</th>
                <th style="width: 15%;" class="text-right">Jumlah Transaksi</th>
                <th style="width: 25%;" class="text-right">Total</th>
                <th style="width: 15%;" class="text-center">Persentase</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1; 
            $totalAmount = array_sum(array_column($categories, 'total')); 
            ?>
            <?php foreach ($categories as $category): ?>
            <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td><?= esc($category['nama_kategori']) ?></td>
                <td class="text-center <?= $category['tipe'] === 'income' ? 'income' : 'expense' ?>">
                    <?= $category['tipe'] === 'income' ? 'Pemasukan' : 'Pengeluaran' ?>
                </td>
                <td class="text-right"><?= number_format($category['jumlah_transaksi'], 0, ',', '.') ?></td>
                <td class="text-right">Rp <?= number_format($category['total'], 0, ',', '.') ?></td>
                <td class="text-center"><?= number_format(($category['total'] / $totalAmount) * 100, 1) ?>%</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr class="summary-total">
                <td colspan="3" class="text-right">Total Keseluruhan</td>
                <td class="text-right"><?= number_format(array_sum(array_column($categories, 'jumlah_transaksi')), 0, ',', '.') ?></td>
                <td class="text-right">Rp <?= number_format($totalAmount, 0, ',', '.') ?></td>
                <td class="text-center">100%</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer" style="margin-top: 20px;">
        <p>* Laporan ini digenerate secara otomatis oleh sistem</p>
    </div>
</body>
</html>
