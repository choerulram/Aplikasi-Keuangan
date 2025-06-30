<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>
<h1 class="text-3xl font-bold text-main mb-4">User</h1>
<p class="text-dark">Ini adalah halaman User. Kelola data user aplikasi di sini.</p>

<?php if (session('role') !== 'admin'): ?>
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
        <p class="font-bold">Akses Ditolak</p>
        <p>Anda tidak memiliki izin untuk mengakses halaman ini.</p>
    </div>
    <a href="<?= site_url('dashboard') ?>" class="text-main font-semibold">Kembali ke Dashboard</a>
<?php else: ?>
    <table class="min-w-full bg-white border border-gray-200 mt-6">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">ID</th>
                <th class="py-2 px-4 border-b">Username</th>
                <th class="py-2 px-4 border-b">Email</th>
                <th class="py-2 px-4 border-b">Nama</th>
                <th class="py-2 px-4 border-b">Tanggal Dibuat</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($users)): ?>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td class="py-2 px-4 border-b"><?= esc($user['id']) ?></td>
                        <td class="py-2 px-4 border-b"><?= esc($user['username']) ?></td>
                        <td class="py-2 px-4 border-b"><?= esc($user['email']) ?></td>
                        <td class="py-2 px-4 border-b"><?= esc($user['nama']) ?></td>
                        <td class="py-2 px-4 border-b"><?= esc($user['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="py-2 px-4 text-center">Tidak ada data user.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
<?php endif; ?>

<?= $this->endSection() ?>
