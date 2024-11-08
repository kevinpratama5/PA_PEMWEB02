<?php
session_start();
require_once "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_produk'])) {
    $id_pengguna = $_SESSION['user_id'];
    $id_produk = $_POST['id_produk'];
    
    // Cek apakah produk sudah ada di keranjang
    $check_query = "SELECT * FROM keranjang WHERE id_pengguna = ? AND id_produk = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("ii", $id_pengguna, $id_produk);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Update jumlah jika produk sudah ada
        $update_query = "UPDATE keranjang SET jumlah = jumlah + 1 WHERE id_pengguna = ? AND id_produk = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ii", $id_pengguna, $id_produk);
    } else {
        // Tambah produk baru ke keranjang
        $insert_query = "INSERT INTO keranjang (id_pengguna, id_produk, jumlah) VALUES (?, ?, 1)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("ii", $id_pengguna, $id_produk);
    }
    
    $stmt->execute();
    header("Location: keranjang.php");
    exit();
} 