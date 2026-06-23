<?php
require_once __DIR__ . '/db.php';
$sid = session_id();
// add to wishlist
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['product_id'])) {
    $product_id = (int)$_POST['product_id'];
    $stmt = $pdo->prepare('SELECT id FROM wishlist_items WHERE session_id = ? AND product_id = ? LIMIT 1');
    $stmt->execute([$sid,$product_id]);
    if (!$stmt->fetch()) {
        $pdo->prepare('INSERT INTO wishlist_items (session_id,product_id) VALUES (?,?)')->execute([$sid,$product_id]);
    }
    header('Location: wishlist.php'); exit;
}
// remove
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['remove_id'])) {
    $id = (int)$_POST['remove_id'];
    $pdo->prepare('DELETE FROM wishlist_items WHERE id = ? AND session_id = ?')->execute([$id,$sid]);
    header('Location: wishlist.php'); exit;
}

$stmt = $pdo->prepare('SELECT w.id as wish_id, p.* FROM wishlist_items w JOIN products p ON p.id = w.product_id WHERE w.session_id = ?');
$stmt->execute([$sid]);
$items = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en"><head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishlist | Butter Fashion</title><link rel="stylesheet" href="style.css">
    <script defer src="app.js"></script></head><body>
<header class="topbar"><a class="brand" href="index.php">
    <img src="images/Butter_logo.png" alt="logo"><span>Butter<br><b>Fashion</b></span></a>
    <nav class="nav show-desktop"><a href="index.php#shop">Shop</a><a href="cart.php">Cart</a>
        <a href="dashboard.php">Dashboard</a></nav><div class="icons"><a href="wishlist.php">♡
     <b id="wishCount"><?=count($items)?></b></a><a href="cart.php">🛒 <b id="cartCount">0</b></a></div></header>
<section class="page-hero"><p class="eyebrow">Saved items</p>
    <h1>Your wishlist</h1></section><main class="section"><div class="products grid4" id="wishlistProducts">
<?php if (empty($items)): ?><p>No items in wishlist.</p><?php else: foreach ($items as $it): ?>
    <article class="product-card"><img src="<?=htmlspecialchars($it['image'])?>" alt="<?=htmlspecialchars($it['title'])?>"><h3><?=htmlspecialchars($it['title'])?></h3>
    <p><?=htmlspecialchars($it['currency'])?> <?=number_format($it['price'],2)?></p>
    <form method="post"><input type="hidden" name="remove_id" value="<?= $it['wish_id'] ?>"><button class="btn light">Remove</button></form>
    </article>
<?php endforeach; endif; ?></div>
    </main><footer><b>Butter Fashion</b><span>Wishlist demo page</span></footer></body></html>
