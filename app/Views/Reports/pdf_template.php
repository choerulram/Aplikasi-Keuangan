<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            padding: 0;
            font-size: 18px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .summary {
            margin-bottom: 20px;
        }
        .summary-item {
            margin-bottom: 5px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .filters {
            margin-bottom: 20px;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Transaksi Keuangan</h1>
        <p>Periode: <?= $periode ?></p>
    </div>

    <div class="summary">
        <div class="summary-item">Total Pemasukan: Rp <?= number_format($summary['total_income'] ?? 0, 0, ',', '.') ?></div>
        <div class="summary-item">Total Pengeluaran: Rp <?= number_format($summary['total_expense'] ?? 0, 0, ',', '.') ?></div>
        <div class="summary-item">Saldo Akhir: Rp <?= number_format(($summary['total_income'] ?? 0) - ($summary['total_expense'] ?? 0), 0, ',', '.') ?></div>
    </div>

    <?php if (!empty($filters)): ?>
    <div class="filters">
        Filter yang digunakan:<br>
        <?php if (!empty($filters['account_id'])): ?>Akun: <?= $account_name ?? '-' ?><br><?php endif; ?>
        <?php if (!empty($filters['category_id'])): ?>Kategori: <?= $category_name ?? '-' ?><br><?php endif; ?>
        <?php if (!empty($filters['tipe'])): ?>Tipe: <?= ucfirst($filters['tipe']) ?><br><?php endif; ?>
    </div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Tanggal</th>
                <th>Deskripsi</th>
                <th>Akun</th>
                <th>Kategori</th>
                <th>Tipe</th>
                <th class="text-right">Jumlah</th>
                <?php if (session()->get('role') === 'admin'): ?>
                <th>User</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($transactions)): ?>
                <?php $no = 1; foreach ($transactions as $trx): ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td><?= date('d/m/Y', strtotime($trx['tanggal'])) ?></td>
                        <td><?= esc($trx['deskripsi']) ?></td>
                        <td><?= esc($trx['account_name']) ?></td>
                        <td><?= esc($trx['category_name']) ?></td>
                        <td><?= ucfirst($trx['tipe']) ?></td>
                        <td class="text-right">Rp <?= number_format($trx['jumlah'], 0, ',', '.') ?></td>
                        <?php if (session()->get('role') === 'admin'): ?>
                        <td><?= esc($trx['username']) ?></td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="<?= session()->get('role') === 'admin' ? '8' : '7' ?>" class="text-center">Tidak ada data transaksi.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div style="font-size: 11px; margin-top: 20px;">
        Dicetak pada: <?= date('d/m/Y H:i:s') ?>
    </div>
</body>
</html>
