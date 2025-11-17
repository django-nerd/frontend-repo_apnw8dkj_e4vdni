<?php
require_once __DIR__ . '/../header.php';
requireAdmin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$editing = $id > 0;
$product = $editing ? getProduct($id) : null;
$categories = getCategories();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $stock = (int)($_POST['stock'] ?? 0);
    $category_id = (int)($_POST['category_id'] ?? 0);
    $desc = trim($_POST['description'] ?? '');
    $image = handleUpload('image');

    if ($editing) {
        if ($image) {
            if ($product && $product['image']) @unlink(UPLOAD_DIR . '/' . $product['image']);
            $stmt = db()->prepare("UPDATE products SET name=?, description=?, price=?, stock=?, category_id=?, image=? WHERE id=?");
            $stmt->bind_param('ssdiisi', $name, $desc, $price, $stock, $category_id, $image, $id);
        } else {
            $stmt = db()->prepare("UPDATE products SET name=?, description=?, price=?, stock=?, category_id=? WHERE id=?");
            $stmt->bind_param('ssdiis', $name, $desc, $price, $stock, $category_id, $id);
        }
        $ok = $stmt->execute();
        $_SESSION['flash'] = ['type' => $ok ? 'success' : 'danger', 'msg' => $ok ? 'Produk diperbarui.' : 'Gagal update.'];
    } else {
        $stmt = db()->prepare("INSERT INTO products (name, description, price, stock, category_id, image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssdiis', $name, $desc, $price, $stock, $category_id, $image);
        $ok = $stmt->execute();
        $_SESSION['flash'] = ['type' => $ok ? 'success' : 'danger', 'msg' => $ok ? 'Produk ditambahkan.' : 'Gagal menambah produk.'];
    }
    header('Location: ' . BASE_URL . '/admin/products.php'); exit;
}
?>
<h3 class="mb-3"><?= $editing ? 'Edit Produk' : 'Tambah Produk' ?></h3>
<form method="post" enctype="multipart/form-data" class="card p-3">
  <div class="row g-3">
    <div class="col-md-6">
      <label class="form-label">Nama</label>
      <input type="text" name="name" class="form-control" required value="<?= e($product['name'] ?? '') ?>">
    </div>
    <div class="col-md-3">
      <label class="form-label">Harga</label>
      <input type="number" step="0.01" name="price" class="form-control" required value="<?= e($product['price'] ?? '0') ?>">
    </div>
    <div class="col-md-3">
      <label class="form-label">Stok</label>
      <input type="number" name="stock" class="form-control" required value="<?= e($product['stock'] ?? '0') ?>">
    </div>
    <div class="col-md-6">
      <label class="form-label">Kategori</label>
      <select name="category_id" class="form-select">
        <option value="0">Umum</option>
        <?php foreach ($categories as $c): ?>
          <option value="<?= $c['id'] ?>" <?= isset($product['category_id']) && (int)$product['category_id'] === (int)$c['id'] ? 'selected' : '' ?>>
            <?= e($c['name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-6">
      <label class="form-label">Gambar Produk</label>
      <input type="file" name="image" class="form-control" accept="image/*" <?= $editing ? '' : 'required' ?>>
      <?php if ($editing && $product['image']): ?>
        <div class="mt-2">
          <img src="<?= UPLOAD_URL . '/' . e($product['image']) ?>" style="height:80px;border-radius:8px;object-fit:cover">
        </div>
      <?php endif; ?>
    </div>
    <div class="col-12">
      <label class="form-label">Deskripsi</label>
      <textarea name="description" rows="5" class="form-control"><?= e($product['description'] ?? '') ?></textarea>
    </div>
  </div>
  <div class="mt-3">
    <button class="btn btn-accent"><?= $editing ? 'Simpan Perubahan' : 'Simpan' ?></button>
    <a href="<?= BASE_URL ?>/admin/products.php" class="btn btn-outline-secondary">Batal</a>
  </div>
</form>
<?php require_once __DIR__ . '/../footer.php'; ?>
