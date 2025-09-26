<header class="bg-light shadow-md px-6 py-4 flex items-center justify-between border-b border-gray-200 fixed top-0 left-0 right-0 z-30">
    <div class="flex items-center gap-6">
        <h2 class="text-3xl font-extrabold tracking-wide text-main bg-gradient-to-r from-main via-[#A7F3D0] to-main bg-clip-text text-transparent animate-shimmer drop-shadow-lg shimmer-title">
            Aplikasi Keuangan
        </h2>
        <span class="text-lg font-semibold text-main hidden sm:inline-block">|</span>
        <span class="text-xl font-bold text-main drop-shadow-sm hidden md:inline-block"> <?= esc($pageTitle ?? 'Dashboard Sistem') ?> </span>
    </div>
    <div class="flex items-center">
        <!-- Profile Dropdown -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" type="button" class="flex items-center gap-3 py-2 px-3 rounded-lg hover:bg-gray-100 transition focus:outline-none">
                <div class="w-8 h-8 rounded-full bg-main text-white flex items-center justify-center font-bold">
                    <?= strtoupper(substr(session('nama'), 0, 1)) ?>
                </div>
                <div class="hidden sm:block text-left">
                    <div class="font-semibold text-gray-800"><?= esc(session('nama')) ?></div>
                    <div class="text-sm text-gray-500"><?= ucfirst(session('role')) ?></div>
                </div>
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <!-- Dropdown Menu -->
            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 border border-gray-100">
                <a href="/profile" class="flex items-center gap-3 px-4 py-2 text-gray-700 hover:bg-gray-50 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Profil
                </a>
                <hr class="my-1 border-gray-200">
                <button id="logoutBtn" class="flex w-full items-center gap-3 px-4 py-2 text-red-600 hover:bg-red-50 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-6 0V5a3 3 0 016 0v1"></path>
                    </svg>
                    Keluar
                </button>
            </div>
        </div>
    </div>

<!-- Modal Logout -->
<div id="logoutModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden transition-opacity duration-200">
  <div class="bg-white rounded-xl shadow-2xl w-full max-w-sm p-7 relative border-t-4 border-red-500 animate-fadeIn">
    <button onclick="document.getElementById('logoutModal').classList.add('hidden')" class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 focus:outline-none">
      <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
    <div class="flex flex-col items-center text-center">
      <span class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-100 text-red-600 mb-4">
        <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-6 0V5a3 3 0 016 0v1" />
        </svg>
      </span>
      <h3 class="text-xl font-bold text-red-600 mb-2">Keluar Aplikasi?</h3>
      <p class="text-gray-700 mb-4">Anda yakin ingin keluar dari aplikasi?</p>
      <div class="flex gap-3 justify-center mt-2 w-full">
        <a href="/logout" class="flex-1 inline-block text-center px-4 py-2 rounded-lg bg-red-600 text-white font-semibold hover:bg-red-700 transition">Yakin Keluar</a>
        <button id="cancelLogout" type="button" class="flex-1 px-4 py-2 rounded-lg bg-gray-200 text-gray-700 font-semibold hover:bg-gray-300 transition">Batal</button>
      </div>
    </div>
  </div>
    </div>
</header>
<style>
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(30px); }
  to { opacity: 1; transform: translateY(0); }
}
.animate-fadeIn { animation: fadeIn 0.3s ease; }
@keyframes shimmer {
  0% {
    background-position: -200px 0;
  }
  100% {
    background-position: 200px 0;
  }
}
.animate-shimmer {
  background-size: 120% auto;
  animation: shimmer 3.5s linear infinite;
}
.shimmer-title {
  text-shadow: 0 2px 8px rgba(167, 243, 208, 0.18), 0 1px 2px rgba(8, 71, 52, 0.12);
}
</style>
<script>
  const logoutBtn = document.getElementById('logoutBtn');
  const logoutModal = document.getElementById('logoutModal');
  const cancelLogout = document.getElementById('cancelLogout');
  if (logoutBtn && logoutModal && cancelLogout) {
    logoutBtn.addEventListener('click', function(e) {
      e.preventDefault();
      logoutModal.classList.remove('hidden');
    });
    cancelLogout.addEventListener('click', function() {
      logoutModal.classList.add('hidden');
    });
    // Optional: close modal on ESC
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') logoutModal.classList.add('hidden');
    });
  }
</script>
