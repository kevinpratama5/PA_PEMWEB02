<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch products
$query_produk = "SELECT * FROM produk";
$result_produk = mysqli_query($conn, $query_produk);

// Fetch users
$query_pengguna = "SELECT p.*, 
    CASE 
        WHEN EXISTS (SELECT 1 FROM keranjang k WHERE k.id_pengguna = p.id_pengguna) THEN 'Belum Checkout'
        WHEN EXISTS (SELECT 1 FROM pesanan ps WHERE ps.id_pengguna = p.id_pengguna) THEN 'Sudah Checkout'
        ELSE 'Belum Pesan'
    END as status_pesanan
    FROM pengguna p";
$result_pengguna = mysqli_query($conn, $query_pengguna);

// Handle delete operations
if (isset($_POST['delete_produk'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id_produk']);
    // First get the image URL to delete the file
    $query = "SELECT url_gambar FROM produk WHERE id_produk = '$id'";
    $result = mysqli_query($conn, $query);
    if ($row = mysqli_fetch_assoc($result)) {
        if (!empty($row['url_gambar']) && file_exists($row['url_gambar'])) {
            unlink($row['url_gambar']); // Delete the image file
        }
    }
    // Then delete the product
    $query = "DELETE FROM produk WHERE id_produk = '$id'";
    mysqli_query($conn, $query);
    header("Location: dashboard.php");
    exit();
}

if (isset($_POST['delete_pengguna'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id_pengguna']);
    $query = "DELETE FROM pengguna WHERE id_pengguna = '$id'";
    mysqli_query($conn, $query);
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Toko Gelato</title>
    <link rel="stylesheet" href="styles/beranda.css">
</head>
<body style="font-family: Arial, sans-serif; margin: 0; background-color: #f5f5f5;">
    <!-- Navbar -->
    <nav class="navbar" style="background-color: #fff; padding: 1rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <div class="logo" style="display: flex; align-items: center; gap: 1rem;">
            <h1 style="margin: 0;">Toko Gelato</h1>
            <img src="images/logo.png" alt="Logo Toko Gelato" style="height: 80px;">
        </div>

        <div class="link-nav" style="display: flex; gap: 1rem; align-items: center;">
            <a href="dashboard.php" style="text-decoration: none; color: #333;">Dashboard</a>
            <a href="logout.php" style="text-decoration: none; color: #fff; background-color: #f44336; padding: 0.5rem 1rem; border-radius: 5px;">Logout</a>
        </div>
    </nav>

    <div style="max-width: 1200px; margin: 2rem auto; padding: 0 1rem;">
        <div style="background-color: white; padding: 2rem; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
            <h2 style="color: #333; text-align: center; margin-bottom: 2rem;">Dashboard Admin</h2>
            
            <!-- Products Section -->
            <div style="margin-bottom: 3rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <h3 style="color: #333; margin: 0;">Manajemen Produk</h3>
                    <a href="tambah_produk.php" style="background-color: #4CAF50; color: white; padding: 0.5rem 1rem; text-decoration: none; border-radius: 5px;">Tambah Produk</a>
                </div>
                
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; margin-top: 1rem;">
                        <thead>
                            <tr style="background-color: #f2f2f2;">
                                <th style="padding: 12px; border: 1px solid #ddd; text-align: left;">ID</th>
                                <th style="padding: 12px; border: 1px solid #ddd; text-align: left;">Gambar</th>
                                <th style="padding: 12px; border: 1px solid #ddd; text-align: left;">Nama</th>
                                <th style="padding: 12px; border: 1px solid #ddd; text-align: left;">Deskripsi</th>
                                <th style="padding: 12px; border: 1px solid #ddd; text-align: left;">Harga</th>
                                <th style="padding: 12px; border: 1px solid #ddd; text-align: left;">Kategori</th>
                                <th style="padding: 12px; border: 1px solid #ddd; text-align: left;">Stok</th>
                                <th style="padding: 12px; border: 1px solid #ddd; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result_produk)) : ?>
                                <tr>
                                    <td style="padding: 12px; border: 1px solid #ddd;"><?php echo $row['id_produk']; ?></td>
                                    <td style="padding: 12px; border: 1px solid #ddd;">
                                        <img src="<?php echo $row['url_gambar']; ?>" alt="<?php echo $row['nama']; ?>" 
                                             style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                    </td>
                                    <td style="padding: 12px; border: 1px solid #ddd;"><?php echo $row['nama']; ?></td>
                                    <td style="padding: 12px; border: 1px solid #ddd;"><?php echo $row['deskripsi']; ?></td>
                                    <td style="padding: 12px; border: 1px solid #ddd;">Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                                    <td style="padding: 12px; border: 1px solid #ddd;"><?php echo $row['kategori']; ?></td>
                                    <td style="padding: 12px; border: 1px solid #ddd;"><?php echo $row['stok']; ?></td>
                                    <td style="padding: 12px; border: 1px solid #ddd; display:flex; flex-wrap:wrap; gap:10px; justify-content:center; align-items;center;">
                                        <a href="edit_produk.php?id=<?php echo $row['id_produk']; ?>" style="background-color: #2196F3; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; margin-right: 5px;">Edit</a>
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="id_produk" value="<?php echo $row['id_produk']; ?>">
                                            <button type="submit" name="delete_produk" style="background-color: #f44336; color: white; padding: 5px 10px; border: none; border-radius: 3px; cursor: pointer;" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Users Section -->
            <div>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <h3 style="color: #333; margin: 0;">Manajemen Pengguna</h3>
                    <a href="tambah_pengguna.php" style="background-color: #4CAF50; color: white; padding: 0.5rem 1rem; text-decoration: none; border-radius: 5px;">Tambah Pengguna</a>
                </div>
                
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; margin-top: 1rem;">
                        <thead>
                            <tr style="background-color: #f2f2f2;">
                                <th style="padding: 12px; border: 1px solid #ddd; text-align: left;">ID</th>
                                <th style="padding: 12px; border: 1px solid #ddd; text-align: left;">Nama Pengguna</th>
                                <th style="padding: 12px; border: 1px solid #ddd; text-align: left;">Email</th>
                                <th style="padding: 12px; border: 1px solid #ddd; text-align: left;">Peran</th>
                                <th style="padding: 12px; border: 1px solid #ddd; text-align: left;">Status</th>
                                <th style="padding: 12px; border: 1px solid #ddd; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result_pengguna)) : ?>
                                <tr>
                                    <td style="padding: 12px; border: 1px solid #ddd;"><?php echo $row['id_pengguna']; ?></td>
                                    <td style="padding: 12px; border: 1px solid #ddd;"><?php echo $row['nama_pengguna']; ?></td>
                                    <td style="padding: 12px; border: 1px solid #ddd;"><?php echo $row['email']; ?></td>
                                    <td style="padding: 12px; border: 1px solid #ddd;"><?php echo $row['peran']; ?></td>
                                    <td style="padding: 12px; border: 1px solid #ddd;">
                                        <?php 
                                        $status_color = '';
                                        $status = $row['status_pesanan'];
                                        
                                        switch($status) {
                                            case 'Belum Pesan':
                                                $status_color = '#6c757d'; // gray
                                                break;
                                            case 'Belum Checkout':
                                                $status_color = '#ffc107'; // yellow/warning
                                                break;
                                            case 'Sudah Checkout':
                                                $status_color = '#28a745'; // green/success
                                                break;
                                        }
                                        ?>
                                        <span style="
                                            background-color: <?php echo $status_color; ?>;
                                            color: <?php echo $status === 'Belum Checkout' ? '#000' : '#fff'; ?>;
                                            padding: 5px 10px;
                                            border-radius: 20px;
                                            font-size: 0.85em;
                                            display: inline-block;
                                        ">
                                            <?php echo $status; ?>
                                        </span>
                                    </td>
                                    <td style="padding: 12px; border: 1px solid #ddd; display:flex; flex-wrap:wrap; gap:10px; justify-content:center; align-items;center;">
                                        <a href="edit_pengguna.php?id=<?php echo $row['id_pengguna']; ?>" style="background-color: #2196F3; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; margin-right: 5px;">Edit</a>
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="id_pengguna" value="<?php echo $row['id_pengguna']; ?>">
                                            <button type="submit" name="delete_pengguna" style="background-color: #f44336; color: white; padding: 5px 10px; border: none; border-radius: 3px; cursor: pointer; margin-right: 5px;" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer style="background-color: #333; color: white; padding: 2rem 0; margin-top: 3rem;">
        <div style="max-width: 1200px; margin: 0 auto; padding: 0 1rem;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem;">
                <div>
                    <h3>Tentang Kami</h3>
                    <p>Toko Gelato menyajikan gelato Italia autentik dengan bahan-bahan premium.</p>
                </div>
                <div>
                    <h3>Kontak</h3>
                    <p>Email: info@tokogelato.com</p>
                    <p>Telepon: (021) 1234-5678</p>
                </div>
            </div>
            <div style="text-align: center; margin-top: 2rem; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.1);">
                <p>&copy; 2024 Toko Gelato. Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </footer>
</body>
</html>
