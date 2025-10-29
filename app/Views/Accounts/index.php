<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="mb-2">
    <h1 class="text-2xl md:text-3xl font-bold text-main tracking-tight drop-shadow-sm mb-4">Akun</h1>
</div>
<div class="flex flex-wrap items-end gap-2 mb-6">
    <form method="get" action="" class="flex flex-wrap gap-2 items-end flex-1">
        <div>
            <label for="search" class="block text-xs font-semibold text-gray-600 mb-1">Cari Akun</label>
            <input type="text" name="search" id="search" value="<?= esc($search) ?>" placeholder="Cari nama akun atau catatan..." class="px-3 py-2 border rounded-lg focus:outline-none focus:ring w-full md:w-[28rem] text-xs md:text-sm" />
        </div>
        <div>
            <label for="filter" class="block text-xs font-semibold text-gray-600 mb-1">Tipe Akun</label>
            <select name="filter" id="filter" class="px-3 py-2 border rounded-lg focus:outline-none focus:ring w-full md:w-40 text-xs md:text-sm">
                <option value="">Semua Tipe</option>
                <?php if (!empty($tipeAkunList)): foreach ($tipeAkunList as $tipe): ?>
                    <option value="<?= esc($tipe['tipe_akun']) ?>" <?= $filter == $tipe['tipe_akun'] ? 'selected' : '' ?>><?= ucfirst(esc($tipe['tipe_akun'])) ?></option>
                <?php endforeach; endif; ?>
            </select>
        </div>
        <div class="flex gap-2 items-end">
            <button type="submit" class="inline-flex items-center justify-center gap-2 px-3 py-2 md:px-4 md:py-2 bg-main text-white rounded-lg font-semibold shadow hover:bg-highlight transition h-9 md:h-11 text-sm md:text-sm">Terapkan</button>
            <?php if (!empty($search) || !empty($filter)): ?>
                <a href="/accounts" class="px-3 py-1.5 md:px-4 md:py-2 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition text-xs md:text-sm">Reset</a>
            <?php endif; ?>
        </div>
    </form>
    <a id="btnShowAddAccountModal" href="#" class="inline-flex items-center gap-2 px-3 py-2 md:px-4 md:py-2 bg-main text-white rounded-lg shadow hover:bg-highlight transition h-9 md:h-11 text-sm md:text-base">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Tambah Akun
    </a>
</div>

