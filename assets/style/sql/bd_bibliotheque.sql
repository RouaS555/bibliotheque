-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 26 avr. 2026 à 20:54
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `bd_bibliotheque`
--

-- --------------------------------------------------------

--
-- Structure de la table `emprunts`
--

CREATE TABLE `emprunts` (
  `id` int(11) NOT NULL,
  `utilisateur_id` int(11) NOT NULL,
  `livre_id` int(11) NOT NULL,
  `date_emprunt` datetime NOT NULL,
  `date_retour_prevue` date NOT NULL,
  `date_retour_reelle` datetime DEFAULT NULL,
  `statut` enum('en_cours','en_retard','rendu') DEFAULT 'en_cours'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `emprunts`
--

INSERT INTO `emprunts` (`id`, `utilisateur_id`, `livre_id`, `date_emprunt`, `date_retour_prevue`, `date_retour_reelle`, `statut`) VALUES
(1, 2, 1, '2026-04-26 01:40:14', '2026-05-10', '2026-04-26 01:40:25', 'rendu'),
(2, 3, 1, '2026-04-26 01:41:30', '2026-05-10', NULL, 'en_cours'),
(3, 2, 1, '2026-04-26 10:23:17', '2026-05-10', '2026-04-26 10:23:23', 'rendu');

-- --------------------------------------------------------

--
-- Structure de la table `livres`
--

CREATE TABLE `livres` (
  `id` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `titre` varchar(150) NOT NULL,
  `auteur` varchar(100) NOT NULL,
  `edition` varchar(100) DEFAULT NULL,
  `annee_publication` int(4) DEFAULT NULL,
  `categorie` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `prix` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `image` varchar(255) DEFAULT 'default.jpg',
  `date_ajout` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `livres`
--

INSERT INTO `livres` (`id`, `code`, `titre`, `auteur`, `edition`, `annee_publication`, `categorie`, `description`, `prix`, `stock`, `image`, `date_ajout`) VALUES
(1, 'LIV0001', 'miserables', 'victor hugo', 'Penguin Classics Deluxe Edition', 1862, 'tragedy', 'Les Misérables (1862) by Victor Hugo is an epic, sweeping novel set in early 19th-century France (1815–1832) that critiques social injustice, poverty, and the legal system. It follows ex-convict Jean Valjean’s quest for redemption while being pursued by the relentless Inspector Javert, weaving together tales of love, sacrifice, and revolution in Paris', 70.00, 9, 'livre_69ed5ee4acac12.35827235.jpg', '2026-04-26 00:40:04'),
(6, 'LIV001', 'أرض زيكولا', 'عمرو عبد الحميد', NULL, NULL, 'fiction', 'رواية خيالية تأخذك إلى عالم موازٍ مليء بالأسرار – أول أجزاء سلسلة زيكولا', 22.50, 5, 'livre_69ee040fa82087.92016971.jpg', '2026-04-26 12:24:47'),
(7, 'LIV003', 'And Then There Were None', 'Agatha Christie', '18.50', 1939, 'mystère', 'Ten strangers are invited to an isolated island, and one by one they die. A masterpiece of suspense.', 18.50, 6, 'livre_69ee048a4e5391.90493567.jpg', '2026-04-26 12:26:50'),
(8, 'LIV004', 'مختارات من الشعر العربي', 'أحمد شوقي وآخرون', 'دار المعارف', 2018, 'Poésie', 'مجموعة من أروع القصائد العربية القديمة والحديثة', 30.00, 3, 'livre_69ee051ec106f2.80646774.jpg', '2026-04-26 12:29:18');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` enum('admin','membre') DEFAULT 'membre',
  `date_inscription` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `email`, `mot_de_passe`, `role`, `date_inscription`) VALUES
(1, 'Admin', 'admin@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '2026-04-25 19:15:42'),
(2, 'Roua Slimi', 'slimiroua2005@gmail.com', '$2y$10$16b05zcJ6/8bf8GbknN8e.4S0p0lPxoSbJbzE3cMUyy53lRsnPWOm', 'admin', '2026-04-25 23:29:33'),
(3, 'souha slimi', 'souhaslimi@gmail.com', '$2y$10$r/VeirpbPWnHGp625hSzj.GnAEo23FI/9qbfjL0E9zltR2rNFu1DK', 'membre', '2026-04-26 00:41:07'),
(4, 'Ikram elloumi', 'IkramElloumi@gmail.com', '$2y$10$dbd4WXZSmV.EH7H6zMSeUuL0QepDHHTHX2Ddf7ONVtrpXcizegV1G', 'membre', '2026-04-26 12:30:42');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `emprunts`
--
ALTER TABLE `emprunts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`),
  ADD KEY `livre_id` (`livre_id`);

--
-- Index pour la table `livres`
--
ALTER TABLE `livres`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `emprunts`
--
ALTER TABLE `emprunts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `livres`
--
ALTER TABLE `livres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `emprunts`
--
ALTER TABLE `emprunts`
  ADD CONSTRAINT `emprunts_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`),
  ADD CONSTRAINT `emprunts_ibfk_2` FOREIGN KEY (`livre_id`) REFERENCES `livres` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
