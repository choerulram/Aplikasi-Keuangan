<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="p-8">
    <div class="max-w-4xl mx-auto">
        <!-- Flash Messages -->
        <?php if (session()->has('error')) : ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?= session('error') ?></span>
            </div>
        <?php endif; ?>

        <!-- Edit Profile Form -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6 bg-main/10">
                <div class="flex items-center justify-between">
                    <h1 class="text-2xl font-bold text-gray-800">Edit Profil</h1>
                    <a href="/profile" class="text-gray-600 hover:text-gray-800">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <div class="p-6">
                <form action="/profile/update" method="post">
                    <?= csrf_field() ?>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1" for="username">
                                Username
                            </label>
                            <input type="text" id="username" value="<?= esc($user['username']) ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100"
                                   disabled>
                            <p class="mt-1 text-sm text-gray-500">Username tidak dapat diubah</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1" for="nama">
                                Nama Lengkap
                            </label>
                            <input type="text" id="nama" name="nama" value="<?= esc($user['nama']) ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-main focus:border-transparent"
                                   required>
                            <?php if (session()->has('errors') && isset(session('errors')['nama'])) : ?>
                                <p class="mt-1 text-sm text-red-600"><?= session('errors')['nama'] ?></p>
                            <?php endif; ?>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1" for="email">
                                Email
                            </label>
                            <input type="email" id="email" name="email" value="<?= esc($user['email']) ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-main focus:border-transparent"
                                   required>
                            <?php if (session()->has('errors') && isset(session('errors')['email'])) : ?>
                                <p class="mt-1 text-sm text-red-600"><?= session('errors')['email'] ?></p>
                            <?php endif; ?>
                        </div>

                        <div class="flex gap-4">
                            <button type="submit" 
                                    class="flex-1 bg-main text-white py-2 px-4 rounded-lg hover:bg-main/90 transition duration-200 font-semibold">
                                Simpan Perubahan
                            </button>
                            <a href="/profile" 
                               class="flex-1 bg-gray-200 text-gray-800 py-2 px-4 rounded-lg hover:bg-gray-300 transition duration-200 font-semibold text-center">
                                Batal
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>