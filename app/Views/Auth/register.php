<?= $this->extend('layouts/auth') ?>
<?= $this->section('content') ?>
<?php $errorMsg = isset($errorMsg) ? $errorMsg : session()->getFlashdata('error'); ?>
<div class="hidden bg-main border-main text-dark"></div>
<div class="min-h-screen flex items-center justify-center p-6 bg-gray-50">
  <div class="w-full max-w-md sm:max-w-md md:max-w-md lg:max-w-md xl:max-w-md rounded-lg shadow-lg p-6 sm:p-8 border-t-8 border-main bg-white flex flex-col items-center">
    <h2 class="text-2xl sm:text-3xl font-bold text-main mb-6 w-full text-center">Register</h2>
    <?php if (session()->getFlashdata('success')): ?>
      <div class="text-green-700 bg-green-100 border border-green-300 rounded px-4 py-2 mb-4 w-full text-center">
        <?= esc(session()->getFlashdata('success')) ?>
      </div>
    <?php endif; ?>
    <form method="POST" action="<?= site_url('register') ?>" class="space-y-5 w-full flex flex-col">
      <?= csrf_field() ?>
      <div class="w-full">
        <label class="block text-dark font-semibold mb-1 w-full text-left">Nama</label>
        <input type="text" name="nama" value="<?= old('nama') ?>" placeholder="Masukkan nama lengkap Anda" class="border border-gray-300 rounded px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-main" required />
      </div>
      <div class="w-full">
        <label class="block text-dark font-semibold mb-1 w-full text-left">Username</label>
        <input type="text" name="username" value="<?= old('username') ?>" placeholder="Buat username Anda" class="border border-gray-300 rounded px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-main" required />
      </div>
      <div class="w-full">
        <label class="block text-dark font-semibold mb-1 w-full text-left">Email</label>
        <input type="email" name="email" value="<?= old('email') ?>" placeholder="Masukkan email Anda" class="border border-gray-300 rounded px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-main" required />
      </div>
      <div class="w-full">
        <label class="block text-dark font-semibold mb-1 w-full text-left">Password</label>
        <input type="password" name="password" id="password-input" placeholder="Buat password yang aman" autocomplete="new-password" class="border border-gray-300 rounded px-3 py-3 sm:py-2 w-full text-base focus:outline-none focus:ring-2 focus:ring-main" required />
        <div id="password-error" class="text-red-600 text-sm mt-1 font-semibold italic" style="display:none"></div>
      </div>
      <button type="submit" class="w-full bg-main hover:bg-highlight text-white font-bold py-3 sm:py-2 rounded-md transition-colors duration-200 text-base">Register</button>
    </form>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
      const form = document.querySelector('form');
      const usernameInput = form.querySelector('input[name="username"]');
      const emailInput = form.querySelector('input[name="email"]');
      const passwordInput = form.querySelector('input[name="password"]');
      const namaInput = form.querySelector('input[name="nama"]');
      // Get register button
      const registerBtn = form.querySelector('button[type="submit"]');
      registerBtn.disabled = true;
      registerBtn.classList.add('opacity-50', 'cursor-not-allowed');
      // Remove old error
      function clearErrors() {
        form.querySelectorAll('.error-message').forEach(e => e.remove());
        usernameInput.classList.remove('border-red-500', 'bg-red-50');
        emailInput.classList.remove('border-red-500', 'bg-red-50');
      }
      form.addEventListener('submit', function(e) {
        e.preventDefault();
        clearErrors();
        const formData = new FormData(form);
        fetch(form.action, {
          method: 'POST',
          body: formData,
          headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            window.location.href = data.redirect;
          } else {
            if (data.errors) {
              if (data.errors.username) {
                usernameInput.classList.add('border-red-500', 'bg-red-50');
                usernameInput.insertAdjacentHTML('afterend', `<div class="error-message flex items-center mt-1 gap-1"><span class='text-red-600 text-sm font-semibold italic'>${data.errors.username}</span></div>`);
              }
              if (data.errors.email) {
                emailInput.classList.add('border-red-500', 'bg-red-50');
                emailInput.insertAdjacentHTML('afterend', `<div class="error-message flex items-center mt-1 gap-1"><span class='text-red-600 text-sm font-semibold italic'>${data.errors.email}</span></div>`);
              }
            }
          }
        });
      });
      // Password realtime validation
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
        // Enable/disable register button
        registerBtn.disabled = !passwordValid;
        registerBtn.classList.toggle('opacity-50', !passwordValid);
        registerBtn.classList.toggle('cursor-not-allowed', !passwordValid);
      });
      // Disable register button if password invalid on submit
      form.addEventListener('submit', function(e) {
        if (!passwordValid) {
          passwordInput.focus();
          return;
        }
      });
    });
    </script>
    <div class="mt-6 w-full text-center">
      <span class="text-dark">Sudah punya akun?</span>
      <a href="/login" class="ml-2 text-main font-semibold hover:underline">Login</a>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
