<?= $this->extend('layouts/auth') ?>
<?= $this->section('content') ?>
<div class="hidden bg-main border-main text-dark"></div>
<div class="min-h-screen flex items-center justify-center p-6 bg-gray-50">
  <div class="w-full max-w-md sm:max-w-md md:max-w-lg lg:max-w-xl rounded-lg shadow-lg p-6 sm:p-8 border-t-8 border-main bg-white flex flex-col items-center">
    <h2 class="text-2xl sm:text-3xl font-bold text-main mb-6 w-full text-center">Login</h2>
    <?php if (session()->getFlashdata('error')): ?>
      <div class="text-red-600 bg-red-100 border border-red-300 rounded px-4 py-2 mb-4 w-full">
        <?= esc(session()->getFlashdata('error')) ?>
      </div>
    <?php endif; ?>
    <form method="POST" class="space-y-5 w-full flex flex-col">
      <?= csrf_field() ?>
      <div class="w-full">
        <label class="block text-dark font-semibold mb-1 w-full text-left text-sm sm:text-base">Email</label>
        <input type="email" name="email" placeholder="Masukkan email Anda" autocomplete="email" class="border border-gray-300 rounded px-3 py-3 sm:py-2 w-full text-base focus:outline-none focus:ring-2 focus:ring-main" required />
      </div>
      <div class="w-full">
        <label class="block text-dark font-semibold mb-1 w-full text-left text-sm sm:text-base">Password</label>
        <input type="password" name="password" id="password-input" placeholder="Masukkan password Anda" autocomplete="current-password" class="border border-gray-300 rounded px-3 py-3 sm:py-2 w-full text-base focus:outline-none focus:ring-2 focus:ring-main" required />
        <div id="password-error" class="text-red-600 text-sm mt-1 font-semibold italic" style="display:none"></div>
      </div>
      <button type="submit" class="w-full bg-main hover:bg-highlight text-white font-bold py-3 sm:py-2 rounded-md transition-colors duration-200 text-base">Login</button>
    </form>
    <div class="mt-6 w-full text-center">
      <span class="text-dark">Belum punya akun?</span>
      <a href="/register" class="ml-2 text-main font-semibold hover:underline">Register</a>
    </div>
  </div>
</div>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const passwordInput = document.getElementById('password-input');
    const loginBtn = form.querySelector('button[type="submit"]');
    loginBtn.disabled = true;
    loginBtn.classList.add('opacity-50', 'cursor-not-allowed');
    let passwordValid = false;
    passwordInput.addEventListener('input', function() {
      const val = passwordInput.value;
      let msg = '';
      if (val.length > 0 && val.length < 8) {
        msg = '<i>Password minimal 8 karakter.</i>';
        passwordValid = false;
      } else if (val.length >= 8 && !/[0-9]/.test(val)) {
        msg = '<i>Password harus ada angkanya.</i>';
        passwordValid = false;
      } else {
        msg = '';
        passwordValid = true;
      }
      const errDiv = document.getElementById('password-error');
      if (msg) {
        errDiv.innerHTML = `<span class='text-red-600 text-sm font-semibold italic'>${msg}</span>`;
        errDiv.style.display = '';
        passwordInput.classList.add('border-red-500', 'bg-red-50');
      } else {
        errDiv.innerHTML = '';
        errDiv.style.display = 'none';
        passwordInput.classList.remove('border-red-500', 'bg-red-50');
      }
      loginBtn.disabled = !passwordValid;
      loginBtn.classList.toggle('opacity-50', !passwordValid);
      loginBtn.classList.toggle('cursor-not-allowed', !passwordValid);
    });
    form.addEventListener('submit', function(e) {
      if (!passwordValid) {
        passwordInput.focus();
        e.preventDefault();
        return;
      }
    });
  });
</script>
<?= $this->endSection() ?>
