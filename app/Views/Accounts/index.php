<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>
<div class="flex items-center justify-between mb-6">
    <h1 class="text-3xl font-bold text-main tracking-tight drop-shadow-sm">Akun</h1>
    <a id="btnShowAddAccountModal" href="#" class="inline-flex items-center gap-2 px-4 py-2 bg-main text-white rounded-lg shadow hover:bg-highlight transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Tambah Akun
    </a>
</div>

<div class="overflow-x-auto rounded-lg shadow border border-gray-200 bg-white">
    <table class="min-w-full border border-gray-300">
        <thead class="bg-main/90">
            <tr>
                <th class="py-3 px-2 w-12 text-center text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">No.</th>
                <th class="py-3 px-4 text-left text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Nama Akun</th>
                <th class="py-3 px-4 text-left text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Tipe Akun</th>
                <th class="py-3 px-4 text-left text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Saldo Awal</th>
                <th class="py-3 px-4 text-left text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Catatan</th>
                <th class="py-3 px-4 text-left text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Tanggal Dibuat</th>
                <th class="py-3 px-2 w-40 text-center text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white">
            <?php if (!empty($accounts)): ?>
                <?php $no = 1; foreach ($accounts as $akun): ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-2 px-2 w-12 text-sm text-gray-700 font-medium border-b border-r border-gray-200 text-center"><?= $no++ ?></td>
                        <td class="py-2 px-4 text-sm text-gray-800 font-semibold border-b border-r border-gray-200"><?= esc($akun['nama_akun']) ?></td>
                        <td class="py-2 px-4 text-sm text-gray-600 border-b border-r border-gray-200"><?= esc($akun['tipe_akun']) ?></td>
                        <td class="py-2 px-4 text-sm text-green-700 font-bold border-b border-r border-gray-200">Rp <?= number_format($akun['saldo_awal'],2,',','.') ?></td>
                        <td class="py-2 px-4 text-sm text-gray-600 border-b border-r border-gray-200"><?= esc($akun['catatan']) ?></td>
                        <td class="py-2 px-4 text-sm text-gray-500 border-b border-r border-gray-200"><?= esc($akun['created_at']) ?></td>
                        <td class="py-2 px-2 w-40 text-center border-b border-r border-gray-200">
                            <div class="flex justify-center gap-1">
                                <a href="#" onclick="toggleDetailAccountModal(true, {
                                    nama_akun: '<?= esc($akun['nama_akun'], 'js') ?>',
                                    tipe_akun: '<?= esc($akun['tipe_akun'], 'js') ?>',
                                    saldo_awal: '<?= $akun['saldo_awal'] ?>',
                                    catatan: '<?= esc($akun['catatan'], 'js') ?>',
                                    created_at: '<?= esc($akun['created_at'], 'js') ?>'
                                })" class="inline-flex items-center px-2 py-1 text-xs font-semibold text-white bg-blue-500 rounded hover:bg-blue-600" title="Detail">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12h.01M12 12h.01M9 12h.01M21 12c0 4.418-4.03 8-9 8s-9-3.582-9-8 4.03-8 9-8 9 3.582 9 8z"/></svg>
                                    Detail
                                </a>
                                <a href="#" onclick="toggleEditAccountModal(true, {
                                    id: '<?= $akun['id'] ?>',
                                    nama_akun: '<?= esc($akun['nama_akun'], 'js') ?>',
                                    tipe_akun: '<?= esc($akun['tipe_akun'], 'js') ?>',
                                    saldo_awal: '<?= $akun['saldo_awal'] ?>',
                                    catatan: '<?= esc($akun['catatan'], 'js') ?>'
                                })" class="inline-flex items-center px-2 py-1 text-xs font-semibold text-white bg-yellow-500 rounded hover:bg-yellow-600" title="Ubah">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 13l6-6m2 2l-6 6m-2 2h6"/></svg>
                                    Ubah
                                </a>
                                <a href="/accounts/delete/<?= $akun['id'] ?>" class="inline-flex items-center px-2 py-1 text-xs font-semibold text-white bg-red-500 rounded hover:bg-red-600" title="Hapus" onclick="return confirm('Yakin ingin menghapus akun ini?')">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                    Hapus
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="py-4 px-4 text-center text-gray-400 border-b border-r border-gray-200">Tidak ada data akun.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include(APPPATH.'Views/Accounts/modal_add.php'); ?>
<?php include(APPPATH.'Views/Accounts/modal_edit.php'); ?>
<?php include(APPPATH.'Views/Accounts/modal_detail.php'); ?>

<?= $this->endSection() ?>
