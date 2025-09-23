<!-- Edit Profile Modal -->
<div id="editProfileModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="min-h-screen px-4 text-center">
        <!-- Modal yang lebih menarik dengan animasi -->
        <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border-t-4 border-main animate-modal">
            <!-- Header Modal yang lebih menarik -->
            <div class="bg-main/10 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl leading-6 font-bold text-gray-900">Edit Profil</h3>
                    <button type="button" onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700 transition-colors">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            
            <div class="bg-white px-6 py-4">
                <form id="editProfileForm" action="/profile/update" method="post" onsubmit="return validateForm()">
                    <?= csrf_field() ?>
                    <div class="space-y-4">
                        <!-- Username Field -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1" for="username">
                                Username
                            </label>
                            <div class="relative">
                                <input type="text" id="username" value="<?= esc($user['username']) ?>"
                                    class="w-full pl-3 pr-10 py-2.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-500"
                                    disabled>
                                <span class="absolute right-3 top-2.5 text-gray-400">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 0v2m0-2h2m-2 0H9m3-3V6a3 3 0 00-3-3H9m1.5-1.5L9 4.5M9 4.5l1.5 1.5M9 4.5H6a3 3 0 00-3 3v12a3 3 0 003 3h12a3 3 0 003-3v-3"/>
                                    </svg>
                                </span>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Username tidak dapat diubah</p>
                        </div>

                        <!-- Nama Lengkap Field -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1" for="nama">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="text" id="nama" name="nama" value="<?= esc($user['nama']) ?>"
                                    class="w-full pl-3 pr-10 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-main focus:border-transparent"
                                    minlength="3" maxlength="100" required>
                                <span class="absolute right-3 top-2.5 text-gray-400">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </span>
                            </div>
                            <div id="namaError" class="mt-1 text-sm text-red-600 hidden"></div>
                            <?php if (session()->has('errors') && isset(session('errors')['nama'])) : ?>
                                <p class="mt-1 text-sm text-red-600"><?= session('errors')['nama'] ?></p>
                            <?php endif; ?>
                        </div>

                        <!-- Email Field -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1" for="email">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="email" id="email" name="email" value="<?= esc($user['email']) ?>"
                                    class="w-full pl-3 pr-10 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-main focus:border-transparent"
                                    required>
                                <span class="absolute right-3 top-2.5 text-gray-400">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </span>
                            </div>
                            <div id="emailError" class="mt-1 text-sm text-red-600 hidden"></div>
                            <?php if (session()->has('errors') && isset(session('errors')['email'])) : ?>
                                <p class="mt-1 text-sm text-red-600"><?= session('errors')['email'] ?></p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Tombol dengan desain yang lebih menarik -->
                    <div class="mt-8 flex gap-3">
                        <button type="submit" class="flex-1 bg-main text-white py-2.5 px-4 rounded-lg hover:bg-main/90 transition duration-200 font-semibold inline-flex items-center justify-center gap-2 hover:shadow-lg">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Simpan Perubahan
                        </button>
                        <button type="button" 
                                onclick="closeEditModal()"
                                class="flex-1 bg-gray-100 text-gray-800 py-2.5 px-4 rounded-lg hover:bg-gray-200 transition duration-200 font-semibold inline-flex items-center justify-center gap-2">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes modalAppear {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-modal {
    animation: modalAppear 0.3s ease-out;
}
</style>

<script>
function closeEditModal() {
    document.getElementById('editProfileModal').classList.add('hidden');
    // Reset error messages
    document.getElementById('namaError').classList.add('hidden');
    document.getElementById('emailError').classList.add('hidden');
}

function validateForm() {
    let isValid = true;
    const nama = document.getElementById('nama');
    const email = document.getElementById('email');
    const namaError = document.getElementById('namaError');
    const emailError = document.getElementById('emailError');
    
    // Reset error messages
    namaError.classList.add('hidden');
    emailError.classList.add('hidden');

    // Validasi Nama
    if (nama.value.length < 3) {
        namaError.textContent = 'Nama harus minimal 3 karakter';
        namaError.classList.remove('hidden');
        isValid = false;
    } else if (nama.value.length > 100) {
        namaError.textContent = 'Nama tidak boleh lebih dari 100 karakter';
        namaError.classList.remove('hidden');
        isValid = false;
    }

    // Validasi Email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email.value)) {
        emailError.textContent = 'Format email tidak valid';
        emailError.classList.remove('hidden');
        isValid = false;
    }

    return isValid;
}

// Event listeners untuk validasi real-time
document.getElementById('nama').addEventListener('input', function() {
    const namaError = document.getElementById('namaError');
    if (this.value.length < 3) {
        namaError.textContent = 'Nama harus minimal 3 karakter';
        namaError.classList.remove('hidden');
    } else if (this.value.length > 100) {
        namaError.textContent = 'Nama tidak boleh lebih dari 100 karakter';
        namaError.classList.remove('hidden');
    } else {
        namaError.classList.add('hidden');
    }
});

document.getElementById('email').addEventListener('input', function() {
    const emailError = document.getElementById('emailError');
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(this.value)) {
        emailError.textContent = 'Format email tidak valid';
        emailError.classList.remove('hidden');
    } else {
        emailError.classList.add('hidden');
    }
});
</script>