<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="mb-2">
    <h1 class="text-3xl font-bold text-main tracking-tight drop-shadow-sm mb-4">Transaksi Pengeluaran</h1>
</div>
<div class="overflow-x-auto rounded-lg shadow border border-gray-200 bg-white">
    <table class="min-w-full border border-gray-300">
        <thead class="bg-main/90">
            <tr>
                <th class="py-3 px-2 w-12 text-center text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">No.</th>
                <th class="py-3 px-4 text-left text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Tanggal</th>
                <th class="py-3 px-4 text-left text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Akun</th>
                <th class="py-3 px-4 text-left text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Kategori</th>
                <th class="py-3 px-4 text-left text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Jumlah</th>
                <th class="py-3 px-4 text-left text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Deskripsi</th>
                <?php if ($isAdmin): ?>
                <th class="py-3 px-4 text-left text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">User</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody class="bg-white">
            <?php if (!empty($transactions)): ?>
                <?php $no = 1; foreach ($transactions as $trx): ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-2 px-2 w-12 text-sm text-gray-700 font-medium border-b border-r border-gray-200 text-center"><?= $no++ ?></td>
                        <td class="py-2 px-4 text-sm text-gray-800 border-b border-r border-gray-200"><?= esc($trx['tanggal']) ?></td>
                        <td class="py-2 px-4 text-sm text-gray-700 border-b border-r border-gray-200"><?= esc($trx['nama_akun']) ?></td>
                        <td class="py-2 px-4 text-sm text-gray-700 border-b border-r border-gray-200"><?= esc($trx['nama_kategori']) ?></td>
                        <td class="py-2 px-4 text-sm font-bold border-b border-r border-gray-200">
                            <?= function_exists('format_rupiah') ? format_rupiah($trx['jumlah']) : 'Rp ' . number_format($trx['jumlah'],2,',','.') ?>
                        </td>
                        <td class="py-2 px-4 text-sm text-gray-600 border-b border-r border-gray-200"><?= esc($trx['deskripsi']) ?></td>
                        <?php if ($isAdmin): ?>
                        <td class="py-2 px-4 text-sm text-gray-700 border-b border-r border-gray-200">
                            <?= esc($trx['username']) ?>
                        </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="<?= $isAdmin ? 7 : 6 ?>" class="py-4 px-4 text-center text-gray-400 border-b border-r border-gray-200">Tidak ada data pengeluaran.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
