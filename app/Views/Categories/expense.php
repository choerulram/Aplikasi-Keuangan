<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="mb-2">
    <h1 class="text-3xl font-bold text-main tracking-tight drop-shadow-sm mb-4">Kategori Pengeluaran</h1>
</div>
<div class="flex flex-wrap items-end gap-2 mb-6">
    <form method="get" action="" class="flex flex-wrap gap-2 items-end flex-1">
        <div>
            <label for="search" class="block text-xs font-semibold text-gray-600 mb-1">Cari Kategori</label>
            <input type="text" name="search" id="search" value="<?= esc($search ?? '') ?>" placeholder="Cari nama kategori..." class="px-3 py-2 border rounded-lg focus:outline-none focus:ring w-80 md:w-[28rem]" />
        </div>
        <div class="flex gap-2 items-end">
            <button type="submit" class="px-4 py-2 bg-main text-white rounded-lg font-semibold shadow hover:bg-highlight transition">Terapkan</button>
            <?php if (!empty($search)): ?>
                <a href="/categories/expense" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition">Reset</a>
            <?php endif; ?>
        </div>
    </form>
    <a id="btnShowAddCategoryModal" href="#" class="inline-flex items-center gap-2 px-4 py-2 bg-main text-white rounded-lg shadow hover:bg-highlight transition h-11">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Tambah
    </a>
</div>
<div class="overflow-x-auto rounded-lg shadow border border-gray-200 bg-white">
    <table class="min-w-full border border-gray-300">
        <thead class="bg-main/90">
            <tr>
                <th class="py-3 px-2 w-12 text-center text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">No.</th>
                <th class="py-3 px-4 text-left text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Nama Kategori</th>
                <th class="py-3 px-4 text-left text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Tipe</th>
                <th class="py-3 px-4 text-left text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Tanggal Dibuat</th>
                <?php if ($role === 'admin'): ?>
                <th class="py-3 px-4 text-left text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">User</th>
                <?php endif; ?>
                <?php /* Kolom Aksi */ ?>
                <th class="py-3 px-2 w-40 text-center text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white">
            <?php if (!empty($categories)): ?>
                <?php 
                $no = 1 + ($pager->getCurrentPage('categories') - 1) * $perPage;
                foreach ($categories as $kategori): ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-2 px-2 w-12 text-sm text-gray-700 font-medium border-b border-r border-gray-200 text-center"><?= $no++ ?></td>
                        <td class="py-2 px-4 text-sm text-gray-800 font-semibold border-b border-r border-gray-200"><?= esc($kategori['nama_kategori']) ?></td>
                        <td class="py-2 px-4 text-sm text-gray-600 border-b border-r border-gray-200"><?= esc($kategori['tipe']) ?></td>
                        <td class="py-2 px-4 text-sm text-gray-500 border-b border-r border-gray-200"><?= esc($kategori['created_at']) ?></td>
                        <?php if ($role === 'admin'): ?>
                        <td class="py-2 px-4 text-sm text-gray-700 border-b border-r border-gray-200">
                            <?php if (!empty($kategori['username'])): ?>
                                <?= esc($kategori['username']) ?>
                            <?php else: ?>
                                <span class="text-gray-400 italic">-</span>
                            <?php endif; ?>
                        </td>
                        <?php endif; ?>
                        <td class="py-2 px-2 w-40 text-center border-b border-r border-gray-200">
                            <div class="flex justify-center gap-1">
                                <a href="#" onclick="toggleDetailCategoryModal(true, {
                                    id: '<?= $kategori['id'] ?>',
                                    nama_kategori: '<?= esc($kategori['nama_kategori'], 'js') ?>',
                                    tipe: '<?= esc($kategori['tipe'], 'js') ?>',
                                    created_at: '<?= esc($kategori['created_at'], 'js') ?>',
                                    username: '<?= isset($kategori['username']) ? esc($kategori['username'], 'js') : '' ?>'
                                })" class="inline-flex items-center px-2 py-1 text-xs font-semibold text-white bg-blue-500 rounded hover:bg-blue-600" title="Detail">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M1.5 12s3.5-7 10.5-7 10.5 7 10.5 7-3.5 7-10.5 7S1.5 12 1.5 12z"/>
                                      <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    Detail
                                </a>
                                <a href="#" onclick="toggleEditCategoryModal(true, {
                                    id: '<?= $kategori['id'] ?>',
                                    nama_kategori: '<?= esc($kategori['nama_kategori'], 'js') ?>',
                                    tipe: '<?= esc($kategori['tipe'], 'js') ?>'
                                })" class="inline-flex items-center px-2 py-1 text-xs font-semibold text-white bg-yellow-500 rounded hover:bg-yellow-600" title="Ubah">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 5.487l1.65 1.65a2.121 2.121 0 010 3l-8.486 8.486a2 2 0 01-.878.513l-3.06.765a.5.5 0 01-.606-.606l.765-3.06a2 2 0 01.513-.878l8.486-8.486a2.121 2.121 0 013 0z"/>
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 7l2 2"/>
                                    </svg>
                                    Ubah
                                </a>
                                <a href="#" onclick="toggleDeleteCategoryModal(true, {
                                    id: '<?= $kategori['id'] ?>',
                                    nama_kategori: '<?= esc($kategori['nama_kategori'], 'js') ?>'
                                })" class="inline-flex items-center px-2 py-1 text-xs font-semibold text-white bg-red-500 rounded hover:bg-red-600" title="Hapus">
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
                    <td colspan="<?= $role === 'admin' ? 5 : 4 ?>" class="py-4 px-4 text-center text-gray-400 border-b border-r border-gray-200">Tidak ada data kategori.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php if (isset($pager) && isset($total_categories) && $total_categories > 10): ?>
<div class="mt-4 flex justify-center">
    <nav class="inline-flex rounded-md shadow-sm" aria-label="Pagination">
        <?= view('Categories/pagination', ['pager' => $pager]) ?>
    </nav>
</div>
<?php endif; ?>

<?= $this->endSection() ?>
