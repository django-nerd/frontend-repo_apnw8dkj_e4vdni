<?php
require_once __DIR__ . '/../header.php';
requireAdmin();

// Statistik sederhana
$totalProducts = db()->query("SELECT COUNT(*) as c FROM products")->fetch_assoc()['c'] ?? 0;
$totalOrders = db()->query("SELECT COUNT(*) as c FROM orders")->fetch_assoc()['c'] ?? 0;
$totalUsers = db()->query("SELECT COUNT(*) as c FROM users")->fetch_assoc()['c'] ?? 0;
$latestOrders = db()->query("SELECT o.*, u.name as customer FROM orders o LEFT JOIN users u ON u.id=o.user_id ORDER BY o.created_at DESC LIMIT 5")->fetch_all(MYSQLI_ASSOC);
?>
<h3 class="mb-4">Dashboard Admin</h3>
<div class="row g-3 mb-4">
  <div class="col-md-4">
    <div class="card p-3">
      <div class="text-muted">Produk</div>
      <div class="display-6"><?= (int)$totalProducts ?></div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card p-3">
      <div class="text-muted">Pesanan</div>
      <div class="display-6"><?= (int)$totalOrders ?></div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card p-3">
      <div class="text-muted">Pengguna</div>
      <div class="display-6"><?= (int)$totalUsers ?></div>
    </div>
  </div>
</div>
<div class="card p-3">
  <div class="d-flex justify-content-between align-items-center mb-2">
    <h5 class="mb-0">Pesanan Terbaru</h5>
    <a class="btn btn-sm btn-outline-secondary" href="<?= BASE_URL ?>/admin/orders.php">Lihat Semua</a>
  </div>
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead><tr><th>ID</th><th>Pembeli</th><th>Total</th><th>Tanggal</th></tr></thead>
      <tbody>
        <?php foreach ($latestOrders as $o): ?>
          <tr>
            <td>#<?= e($o['id']) ?></td>
            <td><?= e($o['customer'] ?? '-') ?></td>
            <td>Rp <?= number_format($o['total_amount'], 2) ?></td>
            <td><?= e($o['created_at']) ?></td>
          </tr>
        <?php endforeach; ?>
        <?php if (!$latestOrders): ?>
          <tr><td colspan="4" class="text-center text-muted">Belum ada pesanan.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<div class="mt-4 d-flex gap-2">
  <a href="<?= BASE_URL ?>/admin/products.php" class="btn btn-accent">Kelola Produk</a>
  <a href="<?= BASE_URL ?>/admin/profile.php" class="btn btn-outline-secondary">Edit Profil</a>
</div>
<?php require_once __DIR__ . '/../footer.php'; ?>
