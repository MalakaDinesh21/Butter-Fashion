<?php
require_once __DIR__ . '/db.php';
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$stmt = $pdo->prepare('SELECT id,name,email,is_admin FROM users WHERE id = ? LIMIT 1');
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
if (!$user) {
    session_destroy();
    header('Location: login.php'); exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Butter Fashion</title>
    <link rel="stylesheet" href="style.css">
    <script defer src="app.js"></script>
</head>
<body>
<header class="admin-top">
    <a class="brand" href="index.html">
        <img src="images/Butter_logo.png" alt="logo">
    <span><br><b></b></span></a>
    <div><a class="btn light" href="index.html">View website</a>
        <a class="btn dark" href="logout.php">Logout</a></div></header>
<main class="dashboard-shell">
    <aside class="side-nav">
        <button data-tab="overview" class="active">Overview</button>
        <button data-tab="products">Products</button>
        <button data-tab="orders">Orders</button>
        <button data-tab="customers">Customers</button>
        <button data-tab="categories">Categories</button>
        <button data-tab="coupons">Coupons</button>
        <button data-tab="settings">Settings</button>
    </aside>
    <section class="dashboard-content">
        <div class="dash-tab active" id="tab-overview">
            <h1>Welcome, <?=htmlspecialchars($user['name'])?></h1>
            <p>Email: <?=htmlspecialchars($user['email'])?></p>
            <p>Role: <?= $user['is_admin'] ? 'Administrator' : 'User' ?></p>
        </div>
        <!-- The rest of the admin UI is left as static HTML from the original dashboard.html -->
    </section>
</main>
</body>
</html>
