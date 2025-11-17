<?php
require_once __DIR__ . '/header.php';
if (isLoggedIn()) { header('Location: ' . BASE_URL); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $stmt = db()->prepare("SELECT * FROM users WHERE email=? LIMIT 1");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    if ($user && password_verify($password, $user['password_hash'])) {
        unset($user['password_hash']);
        $_SESSION['user'] = $user;
        $_SESSION['flash'] = ['type'=>'success','msg'=>'Berhasil login.'];
        header('Location: ' . BASE_URL); exit;
    } else {
        $_SESSION['flash'] = ['type'=>'danger','msg'=>'Email atau password salah.'];
    }
}
?>
<div class="row justify-content-center">
  <div class="col-md-6 col-lg-5">
    <div class="card p-4">
      <h3 class="mb-3 text-center">Login</h3>
      <form method="post">
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" class="form-control" name="email" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" class="form-control" name="password" required>
        </div>
        <button class="btn btn-accent w-100">Masuk</button>
      </form>
      <div class="text-center mt-3">
        Belum punya akun? <a href="<?= BASE_URL ?>/register.php">Daftar</a>
      </div>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/footer.php'; ?>
