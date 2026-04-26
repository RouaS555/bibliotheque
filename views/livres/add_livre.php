<?php
/**
 * views/livres/add_livre.php – Formulaire d'ajout de livre (admin)
 */
require_once __DIR__ . '/../../controllers/addLivreController.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un livre – Bibliothèque</title>
    <link rel="stylesheet" href="../../assets/style/style.css">
</head>
<body>
<div class="library-container">

    <header class="library-header">
        <div class="logo"><h1>📚 Bibliothèque</h1></div>
        <nav class="library-nav">
            <a href="catalogue.php">Accueil</a>
            <a href="add_livre.php" class="active">➕ Ajouter un livre</a>
            <a href="../emprunts/panier.php">📖 Mes emprunts</a>
            <span class="user-info">👤 <?= htmlspecialchars($_SESSION['user_nom'] ?? 'Admin') ?></span>
            <a href="../../controllers/logoutController.php">Déconnexion</a>
        </nav>
    </header>

    <main>
        <div class="form-container">
            <h2>➕ Ajouter un nouveau livre</h2>

            <?php if (!empty($message)): ?>
                <div class="alert success"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="alert error"><?= $error /* peut contenir du HTML (balises <br>) */ ?></div>
            <?php endif; ?>

            <!-- Le formulaire poste vers lui-même -->
            <form action="add_livre.php" method="POST" enctype="multipart/form-data">

                <div class="form-row">
                    <div class="form-group">
                        <label for="code">📌 Code du livre *</label>
                        <input type="text" name="code" id="code"
                               placeholder="Ex : LIV001"
                               value="<?= htmlspecialchars($_POST['code'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="titre">📖 Titre *</label>
                        <input type="text" name="titre" id="titre"
                               value="<?= htmlspecialchars($_POST['titre'] ?? '') ?>" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="auteur">✍️ Auteur *</label>
                        <input type="text" name="auteur" id="auteur"
                               value="<?= htmlspecialchars($_POST['auteur'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="edition">🏢 Édition</label>
                        <input type="text" name="edition" id="edition"
                               value="<?= htmlspecialchars($_POST['edition'] ?? '') ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="prix">💰 Prix (TND) *</label>
                        <input type="number" step="0.01" min="0" name="prix" id="prix"
                               value="<?= htmlspecialchars($_POST['prix'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="stock">📦 Stock *</label>
                        <input type="number" min="0" name="stock" id="stock"
                               value="<?= htmlspecialchars($_POST['stock'] ?? '1') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="annee">📅 Année de publication</label>
                        <input type="number" name="annee" id="annee"
                               min="1000" max="<?= date('Y') ?>"
                               value="<?= htmlspecialchars($_POST['annee'] ?? '') ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="categorie">🏷️ Catégorie</label>
                        <input type="text" name="categorie" id="categorie"
                               placeholder="Roman, Science-fiction…"
                               value="<?= htmlspecialchars($_POST['categorie'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="image">🖼️ Image de couverture</label>
                        <input type="file" name="image" id="image" accept="image/*">
                        <small>Formats acceptés : JPG, PNG, GIF, WEBP (Max 2 Mo)</small>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">📝 Description</label>
                    <textarea name="description" id="description"
                              rows="4"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">✅ Ajouter le livre</button>
                    <a href="catalogue.php" class="btn btn-secondary">Annuler</a>
                </div>

            </form>
        </div>
    </main>

    <footer class="library-footer">
        <p>&copy; <?= date('Y') ?> – Bibliothèque Municipale | Tous droits réservés</p>
    </footer>
</div>
</body>
</html>