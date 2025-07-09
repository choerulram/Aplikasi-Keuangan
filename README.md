# Aplikasi-Keuangan

Aplikasi Keuangan Sederhana untuk mencatat pemasukan, pengeluaran, dan mengelola keuangan pribadi atau organisasi kecil.

## Teknologi yang Digunakan

- **Backend:** PHP (CodeIgniter 4)
- **Frontend:** HTML, Tailwind CSS
- **Build Tool:** Tailwind CLI, PostCSS
- **Package Manager:** npm (Node.js), Composer (PHP)
- **Database:** (Silakan sesuaikan di file konfigurasi, default mendukung MySQL/MariaDB)

## Fitur Utama

- Manajemen akun keuangan
- Kategori pemasukan & pengeluaran
- Pencatatan transaksi
- Laporan keuangan
- Pengaturan pengguna

## Cara Menjalankan Project di Lokal

1. **Install dependency Node.js (untuk Tailwind CSS):**

   ```bash
   npm install
   ```

2. **Jalankan proses Tailwind CSS (harus tetap berjalan selama development):**

   ```bash
   npm run dev:css
   ```

3. **Install dependency PHP (Composer):**

   ```bash
   composer install
   ```

4. **Jalankan server CodeIgniter:**
   ```bash
   php spark serve
   ```

---

**Catatan:**

- Jalankan `npm run dev:css` di terminal terpisah agar proses Tailwind CSS tetap berjalan selama development.
- Pastikan sudah menginstall Node.js, npm, Composer, dan PHP di komputer Anda.
