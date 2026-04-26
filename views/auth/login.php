<?php
/**
 * views/auth/login.php
 */
require_once __DIR__ . '/../../controllers/authController.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion – Bibliothèque</title>
    <link rel="stylesheet" href="../../assets/style/style.css">
</head>
<body>
<div class="library-container">
    <div class="auth-container">
        <div class="auth-card">

            <div class="auth-header">
                <h1>📚 Bibliothèque</h1>
                <h2>Connexion</h2>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert error"><?= $error ?></div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="alert success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <!-- Le formulaire poste vers lui-même ; le contrôleur déjà inclus traite l'action -->
            <form method="POST" action="login.php" class="auth-form">
                <input type="hidden" name="action" value="login">

                <div class="form-group">
                    <label for="email">📧 Email</label>
                    <input type="email" name="email" id="email"
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label for="password">🔒 Mot de passe</label>
                    <input type="password" name="password" id="password" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Se connecter</button>
            </form>

            <div class="auth-footer">
                <p>Pas encore de compte ? <a href="register.php">S'inscrire</a></p>
                <p><a href="../livres/catalogue.php">← Retour au catalogue</a></p>
            </div>

        </div>
    </div>
</div>
</body>
</html>