<!-- Modal Hapus Transaksi Pemasukan -->

<div id="modalDeleteIncomeTransaction" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden transition-opacity duration-200">
  <div class="bg-white rounded-xl shadow-2xl w-full max-w-sm p-4 md:p-7 relative border-t-4 border-red-500 animate-fadeIn">
    <button onclick="toggleDeleteIncomeTransactionModal(false)" class="absolute top-2 right-2 md:top-3 md:right-3 text-gray-400 hover:text-gray-700 focus:outline-none">
      <svg class="w-6 h-6 md:w-7 md:h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
    <div class="flex flex-col items-center text-center">
      <span class="inline-flex items-center justify-center w-12 h-12 md:w-16 md:h-16 rounded-full bg-red-100 text-red-600 mb-4">
        <svg class="w-8 h-8 md:w-10 md:h-10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0 1 16.138 21H7.862a2 2 0 0 1-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M8 7V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2" />
        </svg>
      </span>
      <h2 class="text-lg md:text-xl font-bold text-red-600 mb-2">Hapus Transaksi?</h2>
      <p class="text-sm md:text-base text-gray-700 mb-4">Anda yakin ingin menghapus transaksi <span id="delete_income_deskripsi" class="font-semibold text-main"></span>? Tindakan ini tidak dapat dibatalkan.</p>
      <form id="formDeleteIncomeTransaction" method="post" action="">
        <input type="hidden" name="_method" value="DELETE">
        <?= csrf_field() ?>
        <div class="flex gap-3 justify-center mt-2 w-full">
          <button type="button" onclick="toggleDeleteIncomeTransactionModal(false)" class="px-4 py-3 flex-1 md:w-auto rounded-lg bg-gray-200 text-gray-700 font-semibold hover:bg-gray-300 transition text-base md:text-sm">Batal</button>
          <button type="submit" class="px-4 py-3 flex-1 md:w-auto rounded-lg bg-red-600 text-white font-semibold hover:bg-red-700 transition text-base md:text-sm">Hapus</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function toggleDeleteIncomeTransactionModal(show, data = null) {
  const modal = document.getElementById('modalDeleteIncomeTransaction');
  if (show) {
    if (data) {
      document.getElementById('delete_income_deskripsi').textContent = data.deskripsi;
      const form = document.getElementById('formDeleteIncomeTransaction');
      form.action = `/transactions/income/delete/${data.id}`;
    }
    modal.classList.remove('hidden');
  } else {
    modal.classList.add('hidden');
  }
}
</script>

<style>
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(30px); }
  to { opacity: 1; transform: translateY(0); }
}
.animate-fadeIn { animation: fadeIn 0.3s ease; }
</style>
