<!-- Modal Tambah User -->
<div id="modalAddUser" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden transition-opacity duration-200">
  <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative border-t-4 border-main animate-fadeIn">
    <button onclick="toggleAddUserModal(false)" class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 focus:outline-none">
      <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
    <div class="flex flex-col items-center text-center">
      <span class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-main/10 text-main mb-4">
        <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
      </span>
      <h2 class="text-xl font-bold mb-4 text-main">Tambah User</h2>
    </div>
    <form id="formAddUser" method="POST" action="/users/add">
      <?= csrf_field() ?>
      <div class="mb-3">
        <label class="block text-sm font-semibold mb-1">Username</label>
        <input type="text" name="username" placeholder="Username" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-main" required autocomplete="off">
      </div>
      <div class="mb-3">
        <label class="block text-sm font-semibold mb-1">Email</label>
        <input type="email" name="email" placeholder="Email" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-main" required autocomplete="off">
      </div>
      <div class="mb-3">
        <label class="block text-sm font-semibold mb-1">Nama</label>
        <input type="text" name="nama" placeholder="Nama Lengkap" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-main" required autocomplete="off">
      </div>
      <div class="mb-3">
        <label class="block text-sm font-semibold mb-1">Password</label>
        <input type="password" name="password" id="addUserPassword" placeholder="Password" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-main" required autocomplete="new-password" oninput="validateAddUserPassword()">
        <div id="addUserPasswordError" class="text-xs text-red-600 mt-1 hidden"></div>
      </div>
      <div class="mb-3">
        <label class="block text-sm font-semibold mb-1">Role</label>
        <select name="role" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-main" required>
          <option value="">Pilih Role</option>
          <option value="admin">Admin</option>
          <option value="user">User</option>
        </select>
      </div>
      <div class="flex justify-end mt-4">
        <button type="button" onclick="toggleAddUserModal(false)" class="px-4 py-2 mr-2 rounded bg-gray-200 text-gray-700 hover:bg-gray-300">Batal</button>
        <button id="btnAddUserSubmit" type="submit" class="px-4 py-2 rounded bg-main text-white font-semibold hover:bg-highlight transition" disabled>Simpan</button>
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
function validateAddUserPassword() {
  const passwordInput = document.getElementById('addUserPassword');
  const errorDiv = document.getElementById('addUserPasswordError');
  const submitBtn = document.getElementById('btnAddUserSubmit');
  let errors = [];
  const value = passwordInput.value;
  if (value.length < 8) {
    errors.push('Password minimal 8 karakter');
  }
  if (!/[0-9]/.test(value)) {
    errors.push('Password harus mengandung angka');
  }
  if (errors.length > 0) {
    errorDiv.innerHTML = errors.join('<br>');
    errorDiv.classList.remove('hidden');
    submitBtn.disabled = true;
  } else {
    errorDiv.innerHTML = '';
    errorDiv.classList.add('hidden');
    submitBtn.disabled = false;
  }
}

function toggleAddUserModal(show) {
  const modal = document.getElementById('modalAddUser');
  const form = document.getElementById('formAddUser');
  const passwordInput = document.getElementById('addUserPassword');
  const errorDiv = document.getElementById('addUserPasswordError');
  const submitBtn = document.getElementById('btnAddUserSubmit');
  if (show) {
    if (form) form.reset();
    if (errorDiv) {
      errorDiv.innerHTML = '';
      errorDiv.classList.add('hidden');
    }
    if (submitBtn) submitBtn.disabled = true;
    modal.classList.remove('hidden');
  } else {
    modal.classList.add('hidden');
  }
}
document.addEventListener('DOMContentLoaded', function() {
  const btn = document.getElementById('btnShowAddUserModal');
  if (btn) btn.onclick = () => toggleAddUserModal(true);
  // Inisialisasi validasi password saat halaman dimuat
  const passwordInput = document.getElementById('addUserPassword');
  if (passwordInput) passwordInput.addEventListener('input', validateAddUserPassword);
});
</script>
