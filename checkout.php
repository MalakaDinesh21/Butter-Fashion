<?php
require_once __DIR__ . '/db.php';
$sid = session_id();
$stmt = $pdo->prepare('SELECT c.id as cart_id, c.qty, p.* FROM cart_items c JOIN products p ON p.id = c.product_id WHERE c.session_id = ?');
$stmt->execute([$sid]);
$items = $stmt->fetchAll();
$total = 0.0; foreach ($items as $it) { $total += $it['qty'] * $it['price']; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['customerName'] ?? '';
    $email = $_POST['customerEmail'] ?? '';
    $phone = $_POST['customerPhone'] ?? '';
    $address = $_POST['customerAddress'] ?? '';
    if ($name && $email) {
        $pdo->prepare('INSERT INTO orders (name,email,phone,address,total) VALUES (?,?,?,?,?)')
            ->execute([$name,$email,$phone,$address,$total]);
        // clear cart
        $pdo->prepare('DELETE FROM cart_items WHERE session_id = ?')->execute([$sid]);
        $orderId = $pdo->lastInsertId();
        header('Location: thankyou.php?order=' . $orderId);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | Butter Fashion</title><link rel="stylesheet" href="style.css">
    <script defer src="app.js"></script></head><body>
<header class="topbar"><a class="brand" href="index.php">
    <img src="images/Butter_logo.png" alt="logo">
    <span>Butter<br><b>Fashion</b></span></a>
    <nav class="nav show-desktop">
        <a href="index.php#shop">Shop</a><a href="cart.php">Cart</a>
        <a href="dashboard.php">Dashboard</a></nav><div class="icons">
            <a href="cart.php">🛒 <b id="cartCount"><?=count($items)?></b></a></div></header>
<section class="page-hero"><p class="eyebrow">Checkout</p><h1>Complete your order</h1>
</section><main class="checkout-layout"><form method="post" id="checkoutForm" class="form-card"><h2>Delivery details</h2>
    <input required id="customerName" name="customerName" placeholder="Full name">
    <input required id="customerEmail" name="customerEmail" type="email" placeholder="Email address">
    <input required id="customerPhone" name="customerPhone" placeholder="Phone number">
    <textarea required id="customerAddress" name="customerAddress" placeholder="Delivery address"></textarea>
    <input id="couponCode" name="couponCode" placeholder="Coupon code">
    <button class="btn dark full">Place order</button></form>
    <aside class="summary-card"><h2>Cart total</h2>
        <div id="checkoutItems">
            <?php if (empty($items)): ?>
                <p>No items in cart.</p>
            <?php else: foreach ($items as $it): ?>
                <div class="checkout-line"><span><?=htmlspecialchars($it['title'])?> × <?= $it['qty'] ?></span><b><?=htmlspecialchars($it['currency'])?> <?=number_format($it['price'] * $it['qty'],2)?></b></div>
            <?php endforeach; endif; ?>
        </div>
        <div class="summary-line">
            <span>Total</span>
            <b id="checkoutTotal"><?=htmlspecialchars('LKR '.number_format($total,2))?></b></div></aside></main>
            <footer><b>Butter Fashion</b><span>Checkout saves orders to admin dashboard</span></footer></body></html>
