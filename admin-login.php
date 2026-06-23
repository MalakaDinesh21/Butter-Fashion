<?php
require_once __DIR__ . '/db.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    // Allow login by admin email or username -> here we use email field for simplicity
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    if ($user && $user['is_admin'] && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['is_admin'] = $user['is_admin'];
        header('Location: dashboard.php'); exit;
    }
    $error = 'Invalid admin credentials.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Butter Fashion</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="login-body">
    <div class="login-card">
        <div class="brand center">
            <img src="images/Butter_logo.png" alt="Butter Fashion Logo">
        </div>
        <p class="eyebrow">Admin Panel</p>
        <h2>Butter Fashion Dashboard</h2>
        <p style="color:#6f6f6f;">Hint: use admin@demo.test / admin123 (after running setup)</p>
        <?php if ($error): ?><p style="color:red"><?=htmlspecialchars($error)?></p><?php endif; ?>
        <form method="post" class="form-card" style="border:none;box-shadow:none;padding:0;">
            <input type="text" name="username" placeholder="Admin email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" class="btn dark full">Login</button>
        </form>
    </div>
</body>
</html>
