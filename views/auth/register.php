<?php

require_once __DIR__ . '/../../controllers/authController.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription – Bibliothèque</title>
    <link rel="stylesheet" href="../../assets/style/style.css">
</head>
<body>
<div class="library-container">
    <div class="auth-container">
        <div class="auth-card">

            <div class="auth-header">
                <h1>Bibliothèque</h1>
                <h2>Inscription</h2>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert error"><?= $error ?></div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="alert success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <form method="POST" action="register.php" class="auth-form">
                <input type="hidden" name="action" value="register">

                <div class="form-group">
                    <label for="nom">Nom complet</label>
                    <input type="text" name="nom" id="nom"
                           value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email"
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe <small>(min. 6 caractères)</small></label>
                    <input type="password" name="password" id="password" required>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirmer le mot de passe</label>
                    <input type="password" name="confirm_password" id="confirm_password" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block">S'inscrire</button>
            </form>

            <div class="auth-footer">
                <p>Déjà inscrit ? <a href="login.php">Se connecter</a></p>
            </div>

        </div>
    </div>
</div>
</body>
</html>