<?php
include 'config.php';

if (isset($_POST['aksi']) && isset($_POST['item_index'])) {
    $index = $_POST['item_index'];

    if (isset($_SESSION['keranjang'][$index])) {
        if ($_POST['aksi'] === 'tambah') {
            $_SESSION['keranjang'][$index]['jumlah']++;
        } elseif ($_POST['aksi'] === 'kurangi') {
            $_SESSION['keranjang'][$index]['jumlah']--;
            // Jika jumlah jadi 0, hapus item
            if ($_SESSION['keranjang'][$index]['jumlah'] <= 0) {
                unset($_SESSION['keranjang'][$index]);
                $_SESSION['keranjang'] = array_values($_SESSION['keranjang']);
            }
        }
    }

    header("Location: keranjang.php");
    exit;
}

// Hapus item
if (isset($_POST['hapus_item'])) {
    $index = $_POST['item_index'];
    if (isset($_SESSION['keranjang'][$index])) {
        unset($_SESSION['keranjang'][$index]);
        $_SESSION['keranjang'] = array_values($_SESSION['keranjang']);
    }
    header("Location: keranjang.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Keranjang</title>
    <link rel="stylesheet" href="keranjang.css">
</head>
<body>
<h2>🛒 Keranjang Belanja</h2>
<?php if (count($_SESSION['keranjang']) > 0): ?>
    <table>
        <thead>
        <tr>
            <th>Produk</th>
            <th>Harga</th>
            <th>Jumlah</th>
            <th>Subtotal</th>
            <th>Aksi</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($_SESSION['keranjang'] as $index => $item): ?>
            <tr>
                <td><?= $item['nama']; ?></td>
                <td>Rp <?= number_format($item['harga'], 0, ',', '.'); ?></td>
                <td><?= $item['jumlah']; ?></td>
                <td>Rp <?= number_format($item['harga'] * $item['jumlah'], 0, ',', '.'); ?></td>
                <td>

    <form method="post" style="display: inline;">
        <input type="hidden" name="item_index" value="<?= $index; ?>">
        <input type="hidden" name="aksi" value="kurangi">
        <button type="submit">−</button>
    </form>                      

    <form method="post" style="display: inline;">
        <input type="hidden" name="item_index" value="<?= $index; ?>">
        <input type="hidden" name="aksi" value="tambah">
        <button type="submit">+</button>
    </form> 
    
<form method="post" style="display:inline;">
    <input type="hidden" name="item_index" value="<?= $index; ?>">
    <button type="submit" name="hapus_item" class="button" title="Hapus" style="background: none; border: none; cursor: pointer; font-size: 20px;">🗑️</button>
</form>

    </form>
</td>

            </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="3"><strong>Total</strong></td>
            <td colspan="2"><strong>Rp <?= number_format(hitungTotal(), 0, ',', '.'); ?></strong></td>
        </tr>
        <?php if (cekGratisOngkir()): ?>
            <tr>
                <td colspan="5" style="text-align:center; color:green;">🎉 Anda mendapatkan Gratis Ongkir!</td>
            </tr>
        <?php endif; ?>
        </tfoot>
    </table>
<?php else: ?>
    <p>Keranjang kosong.</p>
<?php endif; ?>
<form action="checkout.php" method="post" style="margin-top: 20px; text-align: right;">
    <button type="submit" class="button" style="padding: 10px 20px; font-size: 16px;">✅ Checkout Sekarang</button>
</form>
<a href="produk.php">⬅️ Kembali ke Produk</a>
<a href="index.php">⬅️ Kembali ke halaman utama</a>
</body>
</html>
