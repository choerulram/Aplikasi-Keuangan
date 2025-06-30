<!-- Modal Ubah Akun -->
<div id="modalEditAccount" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
  <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
    <button onclick="toggleEditAccountModal(false)" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
    <h2 class="text-xl font-bold mb-4 text-main">Ubah Akun</h2>
    <form id="formEditAccount" method="post" action="/accounts/edit">
      <?= csrf_field() ?>
      <input type="hidden" name="id" id="edit_id">
      <div class="mb-3">
        <label class="block text-sm font-semibold mb-1">Nama Akun</label>
        <input type="text" name="nama_akun" id="edit_nama_akun" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-main" required>
      </div>
      <div class="mb-3">
        <label class="block text-sm font-semibold mb-1">Tipe Akun</label>
        <select name="tipe_akun" id="edit_tipe_akun" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-main" required>
          <option value="cash">Kas</option>
          <option value="bank">Bank</option>
          <option value="ewallet">E-Wallet</option>
        </select>
      </div>
      <div class="mb-3">
        <label class="block text-sm font-semibold mb-1">Saldo Awal</label>
        <input type="number" name="saldo_awal" id="edit_saldo_awal" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-main" min="0" step="0.01" required>
      </div>
      <div class="mb-3">
        <label class="block text-sm font-semibold mb-1">Catatan</label>
        <textarea name="catatan" id="edit_catatan" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-main"></textarea>
      </div>
      <div class="flex justify-end mt-4">
        <button type="button" onclick="toggleEditAccountModal(false)" class="px-4 py-2 mr-2 rounded bg-gray-200 text-gray-700 hover:bg-gray-300">Batal</button>
        <button type="submit" class="px-4 py-2 rounded bg-main text-white hover:bg-highlight">Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>

<script>
function toggleEditAccountModal(show, data = null) {
  const modal = document.getElementById('modalEditAccount');
  if (show) {
    if (data) {
      document.getElementById('edit_id').value = data.id;
      document.getElementById('edit_nama_akun').value = data.nama_akun;
      document.getElementById('edit_tipe_akun').value = data.tipe_akun;
      document.getElementById('edit_saldo_awal').value = parseFloat(data.saldo_awal);
      document.getElementById('edit_catatan').value = data.catatan;
    }
    modal.classList.remove('hidden');
  } else {
    modal.classList.add('hidden');
  }
}
</script>
