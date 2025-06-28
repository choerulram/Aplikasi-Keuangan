<?= $this->extend('layouts/auth') ?>
<?= $this->section('content') ?>
<h2 class="text-2xl font-bold mb-4">Register</h2>
<form method="post">
    <div class="mb-4">
        <label>Username</label>
        <input type="text" name="username" class="border p-2 w-full" required />
    </div>
    <div class="mb-4">
        <label>Email</label>
        <input type="email" name="email" class="border p-2 w-full" required />
    </div>
    <div class="mb-4">
        <label>Nama</label>
        <input type="text" name="nama" class="border p-2 w-full" required />
    </div>
    <div class="mb-4">
        <label>Password</label>
        <input type="password" name="password" class="border p-2 w-full" required />
    </div>
    <button type="submit" class="bg-green-500 text-white px-4 py-2">Register</button>
    <a href="/login" class="ml-4 text-blue-700">Login</a>
</form>
<?= $this->endSection() ?>
