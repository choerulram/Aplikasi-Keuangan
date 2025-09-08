<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Tren Bulanan</title>
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
        <h1>Laporan Tren Bulanan</h1>
        <p>Tahun: <?= $selectedYear ?></p>
        <p>Tanggal Cetak: <?= date('d/m/Y H:i:s') ?></p>
    </div>

    <h3>Perbandingan Bulanan</h3>
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 20%;">Bulan</th>
                <th style="width: 25%;" class="text-right">Pemasukan</th>
                <th style="width: 25%;" class="text-right">Pengeluaran</th>
                <th style="width: 25%;" class="text-right">Selisih</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            $months = [
                '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
                '04' => 'April', '05' => 'Mei', '06' => 'Juni',
                '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
                '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
            ];
            $totalIncome = 0;
            $totalExpense = 0;
            foreach ($monthlyData as $data): 
                $monthNum = substr($data['month'], -2);
                $monthName = $months[$monthNum];
                $selisih = $data['income'] - $data['expense'];
                $totalIncome += $data['income'];
                $totalExpense += $data['expense'];
            ?>
            <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td><?= $monthName ?></td>
                <td class="text-right">Rp <?= number_format($data['income'], 0, ',', '.') ?></td>
                <td class="text-right">Rp <?= number_format($data['expense'], 0, ',', '.') ?></td>
                <td class="text-right <?= $selisih >= 0 ? 'text-positive' : 'text-negative' ?>">
                    Rp <?= number_format(abs($selisih), 0, ',', '.') ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2" class="text-right">Total</th>
                <th class="text-right">Rp <?= number_format($totalIncome, 0, ',', '.') ?></th>
                <th class="text-right">Rp <?= number_format($totalExpense, 0, ',', '.') ?></th>
                <th class="text-right <?= ($totalIncome - $totalExpense) >= 0 ? 'text-positive' : 'text-negative' ?>">
                    Rp <?= number_format(abs($totalIncome - $totalExpense), 0, ',', '.') ?>
                </th>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>* Laporan ini digenerate secara otomatis oleh sistem</p>
    </div>
</body>
</html>
