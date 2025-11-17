<?php
require_once __DIR__ . '/../header.php';
requireAdmin();
$user = $_SESSION['user'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? $user['name']);
    $email = trim($_POST['email'] ?? $user['email']);
    $pass = $_POST['password'] ?? '';

    $stmt = db()->prepare("SELECT id FROM users WHERE email=? AND id<>?");
    $stmt->bind_param('si', $email, $user['id']);
    $stmt->execute();
    if ($stmt->get_result()->fetch_assoc()) {
        $_SESSION['flash'] = ['type'=>'warning','msg'=>'Email sudah digunakan.'];
    } else {
        if ($pass) {
            $hash = password_hash($pass, PASSWORD_BCRYPT);
            $stmt = db()->prepare("UPDATE users SET name=?, email=?, password_hash=? WHERE id=?");
            $stmt->bind_param('sssi', $name, $email, $hash, $user['id']);
        } else {
            $stmt = db()->prepare("UPDATE users SET name=?, email=? WHERE id=?");
            $stmt->bind_param('ssi', $name, $email, $user['id']);
        }
        $ok = $stmt->execute();
        if ($ok) {
            $_SESSION['user']['name'] = $name;
            $_SESSION['user']['email'] = $email;
            $_SESSION['flash'] = ['type'=>'success','msg'=>'Profil diperbarui.'];
        } else {
            $_SESSION['flash'] = ['type'=>'danger','msg'=>'Gagal memperbarui profil.'];
        }
    }
    header('Location: ' . BASE_URL . '/admin/profile.php'); exit;
}
?>
<h3 class="mb-3">Edit Profil Admin</h3>
<form method="post" class="card p-3">
  <div class="row g-3">
    <div class="col-md-6">
      <label class="form-label">Nama</label>
      <input type="text" name="name" class="form-control" value="<?= e($user['name']) ?>" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">Email</label>
      <input type="email" name="email" class="form-control" value="<?= e($user['email']) ?>" required>
    </div>
    <div class="col-12">
      <label class="form-label">Password (kosongkan jika tidak diubah)</label>
      <input type="password" name="password" class="form-control">
    </div>
  </div>
  <div class="mt-3">
    <button class="btn btn-accent">Simpan</button>
    <a href="<?= BASE_URL ?>/admin" class="btn btn-outline-secondary">Kembali</a>
  </div>
</form>
<?php require_once __DIR__ . '/../footer.php'; ?>
