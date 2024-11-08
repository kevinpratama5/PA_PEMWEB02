<?php
$server = "localhost";
$user = "	if0_37676060";
$password = "Ppoqp2lo6CKJOTB";
$db_name = "if0_37676060_toko_gelato"; // Nama database sesuai dengan instruksi

$conn = mysqli_connect($server, $user, $password, $db_name);

if (!$conn) {
    die("Gagal terhubung ke database: " . mysqli_connect_error());
}
?>