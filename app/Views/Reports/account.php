<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="container px-6 mx-auto grid">
    <h1 class="text-3xl font-bold text-main mb-4">Laporan Saldo per Akun</h1>

    <!-- Filter & Export Row -->
    <div class="flex flex-wrap items-end gap-2 mb-6">
        <form id="filterForm" method="get" action="" class="flex flex-wrap gap-2 items-end flex-1">
            <!-- Filter Periode -->
            <div>
                <label for="period" class="block text-xs font-semibold text-gray-600 mb-1">Periode</label>
                <select name="period" id="period" class="px-3 py-2 border rounded-lg focus:outline-none focus:ring w-40">
                    <option value="this_month" <?= ($period ?? '') === 'this_month' ? 'selected' : '' ?>>Bulan Ini</option>
                    <option value="last_month" <?= ($period ?? '') === 'last_month' ? 'selected' : '' ?>>Bulan Lalu</option>
                    <option value="last_3_months" <?= ($period ?? '') === 'last_3_months' ? 'selected' : '' ?>>3 Bulan Terakhir</option>
                    <option value="this_year" <?= ($period ?? '') === 'this_year' ? 'selected' : '' ?>>Tahun Ini</option>
                    <option value="custom" <?= ($period ?? '') === 'custom' ? 'selected' : '' ?>>Kustom</option>
                </select>
            </div>

            <div class="flex gap-2 items-end">
                <button type="submit" class="px-4 py-2 bg-main text-white rounded-lg font-semibold shadow hover:bg-highlight transition">
                    Terapkan
                </button>
                <?php if (!empty($_GET)): ?>
                    <a href="/reports/account" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition">Reset</a>
                <?php endif; ?>
            </div>
        </form>

        <div class="flex gap-2 mt-4 md:mt-0">
            <form method="post" action="/reports/exportAccountPDF" target="_blank">
                <input type="hidden" name="period" value="<?= $period ?? 'this_month' ?>">
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg font-semibold shadow hover:bg-red-700 transition">
                    Ekspor PDF
                </button>
            </form>
            <form method="post" action="/reports/exportAccountExcel" target="_blank">
                <input type="hidden" name="period" value="<?= $period ?? 'this_month' ?>">
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg font-semibold shadow hover:bg-green-700 transition">
                    Ekspor Excel
                </button>
            </form>
        </div>
    </div>

    <!-- Account Balance Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <?php foreach ($accounts as $account): ?>
        <div class="bg-white p-4 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-700"><?= esc($account['nama_akun']) ?></h3>
            <p class="text-2xl font-bold text-main">Rp <?= number_format($account['saldo_akhir'], 0, ',', '.') ?></p>
            <p class="text-sm text-<?= $account['mutasi'] >= 0 ? 'green' : 'red' ?>-600">
                <?= $account['mutasi'] >= 0 ? '+' : '' ?>Rp <?= number_format($account['mutasi'], 0, ',', '.') ?> (30d)
            </p>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Donut Chart -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Distribusi Saldo per Akun</h2>
        <div class="h-[400px]" id="accountDonutChart"></div>
    </div>

    <!-- Account Movement Table -->
    <div class="bg-white rounded-lg shadow border border-gray-200">
        <div class="p-4 border-b border-gray-200">
            <h3 class="text-gray-600 text-lg font-semibold">Ringkasan Mutasi per Akun</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-main/90">
                    <tr>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-white uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Akun</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-white uppercase tracking-wider">Saldo Awal</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-white uppercase tracking-wider">Total Masuk</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-white uppercase tracking-wider">Total Keluar</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-white uppercase tracking-wider">Mutasi</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-white uppercase tracking-wider">Saldo Akhir</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $no = 1; foreach ($accounts as $account): ?>
                    <tr>
                        <td class="px-6 py-4 text-center"><?= $no++ ?></td>
                        <td class="px-6 py-4 whitespace-nowrap"><?= esc($account['nama_akun']) ?></td>
                        <td class="px-6 py-4 text-right">Rp <?= number_format($account['saldo_awal'], 0, ',', '.') ?></td>
                        <td class="px-6 py-4 text-right text-green-600">Rp <?= number_format($account['total_income'], 0, ',', '.') ?></td>
                        <td class="px-6 py-4 text-right text-red-600">Rp <?= number_format($account['total_expense'], 0, ',', '.') ?></td>
                        <td class="px-6 py-4 text-right <?= $account['mutasi'] >= 0 ? 'text-green-600' : 'text-red-600' ?>">
                            <?= $account['mutasi'] >= 0 ? '+' : '' ?>Rp <?= number_format($account['mutasi'], 0, ',', '.') ?>
                        </td>
                        <td class="px-6 py-4 text-right font-semibold">Rp <?= number_format($account['saldo_akhir'], 0, ',', '.') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td class="px-6 py-4 text-center font-semibold">#</td>
                        <td class="px-6 py-4 font-semibold">Total</td>
                        <td class="px-6 py-4 text-right font-semibold">Rp <?= number_format(array_sum(array_column($accounts, 'saldo_awal')), 0, ',', '.') ?></td>
                        <td class="px-6 py-4 text-right font-semibold text-green-600">Rp <?= number_format(array_sum(array_column($accounts, 'total_income')), 0, ',', '.') ?></td>
                        <td class="px-6 py-4 text-right font-semibold text-red-600">Rp <?= number_format(array_sum(array_column($accounts, 'total_expense')), 0, ',', '.') ?></td>
                        <td class="px-6 py-4 text-right font-semibold">Rp <?= number_format(array_sum(array_column($accounts, 'mutasi')), 0, ',', '.') ?></td>
                        <td class="px-6 py-4 text-right font-semibold">Rp <?= number_format($totalBalance, 0, ',', '.') ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<!-- Add ApexCharts CDN -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Data untuk grafik donut
    const accounts = <?= json_encode(array_column($accounts, 'nama_akun')) ?>;
    const balances = <?= json_encode(array_column($accounts, 'saldo_akhir')) ?>;
    const colors = ['#4F46E5', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899'];

    // Donut Chart
    const donutOptions = {
        series: balances,
        chart: {
            type: 'donut',
            height: 400
        },
        labels: accounts,
        colors: colors,
        legend: {
            position: 'bottom'
        },
        dataLabels: {
            enabled: true,
            formatter: function (val) {
                return Math.round(val) + "%"
            },
        },
        tooltip: {
            y: {
                formatter: function(value) {
                    return "Rp " + value.toLocaleString('id-ID');
                }
            }
        }
    };

    const donutChart = new ApexCharts(document.querySelector("#accountDonutChart"), donutOptions);
    donutChart.render();
});
</script>
<?= $this->endSection() ?>
