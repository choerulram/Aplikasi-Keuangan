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
        .text-success {
            color: #28a745;
        }
        .text-danger {
            color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1><?= $title ?></h1>
        <p><?= $periode ?></p>
    </div>

    <div class="summary-box">
        <h3>Ringkasan</h3>
        <table>
            <tr>
                <td>Total Pemasukan:</td>
                <td class="text-right text-success">Rp <?= number_format($summary['total_income'] ?? 0, 0, ',', '.') ?></td>
            </tr>
            <tr>
                <td>Total Pengeluaran:</td>
                <td class="text-right text-danger">Rp <?= number_format($summary['total_expense'] ?? 0, 0, ',', '.') ?></td>
            </tr>
            <tr>
                <td><strong>Arus Kas Bersih:</strong></td>
                <td class="text-right"><strong>Rp <?= number_format(($summary['total_income'] ?? 0) - ($summary['total_expense'] ?? 0), 0, ',', '.') ?></strong></td>
            </tr>
        </table>
    </div>

    <h3>Detail Transaksi</h3>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Tipe</th>
                <th>Akun</th>
                <th>Kategori</th>
                <th>Deskripsi</th>
                <th class="text-right">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($transactions as $transaction): ?>
            <tr>
                <td><?= date('d/m/Y', strtotime($transaction['tanggal'])) ?></td>
                <td><?= $transaction['tipe'] === 'income' ? 'Pemasukan' : 'Pengeluaran' ?></td>
                <td><?= $transaction['account_name'] ?></td>
                <td><?= $transaction['category_name'] ?></td>
                <td><?= $transaction['deskripsi'] ?></td>
                <td class="text-right <?= $transaction['tipe'] === 'income' ? 'text-success' : 'text-danger' ?>">
                    Rp <?= number_format($transaction['jumlah'], 0, ',', '.') ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
