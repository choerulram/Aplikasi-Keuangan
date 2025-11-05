<!-- Modal Detail Kategori Pemasukan -->
<div id="modalDetailCategory" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden transition-opacity duration-200">
  <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-4 md:p-6 relative border-t-4 border-blue-500 animate-fadeIn">
    <button onclick="toggleDetailCategoryModal(false)" class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 focus:outline-none">
      <svg class="w-6 h-6 md:w-7 md:h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
    <div class="flex flex-col items-center text-center">
      <span class="inline-flex items-center justify-center w-12 h-12 md:w-16 md:h-16 rounded-full bg-blue-100 text-blue-600 mb-4">
        <svg class="w-8 h-8 md:w-10 md:h-10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M1.5 12s3.5-7 10.5-7 10.5 7 10.5 7-3.5 7-10.5 7S1.5 12 1.5 12z"/>
          <circle cx="12" cy="12" r="3"/>
        </svg>
      </span>
      <h2 class="text-lg md:text-xl font-bold mb-4 text-blue-600">Detail Kategori Pemasukan</h2>
    </div>
    <div class="space-y-5 mt-2">
      <div class="flex flex-col gap-2 bg-white rounded-lg p-3 md:p-4 border border-gray-200">
        <div class="flex flex-col gap-1">
          <span class="text-xs md:text-[13px] text-gray-700 font-semibold uppercase tracking-wide">Nama Kategori</span>
          <span id="detail_nama_kategori" class="text-base md:text-lg font-bold text-gray-900"></span>
        </div>
        <div class="flex flex-col gap-1 mt-2">
          <span class="text-xs md:text-[13px] text-gray-700 font-semibold uppercase tracking-wide">Tipe</span>
          <span id="detail_tipe_badge"></span>
        </div>
        <div class="flex flex-col gap-1 mt-2">
          <span class="text-xs md:text-[13px] text-gray-700 font-semibold uppercase tracking-wide">Tanggal Dibuat</span>
          <span id="detail_created_at" class="text-xs md:text-base text-gray-700"></span>
        </div>
        <?php if (session('role') === 'admin'): ?>
        <div class="flex flex-col gap-1 mt-2" id="detail_user_row" style="display:none;">
          <span class="text-xs md:text-[13px] text-gray-700 font-semibold uppercase tracking-wide">User</span>
          <span id="detail_username" class="text-xs md:text-base text-gray-700"></span>
        </div>
        <?php endif; ?>
      </div>
    </div>
    <div class="flex flex-col md:flex-row md:justify-end mt-6 gap-2">
      <button type="button" onclick="toggleDetailCategoryModal(false)" class="w-full md:w-auto px-4 py-3 rounded-lg bg-blue-500 text-white font-semibold hover:bg-blue-600 transition text-base md:text-sm">Tutup</button>
    </div>
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
function toggleDetailCategoryModal(show, data = null) {
  const modal = document.getElementById('modalDetailCategory');
  if (show) {
    if (data) {
      document.getElementById('detail_nama_kategori').textContent = data.nama_kategori;
      // Badge tipe
      let tipe = data.tipe.charAt(0).toUpperCase() + data.tipe.slice(1);
      let badgeColor = tipe.toLowerCase() === 'income' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800';
      document.getElementById('detail_tipe_badge').innerHTML = `<span class='inline-block px-2 py-0.5 rounded text-xs font-semibold ${badgeColor}'>${tipe}</span>`;
      document.getElementById('detail_created_at').innerHTML = `<span class='inline-flex items-center gap-1'><svg class='w-4 h-4 inline text-gray-400' fill='none' stroke='currentColor' stroke-width='2' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' d='M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'/></svg> ${data.created_at}</span>`;
      // User (hanya admin)
      <?php if (session('role') === 'admin'): ?>
      if (typeof data.username !== 'undefined' && data.username !== null && data.username !== '') {
        document.getElementById('detail_user_row').style.display = '';
        document.getElementById('detail_username').textContent = data.username;
      } else {
        document.getElementById('detail_user_row').style.display = 'none';
        document.getElementById('detail_username').textContent = '';
      }
      <?php endif; ?>
    }
    modal.classList.remove('hidden');
  } else {
    modal.classList.add('hidden');
  }
}
</script>
