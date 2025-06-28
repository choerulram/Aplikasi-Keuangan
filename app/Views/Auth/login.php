<?= $this->extend('layouts/auth') ?>
<?= $this->section('content') ?>
<div class="hidden bg-main border-main text-dark"></div>
<div class="min-h-screen flex items-center justify-center">
  <div class="w-full max-w-md rounded-lg shadow-lg p-8 border-t-8 border-main bg-white flex flex-col items-center text-center">
    <h2 class="text-3xl font-bold text-main mb-6 w-full">Login</h2>
    <?php if (session()->getFlashdata('success')): ?>
      <div class="text-green-700 bg-green-100 border border-green-300 rounded px-4 py-2 mb-4 w-full">
        <?= esc(session()->getFlashdata('success')) ?>
      </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
      <div class="text-red-600 bg-red-100 border border-red-300 rounded px-4 py-2 mb-4 w-full">
        <?= esc(session()->getFlashdata('error')) ?>
      </div>
    <?php endif; ?>
    <form method="post" class="space-y-5 w-full flex flex-col items-center text-center">
      <?= csrf_field() ?>
      <div class="w-full">
        <label class="block text-dark font-semibold mb-1 w-full">Email</label>
        <input type="email" name="email" class="border border-gray-300 rounded px-3 py-2 w-full text-center focus:outline-none focus:ring-2 focus:ring-main" required />
      </div>
      <div class="w-full">
        <label class="block text-dark font-semibold mb-1 w-full">Password</label>
        <input type="password" name="password" class="border border-gray-300 rounded px-3 py-2 w-full text-center focus:outline-none focus:ring-2 focus:ring-main" required />
      </div>
      <button type="submit" class="w-full bg-main hover:bg-highlight text-white font-bold py-2 rounded transition-colors duration-200">Login</button>
    </form>
    <div class="mt-6 w-full">
      <span class="text-dark">Belum punya akun?</span>
      <a href="/register" class="ml-2 text-main font-semibold hover:underline">Register</a>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
