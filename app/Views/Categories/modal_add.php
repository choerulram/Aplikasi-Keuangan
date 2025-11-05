<!-- Modal Tambah Kategori Pemasukan -->
<div id="modalAddCategory" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden transition-opacity duration-200">
  <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-4 md:p-6 relative border-t-4 border-main animate-fadeIn">
    <button onclick="toggleAddCategoryModal(false)" class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 focus:outline-none">
      <svg class="w-6 h-6 md:w-7 md:h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
    <div class="flex flex-col items-center text-center">
      <span class="inline-flex items-center justify-center w-12 h-12 md:w-16 md:h-16 rounded-full bg-main/10 text-main mb-4">
        <svg class="w-8 h-8 md:w-10 md:h-10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
      </span>
      <h2 class="text-lg md:text-xl font-bold mb-4 text-main">Tambah Kategori Pemasukan</h2>
    </div>
    <form id="formAddCategory" method="POST" action="/categories/income/add">
      <?= csrf_field() ?>
      <div class="mb-3">
        <label class="block text-xs md:text-sm font-semibold mb-1">Nama Kategori</label>
        <input type="text" name="nama_kategori" placeholder="Contoh: Gaji, Penjualan, Investasi" class="w-full border rounded px-4 py-3 md:px-3 md:py-2 text-base md:text-sm focus:outline-none focus:ring focus:border-main" required>
      </div>
      <input type="hidden" name="tipe" value="income">
      <div class="flex flex-col md:flex-row md:justify-end mt-4 gap-2">
        <button type="button" onclick="toggleAddCategoryModal(false)" class="px-4 py-3 w-full md:w-auto rounded bg-gray-200 text-gray-700 hover:bg-gray-300 text-base md:text-sm">Batal</button>
        <button type="submit" class="px-4 py-3 w-full md:w-auto rounded bg-main text-white font-semibold hover:bg-highlight transition text-base md:text-sm">Simpan</button>
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
function toggleAddCategoryModal(show) {
  const modal = document.getElementById('modalAddCategory');
  if (show) {
    modal.classList.remove('hidden');
  } else {
    modal.classList.add('hidden');
  }
}
document.addEventListener('DOMContentLoaded', function() {
  const btn = document.getElementById('btnShowAddCategoryModal');
  if (btn) btn.onclick = () => toggleAddCategoryModal(true);
});
</script>
