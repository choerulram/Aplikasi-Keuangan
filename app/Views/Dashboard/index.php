<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<!-- Header Welcome -->
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Dashboard Keuangan</h1>
        <p class="text-gray-600">Selamat datang kembali, <?= session()->get('username') ?>!</p>
    </div>
    <div class="text-right">
        <p class="text-sm text-gray-600"><?= date('l, d F Y') ?></p>
    </div>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Total Balance Card -->
    <div class="bg-white rounded-2xl border-2 border-blue-100 p-6 shadow-lg">
        <div class="flex items-center">
            <div class="flex items-center justify-center w-12 h-12 rounded-full bg-blue-50 mr-4">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6h18M3 12h18M3 18h18"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Total Saldo</p>
                <p class="text-2xl font-bold text-gray-800">Rp <?= number_format($totalBalance ?? 0, 0, ',', '.') ?></p>
            </div>
        </div>
    </div>

    <!-- Income Card -->
    <div class="bg-white rounded-2xl border-2 border-green-100 p-6 shadow-lg">
        <div class="flex items-center">
            <div class="flex items-center justify-center w-12 h-12 rounded-full bg-green-50 mr-4">
                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Pemasukan Bulan Ini</p>
                <p class="text-2xl font-bold text-gray-800">Rp <?= number_format($monthlyIncome ?? 0, 0, ',', '.') ?></p>
            </div>
        </div>
    </div>

    <!-- Expense Card -->
    <div class="bg-white rounded-2xl border-2 border-red-100 p-6 shadow-lg">
        <div class="flex items-center">
            <div class="flex items-center justify-center w-12 h-12 rounded-full bg-red-50 mr-4">
                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Pengeluaran Bulan Ini</p>
                <p class="text-2xl font-bold text-gray-800">Rp <?= number_format($monthlyExpense ?? 0, 0, ',', '.') ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column - Account Summary -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Ringkasan Akun</h2>
            <div class="space-y-4">
                <?php if (!empty($accounts)): foreach ($accounts as $account): ?>
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                        </div>
                        <span class="text-gray-700"><?= esc($account['nama_akun']) ?></span>
                    </div>
                    <span class="font-semibold text-gray-800">Rp <?= number_format($account['saldo'], 0, ',', '.') ?></span>
                </div>
                <?php endforeach; endif; ?>
                
                <a href="/accounts" class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-600 rounded-lg text-sm font-medium hover:bg-blue-100 transition-colors mt-2">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Kelola Akun
                </a>
            </div>
        </div>

        <!-- Budget Status -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Status Anggaran</h2>
            <div class="space-y-4">
                <?php if (!empty($budgets)): foreach ($budgets as $budget): ?>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600"><?= esc($budget['kategori']) ?></span>
                        <span class="text-gray-800"><?= $budget['percentage'] ?>%</span>
                    </div>
                    <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-<?= $budget['percentage'] > 85 ? 'red' : ($budget['percentage'] > 70 ? 'yellow' : 'green') ?>-500 rounded-full" 
                             style="width: <?= $budget['percentage'] ?>%"></div>
                    </div>
                    <div class="flex justify-between text-xs text-gray-500">
                        <span>Rp <?= number_format($budget['used'], 0, ',', '.') ?></span>
                        <span>Rp <?= number_format($budget['limit'], 0, ',', '.') ?></span>
                    </div>
                </div>
                <?php endforeach; endif; ?>
            </div>
        </div>
    </div>

    <!-- Center and Right Column - Charts -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Income vs Expense Chart -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Grafik Pemasukan & Pengeluaran</h2>
            <div class="h-[300px]" id="incomeExpenseChart">
                <!-- Chart will be rendered here -->
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Transaksi Terbaru</h2>
                <a href="/transactions" class="text-blue-600 hover:text-blue-700 text-sm font-medium">Lihat Semua</a>
            </div>
            <div class="space-y-4">
                <?php if (!empty($recentTransactions)): foreach ($recentTransactions as $trx): ?>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-<?= $trx['tipe'] === 'income' ? 'green' : 'red' ?>-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-<?= $trx['tipe'] === 'income' ? 'green' : 'red' ?>-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="<?= $trx['tipe'] === 'income' ? 'M5 10l7-7m0 0l7 7m-7-7v18' : 'M19 14l-7 7m0 0l-7-7m7 7V3' ?>"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800"><?= esc($trx['deskripsi']) ?></p>
                            <p class="text-xs text-gray-500"><?= esc($trx['category_name']) ?></p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold text-<?= $trx['tipe'] === 'income' ? 'green' : 'red' ?>-600">
                            <?= $trx['tipe'] === 'income' ? '+' : '-' ?>Rp <?= number_format($trx['jumlah'], 0, ',', '.') ?>
                        </p>
                        <p class="text-xs text-gray-500"><?= date('d/m/Y', strtotime($trx['tanggal'])) ?></p>
                    </div>
                </div>
                <?php endforeach; endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="fixed bottom-8 right-8">
    <div class="flex space-x-4">
        <a href="/transactions/expense/add" class="flex items-center px-4 py-2 bg-red-600 text-white rounded-lg shadow-lg hover:bg-red-700 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
            </svg>
            Tambah Pengeluaran
        </a>
        <a href="/transactions/income/add" class="flex items-center px-4 py-2 bg-green-600 text-white rounded-lg shadow-lg hover:bg-green-700 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
            </svg>
            Tambah Pemasukan
        </a>
    </div>
</div>

<!-- Add ApexCharts CDN -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<!-- Initialize Chart -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var options = {
        series: [{
            name: 'Pemasukan',
            data: <?= json_encode($chartData['income'] ?? []) ?>
        }, {
            name: 'Pengeluaran',
            data: <?= json_encode($chartData['expense'] ?? []) ?>
        }],
        chart: {
            type: 'area',
            height: 300,
            toolbar: {
                show: false
            }
        },
        colors: ['#10B981', '#EF4444'],
        stroke: {
            curve: 'smooth',
            width: 2
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
        xaxis: {
            categories: <?= json_encode($chartData['labels'] ?? []) ?>,
            labels: {
                style: {
                    colors: '#6B7280',
                    fontSize: '12px'
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
            }
        },
        legend: {
            position: 'top'
        }
    };

    var chart = new ApexCharts(document.querySelector("#incomeExpenseChart"), options);
    chart.render();
});
</script>

<?= $this->endSection() ?>
