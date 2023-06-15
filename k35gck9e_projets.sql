-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 15 juin 2023 à 12:18
-- Version du serveur : 10.4.27-MariaDB
-- Version de PHP : 8.0.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `k35gck9e_projets`
--

-- --------------------------------------------------------

--
-- Structure de la table `kgb_agents`
--

CREATE TABLE `kgb_agents` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `date_naissance` date NOT NULL,
  `code_identification` varchar(50) NOT NULL,
  `nationalite` varchar(50) NOT NULL,
  `competences` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `kgb_agents`
--

INSERT INTO `kgb_agents` (`id`, `nom`, `prenom`, `date_naissance`, `code_identification`, `nationalite`, `competences`) VALUES
(1, 'De Matos', 'Ricardo', '1992-01-27', 'Tango', 'Portugal', 'Arts Martiaux 2'),
(3, 'Mclain', 'John', '1976-03-16', 'Whisky', 'États-Unis', 'Vitesse 3'),
(10, 'Thiago', 'Mendez', '1998-01-09', '1230', 'Portugal', 'Discrétion 1');

-- --------------------------------------------------------

--
-- Structure de la table `kgb_cibles`
--

CREATE TABLE `kgb_cibles` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `date_naissance` date NOT NULL,
  `pays` varchar(20) NOT NULL,
  `nom_de_code` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `kgb_cibles`
--

INSERT INTO `kgb_cibles` (`id`, `nom`, `prenom`, `date_naissance`, `pays`, `nom_de_code`) VALUES
(1, 'Dupont', 'Jeane', '2015-06-15', 'Espagne', 'Jeane'),
(2, 'Martine', 'Jonh', '2014-07-19', 'France', 'Jony');

-- --------------------------------------------------------

--
-- Structure de la table `kgb_contacts`
--

CREATE TABLE `kgb_contacts` (
  `id` int(11) UNSIGNED NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `date_naissance` date NOT NULL,
  `pays` varchar(50) NOT NULL,
  `adresse` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `kgb_contacts`
--

INSERT INTO `kgb_contacts` (`id`, `nom`, `prenom`, `date_naissance`, `pays`, `adresse`) VALUES
(1, 'Silva', 'Catarina', '1984-06-06', 'États-Unis', '5 rue Amerique 1001');

-- --------------------------------------------------------

--
-- Structure de la table `kgb_missions`
--

CREATE TABLE `kgb_missions` (
  `id` int(11) NOT NULL,
  `titre` varchar(11) NOT NULL,
  `description` varchar(15) NOT NULL,
  `nom_de_code` varchar(11) NOT NULL,
  `pays` varchar(11) NOT NULL,
  `statut` varchar(7) NOT NULL,
  `debut_mission` date NOT NULL,
  `fin_mission` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `kgb_missions`
--

INSERT INTO `kgb_missions` (`id`, `titre`, `description`, `nom_de_code`, `pays`, `statut`, `debut_mission`, `fin_mission`) VALUES
(1, 'Condition d', 'Surveillance', 'Jeane', 'Amerique', 'Prépara', '2015-03-15', '2024-03-14'),
(8, 'Mission Cap', 'Capture de Rich', 'jeanys', 'Amérique', 'Prépara', '2021-06-09', '2023-06-30');

-- --------------------------------------------------------

--
-- Structure de la table `kgb_planques`
--

CREATE TABLE `kgb_planques` (
  `id` int(11) NOT NULL,
  `nom_de_code` varchar(10) NOT NULL,
  `pays` varchar(10) NOT NULL,
  `adresse` varchar(20) NOT NULL,
  `type` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `kgb_planques`
--

INSERT INTO `kgb_planques` (`id`, `nom_de_code`, `pays`, `adresse`, `type`) VALUES
(1, 'Thiago', 'Amérique', 'Rue de Paris 1001', 'maison');

-- --------------------------------------------------------

--
-- Structure de la table `kgb_utilisateurs`
--

CREATE TABLE `kgb_utilisateurs` (
  `id` int(10) UNSIGNED NOT NULL,
  `nom` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mot_de_pass` varchar(255) NOT NULL,
  `date_inscription` datetime(6) NOT NULL,
  `date_derniere_connexion` datetime(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `kgb_utilisateurs`
--

INSERT INTO `kgb_utilisateurs` (`id`, `nom`, `email`, `mot_de_pass`, `date_inscription`, `date_derniere_connexion`) VALUES
(1, 'test', 'test@test.test', '$2y$10$mG3m9pyAK3lYTwE8Kn9mS.MzNS/nMJ1lKawpZhxKmWzpGjL.vZAby', '2023-05-15 00:04:13.000000', '2023-05-15 01:06:18.000000'),
(2, 'test2', 'test2@test.test', '$2y$10$JJLVylh41xCErBtAtLQJRuIShK88DADvgr2tdc4Re/7oVbOm68Zp2', '2023-05-15 01:06:16.000000', '2023-05-15 01:36:27.000000');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `kgb_agents`
--
ALTER TABLE `kgb_agents`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `kgb_cibles`
--
ALTER TABLE `kgb_cibles`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `kgb_contacts`
--
ALTER TABLE `kgb_contacts`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `kgb_missions`
--
ALTER TABLE `kgb_missions`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `kgb_planques`
--
ALTER TABLE `kgb_planques`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `kgb_utilisateurs`
--
ALTER TABLE `kgb_utilisateurs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `kgb_agents`
--
ALTER TABLE `kgb_agents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `kgb_cibles`
--
ALTER TABLE `kgb_cibles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `kgb_contacts`
--
ALTER TABLE `kgb_contacts`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `kgb_missions`
--
ALTER TABLE `kgb_missions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `kgb_planques`
--
ALTER TABLE `kgb_planques`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `kgb_utilisateurs`
--
ALTER TABLE `kgb_utilisateurs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
