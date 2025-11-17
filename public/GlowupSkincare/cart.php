<?php
require_once __DIR__ . '/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update'])) {
        foreach (($_POST['qty'] ?? []) as $pid => $qty) {
            setCartQty((int)$pid, (int)$qty);
        }
        $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Keranjang diperbarui.'];
        header('Location: ' . BASE_URL . '/cart.php'); exit;
    }
    if (isset($_POST['remove'])) {
        setCartQty((int)$_POST['remove'], 0);
        $_SESSION['flash'] = ['type' => 'info', 'msg' => 'Produk dihapus dari keranjang.'];
        header('Location: ' . BASE_URL . '/cart.php'); exit;
    }
}
$items = cartItems();
?>
<h3 class="mb-3">Keranjang</h3>
<form method="post">
  <div class="table-responsive">
    <table class="table align-middle">
      <thead>
        <tr>
          <th>Produk</th>
          <th class="text-center">Harga</th>
          <th class="text-center">Qty</th>
          <th class="text-end">Subtotal</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($items as $it): ?>
        <tr data-price="<?= e($it['price']) ?>">
          <td>
            <div class="d-flex align-items-center">
              <img src="<?= $it['image'] ? UPLOAD_URL . '/' . e($it['image']) : 'https://via.placeholder.com/80?text=Img' ?>" alt="" class="me-2 rounded" style="width:60px;height:60px;object-fit:cover">
              <div>
                <div class="fw-semibold"><?= e($it['name']) ?></div>
                <div class="text-muted small"><?= e(mb_strimwidth($it['description'], 0, 60, '...')) ?></div>
              </div>
            </div>
          </td>
          <td class="text-center">Rp <?= number_format($it['price'], 2) ?></td>
          <td class="text-center" style="width:120px">
            <input type="number" min="1" class="form-control qty-input" name="qty[<?= $it['id'] ?>]" value="<?= $it['qty'] ?>">
          </td>
          <td class="text-end line-subtotal">Rp <?= number_format($it['subtotal'], 2) ?></td>
          <td class="text-end" style="width:80px">
            <button name="remove" value="<?= $it['id'] ?>" class="btn btn-sm btn-outline-danger">Hapus</button>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (!$items): ?>
        <tr><td colspan="5" class="text-center text-muted">Keranjang kosong.</td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
  <div class="d-flex justify-content-between align-items-center">
    <a href="<?= BASE_URL ?>/products.php" class="btn btn-outline-secondary">Lanjut Belanja</a>
    <div>
      <strong class="me-3">Total: Rp <?= number_format(cartTotal(), 2) ?></strong>
      <button name="update" value="1" class="btn btn-outline-primary me-2">Perbarui</button>
      <a href="<?= BASE_URL ?>/checkout.php" class="btn btn-accent <?= $items ? '' : 'disabled' ?>">Checkout</a>
    </div>
  </div>
</form>
<?php require_once __DIR__ . '/footer.php'; ?>
