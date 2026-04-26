<?php
/**
 * deleteLivreController.php – Suppression d'un livre (admin uniquement)
 * Appelé via GET : ?code=LIV001
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/config.php';

// Garde admin
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    header("Location: " . BASE_URL . "views/livres/catalogue.php");
    exit;
}

require_once __DIR__ . '/../config/db_connect.php';
require_once __DIR__ . '/../models/Livre.php';
require_once __DIR__ . '/../models/LivreManager.php';

$code = trim($_GET['code'] ?? '');

if (empty($code)) {
    $_SESSION['error'] = "Code du livre manquant.";
    header("Location: " . BASE_URL . "views/livres/catalogue.php");
    exit;
}

$manager = new LivreManager($db);
$livre   = $manager->getLivreByCode($code);

if (!$livre) {
    $_SESSION['error'] = "Livre introuvable.";
} else {
    if ($manager->delete($code)) {
        $_SESSION['success'] = "Le livre « " . $livre->getTitre() . " » a été supprimé.";
    } else {
        $_SESSION['error'] = "Erreur lors de la suppression. Réessayez.";
    }
}

header("Location: " . BASE_URL . "views/livres/catalogue.php");
exit;
?>