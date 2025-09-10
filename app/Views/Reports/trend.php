<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="container px-6 mx-auto grid">
    <h1 class="text-3xl font-bold text-main mb-4">Laporan Tren Bulanan</h1>

    <!-- Filter & Export Row -->
    <div class="flex flex-wrap items-end gap-2 mb-6">
        <form method="get" class="flex flex-wrap gap-2 items-end flex-1">
            <div>
                <label for="year" class="block text-xs font-semibold text-gray-600 mb-1">Tahun</label>
                <select name="year" id="year" class="px-3 py-2 border rounded-lg focus:outline-none focus:ring w-40" onchange="this.form.submit()">
                    <?php for($y = date('Y'); $y >= date('Y')-5; $y--): ?>
                        <option value="<?= $y ?>" <?= $selectedYear == $y ? 'selected' : '' ?>>
                            <?= $y ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="flex gap-2 items-end">
                <button type="submit" class="px-4 py-2 bg-main text-white rounded-lg font-semibold shadow hover:bg-highlight transition">
                    Terapkan
                </button>
                <?php if (!empty($_GET)): ?>
                    <a href="/reports/trend" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition">Reset</a>
                <?php endif; ?>
            </div>
        </form>

        <div class="flex gap-2 mt-4 md:mt-0">
            <form method="post" action="/reports/exportTrendPDF" target="_blank">
                <input type="hidden" name="year" value="<?= $selectedYear ?>">
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg font-semibold shadow hover:bg-red-700 transition">
                    Ekspor PDF
                </button>
            </form>
            <form method="post" action="/reports/exportTrendExcel" target="_blank">
                <input type="hidden" name="year" value="<?= $selectedYear ?>">
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg font-semibold shadow hover:bg-green-700 transition">
                    Ekspor Excel
                </button>
            </form>
        </div>
    </div>

    <!-- Line Chart Tren YTD -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Tren Year-to-Date <?= $selectedYear ?></h2>
        <canvas id="monthlyTrendChart"></canvas>
    </div>

    <!-- Tabel Perbandingan Bulanan -->
    <div class="bg-white rounded-lg shadow border border-gray-200">
        <div class="p-4 border-b border-gray-200">
            <h3 class="text-gray-600 text-lg font-semibold">Perbandingan Bulanan</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300">
                <thead class="bg-main/90">
                    <tr>
                        <th class="py-3 px-2 w-8 text-center text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">No</th>
                        <th class="py-3 px-4 w-36 text-left text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Bulan</th>
                        <th class="py-3 px-4 w-40 text-right text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Pemasukan</th>
                        <th class="py-3 px-4 w-40 text-right text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Pengeluaran</th>
                        <th class="py-3 px-4 w-40 text-right text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Selisih</th>
                        <th class="py-3 px-4 w-32 text-right text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">YoY Growth</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    <?php $no = 1; foreach($currentYearData as $data): ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-2 px-1 text-sm text-gray-600 border-b border-r border-gray-200 text-center"><?= $no++ ?></td>
                        <td class="py-2 px-4 text-sm text-gray-700 border-b border-r border-gray-200"><?= $data['bulan'] ?></td>
                        <td class="py-2 px-4 text-sm text-green-600 border-b border-r border-gray-200 text-right">
                            Rp <?= number_format($data['pemasukan'], 0, ',', '.') ?>
                        </td>
                        <td class="py-2 px-4 text-sm text-red-600 border-b border-r border-gray-200 text-right">
                            Rp <?= number_format($data['pengeluaran'], 0, ',', '.') ?>
                        </td>
                        <td class="py-2 px-4 text-sm border-b border-r border-gray-200 text-right <?= $data['selisih'] >= 0 ? 'text-green-600' : 'text-red-600' ?>">
                            Rp <?= number_format($data['selisih'], 0, ',', '.') ?>
                        </td>
                        <td class="py-2 px-4 text-sm border-b border-r border-gray-200 text-right <?= $data['yoy_growth'] >= 0 ? 'text-green-600' : 'text-red-600' ?>">
                            <?= number_format($data['yoy_growth'], 1) ?>%
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <th colspan="2" class="py-3 px-4 text-right text-xs font-bold uppercase border-t border-r border-gray-300">Total</th>
                        <th class="py-3 px-4 text-right text-xs font-bold uppercase border-t border-r border-gray-300">
                            <?php
                            $totalIncome = array_sum(array_column($currentYearData, 'pemasukan'));
                            echo 'Rp ' . number_format($totalIncome, 0, ',', '.');
                            ?>
                        </th>
                        <th class="py-3 px-4 text-right text-xs font-bold uppercase border-t border-r border-gray-300">
                            <?php
                            $totalExpense = array_sum(array_column($currentYearData, 'pengeluaran'));
                            echo 'Rp ' . number_format($totalExpense, 0, ',', '.');
                            ?>
                        </th>
                        <th class="py-3 px-4 text-right text-xs font-bold uppercase border-t border-r border-gray-300">
                            <?php
                            $totalSelisih = $totalIncome - $totalExpense;
                            $textColorClass = $totalSelisih >= 0 ? 'text-green-600' : 'text-red-600';
                            echo "<span class='$textColorClass'>Rp " . number_format(abs($totalSelisih), 0, ',', '.') . "</span>";
                            ?>
                        </th>
                        <th class="py-3 px-4 text-right text-xs font-bold uppercase border-t border-r border-gray-300">-</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<!-- Script untuk Chart -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Data untuk chart
    const chartData = <?= json_encode($chartData) ?>;
    const months = chartData.map(data => data.bulan);
    const incomeData = chartData.map(data => data.pemasukan);
    const expenseData = chartData.map(data => data.pengeluaran);

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
