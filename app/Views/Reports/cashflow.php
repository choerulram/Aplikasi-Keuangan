<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="container px-6 mx-auto grid">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Laporan Arus Kas
        </h2>
        
        <!-- Filter Section -->
        <div class="flex gap-4">
            <!-- Period Filter -->
            <div class="relative">
                <form id="filterForm" action="" method="get">
                    <select id="period" name="period" class="bg-white border border-gray-300 text-gray-700 rounded-lg px-4 py-2.5 focus:ring-main focus:border-main block w-48" onchange="this.form.submit()">
                        <option value="this_month" <?= ($period ?? '') === 'this_month' ? 'selected' : '' ?>>Bulan Ini</option>
                        <option value="last_month" <?= ($period ?? '') === 'last_month' ? 'selected' : '' ?>>Bulan Lalu</option>
                        <option value="this_year" <?= ($period ?? '') === 'this_year' ? 'selected' : '' ?>>Tahun Ini</option>
                        <option value="last_year" <?= ($period ?? '') === 'last_year' ? 'selected' : '' ?>>Tahun Lalu</option>
                        <option value="custom" <?= ($period ?? '') === 'custom' ? 'selected' : '' ?>>Pilih Tanggal</option>
                    </select>
                </form>
            </div>

            <!-- Custom Date Range -->
            <div id="dateRange" class="<?= ($period ?? '') === 'custom' ? 'flex' : 'hidden' ?> gap-2 items-center">
                <input type="date" name="start_date" class="bg-white border border-gray-300 text-gray-700 rounded-lg px-4 py-2 focus:ring-main focus:border-main" form="filterForm" value="<?= $filters['start_date'] ?? '' ?>">
                <span class="text-gray-500">sampai</span>
                <input type="date" name="end_date" class="bg-white border border-gray-300 text-gray-700 rounded-lg px-4 py-2 focus:ring-main focus:border-main" form="filterForm" value="<?= $filters['end_date'] ?? '' ?>">
            </div>

            <!-- Export Buttons -->
            <form action="<?= base_url('reports/export-excel') ?>" method="post" class="inline">
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Excel
                </button>
            </form>
            <form action="<?= base_url('reports/export-pdf') ?>" method="post" class="inline">
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    PDF
                </button>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-gray-600 text-lg font-semibold">Total Pemasukan</h3>
                <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
            </div>
            <p class="text-3xl font-bold text-green-600">Rp <?= number_format($summary['total_income'] ?? 0, 0, ',', '.') ?></p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-gray-600 text-lg font-semibold">Total Pengeluaran</h3>
                <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                </svg>
            </div>
            <p class="text-3xl font-bold text-red-600">Rp <?= number_format($summary['total_expense'] ?? 0, 0, ',', '.') ?></p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-gray-600 text-lg font-semibold">Arus Kas Bersih</h3>
                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4m8 8V4"/>
                </svg>
            </div>
            <p class="text-3xl font-bold text-blue-600">Rp <?= number_format(($summary['total_income'] ?? 0) - ($summary['total_expense'] ?? 0), 0, ',', '.') ?></p>
        </div>
    </div>

    <!-- Chart -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8 border border-gray-200">
        <h3 class="text-gray-600 text-lg font-semibold mb-4">Tren Arus Kas</h3>
        <div style="position: relative; height: 400px; width: 100%; max-height: 400px;">
            <canvas id="cashFlowChart"></canvas>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200">
        <div class="p-4 border-b border-gray-200">
            <h3 class="text-gray-600 text-lg font-semibold">Detail Transaksi</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-gray-600 text-sm font-semibold">Tanggal</th>
                        <th class="px-6 py-3 text-gray-600 text-sm font-semibold">Akun</th>
                        <th class="px-6 py-3 text-gray-600 text-sm font-semibold">Pemasukan</th>
                        <th class="px-6 py-3 text-gray-600 text-sm font-semibold">Pengeluaran</th>
                        <th class="px-6 py-3 text-gray-600 text-sm font-semibold">Saldo</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <!-- Sample data row -->
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-600">2025-08-08</td>
                        <td class="px-6 py-4 text-sm text-gray-600">Kas Utama</td>
                        <td class="px-6 py-4 text-sm text-green-600">Rp 5.000.000</td>
                        <td class="px-6 py-4 text-sm text-red-600">-</td>
                        <td class="px-6 py-4 text-sm text-blue-600">Rp 5.000.000</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex-1 flex justify-between sm:hidden">
                    <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Previous
                    </a>
                    <a href="#" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Next
                    </a>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Showing
                            <span class="font-medium">1</span>
                            to
                            <span class="font-medium">10</span>
                            of
                            <span class="font-medium">20</span>
                            results
                        </p>
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                            <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                Previous
                            </a>
                            <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                                1
                            </a>
                            <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                                2
                            </a>
                            <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                Next
                            </a>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Chart with fixed dimensions
    const ctx = document.getElementById('cashFlowChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode(array_column($chartData, 'month')) ?>,
            datasets: [{
                label: 'Pemasukan',
                data: <?= json_encode(array_column($chartData, 'income')) ?>,
                borderColor: '#10B981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.4,
                fill: true,
                borderWidth: 2
            }, {
                label: 'Pengeluaran',
                data: <?= json_encode(array_column($chartData, 'expense')) ?>,
                borderColor: '#EF4444',
                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                tension: 0.4,
                fill: true,
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: {
                            size: 12
                        }
                    }
                },
                title: {
                    display: false
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 11
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                        },
                        font: {
                            size: 11
                        }
                    },
                    grid: {
                        borderDash: [2, 2],
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                }
            },
            layout: {
                padding: {
                    top: 20,
                    right: 20,
                    bottom: 20,
                    left: 20
                }
            },
            elements: {
                point: {
                    radius: 4,
                    hitRadius: 10,
                    hoverRadius: 6
                },
                line: {
                    tension: 0.4
                }
            }
        }
    });

    // Handle period selection
    document.getElementById('period').addEventListener('change', function(e) {
        const dateRange = document.getElementById('dateRange');
        if (e.target.value === 'custom') {
            dateRange.classList.remove('hidden');
        } else {
            dateRange.classList.add('hidden');
        }
    });
});
</script>
<?= $this->endSection() ?>
