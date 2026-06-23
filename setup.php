<?php
// Run this script once to create the database and sample users.
// Edit the $rootUser/$rootPass variables if your MySQL root user has a password.
$rootUser = 'root';
$rootPass = '';
try {
    $pdo = new PDO('mysql:host=127.0.0.1;charset=utf8mb4', $rootUser, $rootPass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS butter_fashion CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE butter_fashion");
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        is_admin TINYINT(1) DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $pdo->exec("CREATE TABLE IF NOT EXISTS products (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        price DECIMAL(10,2) NOT NULL DEFAULT 0,
        currency VARCHAR(8) DEFAULT 'LKR',
        category VARCHAR(100) DEFAULT 'Uncategorized',
        image VARCHAR(255) DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $pdo->exec("CREATE TABLE IF NOT EXISTS cart_items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        session_id VARCHAR(128) NOT NULL,
        product_id INT NOT NULL,
        qty INT NOT NULL DEFAULT 1,
        added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $pdo->exec("CREATE TABLE IF NOT EXISTS wishlist_items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        session_id VARCHAR(128) NOT NULL,
        product_id INT NOT NULL,
        added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $pdo->exec("CREATE TABLE IF NOT EXISTS orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        phone VARCHAR(50),
        address TEXT,
        total DECIMAL(10,2) NOT NULL DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // Sample products
    $products = [
        ['Basic White Shoes', 'Comfortable white shoes', 2500.00, 'Shoes', 'images/BasicWhiteShoes.avif'],
        ['Long Belly Grey Pant', 'Classic grey pant', 3200.00, 'Men', 'images/Long%20Belly%20Grey%20Pant.avif']
    ];
    $stmtP = $pdo->prepare('INSERT IGNORE INTO products (title,description,price,category,image) VALUES (?,?,?,?,?)');
    foreach ($products as $p) {
        $stmtP->execute([$p[0], $p[1], $p[2], $p[3], $p[4]]);
    }

    $passUser = password_hash('user123', PASSWORD_DEFAULT);
    $passAdmin = password_hash('admin123', PASSWORD_DEFAULT);

    $stmt = $pdo->prepare('INSERT IGNORE INTO users (name,email,password,is_admin) VALUES (?,?,?,?)');
    $stmt->execute(['Demo User','user@demo.test',$passUser,0]);
    $stmt->execute(['Admin','admin@demo.test',$passAdmin,1]);

    echo "Setup completed.\nUsers created:\n - user@demo.test / user123\n - admin@demo.test / admin123\nProducts added.\n";
    echo "\nNow you can run the PHP server and visit index.php, login.php or admin-login.php.\n";
} catch (PDOException $e) {
    die('Setup failed: ' . $e->getMessage());
}
