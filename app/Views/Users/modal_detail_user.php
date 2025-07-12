<!-- Modal Detail User -->
<div id="modalDetailUser" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden transition-opacity duration-200">
  <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative border-t-4 border-blue-500 animate-fadeIn">
    <button onclick="toggleDetailUserModal(false)" class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 focus:outline-none">
      <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
    <div class="flex flex-col items-center text-center">
      <span class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 text-blue-600 mb-4">
        <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M1.5 12s3.5-7 10.5-7 10.5 7 10.5 7-3.5 7-10.5 7S1.5 12 1.5 12z"/>
          <circle cx="12" cy="12" r="3"/>
        </svg>
      </span>
      <h2 class="text-xl font-bold mb-4 text-blue-600">Detail User</h2>
    </div>
    <div class="space-y-5 mt-2">
      <div class="flex flex-col gap-2 bg-white rounded-lg p-4 border border-gray-200">
        <div class="flex flex-col gap-1">
          <span class="text-[13px] text-gray-700 font-semibold uppercase tracking-wide">Username</span>
          <span id="detail_user_username" class="text-lg font-bold text-gray-900"></span>
        </div>
        <div class="flex flex-col gap-1 mt-2">
          <span class="text-[13px] text-gray-700 font-semibold uppercase tracking-wide">Email</span>
          <span id="detail_user_email" class="text-base text-gray-700"></span>
        </div>
        <div class="flex flex-col gap-1 mt-2">
          <span class="text-[13px] text-gray-700 font-semibold uppercase tracking-wide">Nama</span>
          <span id="detail_user_nama" class="text-base text-gray-700"></span>
        </div>
        <div class="flex flex-col gap-1 mt-2">
          <span class="text-[13px] text-gray-700 font-semibold uppercase tracking-wide">Tanggal Dibuat</span>
          <span id="detail_user_created_at" class="text-base text-gray-700"></span>
        </div>
        <div class="flex flex-col gap-1 mt-2">
          <span class="text-[13px] text-gray-700 font-semibold uppercase tracking-wide">Role</span>
          <span id="detail_user_role_badge"></span>
        </div>
      </div>
    </div>
    <div class="flex justify-end mt-8">
      <button type="button" onclick="toggleDetailUserModal(false)" class="px-5 py-2 rounded-lg bg-blue-500 text-white font-semibold hover:bg-blue-600 transition">Tutup</button>
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
function toggleDetailUserModal(show, data = null) {
  const modal = document.getElementById('modalDetailUser');
  if (show) {
    if (data) {
      document.getElementById('detail_user_username').textContent = data.username || '';
      document.getElementById('detail_user_email').textContent = data.email || '';
      document.getElementById('detail_user_nama').textContent = data.nama || '';
      document.getElementById('detail_user_created_at').textContent = data.created_at || '';
      // Badge role
      let role = data.role ? data.role.charAt(0).toUpperCase() + data.role.slice(1) : '';
      let badgeColor = data.role === 'admin' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700';
      document.getElementById('detail_user_role_badge').innerHTML = `<span class='inline-block px-2 py-0.5 rounded text-xs font-semibold ${badgeColor}'>${role}</span>`;
    }
    modal.classList.remove('hidden');
  } else {
    modal.classList.add('hidden');
  }
}
</script>
