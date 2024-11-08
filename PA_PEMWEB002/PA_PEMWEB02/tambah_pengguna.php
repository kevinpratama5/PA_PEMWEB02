<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_pengguna = mysqli_real_escape_string($conn, $_POST['nama_pengguna']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $kata_sandi = password_hash($_POST['kata_sandi'], PASSWORD_DEFAULT);
    $peran = mysqli_real_escape_string($conn, $_POST['peran']);
    
    $query = "INSERT INTO pengguna (nama_pengguna, email, kata_sandi, peran) 
              VALUES ('$nama_pengguna', '$email', '$kata_sandi', '$peran')";
    
    if (mysqli_query($conn, $query)) {
        header("Location: dashboard.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pengguna - Toko Gelato</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 20px; background-color: #f5f5f5;">
    <div style="max-width: 600px; margin: 0 auto; background-color: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        <h2 style="color: #333; text-align: center;">Tambah Pengguna Baru</h2>
        
        <form method="POST" style="display: flex; flex-direction: column; gap: 15px;">
            <div>
                <label style="display: block; margin-bottom: 5px;">Nama Pengguna:</label>
                <input type="text" name="nama_pengguna" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px;">Email:</label>
                <input type="email" name="email" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px;">Kata Sandi:</label>
                <input type="password" name="kata_sandi" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px;">Peran:</label>
                <select name="peran" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                    <option value="pengguna">Pengguna</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            
            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <a href="dashboard.php" style="background-color: #f44336; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Batal</a>
                <button type="submit" style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">Simpan</button>
            </div>
        </form>
    </div>
</body>
</html> 