<?php
session_start();
require_once "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_keranjang'])) {
    $id_keranjang = $_POST['id_keranjang'];
    $id_pengguna = $_SESSION['user_id'];
    
    $query = "DELETE FROM keranjang WHERE id_keranjang = ? AND id_pengguna = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $id_keranjang, $id_pengguna);
    $stmt->execute();
}

header("Location: keranjang.php");
exit();
?> 