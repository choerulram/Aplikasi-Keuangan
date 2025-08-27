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
        .text-over {
            color: #dc3545;
        }
        .text-under {
            color: #28a745;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Budget vs Aktual</h1>
        <p>Periode: <?= date('F Y', strtotime($period)) ?></p>
        <p>Tanggal Cetak: <?= date('d/m/Y H:i:s') ?></p>
    </div>

    <div class="summary-box">
        <h3>Ringkasan</h3>
        <table>
            <tr>
                <td>Total Budget:</td>
                <td class="text-right">Rp <?= number_format($summary['total_budget'] ?? 0, 0, ',', '.') ?></td>
            </tr>
            <tr>
                <td>Total Aktual:</td>
                <td class="text-right">Rp <?= number_format($summary['total_actual'] ?? 0, 0, ',', '.') ?></td>
            </tr>
            <tr>
                <td><strong>Selisih Budget:</strong></td>
                <td class="text-right"><strong>Rp <?= number_format(($summary['total_budget'] ?? 0) - ($summary['total_actual'] ?? 0), 0, ',', '.') ?></strong></td>
            </tr>
        </table>
    </div>

    <h3>Detail Budget per Kategori</h3>
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 25%;">Kategori</th>
                <th style="width: 20%;" class="text-right">Budget</th>
                <th style="width: 20%;" class="text-right">Aktual</th>
                <th style="width: 15%;" class="text-center">Progress</th>
                <th style="width: 15%;" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach ($categories as $category): ?>
            <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td><?= esc($category['nama_kategori']) ?></td>
                <td class="text-right">Rp <?= number_format($category['budget'], 0, ',', '.') ?></td>
                <td class="text-right">Rp <?= number_format($category['actual'], 0, ',', '.') ?></td>
                <td class="text-center"><?= number_format($category['percentage'], 0) ?>%</td>
                <td class="text-center <?= $category['percentage'] > 100 ? 'text-over' : 'text-under' ?>">
                    <?= $category['percentage'] > 100 ? 'Melebihi Budget' : 'Sesuai Budget' ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="footer">
        <p>* Laporan ini digenerate secara otomatis oleh sistem</p>
    </div>
</body>
</html>
