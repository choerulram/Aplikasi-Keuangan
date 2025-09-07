<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Laporan Tren Bulanan</h1>
        
        <!-- Filter Tahun -->
        <div class="flex items-center gap-4">
            <form method="get" class="flex items-center gap-2">
                <label for="year" class="font-medium">Tahun:</label>
                <select name="year" id="year" class="form-select rounded-md border-gray-300" onchange="this.form.submit()">
                    <?php for($y = date('Y'); $y >= date('Y')-5; $y--): ?>
                        <option value="<?= $y ?>" <?= $selectedYear == $y ? 'selected' : '' ?>>
                            <?= $y ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </form>
        </div>
    </div>

    <!-- Line Chart Tren YTD -->
    <div class="bg-white p-4 rounded-lg shadow mb-6">
        <h2 class="text-lg font-semibold mb-4">Tren Year-to-Date <?= $selectedYear ?></h2>
        <canvas id="monthlyTrendChart"></canvas>
    </div>

    <!-- Tabel Perbandingan Bulanan -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-4 py-2 text-left">Bulan</th>
                    <th class="px-4 py-2 text-right">Pemasukan</th>
                    <th class="px-4 py-2 text-right">Pengeluaran</th>
                    <th class="px-4 py-2 text-right">Selisih</th>
                    <th class="px-4 py-2 text-right">YoY Growth</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($currentYearData as $data): ?>
                <tr class="border-t">
                    <td class="px-4 py-2"><?= $data['bulan'] ?></td>
                    <td class="px-4 py-2 text-right text-green-600">
                        Rp <?= number_format($data['pemasukan'], 0, ',', '.') ?>
                    </td>
                    <td class="px-4 py-2 text-right text-red-600">
                        Rp <?= number_format($data['pengeluaran'], 0, ',', '.') ?>
                    </td>
                    <td class="px-4 py-2 text-right <?= $data['selisih'] >= 0 ? 'text-green-600' : 'text-red-600' ?>">
                        Rp <?= number_format($data['selisih'], 0, ',', '.') ?>
                    </td>
                    <td class="px-4 py-2 text-right <?= $data['yoy_growth'] >= 0 ? 'text-green-600' : 'text-red-600' ?>">
                        <?= number_format($data['yoy_growth'], 1) ?>%
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Script untuk Chart -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Data untuk chart
    const monthlyData = <?= json_encode($monthlyData) ?>;
    const months = monthlyData.map(data => data.month);
    const incomeData = monthlyData.map(data => data.income);
    const expenseData = monthlyData.map(data => data.expense);

    // Membuat line chart
    const ctx = document.getElementById('monthlyTrendChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: months,
            datasets: [
                {
                    label: 'Pemasukan',
                    data: incomeData,
                    borderColor: 'rgb(34, 197, 94)',
                    tension: 0.1,
                    fill: false
                },
                {
                    label: 'Pengeluaran',
                    data: expenseData,
                    borderColor: 'rgb(239, 68, 68)',
                    tension: 0.1,
                    fill: false
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Tren Bulanan Pemasukan dan Pengeluaran'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                        }
                    }
                }
            }
        }
    });
});
</script>
<?= $this->endSection() ?>
