<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="container px-6 mx-auto grid">
    <h1 class="text-3xl font-bold text-main mb-4">Laporan Arus Kas</h1>

    <!-- Filter & Export Row -->
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-6">
        <form id="filterForm" method="get" action="" class="flex flex-wrap gap-2 items-end flex-1">
            <div>
                <label for="period" class="block text-xs font-semibold text-gray-600 mb-1">Periode</label>
                <select id="period" name="period" class="px-3 py-2 border rounded-lg focus:outline-none focus:ring w-40" onchange="this.form.submit()">
                    <option value="this_month" <?= ($period ?? '') === 'this_month' ? 'selected' : '' ?>>Bulan Ini</option>
                    <option value="last_month" <?= ($period ?? '') === 'last_month' ? 'selected' : '' ?>>Bulan Lalu</option>
                    <option value="this_year" <?= ($period ?? '') === 'this_year' ? 'selected' : '' ?>>Tahun Ini</option>
                    <option value="last_year" <?= ($period ?? '') === 'last_year' ? 'selected' : '' ?>>Tahun Lalu</option>
                    <option value="custom" <?= ($period ?? '') === 'custom' ? 'selected' : '' ?>>Pilih Tanggal</option>
                </select>
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
            <form method="post" action="/reports/export/pdf" target="_blank">
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg font-semibold shadow hover:bg-red-700 transition">Ekspor PDF</button>
            </form>
            <form method="post" action="/reports/export/excel" target="_blank">
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
    <div class="bg-white rounded-lg shadow border border-gray-200 mb-8">
        <div class="p-4 border-b border-gray-200">
            <h3 class="text-gray-600 text-lg font-semibold">Tren Arus Kas</h3>
        </div>
        <div class="p-4">
            <div style="position: relative; height: 400px; width: 100%; max-height: 400px;">
                <canvas id="cashFlowChart"></canvas>
            </div>
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
                        <th class="py-3 px-2 w-12 text-center text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">No.</th>
                        <th class="py-3 px-4 text-left text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Tanggal</th>
                        <th class="py-3 px-4 text-left text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Akun</th>
                        <th class="py-3 px-4 text-left text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Pemasukan</th>
                        <th class="py-3 px-4 text-left text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Pengeluaran</th>
                        <th class="py-3 px-4 text-left text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Saldo</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-2 px-2 w-12 text-sm text-gray-700 font-medium border-b border-r border-gray-200 text-center">1</td>
                        <td class="py-2 px-4 text-sm text-gray-800 border-b border-r border-gray-200">2025-08-13</td>
                        <td class="py-2 px-4 text-sm text-gray-700 border-b border-r border-gray-200">Kas Utama</td>
                        <td class="py-2 px-4 text-sm text-green-700 font-bold border-b border-r border-gray-200">Rp 5.000.000</td>
                        <td class="py-2 px-4 text-sm text-red-700 font-bold border-b border-r border-gray-200">-</td>
                        <td class="py-2 px-4 text-sm text-blue-700 font-bold border-b border-r border-gray-200">Rp 5.000.000</td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-2 px-2 w-12 text-sm text-gray-700 font-medium border-b border-r border-gray-200 text-center">2</td>
                        <td class="py-2 px-4 text-sm text-gray-800 border-b border-r border-gray-200">2025-08-13</td>
                        <td class="py-2 px-4 text-sm text-gray-700 border-b border-r border-gray-200">Bank BCA</td>
                        <td class="py-2 px-4 text-sm text-green-700 font-bold border-b border-r border-gray-200">-</td>
                        <td class="py-2 px-4 text-sm text-red-700 font-bold border-b border-r border-gray-200">Rp 1.500.000</td>
                        <td class="py-2 px-4 text-sm text-blue-700 font-bold border-b border-r border-gray-200">Rp 3.500.000</td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-2 px-2 w-12 text-sm text-gray-700 font-medium border-b border-r border-gray-200 text-center">3</td>
                        <td class="py-2 px-4 text-sm text-gray-800 border-b border-r border-gray-200">2025-08-13</td>
                        <td class="py-2 px-4 text-sm text-gray-700 border-b border-r border-gray-200">E-Wallet</td>
                        <td class="py-2 px-4 text-sm text-green-700 font-bold border-b border-r border-gray-200">Rp 2.000.000</td>
                        <td class="py-2 px-4 text-sm text-red-700 font-bold border-b border-r border-gray-200">-</td>
                        <td class="py-2 px-4 text-sm text-blue-700 font-bold border-b border-r border-gray-200">Rp 5.500.000</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <?php if (isset($pager) && isset($total_transactions) && $total_transactions > $perPage): ?>
    <div class="mt-4 flex justify-center">
        <nav class="inline-flex rounded-md shadow-sm" aria-label="Pagination">
            <?= view('Reports/pagination', ['pager' => $pager, 'group' => 'transactions']) ?>
        </nav>
    </div>
    <?php endif; ?>
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
