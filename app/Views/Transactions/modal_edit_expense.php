<!-- Modal Ubah Transaksi Pengeluaran -->

<div id="modalEditExpenseTransaction" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden transition-opacity duration-200">
  <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-4 md:p-6 relative border-t-4 border-yellow-500 animate-fadeIn">
    <button onclick="toggleEditExpenseTransactionModal(false)" class="absolute top-2 right-2 md:top-3 md:right-3 text-gray-400 hover:text-gray-700 focus:outline-none">
      <svg class="w-6 h-6 md:w-7 md:h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
    <div class="flex flex-col items-center text-center">
      <span class="inline-flex items-center justify-center w-12 h-12 md:w-16 md:h-16 rounded-full bg-yellow-100 text-yellow-600 mb-4">
        <svg class="w-8 h-8 md:w-10 md:h-10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 5.487l1.65 1.65a2.121 2.121 0 010 3l-8.486 8.486a2 2 0 01-.878.513l-3.06.765a.5.5 0 01-.606-.606l.765-3.06a2 2 0 01.513-.878l8.486-8.486a2.121 2.121 0 013 0z"/>
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 7l2 2"/>
        </svg>
      </span>
      <h2 class="text-lg md:text-xl font-bold mb-4 text-yellow-600">Ubah Transaksi Pengeluaran</h2>
    </div>
    <form id="formEditExpenseTransaction" method="POST" action="/transactions/expense/edit">
      <?= csrf_field() ?>
      <input type="hidden" name="id" id="edit_expense_id">
      <div class="mb-3">
        <label class="block text-xs md:text-sm font-semibold mb-1">Deskripsi</label>
        <input type="text" name="deskripsi" id="edit_expense_deskripsi" class="w-full border rounded px-4 py-3 md:px-3 md:py-2 text-base md:text-sm focus:outline-none focus:ring focus:border-yellow-500" required>
      </div>
      <div class="mb-3">
        <label class="block text-xs md:text-sm font-semibold mb-1">Jumlah</label>
        <input type="number" name="jumlah" id="edit_expense_jumlah" min="0" step="0.01" class="w-full border rounded px-4 py-3 md:px-3 md:py-2 text-base md:text-sm focus:outline-none focus:ring focus:border-yellow-500" required>
      </div>
      <div class="mb-3">
        <label class="block text-xs md:text-sm font-semibold mb-1">Akun</label>
        <select name="account_id" id="edit_expense_account_id" class="w-full border rounded px-4 py-3 md:px-3 md:py-2 text-base md:text-sm focus:outline-none focus:ring focus:border-yellow-500" required>
          <option value="">Pilih Akun</option>
          <?php foreach (($accounts ?? []) as $acc): ?>
            <option value="<?= $acc['id'] ?>"><?= esc($acc['nama_akun']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="mb-3">
        <label class="block text-xs md:text-sm font-semibold mb-1">Kategori</label>
        <select name="category_id" id="edit_expense_category_id" class="w-full border rounded px-4 py-3 md:px-3 md:py-2 text-base md:text-sm focus:outline-none focus:ring focus:border-yellow-500" required>
          <option value="">Pilih Kategori</option>
          <?php foreach (($categories ?? []) as $cat): ?>
            <option value="<?= $cat['id'] ?>"><?= esc($cat['nama_kategori']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="mb-3">
        <label class="block text-xs md:text-sm font-semibold mb-1">Tanggal</label>
        <input type="date" name="tanggal" id="edit_expense_tanggal" class="w-full border rounded px-4 py-3 md:px-3 md:py-2 text-base md:text-sm focus:outline-none focus:ring focus:border-yellow-500" required>
      </div>
      <input type="hidden" name="tipe" value="expense">
      <div class="flex flex-col md:flex-row md:justify-end mt-4 gap-2">
        <button type="button" onclick="toggleEditExpenseTransactionModal(false)" class="px-4 py-3 w-full md:w-auto rounded bg-gray-200 text-gray-700 hover:bg-gray-300 text-base md:text-sm">Batal</button>
        <button type="submit" class="px-4 py-3 w-full md:w-auto rounded bg-yellow-500 text-white font-semibold hover:bg-yellow-600 transition text-base md:text-sm">Simpan Perubahan</button>
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
function toggleEditExpenseTransactionModal(show, data = null) {
  const modal = document.getElementById('modalEditExpenseTransaction');
  if (show) {
    if (data) {
      document.getElementById('edit_expense_id').value = data.id;
      document.getElementById('edit_expense_deskripsi').value = data.deskripsi;
      let jumlahValue = data.jumlah;
      if (typeof jumlahValue === 'string') {
        jumlahValue = jumlahValue.replace(/\.00$/, '');
      } else if (typeof jumlahValue === 'number') {
        if (jumlahValue % 1 === 0) {
          jumlahValue = jumlahValue.toString();
        }
      }
      document.getElementById('edit_expense_jumlah').value = jumlahValue;
      document.getElementById('edit_expense_account_id').value = data.account_id;
      document.getElementById('edit_expense_category_id').value = data.category_id;
      document.getElementById('edit_expense_tanggal').value = data.tanggal;
    }
    modal.classList.remove('hidden');
  } else {
    modal.classList.add('hidden');
  }
}
</script>
