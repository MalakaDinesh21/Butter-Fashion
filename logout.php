<?php
require_once __DIR__ . '/db.php';
// Destroy session and redirect to public site
session_unset();
session_destroy();
header('Location: index.html');
exit;
