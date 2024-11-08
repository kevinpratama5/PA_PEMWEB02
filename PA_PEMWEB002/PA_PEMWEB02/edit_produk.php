<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];
$query = "SELECT * FROM produk WHERE id_produk = $id";
$result = mysqli_query($conn, $query);
$produk = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $harga = mysqli_real_escape_string($conn, $_POST['harga']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $stok = mysqli_real_escape_string($conn, $_POST['stok']);
    
    $query = "UPDATE produk SET 
              nama = '$nama',
              deskripsi = '$deskripsi',
              harga = '$harga',
              kategori = '$kategori',
              stok = '$stok'";
    
    if(!empty($_FILES['gambar']['name'])) {
        $target_dir = "images/";
        $file_extension = strtolower(pathinfo($_FILES["gambar"]["name"], PATHINFO_EXTENSION));
        $new_filename = uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;
        
        $uploadOk = 1;
        $check = getimagesize($_FILES["gambar"]["tmp_name"]);
        if($check === false) {
            echo "File bukan gambar.";
            $uploadOk = 0;
        }
        
        if ($_FILES["gambar"]["size"] > 5000000) {
            echo "Ukuran file terlalu besar.";
            $uploadOk = 0;
        }
        
        if($file_extension != "jpg" && $file_extension != "jpeg" && $file_extension != "png") {
            echo "Hanya file JPG, JPEG & PNG yang diizinkan.";
            $uploadOk = 0;
        }
        
        if ($uploadOk == 1 && move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
            $query .= ", url_gambar = '$target_file'";
            
            if(!empty($produk['url_gambar']) && file_exists($produk['url_gambar'])) {
                unlink($produk['url_gambar']);
            }
        }
    }
    
    $query .= " WHERE id_produk = $id";
    
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
    <title>Edit Produk - Toko Gelato</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 20px; background-color: #f5f5f5;">
    <div style="max-width: 600px; margin: 0 auto; background-color: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        <h2 style="color: #333; text-align: center;">Edit Produk</h2>
        
        <form method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 15px;">
            <div>
                <label style="display: block; margin-bottom: 5px;">Nama Produk:</label>
                <input type="text" name="nama" value="<?php echo $produk['nama']; ?>" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px;">Deskripsi:</label>
                <textarea name="deskripsi" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; height: 100px;"><?php echo $produk['deskripsi']; ?></textarea>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px;">Harga:</label>
                <input type="number" name="harga" value="<?php echo $produk['harga']; ?>" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px;">Kategori:</label>
                <input type="text" name="kategori" value="<?php echo $produk['kategori']; ?>" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px;">Stok:</label>
                <input type="number" name="stok" value="<?php echo $produk['stok']; ?>" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px;">Gambar Produk:</label>
                <?php if(!empty($produk['url_gambar'])): ?>
                    <img src="<?php echo $produk['url_gambar']; ?>" alt="Current product image" style="max-width: 200px; margin-bottom: 10px;">
                <?php endif; ?>
                <input type="file" name="gambar" accept="image/*" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                <small style="color: #666;">Biarkan kosong jika tidak ingin mengubah gambar</small>
            </div>
            
            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <a href="dashboard.php" style="background-color: #f44336; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Batal</a>
                <button type="submit" style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">Simpan</button>
            </div>
        </form>
    </div>
</body>
</html>
