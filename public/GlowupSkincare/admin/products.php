<?php
require_once __DIR__ . '/../header.php';
requireAdmin();

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $p = getProduct($id);
    if ($p) {
        $stmt = db()->prepare("DELETE FROM products WHERE id=?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        if ($p['image']) @unlink(UPLOAD_DIR . '/' . $p['image']);
        $_SESSION['flash'] = ['type'=>'success','msg'=>'Produk dihapus.'];
    }
    header('Location: ' . BASE_URL . '/admin/products.php'); exit;
}

$products = db()->query("SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON c.id=p.category_id ORDER BY p.created_at DESC")->fetch_all(MYSQLI_ASSOC);
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="mb-0">Kelola Produk</h3>
  <a class="btn btn-accent" href="<?= BASE_URL ?>/admin/product_form.php">Tambah Produk</a>
</div>
<div class="table-responsive">
  <table class="table align-middle">
    <thead>
      <tr><th>Gambar</th><th>Nama</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Tanggal</th><th class="text-end">Aksi</th></tr>
    </thead>
    <tbody>
      <?php foreach ($products as $p): ?>
        <tr>
          <td><img src="<?= $p['image'] ? UPLOAD_URL . '/' . e($p['image']) : 'https://via.placeholder.com/60?text=Img' ?>" style="width:60px;height:60px;object-fit:cover" class="rounded"></td>
          <td><?= e($p['name']) ?></td>
          <td><?= e($p['category_name'] ?? '-') ?></td>
          <td>Rp <?= number_format($p['price'], 2) ?></td>
          <td><?= (int)$p['stock'] ?></td>
          <td><?= e($p['created_at']) ?></td>
          <td class="text-end">
            <a class="btn btn-sm btn-outline-secondary" href="<?= BASE_URL ?>/admin/product_form.php?id=<?= $p['id'] ?>">Edit</a>
            <a class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus produk ini?')" href="?delete=<?= $p['id'] ?>">Hapus</a>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (!$products): ?>
        <tr><td colspan="7" class="text-center text-muted">Belum ada produk.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
<?php require_once __DIR__ . '/../footer.php'; ?>
