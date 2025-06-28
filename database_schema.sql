-- SQL untuk struktur database aplikasi keuangan multi user

-- Tabel Users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    nama VARCHAR(100),
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel Accounts (Akun/Wallet)
CREATE TABLE accounts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    nama_akun VARCHAR(100) NOT NULL,
    tipe_akun VARCHAR(50),
    saldo_awal DECIMAL(18,2) DEFAULT 0,
    catatan TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Tabel Categories (Kategori)
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    nama_kategori VARCHAR(100) NOT NULL,
    tipe ENUM('income', 'expense') NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Tabel Transactions (Transaksi)
CREATE TABLE transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    account_id INT NOT NULL,
    category_id INT NOT NULL,
    tipe ENUM('income', 'expense') NOT NULL,
    jumlah DECIMAL(18,2) NOT NULL,
    tanggal DATE NOT NULL,
    deskripsi TEXT,
    lampiran VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (account_id) REFERENCES accounts(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- Tabel Budgets (Anggaran)
CREATE TABLE budgets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    category_id INT NOT NULL,
    jumlah_anggaran DECIMAL(18,2) NOT NULL,
    periode VARCHAR(7) NOT NULL, -- format: YYYY-MM
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- Tabel Settings (Pengaturan)
CREATE TABLE settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    tema VARCHAR(50) DEFAULT 'default',
    bahasa VARCHAR(10) DEFAULT 'id',
    preferensi_lain TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- DATA DUMMY UNTUK SETIAP TABEL

-- Users
INSERT INTO users (id, username, email, password_hash, nama, role, created_at, updated_at) VALUES
(1, 'admin', 'admin@mail.com', '$2y$10$adminhash', 'Administrator', 'admin', NOW(), NOW()),
(2, 'budi', 'budi@mail.com', '$2y$10$budihash', 'Budi Santoso', 'user', NOW(), NOW()),
(3, 'sari', 'sari@mail.com', '$2y$10$sarihash', 'Sari Dewi', 'user', NOW(), NOW());

-- Accounts
INSERT INTO accounts (id, user_id, nama_akun, tipe_akun, saldo_awal, catatan, created_at, updated_at) VALUES
(1, 1, 'Kas Utama', 'cash', 1000000, 'Kas kantor utama', NOW(), NOW()),
(2, 2, 'Rekening BCA', 'bank', 2500000, 'Tabungan pribadi Budi', NOW(), NOW()),
(3, 3, 'E-Wallet OVO', 'ewallet', 500000, 'Saldo OVO Sari', NOW(), NOW());

-- Categories
INSERT INTO categories (id, user_id, nama_kategori, tipe, created_at, updated_at) VALUES
(1, 1, 'Gaji', 'income', NOW(), NOW()),
(2, 2, 'Makan', 'expense', NOW(), NOW()),
(3, 3, 'Transportasi', 'expense', NOW(), NOW());

-- Transactions
INSERT INTO transactions (id, user_id, account_id, category_id, tipe, jumlah, tanggal, deskripsi, lampiran, created_at, updated_at) VALUES
(1, 1, 1, 1, 'income', 5000000, '2025-06-01', 'Gaji bulan Juni', NULL, NOW(), NOW()),
(2, 2, 2, 2, 'expense', 75000, '2025-06-02', 'Makan siang', NULL, NOW(), NOW()),
(3, 3, 3, 3, 'expense', 20000, '2025-06-03', 'Naik ojek', NULL, NOW(), NOW());

-- Budgets
INSERT INTO budgets (id, user_id, category_id, jumlah_anggaran, periode, created_at, updated_at) VALUES
(1, 1, 1, 5000000, '2025-06', NOW(), NOW()),
(2, 2, 2, 1000000, '2025-06', NOW(), NOW()),
(3, 3, 3, 300000, '2025-06', NOW(), NOW());

-- Settings
INSERT INTO settings (id, user_id, tema, bahasa, preferensi_lain, created_at, updated_at) VALUES
(1, 1, 'dark', 'id', '{"notif":true}', NOW(), NOW()),
(2, 2, 'default', 'id', '{"notif":false}', NOW(), NOW()),
(3, 3, 'light', 'en', '{"notif":true}', NOW(), NOW());
