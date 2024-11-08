<?php
session_start();
require_once "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil total harga dari keranjang
$query = "SELECT SUM(p.harga * k.jumlah) as total 
          FROM keranjang k 
          JOIN produk p ON k.id_produk = p.id_produk 
          WHERE k.id_pengguna = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$total = $result->fetch_assoc()['total'];

// Buat pesanan baru
$query = "INSERT INTO pesanan (id_pengguna, total_harga, status) VALUES (?, ?, 'dibayar')";
$stmt = $conn->prepare($query);
$stmt->bind_param("id", $user_id, $total);
$stmt->execute();
$id_pesanan = $conn->insert_id;

// Pindahkan item dari keranjang ke item_pesanan
$query = "INSERT INTO item_pesanan (id_pesanan, id_produk, jumlah, harga)
          SELECT ?, k.id_produk, k.jumlah, p.harga
          FROM keranjang k
          JOIN produk p ON k.id_produk = p.id_produk
          WHERE k.id_pengguna = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $id_pesanan, $user_id);
$stmt->execute();

// Kosongkan keranjang
$query = "DELETE FROM keranjang WHERE id_pengguna = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();

// Redirect ke halaman sukses
header("Location: checkout_sukses.php");
exit();
?> 