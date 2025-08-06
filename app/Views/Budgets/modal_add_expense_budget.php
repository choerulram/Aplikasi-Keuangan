<?php
/* Modal Tambah Anggaran Pengeluaran */
?>
<div id="modalAddExpenseBudget" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden transition-opacity duration-200">
  <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative border-t-4 border-main animate-fadeIn">
    <button onclick="toggleAddExpenseBudgetModal(false)" class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 focus:outline-none">
      <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
    <div class="flex flex-col items-center text-center">
      <span class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-main/10 text-main mb-4">
        <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
      </span>
      <h2 class="text-xl font-bold mb-4 text-main">Tambah Batas Pengeluaran</h2>
    </div>
    <form id="formAddExpenseBudget" method="POST" action="/budgets/add">
      <?= csrf_field() ?>
      <div id="alertExpenseBudgetMessage" class="mb-3 hidden">
        <div class="px-4 py-3 rounded relative" role="alert">
          <span class="block sm:inline message-text"></span>
        </div>
      </div>
      <div class="mb-3">
        <label class="block text-sm font-semibold mb-1">Kategori Pengeluaran</label>
        <select name="category_id" id="expense_category_id" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-main" required>
          <option value="">Pilih Kategori Pengeluaran</option>
          <?php foreach (($categories ?? []) as $category): ?>
            <?php if ($category['tipe'] === 'expense'): ?>
              <option value="<?= $category['id'] ?>"><?= esc($category['nama_kategori']) ?></option>
            <?php endif; ?>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="mb-3">
        <label class="block text-sm font-semibold mb-1">Batas Pengeluaran</label>
        <input type="number" name="jumlah_anggaran" id="expense_jumlah_anggaran" min="0" 
               placeholder="0" 
               class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-main" required>
      </div>
      <div class="mb-3">
        <label class="block text-sm font-semibold mb-1">Periode</label>
        <input type="month" name="periode" id="expense_periode" 
               class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-main" required>
      </div>
      <div class="flex justify-end mt-4">
        <button type="button" onclick="toggleAddExpenseBudgetModal(false)" 
                class="px-4 py-2 mr-2 rounded bg-gray-200 text-gray-700 hover:bg-gray-300">
          Batal
        </button>
        <button type="submit" 
                class="px-4 py-2 rounded bg-main text-white font-semibold hover:bg-highlight transition">
          Simpan
        </button>
      </div>
    </form>
  </div>
</div>

<script>
function toggleAddExpenseBudgetModal(show) {
  const modal = document.getElementById('modalAddExpenseBudget');
  if (show) {
    modal.classList.remove('hidden');
    document.getElementById('formAddExpenseBudget').reset();
    hideExpenseBudgetAlert();
  } else {
    modal.classList.add('hidden');
  }
}

function showExpenseBudgetAlert(message, isSuccess) {
  const alertDiv = document.getElementById('alertExpenseBudgetMessage');
  const messageSpan = alertDiv.querySelector('.message-text');
  
  alertDiv.classList.remove('hidden');
  alertDiv.querySelector('div').className = `px-4 py-3 rounded relative ${isSuccess ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'}`;
  messageSpan.textContent = message;
}

function hideExpenseBudgetAlert() {
  const alertDiv = document.getElementById('alertExpenseBudgetMessage');
  alertDiv.classList.add('hidden');
}

document.addEventListener('DOMContentLoaded', function() {
  const btnAdd = document.getElementById('btnAddBudget');
  if (btnAdd) btnAdd.onclick = () => toggleAddExpenseBudgetModal(true);

  const form = document.getElementById('formAddExpenseBudget');
  form.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    fetch('/budgets/add', {
      method: 'POST',
      body: formData,
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    .then(response => response.json())
    .then(data => {
      showExpenseBudgetAlert(data.message, data.status);
      if (data.status) {
        setTimeout(() => {
          window.location.reload();
        }, 1500);
      }
    })
    .catch(error => {
      showExpenseBudgetAlert('Terjadi kesalahan saat memproses permintaan.', false);
    });
  });
});
</script>
