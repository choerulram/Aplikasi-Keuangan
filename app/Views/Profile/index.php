<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="container px-3 md:px-6 mx-auto mt-4 md:mt-6">
    <h1 class="text-2xl md:text-3xl font-bold text-main tracking-tight drop-shadow-sm mb-4 md:mb-6">Profil Pengguna</h1>
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
            <div class="bg-main/10 p-4 md:p-6">
                <div class="flex flex-col md:flex-row items-center gap-4 md:gap-6">
                    <div class="w-16 h-16 md:w-24 md:h-24 rounded-full bg-main text-white flex items-center justify-center text-3xl md:text-4xl font-bold shadow-lg mb-2 md:mb-0">
                        <?= strtoupper(substr($user['nama'], 0, 1)) ?>
                    </div>
                    <div class="text-center md:text-left">
                        <h1 class="text-lg md:text-2xl font-bold text-gray-800 leading-tight mb-1 md:mb-0"><?= esc($user['nama']) ?></h1>
                        <p class="text-xs md:text-sm text-gray-600 mb-1 md:mb-0"><?= ucfirst($user['role']) ?></p>
                        <p class="text-xs md:text-sm text-gray-500">Bergabung sejak <?= date('d M Y', strtotime($user['created_at'])) ?></p>
                    </div>
                </div>
            </div>

            <!-- Profile Information -->
            <div class="p-4 md:p-6">
                <div class="flex flex-col md:flex-row md:justify-between md:items-start mb-6 md:mb-8 gap-2 md:gap-0">
                    <h2 class="text-base md:text-xl font-bold text-gray-800">Informasi Profil</h2>
                    <button type="button"
                            onclick="document.getElementById('editProfileModal').classList.remove('hidden')"
                            class="bg-main text-white py-2 px-3 md:px-4 rounded-lg hover:bg-main/90 transition duration-200 font-semibold text-sm md:text-base">
                        Edit Profil
                    </button>
                </div>
                <div class="space-y-3 md:space-y-4">
                    <div>
                        <label class="block text-xs md:text-sm font-medium text-gray-500 mb-1">Username</label>
                        <p class="text-sm md:text-base text-gray-800 break-all"><?= esc($user['username']) ?></p>
                    </div>
                    <div>
                        <label class="block text-xs md:text-sm font-medium text-gray-500 mb-1">Nama Lengkap</label>
                        <p class="text-sm md:text-base text-gray-800 break-all"><?= esc($user['nama']) ?></p>
                    </div>
                    <div>
                        <label class="block text-xs md:text-sm font-medium text-gray-500 mb-1">Email</label>
                        <p class="text-sm md:text-base text-gray-800 break-all"><?= esc($user['email']) ?></p>
                    </div>
                    <div>
                        <label class="block text-xs md:text-sm font-medium text-gray-500 mb-1">Role</label>
                        <p class="text-sm md:text-base text-gray-800"><?= ucfirst($user['role']) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div id="changePasswordModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="min-h-screen px-2 md:px-4 text-center">
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all my-8 align-middle max-w-md md:max-w-lg w-full">
            <div class="bg-white px-3 pt-5 pb-4 md:p-6 md:pb-4">
                <div class="flex items-start">
                    <div class="w-full">
                        <h3 class="text-lg md:text-xl leading-6 font-bold text-gray-900 mb-3 md:mb-4">Ubah Password</h3>
                        <form action="/profile/change-password" method="post">
                            <?= csrf_field() ?>
                            <div class="space-y-3 md:space-y-4">
                                <div>
                                    <label class="block text-xs md:text-sm font-medium text-gray-700 mb-1" for="current_password">
                                        Password Saat Ini
                                    </label>
                                    <input type="password" id="current_password" name="current_password"
                                           class="w-full px-3 py-2 md:py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-main focus:border-transparent text-sm md:text-base"
                                           required>
                                </div>
                                <div>
                                    <label class="block text-xs md:text-sm font-medium text-gray-700 mb-1" for="new_password">
                                        Password Baru
                                    </label>
                                    <input type="password" id="new_password" name="new_password"
                                           class="w-full px-3 py-2 md:py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-main focus:border-transparent text-sm md:text-base"
                                           required>
                                </div>
                                <div>
                                    <label class="block text-xs md:text-sm font-medium text-gray-700 mb-1" for="confirm_password">
                                        Konfirmasi Password Baru
                                    </label>
                                    <input type="password" id="confirm_password" name="confirm_password"
                                           class="w-full px-3 py-2 md:py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-main focus:border-transparent text-sm md:text-base"
                                           required>
                                </div>
                            </div>
                            <div class="mt-4 md:mt-6 flex flex-col md:flex-row gap-2 md:gap-3">
                                <button type="submit" class="w-full md:flex-1 bg-main text-white py-2 px-4 rounded-md hover:bg-main/90 transition duration-200 font-semibold text-sm md:text-base">
                                    Simpan Password
                                </button>
                                <button type="button" 
                                        onclick="document.getElementById('changePasswordModal').classList.add('hidden')"
                                        class="w-full md:flex-1 bg-gray-200 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-300 transition duration-200 font-semibold text-sm md:text-base">
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