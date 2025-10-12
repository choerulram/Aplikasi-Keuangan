<!-- Backdrop for mobile sidebar -->
<div id="sidebarBackdrop" class="fixed inset-0 bg-black bg-opacity-40 z-30 hidden md:hidden"></div>

<!-- Sidebar: off-canvas on mobile, static on md+ -->
<aside id="mainSidebar" class="bg-light border-r border-gray-200 shadow-md min-h-screen w-60 fixed top-0 left-0 pt-20 px-4 z-40 transform -translate-x-full transition-transform duration-200 md:translate-x-0 md:fixed md:shadow-none md:w-60 md:z-10">
    <!-- User Name Display Simple, no icon, no border, only underline -->
    <!-- Mobile close button -->
    <div class="absolute top-3 right-3 md:hidden">
        <button id="sidebarCloseBtn" aria-label="Tutup sidebar" class="p-2 rounded-md text-gray-600 hover:bg-gray-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
    <div class="mb-0 px-0 py-6">
        <span class="font-bold text-main text-base truncate block px-4">
            <?= esc(session('nama')) ?>
        </span>
        <hr class="mt-4 border-b border-gray-300 w-full">
    </div>
    <?= $this->include('layouts/menu') ?>
</aside>
