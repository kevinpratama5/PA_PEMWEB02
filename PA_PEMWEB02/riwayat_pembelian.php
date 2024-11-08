<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$id_pengguna = $_SESSION['user_id'];
$query = "SELECT produk.nama, produk.harga, riwayat_pembelian.jumlah, riwayat_pembelian.tanggal_pembelian 
          FROM riwayat_pembelian
          JOIN produk ON riwayat_pembelian.id_produk = produk.id_produk
          WHERE riwayat_pembelian.id_pengguna = '$id_pengguna'
          ORDER BY riwayat_pembelian.tanggal_pembelian DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pembelian - Toko Gelato</title>
</head>
<body>
    <h2>Riwayat Pembelian</h2>
    <table border="1">
        <tr>
            <th>Nama Produk</th>
            <th>Harga</th>
            <th>Jumlah</th>
            <th>Tanggal Pembelian</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <tr>
                <td><?php echo htmlspecialchars($row['nama']); ?></td>
                <td>Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                <td><?php echo $row['jumlah']; ?></td>
                <td><?php echo $row['tanggal_pembelian']; ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
