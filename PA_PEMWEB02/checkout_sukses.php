<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Berhasil - Toko Gelato</title>
    <link rel="stylesheet" href="styles/beranda.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .kontainer {
            max-width: 600px;
            margin: 50px auto;
            padding: 40px;
            text-align: center;
            background: white;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }

        .sukses-ikon {
            color: #4CAF50;
            font-size: 80px;
            margin-bottom: 20px;
            animation: bounce 1s ease;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {transform: translateY(0);}
            40% {transform: translateY(-30px);}
            60% {transform: translateY(-15px);}
        }

        h2 {
            color: #333;
            font-size: 32px;
            margin-bottom: 20px;
        }

        p {
            color: #666;
            font-size: 18px;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .tombol {
            display: inline-block;
            padding: 15px 30px;
            background: #FF6B6B;
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            margin-top: 20px;
            transition: all 0.3s ease;
        }

        .tombol:hover {
            background: #FF5252;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255,107,107,0.3);
        }

        .order-details {
            margin: 30px 0;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="kontainer">
        <i class="fas fa-check-circle sukses-ikon"></i>
        <h2>Checkout Berhasil!</h2>
        <div class="order-details">
            <p>Terima kasih telah berbelanja di Toko Gelato.</p>
            <p>Pesanan Anda sedang diproses dan akan segera disiapkan.</p>
            <p>Anda akan menerima email konfirmasi segera.</p>
        </div>
        <a href="index.php" class="tombol">
            <i class="fas fa-home"></i> Kembali ke Beranda
        </a>
    </div>
</body>
</html> 