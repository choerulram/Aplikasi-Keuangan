<!-- Modal Detail Expense Budget -->
<div id="modalDetailExpenseBudget" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden transition-opacity duration-200">
  <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative border-t-4 border-blue-500 animate-fadeIn">
    <button onclick="toggleDetailExpenseBudgetModal(false)" class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 focus:outline-none">
      <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
    <div class="flex flex-col items-center text-center">
      <span class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 text-blue-600 mb-4">
        <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
      </span>
      <h2 class="text-xl font-bold text-blue-600">Detail Batas Pengeluaran</h2>
    </div>

    <div class="space-y-5 mt-4">
      <div class="flex flex-col gap-2 bg-white rounded-lg p-4 border border-gray-200">
        <!-- Kategori -->
        <div class="flex flex-col gap-1">
          <span class="text-[13px] text-gray-700 font-semibold uppercase tracking-wide">Kategori</span>
          <div class="flex items-center gap-2">
            <span id="detail_expense_budget_category" class="text-lg font-bold text-gray-900"></span>
            <span id="detail_expense_budget_type" class="text-xs px-2 py-0.5 rounded-full"></span>
          </div>
        </div>

        <!-- Periode -->
        <div class="flex flex-col gap-1 mt-2">
          <span class="text-[13px] text-gray-700 font-semibold uppercase tracking-wide">Periode</span>
          <div class="flex items-center gap-1">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span id="detail_expense_budget_periode" class="text-base font-semibold text-gray-700"></span>
          </div>
        </div>

        <!-- Batas Pengeluaran -->
        <div class="flex flex-col gap-1 mt-2">
          <span class="text-[13px] text-gray-700 font-semibold uppercase tracking-wide">Batas Pengeluaran</span>
          <span id="detail_expense_budget_amount" class="text-lg font-bold text-blue-600"></span>
        </div>

        <!-- Penggunaan -->
        <div class="flex flex-col gap-1 mt-2">
          <span class="text-[13px] text-gray-700 font-semibold uppercase tracking-wide">Penggunaan</span>
          <span id="detail_expense_budget_usage" class="text-lg font-bold text-red-600"></span>
        </div>

        <!-- Progress Bar -->
        <div class="flex flex-col gap-1 mt-2">
          <span class="text-[13px] text-gray-700 font-semibold uppercase tracking-wide">Status Penggunaan</span>
          <div class="flex items-center gap-2">
            <div class="flex-1 bg-gray-200 rounded-full h-2.5 overflow-hidden">
              <div id="detail_expense_budget_progress" class="h-full rounded-full"></div>
            </div>
            <span id="detail_expense_budget_percentage" class="text-sm font-medium"></span>
          </div>
        </div>

        <?php if ($isAdmin): ?>
        <!-- User Info (Admin Only) -->
        <div class="flex flex-col gap-1 mt-2">
          <span class="text-[13px] text-gray-700 font-semibold uppercase tracking-wide">User</span>
          <div class="flex items-center gap-1">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <span id="detail_expense_budget_user" class="text-base font-semibold text-gray-700"></span>
          </div>
        </div>
        <?php endif; ?>

        <!-- Timestamps -->
        <div class="flex flex-col gap-1 mt-4 pt-4 border-t border-gray-200 text-sm text-gray-500">
          <div class="flex justify-between">
            <span>Dibuat:</span>
            <span id="detail_expense_budget_created"></span>
          </div>
          <div class="flex justify-between">
            <span>Diperbarui:</span>
            <span id="detail_expense_budget_updated"></span>
          </div>
        </div>
      </div>
    </div>

    <div class="flex justify-end mt-6">
      <button type="button" onclick="toggleDetailExpenseBudgetModal(false)" 
              class="px-5 py-2 rounded-lg bg-blue-500 text-white font-semibold hover:bg-blue-600 transition">
        Tutup
      </button>
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
function toggleDetailExpenseBudgetModal(show, data = null) {
  const modal = document.getElementById('modalDetailExpenseBudget');
  if (show && data) {
    // Format currency
    const formatCurrency = (number) => {
      return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' })
        .format(number)
        .replace(/\s*IDR\s*/, 'Rp ');
    };

    // Format date
    const formatDate = (dateString) => {
      return new Date(dateString).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      });
    };

    // Set kategori dan tipe
    document.getElementById('detail_expense_budget_category').textContent = data.nama_kategori;
    const typeEl = document.getElementById('detail_expense_budget_type');
    typeEl.textContent = 'Pengeluaran';
    typeEl.className = 'text-xs px-2 py-0.5 rounded-full bg-red-100 text-red-800';

    // Set periode
    document.getElementById('detail_expense_budget_periode').textContent = new Date(data.periode + '-01')
      .toLocaleDateString('id-ID', { month: 'long', year: 'numeric' });

    // Set jumlah batas dan penggunaan
    document.getElementById('detail_expense_budget_amount').textContent = formatCurrency(data.jumlah_anggaran);
    document.getElementById('detail_expense_budget_usage').textContent = formatCurrency(data.current_usage || 0);

    // Hitung dan set progress bar
    const usage = parseFloat(data.current_usage || 0);
    const budget = parseFloat(data.jumlah_anggaran);
    const percentage = budget > 0 ? (usage / budget) * 100 : 0;
    
    const progressBar = document.getElementById('detail_expense_budget_progress');
    const percentageText = document.getElementById('detail_expense_budget_percentage');
    
    progressBar.style.width = Math.min(percentage, 100) + '%';
    if (percentage >= 100) {
      progressBar.className = 'h-full rounded-full bg-red-500';
      percentageText.className = 'text-sm font-medium text-red-600';
    } else {
      progressBar.className = 'h-full rounded-full bg-blue-500';
      percentageText.className = 'text-sm font-medium text-blue-600';
    }
    percentageText.textContent = percentage.toFixed(1) + '% terpakai';

    // Set user info jika admin
    if (document.getElementById('detail_expense_budget_user')) {
      document.getElementById('detail_expense_budget_user').textContent = data.username || '-';
    }

    // Set timestamps
    document.getElementById('detail_expense_budget_created').textContent = formatDate(data.created_at);
    document.getElementById('detail_expense_budget_updated').textContent = formatDate(data.updated_at);

    modal.classList.remove('hidden');
  } else {
    modal.classList.add('hidden');
  }
}
</script>
