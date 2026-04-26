<?php
/**
 * empruntController.php – Emprunter / Retourner un livre
 * Appelé directement (POST/GET), redirige toujours vers une vue
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/config.php';

// Vérification de connexion
if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "views/auth/login.php");
    exit;
}
require_once __DIR__ . '/../config/db_connect.php';
require_once __DIR__ . '/../models/Emprunt.php';
require_once __DIR__ . '/../models/EmpruntManager.php';
require_once __DIR__ . '/../models/Livre.php';
require_once __DIR__ . '/../models/LivreManager.php';

$action        = $_POST['action'] ?? $_GET['action'] ?? '';
$empruntManager = new EmpruntManager($db);
$livreManager   = new LivreManager($db);

/* ══════════════════════════════════════════════════════════════════
 *  EMPRUNTER
 * ══════════════════════════════════════════════════════════════════ */
if ($action === 'emprunter' && isset($_POST['livre_id'])) {

    $livreId = intval($_POST['livre_id']);
    $userId  = (int) $_SESSION['user_id'];

    $livre = $livreManager->getLivreById($livreId);

    if (!$livre) {
        $_SESSION['error'] = "Livre introuvable.";
    } elseif (!$livre->isDisponible()) {
        $_SESSION['error'] = "Ce livre n'est pas disponible actuellement.";
    } elseif ($empruntManager->hasActiveLoan($userId, $livreId)) {
        $_SESSION['error'] = "Vous avez déjà emprunté ce livre.";
    } else {
        if ($empruntManager->createEmprunt($userId, $livreId)) {
            $livreManager->decrementStock($livreId);
            $_SESSION['success'] = "Livre emprunté avec succès ! À retourner avant le "
                . date('d/m/Y', strtotime('+14 days')) . ".";
        } else {
            $_SESSION['error'] = "Erreur lors de l'emprunt. Réessayez.";
        }
    }

    header("Location: " . BASE_URL . "views/emprunts/panier.php");
    exit;
}

/* ══════════════════════════════════════════════════════════════════
 *  RETOURNER
 * ══════════════════════════════════════════════════════════════════ */
if ($action === 'retourner' && isset($_GET['id'])) {

    $empruntId = intval($_GET['id']);

    // Récupérer le livre_id avant de marquer comme rendu
    $stmt = $db->prepare("SELECT livre_id, utilisateur_id FROM emprunts WHERE id = :id");
    $stmt->execute([':id' => $empruntId]);
    $row = $stmt->fetch();

    // Sécurité : l'emprunt doit appartenir à l'utilisateur connecté (ou admin)
    if (!$row) {
        $_SESSION['error'] = "Emprunt introuvable.";
    } elseif (
        (int)$row['utilisateur_id'] !== (int)$_SESSION['user_id']
        && $_SESSION['user_role'] !== 'admin'
    ) {
        $_SESSION['error'] = "Action non autorisée.";
    } else {
        if ($empruntManager->retournerLivre($empruntId)) {
            $livreManager->incrementStock((int)$row['livre_id']);
            $_SESSION['success'] = "Livre retourné avec succès !";
        } else {
            $_SESSION['error'] = "Erreur lors du retour. Réessayez.";
        }
    }

    header("Location: " . BASE_URL . "views/emprunts/panier.php");
    exit;
}

/* ── Aucune action valide ─────────────────────────────────────── */
header("Location: " . BASE_URL . "views/livres/catalogue.php");
exit;
?>