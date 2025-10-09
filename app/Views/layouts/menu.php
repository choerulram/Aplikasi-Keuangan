<?php /**
 * Shared menu partial used by sidebar (desktop) and mobile panel (mobile)
 */ ?>
<ul class="space-y-2">
    <li><a href="/" class="flex items-center gap-2 block py-2 px-3 rounded transition font-semibold <?= url_is('/') ? 'bg-highlight text-main' : 'hover:bg-highlight text-main' ?>">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9.75L12 4l9 5.75V19a2 2 0 01-2 2H5a2 2 0 01-2-2V9.75z"/><path stroke-linecap="round" stroke-linejoin="round" d="M9 22V12h6v10"/></svg>
        Dashboard
    </a></li>
    <li><a href="/accounts" class="flex items-center gap-2 block py-2 px-3 rounded transition font-semibold <?= url_is('accounts*') ? 'bg-highlight text-main' : 'hover:bg-highlight text-dark hover:text-main' ?>">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="3"/><path d="M16 3v4"/><path d="M8 3v4"/><path d="M2 11h20"/></svg>
        Akun
    </a></li>
    <li x-data="{ openBudget: <?= url_is('budgets/income*') || url_is('budgets/expense*') ? 'true' : 'false' ?> }" class="relative">
        <button type="button" @click="openBudget = !openBudget" class="flex items-start gap-2 w-full py-2 px-3 rounded transition font-semibold text-left <?= url_is('budgets/income*') || url_is('budgets/expense*') ? 'hover:bg-highlight text-dark hover:text-main' : (url_is('budgets*') ? 'bg-highlight text-main' : 'hover:bg-highlight text-dark hover:text-main') ?>">
            <svg class="w-5 h-5 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="flex-grow text-left">Manajemen Anggaran</span>
            <svg class="w-4 h-4 ml-auto transition-transform" :class="{'rotate-180': openBudget}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        <ul x-show="openBudget" class="pl-4 py-1 space-y-1 static left-0 w-full z-20 mb-2">
            <li><a href="/budgets/income" class="block py-2 px-3 rounded transition font-normal <?= url_is('budgets/income*') ? 'bg-highlight text-main' : 'hover:bg-highlight text-dark hover:text-main' ?>">Perencanaan Pendapatan</a></li>
            <li><a href="/budgets/expense" class="block py-2 px-3 rounded transition font-normal <?= url_is('budgets/expense*') ? 'bg-highlight text-main' : 'hover:bg-highlight text-dark hover:text-main' ?>">Pelacakan Pengeluaran</a></li>
        </ul>
    </li>
    <!-- ... the rest of the menu items copied from sidebar ... -->
    <li x-data="{ open: <?= url_is('categories/income*') || url_is('categories/expense*') ? 'true' : 'false' ?> }" class="relative">
        <button type="button" @click="open = !open" class="flex items-center gap-2 w-full py-2 px-3 rounded transition font-semibold <?= url_is('categories/income*') || url_is('categories/expense*') ? 'hover:bg-highlight text-dark hover:text-main' : (url_is('categories*') ? 'bg-highlight text-main' : 'hover:bg-highlight text-dark hover:text-main') ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7a2 2 0 012-2h4l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M7 7V5a2 2 0 012-2h6a2 2 0 012 2v2" opacity=".5"/>
            </svg>
            Kategori
            <svg class="w-4 h-4 ml-auto transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        <ul x-show="open" class="pl-4 py-1 space-y-1 static left-0 w-full z-20 mb-2">
            <li><a href="/categories/income" class="block py-2 px-3 rounded transition font-normal <?= url_is('categories/income*') ? 'bg-highlight text-main' : 'hover:bg-highlight text-dark hover:text-main' ?>">Pemasukan</a></li>
            <li><a href="/categories/expense" class="block py-2 px-3 rounded transition font-normal <?= url_is('categories/expense*') ? 'bg-highlight text-main' : 'hover:bg-highlight text-dark hover:text-main' ?>">Pengeluaran</a></li>
        </ul>
    </li>
    <li x-data="{ openTrans: <?= url_is('transactions/income*') || url_is('transactions/expense*') ? 'true' : 'false' ?> }" class="relative">
        <button type="button" @click="openTrans = !openTrans" class="flex items-center gap-2 w-full py-2 px-3 rounded transition font-semibold <?= url_is('transactions/income*') || url_is('transactions/expense*') ? 'hover:bg-highlight text-dark hover:text-main' : (url_is('transactions*') ? 'bg-highlight text-main' : 'hover:bg-highlight text-dark hover:text-main') ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 10l4-4m0 0l-4-4m4 4H7"/><path stroke-linecap="round" stroke-linejoin="round" d="M7 14l-4 4m0 0l4 4m-4-4h14"/></svg>
            Transaksi
            <svg class="w-4 h-4 ml-auto transition-transform" :class="{'rotate-180': openTrans}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        <ul x-show="openTrans" class="pl-4 py-1 space-y-1 static left-0 w-full z-20 mb-2">
            <li><a href="/transactions/income" class="block py-2 px-3 rounded transition font-normal <?= url_is('transactions/income*') ? 'bg-highlight text-main' : 'hover:bg-highlight text-dark hover:text-main' ?>">Pemasukan</a></li>
            <li><a href="/transactions/expense" class="block py-2 px-3 rounded transition font-normal <?= url_is('transactions/expense*') ? 'bg-highlight text-main' : 'hover:bg-highlight text-dark hover:text-main' ?>">Pengeluaran</a></li>
        </ul>
    </li>
    <li x-data="{ openReport: <?= url_is('reports/cashflow*') || url_is('reports/budget*') || url_is('reports/category*') || url_is('reports/account*') || url_is('reports/trend*') ? 'true' : 'false' ?> }" class="relative">
        <button type="button" @click="openReport = !openReport" class="flex items-start gap-2 w-full py-2 px-3 rounded transition font-semibold text-left <?= url_is('reports/cashflow*') || url_is('reports/budget*') || url_is('reports/category*') || url_is('reports/account*') || url_is('reports/trend*') ? 'hover:bg-highlight text-dark hover:text-main' : (url_is('reports*') ? 'bg-highlight text-main' : 'hover:bg-highlight text-dark hover:text-main') ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <rect x="3.5" y="5" width="5" height="14" rx="1"/>
                <rect x="10" y="9" width="5" height="10" rx="1"/>
                <rect x="16.5" y="12" width="5" height="6" rx="1"/>
                <rect x="3" y="18.5" width="18.5" height="0.5" rx="1"/>
            </svg>
            <span class="flex-grow text-left">Laporan</span>
            <svg class="w-4 h-4 ml-auto transition-transform" :class="{'rotate-180': openReport}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        <ul x-show="openReport" class="pl-4 py-1 space-y-1 static left-0 w-full z-20 mb-2">
            <li><a href="/reports/cashflow" class="block py-2 px-3 rounded transition font-normal <?= url_is('reports/cashflow*') ? 'bg-highlight text-main' : 'hover:bg-highlight text-dark hover:text-main' ?>">Arus Kas</a></li>
            <li><a href="/reports/budget" class="block py-2 px-3 rounded transition font-normal <?= url_is('reports/budget*') ? 'bg-highlight text-main' : 'hover:bg-highlight text-dark hover:text-main' ?>">Budget vs Aktual</a></li>
            <li><a href="/reports/category" class="block py-2 px-3 rounded transition font-normal <?= url_is('reports/category*') ? 'bg-highlight text-main' : 'hover:bg-highlight text-dark hover:text-main' ?>">Laporan per Kategori</a></li>
            <li><a href="/reports/account" class="block py-2 px-3 rounded transition font-normal <?= url_is('reports/account*') ? 'bg-highlight text-main' : 'hover:bg-highlight text-dark hover:text-main' ?>">Saldo per Akun</a></li>
            <li><a href="/reports/trend" class="block py-2 px-3 rounded transition font-normal <?= url_is('reports/trend*') ? 'bg-highlight text-main' : 'hover:bg-highlight text-dark hover:text-main' ?>">Tren Bulanan</a></li>
        </ul>
    </li>
    <?php if (session('role') === 'admin'): ?>
    <li><a href="/users" class="flex items-center gap-2 block py-2 px-3 rounded transition font-semibold <?= url_is('users*') ? 'bg-highlight text-main' : 'hover:bg-highlight text-dark hover:text-main' ?>">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 12c2.67 0 8 1.34 8 4v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2c0-2.66 5.33-4 8-4z"/>
            <circle cx="12" cy="7" r="4"/>
        </svg>
        User
    </a></li>
    <?php endif; ?>
</ul>
