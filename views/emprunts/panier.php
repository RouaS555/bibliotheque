<?php
/**
 * views/emprunts/panier.php – Mes emprunts
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once __DIR__ . '/../../config/db_connect.php';
require_once __DIR__ . '/../../models/Emprunt.php';
require_once __DIR__ . '/../../models/EmpruntManager.php';

$empruntManager = new EmpruntManager($db);

// Mise à jour automatique des retards
$empruntManager->updateRetards();

$emprunts = $empruntManager->getEmpruntsByUser((int)$_SESSION['user_id']);

// Récupération et nettoyage des messages flash
$success = $_SESSION['success'] ?? '';
$error   = $_SESSION['error']   ?? '';
unset($_SESSION['success'], $_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes emprunts – Bibliothèque</title>
    <link rel="stylesheet" href="../../assets/style/style.css">
</head>
<body>
<div class="library-container">

    <header class="library-header">
        <div class="logo"><h1>📚 Bibliothèque</h1></div>
        <nav class="library-nav">
            <a href="../livres/catalogue.php">Accueil</a>
            <a href="panier.php" class="active">📖 Mes emprunts</a>
            <?php if (($_SESSION['user_role'] ?? '') === 'admin'): ?>
                <a href="../livres/add_livre.php">➕ Ajouter un livre</a>
            <?php endif; ?>
            <span class="user-info">👤 <?= htmlspecialchars($_SESSION['user_nom']) ?></span>
            <a href="../../controllers/logoutController.php">Déconnexion</a>
        </nav>
    </header>

    <main>
        <div class="panier-section">
            <h2>📖 Mes emprunts en cours</h2>

            <?php if (!empty($success)): ?>
                <div class="alert success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            <?php if (!empty($error)): ?>
                <div class="alert error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php
            $empruntsActifs = array_filter($emprunts, fn($e) => $e->isEnCours() || $e->isEnRetard());
            $empruntsPasses = array_filter($emprunts, fn($e) => $e->isRendu());
            ?>

            <!-- ── EMPRUNTS ACTIFS ──────────────────────────────── -->
            <?php if (empty($empruntsActifs)): ?>
                <div class="alert info">📭 Vous n'avez aucun emprunt en cours.</div>
            <?php else: ?>
                <div class="emprunts-list">
                    <?php foreach ($empruntsActifs as $emprunt): ?>
                        <div class="emprunt-card <?= $emprunt->isEnRetard() ? 'overdue' : '' ?>">
                            <div class="emprunt-info">
                                <h3><?= htmlspecialchars($emprunt->getLivreTitre() ?? '—') ?></h3>
                                <p>✍️ <?= htmlspecialchars($emprunt->getLivreAuteur() ?? '—') ?></p>
                                <p>📅 Emprunté le :
                                    <?= date('d/m/Y', strtotime($emprunt->getDateEmprunt())) ?>
                                </p>
                                <p>📅 À retourner avant le :
                                    <?= date('d/m/Y', strtotime($emprunt->getDateRetourPrevue())) ?>
                                </p>
                                <?php if ($emprunt->isEnRetard()): ?>
                                    <p class="overdue-label">
                                        ⚠️ EN RETARD de <?= abs($emprunt->getJoursRestants()) ?> jour(s) !
                                    </p>
                                <?php else: ?>
                                    <p>⏰ Jours restants : <?= $emprunt->getJoursRestants() ?> jour(s)</p>
                                <?php endif; ?>
                            </div>
                            <div class="emprunt-actions">
                                <a href="../../controllers/empruntController.php?action=retourner&id=<?= $emprunt->getId() ?>"
                                   class="btn btn-success"
                                   onclick="return confirm('Confirmer le retour de ce livre ?')">
                                    ✅ Retourner
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- ── HISTORIQUE ──────────────────────────────────── -->
            <?php if (!empty($empruntsPasses)): ?>
                <h3 style="margin-top:40px;">📜 Historique des emprunts</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Auteur</th>
                            <th>Date emprunt</th>
                            <th>Date retour prévue</th>
                            <th>Date retour réelle</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($empruntsPasses as $emprunt): ?>
                            <tr>
                                <td><?= htmlspecialchars($emprunt->getLivreTitre() ?? '—') ?></td>
                                <td><?= htmlspecialchars($emprunt->getLivreAuteur() ?? '—') ?></td>
                                <td><?= date('d/m/Y', strtotime($emprunt->getDateEmprunt())) ?></td>
                                <td><?= date('d/m/Y', strtotime($emprunt->getDateRetourPrevue())) ?></td>
                                <td>
                                    <?= $emprunt->getDateRetourReelle()
                                        ? date('d/m/Y', strtotime($emprunt->getDateRetourReelle()))
                                        : '—' ?>
                                </td>
                                <td><span class="badge-rentre">Rendu</span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>

        </div>
    </main>

    <footer class="library-footer">
        <p>&copy; <?= date('Y') ?> – Bibliothèque Municipale | Tous droits réservés</p>
    </footer>
</div>
</body>
</html>