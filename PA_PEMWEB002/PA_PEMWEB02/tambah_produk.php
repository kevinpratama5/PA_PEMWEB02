<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $harga = mysqli_real_escape_string($conn, $_POST['harga']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $stok = mysqli_real_escape_string($conn, $_POST['stok']);
    
    // Handle file upload
    $target_dir = "images/";
    $file_extension = strtolower(pathinfo($_FILES["gambar"]["name"], PATHINFO_EXTENSION));
    $new_filename = uniqid() . '.' . $file_extension;
    $target_file = $target_dir . $new_filename;
    
    // Check if image file is valid
    $uploadOk = 1;
    if(isset($_FILES["gambar"])) {
        $check = getimagesize($_FILES["gambar"]["tmp_name"]);
        if($check === false) {
            echo "File bukan gambar.";
            $uploadOk = 0;
        }
        
        // Check file size (max 5MB)
        if ($_FILES["gambar"]["size"] > 5000000) {
            echo "Ukuran file terlalu besar.";
            $uploadOk = 0;
        }
        
        // Allow certain file formats
        if($file_extension != "jpg" && $file_extension != "jpeg" && $file_extension != "png") {
            echo "Hanya file JPG, JPEG & PNG yang diizinkan.";
            $uploadOk = 0;
        }
    }

    if ($uploadOk == 1 && move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
        $query = "INSERT INTO produk (nama, deskripsi, harga, kategori, stok, url_gambar) 
                  VALUES ('$nama', '$deskripsi', '$harga', '$kategori', '$stok', '$target_file')";
        
        if (mysqli_query($conn, $query)) {
            header("Location: dashboard.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - Toko Gelato</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 20px; background-color: #f5f5f5;">
    <div style="max-width: 600px; margin: 0 auto; background-color: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        <h2 style="color: #333; text-align: center;">Tambah Produk Baru</h2>
        
        <form method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 15px;">
            <div>
                <label style="display: block; margin-bottom: 5px;">Nama Produk:</label>
                <input type="text" name="nama" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px;">Deskripsi:</label>
                <textarea name="deskripsi" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; height: 100px;"></textarea>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px;">Harga:</label>
                <input type="number" name="harga" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px;">Kategori:</label>
                <input type="text" name="kategori" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px;">Stok:</label>
                <input type="number" name="stok" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px;">Gambar Produk:</label>
                <input type="file" name="gambar" accept="image/*" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            
            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <a href="dashboard.php" style="background-color: #f44336; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Batal</a>
                <button type="submit" style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">Simpan</button>
            </div>
        </form>
    </div>
</body>
</html>
