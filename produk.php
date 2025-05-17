<?php
include 'config.php';

// Tangani penambahan ke keranjang
if (isset($_POST['tambah_ke_keranjang'])) {
    $produk_id = $_POST['produk_id'];

    foreach ($produkData as $produk) {
        if ($produk['id'] == $produk_id) {
            $found = false;
            foreach ($_SESSION['keranjang'] as $key => $item) {
                if ($item['id'] == $produk_id) {
                    $_SESSION['keranjang'][$key]['jumlah']++;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $produk['jumlah'] = 1;
                $_SESSION['keranjang'][] = $produk;
            }
            header("Location: produk.php");
            exit;
        }
    }
}
$produkData = [
    [
        'id' => 1,
        'nama' => 'Apel Fuji',
        'harga' => 25000,
        'gambar' => 'apeel.jpg',
        'deskripsi' => 'Apel segar import dari Jepang'
    ],
    [
        'id' => 2,
        'nama' => 'Jeruk Mandarin',
        'harga' => 20000,
        'gambar' => 'jeruk.jpg',
        'deskripsi' => 'Jeruk segar dan manis'
    ],
    [
        'id' => 3,
        'nama' => 'Pisang Cavendish',
        'harga' => 15000,
        'gambar' => 'pisang.jpg',
        'deskripsi' => 'Pisang segar dari perkebunan lokal'
    ]
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Produk</title>
    <link rel="stylesheet" href="produk.css">
</head>
<body>
<h2>🛍️ Daftar Produk</h2>
<div class="produk-list">
    <?php foreach ($produkData as $produk): ?>
        <div class="produk-item">
            <img src="<?= $produk['gambar']; ?>" alt="<?= $produk['nama']; ?>">
            <h3><?= $produk['nama']; ?></h3>
            <p><?= $produk['deskripsi']; ?></p>
            <p><strong>Rp <?= number_format($produk['harga'], 0, ',', '.'); ?></strong></p>
            <form method="post">
                <input type="hidden" name="produk_id" value="<?= $produk['id']; ?>">
                <button type="submit" name="tambah_ke_keranjang" class="button">Tambah ke Keranjang</button>
            </form>
        </div>
    <?php endforeach; ?>
</div>
<a href="keranjang.php">Lihat Keranjang 🛒</a>
<a href="index.php">⬅️Kembali ke halaman utama</a>
</body>
</html>
