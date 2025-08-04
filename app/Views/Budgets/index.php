<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-6">
    <div class="mb-2">
        <h1 class="text-3xl font-bold text-main tracking-tight drop-shadow-sm mb-4">Anggaran</h1>
    </div>

    <?php if (session('success')) : ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?= session('success') ?></span>
        </div>
    <?php endif; ?>

    <?php if (session('error')) : ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?= session('error') ?></span>
        </div>
    <?php endif; ?>

    <div class="flex flex-wrap items-end gap-2 mb-6">
        <button id="btnAddBudget" type="button" class="inline-flex items-center gap-2 px-4 py-2 bg-main text-white rounded-lg shadow hover:bg-highlight transition h-11">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Tambah
        </button>
    </div>
    <?= view('Budgets/modal_add_budget', ['categories' => $categories]) ?>
    <?= view('Budgets/modal_edit_budget', ['categories' => $categories]) ?>

    <div class="overflow-x-auto rounded-lg shadow border border-gray-200 bg-white">
        <table class="min-w-full border border-gray-300">
            <thead class="bg-main/90">
                <tr>
                    <th class="py-3 px-2 w-12 text-center text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">No.</th>
                    <th class="py-3 px-4 text-left text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Kategori</th>
                    <th class="py-3 px-4 text-left text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Periode</th>
                    <th class="py-3 px-4 text-left text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Jumlah Anggaran</th>
                    <th class="py-3 px-4 text-left text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Penggunaan</th>
                    <th class="py-3 px-4 text-left text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Status</th>
                    <?php if ($isAdmin): ?>
                    <th class="py-3 px-4 text-left text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">User</th>
                    <?php endif; ?>
                    <th class="py-3 px-2 w-40 text-center text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (empty($budgets)) : ?>
                    <tr>
                        <td colspan="<?= (isset($isAdmin) && $isAdmin) ? '8' : '7' ?>" class="py-4 px-4 text-center text-gray-400 border-b border-r border-gray-200">
                            Belum ada data anggaran
                        </td>
                    </tr>
                <?php else : ?>
                    <?php 
                    $no = 1;
                    foreach ($budgets as $budget) : 
                        $usage = isset($budgetModel) ? $budgetModel->getCurrentUsage($budget['category_id'], $budget['periode']) : 0;
                        $percentage = $budget['jumlah_anggaran'] > 0 ? ($usage / $budget['jumlah_anggaran']) * 100 : 0;
                    ?>
                    <tr class="hover:bg-gray-50 transition">
                    <td class="py-2 px-2 w-12 text-sm text-gray-700 font-medium border-b border-r border-gray-200 text-center"><?= $no++ ?></td>
                    <td class="py-2 px-4 text-sm border-b border-r border-gray-200">
                        <div class="flex flex-col">
                            <div class="font-medium text-gray-900"><?= esc($budget['nama_kategori'] ?? 'Kategori Tidak Ditemukan') ?></div>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="px-2 py-0.5 text-xs rounded-full <?= ($budget['tipe'] ?? '') === 'income' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?> font-medium">
                                    <?= ($budget['tipe'] ?? '') === 'income' ? 'Pemasukan' : 'Pengeluaran' ?>
                                </span>
                            </div>
                        </div>
                    </td>
                    <td class="py-2 px-4 text-sm text-gray-800 border-b border-r border-gray-200">
                        <?= date('F Y', strtotime($budget['periode'] . '-01')) ?>
                    </td>
                    <td class="py-2 px-4 text-sm font-bold border-b border-r border-gray-200">
                        Rp <?= number_format($budget['jumlah_anggaran'], 0, ',', '.') ?>
                    </td>
                    <td class="py-2 px-4 text-sm font-bold border-b border-r border-gray-200">
                        Rp <?= number_format($usage, 0, ',', '.') ?>
                    </td>
                    <td class="py-2 px-4 border-b border-r border-gray-200">
                        <div class="relative pt-1">
                            <div class="overflow-hidden h-2 text-xs flex rounded bg-gray-200">
                                <div style="width: <?= min($percentage, 100) ?>%" 
                                     class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center <?= $percentage > 100 ? 'bg-red-500' : ($percentage > 80 ? 'bg-yellow-500' : 'bg-green-500') ?>">
                                </div>
                            </div>
                            <div class="text-xs mt-1 <?= $percentage > 100 ? 'text-red-500' : ($percentage > 80 ? 'text-yellow-500' : 'text-green-500') ?>">
                                <?= number_format($percentage, 1) ?>%
                            </div>
                        </div>
                    </td>
                    <?php if (isset($isAdmin) && $isAdmin): ?>
                    <td class="py-2 px-4 text-sm text-gray-700 border-b border-r border-gray-200">
                        <?= esc($budget['username'] ?? 'Unknown') ?>
                    </td>
                    <?php endif; ?>
                    <td class="py-2 px-2 w-40 text-center border-b border-r border-gray-200">
                            <div class="flex justify-center gap-1">
                                <button type="button"
                                    class="inline-flex items-center px-2 py-1 text-xs font-semibold text-white bg-blue-500 rounded hover:bg-blue-600"
                                    title="Detail"
                                    onclick='toggleDetailIncomeTransactionModal(true, <?= json_encode($budget, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP) ?>)'>
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M1.5 12s3.5-7 10.5-7 10.5 7 10.5 7-3.5 7-10.5 7S1.5 12 1.5 12z"/>
                                      <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    Detail
                                </button>
                                <button type="button"
                                    class="inline-flex items-center px-2 py-1 text-xs font-semibold text-white bg-yellow-500 rounded hover:bg-yellow-600"
                                    title="Ubah"
                                    onclick='toggleEditBudgetModal(true, <?= json_encode($budget, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP) ?>)'>
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 5.487l1.65 1.65a2.121 2.121 0 010 3l-8.486 8.486a2 2 0 01-.878.513l-3.06.765a.5.5 0 01-.606-.606l.765-3.06a2 2 0 01.513-.878l8.486-8.486a2.121 2.121 0 013 0z"/>
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 7l2 2"/>
                                    </svg>
                                    Ubah
                                </button>
                                <button type="button"
                                    class="inline-flex items-center px-2 py-1 text-xs font-semibold text-white bg-red-500 rounded hover:bg-red-600"
                                    title="Hapus"
                                    onclick='toggleDeleteIncomeTransactionModal(true, <?= json_encode($budget, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP) ?>)'>
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0 1 16.138 21H7.862a2 2 0 0 1-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M8 7V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2" />
                                    </svg>
                                    Hapus
                                </button>
                            </div>
                        </td>
                </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
