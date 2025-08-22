<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Budget vs Aktual</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 24px;
            margin: 0;
            color: #333;
        }
        .header p {
            font-size: 14px;
            margin: 5px 0;
            color: #666;
        }
        .summary {
            margin-bottom: 30px;
        }
        .summary-box {
            padding: 15px;
            border: 1px solid #ddd;
            margin-bottom: 10px;
            border-radius: 5px;
        }
        .summary-title {
            font-size: 14px;
            font-weight: bold;
            color: #666;
            margin-bottom: 5px;
        }
        .summary-amount {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }
        .table-container {
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
            color: #333;
        }
        .amount {
            text-align: right;
        }
        .percentage {
            text-align: center;
        }
        .over-budget {
            color: #dc2626;
        }
        .under-budget {
            color: #059669;
        }
        .progress-container {
            width: 100%;
            background-color: #f3f4f6;
            border-radius: 4px;
            margin-top: 5px;
        }
        .progress-bar {
            height: 10px;
            border-radius: 4px;
        }
        .category-heading {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            margin: 20px 0 10px 0;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Budget vs Aktual</h1>
        <p>Periode: <?= date('F Y', strtotime($period)) ?></p>
        <p>Tanggal Cetak: <?= date('d/m/Y H:i:s') ?></p>
    </div>

    <div class="summary">
        <div style="width: 100%; overflow: hidden;">
            <div style="width: 33%; float: left;">
                <div class="summary-box">
                    <div class="summary-title">Total Budget</div>
                    <div class="summary-amount">Rp <?= number_format($summary['total_budget'] ?? 0, 0, ',', '.') ?></div>
                </div>
            </div>
            <div style="width: 33%; float: left; margin-left: 0.5%;">
                <div class="summary-box">
                    <div class="summary-title">Total Aktual</div>
                    <div class="summary-amount">Rp <?= number_format($summary['total_actual'] ?? 0, 0, ',', '.') ?></div>
                </div>
            </div>
            <div style="width: 33%; float: left; margin-left: 0.5%;">
                <div class="summary-box">
                    <div class="summary-title">Selisih Budget</div>
                    <div class="summary-amount">Rp <?= number_format(($summary['total_budget'] ?? 0) - ($summary['total_actual'] ?? 0), 0, ',', '.') ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="table-container">
        <h2 class="category-heading">Detail Budget per Kategori</h2>
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 25%;">Kategori</th>
                    <th style="width: 20%;">Budget</th>
                    <th style="width: 20%;">Aktual</th>
                    <th style="width: 15%;">Progress</th>
                    <th style="width: 15%;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($categories as $category): ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td><?= esc($category['nama_kategori']) ?></td>
                    <td class="amount">Rp <?= number_format($category['budget'], 0, ',', '.') ?></td>
                    <td class="amount">Rp <?= number_format($category['actual'], 0, ',', '.') ?></td>
                    <td class="percentage"><?= number_format($category['percentage'], 0) ?>%</td>
                    <td class="text-center <?= $category['percentage'] > 100 ? 'over-budget' : 'under-budget' ?>">
                        <?= $category['percentage'] > 100 ? 'Melebihi Budget' : 'Sesuai Budget' ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>* Laporan ini digenerate secara otomatis oleh sistem</p>
    </div>
</body>
</html>
