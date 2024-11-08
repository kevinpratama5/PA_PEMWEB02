<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - Toko Gelato</title>
    <link rel="stylesheet" href="styles/register.css">
</head>
<body>

<?php
include 'db.php';

if (isset($_POST['register'])) {
    $nama_pengguna = $_POST['nama_pengguna'];
    $email = $_POST['email'];
    $kata_sandi = password_hash($_POST['kata_sandi'], PASSWORD_DEFAULT);

    // Cek apakah email sudah ada di database
    $email_check_query = "SELECT * FROM pengguna WHERE email = '$email' LIMIT 1";
    $result = mysqli_query($conn, $email_check_query);

    if (mysqli_num_rows($result) > 0) {
        // Jika email sudah ada
        echo "<div class='error-message'>Email sudah terdaftar. Silakan gunakan email lain atau <a href='login.php'>login</a>.</div>";
    } else {
        // Jika email belum ada, lanjutkan dengan registrasi
        $query = "INSERT INTO pengguna (nama_pengguna, email, kata_sandi) VALUES ('$nama_pengguna', '$email', '$kata_sandi')";
        
        if (mysqli_query($conn, $query)) {
            echo "<div class='success-message'>Registrasi berhasil. Silakan <a href='login.php'>login</a>.</div>";
        } else {
            echo "<div class='error-message'>Error: " . $query . "<br>" . mysqli_error($conn) . "</div>";
        }
    }
}
?>

<form method="POST" action="register.php">
    <input type="text" name="nama_pengguna" placeholder="Nama Pengguna" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="kata_sandi" placeholder="Kata Sandi" required>
    <button type="submit" name="register">Daftar</button>
</form>

</body>
</html>
