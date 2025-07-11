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
    <?php if (session('success')): ?>
        <div id="alertSuccess" class="alert-fade bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            <p class="font-bold">Sukses</p>
            <p><?= session('success') ?></p>
        </div>
    <?php endif; ?>
    <?php if (session('errors')): ?>
        <div id="alertError" class="alert-fade bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
            <p class="font-bold">Terjadi Kesalahan</p>
            <ul class="list-disc pl-5">
                <?php foreach(session('errors') as $err): ?>
                    <li><?= esc($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            toggleAddUserModal(true);
        });
        </script>
    <?php endif; ?>
    <style>
    .alert-fade {
        transition: opacity 0.7s ease, max-height 0.7s ease;
        opacity: 1;
        max-height: 500px;
        overflow: hidden;
    }
    .alert-fade.hide {
        opacity: 0;
        max-height: 0;
        padding: 0 1rem;
        margin: 0;
    }
    </style>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const alertSuccess = document.getElementById('alertSuccess');
        const alertError = document.getElementById('alertError');
        if (alertSuccess) {
            setTimeout(() => {
                alertSuccess.classList.add('hide');
            }, 3000);
            setTimeout(() => {
                if (alertSuccess) alertSuccess.style.display = 'none';
            }, 4000);
        }
        if (alertError) {
            setTimeout(() => {
                alertError.classList.add('hide');
            }, 4000);
            setTimeout(() => {
                if (alertError) alertError.style.display = 'none';
            }, 5000);
        }
    });
    </script>
    <div class="flex flex-wrap items-end gap-2 mb-6">
        <form method="get" action="" class="flex flex-wrap gap-2 items-end flex-1">
            <div>
                <label for="search" class="block text-xs font-semibold text-gray-600 mb-1">Cari Username/Nama</label>
                <input type="text" name="search" id="search" value="<?= esc($search ?? '') ?>" placeholder="Cari username atau nama..." class="px-3 py-2 border rounded-lg focus:outline-none focus:ring w-64 md:w-80" />
            </div>
            <div>
                <label for="role" class="block text-xs font-semibold text-gray-600 mb-1">Role</label>
                <select name="role" id="role" class="px-3 py-2 border rounded-lg focus:outline-none focus:ring w-40">
                    <option value="">Semua Role</option>
                    <option value="admin" <?= (isset($role) && $role === 'admin') ? 'selected' : '' ?>>Admin</option>
                    <option value="user" <?= (isset($role) && $role === 'user') ? 'selected' : '' ?>>User</option>
                </select>
            </div>
            <div class="flex gap-2 items-end">
                <button type="submit" class="px-4 py-2 bg-main text-white rounded-lg font-semibold shadow hover:bg-highlight transition">Terapkan</button>
                <?php if (!empty($search) || !empty($role)): ?>
                    <a href="<?= site_url('users') ?>" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition">Reset</a>
                <?php endif; ?>
            </div>
        </form>
        <button id="btnShowAddUserModal" type="button" class="inline-flex items-center gap-2 px-4 py-2 bg-main text-white rounded-lg shadow hover:bg-highlight transition h-11">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Tambah
        </button>
        <?= view('Users/modal_add_user') ?>
    </div>
    <div class="overflow-x-auto rounded-lg shadow border border-gray-200 bg-white mt-6">
        <table class="min-w-full border border-gray-300">
            <thead class="bg-main/90">
                <tr>
                    <th class="py-3 px-2 w-12 text-center text-xs font-bold text-white uppercase tracking-wider border-b border-r border-gray-300">No.</th>
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
                    <?php 
                        $pager = $pager ?? null;
                        $perPage = $perPage ?? 10;
                        $no = 1 + ($pager ? $pager->getCurrentPage('users') - 1 : 0) * $perPage;
                        foreach ($users as $user): ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-2 px-2 w-12 text-sm text-gray-700 font-medium border-b border-r border-gray-200 text-center"><?= $no++ ?></td>
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

    <?php if (isset($pager) && $pager->getPageCount('users') > 1): ?>
        <div class="mt-4 flex justify-center">
            <nav class="inline-flex rounded-md shadow-sm" aria-label="Pagination">
                <?= view('Users/pagination', ['pager' => $pager, 'group' => 'users']) ?>
            </nav>
        </div>
    <?php endif; ?>
<?php endif; ?>

<?= $this->endSection() ?>
