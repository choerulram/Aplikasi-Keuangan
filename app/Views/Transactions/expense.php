<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>


<div class="mb-2">
    <h1 class="text-2xl md:text-3xl font-bold text-main tracking-tight drop-shadow-sm mb-4 leading-tight">Transaksi Pengeluaran</h1>
</div>
<div class="flex flex-col md:flex-row md:flex-wrap md:items-end gap-2 mb-6">
    <form method="get" action="" class="flex flex-col gap-2 md:flex-row md:flex-wrap md:gap-2 md:items-end flex-1 w-full">
        <div class="w-full md:w-auto">
            <label for="search" class="block text-xs font-semibold text-gray-600 mb-1">Cari Deskripsi</label>
            <input type="text" name="search" id="search" value="<?= esc($search ?? '') ?>" placeholder="Cari deskripsi..." class="px-3 py-2 border rounded-lg focus:outline-none focus:ring w-full md:w-80 text-xs md:text-sm" />
        </div>
        <div class="w-full md:w-auto">
            <label for="account" class="block text-xs font-semibold text-gray-600 mb-1">Akun</label>
            <select name="account" id="account" class="px-3 py-2 border rounded-lg focus:outline-none focus:ring w-full md:w-40 text-xs md:text-sm">
                <option value="">Semua Akun</option>
                <?php foreach (($accounts ?? []) as $acc): ?>
                    <option value="<?= $acc['id'] ?>" <?= (isset($account) && $account == $acc['id']) ? 'selected' : '' ?>><?= esc($acc['nama_akun']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="w-full md:w-auto">
            <label for="category" class="block text-xs font-semibold text-gray-600 mb-1">Kategori</label>
            <select name="category" id="category" class="px-3 py-2 border rounded-lg focus:outline-none focus:ring w-full md:w-40 text-xs md:text-sm">
                <option value="">Semua Kategori</option>
                <?php foreach (($categories ?? []) as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= (isset($category) && $category == $cat['id']) ? 'selected' : '' ?>><?= esc($cat['nama_kategori']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="w-full md:w-auto">
            <label for="date" class="block text-xs font-semibold text-gray-600 mb-1">Tanggal</label>
            <input type="date" name="date" id="date" value="<?= esc($date ?? '') ?>" class="px-3 py-2 border rounded-lg focus:outline-none focus:ring w-full md:w-40 text-xs md:text-sm" />
        </div>
        <div class="flex gap-2 items-end w-full md:w-auto mt-2 md:mt-0">
            <button type="submit" class="inline-flex items-center justify-center gap-2 px-3 py-2 md:px-4 md:py-2 bg-main text-white rounded-lg font-semibold shadow hover:bg-highlight transition h-9 md:h-11 text-sm md:text-sm">Terapkan</button>
            <?php if (!empty($search) || !empty($account) || !empty($category) || !empty($date)): ?>
                <a href="/transactions/expense" class="px-3 py-1.5 md:px-4 md:py-2 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition text-xs md:text-sm">Reset</a>
            <?php endif; ?>
        </div>
    </form>
    <button id="btnShowAddExpenseTransactionModal" type="button" class="inline-flex items-center gap-2 px-3 py-2 md:px-4 md:py-2 bg-main text-white rounded-lg shadow hover:bg-highlight transition h-9 md:h-11 text-sm md:text-base mt-2 md:mt-0">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Tambah
    </button>
<?= view('Transactions/modal_add_expense', ['accounts' => $accounts, 'categories' => $categories]) ?>
<?= view('Transactions/modal_detail_expense', ['isAdmin' => $isAdmin]) ?>
<?= view('Transactions/modal_edit_expense', ['accounts' => $accounts, 'categories' => $categories]) ?>
<?= view('Transactions/modal_delete_expense') ?>
</div>
<div class="overflow-x-auto rounded-lg shadow border border-gray-200 bg-white">
    <table class="min-w-full border border-gray-300 text-xs md:text-sm">
        <thead class="bg-main/90">
            <tr>
                <th class="py-3 px-2 w-12 text-center text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">No.</th>
                <th class="py-3 px-4 text-left text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Deskripsi</th>
                <th class="py-3 px-4 text-left text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Jumlah</th>
                <th class="py-3 px-4 text-left text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Akun</th>
                <th class="py-3 px-4 text-left text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Kategori</th>
                <th class="py-3 px-4 text-left text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Tipe</th>
                <th class="py-3 px-4 text-left text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Tanggal</th>
                <?php if ($isAdmin): ?>
                <th class="py-3 px-4 text-left text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">User</th>
                <?php endif; ?>
                <th class="py-3 px-2 w-40 text-center text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white">
            <?php if (!empty($transactions)): ?>
                <?php 
                $no = 1 + ($pager->getCurrentPage('transactions') - 1) * $perPage;
                foreach ($transactions as $trx): ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-2 px-2 w-12 text-xs md:text-sm text-gray-700 font-medium border-b border-r border-gray-200 text-center"><?= $no++ ?></td>
                        <td class="py-2 px-2 md:px-4 text-xs md:text-sm text-gray-600 border-b border-r border-gray-200"><?= esc($trx['deskripsi']) ?></td>
                        <td class="py-2 px-2 md:px-4 text-xs md:text-sm font-bold border-b border-r border-gray-200">
                            <span class="whitespace-nowrap flex items-center justify-start">
                                <?= function_exists('format_rupiah') ? format_rupiah($trx['jumlah']) : 'Rp ' . number_format($trx['jumlah'],2,',','.') ?>
                            </span>
                        </td>
                        <td class="py-2 px-2 md:px-4 text-xs md:text-sm text-gray-700 border-b border-r border-gray-200"><?= esc($trx['nama_akun']) ?></td>
                        <td class="py-2 px-2 md:px-4 text-xs md:text-sm text-gray-700 border-b border-r border-gray-200"><?= esc($trx['nama_kategori']) ?></td>
                        <td class="py-2 px-2 md:px-4 text-xs md:text-sm text-<?= $trx['tipe'] === 'income' ? 'green-700' : 'red-700' ?> font-bold border-b border-r border-gray-200">
                            <?= ucfirst(esc($trx['tipe'])) ?>
                        </td>
                        <td class="py-2 px-2 md:px-4 text-xs md:text-sm text-gray-800 border-b border-r border-gray-200"><?= esc($trx['tanggal']) ?></td>
                        <?php if ($isAdmin): ?>
                        <td class="py-2 px-2 md:px-4 text-xs md:text-sm text-gray-700 border-b border-r border-gray-200">
                            <?= esc($trx['username']) ?>
                        </td>
                        <?php endif; ?>
                        <td class="py-2 px-2 md:px-4 w-40 text-center border-b border-r border-gray-200">
                            <div class="flex flex-col md:flex-row items-center md:justify-center gap-1">
                                <a href="#" onclick='toggleDetailExpenseTransactionModal(true, { deskripsi: "<?= esc($trx['deskripsi']) ?>", jumlah: "<?= esc($trx['jumlah']) ?>", nama_akun: "<?= esc($trx['nama_akun']) ?>", nama_kategori: "<?= esc($trx['nama_kategori']) ?>", tanggal: "<?= esc($trx['tanggal']) ?>", tipe: "<?= esc($trx['tipe']) ?>"<?= $isAdmin ? ", username: \"" . esc($trx['username']) . "\"" : "" ?> })' class="flex items-center justify-center w-full md:inline-flex md:w-auto px-2 py-1 text-xs font-semibold text-white bg-blue-500 rounded hover:bg-blue-600" title="Detail">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M1.5 12s3.5-7 10.5-7 10.5 7 10.5 7-3.5 7-10.5 7S1.5 12 1.5 12z"/>
                                      <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    Detail
                                </a>
                                <a href="#" onclick='toggleEditExpenseTransactionModal(true, { id: "<?= $trx['id'] ?>", deskripsi: "<?= esc($trx['deskripsi']) ?>", jumlah: "<?= esc($trx['jumlah']) ?>", account_id: "<?= esc($trx['account_id']) ?>", category_id: "<?= esc($trx['category_id']) ?>", tanggal: "<?= esc($trx['tanggal']) ?>" })' class="flex items-center justify-center w-full md:inline-flex md:w-auto px-2 py-1 text-xs font-semibold text-white bg-yellow-500 rounded hover:bg-yellow-600" title="Ubah">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 5.487l1.65 1.65a2.121 2.121 0 010 3l-8.486 8.486a2 2 0 01-.878.513l-3.06.765a.5.5 0 01-.606-.606l.765-3.06a2 2 0 01.513-.878l8.486-8.486a2.121 2.121 0 013 0z"/>
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 7l2 2"/>
                                    </svg>
                                    Ubah
                                </a>
                                <a href="#" onclick='toggleDeleteExpenseTransactionModal(true, { id: "<?= $trx['id'] ?>", deskripsi: "<?= esc($trx['deskripsi']) ?>" })' class="flex items-center justify-center w-full md:inline-flex md:w-auto px-2 py-1 text-xs font-semibold text-white bg-red-500 rounded hover:bg-red-600" title="Hapus">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0 1 16.138 21H7.862a2 2 0 0 1-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M8 7V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2" />
                                    </svg>
                                    Hapus
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="<?= $isAdmin ? 8 : 7 ?>" class="py-4 px-4 text-center text-gray-400 border-b border-r border-gray-200">Tidak ada data pengeluaran.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php if (isset($pager) && isset($total_transactions) && $total_transactions > $perPage): ?>
<div class="mt-4 flex justify-center">
    <nav class="inline-flex rounded-md shadow-sm" aria-label="Pagination">
        <?= view('Transactions/pagination', ['pager' => $pager, 'group' => 'transactions']) ?>
    </nav>
</div>
<?php endif; ?>

<?= $this->endSection() ?>
