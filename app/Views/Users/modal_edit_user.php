<!-- Modal Edit User -->
<div id="modalEditUser" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden transition-opacity duration-200">
  <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative border-t-4 border-yellow-500 animate-fadeIn">
    <button onclick="toggleEditUserModal(false)" class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 focus:outline-none">
      <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
    <div class="flex flex-col items-center text-center">
      <span class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-yellow-100 text-yellow-600 mb-4">
        <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 5.487l1.65 1.65a2.121 2.121 0 010 3l-8.486 8.486a2 2 0 01-.878.513l-3.06.765a.5.5 0 01-.606-.606l.765-3.06a2 2 0 01.513-.878l8.486-8.486a2.121 2.121 0 013 0z"/>
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 7l2 2"/>
        </svg>
      </span>
      <h2 class="text-xl font-bold mb-4 text-yellow-600">Ubah User</h2>
    </div>
    <form id="formEditUser" method="POST" action="/users/edit">
      <?= csrf_field() ?>
      <input type="hidden" name="id" id="edit_user_id">
      <div class="mb-3">
        <label class="block text-sm font-semibold mb-1">Username</label>
        <input type="text" name="username" id="edit_user_username" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-yellow-500" required>
      </div>
      <div class="mb-3">
        <label class="block text-sm font-semibold mb-1">Email</label>
        <input type="email" name="email" id="edit_user_email" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-yellow-500" required>
      </div>
      <div class="mb-3">
        <label class="block text-sm font-semibold mb-1">Nama</label>
        <input type="text" name="nama" id="edit_user_nama" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-yellow-500" required>
      </div>
      <div class="mb-3">
        <label class="block text-sm font-semibold mb-1">Role</label>
        <select name="role" id="edit_user_role" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-yellow-500" required>
          <option value="admin">Admin</option>
          <option value="user">User</option>
        </select>
      </div>
      <div class="mb-3">
        <label class="block text-sm font-semibold mb-1">Password <span class="text-xs text-gray-500">(Kosongkan jika tidak ingin mengubah)</span></label>
        <input type="password" name="password" id="edit_user_password" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-yellow-500" autocomplete="new-password" oninput="validateEditUserPassword()">
        <div id="editUserPasswordError" class="text-xs text-red-600 mt-1 hidden"></div>
      </div>
      <div class="flex justify-end mt-4">
        <button type="button" onclick="toggleEditUserModal(false)" class="px-4 py-2 mr-2 rounded bg-gray-200 text-gray-700 hover:bg-gray-300">Batal</button>
        <button id="btnEditUserSubmit" type="submit" class="px-4 py-2 rounded bg-yellow-500 text-white font-semibold hover:bg-yellow-600 transition">Simpan Perubahan</button>
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
function validateEditUserPassword() {
  const passwordInput = document.getElementById('edit_user_password');
  const errorDiv = document.getElementById('editUserPasswordError');
  const submitBtn = document.getElementById('btnEditUserSubmit');
  let errors = [];
  const value = passwordInput.value;
  // Jika password kosong, tidak perlu validasi, tombol aktif
  if (value.length === 0) {
    errorDiv.innerHTML = '';
    errorDiv.classList.add('hidden');
    submitBtn.disabled = false;
    return;
  }
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

function toggleEditUserModal(show, data = null) {
  const modal = document.getElementById('modalEditUser');
  const passwordInput = document.getElementById('edit_user_password');
  const errorDiv = document.getElementById('editUserPasswordError');
  const submitBtn = document.getElementById('btnEditUserSubmit');
  if (show) {
    if (data) {
      document.getElementById('edit_user_id').value = data.id;
      document.getElementById('edit_user_username').value = data.username;
      document.getElementById('edit_user_email').value = data.email;
      document.getElementById('edit_user_nama').value = data.nama;
      document.getElementById('edit_user_role').value = data.role;
      passwordInput.value = '';
    }
    if (errorDiv) {
      errorDiv.innerHTML = '';
      errorDiv.classList.add('hidden');
    }
    if (submitBtn) submitBtn.disabled = false;
    modal.classList.remove('hidden');
  } else {
    modal.classList.add('hidden');
  }
}
document.addEventListener('DOMContentLoaded', function() {
  const passwordInput = document.getElementById('edit_user_password');
  if (passwordInput) passwordInput.addEventListener('input', validateEditUserPassword);
});
</script>
