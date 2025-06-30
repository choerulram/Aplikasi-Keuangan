<!-- Modal Detail Akun -->
<div id="modalDetailAccount" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden transition-opacity duration-200">
  <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-7 relative border-t-4 border-main animate-fadeIn">
    <button onclick="toggleDetailAccountModal(false)" class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 focus:outline-none">
      <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
    <h2 class="text-2xl font-extrabold mb-6 text-main flex items-center gap-3">
      <svg class="w-7 h-7 text-main" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12h.01M12 12h.01M9 12h.01M21 12c0 4.418-4.03 8-9 8s-9-3.582-9-8 4.03-8 9-8 9 3.582 9 8z"/></svg>
      Detail Akun
    </h2>
    <div class="space-y-5">
      <div class="flex items-center gap-3">
        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-main/10 text-main">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        </span>
        <div>
          <span class="block text-xs text-gray-500 font-semibold">Nama Akun</span>
          <span id="detail_nama_akun" class="block text-lg font-bold text-main"></span>
        </div>
      </div>
      <div class="grid grid-cols-2 gap-4">
        <div class="flex items-center gap-3">
          <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h2l1 2h13"/></svg>
          </span>
          <div>
            <span class="block text-xs text-gray-500 font-semibold">Tipe Akun</span>
            <span id="detail_tipe_akun_badge"></span>
          </div>
        </div>
        <div class="flex items-center gap-3">
          <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-100 text-green-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3zm0 0V4m0 8v8"/></svg>
          </span>
          <div>
            <span class="block text-xs text-gray-500 font-semibold">Saldo Awal</span>
            <span id="detail_saldo_awal" class="block text-base text-green-700 font-bold"></span>
          </div>
        </div>
      </div>
      <div class="flex items-center gap-3">
        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-yellow-100 text-yellow-600">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 17l4 4 4-4m0-5V3"/></svg>
        </span>
        <div>
          <span class="block text-xs text-gray-500 font-semibold">Catatan</span>
          <span id="detail_catatan" class="block text-base text-gray-700"></span>
        </div>
      </div>
      <div class="flex items-center gap-3">
        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-200 text-gray-600">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </span>
        <div>
          <span class="block text-xs text-gray-500 font-semibold">Tanggal Dibuat</span>
          <span id="detail_created_at" class="block text-base text-gray-700"></span>
        </div>
      </div>
    </div>
    <div class="flex justify-end mt-8">
      <button type="button" onclick="toggleDetailAccountModal(false)" class="px-5 py-2 rounded-lg bg-main text-white font-semibold hover:bg-highlight transition">Tutup</button>
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
function toggleDetailAccountModal(show, data = null) {
  const modal = document.getElementById('modalDetailAccount');
  if (show) {
    if (data) {
      document.getElementById('detail_nama_akun').textContent = data.nama_akun;
      // Badge tipe akun
      let tipe = data.tipe_akun.charAt(0).toUpperCase() + data.tipe_akun.slice(1);
      let badgeColor = tipe.toLowerCase() === 'debit' ? 'bg-green-200 text-green-800' : 'bg-blue-200 text-blue-800';
      document.getElementById('detail_tipe_akun_badge').innerHTML = `<span class='inline-block px-2 py-0.5 rounded text-xs font-semibold ${badgeColor}'>${tipe}</span>`;
      document.getElementById('detail_saldo_awal').textContent = 'Rp ' + parseFloat(data.saldo_awal).toLocaleString('id-ID', {minimumFractionDigits: 2});
      document.getElementById('detail_catatan').textContent = data.catatan || '-';
      document.getElementById('detail_created_at').innerHTML = `<span class='inline-flex items-center gap-1'><svg class='w-4 h-4 inline text-gray-400' fill='none' stroke='currentColor' stroke-width='2' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' d='M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'/></svg> ${data.created_at}</span>`;
    }
    modal.classList.remove('hidden');
  } else {
    modal.classList.add('hidden');
  }
}
</script>
