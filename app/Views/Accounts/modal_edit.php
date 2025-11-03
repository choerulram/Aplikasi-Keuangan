
<!-- Modal Ubah Akun -->
<div id="modalEditAccount" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden transition-opacity duration-200">
  <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-4 md:p-6 relative border-t-4 border-yellow-500 animate-fadeIn">
    <button onclick="toggleEditAccountModal(false)" class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 focus:outline-none">
      <svg class="w-6 h-6 md:w-7 md:h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
    <div class="flex flex-col items-center text-center">
      <span class="inline-flex items-center justify-center w-12 h-12 md:w-16 md:h-16 rounded-full bg-yellow-100 text-yellow-600 mb-4">
        <svg class="w-8 h-8 md:w-10 md:h-10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 5.487l1.65 1.65a2.121 2.121 0 010 3l-8.486 8.486a2 2 0 01-.878.513l-3.06.765a.5.5 0 01-.606-.606l.765-3.06a2 2 0 01.513-.878l8.486-8.486a2.121 2.121 0 013 0z"/>
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 7l2 2"/>
        </svg>
      </span>
      <h2 class="text-lg md:text-xl font-bold mb-4 text-yellow-600">Ubah Akun</h2>
    </div>
    <form id="formEditAccount" method="post" action="/accounts/edit">
      <?= csrf_field() ?>
      <input type="hidden" name="id" id="edit_id">
      <div class="mb-3">
        <label class="block text-sm font-semibold mb-1">Nama Akun</label>
        <input type="text" name="nama_akun" id="edit_nama_akun" class="w-full border rounded px-4 py-3 md:px-3 md:py-2 text-base md:text-sm focus:outline-none focus:ring focus:border-yellow-500" required>
      </div>
      <div class="mb-3">
        <label class="block text-sm font-semibold mb-1">Tipe Akun</label>
        <select name="tipe_akun" id="edit_tipe_akun" class="w-full border rounded px-4 py-3 md:px-3 md:py-2 text-base md:text-sm focus:outline-none focus:ring focus:border-yellow-500" required>
          <option value="cash">Kas</option>
          <option value="bank">Bank</option>
          <option value="ewallet">E-Wallet</option>
        </select>
      </div>
      <div class="mb-3">
        <label class="block text-sm font-semibold mb-1">Saldo Awal</label>
        <input type="number" name="saldo_awal" id="edit_saldo_awal" class="w-full border rounded px-4 py-3 md:px-3 md:py-2 text-base md:text-sm focus:outline-none focus:ring focus:border-yellow-500" min="0" step="0.01" required>
      </div>
      <div class="mb-3">
        <label class="block text-sm font-semibold mb-1">Catatan</label>
        <textarea name="catatan" id="edit_catatan" class="w-full border rounded px-4 py-3 md:px-3 md:py-2 text-base md:text-sm focus:outline-none focus:ring focus:border-yellow-500"></textarea>
      </div>
      <div class="flex flex-col md:flex-row md:justify-end mt-4 gap-2">
        <button type="button" onclick="toggleEditAccountModal(false)" class="px-4 py-3 w-full md:w-auto rounded bg-gray-200 text-gray-700 hover:bg-gray-300 text-base md:text-sm">Batal</button>
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
