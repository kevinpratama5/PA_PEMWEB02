<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db.php';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM pengguna WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['kata_sandi'])) {
            // Set session untuk user ID dan role
            $_SESSION['user_id'] = $user['id_pengguna'];
            $_SESSION['role'] = $user['peran'];

            // Redirect sesuai dengan peran pengguna
            if ($user['peran'] === 'admin') {
                header("Location: dashboard.php"); // Arahkan ke dashboard jika admin
            } else {
                header("Location: index.php"); // Arahkan ke beranda jika pengguna biasa
            }
            exit();
        } else {
            $error_message = "Password salah.";
        }
    } else {
        $error_message = "Email tidak ditemukan.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Toko Gelato</title>
    <link rel="stylesheet" href="styles/register.css">
</head>
<body>
    <h2>Login</h2>
    <?php if (isset($error_message)) : ?>
        <p class="error-message"><?php echo $error_message; ?></p>
    <?php endif; ?>
    <form method="POST" action="login.php">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
    </form>
</body>
</html>
