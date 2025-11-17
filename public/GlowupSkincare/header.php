<?php require_once __DIR__ . '/functions.php'; ?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e(APP_NAME) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --soft-pink: #f8d0e5;
      --soft-pink-2: #f5c3dd;
      --soft-pink-3: #f3b1d3;
      --accent: #ff6fa7;
      --text: #333;
      --bg: #fff;
    }
    body { font-family: 'Poppins', sans-serif; background: #fff; color: var(--text); }
    .navbar { background: linear-gradient(90deg, var(--soft-pink), #ffeaf4); }
    .hero {
      background: linear-gradient(135deg, #ffeaf4, #fff);
      border-radius: 18px;
    }
    .brand-badge {
      background: var(--soft-pink-2);
      color: #6b2d53;
      padding: 6px 12px;
      border-radius: 999px;
      font-weight: 600;
      display: inline-block;
    }
    .btn-accent {
      background: var(--accent);
      color: #fff;
      border: none;
    }
    .btn-accent:hover { background: #ff5999; color: #fff; }
    .card {
      border: none;
      border-radius: 16px;
      box-shadow: 0 8px 24px rgba(246, 117, 169, 0.15);
    }
    .category-chip {
      background: #fff;
      border: 1px solid #ffd3e8;
      color: #ba3e7a;
      padding: 6px 12px;
      border-radius: 999px;
      cursor: pointer;
      text-decoration: none;
    }
    .category-chip.active, .category-chip:hover {
      background: var(--soft-pink-3);
      color: white;
    }
    footer { background: #fff0f7; }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold" href="<?= BASE_URL ?>">
      <img src="https://img.icons8.com/?size=24&id=v2c2qfFSg9uS&format=png&color=8A2A52" alt="" class="me-2">
      <?= e(APP_NAME) ?>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div id="nav" class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/products.php">Produk</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/cart.php">Keranjang</a></li>
        <?php if (isAdmin()): ?>
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/admin">Admin</a></li>
        <?php endif; ?>
      </ul>
      <ul class="navbar-nav">
        <?php if (isLoggedIn()): ?>
          <li class="nav-item me-2 align-self-center text-muted small">Halo, <?= e($_SESSION['user']['name']) ?></li>
          <li class="nav-item"><a class="btn btn-sm btn-outline-danger" href="<?= BASE_URL ?>/logout.php">Logout</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="btn btn-sm btn-outline-secondary me-2" href="<?= BASE_URL ?>/login.php">Login</a></li>
          <li class="nav-item"><a class="btn btn-sm btn-accent" href="<?= BASE_URL ?>/register.php">Register</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<main class="container my-4">
<?php flash(); ?>
