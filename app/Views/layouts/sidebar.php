<aside class="bg-light border-r border-gray-200 shadow-md min-h-screen w-60 fixed top-0 left-0 pt-20 px-4 z-10">
    <ul class="space-y-2">
        <li><a href="/" class="flex items-center gap-2 block py-2 px-3 rounded transition font-semibold <?= url_is('/') ? 'bg-highlight text-main' : 'hover:bg-highlight text-main' ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 17v-2a4 4 0 014-4h10a4 4 0 014 4v2M16 3.13a4 4 0 010 7.75M8 3.13a4 4 0 000 7.75"/></svg>
            Dashboard
        </a></li>
        <li><a href="/accounts" class="flex items-center gap-2 block py-2 px-3 rounded transition font-semibold <?= url_is('accounts*') ? 'bg-highlight text-main' : 'hover:bg-highlight text-dark hover:text-main' ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a5 5 0 00-10 0v2M5 20h14a2 2 0 002-2v-5a2 2 0 00-2-2H5a2 2 0 00-2 2v5a2 2 0 002 2z"/></svg>
            Akun
        </a></li>
        <li><a href="/transactions" class="flex items-center gap-2 block py-2 px-3 rounded transition font-semibold <?= url_is('transactions*') ? 'bg-highlight text-main' : 'hover:bg-highlight text-dark hover:text-main' ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-6 0V5a3 3 0 016 0v1"/></svg>
            Transaksi
        </a></li>
        <li><a href="/categories" class="flex items-center gap-2 block py-2 px-3 rounded transition font-semibold <?= url_is('categories*') ? 'bg-highlight text-main' : 'hover:bg-highlight text-dark hover:text-main' ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7a1 1 0 011-1h3.586a1 1 0 01.707.293l7.414 7.414a1 1 0 010 1.414l-5.586 5.586a1 1 0 01-1.414 0L4.293 13.707a1 1 0 010-1.414L7 7zm0 0V3a1 1 0 011-1h3.586a1 1 0 01.707.293l7.414 7.414a1 1 0 010 1.414l-5.586 5.586a1 1 0 01-1.414 0L4.293 13.707a1 1 0 010-1.414L7 7z"/></svg>
            Kategori
        </a></li>
        <li><a href="/reports" class="flex items-center gap-2 block py-2 px-3 rounded transition font-semibold <?= url_is('reports*') ? 'bg-highlight text-main' : 'hover:bg-highlight text-dark hover:text-main' ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0h6"/></svg>
            Laporan
        </a></li>
        <li><a href="/users" class="flex items-center gap-2 block py-2 px-3 rounded transition font-semibold <?= url_is('users*') ? 'bg-highlight text-main' : 'hover:bg-highlight text-dark hover:text-main' ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M16 3.13a4 4 0 010 7.75M8 3.13a4 4 0 000 7.75"/></svg>
            User
        </a></li>
        <li><a href="/settings" class="flex items-center gap-2 block py-2 px-3 rounded transition font-semibold <?= url_is('settings*') ? 'bg-highlight text-main' : 'hover:bg-highlight text-dark hover:text-main' ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0a1.724 1.724 0 002.573 1.01c.797-.46 1.757.3 1.516 1.176a1.724 1.724 0 002.293 2.293c.876-.241 1.636.719 1.176 1.516a1.724 1.724 0 001.01 2.573c.921.3.921 1.603 0 1.902a1.724 1.724 0 00-1.01 2.573c.46.797-.3 1.757-1.176 1.516a1.724 1.724 0 00-2.293 2.293c.241.876-.719 1.636-1.516 1.176a1.724 1.724 0 00-2.573 1.01c-.3.921-1.603.921-1.902 0a1.724 1.724 0 00-2.573-1.01c-.797.46-1.757-.3-1.516-1.176a1.724 1.724 0 00-2.293-2.293c-.876.241-1.636-.719-1.176-1.516a1.724 1.724 0 00-1.01-2.573c-.921-.3-.921-1.603 0-1.902a1.724 1.724 0 001.01-2.573c-.46-.797.3-1.757 1.176-1.516a1.724 1.724 0 002.293-2.293c-.241-.876.719-1.636 1.516-1.176a1.724 1.724 0 002.573-1.01z"/></svg>
            Pengaturan
        </a></li>
    </ul>
</aside>
