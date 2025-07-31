<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Anggaran</h1>
        <button onclick="openAddModal()" class="bg-main text-white px-4 py-2 rounded-md hover:bg-opacity-90 transition">
            Tambah Anggaran
        </button>
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

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Anggaran</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penggunaan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (empty($budgets)) : ?>
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            Belum ada data anggaran
                        </td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($budgets as $budget) : 
                        $usage = isset($budgetModel) ? $budgetModel->getCurrentUsage($budget['category_id'], $budget['periode']) : 0;
                        $percentage = $budget['jumlah_anggaran'] > 0 ? ($usage / $budget['jumlah_anggaran']) * 100 : 0;
                    ?>
                    <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900"><?= esc($budget['nama_kategori'] ?? 'Kategori Tidak Ditemukan') ?></div>
                        <div class="text-sm text-gray-500"><?= ucfirst($budget['tipe'] ?? '-') ?></div>
                        <?php if (isset($isAdmin) && $isAdmin): ?>
                        <div class="text-xs text-blue-500 mt-1">User: <?= esc($budget['username'] ?? 'Unknown') ?></div>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?= date('F Y', strtotime($budget['periode'] . '-01')) ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        Rp <?= number_format($budget['jumlah_anggaran'], 0, ',', '.') ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        Rp <?= number_format($usage, 0, ',', '.') ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
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
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <button onclick="openEditModal(<?= json_encode($budget) ?>)" class="text-main hover:text-opacity-80 mr-3">Edit</button>
                        <button onclick="deleteBudget(<?= $budget['id'] ?>)" class="text-red-600 hover:text-red-900">Hapus</button>
                    </td>
                </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
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

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target == document.getElementById('budgetModal')) {
        closeModal();
    }
}
</script>
<?= $this->endSection() ?>
