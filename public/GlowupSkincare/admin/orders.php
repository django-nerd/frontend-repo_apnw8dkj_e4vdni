<?php
require_once __DIR__ . '/../header.php';
requireAdmin();
$orders = db()->query("SELECT o.*, u.name as customer FROM orders o LEFT JOIN users u ON u.id=o.user_id ORDER BY o.created_at DESC")->fetch_all(MYSQLI_ASSOC);

function orderItems($oid): array {
    $stmt = db()->prepare("SELECT oi.*, p.name FROM order_items oi LEFT JOIN products p ON p.id=oi.product_id WHERE oi.order_id=?");
    $stmt->bind_param('i', $oid);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
?>
<h3 class="mb-3">Pesanan</h3>
<div class="table-responsive">
  <table class="table align-middle">
    <thead><tr><th>ID</th><th>Pembeli</th><th>Total</th><th>Tanggal</th><th>Detail</th></tr></thead>
    <tbody>
      <?php foreach ($orders as $o): ?>
        <tr>
          <td>#<?= e($o['id']) ?></td>
          <td><?= e($o['customer'] ?? '-') ?></td>
          <td>Rp <?= number_format($o['total_amount'], 2) ?></td>
          <td><?= e($o['created_at']) ?></td>
          <td>
            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#order-<?= $o['id'] ?>">Lihat</button>
          </td>
        </tr>
        <tr class="collapse" id="order-<?= $o['id'] ?>">
          <td colspan="5">
            <ul class="list-group">
              <?php foreach (orderItems($o['id']) as $it): ?>
                <li class="list-group-item d-flex justify-content-between">
                  <span><?= e($it['name']) ?> (x<?= (int)$it['quantity'] ?>)</span>
                  <span>Rp <?= number_format($it['price'], 2) ?></span>
                </li>
              <?php endforeach; ?>
            </ul>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (!$orders): ?>
        <tr><td colspan="5" class="text-center text-muted">Belum ada pesanan.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
<?php require_once __DIR__ . '/../footer.php'; ?>
