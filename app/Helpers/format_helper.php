<?php
// ...existing code...
/**
 * Helper to format currency
 */
if (!function_exists('format_rupiah')) {
    function format_rupiah($angka) {
        return 'Rp ' . number_format($angka, 2, ',', '.');
    }
}
// ...existing code...
