<header class="bg-light shadow-md px-6 py-4 flex items-center justify-between border-b border-gray-200 fixed top-0 left-0 right-0 z-30 ml-60">
    <h2 class="text-2xl font-bold text-main">Aplikasi Keuangan</h2>
    <div class="flex items-center gap-4">
        <span class="text-dark text-sm"><?= esc($pageTitle ?? 'Dashboard Sistem') ?></span>
        <a href="/logout" class="inline-flex items-center gap-2 px-4 py-2 rounded bg-red-500 hover:bg-red-600 text-white font-bold shadow-lg transition">
            Logout
        </a>
    </div>
</header>