<div class="overflow-x-auto rounded-lg shadow border border-gray-200 bg-white">
    <table class="min-w-full border border-gray-300 text-xs md:text-sm">
        <thead class="bg-main/90">
            <tr>
                <th class="py-3 px-2 w-12 text-center text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">No.</th>
                <th class="py-3 px-4 text-left text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Nama Akun</th>
                <th class="py-3 px-4 text-left text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Tipe Akun</th>
                <th class="py-3 px-4 text-left text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Saldo Awal</th>
                <th class="py-3 px-4 text-left text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Catatan</th>
                <th class="py-3 px-4 text-left text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Tanggal Dibuat</th>
                <?php if (session('role') === 'admin'): ?>
                <th class="py-3 px-4 text-left text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">User</th>
                <?php endif; ?>
                <th class="py-3 px-2 w-40 text-center text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white">
            <?php if (!empty($accounts)): ?>
                <?php 
                $no = 1 + ($pager->getCurrentPage('accounts') - 1) * $perPage;
                foreach ($accounts as $akun): ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-2 px-2 md:px-4 w-12 text-xs md:text-sm text-gray-700 font-medium border-b border-r border-gray-200 text-center"><?= $no++ ?></td>
                        <td class="py-2 px-2 md:px-4 text-xs md:text-sm text-gray-800 font-semibold border-b border-r border-gray-200"><?= esc($akun['nama_akun']) ?></td>
                        <td class="py-2 px-2 md:px-4 text-xs md:text-sm text-gray-600 border-b border-r border-gray-200"><?= esc($akun['tipe_akun']) ?></td>
                        <td class="py-2 px-2 md:px-4 text-xs md:text-sm text-green-700 font-bold border-b border-r border-gray-200"><span class="whitespace-nowrap">Rp <?= number_format($akun['saldo_awal'],2,',','.') ?></span></td>
                        <td class="py-2 px-2 md:px-4 text-xs md:text-sm text-gray-600 border-b border-r border-gray-200"><?= esc($akun['catatan']) ?></td>
                        <td class="py-2 px-2 md:px-4 text-xs md:text-sm text-gray-500 border-b border-r border-gray-200"><?= esc($akun['created_at']) ?></td>
                        <?php if (session('role') === 'admin'): ?>
                        <td class="py-2 px-2 md:px-4 text-xs md:text-sm text-gray-700 border-b border-r border-gray-200">
                            <?php if (!empty($akun['username'])): ?>
                                <?= esc($akun['username']) ?>
                            <?php else: ?>
                                <span class="text-gray-400 italic">-</span>
                            <?php endif; ?>
                        </td>
                        <?php endif; ?>
                        <td class="py-2 px-2 md:px-4 w-40 text-center border-b border-r border-gray-200">
                            <div class="flex flex-col md:flex-row items-center md:justify-center gap-1">
                                <a href="#" onclick="toggleDetailAccountModal(true, {
                                    nama_akun: '<?= esc($akun['nama_akun'], 'js') ?>',
                                    tipe_akun: '<?= esc($akun['tipe_akun'], 'js') ?>',
                                    saldo_awal: '<?= $akun['saldo_awal'] ?>',
                                    catatan: '<?= esc($akun['catatan'], 'js') ?>',
                                    created_at: '<?= esc($akun['created_at'], 'js') ?>'
                                })" class="flex items-center justify-center w-full md:inline-flex md:w-auto px-2 py-1 text-xs font-semibold text-white bg-blue-500 rounded hover:bg-blue-600" title="Detail">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M1.5 12s3.5-7 10.5-7 10.5 7 10.5 7-3.5 7-10.5 7S1.5 12 1.5 12z"/>
                                      <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    Detail
                                </a>
                                <a href="#" onclick="toggleEditAccountModal(true, {
                                    id: '<?= $akun['id'] ?>',
                                    nama_akun: '<?= esc($akun['nama_akun'], 'js') ?>',
                                    tipe_akun: '<?= esc($akun['tipe_akun'], 'js') ?>',
                                    saldo_awal: '<?= $akun['saldo_awal'] ?>',
                                    catatan: '<?= esc($akun['catatan'], 'js') ?>'
                                })" class="flex items-center justify-center w-full md:inline-flex md:w-auto px-2 py-1 text-xs font-semibold text-white bg-yellow-500 rounded hover:bg-yellow-600" title="Ubah">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 5.487l1.65 1.65a2.121 2.121 0 010 3l-8.486 8.486a2 2 0 01-.878.513l-3.06.765a.5.5 0 01-.606-.606l.765-3.06a2 2 0 01.513-.878l8.486-8.486a2.121 2.121 0 013 0z"/>
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 7l2 2"/>
                                    </svg>
                                    Ubah
                                </a>
                                <a href="#" onclick="toggleDeleteAccountModal(true, {
                                    id: '<?= $akun['id'] ?>',
                                    nama_akun: '<?= esc($akun['nama_akun'], 'js') ?>'
                                })" class="flex items-center justify-center w-full md:inline-flex md:w-auto px-2 py-1 text-xs font-semibold text-white bg-red-500 rounded hover:bg-red-600" title="Hapus">
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
                    <td colspan="<?= session('role') === 'admin' ? 8 : 7 ?>" class="py-4 px-4 text-center text-gray-400 border-b border-r border-gray-200">Tidak ada data akun.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php if (isset($pager) && isset($total_accounts) && $total_accounts > 10): ?>
<div class="mt-4 flex justify-center">
    <nav class="inline-flex rounded-md shadow-sm" aria-label="Pagination">
        <?= view('Accounts/pagination', ['pager' => $pager]) ?>
    </nav>
</div>
<?php endif; ?>

<?php include(APPPATH.'Views/Accounts/modal_add.php'); ?>
<?php include(APPPATH.'Views/Accounts/modal_edit.php'); ?>
<?php include(APPPATH.'Views/Accounts/modal_detail.php'); ?>


<?php include(APPPATH.'Views/Accounts/modal_delete.php'); ?>

<?= $this->endSection() ?>
