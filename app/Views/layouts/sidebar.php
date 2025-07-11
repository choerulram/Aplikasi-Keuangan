<aside class="bg-light border-r border-gray-200 shadow-md min-h-screen w-60 fixed top-0 left-0 pt-20 px-4 z-10">
    <!-- User Name Display Simple, no icon, no border, only underline -->
    <div class="mb-0 px-0 py-6">
        <span class="font-bold text-main text-base truncate block px-4">
            <?= esc(session('nama')) ?>
        </span>
        <hr class="mt-4 border-b border-gray-300 w-full">
    </div>
    <ul class="space-y-2">
        <li><a href="/" class="flex items-center gap-2 block py-2 px-3 rounded transition font-semibold <?= url_is('/') ? 'bg-highlight text-main' : 'hover:bg-highlight text-main' ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9.75L12 4l9 5.75V19a2 2 0 01-2 2H5a2 2 0 01-2-2V9.75z"/><path stroke-linecap="round" stroke-linejoin="round" d="M9 22V12h6v10"/></svg>
            Dashboard
        </a></li>
        <li><a href="/accounts" class="flex items-center gap-2 block py-2 px-3 rounded transition font-semibold <?= url_is('accounts*') ? 'bg-highlight text-main' : 'hover:bg-highlight text-dark hover:text-main' ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="3"/><path d="M16 3v4"/><path d="M8 3v4"/><path d="M2 11h20"/></svg>
            Akun
        </a></li>
        <li x-data="{ openTrans: <?= url_is('transactions/income*') || url_is('transactions/expense*') ? 'true' : 'false' ?> }" class="relative">
            <button
                @click="!('<?= url_is('transactions/income*') || url_is('transactions/expense*') ? 'true' : 'false' ?>' === 'true') ? (openTrans = !openTrans) : null"
                :disabled="<?= url_is('transactions/income*') || url_is('transactions/expense*') ? 'true' : 'false' ?>"
                type="button"
                class="flex items-center gap-2 w-full py-2 px-3 rounded transition font-semibold <?= url_is('transactions/income*') || url_is('transactions/expense*') ? 'hover:bg-highlight text-dark hover:text-main' : (url_is('transactions*') ? 'bg-highlight text-main' : 'hover:bg-highlight text-dark hover:text-main') ?> <?= url_is('transactions/income*') || url_is('transactions/expense*') ? 'cursor-default' : '' ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 10l4-4m0 0l-4-4m4 4H7"/><path stroke-linecap="round" stroke-linejoin="round" d="M7 14l-4 4m0 0l4 4m-4-4h14"/></svg>
                Transaksi
                <svg class="w-4 h-4 ml-auto transition-transform" :class="{'rotate-180': openTrans}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <ul x-show="openTrans" @click.away="<?= url_is('transactions/income*') || url_is('transactions/expense*') ? '' : 'openTrans = false' ?>" class="pl-4 py-1 space-y-1 static left-0 w-full z-20 mb-2">
                <li>
                    <a href="/transactions/income"
                        class="block py-2 px-3 rounded transition font-normal <?= url_is('transactions/income*') ? 'bg-highlight text-main' : 'hover:bg-highlight text-dark hover:text-main' ?>">
                        Pemasukan
                    </a>
                </li>
                <li>
                    <a href="/transactions/expense"
                        class="block py-2 px-3 rounded transition font-normal <?= url_is('transactions/expense*') ? 'bg-highlight text-main' : 'hover:bg-highlight text-dark hover:text-main' ?>">
                        Pengeluaran
                    </a>
                </li>
            </ul>
        </li>
        <li x-data="{ open: <?= url_is('categories/income*') || url_is('categories/expense*') ? 'true' : 'false' ?> }" class="relative">
            <button
                @click="!('<?= url_is('categories/income*') || url_is('categories/expense*') ? 'true' : 'false' ?>' === 'true') ? (open = !open) : null"
                :disabled="<?= url_is('categories/income*') || url_is('categories/expense*') ? 'true' : 'false' ?>"
                type="button"
                class="flex items-center gap-2 w-full py-2 px-3 rounded transition font-semibold <?= url_is('categories/income*') || url_is('categories/expense*') ? 'hover:bg-highlight text-dark hover:text-main' : (url_is('categories*') ? 'bg-highlight text-main' : 'hover:bg-highlight text-dark hover:text-main') ?> <?= url_is('categories/income*') || url_is('categories/expense*') ? 'cursor-default' : '' ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7a2 2 0 012-2h4l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 7V5a2 2 0 012-2h6a2 2 0 012 2v2" opacity=".5"/>
                </svg>
                Kategori
                <svg class="w-4 h-4 ml-auto transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <ul x-show="open" @click.away="<?= url_is('categories/income*') || url_is('categories/expense*') ? '' : 'open = false' ?>" class="pl-4 py-1 space-y-1 static left-0 w-full z-20 mb-2">
                <li>
                    <a href="/categories/income"
                        class="block py-2 px-3 rounded transition font-normal <?= url_is('categories/income*') ? 'bg-highlight text-main' : 'hover:bg-highlight text-dark hover:text-main' ?>">
                        Pemasukan
                    </a>
                </li>
                <li>
                    <a href="/categories/expense"
                        class="block py-2 px-3 rounded transition font-normal <?= url_is('categories/expense*') ? 'bg-highlight text-main' : 'hover:bg-highlight text-dark hover:text-main' ?>">
                        Pengeluaran
                    </a>
                </li>
            </ul>
        </li>
        <li><a href="/reports" class="flex items-center gap-2 block py-2 px-3 rounded transition font-semibold <?= url_is('reports*') ? 'bg-highlight text-main' : 'hover:bg-highlight text-dark hover:text-main' ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <rect x="3.5" y="5" width="5" height="14" rx="1"/>
                <rect x="10" y="9" width="5" height="10" rx="1"/>
                <rect x="16.5" y="12" width="5" height="6" rx="1"/>
                <rect x="3" y="18.5" width="18.5" height="0.5" rx="1"/>
            </svg>
            Laporan
        </a></li>
        <?php if (session('role') === 'admin'): ?>
        <li><a href="/users" class="flex items-center gap-2 block py-2 px-3 rounded transition font-semibold <?= url_is('users*') ? 'bg-highlight text-main' : 'hover:bg-highlight text-dark hover:text-main' ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 12c2.67 0 8 1.34 8 4v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2c0-2.66 5.33-4 8-4z"/>
                <circle cx="12" cy="7" r="4"/>
            </svg>
            User
        </a></li>
        <?php endif; ?>
        <li><a href="/settings" class="flex items-center gap-2 block py-2 px-3 rounded transition font-semibold <?= url_is('settings*') ? 'bg-highlight text-main' : 'hover:bg-highlight text-dark hover:text-main' ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 01-2.83 2.83l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09a1.65 1.65 0 00-1-1.51 1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06a1.65 1.65 0 00.33-1.82 1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09a1.65 1.65 0 001.51-1 1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06a1.65 1.65 0 001.82.33h.09a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51h.09a1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06a1.65 1.65 0 00-.33 1.82v.09a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/></svg>
            Pengaturan
        </a></li>
    </ul>
</aside>
