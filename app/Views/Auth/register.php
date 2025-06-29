<?= $this->extend('layouts/auth') ?>
<?= $this->section('content') ?>
<div class="min-h-screen flex items-center justify-center">
  <div class="w-full max-w-md rounded-lg shadow-lg p-8 border-t-8 border-main bg-white flex flex-col items-center">
    <h2 class="text-3xl font-bold text-main mb-6 w-full text-center">Register</h2>
    <form method="post" class="space-y-5 w-full flex flex-col items-center">
      <div class="w-full">
        <label class="block text-dark font-semibold mb-1 w-full text-left">Nama</label>
        <input type="text" name="nama" placeholder="Masukkan nama lengkap Anda" class="border border-gray-300 rounded px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-main" required />
      </div>
      <div class="w-full">
        <label class="block text-dark font-semibold mb-1 w-full text-left">Username</label>
        <input type="text" name="username" placeholder="Buat username Anda" class="border border-gray-300 rounded px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-main" required />
      </div>
      <div class="w-full">
        <label class="block text-dark font-semibold mb-1 w-full text-left">Email</label>
        <input type="email" name="email" placeholder="Masukkan email Anda" class="border border-gray-300 rounded px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-main" required />
      </div>
      <div class="w-full">
        <label class="block text-dark font-semibold mb-1 w-full text-left">Password</label>
        <input type="password" name="password" placeholder="Buat password yang aman" class="border border-gray-300 rounded px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-main" required />
      </div>
      <button type="submit" class="w-full bg-main hover:bg-highlight text-white font-bold py-2 rounded transition-colors duration-200">Register</button>
    </form>
    <div class="mt-6 w-full text-center">
      <span class="text-dark">Sudah punya akun?</span>
      <a href="/login" class="ml-2 text-main font-semibold hover:underline">Login</a>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
