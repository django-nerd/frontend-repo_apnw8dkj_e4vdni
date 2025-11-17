<?php
require_once __DIR__ . '/header.php';
requireLogin();
$items = cartItems();
if (!$items) {
    $_SESSION['flash'] = ['type'=>'warning','msg'=>'Keranjang masih kosong.'];
    header('Location: ' . BASE_URL . '/products.php'); exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $orderId = saveOrder($_SESSION['user']['id'], $items);
        $_SESSION['cart'] = [];
        $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Pesanan dibuat! ID Pesanan: #' . $orderId . ' (simulasi, belum ada pembayaran).'];
        header('Location: ' . BASE_URL);
        exit;
    } catch (Exception $e) {
        $_SESSION['flash'] = ['type'=>'danger','msg'=>'Gagal membuat pesanan.'];
    }
}
?>
<h3 class="mb-3">Checkout</h3>
<div class="row g-4">
  <div class="col-lg-7">
    <div class="card p-3">
      <h5 class="mb-3">Data Penerima</h5>
      <div class="row g-2">
        <div class="col-md-6">
          <label class="form-label">Nama</label>
          <input type="text" class="form-control" value="<?= e($_SESSION['user']['name']) ?>" disabled>
        </div>
        <div class="col-md-6">
          <label class="form-label">Email</label>
          <input type="email" class="form-control" value="<?= e($_SESSION['user']['email']) ?>" disabled>
        </div>
        <div class="col-12">
          <label class="form-label">Alamat (simulasi)</label>
          <input type="text" class="form-control" placeholder="Alamat pengiriman">
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-5">
    <form method="post" class="card p-3">
      <h5 class="mb-3">Ringkasan Pesanan</h5>
      <ul class="list-group mb-3">
        <?php foreach ($items as $it): ?>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <div>
              <div class="fw-semibold"><?= e($it['name']) ?></div>
              <div class="small text-muted">Qty <?= $it['qty'] ?> × Rp <?= number_format($it['price'], 2) ?></div>
            </div>
            <span>Rp <?= number_format($it['subtotal'], 2) ?></span>
          </li>
        <?php endforeach; ?>
        <li class="list-group-item d-flex justify-content-between">
          <strong>Total</strong>
          <strong>Rp <?= number_format(cartTotal(), 2) ?></strong>
        </li>
      </ul>
      <button class="btn btn-accent w-100">Buat Pesanan</button>
      <div class="form-text mt-2">Tidak ada transaksi nyata. Ini hanya simulasi sampai tombol beli.</div>
    </form>
  </div>
</div>
<?php require_once __DIR__ . '/footer.php'; ?>
