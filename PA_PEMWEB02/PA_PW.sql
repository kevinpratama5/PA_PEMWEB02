-- Membuat database
CREATE DATABASE toko_gelato;
USE toko_gelato;

-- Tabel pengguna
CREATE TABLE pengguna (
    id_pengguna INT PRIMARY KEY AUTO_INCREMENT,
    nama_pengguna VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    kata_sandi VARCHAR(255) NOT NULL,
    peran ENUM('admin', 'pengguna') DEFAULT 'pengguna',
    dibuat_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel produk
CREATE TABLE produk (
    id_produk INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    harga DECIMAL(10,2) NOT NULL,
    kategori VARCHAR(50) NOT NULL,
    stok INT NOT NULL,
    url_gambar VARCHAR(255),
    dibuat_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel ulasan
CREATE TABLE ulasan (
    id_ulasan INT PRIMARY KEY AUTO_INCREMENT,
    id_produk INT,
    id_pengguna INT,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    komentar TEXT,
    dibuat_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_produk) REFERENCES produk(id_produk) ON DELETE CASCADE,
    FOREIGN KEY (id_pengguna) REFERENCES pengguna(id_pengguna) ON DELETE CASCADE
);

-- Tabel pesanan
CREATE TABLE pesanan (
    id_pesanan INT PRIMARY KEY AUTO_INCREMENT,
    id_pengguna INT,
    total_harga DECIMAL(10,2) NOT NULL,
    status ENUM('menunggu', 'dibayar', 'selesai', 'dibatalkan') DEFAULT 'menunggu',
    dibuat_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_pengguna) REFERENCES pengguna(id_pengguna) ON DELETE SET NULL
);

-- Tabel item pesanan
CREATE TABLE item_pesanan (
    id_item_pesanan INT PRIMARY KEY AUTO_INCREMENT,
    id_pesanan INT,
    id_produk INT,
    jumlah INT NOT NULL,
    harga DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_pesanan) REFERENCES pesanan(id_pesanan) ON DELETE CASCADE,
    FOREIGN KEY (id_produk) REFERENCES produk(id_produk) ON DELETE SET NULL
);

-- Tabel artikel
CREATE TABLE artikel (
    id_artikel INT PRIMARY KEY AUTO_INCREMENT,
    judul VARCHAR(255) NOT NULL,
    konten TEXT NOT NULL,
    id_penulis INT,
    dibuat_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_penulis) REFERENCES pengguna(id_pengguna) ON DELETE SET NULL
);

CREATE TABLE gambar_produk (
    id_gambar INT AUTO_INCREMENT PRIMARY KEY,
    id_produk INT NOT NULL,
    url_gambar VARCHAR(255) NOT NULL,
    FOREIGN KEY (id_produk) REFERENCES produk(id_produk) ON DELETE CASCADE
);

CREATE TABLE keranjang (
    id_keranjang INT AUTO_INCREMENT PRIMARY KEY,
    id_pengguna INT NOT NULL,
    id_produk INT NOT NULL,
    jumlah INT NOT NULL,
    FOREIGN KEY (id_pengguna) REFERENCES pengguna(id_pengguna) ON DELETE CASCADE,
    FOREIGN KEY (id_produk) REFERENCES produk(id_produk) ON DELETE CASCADE
);

CREATE TABLE favorit (
    id_favorit INT AUTO_INCREMENT PRIMARY KEY,
    id_pengguna INT NOT NULL,
    id_produk INT NOT NULL,
    FOREIGN KEY (id_pengguna) REFERENCES pengguna(id_pengguna) ON DELETE CASCADE,
    FOREIGN KEY (id_produk) REFERENCES produk(id_produk) ON DELETE CASCADE
);

CREATE TABLE riwayat_pembelian (
    id_pembelian INT AUTO_INCREMENT PRIMARY KEY,
    id_pengguna INT NOT NULL,
    id_produk INT NOT NULL,
    jumlah INT NOT NULL,
    tanggal_pembelian TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_pengguna) REFERENCES pengguna(id_pengguna) ON DELETE CASCADE,
    FOREIGN KEY (id_produk) REFERENCES produk(id_produk) ON DELETE CASCADE
);




INSERT INTO produk (nama, deskripsi, harga, url_gambar, kategori, stok) VALUES
('Gelato Mangga', 'Gelato rasa mangga segar.', 20000, 'images/gelato_mangga.jpg', 'Buah', 10),
('Gelato Keju', 'Gelato rasa keju creamy.', 22000, 'images/gelato_keju.jpg', 'Keju', 15),
('Gelato Avocado', 'Gelato rasa alpukat yang lembut.', 25000, 'images/gelato_avocado.jpg', 'Buah', 20),
('Gelato Banana', 'Gelato pisang yang manis.', 20000, 'images/gelato_banana.jpg', 'Buah', 18),
('Gelato Japanese Matcha', 'Gelato matcha khas Jepang.', 28000, 'images/gelato_japanese_matcha.jpg', 'Matcha', 12),
('Gelato Vanila', 'Gelato vanila klasik.', 18000, 'images/gelato_vanila.jpg', 'Klasik', 25),
('Gelato Black Forest', 'Gelato black forest dengan ceri.', 30000, 'images/gelato_blackforet.jpg', 'Dessert', 10),
('Gelato Bubblegum', 'Gelato rasa permen karet.', 21000, 'images/gelato_bubblegum.jpg', 'Permen', 20),
('Gelato Strawberry', 'Gelato strawberry segar.', 22000, 'images/gelato_strawberry.jpg', 'Buah', 15),
('Gelato kopi', 'Gelato rasa kopi kuat.', 17000, 'images/gelato_kopi.jpg', 'kopi', 20),
('Gelato cookies and cream', 'Gelato rasa cookies and cream.', 29000, 'images/gelato_cookies_and_cream.jpg', 'Cookies', 15),
('gelato Stracciatella', 'gelato Stracciatella premium.', 30000, 'images/gelato_Stracciatella.jpg', 'Stracciatella', 10);



SELECT produk.id_produk, produk.nama, produk.deskripsi, produk.harga, gambar_produk.url_gambar
FROM produk
JOIN gambar_produk ON produk.id_produk = gambar_produk.id_produk;

