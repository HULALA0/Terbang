<?php
session_start();
if (!isset($_SESSION['keranjang']) || count($_SESSION['keranjang']) === 0) {
    header("Location: keranjang.php");
    exit;
}

function hitungTotal() {
    $total = 0;
    foreach ($_SESSION['keranjang'] as $item) {
        $total += $item['harga'] * $item['jumlah'];
    }
    return $total;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <link rel="stylesheet" href="checkout.css">
</head>

<body>
    <h2>✅ Checkout</h2>
    <p>Berikut adalah pesanan Anda:</p>
    <ul>
        <?php foreach ($_SESSION['keranjang'] as $item): ?>
            <li><?= $item['nama']; ?> (<?= $item['jumlah']; ?> x Rp <?= number_format($item['harga'], 0, ',', '.'); ?>)</li>
        <?php endforeach; ?>
    </ul>
    <p><strong>Total: Rp <?= number_format(hitungTotal(), 0, ',', '.'); ?></strong></p>

    <p>Silakan lanjutkan pembayaran melalui metode yang tersedia di halaman utama.</p>
    <a href="index.php">🔁 Kembali ke Beranda</a>
</body>
</html>
