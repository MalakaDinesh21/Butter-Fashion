# Butter Fashion — Local PHP+MySQL Demo

This project was converted to use PHP + MySQL for basic authentication, products, cart, wishlist and orders.

## Requirements (macOS)
- Homebrew
- PHP 8+ (cli & built-in server)
- MySQL (or MariaDB)

## Quick install (Homebrew)
1. Install Homebrew (if needed):
```bash
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```
2. Install PHP and MySQL:
```bash
brew install php
brew install mysql
brew services start mysql
```

## Initialize the database
Edit `setup.php` if your MySQL root user has a password (variables at top). Then run:
```bash
cd /Users/abhshekparindya/Desktop/Web\ Projects/Web\ Designs/butter-fashion
php setup.php
```

## Start the app
```bash
php -S localhost:8000
```

Open in Google Chrome (manually): http://localhost:8000/index.php

## Accounts created by setup.php
- Admin: admin@demo.test / admin123
- User: user@demo.test / user123

If you want, I can help adjust DB credentials, add more seed data, or integrate server-side sessions with logged-in users. I cannot open Chrome from here — you'll need to open the URL locally.
