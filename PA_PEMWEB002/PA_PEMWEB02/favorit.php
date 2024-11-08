<?php
session_start();
require_once "db.php";

if (!isset($_SESSION['user_id']) || !isset($_POST['id_produk']) || !isset($_POST['action'])) {
    header('Location: index.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$product_id = $_POST['id_produk'];
$action = $_POST['action'];

if ($action === 'add') {
    // Tambahkan ke favorit
    $query = "INSERT INTO favorit (id_pengguna, id_produk) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ii", $user_id, $product_id);
    mysqli_stmt_execute($stmt);
} else if ($action === 'remove') {
    // Hapus dari favorit
    $query = "DELETE FROM favorit WHERE id_pengguna = ? AND id_produk = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ii", $user_id, $product_id);
    mysqli_stmt_execute($stmt);
}

// Redirect kembali ke halaman sebelumnya
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;
?>