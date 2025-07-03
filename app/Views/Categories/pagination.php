<?php
$group = 'categories';
$currentPage = $pager->getCurrentPage($group);
$pageCount = $pager->getPageCount($group);

// Window pagination logic: tampilkan maksimal 5 nomor, dan jika di halaman 6 dst, window bergeser
$window = 5;
if ($pageCount <= $window) {
    $start = 1;
    $end = $pageCount;
} else {
    if ($currentPage <= $window) {
        $start = 1;
        $end = $window;
    } else {
        $end = $currentPage;
        $start = max(1, $end - $window + 1);
    }
}
?>
<div class="flex items-center justify-center gap-1">
    <!-- First -->
    <a href="<?= $pager->getPageURI(1, $group) ?>"
       class="inline-flex items-center px-3 py-1 rounded-l border border-gray-300 <?= $currentPage == 1 ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white text-gray-700 hover:bg-main hover:text-white' ?> transition"
       title="Halaman Pertama"
       <?= $currentPage == 1 ? 'tabindex="-1" aria-disabled="true"' : '' ?>>
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 19l-7-7 7-7M5 12h14"/></svg>
        Pertama
    </a>
    <!-- Prev -->
    <a href="<?= $pager->getPageURI($currentPage - 1, $group) ?>"
       class="inline-flex items-center px-3 py-1 border-t border-b border-gray-300 <?= $currentPage == 1 ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white text-gray-700 hover:bg-main hover:text-white' ?> transition"
       title="Sebelumnya"
       <?= $currentPage == 1 ? 'tabindex="-1" aria-disabled="true"' : '' ?>>
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        Sebelumnya
    </a>
    <!-- Page Numbers -->
    <?php for ($i = $start; $i <= $end; $i++): ?>
        <a href="<?= $pager->getPageURI($i, $group) ?>"
           class="inline-flex items-center px-3 py-1 border-t border-b border-gray-300 <?= ($i == $currentPage) ? 'bg-main text-white font-bold' : 'bg-white text-gray-700 hover:bg-main hover:text-white' ?> transition"
           style="min-width:2.5rem; justify-content:center;">
            <?= $i ?>
        </a>
    <?php endfor; ?>
    <!-- Next -->
    <a href="<?= $pager->getPageURI($currentPage + 1, $group) ?>"
       class="inline-flex items-center px-3 py-1 border-t border-b border-gray-300 <?= $currentPage == $pageCount ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white text-gray-700 hover:bg-main hover:text-white' ?> transition"
       title="Berikutnya"
       <?= $currentPage == $pageCount ? 'tabindex="-1" aria-disabled="true"' : '' ?>>
        Berikutnya
        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
    </a>
    <!-- Last -->
    <a href="<?= $pager->getPageURI($pageCount, $group) ?>"
       class="inline-flex items-center px-3 py-1 rounded-r border border-gray-300 <?= $currentPage == $pageCount ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white text-gray-700 hover:bg-main hover:text-white' ?> transition"
       title="Halaman Terakhir"
       <?= $currentPage == $pageCount ? 'tabindex="-1" aria-disabled="true"' : '' ?>>
        Terakhir
        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5l7 7-7 7M5 12h14"/></svg>
    </a>
</div>
