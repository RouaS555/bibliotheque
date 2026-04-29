<?php

require_once __DIR__ . '/../config/config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/db_connect.php';
require_once __DIR__ . '/../models/Livre.php';
require_once __DIR__ . '/../models/LivreManager.php';
require_once __DIR__ . '/../models/FileUploader.php';

$message = '';
$error   = '';


if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
     header("Location: " . BASE_URL . "views/livres/catalogue.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

   
    $code        = trim($_POST['code']        ?? '');
    $titre       = trim($_POST['titre']       ?? '');
    $auteur      = trim($_POST['auteur']      ?? '');
    $prix        = floatval($_POST['prix']    ?? 0);
    $stock       = intval($_POST['stock']     ?? 0);
    $categorie   = trim($_POST['categorie']   ?? '');
    $edition     = trim($_POST['edition']     ?? '') ?: null;
    $annee       = !empty($_POST['annee']) ? intval($_POST['annee']) : null;
    $description = trim($_POST['description'] ?? '') ?: null;

    $errors = [];

    if (empty($code))   $errors[] = "Le code du livre est obligatoire.";
    if (empty($titre))  $errors[] = "Le titre est obligatoire.";
    if (empty($auteur)) $errors[] = "L'auteur est obligatoire.";
    if ($prix <= 0)     $errors[] = "Le prix doit être positif.";
    if ($stock < 0)     $errors[] = "Le stock ne peut pas être négatif.";

    /* ── Upload de l'image ────────────────────────────────────────── */
    $imageName = 'default.jpg';
    if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        // Chemin absolu vers public/uploads/livres/
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

        $manager  = new LivreManager($db);

  
        if ($manager->getLivreByCode($code)) {
            $error = "Un livre avec le code « $code » existe déjà.";
        } else {
            $livre = new Livre(
                $code, $titre, $auteur, $prix, $stock,
                $categorie, $edition, $annee, $description, $imageName
            );

            if ($manager->insert($livre)) {
               header("Location: " . BASE_URL . "views/livres/catalogue.php?success=1");
                exit;
            } else {
                $error = "Erreur lors de l'ajout du livre. Réessayez.";
            }
        }
    } else {
        $error = implode("<br>", $errors);
    }
}
?>