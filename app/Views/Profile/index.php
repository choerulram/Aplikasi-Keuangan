<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="container px-6 mx-auto mt-6">
    <h1 class="text-3xl font-bold text-main mb-6">Profil Pengguna</h1>
    <div class="w-full">
        <!-- Flash Messages -->
        <?php if (session()->has('success')) : ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?= session('success') ?></span>
            </div>
        <?php endif; ?>

        <?php if (session()->has('error')) : ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?= session('error') ?></span>
            </div>
        <?php endif; ?>

        <!-- Profile Card -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <!-- Profile Header with Avatar -->
            <div class="bg-main/10 p-6">
                <div class="flex items-center gap-6">
                    <div class="w-24 h-24 rounded-full bg-main text-white flex items-center justify-center text-4xl font-bold shadow-lg">
                        <?= strtoupper(substr($user['nama'], 0, 1)) ?>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800"><?= esc($user['nama']) ?></h1>
                        <p class="text-gray-600"><?= ucfirst($user['role']) ?></p>
                        <p class="text-sm text-gray-500">Bergabung sejak <?= date('d M Y', strtotime($user['created_at'])) ?></p>
                    </div>
                </div>
            </div>

            <!-- Profile Information -->
            <div class="p-6">
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Informasi Profil</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Username</label>
                            <p class="text-gray-800"><?= esc($user['username']) ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Nama Lengkap</label>
                            <p class="text-gray-800"><?= esc($user['nama']) ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Email</label>
                            <p class="text-gray-800"><?= esc($user['email']) ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Role</label>
                            <p class="text-gray-800"><?= ucfirst($user['role']) ?></p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-4">
                    <button type="button"
                            onclick="document.getElementById('editProfileModal').classList.remove('hidden')"
                            class="flex-1 bg-main text-white py-2 px-4 rounded-lg hover:bg-main/90 transition duration-200 font-semibold">
                        Edit Profil
                    </button>
                    <button type="button" 
                            onclick="document.getElementById('changePasswordModal').classList.remove('hidden')"
                            class="flex-1 bg-gray-800 text-white py-2 px-4 rounded-lg hover:bg-gray-700 transition duration-200 font-semibold">
                        Ubah Password
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div id="changePasswordModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="min-h-screen px-4 text-center">
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="w-full">
                        <h3 class="text-xl leading-6 font-bold text-gray-900 mb-4">Ubah Password</h3>
                        <form action="/profile/change-password" method="post">
                            <?= csrf_field() ?>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1" for="current_password">
                                        Password Saat Ini
                                    </label>
                                    <input type="password" id="current_password" name="current_password"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-main focus:border-transparent"
                                           required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1" for="new_password">
                                        Password Baru
                                    </label>
                                    <input type="password" id="new_password" name="new_password"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-main focus:border-transparent"
                                           required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1" for="confirm_password">
                                        Konfirmasi Password Baru
                                    </label>
                                    <input type="password" id="confirm_password" name="confirm_password"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-main focus:border-transparent"
                                           required>
                                </div>
                            </div>
                            <div class="mt-6 flex gap-3">
                                <button type="submit" class="flex-1 bg-main text-white py-2 px-4 rounded-md hover:bg-main/90 transition duration-200 font-semibold">
                                    Simpan Password
                                </button>
                                <button type="button" 
                                        onclick="document.getElementById('changePasswordModal').classList.add('hidden')"
                                        class="flex-1 bg-gray-200 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-300 transition duration-200 font-semibold">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->include('Profile/_editModal') ?>
<?= $this->endSection() ?>