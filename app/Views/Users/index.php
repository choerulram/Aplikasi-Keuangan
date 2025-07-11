<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>
<h1 class="text-3xl font-bold text-main mb-4">Manajemen User</h1>

<?php if (session('role') !== 'admin'): ?>
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
        <p class="font-bold">Akses Ditolak</p>
        <p>Anda tidak memiliki izin untuk mengakses halaman ini.</p>
    </div>
    <a href="<?= site_url('dashboard') ?>" class="text-main font-semibold">Kembali ke Dashboard</a>
<?php else: ?>
    <div class="overflow-x-auto rounded-lg shadow border border-gray-200 bg-white mt-6">
        <table class="min-w-full border border-gray-300">
            <thead class="bg-main/90">
                <tr>
                    <th class="py-3 px-2 w-12 text-center text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">ID</th>
                    <th class="py-3 px-4 text-left text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Username</th>
                    <th class="py-3 px-4 text-left text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Email</th>
                    <th class="py-3 px-4 text-left text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Nama</th>
                    <th class="py-3 px-4 text-left text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Tanggal Dibuat</th>
                    <th class="py-3 px-4 text-left text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Role</th>
                    <th class="py-3 px-2 w-40 text-center text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $user): ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-2 px-2 w-12 text-sm text-gray-700 font-medium border-b border-r border-gray-200 text-center"><?= esc($user['id']) ?></td>
                            <td class="py-2 px-4 text-sm text-gray-700 border-b border-r border-gray-200"><?= esc($user['username']) ?></td>
                            <td class="py-2 px-4 text-sm text-gray-700 border-b border-r border-gray-200"><?= esc($user['email']) ?></td>
                            <td class="py-2 px-4 text-sm text-gray-700 border-b border-r border-gray-200"><?= esc($user['nama']) ?></td>
                            <td class="py-2 px-4 text-sm text-gray-700 border-b border-r border-gray-200"><?= esc($user['created_at']) ?></td>
                            <td class="py-2 px-4 text-sm text-gray-700 border-b border-r border-gray-200">
                                <span class="inline-block px-2 py-1 rounded text-xs font-semibold <?= esc($user['role']) === 'admin' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' ?>">
                                    <?= ucfirst(esc($user['role'])) ?>
                                </span>
                            </td>
                            <td class="py-2 px-2 w-40 text-center border-b border-r border-gray-200">
                                <div class="flex justify-center gap-1">
                                    <button type="button"
                                        class="inline-flex items-center px-2 py-1 text-xs font-semibold text-white bg-blue-500 rounded hover:bg-blue-600"
                                        title="Detail"
                                        onclick='toggleDetailUserModal(true, <?= json_encode($user, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP) ?>)'>
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                          <path stroke-linecap="round" stroke-linejoin="round" d="M1.5 12s3.5-7 10.5-7 10.5 7 10.5 7-3.5 7-10.5 7S1.5 12 1.5 12z"/>
                                          <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                        Detail
                                    </button>
                                    <button type="button"
                                        class="inline-flex items-center px-2 py-1 text-xs font-semibold text-white bg-yellow-500 rounded hover:bg-yellow-600"
                                        title="Ubah"
                                        onclick='toggleEditUserModal(true, <?= json_encode($user, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP) ?>)'>
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                          <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 5.487l1.65 1.65a2.121 2.121 0 010 3l-8.486 8.486a2 2 0 01-.878.513l-3.06.765a.5.5 0 01-.606-.606l.765-3.06a2 2 0 01.513-.878l8.486-8.486a2.121 2.121 0 013 0z"/>
                                          <path stroke-linecap="round" stroke-linejoin="round" d="M15 7l2 2"/>
                                        </svg>
                                        Ubah
                                    </button>
                                    <button type="button"
                                        class="inline-flex items-center px-2 py-1 text-xs font-semibold text-white bg-red-500 rounded hover:bg-red-600"
                                        title="Hapus"
                                        onclick='toggleDeleteUserModal(true, <?= json_encode($user, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP) ?>)'>
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                          <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0 1 16.138 21H7.862a2 2 0 0 1-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M8 7V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2" />
                                        </svg>
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="py-4 px-4 text-center text-gray-400 border-b border-r border-gray-200">Tidak ada data user.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>
