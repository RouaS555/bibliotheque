<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/config.php';
$_SESSION = [];
session_destroy();
header("Location: " . BASE_URL . "views/auth/login.php");

exit;
?>