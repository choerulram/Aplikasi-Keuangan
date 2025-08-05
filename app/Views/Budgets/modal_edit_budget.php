<!-- Modal Edit Budget -->
<div id="modalEditBudget" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden transition-opacity duration-200">
  <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative border-t-4 border-yellow-500 animate-fadeIn">
    <button onclick="toggleEditBudgetModal(false)" class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 focus:outline-none">
      <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
    <div class="flex flex-col items-center text-center">
      <span class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-yellow-100 text-yellow-600 mb-4">
        <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 5.487l1.65 1.65a2.121 2.121 0 010 3l-8.486 8.486a2 2 0 01-.878.513l-3.06.765a.5.5 0 01-.606-.606l.765-3.06a2 2 0 01.513-.878l8.486-8.486a2.121 2.121 0 013 0z"/>
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 7l2 2"/>
        </svg>
      </span>
      <h2 class="text-xl font-bold mb-4 text-yellow-600">Ubah Anggaran</h2>
    </div>
    <!-- Alert Messages -->
    <div id="alertMessage" class="mb-4 hidden">
      <div class="p-4 rounded-lg text-sm"></div>
    </div>
    <form id="formEditBudget" method="POST" action="/budgets/edit">
      <?= csrf_field() ?>
      <input type="hidden" name="id" id="edit_budget_id">
      <div id="edit_budget_error" class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4 hidden">
        <div class="flex">
          <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
          </div>
          <div class="ml-3">
            <p class="text-sm text-yellow-700" id="edit_budget_error_text"></p>
          </div>
        </div>
      </div>
      <div class="mb-3">
        <label class="block text-sm font-semibold mb-1">Kategori</label>
        <select name="category_id" id="edit_budget_category_id" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-yellow-500" required>
          <option value="">Pilih Kategori</option>
          <?php foreach (($categories ?? []) as $cat): ?>
            <option value="<?= $cat['id'] ?>"><?= esc($cat['nama_kategori']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="mb-3">
        <label class="block text-sm font-semibold mb-1">Periode</label>
        <input type="month" name="periode" id="edit_budget_periode" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-yellow-500" required>
      </div>
      <div class="mb-3">
        <label class="block text-sm font-semibold mb-1">Jumlah Anggaran</label>
        <input type="number" name="jumlah_anggaran" id="edit_budget_jumlah_anggaran" min="0" step="0.01" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-yellow-500" required>
      </div>
      <div class="flex justify-end mt-4">
        <button type="button" onclick="toggleEditBudgetModal(false)" class="px-4 py-2 mr-2 rounded bg-gray-200 text-gray-700 hover:bg-gray-300">Batal</button>
        <button type="submit" class="px-4 py-2 rounded bg-yellow-500 text-white font-semibold hover:bg-yellow-600 transition">Simpan Perubahan</button>
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
function toggleEditBudgetModal(show, data = null) {
  const modal = document.getElementById('modalEditBudget');
  const alertDiv = document.getElementById('alertMessage');
  
  if (show) {
    if (data) {
      document.getElementById('edit_budget_id').value = data.id;
      document.getElementById('edit_budget_category_id').value = data.category_id;
      document.getElementById('edit_budget_periode').value = data.periode;
      document.getElementById('edit_budget_jumlah_anggaran').value = data.jumlah_anggaran;
    }
    modal.classList.remove('hidden');
  } else {
    modal.classList.add('hidden');
    // Clear any existing alert messages
    alertDiv.classList.add('hidden');
    alertDiv.querySelector('div').textContent = '';
  }
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formEditBudget');
    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Dapatkan referensi ke elemen alert
            const alertDiv = document.getElementById('alertMessage');
            const errorDiv = document.getElementById('edit_budget_error');
            const alertContent = alertDiv.querySelector('div');
            
            // Reset pesan error sebelumnya
            errorDiv.classList.add('hidden');
            const errorText = document.getElementById('edit_budget_error_text');
            errorText.textContent = '';
            
            // Tampilkan loading state
            alertDiv.classList.remove('hidden');
            alertContent.className = 'p-4 rounded-lg text-sm bg-blue-100 text-blue-700 border border-blue-400';
            alertContent.textContent = 'Sedang memproses...';
            
            try {
                const formData = new FormData(this);
                
                const response = await fetch('/budgets/edit', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });
                
                const result = await response.json();
                
                // Tampilkan pesan error jika ada
                if (!result.status) {
                    errorDiv.classList.remove('hidden');
                    errorText.textContent = result.message;
                }
                
                // Tampilkan pesan dari server
                if (result.status === true) {
                    // Pesan sukses
                    alertContent.className = 'p-4 rounded-lg text-sm bg-green-100 text-green-700 border border-green-400';
                    alertContent.textContent = result.message;
                    // Tunggu sebentar sebelum reload
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    // Pesan error
                    alertContent.className = 'p-4 rounded-lg text-sm bg-red-100 text-red-700 border border-red-400';
                    alertContent.textContent = result.message || 'Terjadi kesalahan saat mengubah anggaran';
                }
            } catch (error) {
                console.error('Error:', error);
                // Tampilkan pesan error
                alertContent.className = 'p-4 rounded-lg text-sm bg-red-100 text-red-700 border border-red-400';
                alertContent.textContent = 'Terjadi kesalahan saat mengirim data';
            }
        });
    }
});
</script>
