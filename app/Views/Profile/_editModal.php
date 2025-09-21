<!-- Edit Profile Modal -->
<div id="editProfileModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="min-h-screen px-4 text-center">
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="w-full">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl leading-6 font-bold text-gray-900">Edit Profil</h3>
                            <button type="button" onclick="document.getElementById('editProfileModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-500">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        
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
                            </div>

                            <div class="mt-6 flex gap-3">
                                <button type="submit" class="flex-1 bg-main text-white py-2 px-4 rounded-lg hover:bg-main/90 transition duration-200 font-semibold">
                                    Simpan Perubahan
                                </button>
                                <button type="button" 
                                        onclick="document.getElementById('editProfileModal').classList.add('hidden')"
                                        class="flex-1 bg-gray-200 text-gray-800 py-2 px-4 rounded-lg hover:bg-gray-300 transition duration-200 font-semibold">
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