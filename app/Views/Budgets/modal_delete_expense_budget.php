<?php
// Modal Hapus Anggaran Pengeluaran
?>
<div id="modalDeleteExpenseBudget" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden transition-opacity duration-200">
  <div class="bg-white rounded-xl shadow-2xl w-full max-w-sm p-7 relative border-t-4 border-red-500 animate-fadeIn">
    <button onclick="toggleDeleteExpenseBudgetModal(false)" class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 focus:outline-none">
      <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
    <div class="flex flex-col items-center text-center">
      <span class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-100 text-red-600 mb-4">
        <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0 1 16.138 21H7.862a2 2 0 0 1-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M8 7V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2" />
        </svg>
      </span>
      <h2 class="text-xl font-bold text-red-600 mb-2">Hapus Batas Pengeluaran?</h2>
      <p class="text-gray-700 mb-4">Anda yakin ingin menghapus batas pengeluaran untuk kategori <span id="delete_expense_kategori" class="font-semibold text-main"></span>? Tindakan ini tidak dapat dibatalkan.</p>
      <form id="formDeleteExpenseBudget" method="post" action="">
        <input type="hidden" name="_method" value="DELETE">
        <?= csrf_field() ?>
        <div class="flex gap-3 justify-center mt-2">
          <button type="button" onclick="toggleDeleteExpenseBudgetModal(false)" class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 font-semibold hover:bg-gray-300 transition">Batal</button>
          <button type="submit" class="px-4 py-2 rounded-lg bg-red-600 text-white font-semibold hover:bg-red-700 transition">Hapus</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function toggleDeleteExpenseBudgetModal(show, data = null) {
  const modal = document.getElementById('modalDeleteExpenseBudget');
  if (show) {
    if (data) {
      document.getElementById('delete_expense_kategori').textContent = data.nama_kategori;
      const form = document.getElementById('formDeleteExpenseBudget');
      form.action = `/budgets/delete/${data.id}`;
      
      // Setup form submission
      form.onsubmit = async (e) => {
        e.preventDefault();
        try {
          const response = await fetch(form.action, {
            method: 'DELETE',
            headers: {
              'X-Requested-With': 'XMLHttpRequest'
            }
          });
          const result = await response.json();
          
          if (result.status) {
            // Jika berhasil
            if (result.redirect) {
              window.location.href = result.redirect;
            } else {
              window.location.reload();
            }
          } else {
            // Jika gagal, tampilkan pesan error
            alert(result.message);
          }
        } catch (error) {
          console.error('Error:', error);
          alert('Terjadi kesalahan saat menghapus data.');
        }
      };
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
