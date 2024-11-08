<?php
include 'db.php';

// Cek apakah admin sudah ada
$admin_email = 'admin1234@gmail.com';
$check_admin_query = "SELECT * FROM pengguna WHERE email = '$admin_email'";
$result = mysqli_query($conn, $check_admin_query);

if (mysqli_num_rows($result) == 0) {
    // Buat hash untuk password '123'
    $admin_password = password_hash('1q2w3e4r5t', PASSWORD_DEFAULT);
    $query = "INSERT INTO pengguna (nama_pengguna, email, kata_sandi, peran) VALUES ('Admin', '$admin_email', '$admin_password', 'admin')";
    if (mysqli_query($conn, $query)) {
        echo "Akun admin berhasil dibuat.";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    echo "Akun admin sudah ada.";
}
?>
