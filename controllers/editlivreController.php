<?php
/**
 * editlivreController.php – Modification d'un livre (admin uniquement)
 * Inclus depuis views/livres/edit_livre.php
 */
require_once __DIR__ . '/../config/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Garde admin
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    header("Location: " . BASE_URL . "views/livres/catalogue.php");
    exit;
}

require_once __DIR__ . '/../config/db_connect.php';
require_once __DIR__ . '/../models/Livre.php';
require_once __DIR__ . '/../models/LivreManager.php';
require_once __DIR__ . '/../models/FileUploader.php';

$manager = new LivreManager($db);
$message = '';
$error   = '';

$code = trim($_GET['code'] ?? $_POST['code'] ?? '');

if (empty($code)) {
    header("Location: " . BASE_URL . "views/livres/catalogue.php");
    exit;
}

$livre = $manager->getLivreByCode($code);

if (!$livre) {
    $_SESSION['error'] = "Livre introuvable.";
    header("Location: " . BASE_URL . "views/livres/catalogue.php");
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $titre       = trim($_POST['titre']       ?? '');
    $auteur      = trim($_POST['auteur']      ?? '');
    $prix        = floatval($_POST['prix']    ?? 0);
    $stock       = intval($_POST['stock']     ?? 0);
    $categorie   = trim($_POST['categorie']   ?? '');
    $edition     = trim($_POST['edition']     ?? '') ?: null;
    $annee       = !empty($_POST['annee']) ? intval($_POST['annee']) : null;
    $description = trim($_POST['description'] ?? '') ?: null;

    $errors = [];

    if (empty($titre))  $errors[] = "Le titre est obligatoire.";
    if (empty($auteur)) $errors[] = "L'auteur est obligatoire.";
    if ($prix <= 0)     $errors[] = "Le prix doit être positif.";
    if ($stock < 0)     $errors[] = "Le stock ne peut pas être négatif.";


    $imageName = $livre->getImage(); 
    if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        $uploadDir = dirname(__DIR__) . '/public/uploads/livres/';
        $uploader  = new FileUploader($uploadDir);
        $result    = $uploader->upload($_FILES['image']);

        if ($result !== false) {
            $imageName = $result;
        } else {
            $errors = array_merge($errors, $uploader->getErrors());
        }
    }

    if (empty($errors)) {
        $livre->setTitre($titre);
        $livre->setAuteur($auteur);
        $livre->setPrix($prix);
        $livre->setStock($stock);
        $livre->setCategorie($categorie ?: null);
        $livre->setEdition($edition);
        $livre->setAnneePublication($annee);
        $livre->setDescription($description);
        $livre->setImage($imageName);

        if ($manager->update($livre)) {
            $_SESSION['success'] = "Le livre « " . $livre->getTitre() . " » a été mis à jour.";
            header("Location: " . BASE_URL . "views/livres/catalogue.php");
            exit;
        } else {
            $error = "Erreur lors de la mise à jour. Réessayez.";
        }
    } else {
        $error = implode("<br>", $errors);
    }
}
?>