<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="p-8">
    <div class="max-w-4xl mx-auto">
        <!-- Profile Header -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <div class="flex items-center gap-6">
                <div class="w-24 h-24 rounded-full bg-main text-white flex items-center justify-center text-4xl font-bold">
                    <?= strtoupper(substr($user['nama'], 0, 1)) ?>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800"><?= esc($user['nama']) ?></h1>
                    <p class="text-gray-500"><?= ucfirst($user['role']) ?></p>
                    <p class="text-sm text-gray-400">Bergabung sejak <?= date('d M Y', strtotime($user['created_at'])) ?></p>
                </div>
            </div>
        </div>

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

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Profile Information -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Informasi Profil</h2>
                <form action="/profile/update" method="post">
                    <?= csrf_field() ?>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1" for="username">
                                Username
                            </label>
                            <input type="text" id="username" value="<?= esc($user['username']) ?>" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100" 
                                   disabled>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1" for="nama">
                                Nama Lengkap
                            </label>
                            <input type="text" id="nama" name="nama" value="<?= esc($user['nama']) ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-main focus:border-transparent"
                                   required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1" for="email">
                                Email
                            </label>
                            <input type="email" id="email" name="email" value="<?= esc($user['email']) ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-main focus:border-transparent"
                                   required>
                        </div>

                        <button type="submit" class="w-full bg-main text-white py-2 px-4 rounded-md hover:bg-main/90 transition duration-200 font-semibold">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Change Password -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Ubah Password</h2>
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

                        <button type="submit" class="w-full bg-main text-white py-2 px-4 rounded-md hover:bg-main/90 transition duration-200 font-semibold">
                            Ubah Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>