<?php
require_once __DIR__ . '/db.php';
$sid = session_id();
// handle add
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['product_id'])) {
    $product_id = (int)$_POST['product_id'];
    // check if exists -> increment qty
    $stmt = $pdo->prepare('SELECT id,qty FROM cart_items WHERE session_id = ? AND product_id = ? LIMIT 1');
    $stmt->execute([$sid, $product_id]);
    $row = $stmt->fetch();
    if ($row) {
        $pdo->prepare('UPDATE cart_items SET qty = qty + 1 WHERE id = ?')->execute([$row['id']]);
    } else {
        $pdo->prepare('INSERT INTO cart_items (session_id,product_id,qty) VALUES (?,?,1)')->execute([$sid,$product_id]);
    }
    header('Location: cart.php'); exit;
}
// handle remove
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['remove_id'])) {
    $id = (int)$_POST['remove_id'];
    $pdo->prepare('DELETE FROM cart_items WHERE id = ? AND session_id = ?')->execute([$id,$sid]);
    header('Location: cart.php'); exit;
}

$stmt = $pdo->prepare('SELECT c.id as cart_id, c.qty, p.* FROM cart_items c JOIN products p ON p.id = c.product_id WHERE c.session_id = ?');
$stmt->execute([$sid]);
$items = $stmt->fetchAll();
$subtotal = 0.0;
foreach ($items as $it) { $subtotal += $it['qty'] * $it['price']; }
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Cart | Butter Fashion</title>
        <link rel="stylesheet" href="style.css"><script defer src="app.js"></script></head><body>
<header class="topbar"><a class="brand" href="index.php"><img src="images/Butter_logo.png" alt="logo"><span>Butter<br>
    <b>Fashion</b></span></a><nav class="nav show-desktop"><a href="index.php#shop">Shop</a>
        <a href="wishlist.php">Wishlist</a><a href="checkout.php">Checkout</a>
        <a href="dashboard.php">Dashboard</a></nav><div class="icons"><a href="wishlist.php">♡ <b id="wishCount">0</b></a><a href="cart.php">🛒 <b id="cartCount"><?=count($items)?></b></a></div></header>
<section class="page-hero"><p class="eyebrow">Shopping bag</p><h1>Your cart</h1>
</section><main class="cart-layout"><section id="cartItems" class="cart-list">

<?php if (empty($items)): ?>
    <p>Your cart is empty.</p>
<?php else: foreach ($items as $it): ?>
    <div class="cart-item">
        <img src="<?=htmlspecialchars($it['image'])?>" alt="<?=htmlspecialchars($it['title'])?>">
        <div><h3><?=htmlspecialchars($it['title'])?></h3>
        <p>Qty: <?= $it['qty'] ?> • <?=htmlspecialchars($it['currency'])?> <?=number_format($it['price'],2)?></p>
        <form method="post"><input type="hidden" name="remove_id" value="<?= $it['cart_id'] ?>"><button class="btn light">Remove</button></form>
        </div>
    </div>
<?php endforeach; endif; ?>

</section><aside class="summary-card"><h2>Order summary</h2><div class="summary-line">
    <span>Subtotal</span>
    <b id="subtotal"><?=htmlspecialchars('LKR '.number_format($subtotal,2))?></b></div><p>Coupon BUTTER10 can be applied at checkout.</p>
    <a class="btn dark full" href="checkout.php">Proceed to checkout</a>
    <a class="btn light full" href="index.php#shop">Continue shopping</a></aside></main>
    <footer><b>Butter Fashion</b><span>Secure demo cart</span></footer></body></html>
