<?php
require_once __DIR__ . '/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add_to_cart') {
    $pid = (int)($_POST['product_id'] ?? 0);
    $qty = (int)($_POST['qty'] ?? 1);
    $p = getProduct($pid);
    if ($p) {
        addToCart($pid, $qty);
        $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Produk ditambahkan ke keranjang.'];
    }
    header('Location: ' . BASE_URL . '/products.php');
    exit;
}

$categories = getCategories();
$selCat = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$keyword = isset($_GET['q']) ? trim($_GET['q']) : null;
$products = getProducts($selCat ?: null, $keyword ?: null);
?>
<div class="row align-items-center mb-3">
  <div class="col-md-8">
    <div class="d-flex flex-wrap gap-2">
      <a href="<?= BASE_URL ?>/products.php" class="category-chip <?= $selCat ? '' : 'active' ?>">Semua</a>
      <?php foreach ($categories as $c): ?>
        <a href="?category=<?= $c['id'] ?>" class="category-chip <?= $selCat === (int)$c['id'] ? 'active' : '' ?>"><?= e($c['name']) ?></a>
      <?php endforeach; ?>
    </div>
  </div>
  <div class="col-md-4 mt-2 mt-md-0">
    <form class="input-group">
      <input type="text" name="q" class="form-control" placeholder="Cari produk..." value="<?= e($keyword ?? '') ?>">
      <?php if ($selCat): ?><input type="hidden" name="category" value="<?= $selCat ?>"><?php endif; ?>
      <button class="btn btn-outline-secondary">Cari</button>
    </form>
  </div>
</div>

<div class="row g-3 g-md-4">
  <?php foreach ($products as $p): ?>
    <div class="col-6 col-md-4 col-lg-3">
      <div class="card h-100">
        <img src="<?= $p['image'] ? UPLOAD_URL . '/' . e($p['image']) : 'https://via.placeholder.com/600x600?text=Skincare' ?>" class="card-img-top" alt="<?= e($p['name']) ?>">
        <div class="card-body d-flex flex-column">
          <span class="badge rounded-pill bg-light text-dark mb-2"><?= e($p['category_name'] ?? 'Umum') ?></span>
          <h5 class="card-title"><?= e($p['name']) ?></h5>
          <p class="card-text text-muted small flex-grow-1"><?= e(mb_strimwidth($p['description'], 0, 80, '...')) ?></p>
          <div class="d-flex align-items-center justify-content-between mt-2">
            <strong>Rp <?= number_format($p['price'], 2) ?></strong>
            <form method="post">
              <input type="hidden" name="action" value="add_to_cart">
              <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
              <button class="btn btn-sm btn-accent">Tambah</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
  <?php if (!$products): ?>
    <div class="col-12 text-center text-muted">Produk tidak ditemukan.</div>
  <?php endif; ?>
</div>
<?php require_once __DIR__ . '/footer.php'; ?>
