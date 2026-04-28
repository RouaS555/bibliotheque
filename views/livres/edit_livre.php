<?php
/**
 * views/livres/edit_livre.php – Formulaire de modification de livre (admin)
 */
require_once __DIR__ . '/../../controllers/editlivreController.php';

// Safety net: if $livre is not loaded, stop here — never render the page
if (!isset($livre) || $livre === null) {
    die("Erreur : livre non chargé. <a href='catalogue.php'>Retour au catalogue</a>");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un livre – Bibliothèque</title>
    <link rel="stylesheet" href="../../assets/style/style.css">
</head>
<body>
<div class="library-container">

    <header class="library-header">
        <div class="logo"><h1>Bibliothèque</h1></div>
        <nav class="library-nav">
            <a href="catalogue.php">Accueil</a>
            <a href="add_livre.php">Ajouter un livre</a>
            <a href="../emprunts/panier.php">Mes emprunts</a>
            <span class="user-info"><?= htmlspecialchars($_SESSION['user_nom'] ?? 'Admin') ?></span>
            <a href="../../controllers/logoutController.php">Déconnexion</a>
        </nav>
    </header>

    <main>
        <div class="form-container">
            <h2> Modifier le livre</h2>

            <?php if (!empty($message)): ?>
                <div class="alert success"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="alert error"><?= $error /* peut contenir du HTML */ ?></div>
            <?php endif; ?>

            <form action="edit_livre.php?code=<?= urlencode($livre->getCode()) ?>"
                  method="POST" enctype="multipart/form-data">

                <!-- Code (lecture seule – non modifiable) -->
                <input type="hidden" name="code" value="<?= htmlspecialchars($livre->getCode()) ?>">

                <div class="form-row">
                    <div class="form-group">
                        <label>Code du livre</label>
                        <input type="text" value="<?= htmlspecialchars($livre->getCode()) ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="titre">Titre *</label>
                        <input type="text" name="titre" id="titre"
                               value="<?= htmlspecialchars($_POST['titre'] ?? $livre->getTitre()) ?>"
                               required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="auteur"> Auteur *</label>
                        <input type="text" name="auteur" id="auteur"
                               value="<?= htmlspecialchars($_POST['auteur'] ?? $livre->getAuteur()) ?>"
                               required>
                    </div>
                    <div class="form-group">
                        <label for="edition"> Édition</label>
                        <input type="text" name="edition" id="edition"
                               value="<?= htmlspecialchars($_POST['edition'] ?? $livre->getEdition() ?? '') ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="prix">Prix (TND) *</label>
                        <input type="number" step="0.01" min="0" name="prix" id="prix"
                               value="<?= htmlspecialchars($_POST['prix'] ?? $livre->getPrix()) ?>"
                               required>
                    </div>
                    <div class="form-group">
                        <label for="stock"> Stock *</label>
                        <input type="number" min="0" name="stock" id="stock"
                               value="<?= htmlspecialchars($_POST['stock'] ?? $livre->getStock()) ?>"
                               required>
                    </div>
                    <div class="form-group">
                        <label for="annee">Année de publication</label>
                        <input type="number" name="annee" id="annee"
                               min="1000" max="<?= date('Y') ?>"
                               value="<?= htmlspecialchars($_POST['annee'] ?? $livre->getAnneePublication() ?? '') ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="categorie">Catégorie</label>
                        <input type="text" name="categorie" id="categorie"
                               placeholder="Roman, Science-fiction…"
                               value="<?= htmlspecialchars($_POST['categorie'] ?? $livre->getCategorie() ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="image">Nouvelle image de couverture</label>
                        <input type="file" name="image" id="image" accept="image/*">
                        <small>Laisser vide pour conserver l'image actuelle. Formats : JPG, PNG, GIF, WEBP (Max 2 Mo)</small>
                        <?php if ($livre->getImage() !== 'default.jpg'): ?>
                            <div style="margin-top:8px;">
                                <img src="<?= htmlspecialchars($livre->getImageUrl()) ?>"
                                     alt="Couverture actuelle"
                                     style="height:80px;border-radius:6px;box-shadow:0 2px 6px rgba(0,0,0,0.15);">
                                <small style="display:block;margin-top:4px;">Image actuelle</small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" id="description"
                              rows="4"><?= htmlspecialchars($_POST['description'] ?? $livre->getDescription() ?? '') ?></textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                    <a href="catalogue.php" class="btn btn-warning">Annuler</a>
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