<?php
/**
 * views/livres/catalogue.php
 */
require_once __DIR__ . '/../../controllers/livreController.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bibliothèque – Catalogue</title>
    <link rel="stylesheet" href="../../assets/style/style.css">
</head>
<body>
<div class="library-container">

    <!-- ── HEADER ─────────────────────────────────────────────────── -->
    <header class="library-header">
        <div class="logo"><h1>Bibliothèque</h1></div>
        <nav class="library-nav">
            <a href="catalogue.php" class="active">Accueil</a>

            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="../emprunts/panier.php">Mes emprunts</a>
                <?php if ($_SESSION['user_role'] === 'admin'): ?>
                    <a href="add_livre.php">➕ Ajouter un livre</a>
                <?php endif; ?>
                <span class="user-info">👤 <?= htmlspecialchars($_SESSION['user_nom']) ?></span>
                <a href="../../controllers/logoutController.php">Déconnexion</a>
            <?php else: ?>
                <a href="../auth/login.php">Connexion</a>
                <a href="../auth/register.php">Inscription</a>
            <?php endif; ?>
        </nav>
    </header>

    <main>
        <!-- ── RECHERCHE ──────────────────────────────────────────── -->
        <div class="search-section">
            <h2>Rechercher un livre</h2>
            <form method="GET" action="catalogue.php" class="search-form">
                <input type="hidden" name="action" value="search">

                <div class="form-group">
                    <input type="text" name="search" placeholder="Titre du livre"
                           value="<?= htmlspecialchars($searchTitre) ?>">
                </div>

                <div class="form-group">
                    <input type="text" name="auteur" placeholder="Nom de l'auteur"
                           value="<?= htmlspecialchars($searchAuteur) ?>">
                </div>

                <div class="form-group">
                    <select name="categorie">
                        <option value="">Toutes les catégories</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= htmlspecialchars($cat) ?>"
                                <?= $searchCategorie === $cat ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Rechercher</button>
                <a href="catalogue.php" class="btn btn-secondary">Réinitialiser</a>
            </form>
        </div>

        <div class="catalogue">
            <h2>Notre catalogue
                <small style="font-size:.7em;font-weight:normal;color:#8d6e63;">
                    (<?= count($livres) ?> livre<?= count($livres) !== 1 ? 's' : '' ?>)
                </small>
            </h2>

            <?php if (!empty($successMsg)): ?>
                <div class="alert success"><?= htmlspecialchars($successMsg) ?></div>
            <?php endif; ?>

            <?php if (!empty($_SESSION['success'])): ?>
                <div class="alert success"><?= htmlspecialchars($_SESSION['success']) ?></div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (!empty($_SESSION['error'])): ?>
                <div class="alert error"><?= htmlspecialchars($_SESSION['error']) ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <?php if (empty($livres)): ?>
                <div class="alert info">Aucun livre trouvé pour cette recherche.</div>
            <?php else: ?>
                <div class="livres-grid">
                    <?php foreach ($livres as $livre): ?>
                        <div class="livre-card">
                            <div class="card-img-wrapper">
                                <img src="<?= htmlspecialchars($livre->getImageUrl()) ?>"
                                     alt="<?= htmlspecialchars($livre->getTitre()) ?>"
                                     onerror="this.src='../../public/uploads/livres/default.jpg'">
                            </div>
                            <div class="card-content">
                                <span class="badge-cat">
                                    <?= htmlspecialchars($livre->getCategorie() ?? 'Non catégorisé') ?>
                                </span>
                                <h3 class="card-title"><?= htmlspecialchars($livre->getTitre()) ?></h3>
                                <p class="card-author"><?= htmlspecialchars($livre->getAuteur()) ?></p>
                                <p class="card-year"><?= $livre->getAnneePublication() ?? 'Année inconnue' ?></p>
                                <p class="card-price"><?= $livre->getPrixFormate() ?></p>

                                <?php if ($livre->isDisponible()): ?>
                                    <p class="stock-disponible">
                                        ✓ Disponible (<?= $livre->getStock() ?>)
                                    </p>
                                    <?php if (isset($_SESSION['user_id'])): ?>
                                        <form action="../../controllers/empruntController.php" method="POST">
                                            <input type="hidden" name="livre_id" value="<?= $livre->getId() ?>">
                                            <button type="submit" name="action" value="emprunter"
                                                    class="btn btn-success">
                                                Emprunter
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <a href="../auth/login.php" class="btn btn-primary">
                                            Connectez-vous pour emprunter
                                        </a>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <p class="stock-indisponible">✗ Indisponible</p>
                                    <button class="btn btn-disabled" disabled>Indisponible</button>
                                <?php endif; ?>

                                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                                    <a href="edit_livre.php?code=<?= urlencode($livre->getCode()) ?>"
                                       class="btn btn-warning"
                                       style="margin-top:6px;">
                                        Modifier
                                    </a>
                                    <a href="../../controllers/deleteLivreController.php?code=<?= urlencode($livre->getCode()) ?>"
                                       class="btn btn-danger"
                                       style="margin-top:6px;"
                                       onclick="return confirm('Supprimer « <?= htmlspecialchars($livre->getTitre(), ENT_QUOTES) ?> » ?')">
                                        🗑 Supprimer
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <footer class="library-footer">
        <p>&copy; <?= date('Y') ?> – Bibliothèque Municipale | Tous droits réservés</p>
    </footer>
</div>
</body>
</html>