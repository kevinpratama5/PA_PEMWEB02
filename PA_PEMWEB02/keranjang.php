<?php
session_start();
include 'db.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil data keranjang
$query = "SELECT keranjang.id_keranjang, produk.nama, produk.harga, produk.url_gambar, keranjang.jumlah 
          FROM keranjang
          JOIN produk ON keranjang.id_produk = produk.id_produk
          WHERE keranjang.id_pengguna = '$user_id'";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang - Toko Gelato</title>
    <link rel="stylesheet" href="styles/beranda.css">
    <link rel="stylesheet" href="styles/keranjang.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="logo">
            <img src="images/logo.png" alt="Logo Toko Gelato">
        </div>

        <div class="link-nav">
            <a href="index.php">Home</a>
            <a href="index.php#about">About</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Masuk</a>
                <a href="register.php">Daftar</a>
            <?php endif; ?>
        </div>

        <div class="hamburger-menu" id="hamburger-menu">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </nav>

    <div class="kontainer">
        <h2>Keranjang Belanja</h2>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="keranjang-wrapper">
                <table class="keranjang-table">
                    <thead>
                        <tr>
                            <th style="color:black;">Gambar</th>
                            <th style="color:black;">Nama Produk</th>
                            <th style="color:black;">Harga</th>
                            <th style="color:black;">Jumlah</th>
                            <th style="color:black;">Subtotal</th>
                            <th style="color:black;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total = 0;
                        while ($item = mysqli_fetch_assoc($result)) :
                            $subtotal = $item['harga'] * $item['jumlah'];
                            $total += $subtotal;
                        ?>
                            <tr>
                                <td><img src="<?php echo htmlspecialchars($item['url_gambar']); ?>" alt="<?php echo htmlspecialchars($item['nama']); ?>" class="produk-img"></td>
                                <td><?php echo htmlspecialchars($item['nama']); ?></td>
                                <td>Rp <?php echo number_format($item['harga'], 0, ',', '.'); ?></td>
                                <td><?php echo $item['jumlah']; ?></td>
                                <td>Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></td>
                                <td>
                                    <form method="POST" action="hapus_dari_keranjang.php">
                                        <input type="hidden" name="id_keranjang" value="<?php echo $item['id_keranjang']; ?>">
                                        <button type="submit" class="tombol-hapus">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <div class="ringkasan-order">
                    <h3>Ringkasan Order</h3>
                    <div class="total-harga">
                        <span>Total:</span>
                        <span>Rp <?php echo number_format($total, 0, ',', '.'); ?></span>
                    </div>
                    <a href="checkout.php" class="tombol-checkout">Lanjut ke Checkout</a>
                    <a href="index.php" class="tombol-lanjut">Lanjut Belanja</a>
                </div>
            </div>
        <?php else: ?>
            <div class="keranjang-kosong">
                <p>Keranjang Anda kosong.</p>
                <a href="index.php" class="tombol-lanjut">Belanja Sekarang</a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Tentang Kami</h3>
                <p>Toko Gelato menyajikan gelato Italia autentik dengan bahan-bahan premium.</p>
            </div>
            <div class="footer-section">
                <h3>Kontak</h3>
                <p>Email: info@tokogelato.com</p>
                <p>Telepon: (021) 1234-5678</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 Toko Gelato. Hak Cipta Dilindungi.</p>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>
