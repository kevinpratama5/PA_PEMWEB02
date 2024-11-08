<?php
session_start();
include 'db.php';

// Pengecekan login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['id_keranjang'])) {
    $id_keranjang = $_POST['id_keranjang'];

    // Query untuk menghapus item dari keranjang
    $query = "DELETE FROM keranjang WHERE id_keranjang = '$id_keranjang'";
    if (mysqli_query($conn, $query)) {
        header("Location: keranjang.php"); // Kembali ke halaman keranjang setelah menghapus
        exit();
    } else {
        echo "Gagal menghapus item: " . mysqli_error($conn);
    }
} else {
    header("Location: keranjang.php");
    exit();
}
