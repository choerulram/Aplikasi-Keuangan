<!-- Modal Edit Income Budget -->
<div id="modalEditIncomeBudget" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden transition-opacity duration-200">
  <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative border-t-4 border-yellow-500 animate-fadeIn">
    <button onclick="toggleEditIncomeBudgetModal(false)" class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 focus:outline-none">
      <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
    <div class="flex flex-col items-center text-center">
      <span class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-yellow-100 text-yellow-600 mb-4">
        <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
        </svg>
      </span>
      <h2 class="text-xl font-bold mb-4 text-yellow-600">Ubah Target Pendapatan</h2>
    </div>
    <form id="formEditIncomeBudget" method="POST" action="/budgets/edit">
      <?= csrf_field() ?>
      <input type="hidden" name="id" id="edit_income_budget_id">
      <div id="alertIncomeEditMessage" class="mb-3 hidden">
        <div class="px-4 py-3 rounded relative" role="alert">
          <span class="block sm:inline message-text"></span>
        </div>
      </div>
      <div class="mb-3">
        <label class="block text-sm font-semibold mb-1">Kategori Pendapatan</label>
        <select name="category_id" id="edit_income_category_id" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-yellow-500" required>
          <option value="">Pilih Kategori Pendapatan</option>
          <?php foreach (($categories ?? []) as $category): ?>
            <?php if ($category['tipe'] === 'income'): ?>
              <option value="<?= $category['id'] ?>"><?= esc($category['nama_kategori']) ?></option>
            <?php endif; ?>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="mb-3">
        <label class="block text-sm font-semibold mb-1">Target Pendapatan</label>
        <input type="number" name="jumlah_anggaran" id="edit_income_jumlah_anggaran" min="0" 
               class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-yellow-500" required>
      </div>
      <div class="mb-3">
        <label class="block text-sm font-semibold mb-1">Periode</label>
        <input type="month" name="periode" id="edit_income_periode" 
               class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-yellow-500" required>
      </div>
      <div class="flex justify-end mt-4">
        <button type="button" onclick="toggleEditIncomeBudgetModal(false)" 
                class="px-4 py-2 mr-2 rounded bg-gray-200 text-gray-700 hover:bg-gray-300">
          Batal
        </button>
        <button type="submit" 
                class="px-4 py-2 rounded bg-yellow-500 text-white font-semibold hover:bg-yellow-600 transition">
          Simpan
        </button>
      </div>
    </form>
  </div>
</div>

<script>
function toggleEditIncomeBudgetModal(show, data = null) {
  const modal = document.getElementById('modalEditIncomeBudget');
  if (show) {
    if (data) {
      document.getElementById('edit_income_budget_id').value = data.id;
      document.getElementById('edit_income_category_id').value = data.category_id;
      document.getElementById('edit_income_periode').value = data.periode;
      document.getElementById('edit_income_jumlah_anggaran').value = data.jumlah_anggaran;
    }
    modal.classList.remove('hidden');
    hideIncomeEditAlert();
  } else {
    modal.classList.add('hidden');
  }
}

function showIncomeEditAlert(message, isSuccess) {
  const alertDiv = document.getElementById('alertIncomeEditMessage');
  const messageSpan = alertDiv.querySelector('.message-text');
  
  alertDiv.classList.remove('hidden');
  alertDiv.querySelector('div').className = `px-4 py-3 rounded relative ${isSuccess ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'}`;
  messageSpan.textContent = message;
}

function hideIncomeEditAlert() {
  const alertDiv = document.getElementById('alertIncomeEditMessage');
  alertDiv.classList.add('hidden');
}

document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('formEditIncomeBudget');
  form.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    fetch('/budgets/edit', {
      method: 'POST',
      body: formData,
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    .then(response => response.json())
    .then(data => {
      showIncomeEditAlert(data.message, data.status);
      if (data.status) {
        setTimeout(() => {
          window.location.reload();
        }, 1500);
      }
    })
    .catch(error => {
      showIncomeEditAlert('Terjadi kesalahan saat memproses permintaan.', false);
    });
  });
});
</script>
