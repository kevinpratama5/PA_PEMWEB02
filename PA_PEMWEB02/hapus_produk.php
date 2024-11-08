<?php
require_once "db.php";
require_once "otentikasi.php";

$id = $_GET['id'];
$query = "DELETE FROM produk WHERE id_produk = '$id'";
if (mysqli_query($koneksi, $query)) {
    header("Location: dashboard.php");
    exit();
} else {
    echo "Gagal menghapus produk: " . mysqli_error($koneksi);
}
?>
