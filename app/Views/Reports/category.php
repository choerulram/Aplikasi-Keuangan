<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="container px-6 mx-auto grid">
    <h1 class="text-3xl font-bold text-main mb-4">Laporan per Kategori</h1>

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

            <!-- Filter Tipe Transaksi -->
            <div>
                <label for="type" class="block text-xs font-semibold text-gray-600 mb-1">Tipe Transaksi</label>
                <select name="type" id="type" class="px-3 py-2 border rounded-lg focus:outline-none focus:ring w-40">
                    <option value="all" <?= ($type ?? '') === 'all' ? 'selected' : '' ?>>Semua</option>
                    <option value="income" <?= ($type ?? '') === 'income' ? 'selected' : '' ?>>Pemasukan</option>
                    <option value="expense" <?= ($type ?? '') === 'expense' ? 'selected' : '' ?>>Pengeluaran</option>
                </select>
            </div>

            <div class="flex gap-2 items-end">
                <button type="submit" class="px-4 py-2 bg-main text-white rounded-lg font-semibold shadow hover:bg-highlight transition">
                    Terapkan
                </button>
                <?php if (!empty($_GET)): ?>
                    <a href="/reports/category" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition">Reset</a>
                <?php endif; ?>
            </div>
        </form>

        <div class="flex gap-2 mt-4 md:mt-0">
            <form method="post" action="/reports/exportCategoryPDF" target="_blank">
                <input type="hidden" name="period" value="<?= $period ?? 'this_month' ?>">
                <input type="hidden" name="type" value="<?= $type ?? 'all' ?>">
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg font-semibold shadow hover:bg-red-700 transition">
                    Ekspor PDF
                </button>
            </form>
            <form method="post" action="/reports/exportCategoryExcel" target="_blank">
                <input type="hidden" name="period" value="<?= $period ?? 'this_month' ?>">
                <input type="hidden" name="type" value="<?= $type ?? 'all' ?>">
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg font-semibold shadow hover:bg-green-700 transition">
                    Ekspor Excel
                </button>
            </form>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Pie Chart -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Distribusi per Kategori</h2>
            <div class="h-[400px]" id="categoryPieChart"></div>
        </div>
        
        <!-- Bar Chart -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Perbandingan antar Kategori</h2>
            <div class="h-[400px]" id="categoryBarChart"></div>
        </div>
    </div>

    <!-- Summary Table -->
    <div class="bg-white rounded-lg shadow border border-gray-200">
        <div class="p-4 border-b border-gray-200">
            <h3 class="text-gray-600 text-lg font-semibold">Ringkasan per Kategori</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300">
                <thead class="bg-main/90">
                    <tr>
                        <th style="width: 5%;" class="px-2 py-3 text-center text-xs font-medium text-white uppercase tracking-wider border-r border-white/10">No</th>
                        <th style="width: 25%;" class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider border-r border-white/10">Kategori</th>
                        <th style="width: 15%;" class="px-4 py-3 text-center text-xs font-medium text-white uppercase tracking-wider border-r border-white/10">Tipe</th>
                        <th style="width: 15%;" class="px-4 py-3 text-center text-xs font-medium text-white uppercase tracking-wider border-r border-white/10">Jumlah Transaksi</th>
                        <th style="width: 25%;" class="px-4 py-3 text-right text-xs font-medium text-white uppercase tracking-wider border-r border-white/10">Total</th>
                        <th style="width: 15%;" class="px-4 py-3 text-center text-xs font-medium text-white uppercase tracking-wider">Persentase</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    <?php $no = 1; $totalAmount = array_sum(array_column($categories, 'total')); ?>
                    <?php foreach ($categories as $category): ?>
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-2 py-2 whitespace-nowrap text-sm text-gray-600 text-center border-r border-gray-200"><?= $no++ ?></td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-600 border-r border-gray-200"><?= esc($category['nama_kategori']) ?></td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-600 text-center border-r border-gray-200">
                            <span class="px-2 py-1 text-xs rounded-full <?= $category['tipe'] === 'income' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                <?= $category['tipe'] === 'income' ? 'Pemasukan' : 'Pengeluaran' ?>
                            </span>
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-600 text-center border-r border-gray-200"><?= $category['jumlah_transaksi'] ?></td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-600 text-right border-r border-gray-200">Rp <?= number_format($category['total'], 0, ',', '.') ?></td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-600 text-center"><?= number_format(($category['total'] / $totalAmount) * 100, 1) ?>%</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="bg-main/90">
                    <tr>
                        <td colspan="4" class="px-4 py-2 whitespace-nowrap text-sm font-medium text-white text-right border-r border-white/10">Total</td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-white text-right border-r border-white/10">Rp <?= number_format($totalAmount, 0, ',', '.') ?></td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-white text-center">100%</td>
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
    // Data untuk grafik
    const categories = <?= json_encode(array_column($categories, 'nama_kategori')) ?>;
    const values = <?= json_encode(array_column($categories, 'total')) ?>;
    const colors = ['#4F46E5', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899'];

    // Pie Chart
    const pieOptions = {
        series: values,
        chart: {
            type: 'pie',
            height: 400,
        },
        labels: categories,
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
                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(value)
                }
            }
        }
    };

    const pieChart = new ApexCharts(document.querySelector("#categoryPieChart"), pieOptions);
    pieChart.render();

    // Bar Chart
    const barOptions = {
        series: [{
            name: 'Total',
            data: values
        }],
        chart: {
            type: 'bar',
            height: 400
        },
        plotOptions: {
            bar: {
                borderRadius: 4,
                horizontal: true,
            }
        },
        colors: ['#4F46E5'],
        xaxis: {
            categories: categories,
            labels: {
                formatter: function(value) {
                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(value)
                }
            }
        },
        dataLabels: {
            enabled: false
        },
        tooltip: {
            y: {
                formatter: function(value) {
                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(value)
                }
            }
        }
    };

    const barChart = new ApexCharts(document.querySelector("#categoryBarChart"), barOptions);
    barChart.render();
});
</script>
<?= $this->endSection() ?>
