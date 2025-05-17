<?php
session_start();

// Inisialisasi keranjang jika belum ada
if (!isset($_SESSION['keranjang'])) {
    $_SESSION['keranjang'] = [];
}


// Data produk
$produkData = [
    [
        'id' => 1,
        'nama' => 'Apel Fuji',
        'harga' => 25000,
        'gambar' => 'images/apel.jpg',
        'deskripsi' => 'Apel segar import dari Jepang'
    ],
    [
        'id' => 2,
        'nama' => 'Jeruk Mandarin',
        'harga' => 20000,
        'gambar' => 'images/jeruk.jpg',
        'deskripsi' => 'Jeruk segar dan manis'
    ],
    [
        'id' => 3,
        'nama' => 'Pisang Cavendish',
        'harga' => 15000,
        'gambar' => 'images/pisang.jpg',
        'deskripsi' => 'Pisang segar dari perkebunan lokal'
    ]
];

function hitungTotal() {
    $total = 0;
    foreach ($_SESSION['keranjang'] as $item) {
        $total += $item['harga'] * $item['jumlah'];
    }
    return $total;
}

function cekGratisOngkir() {
    return hitungTotal() >= 100000;
}
?>
