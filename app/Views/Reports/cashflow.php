<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="container px-6 mx-auto grid">
    <h1 class="text-3xl font-bold text-main mb-4">Laporan Arus Kas</h1>

    <!-- Filter & Export Row -->
    <div class="flex flex-wrap items-end gap-2 mb-6">
        <form id="filterForm" method="get" action="" class="flex flex-wrap gap-2 items-end flex-1">
            <div>
                <label for="view_type" class="block text-xs font-semibold text-gray-600 mb-1">Tampilan</label>
                <select id="view_type" name="view_type" class="px-3 py-2 border rounded-lg focus:outline-none focus:ring w-40">
                    <option value="monthly" <?= ($view_type ?? 'monthly') === 'monthly' ? 'selected' : '' ?>>Per Bulan</option>
                    <option value="yearly" <?= ($view_type ?? '') === 'yearly' ? 'selected' : '' ?>>Per Tahun</option>
                </select>
            </div>
            
            <!-- Filter untuk tampilan bulanan -->
            <div id="monthlyFilter" class="<?= ($view_type ?? 'monthly') === 'monthly' ? 'flex' : 'hidden' ?> gap-2">
                <div>
                    <label for="month" class="block text-xs font-semibold text-gray-600 mb-1">Bulan</label>
                    <select id="month" name="month" class="px-3 py-2 border rounded-lg focus:outline-none focus:ring w-40">
                        <?php 
                        $months = [
                            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
                            '04' => 'April', '05' => 'Mei', '06' => 'Juni',
                            '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
                            '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                        ];
                        foreach ($months as $value => $label): 
                            $selected = ($month ?? date('m')) === $value ? 'selected' : '';
                        ?>
                            <option value="<?= $value ?>" <?= $selected ?>><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="year" class="block text-xs font-semibold text-gray-600 mb-1">Tahun</label>
                    <select id="year" name="year" class="px-3 py-2 border rounded-lg focus:outline-none focus:ring w-32">
                        <?php 
                        $currentYear = date('Y');
                        for($y = $currentYear; $y >= $currentYear - 5; $y--): 
                            $selected = ($year ?? $currentYear) == $y ? 'selected' : '';
                        ?>
                            <option value="<?= $y ?>" <?= $selected ?>><?= $y ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>

            <!-- Filter untuk tampilan tahunan -->
            <div id="yearlyFilter" class="<?= ($view_type ?? '') === 'yearly' ? 'flex' : 'hidden' ?> gap-2">
                <div>
                    <label for="year_filter" class="block text-xs font-semibold text-gray-600 mb-1">Tahun</label>
                    <select id="year_filter" name="year_filter" class="px-3 py-2 border rounded-lg focus:outline-none focus:ring w-40">
                        <?php 
                        $currentYear = date('Y');
                        for($y = $currentYear; $y >= $currentYear - 5; $y--): 
                            $selected = ($year_filter ?? $currentYear) == $y ? 'selected' : '';
                        ?>
                            <option value="<?= $y ?>" <?= $selected ?>><?= $y ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>

            <div class="flex gap-2 items-end">
                <button type="submit" class="px-4 py-2 bg-main text-white rounded-lg font-semibold shadow hover:bg-highlight transition">Terapkan</button>
                <?php if (!empty($_GET)): ?>
                    <a href="/reports/cashflow" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition">Reset</a>
                <?php endif; ?>
            </div>

            <div id="dateRange" class="<?= ($period ?? '') === 'custom' ? 'flex' : 'hidden' ?> gap-4">
                <div>
                    <label for="start_date" class="block text-xs font-semibold text-gray-600 mb-1">Dari Tanggal</label>
                    <input type="date" name="start_date" id="start_date" class="px-3 py-2 border rounded-lg focus:outline-none focus:ring w-40" value="<?= $filters['start_date'] ?? '' ?>">
                </div>
                <div>
                    <label for="end_date" class="block text-xs font-semibold text-gray-600 mb-1">Sampai Tanggal</label>
                    <input type="date" name="end_date" id="end_date" class="px-3 py-2 border rounded-lg focus:outline-none focus:ring w-40" value="<?= $filters['end_date'] ?? '' ?>">
                </div>
            </div>
        </form>

        <div class="flex gap-2 mt-4 md:mt-0">
            <form method="post" action="/reports/exportPDF" target="_blank">
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg font-semibold shadow hover:bg-red-700 transition">Ekspor PDF</button>
            </form>
            <form method="post" action="/reports/exportExcel" target="_blank">
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg font-semibold shadow hover:bg-green-700 transition">Ekspor Excel</button>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="flex items-center p-6 bg-green-50 border-2 border-green-300 rounded-2xl shadow-lg min-h-[120px] h-[140px]">
            <div class="flex items-center justify-center w-16 h-16 rounded-full bg-green-200 mr-5">
                <svg class="w-10 h-10 text-green-700" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 17l6-6 4 4 8-8" />
                </svg>
            </div>
            <div>
                <div class="text-base font-bold text-green-800 mb-1 tracking-wide">Total Pemasukan</div>
                <div class="text-3xl font-extrabold text-green-700">Rp <?= number_format($summary['total_income'] ?? 0, 0, ',', '.') ?></div>
            </div>
        </div>

        <div class="flex items-center p-6 bg-red-50 border-2 border-red-300 rounded-2xl shadow-lg min-h-[120px] h-[140px]">
            <div class="flex items-center justify-center w-16 h-16 rounded-full bg-red-200 mr-5">
                <svg class="w-10 h-10 text-red-700" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7l6 6 4-4 8 8" />
                </svg>
            </div>
            <div>
                <div class="text-base font-bold text-red-800 mb-1 tracking-wide">Total Pengeluaran</div>
                <div class="text-3xl font-extrabold text-red-700">Rp <?= number_format($summary['total_expense'] ?? 0, 0, ',', '.') ?></div>
            </div>
        </div>

        <div class="flex items-center p-6 bg-blue-50 border-2 border-blue-300 rounded-2xl shadow-lg min-h-[120px] h-[140px]">
            <div class="flex items-center justify-center w-16 h-16 rounded-full bg-blue-200 mr-5">
                <svg class="w-10 h-10 text-blue-700" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M2 7a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V7z" stroke="currentColor" stroke-width="2.5" fill="#e0e7ff"/>
                    <path d="M22 9H18a2 2 0 0 0-2 2v2a2 2 0 0 0 2 2h4" stroke="currentColor" stroke-width="2.5"/>
                    <circle cx="18.5" cy="12" r="1" fill="currentColor"/>
                </svg>
            </div>
            <div>
                <div class="text-base font-bold text-blue-800 mb-1 tracking-wide">Arus Kas Bersih</div>
                <div class="text-3xl font-extrabold text-blue-700">Rp <?= number_format(($summary['total_income'] ?? 0) - ($summary['total_expense'] ?? 0), 0, ',', '.') ?></div>
            </div>
        </div>
    </div>

    <!-- Chart -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-8">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Tren Arus Kas</h2>
        <div class="h-[400px]" id="cashFlowChart">
            <!-- Chart will be rendered here -->
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow border border-gray-200">
        <div class="p-4 border-b border-gray-200">
            <h3 class="text-gray-600 text-lg font-semibold">Detail Transaksi</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300">
                <thead class="bg-main/90">
                    <tr>
                        <th class="py-3 px-2 w-12 text-center text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">No</th>
                        <th class="py-3 px-4 w-32 text-left text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Tanggal</th>
                        <th class="py-3 px-4 text-left text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Deskripsi</th>
                        <th class="py-3 px-4 w-36 text-left text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Kategori</th>
                        <th class="py-3 px-4 w-36 text-left text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Akun</th>
                        <th class="py-3 px-4 w-32 text-left text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Tipe</th>
                        <th class="py-3 px-4 w-40 text-right text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    <?php if (!empty($transactions)): ?>
                        <?php 
                        $currentPage = $pager->getCurrentPage('transactions');
                        $perPage = 10; // Sesuaikan dengan yang ada di controller
                        $no = ($currentPage - 1) * $perPage + 1;
                        ?>
                        <?php foreach ($transactions as $trx): ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-2 px-2 text-sm text-gray-600 border-b border-r border-gray-200 text-center"><?= $no++ ?></td>
                                <td class="py-2 px-4 text-sm text-gray-700 border-b border-r border-gray-200"><?= date('d/m/Y', strtotime($trx['tanggal'])) ?></td>
                                <td class="py-2 px-4 text-sm text-gray-700 border-b border-r border-gray-200"><?= esc($trx['deskripsi']) ?></td>
                                <td class="py-2 px-4 text-sm text-gray-700 border-b border-r border-gray-200"><?= esc($trx['category_name']) ?></td>
                                <td class="py-2 px-4 text-sm text-gray-700 border-b border-r border-gray-200"><?= esc($trx['account_name']) ?></td>
                                <td class="py-2 px-4 text-sm border-b border-r border-gray-200">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full <?= $trx['tipe'] === 'income' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                        <?= $trx['tipe'] === 'income' ? 'Pemasukan' : 'Pengeluaran' ?>
                                    </span>
                                </td>
                                <td class="py-2 px-4 text-sm font-medium border-b border-r border-gray-200 text-right <?= $trx['tipe'] === 'income' ? 'text-green-600' : 'text-red-600' ?>">
                                    Rp <?= number_format($trx['jumlah'], 0, ',', '.') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="px-4 py-3 text-sm text-center text-gray-500">Tidak ada transaksi untuk ditampilkan</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <?php if (isset($pager) && $pager->getPageCount('transactions') > 1): ?>
    <div class="mt-4 flex justify-center">
        <nav class="inline-flex rounded-md shadow-sm" aria-label="Pagination">
            <?= view('Reports/pagination', [
                'pager' => $pager,
                'group' => 'transactions',
                'currentPage' => $pager->getCurrentPage('transactions'),
                'pageCount' => $pager->getPageCount('transactions')
            ]) ?>
        </nav>
    </div>
    <?php endif; ?>
</div>

<!-- Add ApexCharts CDN -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Data untuk grafik
    const chartData = {
        income: <?= json_encode(array_map(function($val) { return floatval($val); }, array_column($chartData, 'income'))) ?>,
        expense: <?= json_encode(array_map(function($val) { return floatval($val); }, array_column($chartData, 'expense'))) ?>,
        categories: <?= json_encode(array_column($chartData, 'month')) ?>
    };

    // Log data untuk debugging
    console.log('Chart Data:', chartData);

    var options = {
        series: [{
            name: 'Pemasukan',
            data: chartData.income,
            color: '#10B981'
        }, {
            name: 'Pengeluaran',
            data: chartData.expense,
            color: '#EF4444'
        }],
        chart: {
            type: 'area',
            height: 400,
            toolbar: {
                show: false
            },
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800
            }
        },
        colors: ['#10B981', '#EF4444'],
        stroke: {
            curve: 'smooth',
            width: 2,
            colors: ['#059669', '#DC2626']
        },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.4,
                opacityTo: 0.1,
                stops: [0, 90, 100]
            }
        },
        dataLabels: {
            enabled: true,
            formatter: function(value, { seriesIndex }) {
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
            },
            style: {
                fontSize: '11px',
                fontWeight: 600,
                colors: ['#059669', '#DC2626']
            },
            background: {
                enabled: true,
                borderRadius: 4,
                borderWidth: 1,
                borderColor: '#E5E7EB',
                opacity: 0.9,
                dropShadow: {
                    enabled: true,
                    top: 1,
                    left: 1,
                    blur: 1,
                    opacity: 0.1
                }
            },
            offsetY: -10
        },
        xaxis: {
            type: 'category',
            categories: chartData.categories,
            labels: {
                style: {
                    colors: '#6B7280',
                    fontSize: '12px'
                },
                formatter: function(value) {
                    if ('<?= $view_type ?>' === 'monthly') {
                        // Untuk tampilan bulanan, tampilkan tanggal dengan format 01-31
                        return String(value).padStart(2, '0');
                    }
                    // Untuk tampilan tahunan, tampilkan nama bulan (sudah diformat dari model)
                    return value;
                },
                rotate: -45,
                trim: true
            },
            tickPlacement: 'on',
            tickAmount: <?= ($view_type === 'monthly') ? 'undefined' : '12' ?>,
            title: {
                text: '<?= ($view_type === 'monthly') ? 'Tanggal' : 'Bulan' ?>',
                style: {
                    fontSize: '14px',
                    fontWeight: 600,
                    color: '#374151'
                }
            }
        },
        yaxis: {
            labels: {
                formatter: function(value) {
                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                },
                style: {
                    colors: '#6B7280',
                    fontSize: '12px'
                }
            },
            tickAmount: 5,
            min: 0
        },
        legend: {
            position: 'top',
            horizontalAlign: 'left',
            offsetX: 40,
            labels: {
                colors: '#6B7280'
            }
        },
        grid: {
            borderColor: '#E5E7EB',
            strokeDashArray: 4,
            xaxis: {
                lines: {
                    show: true
                }
            }
        }
    };

    var chart = new ApexCharts(document.querySelector("#cashFlowChart"), options);
    chart.render();

    // Handle view type selection
    document.getElementById('view_type').addEventListener('change', function(e) {
        const monthlyFilter = document.getElementById('monthlyFilter');
        const yearlyFilter = document.getElementById('yearlyFilter');
        
        if (e.target.value === 'monthly') {
            monthlyFilter.classList.remove('hidden');
            yearlyFilter.classList.add('hidden');
        } else {
            monthlyFilter.classList.add('hidden');
            yearlyFilter.classList.remove('hidden');
        }
    });
});
</script>
<?= $this->endSection() ?>
