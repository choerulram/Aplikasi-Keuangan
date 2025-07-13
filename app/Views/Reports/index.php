<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>
<h1 class="text-3xl font-bold text-main mb-4">Laporan</h1>

<form method="get" class="mb-6 flex flex-wrap gap-4 items-end">
    <div>
        <label class="block text-sm font-medium mb-1">Akun</label>
        <select name="account_id" class="form-select">
            <option value="">Semua Akun</option>
            <?php foreach ($accounts as $acc): ?>
                <option value="<?= $acc['id'] ?>" <?= $filters['account_id'] == $acc['id'] ? 'selected' : '' ?>><?= esc($acc['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Kategori</label>
        <select name="category_id" class="form-select">
            <option value="">Semua Kategori</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= $filters['category_id'] == $cat['id'] ? 'selected' : '' ?>><?= esc($cat['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Dari Tanggal</label>
        <input type="date" name="start_date" class="form-input" value="<?= esc($filters['start_date']) ?>">
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Sampai Tanggal</label>
        <input type="date" name="end_date" class="form-input" value="<?= esc($filters['end_date']) ?>">
    </div>
    <div>
        <button type="submit" class="btn btn-primary">Terapkan Filter</button>
    </div>
</form>

<div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="p-4 bg-white rounded shadow">
        <div class="text-gray-500 text-sm">Total Pemasukan</div>
        <div class="text-xl font-bold text-green-600">Rp <?= number_format($summary['total_income'] ?? 0, 0, ',', '.') ?></div>
    </div>
    <div class="p-4 bg-white rounded shadow">
        <div class="text-gray-500 text-sm">Total Pengeluaran</div>
        <div class="text-xl font-bold text-red-600">Rp <?= number_format($summary['total_expense'] ?? 0, 0, ',', '.') ?></div>
    </div>
    <div class="p-4 bg-white rounded shadow">
        <div class="text-gray-500 text-sm">Saldo Akhir</div>
        <div class="text-xl font-bold text-blue-600">Rp <?= number_format(($summary['total_income'] ?? 0) - ($summary['total_expense'] ?? 0), 0, ',', '.') ?></div>
    </div>
</div>

<div class="overflow-x-auto">
    <table class="min-w-full bg-white rounded shadow mt-4">
        <thead>
            <tr class="bg-gray-100">
                <th class="py-2 px-4 text-left">Tanggal</th>
                <th class="py-2 px-4 text-left">Akun</th>
                <th class="py-2 px-4 text-left">Kategori</th>
                <th class="py-2 px-4 text-left">Tipe</th>
                <th class="py-2 px-4 text-right">Jumlah</th>
                <th class="py-2 px-4 text-left">Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($transactions)): ?>
                <tr><td colspan="6" class="py-4 text-center text-gray-500">Tidak ada data transaksi.</td></tr>
            <?php else: ?>
                <?php foreach ($transactions as $trx): ?>
                    <tr>
                        <td class="py-2 px-4"><?= esc($trx['tanggal']) ?></td>
                        <td class="py-2 px-4"><?= esc($trx['account_name']) ?></td>
                        <td class="py-2 px-4"><?= esc($trx['category_name']) ?></td>
                        <td class="py-2 px-4">
                            <span class="px-2 py-1 rounded text-xs <?= $trx['tipe'] === 'income' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                                <?= $trx['tipe'] === 'income' ? 'Pemasukan' : 'Pengeluaran' ?>
                            </span>
                        </td>
                        <td class="py-2 px-4 text-right">Rp <?= number_format($trx['jumlah'], 0, ',', '.') ?></td>
                        <td class="py-2 px-4"><?= esc($trx['deskripsi']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Ekspor PDF/Excel dapat ditambahkan di sini -->

<?= $this->endSection() ?>
