<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="mb-2">
    <h1 class="text-3xl font-bold text-main tracking-tight drop-shadow-sm mb-4">Kategori</h1>
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
