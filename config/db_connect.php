<?php
/**
 * Configuration de la base de données
 * Base : bd_bibliotheque | XAMPP / phpMyAdmin
 */

$dsn  = 'mysql:host=localhost;dbname=bd_bibliotheque;charset=utf8mb4';
$user = 'root';
$psw  = '';          // mot de passe vide par défaut sous XAMPP

try {
    $db = new PDO($dsn, $user, $psw);
    $db->setAttribute(PDO::ATTR_ERRMODE,            PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES,   false);
} catch (PDOException $e) {
    die("❌ Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
?>
