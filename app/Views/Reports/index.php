<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>
<h1 class="text-3xl font-bold text-main mb-4">Laporan</h1>

<div class="flex flex-wrap items-end gap-2 mb-6">
    <form method="get" action="" class="flex flex-wrap gap-2 items-end flex-1">
        <div>
            <label for="account_id" class="block text-xs font-semibold text-gray-600 mb-1">Akun</label>
            <select name="account_id" id="account_id" class="px-3 py-2 border rounded-lg focus:outline-none focus:ring w-40">
                <option value="">Semua Akun</option>
                <?php foreach ($accounts as $acc): ?>
                    <option value="<?= $acc['id'] ?>" <?= $filters['account_id'] == $acc['id'] ? 'selected' : '' ?>><?= esc($acc['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="category_id" class="block text-xs font-semibold text-gray-600 mb-1">Kategori</label>
            <select name="category_id" id="category_id" class="px-3 py-2 border rounded-lg focus:outline-none focus:ring w-40">
                <option value="">Semua Kategori</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= $filters['category_id'] == $cat['id'] ? 'selected' : '' ?>><?= esc($cat['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="tipe" class="block text-xs font-semibold text-gray-600 mb-1">Tipe</label>
            <select name="tipe" id="tipe" class="px-3 py-2 border rounded-lg focus:outline-none focus:ring w-32">
                <option value="">Semua</option>
                <option value="income" <?= (isset($filters['tipe']) && $filters['tipe'] === 'income') ? 'selected' : '' ?>>Pemasukan</option>
                <option value="expense" <?= (isset($filters['tipe']) && $filters['tipe'] === 'expense') ? 'selected' : '' ?>>Pengeluaran</option>
            </select>
        </div>
        <div>
            <label for="month" class="block text-xs font-semibold text-gray-600 mb-1">Bulan</label>
            <select name="month" id="month" class="px-3 py-2 border rounded-lg focus:outline-none focus:ring w-32">
                <option value="">Semua</option>
                <?php for ($m = 1; $m <= 12; $m++): ?>
                    <option value="<?= $m ?>" <?= (isset($filters['month']) && $filters['month'] == $m) ? 'selected' : '' ?>><?= date('F', mktime(0,0,0,$m,1)) ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div>
            <label for="year" class="block text-xs font-semibold text-gray-600 mb-1">Tahun</label>
            <select name="year" id="year" class="px-3 py-2 border rounded-lg focus:outline-none focus:ring w-32">
                <option value="">Semua</option>
                <?php $yearNow = date('Y');
                for ($y = $yearNow; $y >= $yearNow-10; $y--): ?>
                    <option value="<?= $y ?>" <?= (isset($filters['year']) && $filters['year'] == $y) ? 'selected' : '' ?>><?= $y ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div>
            <label for="start_date" class="block text-xs font-semibold text-gray-600 mb-1">Dari Tanggal</label>
            <input type="date" name="start_date" id="start_date" value="<?= esc($filters['start_date']) ?>" class="px-3 py-2 border rounded-lg focus:outline-none focus:ring w-40" />
        </div>
        <div>
            <label for="end_date" class="block text-xs font-semibold text-gray-600 mb-1">Sampai Tanggal</label>
            <input type="date" name="end_date" id="end_date" value="<?= esc($filters['end_date']) ?>" class="px-3 py-2 border rounded-lg focus:outline-none focus:ring w-40" />
        </div>
        <div class="flex gap-2 items-end">
            <button type="submit" class="px-4 py-2 bg-main text-white rounded-lg font-semibold shadow hover:bg-highlight transition">Terapkan</button>
            <?php if (!empty($filters['account_id']) || !empty($filters['category_id']) || !empty($filters['tipe']) || !empty($filters['start_date']) || !empty($filters['end_date'])): ?>
                <a href="/reports" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition">Reset</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<div class="flex flex-wrap justify-between items-center mb-4 gap-2">
    <div class="flex gap-2">
        <form method="post" action="/reports/export/pdf" target="_blank">
            <?php foreach ($filters as $k => $v): if ($v): ?><input type="hidden" name="<?= $k ?>" value="<?= esc($v) ?>" /><?php endif; endforeach; ?>
            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg font-semibold shadow hover:bg-red-700 transition">Ekspor PDF</button>
        </form>
        <form method="post" action="/reports/export/excel" target="_blank">
            <?php foreach ($filters as $k => $v): if ($v): ?><input type="hidden" name="<?= $k ?>" value="<?= esc($v) ?>" /><?php endif; endforeach; ?>
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg font-semibold shadow hover:bg-green-700 transition">Ekspor Excel</button>
        </form>
    </div>
</div>
<div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="flex items-center p-6 bg-green-50 border-2 border-green-300 rounded-2xl shadow-lg">
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
    <div class="flex items-center p-6 bg-red-50 border-2 border-red-300 rounded-2xl shadow-lg">
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
    <div class="flex items-center p-6 bg-blue-50 border-2 border-blue-300 rounded-2xl shadow-lg">
        <div class="flex items-center justify-center w-16 h-16 rounded-full bg-blue-200 mr-5">
            <svg class="w-10 h-10 text-blue-700" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M2 7a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V7z" stroke="currentColor" stroke-width="2.5" fill="#e0e7ff"/>
                <path d="M22 9H18a2 2 0 0 0-2 2v2a2 2 0 0 0 2 2h4" stroke="currentColor" stroke-width="2.5"/>
                <circle cx="18.5" cy="12" r="1" fill="currentColor"/>
            </svg>
        </div>
        <div>
            <div class="text-base font-bold text-blue-800 mb-1 tracking-wide">Saldo Akhir</div>
            <div class="text-3xl font-extrabold text-blue-700">Rp <?= number_format(($summary['total_income'] ?? 0) - ($summary['total_expense'] ?? 0), 0, ',', '.') ?></div>
        </div>
    </div>
</div>


<div class="overflow-x-auto rounded-lg shadow border border-gray-200 bg-white">
    <table class="min-w-full border border-gray-300">
        <thead class="bg-main/90">
            <tr>
                <th class="py-3 px-2 w-12 text-center text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">No.</th>
                <th class="py-3 px-4 text-left text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Deskripsi</th>
                <th class="py-3 px-4 text-left text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Jumlah</th>
                <th class="py-3 px-4 text-left text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Akun</th>
                <th class="py-3 px-4 text-left text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Kategori</th>
                <th class="py-3 px-4 text-left text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Tipe</th>
                <th class="py-3 px-4 text-left text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Tanggal</th>
            </tr>
        </thead>
        <tbody class="bg-white">
            <?php if (!empty($transactions)): ?>
                <?php $no = 1; foreach ($transactions as $trx): ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-2 px-2 w-12 text-sm text-gray-700 font-medium border-b border-r border-gray-200 text-center"><?= $no++ ?></td>
                        <td class="py-2 px-4 text-sm text-gray-600 border-b border-r border-gray-200"><?= esc($trx['deskripsi']) ?></td>
                        <td class="py-2 px-4 text-sm font-bold border-b border-r border-gray-200 text-left">Rp <?= number_format($trx['jumlah'], 0, ',', '.') ?></td>
                        <td class="py-2 px-4 text-sm text-gray-700 border-b border-r border-gray-200"><?= esc($trx['account_name']) ?></td>
                        <td class="py-2 px-4 text-sm text-gray-700 border-b border-r border-gray-200"><?= esc($trx['category_name']) ?></td>
                        <td class="py-2 px-4 text-sm text-<?= $trx['tipe'] === 'income' ? 'green-700' : 'red-700' ?> font-bold border-b border-r border-gray-200">
                            <?= ucfirst(esc($trx['tipe'])) ?>
                        </td>
                        <td class="py-2 px-4 text-sm text-gray-800 border-b border-r border-gray-200"><?= esc($trx['tanggal']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="py-4 px-4 text-center text-gray-400 border-b border-r border-gray-200">Tidak ada data transaksi.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
