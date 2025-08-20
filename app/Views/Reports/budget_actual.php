<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="container px-6 mx-auto grid">
    <h1 class="text-3xl font-bold text-main mb-4">Laporan Budget vs Aktual</h1>

    <!-- Filter & Export Row -->
    <div class="flex flex-wrap items-end gap-2 mb-6">
        <form id="filterForm" method="get" action="" class="flex flex-wrap gap-2 items-end flex-1">
            <!-- Filter Periode -->
            <div class="flex-1 min-w-[200px]">
                <label for="period" class="block text-sm font-medium text-gray-700 mb-1">Periode</label>
                <select name="period" id="period" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-main focus:ring focus:ring-main focus:ring-opacity-50">
                    <option value="<?= date('Y-m') ?>" <?= ($period ?? '') == date('Y-m') ? 'selected' : '' ?>>
                        <?= date('F Y') ?>
                    </option>
                    <option value="<?= date('Y-m', strtotime('-1 month')) ?>" <?= ($period ?? '') == date('Y-m', strtotime('-1 month')) ? 'selected' : '' ?>>
                        <?= date('F Y', strtotime('-1 month')) ?>
                    </option>
                    <option value="<?= date('Y-m', strtotime('-2 month')) ?>" <?= ($period ?? '') == date('Y-m', strtotime('-2 month')) ? 'selected' : '' ?>>
                        <?= date('F Y', strtotime('-2 month')) ?>
                    </option>
                </select>
            </div>

            <!-- Tombol Filter -->
            <div>
                <button type="submit" class="bg-main text-white px-4 py-2 rounded-md hover:bg-main-dark focus:outline-none focus:ring-2 focus:ring-main focus:ring-opacity-50">
                    Filter
                </button>
            </div>
        </form>

        <!-- Export Buttons -->
        <div class="flex gap-2">
            <form method="post" action="/reports/exportBudgetPDF" target="_blank">
                <input type="hidden" name="period" value="<?= $period ?? date('Y-m') ?>">
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50">
                    Export PDF
                </button>
            </form>
            <form method="post" action="/reports/exportBudgetExcel" target="_blank">
                <input type="hidden" name="period" value="<?= $period ?? date('Y-m') ?>">
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50">
                    Export Excel
                </button>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Budget Card -->
        <div class="bg-white p-6 rounded-xl shadow-md border-t-4 border-blue-500">
            <h3 class="text-lg font-semibold text-gray-600 mb-2">Total Budget</h3>
            <p class="text-2xl font-bold text-blue-600">
                Rp <?= number_format($summary['total_budget'] ?? 0, 0, ',', '.') ?>
            </p>
        </div>

        <!-- Total Aktual Card -->
        <div class="bg-white p-6 rounded-xl shadow-md border-t-4 border-green-500">
            <h3 class="text-lg font-semibold text-gray-600 mb-2">Total Aktual</h3>
            <p class="text-2xl font-bold text-green-600">
                Rp <?= number_format($summary['total_actual'] ?? 0, 0, ',', '.') ?>
            </p>
        </div>

        <!-- Selisih Card -->
        <div class="bg-white p-6 rounded-xl shadow-md border-t-4 border-purple-500">
            <h3 class="text-lg font-semibold text-gray-600 mb-2">Selisih</h3>
            <p class="text-2xl font-bold text-purple-600">
                Rp <?= number_format(($summary['total_budget'] ?? 0) - ($summary['total_actual'] ?? 0), 0, ',', '.') ?>
            </p>
        </div>
    </div>

    <!-- Budget Progress by Category -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Progress Budget per Kategori</h2>
        
        <?php foreach ($categories as $category): ?>
        <div class="mb-6 last:mb-0">
            <div class="flex justify-between items-center mb-2">
                <h3 class="text-lg font-medium text-gray-700"><?= esc($category['nama_kategori']) ?></h3>
                <span class="text-sm font-semibold <?= $category['percentage'] > 100 ? 'text-red-600' : 'text-blue-600' ?>">
                    <?= number_format($category['percentage'], 0) ?>%
                </span>
            </div>
            <div class="flex justify-between text-sm text-gray-600 mb-1">
                <span>Aktual: Rp <?= number_format($category['actual'], 0, ',', '.') ?></span>
                <span>Budget: Rp <?= number_format($category['budget'], 0, ',', '.') ?></span>
            </div>
            <div class="relative pt-1">
                <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-blue-200">
                    <div style="width: <?= min($category['percentage'], 100) ?>%"
                         class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center <?= $category['percentage'] > 100 ? 'bg-red-500' : 'bg-blue-500' ?>">
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Bar Chart Comparison -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Perbandingan Budget vs Aktual</h2>
        <div class="h-[400px]" id="budgetChart">
            <!-- Chart will be rendered here -->
        </div>
    </div>
</div>

<!-- Add ApexCharts CDN -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chartData = {
        categories: <?= json_encode(array_column($categories, 'nama_kategori')) ?>,
        budget: <?= json_encode(array_column($categories, 'budget')) ?>,
        actual: <?= json_encode(array_column($categories, 'actual')) ?>
    };

    const options = {
        series: [{
            name: 'Budget',
            data: chartData.budget
        }, {
            name: 'Aktual',
            data: chartData.actual
        }],
        chart: {
            type: 'bar',
            height: 400,
            toolbar: {
                show: false
            }
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '55%',
                endingShape: 'rounded'
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        xaxis: {
            categories: chartData.categories,
            labels: {
                rotate: -45,
                trim: true
            }
        },
        yaxis: {
            title: {
                text: 'Rupiah'
            },
            labels: {
                formatter: function(value) {
                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                }
            }
        },
        fill: {
            opacity: 1
        },
        colors: ['#3B82F6', '#10B981'],
        legend: {
            position: 'top'
        },
        tooltip: {
            y: {
                formatter: function(value) {
                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                }
            }
        }
    };

    const chart = new ApexCharts(document.querySelector("#budgetChart"), options);
    chart.render();
});
</script>
<?= $this->endSection() ?>
