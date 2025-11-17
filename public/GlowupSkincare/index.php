<?php require_once __DIR__ . '/header.php'; ?>
<section class="hero p-4 p-md-5 mb-4">
  <div class="row align-items-center g-4">
    <div class="col-lg-6">
      <div class="brand-badge mb-3">Skincare Premium • Pastel Aesthetic</div>
      <h1 class="fw-bold mb-3">GlowupSkincare</h1>
      <p class="lead text-muted mb-4">Temukan rangkaian skincare dengan formula lembut, kemasan elegan, dan hasil maksimal. Desain minimalis, warna pastel, hasil glow-up yang terasa.</p>
      <a href="<?= BASE_URL ?>/products.php" class="btn btn-accent btn-lg">Belanja Sekarang</a>
    </div>
    <div class="col-lg-6 text-center">
      <img class="img-fluid" alt="Glowup Banner"
           src="https://images.unsplash.com/photo-1585238341410-32aaee78a4f6?q=80&w=1200&auto=format&fit=crop">
    </div>
  </div>
</section>

<?php
$categories = getCategories();
$selected = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$products = getProducts($selected ?: null, null);
?>
<section class="mb-4">
  <div class="d-flex flex-wrap gap-2">
    <a href="<?= BASE_URL ?>/" class="category-chip <?= $selected ? '' : 'active' ?>">Semua</a>
    <?php foreach ($categories as $cat): ?>
      <a href="<?= BASE_URL ?>/?category=<?= $cat['id'] ?>" class="category-chip <?= $selected === (int)$cat['id'] ? 'active' : '' ?>">
        <?= e($cat['name']) ?>
      </a>
    <?php endforeach; ?>
  </div>
</section>

<section>
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
              <form method="post" action="<?= BASE_URL ?>/products.php">
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
      <div class="col-12 text-center text-muted">Belum ada produk.</div>
    <?php endif; ?>
  </div>
</section>
<?php require_once __DIR__ . '/footer.php'; ?>
