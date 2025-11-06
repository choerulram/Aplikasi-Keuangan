<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="container px-2 md:px-6 mx-auto grid">
    <h1 class="text-2xl md:text-3xl font-bold text-main mb-4 leading-tight">Laporan Budget vs Aktual</h1>

    <!-- Filter & Export Row -->
    <div class="flex flex-col md:flex-row md:flex-wrap md:items-end gap-2 mb-6">
        <form id="filterForm" method="get" action="" class="flex flex-col gap-2 md:flex-row md:flex-wrap md:gap-2 md:items-end flex-1 w-full">
            <!-- Filter Periode -->
            <div class="w-full md:w-auto">
                <label for="period" class="block text-xs font-semibold text-gray-600 mb-1">Periode</label>
                <select name="period" id="period" class="px-3 py-2 border rounded-lg focus:outline-none focus:ring w-full md:w-40 text-xs md:text-sm">
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
            <div class="flex gap-2 items-end w-full md:w-auto mt-2 md:mt-0">
                <button type="submit" class="inline-flex items-center justify-center gap-2 px-3 py-2 md:px-4 md:py-2 bg-main text-white rounded-lg font-semibold shadow hover:bg-highlight transition h-9 md:h-11 text-sm md:text-sm">Terapkan</button>
                <?php if (!empty($_GET)): ?>
                    <a href="/reports/budget" class="px-3 py-1.5 md:px-4 md:py-2 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition text-xs md:text-sm">Reset</a>
                <?php endif; ?>
            </div>
        </form>
        <div class="flex gap-2 mt-4 md:mt-0">
            <form method="post" action="/reports/exportBudgetPDF" target="_blank">
                <input type="hidden" name="period" value="<?= $period ?? date('Y-m') ?>">
                <button type="submit" class="px-3 py-2 md:px-4 md:py-2 bg-red-600 text-white rounded-lg font-semibold shadow hover:bg-red-700 transition h-9 md:h-11 text-sm md:text-sm">Ekspor PDF</button>
            </form>
            <form method="post" action="/reports/exportBudgetExcel" target="_blank">
                <input type="hidden" name="period" value="<?= $period ?? date('Y-m') ?>">
                <button type="submit" class="px-3 py-2 md:px-4 md:py-2 bg-green-600 text-white rounded-lg font-semibold shadow hover:bg-green-700 transition h-9 md:h-11 text-sm md:text-sm">Ekspor Excel</button>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Budget Card -->
        <div class="flex items-center p-4 md:p-6 bg-blue-50 border-2 border-blue-300 rounded-2xl shadow-lg min-h-[100px] md:min-h-[120px] h-[120px] md:h-[140px]">
            <div class="flex items-center justify-center w-12 h-12 md:w-16 md:h-16 rounded-full bg-blue-200 mr-3 md:mr-5">
                <svg class="w-8 h-8 md:w-10 md:h-10 text-blue-700" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-8-6h16"/>
                </svg>
            </div>
            <div>
                <div class="text-xs md:text-base font-bold text-blue-800 mb-1 tracking-wide">Total Budget</div>
                <div class="text-xl md:text-3xl font-extrabold text-blue-700">Rp <?= number_format($summary['total_budget'] ?? 0, 0, ',', '.') ?></div>
            </div>
        </div>
        <!-- Total Aktual Card -->
        <div class="flex items-center p-4 md:p-6 bg-green-50 border-2 border-green-300 rounded-2xl shadow-lg min-h-[100px] md:min-h-[120px] h-[120px] md:h-[140px]">
            <div class="flex items-center justify-center w-12 h-12 md:w-16 md:h-16 rounded-full bg-green-200 mr-3 md:mr-5">
                <svg class="w-8 h-8 md:w-10 md:h-10 text-green-700" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                </svg>
            </div>
            <div>
                <div class="text-xs md:text-base font-bold text-green-800 mb-1 tracking-wide">Total Aktual</div>
                <div class="text-xl md:text-3xl font-extrabold text-green-700">Rp <?= number_format($summary['total_actual'] ?? 0, 0, ',', '.') ?></div>
            </div>
        </div>
        <!-- Selisih Card -->
        <div class="flex items-center p-4 md:p-6 bg-purple-50 border-2 border-purple-300 rounded-2xl shadow-lg min-h-[100px] md:min-h-[120px] h-[120px] md:h-[140px]">
            <div class="flex items-center justify-center w-12 h-12 md:w-16 md:h-16 rounded-full bg-purple-200 mr-3 md:mr-5">
                <svg class="w-8 h-8 md:w-10 md:h-10 text-purple-700" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4M12 4v16"/>
                </svg>
            </div>
            <div>
                <div class="text-xs md:text-base font-bold text-purple-800 mb-1 tracking-wide">Selisih Budget</div>
                <div class="text-xl md:text-3xl font-extrabold text-purple-700">Rp <?= number_format(($summary['total_budget'] ?? 0) - ($summary['total_actual'] ?? 0), 0, ',', '.') ?></div>
            </div>
        </div>
    </div>

    <!-- Budget Progress by Category -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-8">
        <h2 class="text-lg font-semibold text-gray-800 mb-6">Progress Budget per Kategori</h2>
        
        <?php foreach ($categories as $category): ?>
        <div class="mb-6 last:mb-0">
            <div class="flex justify-between items-center mb-2">
                <h3 class="text-base font-semibold text-gray-700"><?= esc($category['nama_kategori']) ?></h3>
                <div class="flex items-center gap-2">
                    <span class="text-sm font-semibold bg-gray-100 px-2 py-1 rounded-lg text-gray-600">
                        <?= number_format($category['percentage'], 0) ?>%
                    </span>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 text-sm text-gray-600 mb-2">
                <div class="flex justify-between">
                    <span class="font-medium">Aktual:</span>
                    <span class="<?= $category['percentage'] > 100 ? 'text-red-600' : 'text-green-600' ?> font-semibold">
                        Rp <?= number_format($category['actual'], 0, ',', '.') ?>
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium">Budget:</span>
                    <span class="text-blue-600 font-semibold">
                        Rp <?= number_format($category['budget'], 0, ',', '.') ?>
                    </span>
                </div>
            </div>
            <div class="relative pt-1">
                <div class="overflow-hidden h-3 mb-4 text-xs flex rounded-full bg-gray-200">
                    <div style="width: <?= min($category['percentage'], 100) ?>%"
                         class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center transition-all duration-500 <?= $category['percentage'] > 100 ? 'bg-red-500' : 'bg-blue-500' ?>">
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Bar Chart Comparison -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-8">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Perbandingan Budget vs Aktual</h2>
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
