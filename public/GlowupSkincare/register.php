<?php
require_once __DIR__ . '/header.php';
if (isLoggedIn()) { header('Location: ' . BASE_URL); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = 'customer';
    if (!$name || !$email || !$password) {
        $_SESSION['flash'] = ['type'=>'danger','msg'=>'Semua field wajib diisi.'];
    } else {
        $stmt = db()->prepare("SELECT id FROM users WHERE email=? LIMIT 1");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        if ($stmt->get_result()->fetch_assoc()) {
            $_SESSION['flash'] = ['type'=>'warning','msg'=>'Email sudah terdaftar.'];
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = db()->prepare("INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param('ssss', $name, $email, $hash, $role);
            if ($stmt->execute()) {
                $_SESSION['flash'] = ['type'=>'success','msg'=>'Registrasi berhasil. Silakan login.'];
                header('Location: ' . BASE_URL . '/login.php'); exit;
            } else {
                $_SESSION['flash'] = ['type'=>'danger','msg'=>'Gagal registrasi.'];
            }
        }
    }
}
?>
<div class="row justify-content-center">
  <div class="col-md-6 col-lg-5">
    <div class="card p-4">
      <h3 class="mb-3 text-center">Daftar</h3>
      <form method="post">
        <div class="mb-3">
          <label class="form-label">Nama</label>
          <input type="text" class="form-control" name="name" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" class="form-control" name="email" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" class="form-control" name="password" required>
        </div>
        <button class="btn btn-accent w-100">Buat Akun</button>
      </form>
      <div class="text-center mt-3">
        Sudah punya akun? <a href="<?= BASE_URL ?>/login.php">Login</a>
      </div>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/footer.php'; ?>
