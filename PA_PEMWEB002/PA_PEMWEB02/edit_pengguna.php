<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];
$query = "SELECT * FROM pengguna WHERE id_pengguna = $id";
$result = mysqli_query($conn, $query);
$pengguna = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_pengguna = mysqli_real_escape_string($conn, $_POST['nama_pengguna']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $peran = mysqli_real_escape_string($conn, $_POST['peran']);
    
    $query = "UPDATE pengguna SET 
              nama_pengguna = '$nama_pengguna',
              email = '$email',
              peran = '$peran'
              WHERE id_pengguna = $id";
    
    // If password is being updated
    if (!empty($_POST['kata_sandi'])) {
        $kata_sandi = password_hash($_POST['kata_sandi'], PASSWORD_DEFAULT);
        $query = "UPDATE pengguna SET 
                  nama_pengguna = '$nama_pengguna',
                  email = '$email',
                  kata_sandi = '$kata_sandi',
                  peran = '$peran'
                  WHERE id_pengguna = $id";
    }
    
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
    <title>Edit Pengguna - Toko Gelato</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 20px; background-color: #f5f5f5;">
    <div style="max-width: 600px; margin: 0 auto; background-color: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        <h2 style="color: #333; text-align: center;">Edit Pengguna</h2>
        
        <form method="POST" style="display: flex; flex-direction: column; gap: 15px;">
            <div>
                <label style="display: block; margin-bottom: 5px;">Nama Pengguna:</label>
                <input type="text" name="nama_pengguna" value="<?php echo $pengguna['nama_pengguna']; ?>" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px;">Email:</label>
                <input type="email" name="email" value="<?php echo $pengguna['email']; ?>" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px;">Kata Sandi Baru (kosongkan jika tidak ingin mengubah):</label>
                <input type="password" name="kata_sandi" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px;">Peran:</label>
                <select name="peran" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                    <option value="pengguna" <?php echo ($pengguna['peran'] == 'pengguna') ? 'selected' : ''; ?>>Pengguna</option>
                    <option value="admin" <?php echo ($pengguna['peran'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
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