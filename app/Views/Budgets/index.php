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
        <button id="btnAddBudget" onclick="openAddModal()" class="inline-flex items-center gap-2 px-4 py-2 bg-main text-white rounded-lg shadow hover:bg-highlight transition h-11">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Tambah
        </button>
    </div>

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
                            <button type="button" onclick="openDetailModal(<?= json_encode($budget) ?>)"
                                class="inline-flex items-center px-2 py-1 text-xs font-semibold text-white bg-blue-500 rounded hover:bg-blue-600"
                                title="Detail">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M1.5 12s3.5-7 10.5-7 10.5 7 10.5 7-3.5 7-10.5 7S1.5 12 1.5 12z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                                Detail
                            </button>
                            <button type="button" onclick="openEditModal(<?= json_encode($budget) ?>)"
                                class="inline-flex items-center px-2 py-1 text-xs font-semibold text-white bg-yellow-500 rounded hover:bg-yellow-600"
                                title="Ubah">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 5.487l1.65 1.65a2.121 2.121 0 010 3l-8.486 8.486a2 2 0 01-.878.513l-3.06.765a.5.5 0 01-.606-.606l.765-3.06a2 2 0 01.513-.878l8.486-8.486a2.121 2.121 0 013 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 7l2 2"/>
                                </svg>
                                Ubah
                            </button>
                            <button type="button" onclick="deleteBudget(<?= $budget['id'] ?>)"
                                class="inline-flex items-center px-2 py-1 text-xs font-semibold text-white bg-red-500 rounded hover:bg-red-600"
                                title="Hapus">
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

<!-- Modal Detail Anggaran -->
<div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900">Detail Anggaran</h3>
            <div class="mt-4 space-y-4">
                <div>
                    <label class="text-sm font-semibold text-gray-600">Kategori:</label>
                    <p id="detailKategori" class="mt-1 text-gray-900"></p>
                    <p id="detailTipe" class="text-sm"></p>
                </div>
                <div>
                    <label class="text-sm font-semibold text-gray-600">Periode:</label>
                    <p id="detailPeriode" class="mt-1 text-gray-900"></p>
                </div>
                <div>
                    <label class="text-sm font-semibold text-gray-600">Jumlah Anggaran:</label>
                    <p id="detailJumlah" class="mt-1 text-gray-900"></p>
                </div>
                <div>
                    <label class="text-sm font-semibold text-gray-600">Penggunaan:</label>
                    <p id="detailPenggunaan" class="mt-1 text-gray-900"></p>
                </div>
                <div>
                    <label class="text-sm font-semibold text-gray-600">Status:</label>
                    <div id="detailStatus" class="mt-2"></div>
                </div>
            </div>
            <div class="flex justify-end mt-6">
                <button type="button" onclick="closeDetailModal()" 
                        class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah/Edit Anggaran -->
<div id="budgetModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Tambah Anggaran</h3>
            <form id="budgetForm" method="POST" action="/budgets/add" class="mt-4">
                <input type="hidden" name="id" id="budgetId">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="category_id">
                        Kategori
                    </label>
                    <select name="category_id" id="category_id" required class="form-select mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">Pilih Kategori</option>
                        <?php foreach ($categories as $category) : ?>
                            <option value="<?= $category['id'] ?>"><?= esc($category['nama_kategori']) ?> (<?= ucfirst($category['tipe']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="jumlah_anggaran">
                        Jumlah Anggaran
                    </label>
                    <input type="number" name="jumlah_anggaran" id="jumlah_anggaran" required min="0"
                           class="form-input mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="periode">
                        Periode (YYYY-MM)
                    </label>
                    <input type="month" name="periode" id="periode" required
                           class="form-input mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div class="flex justify-end mt-6">
                    <button type="button" onclick="closeModal()"
                            class="bg-gray-500 text-white px-4 py-2 rounded-md mr-2 hover:bg-gray-600">
                        Batal
                    </button>
                    <button type="submit"
                            class="bg-main text-white px-4 py-2 rounded-md hover:bg-opacity-90">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Tambah Anggaran';
    document.getElementById('budgetForm').action = '/budgets/add';
    document.getElementById('budgetForm').reset();
    document.getElementById('budgetId').value = '';
    document.getElementById('budgetModal').classList.remove('hidden');
}

function openEditModal(budget) {
    document.getElementById('modalTitle').textContent = 'Edit Anggaran';
    document.getElementById('budgetForm').action = '/budgets/edit';
    document.getElementById('budgetId').value = budget.id;
    document.getElementById('category_id').value = budget.category_id;
    document.getElementById('jumlah_anggaran').value = budget.jumlah_anggaran;
    document.getElementById('periode').value = budget.periode;
    document.getElementById('budgetModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('budgetModal').classList.add('hidden');
    document.getElementById('budgetForm').reset();
}

function deleteBudget(id) {
    if (confirm('Apakah Anda yakin ingin menghapus anggaran ini?')) {
        fetch(`/budgets/delete/${id}`, {
            method: 'DELETE',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus anggaran');
        });
    }
}

function openDetailModal(budget) {
    const detailModal = document.getElementById('detailModal');
    const usage = budget.current_usage || 0;
    const percentage = budget.jumlah_anggaran > 0 ? (usage / budget.jumlah_anggaran) * 100 : 0;
    
    document.getElementById('detailKategori').textContent = budget.nama_kategori || 'Kategori Tidak Ditemukan';
    document.getElementById('detailTipe').textContent = (budget.tipe || '-').charAt(0).toUpperCase() + (budget.tipe || '-').slice(1);
    document.getElementById('detailTipe').className = `text-sm font-semibold ${budget.tipe === 'income' ? 'text-green-600' : 'text-red-600'}`;
    document.getElementById('detailPeriode').textContent = new Date(budget.periode + '-01').toLocaleString('id-ID', { month: 'long', year: 'numeric' });
    document.getElementById('detailJumlah').textContent = 'Rp ' + number_format(budget.jumlah_anggaran);
    document.getElementById('detailPenggunaan').textContent = 'Rp ' + number_format(usage);
    
    const statusBar = `
        <div class="relative pt-1">
            <div class="overflow-hidden h-2 text-xs flex rounded bg-gray-200">
                <div style="width: ${Math.min(percentage, 100)}%" 
                     class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center ${percentage > 100 ? 'bg-red-500' : (percentage > 80 ? 'bg-yellow-500' : 'bg-green-500')}">
                </div>
            </div>
            <div class="text-xs mt-1 ${percentage > 100 ? 'text-red-500' : (percentage > 80 ? 'text-yellow-500' : 'text-green-500')}">
                ${percentage.toFixed(1)}%
            </div>
        </div>
    `;
    document.getElementById('detailStatus').innerHTML = statusBar;
    
    detailModal.classList.remove('hidden');
}

function closeDetailModal() {
    document.getElementById('detailModal').classList.add('hidden');
}

function number_format(number) {
    return new Intl.NumberFormat('id-ID').format(number);
}

// Close modals when clicking outside
window.onclick = function(event) {
    const budgetModal = document.getElementById('budgetModal');
    const detailModal = document.getElementById('detailModal');
    
    if (event.target == budgetModal) {
        closeModal();
    } else if (event.target == detailModal) {
        closeDetailModal();
    }
}
</script>
<?= $this->endSection() ?>
