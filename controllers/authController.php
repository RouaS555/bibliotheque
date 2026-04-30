<?php



if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db_connect.php';
require_once __DIR__ . '/../models/Utilisateur.php';
require_once __DIR__ . '/../models/UtilisateurManager.php';

$manager = new UtilisateurManager($db);
$error   = '';
$success = '';

$action = $_GET['action'] ?? $_POST['action'] ?? '';

if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {

    $email    = trim($_POST['email']    ?? '');
    $password =      $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Veuillez remplir tous les champs.";
    } else {
        $user = $manager->authenticate($email, $password);

        if ($user) {
            $_SESSION['user_id']    = $user->getId();
            $_SESSION['user_nom']   = $user->getNom();
            $_SESSION['user_email'] = $user->getEmail();
            $_SESSION['user_role']  = $user->getRole();


            header("Location: " . BASE_URL . "views/livres/catalogue.php");
            exit;
        } else {
            $error = "Email ou mot de passe incorrect.";
        }
    }
}


if ($action === 'register' && $_SERVER['REQUEST_METHOD'] === 'POST') {

    $nom              = trim($_POST['nom']              ?? '');
    $email            = trim($_POST['email']            ?? '');
    $password         =      $_POST['password']         ?? '';
    $confirm_password =      $_POST['confirm_password'] ?? '';

    $errors = [];

    if (empty($nom))                                $errors[] = "Le nom est obligatoire.";
    if (empty($email))                              $errors[] = "L'email est obligatoire.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Format d'email invalide.";
    if (strlen($password) < 6)                      $errors[] = "Mot de passe : minimum 6 caractères.";
    if ($password !== $confirm_password)            $errors[] = "Les mots de passe ne correspondent pas.";
    if ($manager->findByEmail($email))              $errors[] = "Cet email est déjà utilisé.";

    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $user = new Utilisateur($nom, $email, $hashedPassword, 'membre');

        if ($manager->insert($user)) {
            $success = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
        } else {
            $error = "Erreur lors de l'inscription. Réessayez.";
        }
    } 
}
?>