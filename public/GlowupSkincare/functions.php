<?php
require_once __DIR__ . '/db.php';

function e(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function isLoggedIn(): bool {
    return isset($_SESSION['user']);
}

function isAdmin(): bool {
    return isLoggedIn() && $_SESSION['user']['role'] === 'admin';
}

function requireLogin(): void {
    if (!isLoggedIn()) {
        $_SESSION['flash'] = ['type' => 'warning', 'msg' => 'Silakan login terlebih dahulu.'];
        header('Location: ' . BASE_URL . '/login.php');
        exit;
    }
}

function requireAdmin(): void {
    if (!isAdmin()) {
        $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Akses admin diperlukan.'];
        header('Location: ' . BASE_URL);
        exit;
    }
}

function flash(): void {
    if (!empty($_SESSION['flash'])) {
        $type = e($_SESSION['flash']['type'] ?? 'info');
        $msg = e($_SESSION['flash']['msg'] ?? '');
        echo "<div class='alert alert-$type alert-dismissible fade show' role='alert'>$msg<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
        unset($_SESSION['flash']);
    }
}

function getCategories(): array {
    $res = db()->query("SELECT id, name FROM categories ORDER BY name");
    return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}

function getProducts(?int $categoryId = null, ?string $keyword = null): array {
    $sql = "SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON c.id=p.category_id WHERE 1=1";
    $types = '';
    $params = [];
    if ($categoryId) {
        $sql .= " AND p.category_id = ?";
        $types .= 'i';
        $params[] = $categoryId;
    }
    if ($keyword) {
        $sql .= " AND (p.name LIKE ? OR p.description LIKE ?)";
        $types .= 'ss';
        $kw = '%' . $keyword . '%';
        $params[] = $kw; $params[] = $kw;
    }
    $sql .= " ORDER BY p.created_at DESC";
    $stmt = db()->prepare($sql);
    if ($types) $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $res = $stmt->get_result();
    return $res->fetch_all(MYSQLI_ASSOC);
}

function getProduct(int $id): ?array {
    $stmt = db()->prepare("SELECT * FROM products WHERE id=? LIMIT 1");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    return $res ?: null;
}

function addToCart(int $productId, int $qty = 1): void {
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    if (!isset($_SESSION['cart'][$productId])) $_SESSION['cart'][$productId] = 0;
    $_SESSION['cart'][$productId] += max(1, $qty);
}

function setCartQty(int $productId, int $qty): void {
    if ($qty <= 0) {
        unset($_SESSION['cart'][$productId]);
    } else {
        $_SESSION['cart'][$productId] = $qty;
    }
}

function cartItems(): array {
    $items = [];
    $cart = $_SESSION['cart'] ?? [];
    if (!$cart) return [];
    $ids = array_keys($cart);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $types = str_repeat('i', count($ids));
    $stmt = db()->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->bind_param($types, ...$ids);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($p = $res->fetch_assoc()) {
        $qty = $cart[$p['id']];
        $p['qty'] = $qty;
        $p['subtotal'] = $qty * $p['price'];
        $items[] = $p;
    }
    return $items;
}

function cartTotal(): float {
    return array_reduce(cartItems(), function($sum, $item) { return $sum + $item['subtotal']; }, 0.0);
}

function saveOrder(int $userId, array $items): int {
    $conn = db();
    $conn->begin_transaction();
    try {
        $total = 0;
        foreach ($items as $it) $total += $it['subtotal'];
        $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount) VALUES (?, ?)");
        $stmt->bind_param('id', $userId, $total);
        $stmt->execute();
        $orderId = $stmt->insert_id;

        $stmtItem = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        foreach ($items as $it) {
            $pid = $it['id']; $q = $it['qty']; $pr = $it['price'];
            $stmtItem->bind_param('iiid', $orderId, $pid, $q, $pr);
            $stmtItem->execute();
        }
        $conn->commit();
        return $orderId;
    } catch (Exception $e) {
        $conn->rollback();
        throw $e;
    }
}

function handleUpload(string $field, array $allowed = ['image/jpeg','image/png','image/webp']): ?string {
    if (empty($_FILES[$field]) || $_FILES[$field]['error'] !== UPLOAD_ERR_OK) return null;
    $tmp = $_FILES[$field]['tmp_name'];
    $type = mime_content_type($tmp);
    if (!in_array($type, $allowed, true)) return null;
    if (!is_dir(UPLOAD_DIR)) mkdir(UPLOAD_DIR, 0775, true);
    $ext = pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION);
    $name = 'img_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . strtolower($ext);
    $dest = UPLOAD_DIR . '/' . $name;
    if (move_uploaded_file($tmp, $dest)) {
        return $name;
    }
    return null;
}
?>
