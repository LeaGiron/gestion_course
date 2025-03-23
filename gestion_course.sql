-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 23 mars 2025 à 16:00
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
-- Base de données : `gestion_course`
--

-- --------------------------------------------------------

--
-- Structure de la table `courses`
--

CREATE TABLE `courses` (
  `cour_id` int(11) NOT NULL,
  `cour_nom` varchar(50) NOT NULL,
  `cour_date` date NOT NULL,
  `cour_distance` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `courses`
--

INSERT INTO `courses` (`cour_id`, `cour_nom`, `cour_date`, `cour_distance`) VALUES
(1, 'Course de la Ville', '2025-06-10', 5),
(2, 'Course de la Ville', '2025-06-10', 10),
(3, 'Course de la Ville', '2025-06-10', 15);

-- --------------------------------------------------------

--
-- Structure de la table `inscriptions`
--

CREATE TABLE `inscriptions` (
  `inscr_id` int(11) NOT NULL,
  `cour_id` int(11) NOT NULL,
  `part_id` int(11) NOT NULL,
  `inscr_date` datetime NOT NULL,
  `inscr_statut` enum('en attente','confirmée','annulée') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `inscriptions`
--

INSERT INTO `inscriptions` (`inscr_id`, `cour_id`, `part_id`, `inscr_date`, `inscr_statut`) VALUES
(2, 2, 2, '2025-02-25 00:00:00', 'confirmée'),
(3, 3, 3, '2025-02-25 00:00:00', 'confirmée'),
(4, 1, 4, '2025-02-25 00:00:00', 'annulée'),
(5, 2, 5, '2025-02-25 00:00:00', 'confirmée'),
(17, 3, 52, '2025-03-10 19:35:27', 'en attente');

-- --------------------------------------------------------

--
-- Structure de la table `organisateurs`
--

CREATE TABLE `organisateurs` (
  `orga_id` int(11) NOT NULL,
  `orga_nom` varchar(50) NOT NULL,
  `orga_prenom` varchar(50) NOT NULL,
  `orga_email` varchar(100) NOT NULL,
  `orga_mot_de_passe` varchar(255) NOT NULL,
  `orga_telephone` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `organisateurs`
--

INSERT INTO `organisateurs` (`orga_id`, `orga_nom`, `orga_prenom`, `orga_email`, `orga_mot_de_passe`, `orga_telephone`) VALUES
(1, 'Durand', 'Paul', 'paul.durand@email.com', '$2y$10$ekDNmXDJmMC7MPELXjkFO.mebBulkfu.9/go9eqERtrhlCJ6eYFjy', '0623456789'),
(2, 'Lemoine', 'Julie', 'julie.lemoine@email.com', '$2y$10$ij52V6PcNXHR2X4McdNelOIKw02Vc8Hs/UI7N31p5Zl4M1jJj6VpW', '0676543210'),
(3, 'Petit', 'Nicolas', 'nicolas.petit@email.com', '$2y$10$44n.AGnR8Wd9xrq6pKG17uMVErbYZGffLsEOS9d8f9AdkCR/6R55W', '0611122233'),
(4, 'Garcia', 'Camille', 'camille.garcia@email.com', '$2y$10$dk.ClNfYr0UvGCC6qnk2lOQS2kMy3MV3hLk6i5qw1dorrS5cwygn.', '0633445566'),
(5, 'Roux', 'Antoine', 'antoine.roux@email.com', '$2y$10$2a.lyblG2she3y1tHHZT2ezXQ8dkOR7ZdmNXEHEtEMzFMOaxWU4Oa', '0655667788');

-- --------------------------------------------------------

--
-- Structure de la table `participants`
--

CREATE TABLE `participants` (
  `part_id` int(11) NOT NULL,
  `part_nom` varchar(50) NOT NULL,
  `part_prenom` varchar(50) NOT NULL,
  `part_date_de_naissance` date NOT NULL,
  `part_email` varchar(100) NOT NULL,
  `part_telephone` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `participants`
--

INSERT INTO `participants` (`part_id`, `part_nom`, `part_prenom`, `part_date_de_naissance`, `part_email`, `part_telephone`) VALUES
(2, 'Martin', 'Sophie', '1985-09-25', 'sophie.martin@email.com', '0698765432'),
(3, 'Bernard', 'Lucas', '1998-07-15', 'lucas.bernard@email.com', '0678456123'),
(4, 'Leroy', 'Emma', '2000-03-30', 'emma.leroy@email.com', '0654321897'),
(5, 'Morel', 'Thomas', '1995-11-22', 'thomas.morel@email.com', '0645678932'),
(52, 'Test', 'Adrien', '1999-05-02', 'adrien@test.com', '0654723879');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`cour_id`);

--
-- Index pour la table `inscriptions`
--
ALTER TABLE `inscriptions`
  ADD PRIMARY KEY (`inscr_id`),
  ADD KEY `cour_id` (`cour_id`),
  ADD KEY `part_id` (`part_id`);

--
-- Index pour la table `organisateurs`
--
ALTER TABLE `organisateurs`
  ADD PRIMARY KEY (`orga_id`),
  ADD UNIQUE KEY `orga_email` (`orga_email`);

--
-- Index pour la table `participants`
--
ALTER TABLE `participants`
  ADD PRIMARY KEY (`part_id`),
  ADD UNIQUE KEY `part_email` (`part_email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `courses`
--
ALTER TABLE `courses`
  MODIFY `cour_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `inscriptions`
--
ALTER TABLE `inscriptions`
  MODIFY `inscr_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT pour la table `organisateurs`
--
ALTER TABLE `organisateurs`
  MODIFY `orga_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `participants`
--
ALTER TABLE `participants`
  MODIFY `part_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `inscriptions`
--
ALTER TABLE `inscriptions`
  ADD CONSTRAINT `inscriptions_ibfk_1` FOREIGN KEY (`cour_id`) REFERENCES `courses` (`cour_id`),
  ADD CONSTRAINT `inscriptions_ibfk_2` FOREIGN KEY (`part_id`) REFERENCES `participants` (`part_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
