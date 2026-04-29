<?php


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db_connect.php';
require_once __DIR__ . '/../models/Livre.php';
require_once __DIR__ . '/../models/LivreManager.php';

$manager = new LivreManager($db);
$searchTitre = '';
$searchAuteur = '';
$searchCategorie = '';
$categories = $manager->findAllCategories();


if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'search') {
    $searchTitre = trim($_GET['search'] ?? '');
    $searchAuteur = trim($_GET['auteur'] ?? '');
    $searchCategorie = trim($_GET['categorie'] ?? '');
    $livres = $manager->search($searchTitre, $searchCategorie, $searchAuteur);
} else {
    $livres = $manager->getAllLivres();
}


$successMsg = $_GET['success'] ?? '';
?>