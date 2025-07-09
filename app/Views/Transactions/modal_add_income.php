<!-- Modal Tambah Transaksi Pemasukan -->
<div id="modalAddIncomeTransaction" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden transition-opacity duration-200">
  <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative border-t-4 border-main animate-fadeIn">
    <button onclick="toggleAddIncomeTransactionModal(false)" class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 focus:outline-none">
      <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
    <div class="flex flex-col items-center text-center">
      <span class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-main/10 text-main mb-4">
        <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
      </span>
      <h2 class="text-xl font-bold mb-4 text-main">Tambah Transaksi Pemasukan</h2>
    </div>
    <form id="formAddIncomeTransaction" method="POST" action="/transactions/income/add">
      <?= csrf_field() ?>
      <div class="mb-3">
        <label class="block text-sm font-semibold mb-1">Deskripsi</label>
        <input type="text" name="deskripsi" placeholder="Contoh: Gaji Bulanan" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-main" required>
      </div>
      <div class="mb-3">
        <label class="block text-sm font-semibold mb-1">Jumlah</label>
        <input type="number" name="jumlah" min="0" step="0.01" placeholder="0" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-main" required>
      </div>
      <div class="mb-3">
        <label class="block text-sm font-semibold mb-1">Akun</label>
        <select name="account_id" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-main" required>
          <option value="">Pilih Akun</option>
          <?php foreach (($accounts ?? []) as $acc): ?>
            <option value="<?= $acc['id'] ?>"><?= esc($acc['nama_akun']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="mb-3">
        <label class="block text-sm font-semibold mb-1">Kategori</label>
        <select name="category_id" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-main" required>
          <option value="">Pilih Kategori</option>
          <?php foreach (($categories ?? []) as $cat): ?>
            <option value="<?= $cat['id'] ?>"><?= esc($cat['nama_kategori']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="mb-3">
        <label class="block text-sm font-semibold mb-1">Tanggal</label>
        <input type="date" name="tanggal" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-main" required>
      </div>
      <input type="hidden" name="tipe" value="income">
      <div class="flex justify-end mt-4">
        <button type="button" onclick="toggleAddIncomeTransactionModal(false)" class="px-4 py-2 mr-2 rounded bg-gray-200 text-gray-700 hover:bg-gray-300">Batal</button>
        <button type="submit" class="px-4 py-2 rounded bg-main text-white font-semibold hover:bg-highlight transition">Simpan</button>
      </div>
    </form>
  </div>
</div>

<style>
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(30px); }
  to { opacity: 1; transform: translateY(0); }
}
.animate-fadeIn { animation: fadeIn 0.3s ease; }
</style>

<script>
function toggleAddIncomeTransactionModal(show) {
  const modal = document.getElementById('modalAddIncomeTransaction');
  if (show) {
    modal.classList.remove('hidden');
  } else {
    modal.classList.add('hidden');
  }
}
document.addEventListener('DOMContentLoaded', function() {
  const btn = document.getElementById('btnShowAddIncomeTransactionModal');
  if (btn) btn.onclick = () => toggleAddIncomeTransactionModal(true);
});
</script>
