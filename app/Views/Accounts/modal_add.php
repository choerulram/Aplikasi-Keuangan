<!-- Modal Tambah Akun -->

<div id="modalAddAccount" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden transition-opacity duration-200">
  <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative border-t-4 border-main animate-fadeIn">
    <button onclick="toggleAddAccountModal(false)" class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 focus:outline-none">
      <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
    <div class="flex flex-col items-center text-center">
      <span class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-main/10 text-main mb-4">
        <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
      </span>
      <h2 class="text-xl font-bold mb-4 text-main">Tambah Akun</h2>
    </div>
    <form id="formAddAccount" method="POST" action="/accounts/add">
      <?= csrf_field() ?>
      <div class="mb-3">
        <label class="block text-sm font-semibold mb-1">Nama Akun</label>
        <input type="text" name="nama_akun" placeholder="Contoh: Kas Utama, Rekening BCA, OVO" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-main" required>
      </div>
      <div class="mb-3">
        <label class="block text-sm font-semibold mb-1">Tipe Akun</label>
        <select name="tipe_akun" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-main" required>
          <option value="">Pilih tipe akun</option>
          <option value="cash">Kas</option>
          <option value="bank">Bank</option>
          <option value="ewallet">E-Wallet</option>
        </select>
      </div>
      <div class="mb-3">
        <label class="block text-sm font-semibold mb-1">Saldo Awal</label>
        <input type="number" name="saldo_awal" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-main" min="0" value="0" required>
      </div>
      <div class="mb-3">
        <label class="block text-sm font-semibold mb-1">Catatan</label>
        <textarea name="catatan" placeholder="Contoh: Saldo awal kas kantor, tabungan pribadi, dsb" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-main"></textarea>
      </div>
      <div class="flex justify-end mt-4">
        <button type="button" onclick="toggleAddAccountModal(false)" class="px-4 py-2 mr-2 rounded bg-gray-200 text-gray-700 hover:bg-gray-300">Batal</button>
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
function toggleAddAccountModal(show) {
  const modal = document.getElementById('modalAddAccount');
  if (show) {
    modal.classList.remove('hidden');
  } else {
    modal.classList.add('hidden');
  }
}
document.addEventListener('DOMContentLoaded', function() {
  const btn = document.getElementById('btnShowAddAccountModal');
  if (btn) btn.onclick = () => toggleAddAccountModal(true);
});
</script>
