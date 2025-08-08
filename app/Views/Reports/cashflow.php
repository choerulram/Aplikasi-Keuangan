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
                <select id="period" name="period" class="bg-white border border-gray-300 text-gray-700 rounded-lg px-4 py-2.5 focus:ring-main focus:border-main block w-48">
                    <option value="this_month">Bulan Ini</option>
                    <option value="last_month">Bulan Lalu</option>
                    <option value="this_year">Tahun Ini</option>
                    <option value="last_year">Tahun Lalu</option>
                    <option value="custom">Kustom...</option>
                </select>
            </div>

            <!-- Custom Date Range (initially hidden) -->
            <div id="dateRange" class="hidden flex gap-2 items-center">
                <input type="date" class="bg-white border border-gray-300 text-gray-700 rounded-lg px-4 py-2 focus:ring-main focus:border-main">
                <span class="text-gray-500">sampai</span>
                <input type="date" class="bg-white border border-gray-300 text-gray-700 rounded-lg px-4 py-2 focus:ring-main focus:border-main">
            </div>

            <!-- Export Buttons -->
            <button class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Excel
            </button>
            <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                PDF
            </button>
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
            <p class="text-3xl font-bold text-green-600">Rp 15.000.000</p>
            <p class="text-sm text-gray-500 mt-2">+20% dari bulan lalu</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-gray-600 text-lg font-semibold">Total Pengeluaran</h3>
                <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4m8 8V4"/>
                </svg>
            </div>
            <p class="text-3xl font-bold text-red-600">Rp 8.000.000</p>
            <p class="text-sm text-gray-500 mt-2">-5% dari bulan lalu</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-gray-600 text-lg font-semibold">Arus Kas Bersih</h3>
                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-3xl font-bold text-blue-600">Rp 7.000.000</p>
            <p class="text-sm text-gray-500 mt-2">+45% dari bulan lalu</p>
        </div>
    </div>

    <!-- Chart -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8 border border-gray-200">
        <h3 class="text-gray-600 text-lg font-semibold mb-4">Tren Arus Kas</h3>
        <canvas id="cashFlowChart" class="w-full" style="height: 400px;"></canvas>
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
    // Initialize Chart
    const ctx = document.getElementById('cashFlowChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug'],
            datasets: [{
                label: 'Pemasukan',
                data: [12000000, 15000000, 18000000, 14000000, 16000000, 15000000, 17000000, 15000000],
                borderColor: '#10B981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Pengeluaran',
                data: [8000000, 9000000, 11000000, 8500000, 10000000, 8000000, 9500000, 8000000],
                borderColor: '#EF4444',
                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                tension: 0.4,
                fill: true
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
                },
                title: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        }
                    }
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
