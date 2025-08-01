<?php
/* Modal Tambah Anggaran */
?>
<div id="modalAddBudget" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden transition-opacity duration-200">
  <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative border-t-4 border-main animate-fadeIn">
    <button onclick="toggleAddBudgetModal(false)" class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 focus:outline-none">
      <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
    <div class="flex flex-col items-center text-center">
      <span class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-main/10 text-main mb-4">
        <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
      </span>
      <h2 class="text-xl font-bold mb-4 text-main">Tambah Anggaran</h2>
    </div>
    <form id="formAddBudget" method="POST" action="/budgets/add">
      <?= csrf_field() ?>
      <div id="alertMessage" class="mb-3 hidden">
        <div class="px-4 py-3 rounded relative" role="alert">
          <span class="block sm:inline message-text"></span>
        </div>
      </div>
      <div class="mb-3">
        <label class="block text-sm font-semibold mb-1">Kategori</label>
        <select name="category_id" id="category_id" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-main" 
                dropdownAlign="below" style="transform: none !important;" required>
          <option value="">Pilih Kategori</option>
          <?php foreach (($categories ?? []) as $category): ?>
            <option value="<?= $category['id'] ?>"><?= esc($category['nama_kategori']) ?> (<?= ucfirst($category['tipe']) ?>)</option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="mb-3">
        <label class="block text-sm font-semibold mb-1">Jumlah Anggaran</label>
        <input type="number" name="jumlah_anggaran" id="jumlah_anggaran" min="0" 
               placeholder="0" 
               class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-main" required>
      </div>
      <div class="mb-3">
        <label class="block text-sm font-semibold mb-1">Periode</label>
        <input type="month" name="periode" id="periode"
               class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-main" required>
      </div>
      <div class="flex justify-end mt-4">
        <button type="button" onclick="toggleAddBudgetModal(false)" 
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

<style>
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(30px); }
  to { opacity: 1; transform: translateY(0); }
}
.animate-fadeIn { animation: fadeIn 0.3s ease; }

/* Memastikan dropdown selalu muncul di bawah */
select {
  position: relative;
}

select option {
  position: absolute;
  max-height: 200px;
  overflow-y: auto;
}

/* Override browser default dropdown position */
select:not([size]):not([multiple]) {
  background-position: right 0.5rem center;
}
</style>

<script>
function toggleAddBudgetModal(show) {
  const modal = document.getElementById('modalAddBudget');
  if (show) {
    modal.classList.remove('hidden');
    // Reset form dan alert saat modal dibuka
    document.getElementById('formAddBudget').reset();
    hideAlert();
  } else {
    modal.classList.add('hidden');
  }
}

function showAlert(message, isSuccess) {
  const alertDiv = document.getElementById('alertMessage');
  const messageSpan = alertDiv.querySelector('.message-text');
  
  alertDiv.classList.remove('hidden');
  alertDiv.querySelector('div').className = `px-4 py-3 rounded relative ${isSuccess ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'}`;
  messageSpan.textContent = message;
}

function hideAlert() {
  const alertDiv = document.getElementById('alertMessage');
  alertDiv.classList.add('hidden');
}

document.addEventListener('DOMContentLoaded', function() {
  const btn = document.getElementById('btnAddBudget');
  if (btn) btn.onclick = () => toggleAddBudgetModal(true);

  // Handle form submission
  const form = document.getElementById('formAddBudget');
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
      showAlert(data.message, data.status);
      
      if (data.status) {
        // Jika berhasil, reload halaman setelah 1.5 detik
        setTimeout(() => {
          window.location.reload();
        }, 1500);
      }
    })
    .catch(error => {
      showAlert('Terjadi kesalahan saat memproses permintaan.', false);
    });
  });
});
</script>
