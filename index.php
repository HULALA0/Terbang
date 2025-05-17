<?php
// Start session for managing cart and user login status
session_start();

// Initialize cart if not exists
if (!isset($_SESSION['keranjang'])) {
    $_SESSION['keranjang'] = [];
}

// Sample product data (in real application, this would come from a database)
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

// Handle add to cart
if (isset($_POST['tambah_ke_keranjang'])) {
    $produk_id = $_POST['produk_id'];
    $found = false;
    
    // Find product in data
    foreach ($produkData as $produk) {
        if ($produk['id'] == $produk_id) {
            // Check if product already in cart
            $found_in_cart = false;
            foreach ($_SESSION['keranjang'] as $key => $item) {
                if ($item['id'] == $produk_id) {
                    $_SESSION['keranjang'][$key]['jumlah']++;
                    $found_in_cart = true;
                    break;
                }
            }
            
            // If not in cart, add it
            if (!$found_in_cart) {
                $produk['jumlah'] = 1;
                $_SESSION['keranjang'][] = $produk;
            }
            
            // Redirect to prevent form resubmission
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }
    }
}

// Handle cart item removal
if (isset($_POST['hapus_item'])) {
    $index = $_POST['item_index'];
    if (isset($_SESSION['keranjang'][$index])) {
        unset($_SESSION['keranjang'][$index]);
        $_SESSION['keranjang'] = array_values($_SESSION['keranjang']); // Reindex array
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Calculate total
function hitungTotal() {
    $total = 0;
    if (isset($_SESSION['keranjang'])) {
        foreach ($_SESSION['keranjang'] as $item) {
            $total += $item['harga'] * $item['jumlah'];
        }
    }
    return $total;
}

// Check if order qualifies for free shipping
function cekGratisOngkir() {
    $total = hitungTotal();
    return $total >= 100000;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Toko Online Sederhana</title>
  <link rel="stylesheet" href="style.css" />
  <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
  <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>
</head>
<body>
  <header>
    <nav>
      <div class="menu">
          <div class="logo">
            <img src="Logo.png" alt="Freshly Logo">
            <h1>FRESHLY</h1>
          </div>
          <ul>
              <li><a href="#Home">Home</a></li>
              <li><a href="#produk">Produk</a></li>
              <li><a href="#logout">Tentang</a></li>
              <li><a href="#logout">Kontak</a></li>
              <li><img src="mage_basket-fill.svg"></li>
          </ul>
      </div>
    </nav>
  </header>

  <section class="hero">
    <div class="hero-text">
      <h1>FRESH ORGANIC & FOODS</h1>
      <p>WE DELIVERY ORGANIC VEGETABLES & FRUITS</p>
    </div>
  </section>

  <section class="adv">
    <table>
      <tr id="gambar">
        <th>
          <dotlottie-player
            src="https://lottie.host/f985b52e-60fa-4f93-a7f9-6a2ec252458c/ESqVuq6jya.lottie"
            background="transparent"
            justify_content="center"
            speed="1"
            style="width: 200px; height: 200px"
            loop
            autoplay>
          </dotlottie-player>
        </th>
        <th>
          <dotlottie-player
            src="https://lottie.host/5d3aeaf2-8491-4628-9a72-d45dc1f5b18c/E31zAT58rQ.lottie"
            background="transparent"
            speed="1"
            style="width: 150px; height: 150px"
            loop
            autoplay>
          </dotlottie-player>
        </th>
        <th>
          <dotlottie-player
            src="https://lottie.host/919c464b-2246-4d50-94a7-aa77abc58e6c/61DK9dmhfR.lottie"
            background="transparent"
            speed="1"
            style="width: 150px; height: 150px"
            loop
            autoplay>
          </dotlottie-player>
        </th>
        <th>
          <dotlottie-player
            src="https://lottie.host/e9a8ed1a-f752-43e2-88df-35d9edccab0f/5stT9pDDoU.lottie"
            background="transparent"
            speed="1"
            style="width: 150px; height: 150px"
            loop
            autoplay>
          </dotlottie-player>
        </th>
      </tr>
      <tr>
        <th>Free Shipping</th>
        <th>Fresh</th>
        <th>Quality</th>
        <th>Support</th>
      </tr>
      <tr id="desc">
        <th>On order over 100k</th>
        <th>Product well package</th>
        <th>Good Quality</th>
        <th>24/7 Support</th>
      </tr>
    </table>
  </section>

  <section id="keranjang">
    <h2>🛒 Keranjang Belanja</h2>
    <div id="listKeranjang">
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
                <td><?php echo $item['nama']; ?></td>
                <td>Rp <?php echo number_format($item['harga'], 0, ',', '.'); ?></td>
                <td><?php echo $item['jumlah']; ?></td>
                <td>Rp <?php echo number_format($item['harga'] * $item['jumlah'], 0, ',', '.'); ?></td>
                <td>
                  <form method="post">
                    <input type="hidden" name="item_index" value="<?php echo $index; ?>">
                    <button type="submit" name="hapus_item" class="button">Hapus</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="3"><strong>Total</strong></td>
              <td colspan="2"><strong>Rp <?php echo number_format(hitungTotal(), 0, ',', '.'); ?></strong></td>
            </tr>
            <?php if (cekGratisOngkir()): ?>
              <tr>
                <td colspan="5" style="color: green; text-align: center;">
                  <strong>Selamat! Anda mendapatkan GRATIS ONGKIR</strong>
                </td>
              </tr>
            <?php endif; ?>
          </tfoot>
        </table>
        <button class="button" style="margin-top: 15px;" onclick="kirimWhatsApp()">Kirim ke WhatsApp</button>
      <?php else: ?>
        <p>Keranjang belanja Anda kosong.</p>
      <?php endif; ?>
    </div>
  </section>

  <section id="status-pembayaran">
    <h2>💳 Status Pembayaran</h2>
    <form method="post" enctype="multipart/form-data" action="proses_pembayaran.php">
      <label for="status">Pilih status:</label>
      <select id="status" name="status" onchange="toggleUpload()">
        <option value="BELUM BAYAR">BELUM BAYAR</option>
        <option value="SUDAH BAYAR">SUDAH BAYAR</option>
      </select>
    
      <div id="upload-bukti" style="display: none; margin-top: 10px;">
        <label for="bukti">Upload Bukti Pembayaran:</label><br />
        <input type="file" id="bukti" name="bukti" accept="image/*" />
        <div id="preview" style="margin-top: 10px;"></div>
      </div>
      
      <button type="submit" class="button" style="margin-top: 10px;">Simpan Status</button>
    </form>
  </section>
  
  <section id="promo">
    <h2>🔥 Promo</h2>
    <div class="banner">Gratis Ongkir untuk Pembelian > Rp 100.000</div>
  </section>

  <section id="produk">
    <h2>🛍️ Produk</h2>
    <div class="produk-list">
      <?php foreach ($produkData as $produk): ?>
        <div class="produk-item">
          <img src="<?php echo $produk['gambar']; ?>" alt="<?php echo $produk['nama']; ?>">
          <h3><?php echo $produk['nama']; ?></h3>
          <p><?php echo $produk['deskripsi']; ?></p>
          <p><strong>Rp <?php echo number_format($produk['harga'], 0, ',', '.'); ?></strong></p>
          <form method="post">
            <input type="hidden" name="produk_id" value="<?php echo $produk['id']; ?>">
            <button type="submit" name="tambah_ke_keranjang" class="button">Tambah ke Keranjang</button>
          </form>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <section id="info-toko">
    <h2>🏪 Info Toko</h2>
    <p>Alamat: Jl. Contoh No. 1, Yogyakarta</p>
    <p>WA Admin: <a href="https://wa.me/6282398707777" target="_blank">+62 823-9870-7777</a></p>
    <iframe 
      src="https://www.google.com/maps/place/Istana+Sayur+dan+Buah+(ISB)/@-0.8626654,134.0481551,17z/data=!3m1!4b1!4m6!3m5!1s0x2d540b5d3f7765d9:0xf3a2a80e62b26c3!8m2!3d-0.8626654!4d134.05073!16s%2Fg%2F11v3m8qr_q?hl=en-US&entry=ttu&g_ep=EgoyMDI1MDQyNy4xIKXMDSoASAFQAw%3D%3D" 
      width="100%" height="200" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
  </section>

  <script>
    // Toggle upload field visibility
    function toggleUpload() {
      var status = document.getElementById('status').value;
      var uploadDiv = document.getElementById('upload-bukti');
      
      if (status === 'SUDAH BAYAR') {
        uploadDiv.style.display = 'block';
      } else {
        uploadDiv.style.display = 'none';
      }
    }
    
    // Preview uploaded image
    function previewBukti() {
      var preview = document.getElementById('preview');
      var file = document.getElementById('bukti').files[0];
      var reader = new FileReader();
      
      reader.onloadend = function() {
        preview.innerHTML = '<img src="' + reader.result + '" style="max-width: 100%; max-height: 200px;">';
      }
      
      if (file) {
        reader.readAsDataURL(file);
      } else {
        preview.innerHTML = '';
      }
    }
    
    // WhatsApp integration
    function kirimWhatsApp() {
      // In a real application, this would generate a message with order details
      var phone = '6282398707777';
      var message = 'Halo, saya ingin memesan: ';
      
      <?php if (isset($_SESSION['keranjang']) && count($_SESSION['keranjang']) > 0): ?>
        <?php foreach ($_SESSION['keranjang'] as $item): ?>
          message += "<?php echo $item['nama'] . ' (' . $item['jumlah'] . ') - Rp ' . number_format($item['harga'] * $item['jumlah'], 0, ',', '.') . ', '; ?>";
        <?php endforeach; ?>
        
        message += "Total: Rp <?php echo number_format(hitungTotal(), 0, ',', '.'); ?>";
        <?php if (cekGratisOngkir()): ?>
          message += " (dengan Gratis Ongkir)";
        <?php endif; ?>
      <?php endif; ?>
      
      var url = "https://wa.me/" + phone + "?text=" + encodeURIComponent(message);
      window.open(url, '_blank');
    }
  </script>
</body>
</html>