<?php
require_once "db.php";
require_once "otentikasi.php";

$user_name = '';
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $query = "SELECT nama_pengguna FROM pengguna WHERE id_pengguna = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($result)) {
        $user_name = $row['nama_pengguna'];
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Gelato</title>
    <link rel="stylesheet" href="styles/beranda.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="logo">
            <img src="images/logo.png" alt="Logo Toko Gelato">
        </div>

        <?php if (isset($_SESSION['user_id'])): ?>
            <span style="color: #fff; font-weight: bold; margin: auto; font-size: 24px;">
                Halo, <?php echo htmlspecialchars($user_name); ?>
            </span>
        <?php endif; ?>

        <div class="link-nav">
            <a href="#home">Home</a>
            <a href="#about">About</a>
            <a href="#guide">Panduan</a>
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

    <!-- Banner -->
    <div id="home" class="banner">
        <h2>Selamat Datang di Toko Gelato</h2>
        <p>Nikmati kelezatan gelato Italia autentik dengan berbagai pilihan rasa</p>
    </div>

    <!-- Pencarian Produk -->
    <div class="pencarian">
        <input type="text" id="input-pencarian" placeholder="Cari produk...">
    </div>

    <!-- Filter dan Sorting Produk -->
    <div class="filter-section">
        <form method="GET" action="index.php">
            <!-- Filter Kategori -->
            <select name="kategori" onchange="this.form.submit()">
                <option value="">Semua Kategori</option>
                <option value="Buah" <?php if (isset($_GET['kategori']) && $_GET['kategori'] == 'Buah') echo 'selected'; ?>>Buah</option>
                <option value="Dessert" <?php if (isset($_GET['kategori']) && $_GET['kategori'] == 'Dessert') echo 'selected'; ?>>Dessert</option>
                <option value="Permen" <?php if (isset($_GET['kategori']) && $_GET['kategori'] == 'Permen') echo 'selected'; ?>>Permen</option>
            </select>
            
            <!-- Sortir Harga -->
            <select name="sortir_harga" onchange="this.form.submit()">
                <option value="">Urutkan Harga</option>
                <option value="murah" <?php if (isset($_GET['sortir_harga']) && $_GET['sortir_harga'] == 'murah') echo 'selected'; ?>>Termurah</option>
                <option value="mahal" <?php if (isset($_GET['sortir_harga']) && $_GET['sortir_harga'] == 'mahal') echo 'selected'; ?>>Termahal</option>
            </select>
        </form>
    </div>

    <!-- Grid Produk -->
     <div id="Menu" class="kontainer">
     <h1>Menu Gelato</h1>
    <div class="grid-produk">
    <?php
    // Ambil filter kategori dan sortir harga dari form
    $kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';
    $sortir_harga = isset($_GET['sortir_harga']) ? $_GET['sortir_harga'] : '';
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    // Query untuk mengambil produk dengan status favorit
    $query = "SELECT p.*, 
              CASE WHEN f.id_favorit IS NOT NULL THEN 1 ELSE 0 END as is_favorit 
              FROM produk p 
              LEFT JOIN favorit f ON p.id_produk = f.id_produk AND f.id_pengguna = " . ($user_id ? $user_id : 'NULL');

    // Tambahkan kondisi kategori jika ada
    if (!empty($kategori)) {
        $query .= " WHERE p.kategori = '$kategori'";
    }

    // Tambahkan sorting berdasarkan favorit terlebih dahulu, kemudian harga
    $query .= " ORDER BY is_favorit DESC";
    if ($sortir_harga === 'murah') {
        $query .= ", harga ASC";
    } elseif ($sortir_harga === 'mahal') {
        $query .= ", harga DESC";
    }

    $result = mysqli_query($conn, $query);

    while ($item = mysqli_fetch_assoc($result)) : ?>
        <div class="kartu-produk">
            <img src="<?php echo htmlspecialchars($item['url_gambar']); ?>" alt="<?php echo htmlspecialchars($item['nama']); ?>">
            <h3>
                <?php if ($item['is_favorit']): ?>
                    <span class="star-icon">⭐</span>
                <?php endif; ?>
                <?php echo htmlspecialchars($item['nama']); ?>
            </h3>
            <p>Rp <?php echo number_format($item['harga'], 0, ',', '.'); ?></p>

            <?php if (isset($_SESSION['user_id'])): ?>
                <form method="POST" action="favorit.php" class="toggle-favorit">
                    <input type="hidden" name="id_produk" value="<?php echo $item['id_produk']; ?>">
                    <input type="hidden" name="action" value="<?php echo $item['is_favorit'] ? 'remove' : 'add'; ?>">
                    <button type="submit" class="tombol <?php echo $item['is_favorit'] ? 'favorited' : ''; ?>">
                        <?php echo $item['is_favorit'] ? 'Hapus dari Favorit' : 'Tambah ke Favorit'; ?>
                    </button>
                </form>
                <form method="POST" action="tambah_ke_keranjang.php">
                    <input type="hidden" name="id_produk" value="<?php echo $item['id_produk']; ?>">
                    <button type="submit" class="tombol">Tambah ke Keranjang</button>
                </form>
            <?php else: ?>
                <a href="login.php" class="tombol">Login untuk Beli</a>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>
    </div>
    </div>

     <!-- Bagian Tentang Kami -->
     <div id="about" class="kontainer">
        <h1>Tentang Kami</h1>
        <h2>Kenapa Memilih Gelato Kami?<h2>
        <h3>Di Toko Gelato, kami percaya bahwa setiap rasa memiliki cerita. Mulai dari pilihan klasik seperti vanila dan cokelat hingga varian unik yang terinspirasi dari cita rasa lokal, kami ingin membawa Anda menjelajahi dunia rasa yang penuh kejutan dan kenikmatan.</h3>
    </div>

     <!-- Panduan Penyimpanan Gelato -->
     <div id="guide" class="kontainer">
        <h1>Panduan</h1>
        <h2>Cara Menyimpan Gelato<h2>
        <h3>Untuk menjaga kesegaran dan kualitas gelato Anda, simpan di suhu -18°C dalam freezer. Jangan biarkan terlalu lama di suhu ruangan untuk menjaga konsistensi dan rasa terbaik dari gelato kami.</h3>
    </div>

      <!-- Footer -->
      <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Kontak Kami</h3>
                <p>Email: info@tokogelato.com</p>
                <p>WA: 0812-3456-7890</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 Toko Gelato. Hak Cipta Dilindungi.</p>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>
