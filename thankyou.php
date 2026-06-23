<?php
require_once __DIR__ . '/db.php';
$orderId = isset($_GET['order']) ? (int)$_GET['order'] : 0;
$order = null;
if ($orderId) {
    $stmt = $pdo->prepare('SELECT * FROM orders WHERE id = ? LIMIT 1');
    $stmt->execute([$orderId]);
    $order = $stmt->fetch();
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Thank you</title>
<link rel="stylesheet" href="style.css"></head><body>
<main class="login-card"><h1>Thank you</h1>
<?php if ($order): ?>
  <p>Your order #<?= $order['id'] ?> has been placed.</p>
  <p>Total: <?=htmlspecialchars('LKR '.number_format($order['total'],2))?></p>
<?php else: ?>
  <p>Order not found.</p>
<?php endif; ?>
<a class="btn light" href="index.php">Continue shopping</a></main></body></html>
