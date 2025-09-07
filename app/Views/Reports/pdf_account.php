<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Saldo per Akun</title>
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
        .text-positive {
            color: #28a745;
        }
        .text-negative {
            color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Saldo per Akun</h1>
        <p>Periode: <?= $periode ?></p>
        <p>Tanggal Cetak: <?= date('d/m/Y H:i:s') ?></p>
    </div>

    <h3>Rincian Saldo per Akun</h3>
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 25%;">Nama Akun</th>
                <th style="width: 20%;" class="text-right">Saldo Awal</th>
                <th style="width: 20%;" class="text-right">Mutasi</th>
                <th style="width: 30%;" class="text-right">Saldo Akhir</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach ($accounts as $account): ?>
            <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td><?= esc($account['nama_akun']) ?></td>
                <td class="text-right">Rp <?= number_format($account['saldo_awal'], 0, ',', '.') ?></td>
                <td class="text-right <?= $account['mutasi'] >= 0 ? 'text-positive' : 'text-negative' ?>">
                    <?= $account['mutasi'] >= 0 ? '+' : '' ?>Rp <?= number_format($account['mutasi'], 0, ',', '.') ?>
                </td>
                <td class="text-right">Rp <?= number_format($account['saldo_akhir'], 0, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2" class="text-right">Total</th>
                <th class="text-right">Rp <?= number_format(array_sum(array_column($accounts, 'saldo_awal')), 0, ',', '.') ?></th>
                <th class="text-right">Rp <?= number_format(array_sum(array_column($accounts, 'mutasi')), 0, ',', '.') ?></th>
                <th class="text-right">Rp <?= number_format(array_sum(array_column($accounts, 'saldo_akhir')), 0, ',', '.') ?></th>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>* Laporan ini digenerate secara otomatis oleh sistem</p>
    </div>
</body>
</html>
