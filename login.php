<?php
require_once __DIR__ . '/db.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';
    if ($email) {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['is_admin'] = $user['is_admin'];
            header('Location: dashboard.php');
            exit;
        }
    }
    $error = 'Invalid credentials.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Butter Fashion</title>
    <link rel="stylesheet" href="style.css">
    <script defer src="app.js"></script>
</head>
<body class="login-body">
    <main class="login-card">
        <a class="brand center" href="index.html">
            <img src="images/Butter_logo.png" alt="logo">
            <span>Butter<br><b>Fashion</b></span>
        </a>
        <h1>Login</h1>
        <?php if ($error): ?>
            <p style="color:red"><?=htmlspecialchars($error)?></p>
        <?php endif; ?>
        <form method="post" id="loginForm">
            <input name="email" type="email" placeholder="Email" required>
            <input name="password" type="password" placeholder="Password" required>
            <button class="btn dark full">Login</button>
        </form>
        <a href="index.html">← Back to website</a>
    </main>
</body>
</html>
